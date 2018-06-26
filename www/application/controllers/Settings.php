<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller
{
    private $selected_domain_idx = 0;
    private $selected_domain_info;
    private $user_sites;
    private $nakedDomain;

    public function __construct()
    {
        parent::__construct();

        if(empty($this->session->userdata('user_id')) ){
            redirect('/sign/in');
        }

        $this->selected_domain_idx =  $this->uri->segment(3);

        //모바일에선 3번째 도메인idx 인자가 없다
        if ($this->uri->segment(2) != 'mobile') {
            if(empty($this->selected_domain_idx)) {
                redirect('/mysites');
            } else {
                $this->selected_domain_info = get_selected_domain($this->selected_domain_idx);
            }
        }

        if($this->selected_domain_info['forced_bypass'] == 'ON') {
            redirect('/mysites');
        }

        //부하 줄이기 : 필요한 곳에서만 사용 (추가 페이지가 생기면 이곳에 배열 추가 할 것)
        $page_type_methods = array('overview','dns','ip_control','country','excluded_url','ddos','ssl_redirect','delete');
        if(in_array($this->uri->segment(2), $page_type_methods)) {
            $this->user_sites = get_domain_group_list();

            $this->assets['css'][] = 'assets/css/settings/common.css';
            $this->assets['end_script'][] = 'assets/js/settings/common.js';
        }

    }

    public function index()
    {
        redirect('/settings/overview');
    }

    public function mobile()
    {
        $this->layout = 'mobile_blank'; //framework7 사용

        $this->load->view('settings/mobile');
    }

    public function overview()
    {
        $this->assets['css'][] = array('assets/css/settings/overview.css', false);
        $this->assets['css'][] = array('assets/plugins/bootstrap-switch/bootstrap-switch.css', false);
        $this->assets['head_script'][] = array('assets/plugins/bootstrap-switch/bootstrap-switch.js', false);
        $this->assets['end_script'][] = 'assets/js/settings/overview.js';

        $domain_idx = $this->selected_domain_info['domain_idx'];

        $subquery = '(SELECT USER_DOMAIN_GROUP_IDX FROM USER_DOMAIN_GROUP_LINK WHERE USER_DOMAIN_IDX = %d)';
        $subquery = sprintf($subquery, $domain_idx);

        $query = $this->db
            ->select('NAKED_DOMAIN')
            ->from('USER_DOMAIN_GROUP')
            ->where('USER_DOMAIN_GROUP_IDX', $subquery, false)
            ->get_compiled_select();

        $ret = $this->db->query($query);
        if($ret->row() != NULL) {
            $this->nakedDomain = $ret->row()->NAKED_DOMAIN;
        }

        $this->load->view('settings/overview',
            array(
                "user_sites"  => $this->user_sites,
                "selected_domain_info"  => $this->selected_domain_info,
                "webServerIp"   => $this->selected_domain_info['ip'],
                "webServerIpMulti"   => $this->selected_domain_info['ip_multi'],
                "nakedDomain" => $this->nakedDomain
            )
        );
    }

    public function dns()
    {
        // HTTP method 기준으로 로직 처리
        // TODO: 아래 GET, POST, DELETE에서 DNS DB를 다루는 부분들은
        //       API2로 이동하는게 좋을 듯 싶다.
        $http_method = $this->input->method(TRUE);
        if ($http_method == 'POST') {
            $this->hooks->enabled = false;
            $this->processDnsPost($this->selected_domain_info['domain_idx']);
            return;
        }

        if ($http_method == 'DELETE') {
            $this->hooks->enabled = false;
            $this->processDnsDelete();
            return;
        }

        // GET: dns page
        // DNS 설정 화면은 route53 네임서버가 발급되어 있는 모든 경우에 이용가능
        // 즉, 네이키드 도메인이면서 펜딩상태와 NS변경 확인된 도메인들
        if(count($this->selected_domain_info['cb_nameserver']) < 1) {
            redirect('/mysites');
        }

        $this->assets['css'][] = 'assets/css/settings/dns.css';
        $this->assets['end_script'][] = 'assets/js/settings/dns.js';

        $results = $this->getDnsRecords($this->selected_domain_info['domain_idx']);

        $status_map = [
            'FORWARDING' => 'Pending',
            'COMPLETE' => 'Protected',
            'BYPASS' => 'Bypass',
            'DISCONNECT' => 'Disconnected'
            // => 'Deleting'
        ];

        $dns_records = [];
        $root_domain_status = $this->selected_domain_info['status'];

        foreach ($results as $result) {

            // Y(Inserted) or N(Insert waiting) or D(Delete waiting)
            if ($result['ACTIVE'] == 'D') {
                // 삭제 대상을 의미하는 'D'는 front-end로 전달하지 않는다.
                continue;
            }
            $record['record_idx'] = $result['ZONE_IDX'];
            $record['type'] = $result['TYPE'];
            $record['class'] = $result['CLASS'];
            $record['host'] = $result['HOST'];
            $record['target'] = $result['TARGET'];
            $record['pri'] = $result['PRI'];
            $record['weight'] = $result['WEIGHT'];
            $record['port'] = $result['PORT'];
            $record['active'] = $result['ACTIVE'];
            $record['using_type'] = $result['USING_TYPE'];
            $record['domain_idx'] = $result['USER_DOMAIN_IDX'];

            $record['status'] = null;
            if ($result['AFC_STATUS'] != null) {
                $record['status'] = $status_map[$result['AFC_STATUS']];
            }

            // 예외처리: root domain을 등록 되었을 때, www subdomain의 status는 어떻게 할 것인가?
            //
            // www subdomain 레코드는 using_type은 Y이지만(보호대상) domain_idx와 status가 null인 상태가 된다.
            // status가 null인 녀석들은 root 도메인과 동일한 값을 갖도록 설정한다.
            //
            if ($record['using_type'] == 'Y' && $record['status'] == null) {
                $record['status'] = $root_domain_status;
                $record['domain_idx'] = $this->selected_domain_info['domain_idx'];
            }

            $dns_records[] = $record;
        }

        $this->load->view('settings/dns',
            array(
                "user_sites"  => $this->user_sites,
                "selected_domain_info" => $this->selected_domain_info,
                "dns_records" => $dns_records
            )
        );
    }

    public function ip_control($domain_idx)
    {
        $this->assets['css'][] = 'assets/css/settings/ip_control.css';
        $this->assets['head_script'][] = array('assets/plugins/jquery/jquery-ui.min.js', false);
        $this->assets['head_script'][] = array('assets/plugins/tagit/tag-it.min.js', false);
        $this->assets['end_script'][] = 'assets/js/settings/ip_control.js';

        $response = request_api_server(
            'GET',
            "/settings/ip_control/" . $domain_idx,
            null,
            30
        );

        $response = json_decode($response);
        $resultInfo = $response->result_info;

        $this->load->view('settings/ip_control',
            array(
                'user_sites'  => $this->user_sites,
                'selected_domain_info' => $this->selected_domain_info,
                'whiteList' => $resultInfo->white_list,
                'blackList' => $resultInfo->black_list
            )
        );
    }

    public function country($domain_idx)
    {
        $this->load->helper('country');

        $this->assets['css'][] = 'assets/css/settings/country.css';
        $this->assets['css'][] = array('assets/plugins/select2/select2.css', false);
        $this->assets['css'][] = array('assets/plugins/bootstrap-switch/bootstrap-switch.css', false);
        $this->assets['head_script'][] = array('assets/plugins/jquery/jquery-ui.min.js', false);
        $this->assets['head_script'][] = array('assets/plugins/tagit/tag-it.min.js', false);
        $this->assets['head_script'][] = array('assets/plugins/select2/select2.js', false);
        $this->assets['head_script'][] = array('assets/plugins/bootstrap-switch/bootstrap-switch.js', false);
        $this->assets['end_script'][] = 'assets/js/settings/country.js';

        $this->load->view('settings/country',
            array(
                "user_sites"  => $this->user_sites,
                "selected_domain_info" => $this->selected_domain_info
            )
        );
    }

    public function excluded_url($domain_idx)
    {
        $this->assets['css'][] = 'assets/css/settings/excluded_url.css';
        $this->assets['head_script'][] = array('assets/plugins/jquery/jquery-ui.min.js', false);
        $this->assets['head_script'][] = array('assets/plugins/tagit/tag-it.min.js', false);
        $this->assets['end_script'][] = 'assets/js/settings/extra_url.js';

        $response = request_api_server(
            'GET',
            "/settings/excluded_url/" . $domain_idx,
            null,
            30
        );

        $response = json_decode($response);
        $extraUrlList = $response->result_info;

        $this->load->view('settings/excluded_url',
            array(
                "user_sites"  => $this->user_sites,
                "selected_domain_info" => $this->selected_domain_info,
                "extraUrlList" => $extraUrlList->url
            )
        );
    }

    public function ddos()
    {
        $this->assets['css'][] = 'assets/css/settings/ddos.css';
        $this->assets['end_script'][] = 'assets/js/settings/ddos.js';

        $this->load->view('settings/ddos',
            array(
                "user_sites"  => $this->user_sites,
                "selected_domain_info" => $this->selected_domain_info
            )
        );
    }

    public function ssl_redirect()
    {
        $this->assets['css'][] = array('assets/css/settings/ssl.css', false);
        $this->assets['css'][] = array('assets/plugins/bootstrap-switch/bootstrap-switch.css', false);
        $this->assets['head_script'][] = array('assets/plugins/bootstrap-switch/bootstrap-switch.js', false);
        $this->assets['end_script'][] = 'assets/js/settings/ssl.js';

        $this->load->view('settings/ssl',
            array(
                "user_sites"  => $this->user_sites,
                "selected_domain_info" => $this->selected_domain_info
            )
        );
    }

    public function delete()
    {
        $this->assets['css'][] = 'assets/css/settings/delete.css';
        $this->assets['end_script'][] = 'assets/js/settings/delete.js';

        $this->load->view('settings/delete',
            array(
                "user_sites"  => $this->user_sites,
                "selected_domain_info" => $this->selected_domain_info
            )
        );
    }

    public function changeIP($domain_idx) {
        $getData = file_get_contents('php://input');
        $getDataArray = json_decode($getData, true);
        $ipLength = count($getDataArray['ips']);

        if($ipLength > 1) {
            $api_url = 'ip_multi';
            $requestText = '{"ip_multi" : ["'. implode("\",\"", $getDataArray['ips']).'"]}';

            $response1 = request_api_server(
                "PUT",
                "/settings/ip/" . $domain_idx,
                '{"ip" : "'.$getDataArray['ips'][0].'"}',
                30
            );

        } else {
            $api_url = 'ip';
            $requestText = '{"ip" : "'.$getDataArray['ips'][0].'"}';

            $response2 = request_api_server(
                "PUT",
                "/settings/ip_multi/" . $domain_idx,
                '{"ip_multi" : }',
                30
            );
        }

        $response = request_api_server(
            "PUT",
            "/settings/" . $api_url . "/" . $domain_idx,
            $requestText,
            30
        );

        echo $response;
        exit;
    }

    public function changeCNAME($domain_idx) {
        $getData = file_get_contents('php://input');

        $response = request_api_server(
            "PUT",
            "/settings/ip_cname/" . $domain_idx,
            $getData,
            30
        );

        echo $response;
        exit;
    }

    public function changeBypassMode($domain_idx) {
        $getData = file_get_contents('php://input');

        $response = request_api_server(
            "PUT",
            "/settings/bypass_mode/" . $domain_idx,
            $getData,
            30
        );

        echo $response;
        exit;
    }

    public function changeWebSeal($domain_idx) {
        $getData = file_get_contents('php://input');

        $response = request_api_server(
            "PUT",
            "/settings/web_seal/" . $domain_idx,
            $getData,
            30
        );

        echo $response;
        exit;
    }

    public function updateIpControl($domain_idx) {
        $getData = file_get_contents('php://input');
        $getMethod = $_SERVER["REQUEST_METHOD"];

        $response = request_api_server(
            $getMethod,
            "/settings/ip_control/" . $domain_idx,
            $getData,
            30
        );

        echo $response;
        exit;
    }

    public function selectCountries($domain_idx, $type) {
        $this->load->helper('country');

        if($type == 'white') {
            $requestUrl = 'country_white';
        } else if($type == 'block') {
            $requestUrl = 'country_block';
        }

        $response = request_api_server(
            'GET',
            "/settings/". $requestUrl . "/" . $domain_idx,
            null,
            30
        );

        $response = json_decode($response);
        $resultInfo = $response->result_info;
        $country_code = $resultInfo->country_code;

        foreach($country_code as $key => $code) {
            $resultInfo->country_code[$key] = language_country_to_code($code);
        }

        echo json_encode($response);
        exit;
    }

    public function updateBlockCountry($domain_idx) {
        $this->load->helper('country');

        $getData = file_get_contents('php://input');
        $getMethod = $_SERVER["REQUEST_METHOD"];

        if($getMethod == 'DELETE') {
            $getData = json_decode($getData);
            $getCountryCode = language_code_to_country($getData->country_code[0]);

            $getData->country_code[0] = $getCountryCode;

            $getData = json_encode($getData);
        }

        $response = request_api_server(
            $getMethod,
            "/settings/country_block/" . $domain_idx,
            $getData,
            30
        );

        echo $response;
        exit;
    }

    public function updateWhiteCountry($domain_idx) {
        $this->load->helper('country');

        $getData = file_get_contents('php://input');
        $getMethod = $_SERVER["REQUEST_METHOD"];

        if($getMethod == 'DELETE') {
            $getData = json_decode($getData);
            $getCountryCode = language_code_to_country($getData->country_code[0]);

            $getData->country_code[0] = $getCountryCode;

            $getData = json_encode($getData);
        }

        $response = request_api_server(
            $getMethod,
            "/settings/country_white/" . $domain_idx,
            $getData,
            30
        );

        echo $response;
        exit;
    }

    public function updateExtraUrl($domain_idx) {
        $getData = file_get_contents('php://input');
        $getMethod = $_SERVER["REQUEST_METHOD"];

        $response = request_api_server(
            $getMethod,
            "/settings/excluded_url/" . $domain_idx,
            $getData,
            30
        );

        echo $response;
        exit;
    }

    public function changeDDosLimit($domain_idx)
    {
        $getData = file_get_contents('php://input');

        $response = request_api_server(
            "PUT",
            "/settings/ddos_limit/" . $domain_idx,
            $getData,
            30
        );

        echo $response;
        exit;
    }

    public function changeSSLMode($domain_idx)
    {
        $getData = file_get_contents('php://input');

        $response = request_api_server(
            "PUT",
            "/settings/ssl_mode/" . $domain_idx,
            $getData,
            30
        );

        echo $response;
        exit;
    }

    public function toggleRedirectHttps($domain_idx)
    {
        $getData = file_get_contents('php://input');

        $response = request_api_server(
            "PUT",
            "/settings/redirect_https/" . $domain_idx,
            $getData,
            30
        );

        echo $response;
        exit;
    }

    public function toggleRedirectWww($domain_idx)
    {
        $getData = file_get_contents('php://input');

        $response = request_api_server(
            "PUT",
            "/settings/redirect_www/" . $domain_idx,
            $getData,
            30
        );

        echo $response;
        exit;
    }

    public function deleteDomain($domain_idx)
    {
        $response = request_api_server(
            "DELETE",
            "/websites/".$domain_idx,
            null,
            120
        );

        $decode = json_decode($response, true);
        //삭제 액션은 성공하고 삭제일이 내려오지 않으면 즉시 삭제 아니고 삭제 신청 된 것
        if($decode['result'] == true && empty($decode["result_info"]["delete_date"])) {

            //사용자에게 확인 이메일 보내기
            $domain = $this->db
                ->select('WEBSERVER_DOMAIN')
                ->where('USER_DOMAIN_IDX', $domain_idx)
                ->get('USER_DOMAIN')
                ->row();

            $param = array(
                "USER_DOMAIN_IDX" => $domain_idx,
                "USER_NAME" => $this->session->userdata('user_name'),
                "USER_EMAIL" => $this->session->userdata('user_id'),
                "WEBSERVER_DOMAIN" => $domain->WEBSERVER_DOMAIN,
                "DELETE_DATE" => date('Y-m-d', strtotime('+3days')),
                "RESIDUAL_HOUR" => 72);
            $param = json_encode($param);

            request_cc_server(
                '/wFunction/mail/send_cancel_alarm_mail',
                '20006',
                $param,
                30
            );

            // 메일 발송 로그 등록
            $query = "
                INSERT IGNORE INTO
                    mlog.USER_DOMAIN_DELETE_SENDMAIL_LOG
                SET
                    USER_DOMAIN_IDX = '".$domain_idx."',
                    SENDING1 = 'Y',
                    SENDING1_DATE = now()
            ";
            $this->db->query($query);

        }

        echo $response;
        exit;
    }

    public function toggleCountryAccess($domain_idx)
    {
        $getData = file_get_contents('php://input');

        $response = request_api_server(
            "PUT",
            "/settings/country_access/" . $domain_idx,
            $getData,
            30
        );

        echo $response;
        exit;
    }

    public function changeCountryAccessType($domain_idx)
    {
        $getData = file_get_contents('php://input');

        $response = request_api_server(
            "PUT",
            "/settings/country_access_type/" . $domain_idx,
            $getData,
            30
        );

        echo $response;
        exit;
    }

    /**
     * 고객 도메인을 위한 NS(second level domain) zone의 index를 가져온다.
     *
     * @param $domain_idx
     * @return null
     */
    private function getNameserverZoneIdx($domain_idx)
    {
        // NS_NAMESERVER는 고객이 NS서버 변경 방식을 선택 했을 때 필요한 정보를 담고 있다.
        $query = $this->db
            ->select('NAMESERVER_IDX')
            ->from('NS_NAMESERVER')
            ->where('USER_DOMAIN_IDX', $domain_idx)
            ->get_compiled_select();

        $ret = $this->db->query($query);
        if ($this->db->affected_rows() == 0) {
            return null;
        }
        
        return $ret->row()->NAMESERVER_IDX;
    }

    private function getDnsRecordsSimple($zone_idx): array
    {
        $query = $this->db
            ->select('*')
            ->from('NS_ZONE')
            ->where('ZONE_IDX', $zone_idx)
            ->get_compiled_select();

        $query_result = $this->db->query($query);
        return $query_result->result_array();
    }

    private function getDnsRecords($domain_idx): array
    {

        $nameserver_idx = $this->getNameserverZoneIdx($domain_idx);
        if (empty($nameserver_idx)) {
            return [];
        }
        $sub_query1 = "
        (SELECT USER_DOMAIN_STATUS.AFC_STATUS
		  FROM USER_DOMAIN_STATUS
		  JOIN USER_DOMAIN ON USER_DOMAIN_STATUS.USER_DOMAIN_IDX = USER_DOMAIN.USER_DOMAIN_IDX
		  WHERE USER_DOMAIN.WEBSERVER_DOMAIN = NS_ZONE.HOST) AS AFC_STATUS";
        $sub_query2 = "
        (SELECT USER_DOMAIN_STATUS.USER_DOMAIN_IDX
		  FROM USER_DOMAIN_STATUS
		  JOIN USER_DOMAIN ON USER_DOMAIN_STATUS.USER_DOMAIN_IDX = USER_DOMAIN.USER_DOMAIN_IDX
		  WHERE USER_DOMAIN.WEBSERVER_DOMAIN = NS_ZONE.HOST) AS USER_DOMAIN_IDX";

        $query = $this->db
            ->select('*')
            ->select($sub_query1)
            ->select($sub_query2)
            ->from('NS_ZONE')
            ->where('NAMESERVER_IDX', $nameserver_idx)
            ->get_compiled_select();

        $query_result = $this->db->query($query);

        return $query_result->result_array();
    }

    private function getDnsRecord($nameserver_idx, $type, $host, $target = null)
    {
        if ($target == null) {
            $query = $this->db
                ->select('*')
                ->from('NS_ZONE')
                ->where('NAMESERVER_IDX', $nameserver_idx)
                ->where('TYPE', $type)
                ->where('HOST', $host)
                ->where('ACTIVE !=', 'D')
                ->get_compiled_select();
        } else {
            $query = $this->db
                ->select('*')
                ->from('NS_ZONE')
                ->where('NAMESERVER_IDX', $nameserver_idx)
                ->where('TYPE', $type)
                ->where('HOST', $host)
                ->where('TARGET', $target)
                ->where('ACTIVE !=', 'D')
                ->get_compiled_select();
        }

        $query_result = $this->db->query($query);
        return $query_result->row();
    }

    private function delDnsRecordDBTable($zone_idx): bool
    {
        $where = ['ZONE_IDX' => $zone_idx];
        $this->db->delete('NS_ZONE', $where);
        if ($this->db->affected_rows() == 0) {
            return false;
        }

        return true;
    }

    /**
     * @param $nameserver_idx
     * @param $record
     * @return int
     */
    private function insertDnsRecordDBTable($nameserver_idx, $record)
    {
        // default value
        $pri = '';
        $weight = '';
        $port = '';
        $using_type = 'N';

        if ($record['type'] == 'SRV') {
            $pri = $record['pri'];
            $weight = $record['weight'];
            $port = $record['port'];

        } else if ($record['type'] == 'MX') {
            $pri = $record['pri'];
        }

        $query = sprintf("
            INSERT INTO
				NS_ZONE
           	SET
                 NAMESERVER_IDX = '%s',
                 HOST = '%s',
                 TTL = '%s',
                 CLASS = '%s',
                 TYPE = '%s',
                 TARGET = '%s',
                 PRI = '%s',
                 WEIGHT = '%s',
                 PORT = '%s',
                 ACTIVE = '%s',
                 USING_TYPE = '%s',
                 REGISTER_DATE = '%s'
            ON DUPLICATE KEY
            UPDATE
                TTL     = VALUES(TTL),
                CLASS   = VALUES(CLASS),
                PRI     = VALUES(PRI),
                TARGET  = VALUES(TARGET),
                WEIGHT  = VALUES(WEIGHT),
                PORT    = VALUES(PORT),
                ACTIVE  = VALUES(ACTIVE),
                USING_TYPE  = VALUES(USING_TYPE)
        ",
            $nameserver_idx, $record['host'], '3600', $record['class'], $record['type'],
            $record['target'], $pri, $weight, $port, $record['active'], $using_type,
            date('Y-m-d H:i:s')
        );

        $this->db->query($query);
        if ($this->db->affected_rows() == 0) {
            return -1;
        }

        $where = [
            'NAMESERVER_IDX' => $nameserver_idx,
            'HOST' => $record['host'],
            'TTL' => 3600,
            'CLASS' => 'IN',
            'TYPE' => $record['type'],
            'TARGET' => $record['target'],
            'PRI' => $pri,
            'WEIGHT' => $weight,
            'port' => $port
        ];

        $query = $this->db
            ->select('*')
            ->from('NS_ZONE')
            ->where($where)
            ->get_compiled_select();

        $query_result = $this->db->query($query);
        $row = $query_result->row();
        return $row->ZONE_IDX;
    }

    private function updateDnsRecordDBTable($record_idx, $active)
    {
        $this->db->set('ACTIVE', $active);
        $this->db->where('ZONE_IDX', $record_idx);
        $this->db->update('NS_ZONE');

        if ($this->db->affected_rows() == 0) {
            return false;
        }

        return true;
    }

    private function processDnsPost($domain_idx): bool
    {
        $req_body = json_decode($this->input->raw_input_stream,true);
        if ($req_body == null) {
            $response['result'] = false;

            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            return false;
        }

        try{
            $record_idx = $this->addDnsRecord($domain_idx, $req_body);
            if ($record_idx == -1) {
                return false;
            }

            $response['result'] = true;
            $response['result_info'] = [];
            $response['result_info']['record_idx'] = $record_idx;

            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        } catch (Exception $e) {

            $response['result'] = false;
            $response['messages'] = $e->getMessage();

            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT));
            return false;
        }

        return true;
    }

    private function validateAddDnsRecord(&$req_body)
    {
        //기본적인 폼검증 시작
        $valid_config = array(
            // not supported type: AAAA, SPF, NAPTR, CAA, NS
            array('field' => 'type', 'label' => '[type]', 'rules' => 'required|trim|in_list[A,CNAME,MX,TXT,PTR,SRV]'),

            array('field' => 'host', 'label' => '[host]', 'rules' => 'required|trim'),
            array('field' => 'target', 'label' => '[target]', 'rules' => 'required|trim'),
            array('field' => 'class', 'label' => '[class]', 'rules' => 'required|trim'),
            array('field' => 'pri', 'label' => '[pri]', 'rules' => 'trim'),
            array('field' => 'weight', 'label' => '[weight]', 'rules' => 'trim'),
            array('field' => 'port', 'label' => '[port]', 'rules' => 'trim'),
        );

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        $this->form_validation->set_data($req_body);
        $this->form_validation->set_rules($valid_config);
        if ($this->form_validation->run() == false) {
            clog_message('error', 'Failed to validate dns record: %s', [trim(strip_tags(validation_errors()))]);
            return false;
        }

        $req_body['host'] = $this->form_validation->set_value('host');
        $req_body['class'] = $this->form_validation->set_value('class');
        $req_body['target'] = $this->form_validation->set_value('target');
        $req_body['type'] = $this->form_validation->set_value('type');
        $req_body['pri'] = $this->form_validation->set_value('pri');
        $req_body['weight'] = $this->form_validation->set_value('weight');
        $req_body['port'] = $this->form_validation->set_value('port');

        // host의 값은 Second level domain까지 같아야 한다.
        // ex: cloudbric.com
        //     aa.cloudbric.com(o), aa.cloudbric.co.kr(x), aa2.test.com(x)

        $domain_name = $this->selected_domain_info['domain'];

        $host_words = explode('.', $req_body['host']);
        $domain_words = explode('.', $domain_name);

        // host의 값은 최소한 Second level domain까지 있어야 한다.
        if (count($host_words) < 2){
            return false;
        }

        if ($host_words[count($host_words)-1] !== $domain_words[count($domain_words)-1] ||
            $host_words[count($host_words)-2] !== $domain_words[count($domain_words)-2]) {
            return false;
        }

        // record type별 체크
        //
        //  - A  192.0.2.235
        //  - CNAME  www.example.com
        //  - MX   [priority] [mail server host name]
        //  - TXT "txt"
        //  - PTR  www.example.com
        //  - SRV [priority] [weight] [port] [server host name]
        if ($req_body['type'] == 'A') {
            if (filter_var($req_body['target'], FILTER_VALIDATE_IP) == false) {
                return false;
            }
        }

        if ($req_body['type'] == 'MX') {
            if(filter_var($req_body['pri'], FILTER_VALIDATE_INT) === false){
                return false;
            }
        }

        if ($req_body['type'] == 'SRV') {
            if(filter_var($req_body['pri'], FILTER_VALIDATE_INT) === false){
                return false;
            }
            if(filter_var($req_body['weight'], FILTER_VALIDATE_INT) === false){
                return false;
            }
            if(filter_var($req_body['port'], FILTER_VALIDATE_INT) === false){
                return false;
            }
        }

        return true;
    }

    private function excludeAddDnsRecord($nameserver_idx, $record)
    {
        // Cloudbric 서비스를 위해 미리 넣어둔 record와 관련된 A record, CNAME record는 추가되지 않도록 조치해야 한다.
        // 특히 A record는 동일한 name으로 여러개의 값이 들어갈 수 있어 예외처리가 반드시 필요하다.
        //
        if (($record['host'] === $this->selected_domain_info['domain']) &&
            ($record['type'] === 'A')) {
            return false;
        }

        if (($record['host'] === 'www' . $this->selected_domain_info['domain']) &&
            ($record['type'] === 'CNAME')) {
            return false;
        }

        // AWS Route53을 위한 필요 제한조건
        //  1) 모든 레코드 타입에 대해서 동일한 Type과 Name이 등록되어 있는지 확인한다.
        //
        if ($record['type'] == 'A') {
            // A record는 여러개의 값으로 등록이 가능하다. 값까지 비교해보자.
            //
            $registered_record = $this->getDnsRecord($nameserver_idx, $record['type'], $record['host'], $record['target']);
            if ($registered_record != null) {
                return false;
            }

        } else {

            $registered_record = $this->getDnsRecord($nameserver_idx, $record['type'], $record['host']);
            if ($registered_record != null) {
                return false;
            }
        }

        //  2) 동일한 Name의 A레코드 타입이 1개이상 이미 있다면 Cname 등록 못함
        //     동일한 Name의 Cname레코드 타입이 있다면 A레코드/Cname타입 등록 못함
        //
        if ($record['type'] == 'A' || $record['type'] == 'CNAME') {

            $check_type = 'A';
            if ($record['type'] == 'A') {
                $check_type = 'CNAME';
            }
            $registered_record = $this->getDnsRecord($nameserver_idx, $check_type, $record['host']);
            if ($registered_record != null) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $domain_idx
     * @param $req_body
     * @return int
     * @throws Exception
     */
    private function addDnsRecord($domain_idx, $req_body): int
    {
        if ($this->validateAddDnsRecord($req_body) == false) {
            $response['result'] = false;

            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            return -1;
        }

        $nameserver_idx = $this->getNameserverZoneIdx($domain_idx);
        if ($nameserver_idx == null || empty($req_body)) {
            $response['result'] = false;

            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            return -1;
        }

        if ($this->excludeAddDnsRecord($nameserver_idx, $req_body) == false) {
            $response['result'] = false;

            $this->output
                ->set_status_header(409) // Conflict
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            return -1;
        }

        $req_body['active'] = 'N';
        $zone_idx = $this->insertDnsRecordDBTable($nameserver_idx, $req_body);
        return $zone_idx;
    }

    private function processDnsDelete(): bool
    {
        $req_body = json_decode($this->input->raw_input_stream,true);

        if ($req_body == null) {
            $response['result'] = false;

            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            return false;
        }

        try{
            $ret = $this->delDnsRecord($req_body);
            if ($ret == false) {
                return false;
            }

            $response['result'] = true;

            $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        } catch (Exception $e) {

            $response['result'] = false;
            $response['messages'] = $e->getMessage();

            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT));
            return false;
        }

        return true;
    }

    private function validateDelDnsRecord(&$req_body)
    {
        //기본적인 폼검증 시작
        $valid_config = array(
            array('field' => 'record_idx', 'label' => '[type]', 'rules' => 'required|trim')
        );

        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        $this->form_validation->set_data($req_body);
        $this->form_validation->set_rules($valid_config);
        if ($this->form_validation->run() == false) {
            return false;
        }

        $record_idx = $this->form_validation->set_value('record_idx');
        $req_body['record_idx'] = intval($record_idx);

        if ($req_body['record_idx'] <= 0) {
            return false;
        }

        return true;
    }

    private function excludeDelDnsRecord($record)
    {
        // Cloudbric 서비스를 위해 미리 넣어둔 record는 삭제되지 말아야 한다.
        // Frontend에서 이런 요청을 하지는 않겠지만 예외처리를 위해 넣어둔다.
        //
        if (($record['HOST'] === $this->selected_domain_info['domain']) &&
            ($record['TYPE'] === 'A')) {
           return false;
        }

        if (($record['HOST'] === 'www' . $this->selected_domain_info['domain']) &&
            ($record['TYPE'] === 'CNAME')) {
            return false;
        }

        return true;
    }

    private function delDnsRecord($req_body): bool
    {
        if ($this->validateDelDnsRecord($req_body) == false) {
            $response['result'] = false;

            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            return false;
        }

        $record_idx = $req_body['record_idx'];

        // validation record index
        $record_info = $this->getDnsRecordsSimple($record_idx);

        if ($this->excludeDelDnsRecord($record_info[0]) == false) {
            $response['result'] = false;

            $this->output
                ->set_status_header(403) // Forbidden
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            return false;
        }

        if ($record_info[0]['ACTIVE'] == 'N') {
            // 아직 반영 전이므로 즉시 삭제하자
            //
            if ($this->delDnsRecordDBTable($req_body['record_idx']) == false) {
                $response['result'] = false;

                $this->output
                    ->set_status_header(500)
                    ->set_content_type('application/json', 'utf-8')
                    ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                return false;
            }
            return true;
        }

        $active = 'D';
        if ($this->updateDnsRecordDBTable($record_idx, $active) == false) {
            $response['result'] = false;

            $this->output
                ->set_status_header(500)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            return false;
        }

        return true;
    }
}

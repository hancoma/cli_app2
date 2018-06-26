<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Addsite extends CI_Controller {

    private $selected_domain_idx = 0;

    public function __construct()
    {
        parent::__construct();

        if(empty($this->session->userdata('user_id')) ){
            redirect('/sign/in');
        }

        $this->assets['css'][] = 'assets/css/addsite/common.css';
        $this->assets['end_script'][] = 'assets/js/addsite/common.js';
    }

    public function index($domain = '')
    {
        //파트너 콘솔 사이트 등록 불가일 때 접근 금지, 파트너 콘솔이라도 관리자 강제로그인은 가능
        if(PARTNER_CONSOLE == 'yes' && $this->session->userdata('manager') != true) {
            $partner_info = $this->_partner_info();

            if($partner_info->ADD_WEBSITES == 'no') {
                redirect('/mysites');
            }
        }

        $this->assets['css'][] = 'assets/css/addsite/modal.css';
        $this->assets['end_script'][] = 'assets/js/addsite/index.js';

        $this->session->unset_userdata('addsite');
        $this->session->unset_userdata('dns');

        $this->load->view('addsite/index', array('add_domain' => $domain));
    }

    private function _partner_info()
    {
        $this->db->where('PARTNER_IDX', $this->session->userdata('partner_idx'));
        $row = $this->db->get('morpiers.PARTNERS')->row();

        return $row;
    }

    /**
     * 등록 가능한 도메인인지 종합검사 (TLD 형식 검사, 이미 등록 여부, 등록금지 사이트 여부) api 호출
     * @return mixed
     */
    public function api_domain_check(){

        $requestUrl = $this->session->userdata()['addsite']['user_domain'];

        $response = request_api_server(
            'GET',
            '/websites/check_domain/' . $requestUrl,
            null,
            30
        );
        clog_message("debug", $response);

        return $response;
    }

    /**
     * 도메인 유효성 검사
     */
    public function domainCheck()
    {
        $user_domain = $this->input->post('user_domain');

        $user_domain = trim(strtolower($user_domain));
        if ($user_domain_tmp = parse_url($user_domain)) {
            if (isset($user_domain_tmp['host'])) {
                $user_domain = $user_domain_tmp['host'];
            } else if (isset($user_domain_tmp['path'])) {
                $user_domain = str_replace("/", "", $user_domain_tmp['path']);
            }
        }

        //IDN 퓨니코드 변환처리
        $this->load->library('Punycode');
        $punycode = new Punycode;
        $user_domain = $punycode->EncodePunycodeIDN($user_domain);

        $this->session->set_userdata(array('addsite' => array('user_domain'=>$user_domain)));

        $domain_check = $this->api_domain_check();
        $domain_check = json_decode($domain_check);

        if ($domain_check == null) {
            $result = array(
                "result" 	=> "error",
                "message"	=> 'failed connection fail'
            );
            echo json_encode($result);
            exit;
        }

        if(!$domain_check->result){
            switch ($domain_check->error_code) {
                case 'SC001':
                    $c2ms_key = 'domain_error1';
                    break;
                case 'SC002':
                    $c2ms_key = 'domain_error2';
                    break;
                case 'SC003':
                    $c2ms_key = 'domain_error3';
                    break;
                default:
                    $c2ms_key = 'domain_error1';
            }

            $result = array(
                "result" 	=> "error",
                "message"	=> $domain_check->message, //api return message
                "c2ms_key"  => $c2ms_key
            );
            echo json_encode($result);
            exit;
        }

        $result = array(
            "result" 	=> "success",
            "message"	=> "패턴 일치" //no output
        );
        echo json_encode($result, JSON_NUMERIC_CHECK);
        exit;

    }


    /**
     * region 페이지
     */
    public function region()
    {
        if(empty($this->session->userdata['addsite']['user_domain']) ){
            redirect('/addsite');
        }

        $this->assets['css'][] = 'assets/css/addsite/region.css';
        $this->assets['end_script'][] = 'assets/js/addsite/region.js';

        $user_domain = $this->session->userdata['addsite']['user_domain'];
        $email = $this->session->userdata('user_id');

        #-- dns레코드 찾아서 ip정보 가져옴
        $get_domain_info = json_decode($this->get_domain_ip_info($user_domain));

        if($this->input->post('change_ip')!=null){
            $ip = $this->input->post('change_ip');
            $ip_multi = $this->input->post('change_ip_multi');
        }else{
            $ip = $get_domain_info->ip;
            $ip_multi = $get_domain_info->ip_multi;
        }

        if($this->closest_afc($ip,$email) == null){
            redirect('/addsite');
        }

        $result = array(
            "user_domain" 	=> $user_domain,
            "user_domain_ip"	=> $ip,
            "user_domain_ip_multi"	=> $ip_multi,
            "closest_afc"	=> $this->closest_afc($ip,$email) //가까운 리전 정보가 없을떈
        );

        $this->load->view('addsite/region', $result);
    }

    /**
     * dns레코드로 등록한 도메인의 ip 정보를 가져옴
     * @return string ip 정보를 못가져올 경우 0.0.0.0 으로 리턴
     */
    private function get_domain_ip_info($domain_name)
    {
        $dns_ip_list = array();
        $dns_ips = @dns_get_record($domain_name, DNS_A);
        if ( $dns_ips && is_array($dns_ips) ) {
            foreach ($dns_ips as $key => $val) {
                $dns_ip_list[$key] = $val["ip"];
            }
        }

        $ip_multi = null;
        $ip = null;

        if ( !empty($dns_ip_list)){

            if ( count($dns_ip_list) > 1 ){
                $ip_multi = $dns_ip_list;
                $ip = $dns_ip_list[0];
            }else if ( count($dns_ip_list) == 1 ){
                $ip = $dns_ip_list[0];
            }else{
                $ip = '0.0.0.0';
            }

        }else{
            $ip = '0.0.0.0';
        }

        $result = array("ip" => $ip, "ip_multi" => $ip_multi);
        if($this->input->post('is_return')){
            echo json_encode($result);
            exit;
        }
        return json_encode($result);

    }



    /**
     * ip로 가장 가까운 리전 afc 리스트 가져오기 api 호출
     * @param $ip
     * @return mixed|null|string
     */
    private function closest_afc($ip, $email) {
        $requestUrl = '?ip='.$ip.'&email='.$email;

        $response = request_api_server(
            'GET',
            '/websites/closest_afc/' . $requestUrl,
            null,
            30
        );
        clog_message("debug", $response);

        $responseArray = json_decode($response, true);
        if($responseArray['result']){
            if(isset($responseArray['result_info']['ip']) && $responseArray['result_info']['cloudbric_idc'] != null) {
                $responseArray['result_info']['cloudbric_idc'][0]['speed'] = 'fastest';
                $responseArray = $responseArray['result_info']['cloudbric_idc'];

                for( $i=0;$i<count($responseArray);$i++ ){

                    if($responseArray[$i]['afc_ip'] == "0.0.0.0"){
                        unset($responseArray[$i]);
                    }
                }
                $responseArray = array_values($responseArray);

                $response = json_encode($responseArray);
            }else{
                $response = null;
            }
        }else{
            $response = null;
        }
        return $response;
    }

    public function closest_afc_reload() {


        $ip = $this->input->post('ip');
        if($this->input->post('cname') != ''){
            #-- dns레코드 찾아서 ip정보 가져옴
            $get_domain_info = json_decode($this->get_domain_ip_info($this->input->post('cname')));
            $ip = $get_domain_info->ip;
            //$ip_multi = $get_domain_info->ip_multi;

        }
        $email = $this->input->post('email');

        $requestUrl = '?ip='.$ip.'&email='.$email;

        $response = request_api_server(
            'GET',
            '/websites/closest_afc/' . $requestUrl,
            null,
            30
        );
        clog_message("debug", $response);

        echo $response;
        exit;

    }

    public function region_process()
    {
        $selected_ip = $this->input->post('selected_ip');
        $ip_multi = $this->input->post('ip_multi');
        $cname = $this->input->post('cname');
        $webserver_type = $this->input->post('webserver_type');
        $afc_code = $this->input->post('afc_code');



        $user_domain = $this->session->userdata['addsite']['user_domain'];

        //$this->session->set_userdata(array('addsite' => array('domain'=>$user_domain)));
        $set_session['addsite']['user_domain'] = $user_domain;
        $set_session['addsite']['user_domain_ip'] = $selected_ip;
        $set_session['addsite']['user_domain_ip_multi'] = $ip_multi;
        $set_session['addsite']['user_domain_cname'] = $cname;
        $set_session['addsite']['webserver_type'] = $webserver_type;
        $set_session['addsite']['afc_code'] = $afc_code;

        $this->session->set_userdata($set_session);
        //$this->session->set_userdata(array('addsite' => array('multi_ip'=>$multi_ip)));
        //$this->session->set_userdata(array('addsite' => array('afc_code'=>$afc_code)));

        $result = array(
            "user_domain"           => $user_domain,
            "user_domain_ip"        => $selected_ip,
            "user_domain_ip_multi"  => $ip_multi,
            "user_domain_cname"     => $cname,
            "webserver_type"        => $webserver_type,
            "afc_code"              => $afc_code,
        );
        echo json_encode($result);
        exit;

    }

    /**
     * 인증서 선택
     */
    public function ssl() {
        if(empty($this->session->userdata['addsite']['user_domain'])){
            redirect('/addsite');
        }

        $result = array(
            "user_domain"  => $this->session->userdata['addsite']['user_domain'],
        );

        $this->load->view('addsite/ssl', $result);
    }


    public function ssl_process(){
        if(empty($this->session->userdata['addsite']['user_domain'])){
            redirect('/addsite');
        }

        $ssl_status = $this->input->post('ssl_status');
        $ssl_type = $this->input->post('ssl_type');

        $ssl_mode = "full";
        if($ssl_status == 'E'){
            $ssl_mode = "basic";
        }

        #-- 도메인 등록 api 호출
        $getData = array(
            "email" => $this->session->userdata['user_id'],
            "domain" => $this->session->userdata['addsite']['user_domain'],
            "ssl_mode" => $ssl_mode,
            "ip" => $this->session->userdata['addsite']['user_domain_ip'],
            "ip_multi" => $this->session->userdata['addsite']['user_domain_ip_multi'],
            "ip_cname" => $this->session->userdata['addsite']['user_domain_cname'],
            "afc_code" => $this->session->userdata['addsite']['afc_code'],
            "ssl_type" => $ssl_type,
            "add_type" => "ns",
        );
        $getData = json_encode($getData);

        $response = request_api_server(
            'POST',
            '/websites',
            $getData,
            120
        );

        clog_message("debug", $response);

//echo $getData;
//echo $response;
//exit;
        $responseArray = json_decode($response, true);

        //print_r($response);
        //exit;

        #-- 도메인 등록 성공 실패 여부
        if($responseArray['result']) {

            $domain_idx = $responseArray["result_info"]["domain_idx"];
            $user_domain = $responseArray["result_info"]["domain"];
            $user_idx = $responseArray["result_info"]["user_idx"]; //방금 등록한 도메인의 유저
            $is_naked = (count($responseArray["result_info"]["cb_nameserver"]) > 0)? true : false; //네이키드 도메인인지 확인

            $this->session->set_userdata(array("addsite"=>array("domain_idx"=>$domain_idx)));
            $this->session->set_userdata(array("dns"=>$responseArray["result_info"]));

            #-- 이미 등록된 ns가 있을 경우 ns를 갖고 있는 유저와 도메인등록한 유저가 같다면 complete 페이지로 이동
            #root_domain 생성
            $naked_domain = substr($user_domain, strpos($user_domain, ".")+1);
            $msg["has_root"] = false;

            $query = $this->db
                ->select('USER_DOMAIN.*')
                ->select('USER_DOMAIN_STATUS.*')
                ->from('USER_DOMAIN')
                ->join('USER_DOMAIN_STATUS', 'USER_DOMAIN_STATUS.USER_DOMAIN_IDX = USER_DOMAIN.USER_DOMAIN_IDX')
                //->join('USER', 'USER_DOMAIN.USER_CODE = USER.USER_CODE')
                ->where('USER_DOMAIN.WEBSERVER_DOMAIN', $naked_domain)
                ->where('USER_DOMAIN_STATUS.NS_TYPE', "NS")
                ->get_compiled_select();

            $ret = $this->db->query($query);
            $has_root = count($ret->result_array());

            if($has_root > 0) {
                if($ret->row()->USER_CODE == $user_idx){
                    $msg["has_root"] = true;
                    $msg["domain"] = $naked_domain;
                }
            }

            #-- root도메인 가졌다면 complete으로 이동
            if($msg["has_root"]){
                //여기선 고객에게 이메일 발송 불필요

                $this->session->set_userdata(array("naked_domain"=>$naked_domain));
                $result = array(
                    "result" => "success",
                    "url" => "complete",
                    "message" => "go to complete" // no output
                );
                echo json_encode($result);
                exit;

            }

            //고객에게 이메일 발송
            $param = array("user_domain_idx"=>$domain_idx);
            $param = json_encode($param);

            if($is_naked == true) { //네이키드 도메인
                if($ssl_type == 'LD01') {
                    request_cc_server(
                        '/wFunction/mail/send_change_dns_token_mail',
                        '20006',
                        $param,
                        30
                    );
                } else {
                    request_cc_server(
                        '/wFunction/mail/send_change_nameserver_request_mail',
                        '20006',
                        $param,
                        30
                    );
                }
            } else { //서브 도메인
                if($ssl_type == 'LD01') {
                    request_cc_server(
                        '/wFunction/mail/send_change_subdomain_dns_token_mail',
                        '20006',
                        $param,
                        30
                    );
                } else {
                    request_cc_server(
                        '/wFunction/mail/send_change_subdomain_request_mail',
                        '20006',
                        $param,
                        30
                    );
                }
            }

            $result = array(
                "result" => "success",
                "message" => "Domain registration was Completed." // no output
            );
            echo json_encode($result);
            exit;

        } else {

            $result = array(
                "result" => "error",
                "url" => "error",
                "message" => $responseArray["message"], //도메인 등록 실패
                "c2ms_key"  => "domain_failed"
            );
            echo json_encode($result);
            exit;
            //$this->load->view('/addsite/ssl');
        }


    }

    /**
     * domain_idx로 도메인 정보 가져오기 api 호출
     * @return null
     */
    private function get_domain(){

        if(empty($this->selected_domain_idx) && empty($this->session->userdata['addsite']['domain_idx'])) {
            redirect('/mysites');
        }else if(empty($this->selected_domain_idx)){
            $this->selected_domain_idx = $this->session->userdata['addsite']['domain_idx'];
        }

        $domain_idx = $this->selected_domain_idx;

        $response = request_api_server(
            'GET',
            '/websites/' . $domain_idx,
            null,
            30
        );
        clog_message("debug", $response);

        $responseArray = json_decode($response, true);
        if($responseArray['result']){

            return $responseArray["result_info"];

        }else{
            return null;
        }

    }

    /**
     * 등록한 도메인의 현재 네임서버 정보 가져오기
     * @param $domainname
     * @return array
     */
    private function get_nameserver($domainname)
    {
        exec("dig @8.8.8.8 ".$domainname." ns",$result);
        $nameserver=array();
        $check=-1;
        for($i=0;$i<sizeof($result);$i++)
        {
            if($check==0)
            {
                if($result[$i]!=NULL)
                {
                    $record=explode("\t",$result[$i]);
                    array_push($nameserver,$record[sizeof($record)-1]);
                }
                else
                    break;
            }
            if($result[$i]==';; ANSWER SECTION:')
                $check=0;
        }
        sort($nameserver);
        return $nameserver;
    }

    /**
     * /addsite/dns
     */
    public function dns()
    {
        $this->selected_domain_idx =  $this->uri->segment(3);
        if(empty($this->selected_domain_idx) && empty($this->session->userdata['addsite']['domain_idx'])) {
            redirect('/mysites');
        }else if(empty($this->selected_domain_idx)){
            $this->selected_domain_idx = $this->session->userdata['addsite']['domain_idx'];
        }

        $this->assets['css'][] = 'assets/css/addsite/dns.css';

        $domain_idx = $this->selected_domain_idx;

        #-- 호스팅정보 현재 사용하지 않음
        $hosting_info["result"] = "false";
        $hosting_info["guide"]["company"] = "godaddy";
        $hosting_info["guide"]["url"] = "https://cloudbric.zendesk.com/hc/en-us/articles/115001539583-A-beginner-s-guide-to-DNS";


        #-- getDomain api 호출
        $domain_info = $this->get_domain();
        $domain = $domain_info["domain"];

        //current ns 정보 가져오기
        $origin_ns = $this->get_nameserver($domain);
        if($origin_ns == null) $origin_ns = null;
        //변경할 ns 정보 가져오기
        $ns_info = $domain_info["cb_nameserver"];
        if($ns_info == null) $using_nameserver = false;
        else $using_nameserver = true;

        //current dns 정보 가져오기
        $origin_ip = $domain_info["ip"];

        //변경할 dns 정보 가져오기
        $cname = $domain_info["cb_dns"]["sub_cname"];
        $redirect_server_ip = (!empty($domain_info["cb_dns"]["root_ip"])) ? $domain_info["cb_dns"]["root_ip"] : null;
        //naked인지 판단여부
        if($redirect_server_ip == null) $is_subdomain = true;
        else $is_subdomain = false;

        //addsite_progress 필요한 값들
        $ssl_type =  $domain_info["ssl_type"];


        #### dns세션 통체로 구움 ####
        /*$result_data = $this->session->userdata("dns");
        $domain = $result_data["domain"];
        $domain_idx = $result_data["domain_idx"];

        #current ns 정보 가져오기
        $origin_ns = $this->get_nameserver($domain);
        if($origin_ns == null) $origin_ns = null;
        #print_r($origin_ns);
        #변경할 ns 정보 가져오기
        $ns_info = $result_data["cb_nameserver"];
        if($ns_info == null) $using_nameserver = false;
        else $using_nameserver = true;


        #current dns 정보 가져오기
        $origin_ip = $result_data["ip"];

        #변경할 dns 정보 가져오기
        $cname = $result_data["cb_dns"]["sub_cname"];
        $redirect_server_ip = $result_data["cb_dns"]["root_ip"];

        #addsite_progress 필요한 값들
        $ssl_type =  $result_data["ssl_type"];*/

        //$origin_ip = "1.1.1.1";//$result_data->ip;
        //$afc_region = "Tokyo";
        //$afc_ip = "52.26.31.230";

        //$using_nameserver = true;
        //$ns_info = ["aws01-demo-test.com","aws15-demo-test.com","aws05-demo-test.net","aws02-demo-test.co.kr"];
        //$origin_ns = ["ns-godaddy.com", "ns02-godaddy.co.kr", "ns03-godaddy.net", "ns04-godaddy.com"];

        //$records = null;

        //$ssl_type = $this->ssl_type;
        //$ssl_type = "ld01";

        /*
         * [view] dns_info
         * 1) 루트 도메인이고, 관련 서브도메인을 다른 유저가 사용하는 경우 ---> A/CNAME만 제공
         * 2) 루트 도메인이고, 등록하는 유저가 서브 도메인을 이미 등록한 경우
              ---> Route53에 등록되어있는 서브 도메인 정보 넣기
              ---> NS/CNAME 제공
         * 3) 서브 도메인이고, 루트 도메
         */
        $result = array(
            "host_name"             => $domain
            ,"hosting_info"         => $hosting_info
            ,"domain"               => $domain
            ,"domain_idx"           => $domain_idx
            ,"origin_ip"            => $origin_ip
            ,"using_nameserver"     => $using_nameserver
            ,"is_subdomain"         => $is_subdomain
            ,"ns_info"              => $ns_info
            ,"origin_ns"            => $origin_ns
            ,"cname"                => $cname
            ,"redirect_server_ip"   => $redirect_server_ip
            ,"ssl_type"             => $ssl_type
        );

        $this->load->view('addsite/dns_info', $result);
    }

    /**
     * /addsite/manual
     */
    public function manual()
    {
        $this->selected_domain_idx =  $this->uri->segment(3);

        if(empty($this->selected_domain_idx) && empty($this->session->userdata['addsite']['domain_idx'])) {
            redirect('/mysites');
        }else if(empty($this->selected_domain_idx)){
            $this->selected_domain_idx = $this->session->userdata['addsite']['domain_idx'];
        }


        $this->assets['css'][] = 'assets/css/addsite/region.css';
        $this->assets['css'][] = 'assets/css/addsite/modal.css';
        //$this->assets['end_script'][] = 'assets/js/addsite/modal.js';

        $domain_idx = $this->selected_domain_idx;

        #-- getDomain api 호출
        $domain_info = $this->get_domain();

        $txt_list = null;
        if(!empty($domain_info["ssl_token"])) {
            foreach ($domain_info["ssl_token"] as $key => $val) {
                $txt_list[] = array(
                    "name" => $key,
                    "value" => $val
                );
            }
        }else{
            $txt_list = array(
                "name" => '',
                "value" => ''
            );
        }

        $this->load->view('addsite/ssl_manual', array(
            "txt_list" => $txt_list,
            "domain_idx" => $domain_idx,
            "domain" => $domain_info["domain"]
        ));
    }


    /*public function manual_process()
    {
        if (empty($this->domain_idx) && empty($this->session->userdata['addsite']['domain_idx'])) {
            redirect('/mysites');
        } else if (empty($this->domain_idx)) {
            $this->domain_idx = $this->session->userdata['addsite']['domain_idx'];
        }

        $domain_idx = $this->domain_idx;

        print_r($this->input->post('step'));



        echo json_encode($domain_idx);
        return;

    }*/


    /**
     * 현재 기능 안씀
     */
    public function ssl_token_check(){

        $domain_idx = $this->selected_domain_idx;


        #### getDomain api 호출 ####
        //$domain_info = $this->get_domain();
        //$user_domain = $domain_info["domain"];

        $query = $this->db
            ->select('USER_DOMAIN.*')
            ->select('USER_DOMAIN_STATUS.*')
            ->from('USER_DOMAIN')
            ->join('USER_DOMAIN_STATUS', 'USER_DOMAIN_STATUS.USER_DOMAIN_IDX = USER_DOMAIN.USER_DOMAIN_IDX')
            ->where('USER_DOMAIN.USER_DOMAIN_IDX', $domain_idx)
            ->get_compiled_select();


        $ret = $this->db->query($query);
        $ret_cnt = count($ret->result_array());

        $sub_type = null;
        if($ret_cnt > 0) {
            $sub_type = $ret->row()->SUB_TYPE;
            $ssl_token = $ret->row()->SSL_TOKEN;
            $domain = $ret->row()->WEBSERVER_DOMAIN;

            //$this->session->set_userdata(array('addsite'=> array('step'=>$this->input->post('step'))));


            if($sub_type == "M"){

                $ssl_token_naked = explode(",", $ssl_token);

                $naked_result = $this->token_check($domain, $ssl_token_naked[0]);
                $www_result = $this->token_check("www.".$domain, $ssl_token_naked[1]);

                if(!($naked_result && $www_result)){

                    $result = array(
                        "result" => "error",
                        "message" => "TXT record is not added. Please try again.(Fail | naked token)"
                    );
                    echo json_encode($result);
                    exit;
                }

            }else{
                $sub_result = $this->token_check($domain, $ssl_token);
                if(!$sub_result){
                    $result = array(
                        "result" => "error",
                        "message" => "Fail | sub token"
                    );
                    echo json_encode($result);
                    exit;

                }
            }


            if(!empty($this->input->post('step'))){
                $this->session->set_userdata(array('addsite'=> array('step'=>$this->input->post('step'))));

                $result = array(
                    "result" => "success",
                    "message" => "Your SSL Certificate has been successfully issued!"
                );
                echo json_encode($result);
                exit;
            }

        }else{

            $domain_idx = $this->uri->segment(3);

            $this->session->set_userdata(array('addsite'=> array('step'=>$this->input->post('step'))));
            $this->session->set_userdata(array('addsite'=> array('domain_idx'=>$this->uri->segment(3))));

            $result = array(
                "result" => "error",
                "message" => "데이터가 없음".$this->uri->segment(3)."<<<".$this->selected_domain_idx
            );
            echo json_encode($result);
            exit;
        }


    }

    /**
     * 현재 기능 안씀ㅎ
     */
    public function token_check($user_domain, $ssl_token)
    {

        //dns01 인증 도메인 token을 정상적으로 가지고 있는지 확인
        exec("dig \"_acme-challenge.".$user_domain."\" txt +short", $result_txt_record);
        if(!(isset($result_txt_record) && !empty($result_txt_record))){
            //print_r("Fail | DNS TXT Records Does not exist. | USER_DOMAIN_IDX : ".$domain_idx." | DOMAIN : ".$user_domain." | Line : ".__LINE__);
            return false;
        }

        if(count($result_txt_record) > 1){
            //print_r("Fail | Multiple DNS Records are updated on dns. | USER_DOMAIN_IDX : ".$domain_idx." | DOMAIN : ".$user_domain." | Line : ".__LINE__);
            return false;
        }

        $result_txt_record = $result_txt_record[0];

        if($result_txt_record != '"'.$ssl_token.'"'){
            //print_r($result_txt_record[0]." Fail | DNS Token is wrong in customer's record! | ".$domain_idx." | ".$user_domain." | Line : ".__LINE__);
            return false;
        }

        return true;
    }


    /**
     * /addsite/complete
     * naked domain의 ns를 aws route53의 ns를 사용하고 있고 같은 유저가 도메인을 등록하면 이동
     */
    public function complete()
    {
        $this->selected_domain_idx =  $this->uri->segment(3);

        if(empty($this->selected_domain_idx) && empty($this->session->userdata['addsite']['domain_idx'])) {
            redirect('/mysites');
        }else if(empty($this->selected_domain_idx)){
            $this->selected_domain_idx = $this->session->userdata['addsite']['domain_idx'];
        }


        $result = array(
            "domain" => $this->session->userdata['dns']['domain'],
            "naked_domain"  => $this->session->userdata['naked_domain'],
        );

        $this->load->view('addsite/complete',$result);
    }


}
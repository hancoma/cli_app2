<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    private $selected_domain_idx = 0;
    private $selected_domain_info;
    private $user_sites;

    public function __construct()
    {
        parent::__construct();

        if(empty($this->session->userdata('user_id')) ){
            redirect('/sign/in');
        }

        //모바일에선 3번째 도메인idx 인자가 없다
        if ($this->uri->segment(2) != 'a_mobile' && $this->uri->segment(2) != 'b_mobile') {
            $this->selected_domain_idx =  $this->uri->segment(3);
            if(empty($this->selected_domain_idx)){
                redirect('/mysites');
            }else{
                $this->selected_domain_info = get_selected_domain($this->selected_domain_idx);
            }
        }

        if($this->selected_domain_info['forced_bypass'] == 'ON') {
            redirect('/mysites');
        }

        //부하 줄이기 : 필요한 곳에서만 사용 (추가 페이지가 생기면 이곳에 배열 추가 할 것)
        $page_type_methods = array('a');
        if(in_array($this->uri->segment(2), $page_type_methods)) {
            $this->user_sites = get_domain_group_list();

            $this->assets['css'][] = 'assets/css/dashboard.css';
            $this->assets['end_script'][] = array('assets/plugins/dx/globalize.min.js', false);
            $this->assets['end_script'][] = array('assets/plugins/dx/dx.all.js', false);
            $this->assets['end_script'][] = array('assets/plugins/dx/world.js', false);
            $this->assets['end_script'][] = array('assets/plugins/dx/dx.exporter.debug.js', false);
            $this->assets['end_script'][] = array('assets/plugins/daterangepicker/moment.js', false);
            $this->assets['end_script'][] = array('assets/plugins/daterangepicker/daterangepicker.js', false);
            $this->assets['end_script'][] = 'assets/js/dashboard.js';
        }

    }

    public function a_mobile()
    {
        $this->layout = 'mobile_blank'; //framework7 사용

        $this->load->view('dashboard/index_mobile');
    }

    public function domainData($index) {
        $getDomainData = get_selected_domain($index);
        $visitsAttacks = json_decode($this->visitsAttacks($index), true);
        $visitCountries = json_decode($this->visitCountries($index), true);
        $trafficLogs = json_decode($this->trafficLogs($index), true);
        $attackIpCountries = json_decode($this->attackIpCountries($index), true);
        $attackPurposes = json_decode($this->attackPurposes($index), true);
        $topAttackUrls = json_decode($this->topAttackUrls($index), true);

        $result = array(
            'idx' => $index,
            'name' => $getDomainData['domain'],
            'status' => $getDomainData['status'],
            'traffic' => array(
                'total' => $getDomainData['traffic'],
                'limit' => $getDomainData['limit_traffic'],
                'data' => $trafficLogs['result_info']['traffic_logs']
            ),
            'visits_attacks' => array(
                'data' => $visitsAttacks['result_info']['total_log'],
                'visits_count' => $visitsAttacks['result_info']['total_count']['visits'],
                'attacks_count' => $visitsAttacks['result_info']['total_count']['attacks'],
            ),
            'countries' => array(
                'data' => $visitCountries['result_info']['top_visit_countries']
            ),
            'ips' => array(
                'data' => $attackIpCountries['result_info']['top_attack_ips']
            ),
            'purpose' => array(
                'data' => $attackPurposes['result_info']['top_attack_purposes']
            ),
            'urls' => array(
                'data' => $topAttackUrls['result_info']['top_attack_uris']
            )
        );

        echo json_encode($result);
        exit;
    }

    public function a()
    {
        $this->assets['css'][] = array('assets/plugins/dx/dx.exporter.light.css', false);
        $this->assets['css'][] = array('assets/plugins/daterangepicker/daterangepicker.css', false);

        $this->load->view('dashboard/index',
            array(
                "plan" => $this->selected_domain_info['paid_plan'],
                "user_sites"  => $this->user_sites,
                "selected_domain_info" => $this->selected_domain_info
            )
        );
    }

    public function visitsAttacks($index) {
        $start_date = date("Ymd", strtotime('first day of this month'));
        $last_date = date("Ymd");

        $from = $this->input->get('from');
        $from = $from ? $from : $start_date;

        $to = $this->input->get('to');
        $to = $to ? $to : $last_date;

        $domain_idx = $index;
        $requestUrl = $domain_idx . '?from=' . $from . '&to=' . $to;

        $response = request_api_server(
            'GET',
            '/logs/visits_attacks/' . $requestUrl,
            null,
            30
        );

        return $response;
    }

    public function visitorsHackers() {
        $start_date = date("Ymd", strtotime('first day of -1 month'));
        $last_date = date("Ymd");

        $from = $this->input->get('from');
        $from = $from ? $from : $start_date;

        $to = $this->input->get('to');
        $to = $to ? $to : $last_date;

        $domain_idx = $this->selected_domain_idx;
        $requestUrl = $domain_idx . '?from=' . $from . '&to=' . $to;

        $response = request_api_server(
            'GET',
            '/logs/visitors_hackers/' . $requestUrl,
            null,
            30
        );

        echo $response;
        exit;
    }

    public function attackIpCountries($index) {
        $start_date = date("Ymd", strtotime('first day of this month'));
        $last_date = date("Ymd");

        $from = $this->input->get('from');
        $from = $from ? $from : $start_date;

        $to = $this->input->get('to');
        $to = $to ? $to : $last_date;

        $domain_idx = $index;
        $requestUrl = $domain_idx . '?from=' . $from . '&to=' . $to;

        $ipResponse = request_api_server(
            'GET',
            '/logs/top_attack_ips/' . $requestUrl,
            null,
            30
        );

        $ipResponseArray = json_decode($ipResponse, true);
        if(isset($ipResponseArray['result_info']['top_attack_ips']) && $ipResponseArray['result_info']['top_attack_ips'] != null) {
            $ipResponseArray['result_info']['top_attack_ips'] = array_slice($ipResponseArray['result_info']['top_attack_ips'], 0, 5);
        }

        $ipResponse = json_encode($ipResponseArray);
        return $ipResponse;
    }

    public function attackPurposes($index) {
        $start_date = date("Ymd", strtotime('first day of this month'));
        $last_date = date("Ymd");

        $from = $this->input->get('from');
        $from = $from ? $from : $start_date;

        $to = $this->input->get('to');
        $to = $to ? $to : $last_date;

        $domain_idx = $index;
        $requestUrl = $domain_idx . '?from=' . $from . '&to=' . $to;

        $response = request_api_server(
            'GET',
            '/logs/top_attack_purposes/' . $requestUrl,
            null,
            30
        );

        return $response;
    }

    public function topAttackUrls($index) {
        $start_date = date("Ymd", strtotime('first day of this month'));
        $last_date = date("Ymd");

        $from = $this->input->get('from');
        $from = $from ? $from : $start_date;

        $to = $this->input->get('to');
        $to = $to ? $to : $last_date;

        $domain_idx = $index;
        $requestUrl = $domain_idx . '?from=' . $from . '&to=' . $to;

        $response = request_api_server(
            'GET',
            '/logs/top_attack_uris/' . $requestUrl,
            null,
            30
        );

        $responseArray = json_decode($response, true);
        if(isset($responseArray['result_info']['top_attack_uris']) && $responseArray['result_info']['top_attack_uris'] != null) {
            $responseArray['result_info']['top_attack_uris'] = array_slice($responseArray['result_info']['top_attack_uris'], 0, 5);
        }

        $response = json_encode($responseArray);

        return $response;
    }

    public function recentAttacks() {
        $start_date = date("Ymd", strtotime('first day of -1 month'));
        $last_date = date("Ymd");

        $from = $this->input->get('from');
        $from = $from ? $from : $start_date;

        $to = $this->input->get('to');
        $to = $to ? $to : $last_date;

        $domain_idx = $this->selected_domain_idx;
        $requestUrl = $domain_idx . '?from=' . $from . '&to=' . $to . '&offset=0&limit=5';

        $response = request_api_server(
            'GET',
            '/logs/attack_logs/' . $requestUrl,
            null,
            30
        );

        echo $response;
        exit;
    }

    public function visitCountries($index) {
        $start_date = date("Ymd", strtotime('first day of this month'));
        $last_date = date("Ymd");

        $from = $this->input->get('from');
        $from = $from ? $from : $start_date;

        $to = $this->input->get('to');
        $to = $to ? $to : $last_date;

        $domain_idx = $index;
        $requestUrl = $domain_idx . '?from=' . $from . '&to=' . $to;

        $response = request_api_server(
            'GET',
            '/logs/top_visit_countries/' . $requestUrl,
            null,
            30
        );

        $responseArray = json_decode($response, true);
        if(isset($responseArray['result_info']['top_visit_countries']) && $responseArray['result_info']['top_visit_countries'] != null) {
            $responseArray['result_info']['top_visit_countries'] = array_slice($responseArray['result_info']['top_visit_countries'], 0, 5);
        }

        $response = json_encode($responseArray);
        return $response;
    }

    public function accumulatedBandwidth() {
        $from = date("Ym01");
        $to = date("Ymd");

        $domain_idx = $this->selected_domain_idx;
        $requestUrl = $domain_idx . '?from=' . $from . '&to=' . $to; // 사용자의 날짜 선택에 상관 없이 이번 달만 보여줘야 함. (당일까지)
        $limit = $this->selected_domain_info['limit_traffic'];

        $response = request_api_server(
            'GET',
            '/logs/traffic_logs/' . $requestUrl,
            null,
            30
        );

        $responseArray = json_decode($response, true);

        $expectedResponse = request_api_server(
            'GET',
            '/logs/expected_traffic/' . $domain_idx,
            null,
            30
        );

        $expectedArray = json_decode($expectedResponse, true);
        $expected = $expectedArray['result_info']['expected_traffic'];

        $logLength = count($responseArray['result_info']['traffic_logs']);

        for($i = $logLength; $i < date('t'); $i++) {
            $date = date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-' . $i))));

            $responseArray['result_info']['traffic_logs'][$i]['date'] = $date;
            $responseArray['result_info']['traffic_logs'][$i]['traffic'] = 0;
        }

        $responseArray['limit'] = $limit;
        $responseArray['expected'] = $expected;
        $logLength = count($responseArray['result_info']['traffic_logs']);

        $responseArray['result_info']['traffic_logs'][0]['expected'] = 0;
        $responseArray['result_info']['traffic_logs'][$logLength-1]['expected'] = $expected;

        $response = json_encode($responseArray);
        echo $response;
        exit;
    }

    public function throughputLogs() {
        $start_date = date("Ymd", strtotime('first day of -1 month'));
        $last_date = date("Ymd");

        $from = $this->input->get('from');
        $from = $from ? $from : $start_date;

        $to = $this->input->get('to');
        $to = $to ? $to : $last_date;

        $domain_idx = $this->selected_domain_idx;
        $requestUrl = $domain_idx . '?from=' . $from . '&to=' . $to;
        $limit = $this->selected_domain_info['limit_throughput'];

        $response = request_api_server(
            'GET',
            '/logs/maximum_bps_logs/' . $requestUrl,
            null,
            30
        );

        $responseArray = json_decode($response, true);
        $responseArray['limit'] = $limit;

        $response = json_encode($responseArray);
        echo $response;
        exit;
    }

    public function trafficLogs($index) {
        $start_date = date("Ymd", strtotime('first day of this month'));
        $last_date = date("Ymd");

        $from = $this->input->get('from');
        $from = $from ? $from : $start_date;

        $to = $this->input->get('to');
        $to = $to ? $to : $last_date;

        $domain_idx = $index;
        $requestUrl = $domain_idx . '?from=' . $from . '&to=' . $to;

        $response = request_api_server(
            'GET',
            '/logs/traffic_logs/' . $requestUrl,
            null,
            30
        );

        return $response;
    }
}
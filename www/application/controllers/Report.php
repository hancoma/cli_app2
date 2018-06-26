<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller
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

        $this->selected_domain_idx =  $this->uri->segment(3);
        if(empty($this->selected_domain_idx)){
            redirect('/mysites');
        }else{
            $this->selected_domain_info = get_selected_domain($this->selected_domain_idx);
        }

        if($this->selected_domain_info['forced_bypass'] == 'ON') {
            redirect('/mysites');
        }

        //부하 줄이기 : 필요한 곳에서만 사용 (추가 페이지가 생기면 이곳에 배열 추가 할 것)
        $page_type_methods = array('view');
        if(in_array($this->uri->segment(2), $page_type_methods)) {
            $this->user_sites = get_domain_group_list();
        }

    }

    public function index() {
        redirect('/mysites');
    }

    public function view() {
        if(empty($this->selected_domain_idx)) {
            redirect('/mysites');
        } else {
            $this->selected_domain_info = get_selected_domain($this->selected_domain_idx);
        }

        $_baseURI = 'https://console.cloudbric.com/console/report/monthly';
        $range_reports_month[] = date("Y/m", strtotime('first day of -1 month'));
        $range_reports_month[] = date("Y/m", strtotime('first day of -2 month'));
        $range_reports_month[] = date("Y/m", strtotime('first day of -3 month'));

        $this->load->view('report/index',
            array(
                "user_sites"  => $this->user_sites,
                "selected_domain_info" => $this->selected_domain_info,
                "baseURI" => $_baseURI,
                "range_reports_month" => $range_reports_month,
                "thisDomain" => $this->selected_domain_info['domain'],
                "iframeURI" => $_baseURI . '/' . $range_reports_month[0] . '/' . $this->selected_domain_info['domain']
            )
        );
    }

    public function monthly($y, $m, $domain) {
        $this->yield = false;
        $this->load->view('report/monthly',
            array(
                "year" => $y,
                "month" => $m,
                "domain" => $domain
            )
        );
    }

    public function testMethod() {
        $search_term = array(
            "start_date" => "2017-03-01",
            "end_date" => "2017-03-02"
        );

        $overview_data = array ( 'page_view' => array ( 'chart' => array ( 0 => array ( 'date' => '2017-03-01', 'attack' => 0, 'visit' => 0, ), 1 => array ( 'date' => '2017-03-02', 'attack' => 0, 'visit' => 0, ), 2 => array ( 'date' => '2017-03-03', 'attack' => 39, 'visit' => 1374, ), 3 => array ( 'date' => '2017-03-04', 'attack' => 0, 'visit' => 0, ), 4 => array ( 'date' => '2017-03-05', 'attack' => 0, 'visit' => 0, ), 5 => array ( 'date' => '2017-03-06', 'attack' => 0, 'visit' => 0, ), 6 => array ( 'date' => '2017-03-07', 'attack' => 0, 'visit' => 0, ), 7 => array ( 'date' => '2017-03-08', 'attack' => 0, 'visit' => 0, ), 8 => array ( 'date' => '2017-03-09', 'attack' => 0, 'visit' => 0, ), ), 'summary' => array ( 'attacks' => 39, 'visits' => 1374, ), ), 'uniq_view' => array ( 'chart' => array ( 0 => array ( 'date' => '2017-03-01', 'hacker' => 0, 'visitor' => 0, ), 1 => array ( 'date' => '2017-03-02', 'hacker' => 0, 'visitor' => 0, ), 2 => array ( 'date' => '2017-03-03', 'hacker' => 26, 'visitor' => 221, ), 3 => array ( 'date' => '2017-03-04', 'hacker' => 0, 'visitor' => 0, ), 4 => array ( 'date' => '2017-03-05', 'hacker' => 0, 'visitor' => 0, ), 5 => array ( 'date' => '2017-03-06', 'hacker' => 0, 'visitor' => 0, ), 6 => array ( 'date' => '2017-03-07', 'hacker' => 0, 'visitor' => 0, ), 7 => array ( 'date' => '2017-03-08', 'hacker' => 0, 'visitor' => 0, ), 8 => array ( 'date' => '2017-03-09', 'hacker' => 0, 'visitor' => 0, ), ), 'summary' => array ( 'hackers' => '1', 'visitors' => 221, ), ), );
        $report_attacks = array ( 'worldwide' => array ( 'distribution' => array ( 'ip' => NULL, 'country' => NULL, ), ), 'top_attack_purpose' => NULL, 'top_attack_urls' => NULL, 'recent_attack' => NULL, );
        $report_visits = array ( 'worldwide' => array ( 'distribution' => array ( 'country' => NULL, ), 'top5' => NULL, ), 'accumulated' => array ( 0 => array ( 'date' => '2017/04/01', 'data' => 0, 'expected_data' => 0, ), 1 => array ( 'date' => '2017/04/02', 'data' => 0, ), 2 => array ( 'date' => '2017/04/03', 'data' => 0, ), 3 => array ( 'date' => '2017/04/04', 'data' => 0, ), 4 => array ( 'date' => '2017/04/05', 'data' => 0, ), 5 => array ( 'date' => '2017/04/06', 'data' => 0, ), 6 => array ( 'date' => '2017/04/07', 'data' => 0, ), 7 => array ( 'date' => '2017/04/08', 'data' => 0, ), 8 => array ( 'date' => '2017/04/09', 'data' => 0, ), 9 => array ( 'date' => '2017/04/10', 'data' => 0, ), 10 => array ( 'date' => '2017/04/11', 'data' => 0, ), 11 => array ( 'date' => '2017/04/12', 'data' => 0, ), 12 => array ( 'date' => '2017/04/13', 'data' => 0, ), 13 => array ( 'date' => '2017/04/14', 'data' => 0, ), 14 => array ( 'date' => '2017/04/15', 'data' => 0, ), 15 => array ( 'date' => '2017/04/16', 'data' => 0, ), 16 => array ( 'date' => '2017/04/17', 'data' => 0, ), 17 => array ( 'date' => '2017/04/18', 'data' => 0, ), 18 => array ( 'date' => '2017/04/19', 'data' => 0, ), 19 => array ( 'date' => '2017/04/20', 'data' => 0, ), 20 => array ( 'date' => '2017/04/21', 'data' => 0, ), 21 => array ( 'date' => '2017/04/22', 'data' => 0, ), 22 => array ( 'date' => '2017/04/23', 'data' => 0, ), 23 => array ( 'date' => '2017/04/24', 'data' => 0, ), 24 => array ( 'date' => '2017/04/25', 'data' => 0, ), 25 => array ( 'date' => '2017/04/26', 'data' => 0, ), 26 => array ( 'date' => '2017/04/27', 'data' => 0, ), 27 => array ( 'date' => '2017/04/28', 'data' => 0, ), 28 => array ( 'date' => '2017/04/29', 'data' => 0, ), 29 => array ( 'date' => '2017/04/30', 'data' => 0, 'expected_data' => 0, ), ), 'bps' => array ( 0 => array ( 'date' => '2017/03/01', 'data' => 0, ), 1 => array ( 'date' => '2017/03/02', 'data' => 0, ), ), );
        $block_white = array ( 'black_ip' => array ( 0 => '66.249.77.49', 1 => '66.249.77.55', ), 'white_ip' => array ( 0 => '1.1.1.1', ), 'black_country' => array ( 0 => 'KR', ), 'white_country' => NULL, 'extra_url' => array ( 0 => '/index.php/find/search/', ), );

        $result = array(
            "search_term"      => $search_term,
            "overview_data"    => $overview_data,
            "report_attacks"   => $report_attacks,
            "report_visits"    => $report_visits,
            "block_white"      => $block_white
        );

        echo json_encode($result, JSON_NUMERIC_CHECK);
        exit;
    }

    public function testMethod2() {
        $_il = $this->input;
        $data = $_il->post('haha') . ' + ' . $_il->post('no');
        echo $data;
        exit;
    }
}
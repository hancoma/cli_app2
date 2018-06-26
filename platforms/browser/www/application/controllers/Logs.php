<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends CI_Controller
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
        $page_type_methods = array('a');
        if(in_array($this->uri->segment(2), $page_type_methods)) {
            $this->user_sites = get_domain_group_list();

            $this->assets['css'][] = 'assets/css/logs.css';
            $this->assets['css'][] = array('assets/plugins/dx/dx.common.css', false);
            $this->assets['css'][] = array('assets/plugins/daterangepicker/daterangepicker.css', false);
            $this->assets['css'][] = array('assets/plugins/dx/dx.light.compact.css', false);
            $this->assets['end_script'][] = array('assets/plugins/daterangepicker/moment.js', false);
            $this->assets['end_script'][] = array('assets/plugins/daterangepicker/daterangepicker.js', false);
            $this->assets['end_script'][] = array('assets/plugins/dx/globalize.min.js', false);
            $this->assets['end_script'][] = array('assets/plugins/jquery/jquery-ui.min.js', false);
            $this->assets['end_script'][] = array('assets/plugins/tagit/tag-it.min.js', false);
            $this->assets['end_script'][] = array('assets/plugins/dx/globalize.min.js', false);
            $this->assets['end_script'][] = array('assets/plugins/dx/dx.webappjs.debug.js', false);
            $this->assets['end_script'][] = array('assets/plugins/dx/dx.all.js', false);
            $this->assets['end_script'][] = array('assets/plugins/dx/jszip.min.js', false);
            $this->assets['end_script'][] = 'assets/js/logs.js';
        }

    }

    public function a()
    {
        $this->load->view('logs/index',
            array(
                "user_sites"  => $this->user_sites,
                "selected_domain_info" => $this->selected_domain_info,
            )
        );
    }

    public function attackLogs() {
        $start_date = date("Ym01", strtotime('first day of -1 month'));
        $last_date = date("Ymd");

        $from = $this->input->get('from');
        $from = $from ? $from : $start_date;

        $to = $this->input->get('to');
        $to = $to ? $to : $last_date;

        $domain_idx = $this->selected_domain_idx;
        $requestUrl = $domain_idx . '?from=' . $from . '&to=' . $to . '&limit=1000'; //레벨 10 api 키. 맥시멈 1000개 한번에 호출


        $response = request_api_server(
            "GET",
            '/logs/attack_logs/' . $requestUrl,
            null,
            120
        );

        echo $response;
        exit;
    }
}
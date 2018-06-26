<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mysites extends CI_Controller {
    public function __construct()
    {
        parent::__construct();

        if(empty($this->session->userdata('user_id')) ){
            redirect('/sign/in');
        }

        $this->assets['css'][] = 'assets/css/mysites.css';
        $this->assets['end_script'][] = 'assets/js/mysites.js';
    }

    public function index()
    {
        $this->layout = 'mobile_blank'; //framework7 사용

        $site_info = get_domain_group_list();

        $this->load->view('mysites/index_mobile' , array(
            'mysite_info' => $site_info
        ));
    }
}

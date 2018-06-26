<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Myaccount extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if(empty($this->session->userdata('user_id')) ){
            redirect('/sign/in');
        }

        $this->assets['css'][] = 'assets/css/myaccount.css';
        $this->assets['end_script'][] = 'assets/js/myaccount.js';

        //oauth session value 비어 있을 경우 대비
        if(empty($this->session->userdata('oauth'))) $this->session->set_userdata('oauth', 'cb');
    }

    public function index()
    {
        $this->layout = 'mobile_blank'; //framework7 사용

        //info
        $user_info = array(
            "oauth" => $this->session->userdata('oauth'), //facebook으로 로그인 했으면 fb, google로 로그인 했으면 gp, 이외 로그인은 cb
            "user_name" => $this->session->userdata('user_name'),
            "user_id" => $this->session->userdata('user_id'),
            "coupon_number" => $this->session->userdata('coupon_number'),
        );

        $this->load->view('myaccount/index_mobile' , array('user_info'=>$user_info));
    }

    public function alarm()
    {
        $this->layout = 'mobile_blank'; //framework7 사용

        //info
        $user_info = array(
            "user_name" => $this->session->userdata('user_name'),
            "user_id" => $this->session->userdata('user_id'),
        );

        $this->load->view('myaccount/alarm_mobile' , array('user_info'=>$user_info));
    }

    public function verify_coupon()
    {
        $result = array(
            "result" => "fail"
        );

        if(!empty($this->input->post('coupon_number'))) {

            $coupon_info = $this->_coupon_info($this->input->post('coupon_number'));

            if(!empty($coupon_info) && $coupon_info->COUPON_IDX > 0) {

                $result['result'] = 'success'; //성공 리턴
                $data = array(
                    "COUPON_VALIDITY" => 'Y',
                    "COUPON_CHECK_DATE" => date("Y-m-d H:i:s"),
                );
                $this->session->set_userdata('coupon_validity', 'Y');

            } else {
                //실패하면 쿠폰 인증 초기화
                $data = array(
                    "COUPON_VALIDITY" => null,
                    "COUPON_CHECK_DATE" => null,
                );
                $this->session->set_userdata('coupon_validity', 'N');
            }

            $this->_update_user($data);
        }

        echo json_encode($result);
        exit;
    }

    public function update_user()
    {
        $result = array(
            "result" => 'not_change',
            "db_user_name" => $this->input->post('user_name')
        );

        if(!empty($this->input->post('user_name')) && $this->input->post('user_name') != $this->session->userdata('user_name')) {

            $data = array(
                "USER_NAME" => trim($this->input->post('user_name')),
                "MODIFY_DATE" => date("Y-m-d H:i:s")
            );

            $db_result = $this->_update_user($data);

            $result = array(
                "result" => 'success',
                "db_user_name" => $db_result->USER_NAME
            );
            $this->session->set_userdata('user_name', $db_result->USER_NAME);
        }

        if(!empty($this->input->post('coupon_number'))
            && $this->input->post('coupon_number') != $this->session->userdata('coupon_number')
            && $this->session->userdata('coupon_validity') == 'Y'
        ) {

            $data = array(
                "COUPON_NUMBER" => strtoupper(trim($this->input->post('coupon_number'))),
                "COUPON_REG_DATE" => date("Y-m-d H:i:s"),
                "COUPON_USE" => 'Y',
                "MODIFY_DATE" => date("Y-m-d H:i:s")
            );

            $db_result = $this->_update_user($data);

            $result = array(
                "result" => 'success',
                "db_user_name" => $db_result->USER_NAME
            );
            $this->session->set_userdata('coupon_number', $db_result->COUPON_NUMBER);
        }

        echo json_encode($result);
        exit;
    }

    public function change_pw()
    {
        $result = array(
            "result" => 'fail',
        );

        if(!empty($this->input->post('cur_pw')) && !empty(trim($this->input->post('new_pw')))) {

            if($this->_correct_pw($this->input->post('cur_pw')) == 1) {
                $data = array(
                    "USER_PASSWORD" => md5(trim($this->input->post('new_pw'))),
                    "MODIFY_DATE" => date("Y-m-d H:i:s")
                );
                $this->_update_user($data);

                $result = array(
                    "result" => 'success',
                );
            }
        }

        echo json_encode($result);
        exit;
    }

    private function _coupon_info($coupon_number = null)
    {
        return $this->db
            ->where('COUPON_NUMBER', $coupon_number)
            ->where('COUPON_START <', 'NOW()', false)
            ->where('COUPON_END >', 'NOW()', false)
            ->get('COUPON_INFO')->row();
    }

    private function _update_user($data = null)
    {
        $this->db
            ->set($data)
            ->where('USER_CODE', $this->session->userdata('user_idx'))
            ->update('USER');

        return $this->db
            ->where('USER_CODE', $this->session->userdata('user_idx'))
            ->get('USER')->row();
    }

    private function _correct_pw($user_pwd = null)
    {
        return $this->db
            ->where('USER_CODE', $this->session->userdata('user_idx'))
            ->where('USER_PASSWORD', md5($user_pwd))
            ->count_all_results('USER');
    }
}
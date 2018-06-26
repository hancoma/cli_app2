<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sign extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('encrypt');
    }

    public function index()
    {
        //로그인 했으면 mysites 로!
        if (!empty($this->session->userdata('user_id'))) {
            redirect('/mysites');
        } else {
            redirect('/sign/in');
        }

    }

    public function in()
    {
        $this->layout = 'mobile_blank'; //framework7 사용

        $data = array(); // View sign/in에 전달할 변수 배열 선언 
        $data['isRememberId'] = get_cookie('userId') ?: ''; // 회원 아이디가 저장되어 있는 쿠키가 있을 경우의 분기
        $data['isRememberId'] = $this->encrypt->decode($data['isRememberId']);

        //로그인 했으면 mysites 로!
        if (!empty($this->session->userdata('user_id'))) {
            redirect('/mysites');
        }

        $this->load->view('sign/in_mobile', $data); // 상기 선언된 변수 전달
    }

    public function in_process_mobile()
    {
        //우선 무조건 support@cloudbric.com 으로 로그인 되도록
        $session = array(
            "oauth" => 'cb',
            "manager" => false, //매니저 강제로그인 정보
            "user_id" => 'staff@greenolivetree.net',
            "user_idx" => 'P1704M010125',
            "partner_idx" => 'A2017080002',
            'user_name' => 'Jon Berry'
        );

        $this->session->set_userdata($session);

        //로그인 했으면 mysites 로!
        if (!empty($this->session->userdata('user_id'))) {
            redirect('/mysites');
        } else {
            redirect('/sign/in');
        }
    }

    public function in_process()
    {
        //--- 변수 체크
        if (empty($this->input->post('user_email')) || empty($this->input->post('user_pwd'))) {
            $result = array(
                "result" => "error",
                "message" => "empty"
            );
            echo json_encode($result);
            exit;
        }

        $user_info = $this->_user_info(trim($this->input->post('user_email')), trim($this->input->post('user_pwd')));

        if (!empty($user_info)) {

            $user_remember = $this->input->post("user_remember");
            $session = array(
                "oauth" => 'cb', //facebook으로 로그인 했으면 fb, google로 로그인 했으면 gp, 이외 로그인은 cb
                "manager" => false, //매니저 강제로그인 정보
                "user_id" => $user_info->USER_EMAIL,
                "user_idx" => $user_info->USER_CODE,
                "partner_idx" => $user_info->PARTNER_IDX,
                'user_name' => $user_info->USER_NAME,
                'coupon_number' => $user_info->COUPON_NUMBER,
                "checked_auto" => $user_remember
            );

            $this->session->set_userdata($session);

            $this->_login_log_insert($user_info->USER_CODE); // 로그인 로그 등록

            if ($user_remember == 'auto') {
                $cookieEncode = $this->encrypt->encode($user_info->USER_EMAIL);
                $expiresTimes = strtotime("+1 Year", time()) - time(); // 쿠키 만료시간
                set_cookie('userId', $cookieEncode, $expiresTimes); // Remember Me를 체크했을 때 쿠키 생성
            } else {
                delete_cookie('userId'); // 이미 쿠키가 생성되어 있는데 체크 박스를 풀었을 경우를 고려하여 쿠키 삭제
            }

            $domain_count = $this->db
                ->where('USER_CODE', $user_info->USER_CODE)
                ->count_all_results('USER_DOMAIN');

            if ($domain_count > 0) $landing = '/mysites';
            else $landing = '/addsite';

            $result = array(
                "result" => "success",
                "message" => "success",
                "landing" => $landing
            );
            echo json_encode($result);

        } else {
            $result = array(
                "result" => "error",
                "message" => "Cannot find the user"
            );
            echo json_encode($result);

        }

        exit();

    }

    public function up()
    {
        //로그인 했으면 mysites 로!
        if (!empty($this->session->userdata('user_id'))) {
            redirect('/mysites');
        }

        //파트너 콘솔에선 회원가입 금지
        if(PARTNER_CONSOLE == 'yes') {
            redirect('/sign/in');
        }

        $this->assets['end_script'][] = 'assets/js/sign/up.js';

        $this->load->view('sign/up');
    }

    public function reset()
    {
        //로그인 했으면 mysites 로!
        if (!empty($this->session->userdata('user_id'))) {
            redirect('/mysites');
        }

        //파트너 콘솔에선 패스워드 리셋 금지
        if(PARTNER_CONSOLE == 'yes') {
            redirect('/sign/in');
        }

        $this->load->view('sign/reset');
    }

    public function out()
    {
        $this->session->sess_destroy();
        redirect('/sign/in');
    }

    public function manager($encryption = 'none')
    {
        $user_id = $this->_simple_crypt($encryption, 'd');
        if (empty($user_id)) exit();

        $user_info = $this->_user_info($user_id, null, true);

        if (!empty($user_info)) {
            $session = array(
                "oauth" => 'cb', //facebook으로 로그인 했으면 fb, google로 로그인 했으면 gp, 이외 로그인은 cb
                "manager" => true, //매니저 강제로그인 정보
                "user_id" => $user_info->USER_EMAIL,
                "user_idx" => $user_info->USER_CODE,
                "partner_idx" => $user_info->PARTNER_IDX,
                'user_name' => $user_info->USER_NAME,
                'coupon_number' => $user_info->COUPON_NUMBER,
            );

            $this->session->set_userdata($session);

            $this->_login_log_insert($user_info->USER_CODE); // 로그인 로그 등록

            $domain_count = $this->db
                ->where('USER_CODE', $user_info->USER_CODE)
                ->count_all_results('USER_DOMAIN');

            if ($domain_count > 0) redirect('/mysites');
            else redirect('/addsite');

        } else {

            redirect('/sign/in');
        }

    }

    public function api_in($login_token = null)
    {
        $user_id = $this->_api_token($login_token);
        if (empty($user_id)) exit();

        $user_info = $this->_user_info($user_id, null, true);

        if (!empty($user_info)) {
            $session = array(
                "oauth" => 'cb', //facebook으로 로그인 했으면 fb, google로 로그인 했으면 gp, 이외 로그인은 cb
                "manager" => false, //매니저 강제로그인 정보
                "user_id" => $user_info->USER_EMAIL,
                "user_idx" => $user_info->USER_CODE,
                "partner_idx" => $user_info->PARTNER_IDX,
                'user_name' => $user_info->USER_NAME,
                'coupon_number' => $user_info->COUPON_NUMBER,
            );

            $this->session->set_userdata($session);

            $this->_login_log_insert($user_info->USER_CODE); // 로그인 로그 등록

            $domain_count = $this->db
                ->where('USER_CODE', $user_info->USER_CODE)
                ->count_all_results('USER_DOMAIN');

            if ($domain_count > 0) redirect('/mysites');
            else redirect('/addsite');

        } else {

            redirect('/sign/in');
        }

    }

    /**
     * User 정보 조회
     *
     * @param string $pk
     * @param string $user_pwd
     * @param bool $is_code $is_code 값이 true 로 넘어오면 USER_CODE 로 회원정보 조회하고 false 이면 일반 로그인 (ID/Pass 검증)
     * @return mixed
     */
    private function _user_info($pk = 'none', $user_pwd = null, $is_code = false)
    {
        if ($is_code !== true) {
            $this->db->where('USER_EMAIL', $pk);
            $this->db->where('USER_PASSWORD', md5($user_pwd));
        } else {
            $this->db->where('USER_CODE', $pk);
        }

        $query = $this->db->get('USER');

        return $query->row();
    }

    private function _api_token($token = null)
    {
        $this->db->where('login_token', $token);
        $this->db->where('hour_end >', time());
        $row = $this->db->get('api_login_token')->row();

        return $row->user_code;
    }

    private function _login_log_insert($user_code = null)
    {
        $data['LOGIN_IP'] = $this->input->ip_address();
        $data['USER_CODE'] = $user_code;
        $data['W_DATE'] = date("Y-m-d H:i:s");

        return $this->db
            ->set($data)
            ->insert('USER_LAST_LOGIN');
    }

    private function _simple_crypt($string, $action = 'e')
    {
        $secret_key = 'cloudbric_2018';
        $secret_iv = date("YmdH");

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'e') {
            $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
        } else if ($action == 'd') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

}

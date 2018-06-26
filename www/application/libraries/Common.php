<?php
class Common {

    private $c2ms = array();

    /**c2ms**/
    public function c2ms($key)
    {
        //c2ms group data 가져오기
        if(count($this->c2ms) === 0){
            $ci =& get_instance();

            //language (cookie)
            $lang = (!empty(get_cookie('lang')))? get_cookie('lang') : "en";

            //lang 쿠키를 불러와 분기 처리하는 곳이 많아 쿠키가 없는 경우는 없도록 함
            if(empty(get_cookie('lang'))) {
                $_cookie = array(
                    'name'   => 'lang',
                    'value'  => $lang,
                    'expire' => '2592000', //30일 후 만료
                    'domain' => '.cloudbric.com',
                    'path'   => '/',
                    'prefix' => '',
                    'secure' => TRUE
                );

                set_cookie($_cookie);
            }

            //파트너 콘솔 분기
            if(PARTNER_CONSOLE == 'yes') {

                $ci->db->select(array('PARTNER_IDX', 'PARTNER_NAME', 'PARTNER_NAME_VIEW', 'SERVICE_TYPE', 'PARTNER_LANG', 'ADD_WEBSITES'));
                $ci->db->where('PARTNER_CONSOLE_URL', $_SERVER['HTTP_HOST']);
                $ci->db->or_where('PARTNER_IDX', explode('.',strtoupper($_SERVER['HTTP_HOST']))[0]);
                $row = $ci->db->get('morpiers.PARTNERS')->row();

                $this->c2ms['partner_console_PARTNER_IDX'] = empty($row->PARTNER_IDX)?'A2014100001':$row->PARTNER_IDX;
                $this->c2ms['partner_console_PARTNER_NAME'] = empty($row->PARTNER_NAME)?'Cloudbric':$row->PARTNER_NAME;
                $this->c2ms['partner_console_PARTNER_NAME_VIEW'] = empty($row->PARTNER_NAME_VIEW)?$this->c2ms['partner_console_PARTNER_NAME']:$row->PARTNER_NAME_VIEW;
                $this->c2ms['partner_console_SERVICE_TYPE'] = empty($row->SERVICE_TYPE)?'White Label':$row->SERVICE_TYPE;
                $this->c2ms['partner_console_PARTNER_LANG'] = empty($row->PARTNER_LANG)?'en':$row->PARTNER_LANG;
                $this->c2ms['partner_console_ADD_WEBSITES'] = empty($row->ADD_WEBSITES)?'yes':$row->ADD_WEBSITES;

                $lang = $this->c2ms['partner_console_PARTNER_LANG']; //파트너 콘솔은 디폴트 언어 고정

                //lang 쿠키로 UI 분기도 하고 있으니 생성해 둠
                if(empty(get_cookie('lang')) || get_cookie('lang') != $lang) {

                    //쿠키명이 동일하고 중복으로 생성되어 있다면, 세밀한 도메인보다 짧은형태 상위 도메인 쿠키가 우선한다. 그래서 지워줘야 함!
                    delete_cookie('lang', 'cloudbric.com');

                    $_cookie = array(
                        'name'   => 'lang',
                        'value'  => $lang,
                        'expire' => '2592000', //30일 후 만료
                        'domain' => $_SERVER['HTTP_HOST'],
                        'path'   => '/',
                        'prefix' => '',
                        'secure' => FALSE //파트너콘솔 도메인은 https 가 아닌 경우 있으니 secure false 로 구운 쿠키만 작동 할 수 있다.
                    );

                    set_cookie($_cookie);
                }
            }

            $ci->db->select(array('c2ms_key', 'c2ms_value'));
            $ci->db->from('mlog.c2ms');
            $ci->db->where(array(
                'c2ms_lang'  =>  $lang,
                'visible' => 'Y'
            ));
            $ci->db->where_in('c2ms_group', array('common', $ci->load->uri->segment(1)));
            $result = $ci->db->get()->result();

            for($i=0; $i<count($result); $i++) {

                if(PARTNER_CONSOLE == 'yes' && $this->c2ms['partner_console_PARTNER_IDX'] != "A2014100001") { //파트너 언어 변환
                    $result[$i]->c2ms_value = str_ireplace("jp-support@pentasecurity.com","Support",$result[$i]->c2ms_value);
                    $result[$i]->c2ms_value = str_ireplace("support@pentasecurity.com","Support",$result[$i]->c2ms_value);
                    $result[$i]->c2ms_value = str_ireplace("demo.cloudbric.com","#",$result[$i]->c2ms_value);
                    $result[$i]->c2ms_value = str_ireplace("cloudbric",$this->c2ms['partner_console_PARTNER_NAME_VIEW'],$result[$i]->c2ms_value);
                    if($this->c2ms['partner_console_PARTNER_IDX'] == 'A2017090003')  $result[$i]->c2ms_value = str_ireplace("クラウドブリック",'ウェブアルゴスフォーティファイ',$result[$i]->c2ms_value); //클라우드브릭 일문
                    else $result[$i]->c2ms_value = str_ireplace("クラウドブリック",$this->c2ms['partner_console_PARTNER_NAME_VIEW'],$result[$i]->c2ms_value); //클라우드브릭 일문
                }

                $this->c2ms[$result[$i]->c2ms_key] = $result[$i]->c2ms_value;
            }
        }

        //key 가 all로 오면 c2ms 값 전체 보내기 (javascript 를 위한 c2ms json 변수 만들기 위해)
        if($key == "__all") {
            return $this->c2ms;
        }else{
            //key 값의 value  가져오기
            return (!isset($this->c2ms[$key]) || empty($this->c2ms[$key]))? $key : $this->c2ms[$key];
        }
    }

    public function paid_plan($grade)
    {
        $paid_plan_list = array(
            "CB04" => array (
                "paid_plan" => "FREE",
                "min_traffic" => 0,
                "max_traffic" => 4
            ),
            "CB10" => array (
                "paid_plan" => "FREE",
                "min_traffic" => 0,
                "max_traffic" => 4
            ),
            "CB40" => array (
                "paid_plan" => "FREE",
                "min_traffic" => 0,
                "max_traffic" => 4
            ),
            "CB100" => array (
                "paid_plan" => "FREE",
                "min_traffic" => 0,
                "max_traffic" => 4
            )
        );

        $paid_plan = null;
        $min_traffic = 0;
        $max_traffic = 0;
        if(isset($paid_plan_list[$grade])){
            $paid_plan = $paid_plan_list[$grade]["paid_plan"];
            $min_traffic = $paid_plan_list[$grade]["min_traffic"];
            $max_traffic = $paid_plan_list[$grade]["max_traffic"];
        }

        return array("plan"=>$paid_plan, "min"=>$min_traffic, "max"=>$max_traffic, "plan_list"=> $paid_plan_list);
    }

    public function get_selected_domain($domain_idx)
    {
        $CI =& get_instance();
        $selected_domain = array();

        $response = request_api_server(
            "GET",
            "/websites/" . $domain_idx,
            null,
            30
        );

        $response = json_decode($response, true);
        if($response != null) {
            if($response['result_info']['user_idx'] == $CI->session->userdata('user_idx')) {
                $selected_domain = $response['result_info'];
            } else {
                redirect('/mysites');
            }
        }

        return $selected_domain;
    }

    public function get_domain_list()
    {
        $CI =& get_instance();

        if(empty($CI->session->userdata('user_idx'))) {
            redirect('/sign/in');
        }

        $response = request_api_server(
            "GET",
            "/websites/user/" . $CI->session->userdata('user_idx') . "?limit=9999",
            null,
            30
        );

        $response = json_decode($response, true);

        return $response;
    }
    public function get_domain_group_list() {
        $CI =& get_instance();
        $site_info = array();

        if(empty($CI->session->userdata('user_idx'))) {
            return $site_info;
        }

        $response = $this->get_domain_list();

        $domainCount = count($response['result_info']);

        if($domainCount > 0) {
            for($i = 0; $i < $domainCount; $i++) {
                $domain_idx = $response['result_info'][$i]['domain_idx'];

                $group_name = '';
                if(array_key_exists('root_ip', $response['result_info'][$i]['cb_dns'])) {
                    $group_name = $response['result_info'][$i]['domain'];
                } else {
                    $subquery = '(SELECT USER_DOMAIN_GROUP_IDX FROM USER_DOMAIN_GROUP_LINK WHERE USER_DOMAIN_IDX = %d)';
                    $subquery = sprintf($subquery, $domain_idx);

                    $query = $CI->db
                        ->select('NAKED_DOMAIN')
                        ->from('USER_DOMAIN_GROUP')
                        ->where('USER_DOMAIN_GROUP_IDX', $subquery, false)
                        ->get_compiled_select();

                    $ret = $CI->db->query($query);
                    if($ret->row() != NULL) {
                        $group_name = $ret->row()->NAKED_DOMAIN;
                    }
                }

                $site_info[$group_name][$response['result_info'][$i]['domain']] = $response['result_info'][$i];
            }
        }

        return $site_info;
    }
}
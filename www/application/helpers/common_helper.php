<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**c2ms**/
if ( ! function_exists('c2ms')) {
    function c2ms($key)
    {
        $ci =& get_instance();
        return $ci->common->c2ms($key);
    }
}

if ( ! function_exists('paid_plan')) {
    function paid_plan($grade)
    {
        $ci =& get_instance();
        return $ci->common->paid_plan($grade);
    }
}

if ( ! function_exists('get_selected_domain')) {
    function get_selected_domain($domain_idx)
    {
        $ci =& get_instance();
        return $ci->common->get_selected_domain($domain_idx);
    }
}

if ( ! function_exists('get_domain_list')) {
    function get_domain_list()
    {
        $ci =& get_instance();
        return $ci->common->get_domain_list();
    }
}

if ( ! function_exists('get_domain_group_list')) {
    function get_domain_group_list()
    {
        $ci =& get_instance();
        return $ci->common->get_domain_group_list();
    }
}
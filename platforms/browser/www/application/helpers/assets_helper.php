<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('init_assets')) {
    function init_assets($type, $array = [])
    {
        $CI =& get_instance();

        $output = '';
        $queryCache = '?'.LIB_CACHE_DATE;
        $assetsType = $type;

        if($assetsType == 'css') {
            $CI->load->helper('html');
        }

        if(isset($array)) {
            foreach($array as $a){
                $isArray = is_array($a);
                $useCache = $a[1];

                if($isArray && !$useCache) {
                    $href = $a[0];
                } else {
                    $href = $a . $queryCache;
                }

                if($assetsType == 'css') {
                    $output .= link_tag($href);
                } else if($assetsType == 'js') {
                    $output .= '<script src="/'.$href.'"></script>';
                }
            }
        }

        return $output;
    }
}
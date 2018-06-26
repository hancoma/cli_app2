<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Class Layout
 *
 * Header, Footer
 */
class Layout
{
    function setLayout()
    {
        global $OUT;
        $CI =& get_instance();
        $output = $CI->output->get_output();
        $CI->yield = isset($CI->yield) ? $CI->yield : TRUE;
        $CI->layout = isset($CI->layout) ? $CI->layout : 'layout';
        if ($CI->yield === TRUE) {
            if (!preg_match('/(.+).php$/', $CI->layout)) {
                $CI->layout .= '.php';
            }
            $requested = APPPATH . 'views/layout/' . $CI->layout;
            $layout = $CI->load->file($requested, true);

            $layout = $this->setAssetsCss($layout);
            $layout = $this->setAssetsHeadScript($layout);
            $layout = $this->setAssetsEndScript($layout);

            $view = str_replace("{contents}", $output, $layout);
        } else {
            $view = $output;
        }
        $OUT->_display($view);
    }

    function setAssetsCss($layout) {
        $CI =& get_instance();
        $CI->load->helper('assets');

        if(isset($CI->assets['css'])) {
            $assetsArray = $CI->assets['css'];
            $output = init_assets('css', $assetsArray);
        } else {
            $output = '';
        }

        $setLayout = str_replace("{load_css}", $output, $layout);
        return $setLayout;
    }

    function setAssetsHeadScript($layout) {
        $CI =& get_instance();
        $CI->load->helper('assets');

        if(isset($CI->assets['head_script'])) {
            $assetsArray = $CI->assets['head_script'];
            $output = init_assets('js', $assetsArray);
        } else {
            $output = '';
        }

        $setLayout = str_replace("{load_head_script}", $output, $layout);
        return $setLayout;
    }

    function setAssetsEndScript($layout) {
        $CI =& get_instance();
        $CI->load->helper('assets');

        if(isset($CI->assets['end_script'])) {
            $assetsArray = $CI->assets['end_script'];
            $output = init_assets('js', $assetsArray);
        } else {
            $output = '';
        }

        $setLayout = str_replace("{load_end_script}", $output, $layout);
        return $setLayout;
    }
}
?>
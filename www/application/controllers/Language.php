<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Language extends CI_Controller {
    function __construct() {
        parent::__construct();
    }

    public function index($langCode) {
        $_langCode = $langCode ? $langCode : 'en';
        $refererHost = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) : '';
        $hostpos = strpos($refererHost, 'cloudbric.com');

        if($hostpos !== false) {
            $_redirectURI = $_SERVER['HTTP_REFERER'];
        } else {
            $_redirectURI = '/';
        }

        $_cookie = array(
            'name'   => 'lang',
            'value'  => $_langCode,
            'expire' => '2592000', //30일 후 만료
            'domain' => '.cloudbric.com',
            'path'   => '/',
            'prefix' => '',
            'secure' => TRUE
        );

        $this->input->set_cookie($_cookie);
        redirect($_redirectURI, 'location');
        exit;
    }
}
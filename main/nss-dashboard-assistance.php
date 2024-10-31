<?php
/* protected */
if (!defined('ABSPATH'))
    exit;

class nss_dashboard_assistance{
    //constructor
    function __construct()
    {   
        add_action('wp_enqueue_scripts',array($this,'nss_fronend_styles'));
        add_action('init',array($this,'nss_woo_active_http_falback'));
    }

    //check http
    function nss_woo_active_http_falback()
    {
        if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
        {	
			$scheme = 'http';}else{$scheme = 'https';
		}
		return $scheme;
    }

    //fronend
    function nss_fronend_styles()
    {
        wp_register_style('style-frontend-css', NSS_WOO_REG_PLUGIN_URL . 'assets/css/front-end/style-frontend.css');
        wp_enqueue_style('style-frontend-css');

        wp_enqueue_script('jquery');

        wp_register_script('style-frontend-js', NSS_WOO_REG_PLUGIN_URL . 'assets/js/front-end/style-frontend.js', array('jquery'), time(), TRUE);
        wp_enqueue_script('style-frontend-js');

        wp_register_script('style-google-recaptcha-js', $this->nss_woo_active_http_falback().'://www.google.com/recaptcha/api.js', array('jquery'), time(), TRUE);
        wp_enqueue_script('style-google-recaptcha-js');
    }
}
new nss_dashboard_assistance();
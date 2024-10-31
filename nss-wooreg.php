<?php
/*
Plugin Name: Nss Wooregistration Form
Plugin URI: https://wordpress.org/plugins/nss-wooregistration-form
Description: This plugin used for woocommerce user registration custom form. 
Author: NssTheme Team
Version: 1.0
Author URI: https://www.linkedin.com/in/saiful5721/
Text Domain: woocommerce
*/

/* protected */
if (!defined('ABSPATH'))
exit;

//define
define('NSS_WOO_REG_PLUGIN_URL', plugin_dir_url(__FILE__));

//register files
include_once("main/nss-dashboard-settings.php");
include_once("main/nss-woo-form.php");
include_once("main/nss-dashboard-assistance.php");


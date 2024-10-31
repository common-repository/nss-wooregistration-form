<?php

/* protected */
if (!defined('ABSPATH'))
exit;

class nss_dashboard_manager{
    //construct
    function __construct()
    {
        if( is_admin() ):
            add_action( 'admin_menu', array( $this, 'nss_woo_admin_meue' ) );
            add_action( 'admin_init', array( $this, 'nss_woo_page_init' ) );
        endif;
    }

    //method
    function nss_woo_admin_meue()
    {
        add_options_page(
            'Settings', 
            'Registration Setting', 
            'manage_options', 
            'nss-woo-setting-admin', 
            array( $this, 'nss_admin_page_method' )
        ); 
    }
    //admin form
    function nss_admin_page_method()
    {
        $this->options = get_option( 'nss_reg_opt_name' );
        ?>
        <form method="post" action="options.php">
            <?php
                settings_fields('nss_reg_opt_group');
                do_settings_sections('nss_reg_sett_admin');
                submit_button();
            ?>
        </form>
        <?php
    }
    //page
    function nss_woo_page_init()
    {
        register_setting(
            'nss_reg_opt_group',
            'nss_reg_opt_name',
            array($this,'sanitize')
        );

        add_settings_section(
            'nss_reg_sett_id',
            'Registration Field Settings',
            array($this,'nss_reg_section_info'),
            'nss_reg_sett_admin'
        );

        add_settings_field(
            'site_key_id',
            'Site Key Here',
            array($this, 'site_key_callback'),
            'nss_reg_sett_admin',
            'nss_reg_sett_id'
        );

        add_settings_field(
            'secret_key_id',
            'Secret Key Here',
            array($this, 'secret_key_callback'),
            'nss_reg_sett_admin',
            'nss_reg_sett_id'
        );

    }
    //sanitize field
    function sanitize($input){
        $new_input = array();
        if( isset( $input['site_key_id'] ) )
            $new_input['site_key_id'] = sanitize_text_field( $input['site_key_id'] );

        if( isset( $input['secret_key_id'] ) )
            $new_input['secret_key_id'] = sanitize_text_field( $input['secret_key_id'] );

        return $new_input;
    }
    //display setting message 
    function nss_reg_section_info(){
        print 'Enter your settings below:';
    }
    //field callback
    function site_key_callback(){
        printf(
            '<input type="text" class="regular-text" id="site_key" name="nss_reg_opt_name[site_key_id]" value="%s" />',
            isset( $this->options['site_key_id'] ) ? esc_attr( $this->options['site_key_id']) : ''
        );
    }
    //field callback
    function secret_key_callback(){
        printf(
            '<input type="text" class="regular-text" id="secret_key" name="nss_reg_opt_name[secret_key_id]" value="%s" />',
            isset( $this->options['secret_key_id'] ) ? esc_attr( $this->options['secret_key_id']) : ''
        );
    }

}
new nss_dashboard_manager();

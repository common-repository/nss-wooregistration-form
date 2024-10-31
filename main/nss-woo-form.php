<?php
/* protected */
if (!defined('ABSPATH'))
exit;

//class init
class nss_wooreg_form_design{
	//initialization 
	public function __construct()
	{
		add_action( 'woocommerce_register_form',array($this,'nss_woo_register_form'));
		add_action('woocommerce_registration_errors',array($this,'nss_woo_register_errors'),10,3);
		add_action('woocommerce_created_customer',array($this,'nss_woo_register_save'));
		add_action('init',array($this,'nss_woo_active_http_check'));
	}
	//active http check
	public function nss_woo_active_http_check()
	{
		if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on"){	
			$scheme = 'http';}else{$scheme = 'https';
		}
		return $scheme;
	}
	//create a form 
	public function nss_woo_register_form()
	{
		?>
		<p class="form-row form-row-first">
		    <label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
		    <input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
	    </p>	 
	    <p class="form-row form-row-last">
		    <label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
		    <input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
	    </p>	    
		<p class="form-row form-row-first">
			<label for="reg_billing_gender"><?php _e( 'Gender', 'woocommerce' ); ?></label>
			<select class="input-text" name="billing_gender" id="reg_billing_gender"> 
				<option <?php if ( ! empty( $_POST['billing_gender'] ) && $_POST['billing_gender'] == 'male') esc_attr_e( 'selected' ); ?> value="male"><?php _e('Male','woocommerce'); ?></option> 
				<option <?php if ( ! empty( $_POST['billing_gender'] ) && $_POST['billing_gender'] == 'female') esc_attr_e( 'selected' ); ?> value="female"><?php _e('Female','woocommerce'); ?></option>
				<option <?php if ( ! empty( $_POST['billing_gender'] ) && $_POST['billing_gender'] == 'other') esc_attr_e( 'selected' ); ?> value="other"><?php _e('Other','woocommerce'); ?></option>
        	</select> 
		</p> 			
		<p class="form-row form-row-last">
		    <label for="reg_billing_phone"><?php _e( 'Phone', 'woocommerce' ); ?></label>
		    <input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" value="<?php if ( ! empty( $_POST['billing_phone'] ) ) esc_attr_e( $_POST['billing_phone'] ); ?>" />
		</p> 
		<p class="form-row form-row-wide">
			<label for="find_where"><?php _e( 'Where did you find us?', 'woocommerce' ); ?></label>
			<select name="find_where" id="find_where">
				<?php
					$find_where_options = array(
                        'goo' => esc_html__('Google', 'woocommerce'),
                        'fcb' => esc_html__('Facebook', 'woocommerce'),
                        'Twitter' => esc_html__('Twitter', 'woocommerce')
                    );
					$find_where_options_get = (isset($find_where_options) && is_array($find_where_options)) ? $find_where_options : '';
					foreach($find_where_options_get as $fwo => $label)
					{
						echo '<option '. selected($find_where_options_get, $fwo) .' value="'.$fwo.'">'. $label .'</option>';
					}
				?>
			</select>
		</p>
		<?php 
		$google_site_key = get_option( 'nss_reg_opt_name' );
		$site_key = (isset($google_site_key['site_key_id']) && ($google_site_key['site_key_id']) !='') ? $google_site_key['site_key_id'] : '';
		$secret_key = (isset($google_site_key['secret_key_id']) && ($google_site_key['secret_key_id']) !='') ? $google_site_key['secret_key_id'] : '';
		?>
		<div class="g-recaptcha" data-sitekey="<?php echo esc_html($site_key); ?>"></div>
		<?php
		$captcha = (isset($_POST['g-recaptcha-response']) && ($_POST['g-recaptcha-response']) !='') ? sanitize_text_field($_POST['g-recaptcha-response']) : '';		
		$url = $this->nss_woo_active_http_check().'://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$captcha;
		$response = wp_remote_get($url);
		$responseData = json_decode(json_encode($response),true);
		
		if($responseData["response"]["message"] == 'OK')
		{
			//echo 'Your contact request have submitted successfully.';
		}else{
			echo 'Robot verification failed, please try again.';
		}		
		?>
    	<div class="clear"></div>
		<?php
	}
	//error 
	public function nss_woo_register_errors($errors, $username, $email){

		if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
        	$errors->add( 'billing_first_name_error', __( '<strong>Error</strong>: First name is required!', 'woocommerce' ) );
	    }
	    if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
	        $errors->add( 'billing_last_name_error', __( '<strong>Error</strong>: Last name is required!.', 'woocommerce' ) );
	    }   
	    if ( isset( $_POST['g-recaptcha-response'] ) && empty( $_POST['g-recaptcha-response'] ) ) {
	        $errors->add( 'g-recaptcha-response_error', __( '<strong>Error</strong>: Recaptha is required!.', 'woocommerce' ) );
	    }   

	    return $errors;
	}
	//save data
	public function nss_woo_register_save($customer_id)
	{
		if ( isset( $_POST['billing_first_name'] ) ) {
	        update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
	        update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name']) );
	    }

	    if ( isset( $_POST['billing_last_name'] ) ) {
	        update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
	        update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name']) );
		}
		
	    if (isset($_POST['billing_gender'])) {
			update_user_meta($customer_id, 'billing_gender', sanitize_text_field($_POST['billing_gender']));
		}

	    if ( isset( $_POST['billing_phone'] ) ) {
	        update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
	        update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
	    }

		if ( isset( $_POST['find_where'] ) ) {
        	update_user_meta( $customer_id, 'find_where', sanitize_text_field($_POST['find_where'] ) );
		}  

	    if ( isset( $_POST['g-recaptcha-response'] ) ) {
        	update_user_meta( $customer_id, 'g-recaptcha-response', sanitize_text_field($_POST['g-recaptcha-response'] ) );
		}  


	}
} 
new nss_wooreg_form_design();
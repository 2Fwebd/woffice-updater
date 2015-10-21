<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

class FW_Extension_Woffice_Updater extends FW_Extension {
	/**
	 * @internal
	 */
	public function _init() {
		add_action('fw_extension_settings_form_saved:woffice-updater', array($this, 'woffice_tf_check'));
	}
	
	/**
	 * CHECK IF THE PURCHASE CODE IS VALID
	 */
	public function woffice_tf_check() {
		
		/* We get the data from the OPTIONS */
		$tf_username = fw_get_db_ext_settings_option('woffice-updater', 'tf_username');
		$tf_purchasecode = fw_get_db_ext_settings_option('woffice-updater', 'tf_purchasecode');
		
		if (empty($tf_username) || empty($tf_purchasecode)) {
			return;
		} 
		
		$request_string = array(
			'body' => array( 
				'action' => 'check_purchase', 
			    'username' => htmlspecialchars($tf_username),
			    'purchase_code' => htmlspecialchars($tf_purchasecode),
			)
		);
		/* We use our API */
		$raw_response = wp_remote_post( 'http://alka-web.com/woffice-updater/theme-updater.php', $request_string );
      
		/* We check */
		$response = null;
    	if( !is_wp_error($raw_response) && ($raw_response['response']['code'] == 200) ) {
    		$response = $raw_response['body'];
    	}
    	if( !empty($response) ) {
    		if ($response == 'true') {
	    		/*If it works we save that data in the options table*/
				update_option('woffice_license','checked');
				return true;
    		}
    		else {
	    		update_option('woffice_license','not-checked');
				return;
    		}
    	}
		
	}
	
}
<?php 
	
/**
*************************************************
*
*  Check the purchase code
* 
*************************************************
*/
function woffice_check_purchase ($user, $purchase_code) {
	
	if (empty($user) || empty($purchase_code)) {
		return false;
	} 
	
	/* Our data for the API */
	$api_key = "xxxx"; // Envato API key
	$username = "xxx"; // Envato username
	$theme_ID = "xxxx"; // Theme's ID in Themeforest
	
	/* Open cURL channel */
	$ch = curl_init();
  
    /* Set cURL options */
    $agent = 'Woffice-Updater';
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    curl_setopt($ch, CURLOPT_URL, "http://marketplace.envato.com/api/edge/". $username ."/". $api_key ."/verify-purchase:". $purchase_code .".json");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  
	/* Decode returned JSON */
	$output = json_decode(curl_exec($ch), true);
  
	/* Close Channel */
	curl_close($ch);
  
	/* We check */
	if(isset($output['verify-purchase']['buyer'])){
		
		if ($output['verify-purchase']['buyer'] == $user && $output['verify-purchase']['item_id'] == $theme_ID) {
			return true;
		}
		else {
			return false;
		}
		
	}
	else {
		return false;
	}
	
}
/**
*************************************************
*
*  Check if the license is already activated
* 
*************************************************
*/
function woffice_check_license_activation ($user, $purchase_code) {
	
	/*Licenses file*/
	$file_licenses = "licenses.json";
	
	/*Get the php array from Json*/
	$licenses_content = file_get_contents($file_licenses);
	$json_licenses = json_decode($licenses_content, true);
	/*Prevent memory leaks for large json.*/
	unset($licenses_content);
	
	$present = false; 
	
	/*We check*/
	foreach($json_licenses as $user_key => $license) {
		
		/* We search for the same username */
		if ($user == $user_key && $license == $purchase_code) {
			/* If it already exists */
			$present = true; 
		}
		
	}
	
	if ($present == false ) {
		
		//insert data here
		$json_licenses[$user] = $purchase_code;
		//save the file
		file_put_contents($file_licenses,json_encode($json_licenses));
		unset($new_license);//release memory
		
		return 'license_added';
		
	} 
	else {
		return 'license_already';
	}
		
}
/**
*************************************************
*
*  We check the form now 
* 
*************************************************
*/
if ($_POST['source'] == "from-form") {
	
	$result = 'default';
	
	if (isset($_POST['username']) && isset($_POST['purchase_code'])) {
		
		$tf_user = htmlspecialchars($_POST['username']);
		$tf_purchase_code = htmlspecialchars($_POST['purchase_code']);
		
		$check = woffice_check_purchase($tf_user,$tf_purchase_code);
			
		if ($check == true) {
			$result = 'success';
			
			$data = file_get_contents('http://YOUR-URL.com/json-data.json');
			$data_decoded = json_decode($data, true); 
			foreach ($data_decoded as $update) {
				
				if ($update['new_version'] == $_POST['version']) {
					
					$theme_file = $update['package'];
					/* We start the download */
					header('Content-type: application/pdf');
					header('Content-Disposition: attachment; filename="' . basename($theme_file) . '"');
					header('Content-Transfer-Encoding: binary');
					readfile($theme_file);
					/*header("Content-Disposition: attachment; filename=\"" . basename($theme_file) . "\"");
					header("Content-Type: application/force-download");
					header("Content-Length: " . filesize($theme_file));
					header("Connection: close");*/
					header("Location: index.php?result=$result");
					
				}
				
			}
			
		}
		else {
			$result = 'error';
		}
		
	}
	else {
		$result = 'empty';
	}
	
	header("Location: index.php?result=$result");
	
}
/**
*************************************************
*
*  We check if the user has purchased (for Woffice extension's settings page)
* 
*************************************************
*/
if (isset($_POST['action']) && $_POST['action'] == 'check_purchase'){
	
	$tf_user = $_POST['username'];
	$tf_purchase_code = $_POST['purchase_code'];
	$check = woffice_check_purchase($tf_user,$tf_purchase_code);
	if ($check == true) {
		// We check if the license is already activated : 
		$activated = woffice_check_license_activation ($tf_user, $tf_purchase_code);
		// We return to the page so the option is updated
		if ($activated == 'license_added') {
			print 'true';
		}
		else {
			print 'false';
		}
		exit;
	}
	else {
		print 'false';
		exit;
	} 
	
}
/**
*************************************************
*
*  We check if the theme needs do be upated
* 
*************************************************
*/
if (isset($_POST['action']) && $_POST['action'] == 'check_updated'){
	
	$woffice_version = $_POST['version'];
	$data = file_get_contents('YOUR-URL.com/json-data.json');
	$data_decoded = json_decode($data, true); 
	$need_update = false;
	$update_data = array();
	foreach ($data_decoded as $update) {
		if( version_compare( $woffice_version, $update['new_version'], '<' ) ) {
			$need_update = 'true';
			$update_data = serialize($update);
		}
	}
	
	if ($need_update == true) {
		print_r($update_data);
		exit;
	}
	else {
		exit;
	} 
	
}



?>
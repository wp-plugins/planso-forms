<?php

require_once(dirname(__FILE__).'/vars.inc.php');

$psform = get_post( $_POST['psfb_form_id'] );
	
$j = json_decode($psform->post_content);
if(!function_exists('validate_url')){
	function validate_url($url){
		if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)) {
	  	return false;
		}
		return true;
	}
}

/**** ANTI SPAM ****/

//honeypot
if(isset($_POST['psfb_hon_as']) && !empty($_POST['psfb_hon_as'])){
	if($_POST['psfb_hon_as']!=''){
		//honeypot is not empty - must be spam
		$_SESSION['psfb_errors'][$_POST['psfb_form_id']] = array('psfb_message' => __('Honeypot is not empty','psfbldr') );
		wp_safe_redirect( $_POST['psfb_pageurl'].'#planso_forms_'.$_POST['psfb_form_id'].'_'.$_POST['psfb_form_cnt'] );
	}
}

if(isset($_SESSION['psfb_anti_spam'][$_POST['psfb_form_id']][$_POST['psfb_cnt_check']])){
	//psfb_hon_as2
	if( $_SESSION['psfb_anti_spam'][$_POST['psfb_form_id']][$_POST['psfb_cnt_check']] == $_POST['psfb_hon_as2'] ){
		//session and value are equal - seems to be a user
	} else {
		//either no session available or 
		if(strlen($_POST['psfb_hon_as2']) != 32){
			//md5 was changed is definitly not 32 chars and therefore spam
			$_SESSION['psfb_errors'][$_POST['psfb_form_id']] = array('psfb_message' => __('Salt missmatch','psfbldr') );
			wp_safe_redirect( $_POST['psfb_pageurl'].'#planso_forms_'.$_POST['psfb_form_id'].'_'.$_POST['psfb_form_cnt'] );
		}
	}
}
if(isset($j->javascript_antispam) && $j->javascript_antispam==true){
	if( !isset($_POST['psfb_js_as']) || empty($_POST['psfb_js_as'])){
		//javascript antispam field not submitted - spam
		
		$_SESSION['psfb_errors'][$_POST['psfb_form_id']] = array('psfb_message' => __('Anti spam field was omitted','psfbldr') );
		wp_safe_redirect( $_POST['psfb_pageurl'].'#planso_forms_'.$_POST['psfb_form_id'].'_'.$_POST['psfb_form_cnt'] );
	} else {
		if($_POST['psfb_js_as']!=$_POST['psfb_hon_as2']){
			//fields dont match - spam
			$_SESSION['psfb_errors'][$_POST['psfb_form_id']] = array('psfb_message' => __('Anti spam fields dont match','psfbldr') );
			wp_safe_redirect( $_POST['psfb_pageurl'].'#planso_forms_'.$_POST['psfb_form_id'].'_'.$_POST['psfb_form_cnt'] );
		}
	}
}


/****** VALIDATION *********/
//if errors register values in session and redirect
$errors = array();
$mail_replace = array();
$file_keys = array();
$cnt = 1;
if(isset($j->fields) && count($j->fields)>0){
	foreach($j->fields as $row){
		foreach($row as $col){
			$cnt ++;
			$mytype = $col->type;
			$fieldinfo = $fieldtypes[$mytype];
			$fieldtype = $fieldinfo['type'];
			
			if(!isset($col->name) || empty($col->name)){
				$col->name = $col->label;
			}
			$col->name = preg_replace("/[^A-Za-z0-9_]+/", '_', $col->name);
			
			if(trim($col->name)==''){
				$col->name = 'field'.$cnt;
			}
			$post_value = '';
			if(isset($_POST[$col->name])){
				$post_value = $_POST[$col->name];
				if(is_array($post_value)){
					if(count($post_value)>0){
						$tmp = implode(', ',$post_value);
						$post_value = $tmp;
					} else {
						$post_value = '';
					}
				}
				$post_value = trim($post_value);
			  $post_value = stripslashes($post_value);
			  $post_value = htmlspecialchars($post_value);
			}
			
			if( isset($col->required) && ($col->required==true || $col->required=='required' || $col->required=='true')){
				
				if(strstr($col->type,'file')){
					//file && required
					if(is_array($_FILES[$col->name]['tmp_name'])){
						foreach($_FILES[$col->name]['tmp_name'] as $key=>$val){
							if($_FILES[$col->name]['error'][$key]!=UPLOAD_ERR_OK){
								$errors[$col->name]['error'] = true;
								$errors[$col->name]['message'] = __('File upload error','psfbldr');
							}
						}
					} else {
						if($_FILES[$col->name]['error']!=UPLOAD_ERR_OK){
							$errors[$col->name]['error'] = true;
							$errors[$col->name]['message'] = __('File upload error','psfbldr');
						}
					}
					
				} else if(strstr($col->type,'checkbox')){
					if($post_value==''){
						//fehler - feld muss ausgefüllt sein
						$errors[$col->name]['error'] = true;
						$errors[$col->name]['required'] = true;
						$errors[$col->name]['message'] = __('This field is required','psfbldr');
					}
					
				} else {
					//all other fields
					if(!isset($post_value) || empty($post_value)){
						//fehler - feld muss ausgefüllt sein
						$errors[$col->name]['error'] = true;
						$errors[$col->name]['required'] = true;
						$errors[$col->name]['message'] = __('This field is required','psfbldr');
					}
					
					if($fieldtype=='email' && !is_email($post_value)){
						$errors[$col->name]['error'] = true;
						$errors[$col->name]['message'] = __('Invalid E-Mail','psfbldr');
					}
					
					if($fieldtype=='url' && !validate_url($post_value)){
						$errors[$col->name]['error'] = true;
						$errors[$col->name]['message'] = __('Invalid URL','psfbldr');
					}
				}
			}//end required
			
			$mail_replace[$col->name] = $post_value;
			if(strstr($col->type,'file')){
				$file_keys[] = $col->name;
			} else {
				$zmail_replace[$col->name] = $post_value;
			}
		}
	}
}



if(count($errors)<1){
	
	$page_detail_atts = array(
		'id' => $psform->ID,
		'permalink' => $_POST['psfb_pageurl'],
		'title' => $psform->post_title,
		'mail_replace' => $mail_replace,
		'zmail_replace' => $zmail_replace,
		'j' => $j
	);
	
	do_action( 'psfb_submit_after_error_check_success',$page_detail_atts );
	
	
	//send mails and notifications
	require_once( dirname(__FILE__).'/submit_notifications.php');
	
	
	
	
	do_action( 'psfb_submit_before_redirect_successfull',$page_detail_atts );
	
	if(isset($j->thankyou_page_url) && validate_url($j->thankyou_page_url)){
		wp_redirect( $j->thankyou_page_url );
	} else {
		$_SESSION['psfb_success'][$_POST['psfb_form_id']] = true;
		wp_safe_redirect( $_POST['psfb_pageurl'].'#planso_forms_'.$_POST['psfb_form_id'].'_'.$_POST['psfb_form_cnt'] );
	}
	exit();
	
}//no errors
else {
	do_action( 'psfb_submit_before_redirect_error' );
	//error: register in session
	$_SESSION['psfb_errors'][$_POST['psfb_form_id']] = $errors;
	$_SESSION['psfb_values'][$_POST['psfb_form_id']] = $_POST;
	wp_safe_redirect( $_POST['psfb_pageurl'].'#planso_forms_'.$_POST['psfb_form_id'].'_'.$_POST['psfb_form_cnt'] );
	exit();
}

?>
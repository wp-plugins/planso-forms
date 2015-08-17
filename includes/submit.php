<?php

require_once(dirname(__FILE__).'/vars.inc.php');

if(!isset($submitted_j)){
	$psform = get_post( $_POST['psfb_form_id'] );
		
	$j = json_decode($psform->post_content);
} else {
	$j = $submitted_j;
}
if(!function_exists('validate_url')){
	function validate_url($url){
		if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)) {
	  	return false;
		}
		return true;
	}
}

/**** ANTI SPAM ****/

if(!isset($submitted_j)){
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
}//ende !isset($submitted_j)

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
			$post_indexes = array();
			$post_types = array();
			if(isset($col->field_options) && isset($col->field_options->names)){
				$fcnt = 0;
				foreach($col->field_options->names as $name){
					$name = preg_replace("/[^A-Za-z0-9_]+/", '_', $name);
					if(trim($name)==''){
						$name = 'field'.$cnt.'_'.$fcnt;
					}
					$post_indexes[] = $name;
					$post_types[] = $col->type;
					$fcnt ++;
				}
				
			} else {
				
			}
			
			if(!isset($col->name) || empty($col->name)){
				$col->name = $col->label;
			}
			if(count($post_indexes)<1){
				$col->name = preg_replace("/[^A-Za-z0-9_]+/", '_', $col->name);
				
				if(trim($col->name)==''){
					$col->name = 'field'.$cnt;
				}
				$post_indexes[] = $col->name;
				$post_types[] = $col->type;
			} 
			
      
			foreach($post_indexes as $i => $pidx){
				
				$post_value = '';
				if(isset($_POST[$pidx])){
					$post_value = $_POST[$pidx];
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
				
				if( isset($col->required) && $post_value!='c34774bc9ee05f717f36af1bd98ac0939120ccc5592cf7863d0050afc2652ed5' && ($col->required==true || $col->required=='required' || $col->required=='true')){
					
					if(strstr($post_types[$i],'file')){
						//file && required
						if(is_array($_FILES[$pidx]['tmp_name'])){
							foreach($_FILES[$pidx]['tmp_name'] as $key=>$val){
								if($_FILES[$pidx]['error'][$key]!=UPLOAD_ERR_OK){
									$errors[$pidx]['error'] = true;
									$errors[$pidx]['message'] = __('File upload error','psfbldr');
								}
							}
						} else {
							if($_FILES[$pidx]['error']!=UPLOAD_ERR_OK){
								$errors[$pidx]['error'] = true;
								$errors[$pidx]['message'] = __('File upload error','psfbldr');
							}
						}
						
					} else if(strstr($post_types[$i],'checkbox')){
						if($post_value==''){
							//fehler - feld muss ausgefüllt sein
							$errors[$pidx]['error'] = true;
							$errors[$pidx]['required'] = true;
							$errors[$pidx]['message'] = __('This field is required','psfbldr');
						}
						
					} else {
						//all other fields
						if(!isset($post_value) || empty($post_value)){
							//fehler - feld muss ausgefüllt sein
							$errors[$pidx]['error'] = true;
							$errors[$pidx]['required'] = true;
							$errors[$pidx]['message'] = __('This field is required','psfbldr');
						}
						
						if($post_types[$i]=='email' && !is_email($post_value)){
							$errors[$pidx]['error'] = true;
							$errors[$pidx]['message'] = __('Invalid E-Mail','psfbldr');
						}
						
						if($post_types[$i]=='url' && !validate_url($post_value)){
							$errors[$pidx]['error'] = true;
							$errors[$pidx]['message'] = __('Invalid URL','psfbldr');
						}
					}
				}//end required
				if($post_value == 'c34774bc9ee05f717f36af1bd98ac0939120ccc5592cf7863d0050afc2652ed5'){
					$post_value = '';
				}
				$mail_replace[$pidx] = $post_value;
				if(strstr($post_types[$i],'file')){
					$file_keys[] = $pidx;
				} else {
					$zmail_replace[$pidx] = $post_value;
				}
				
			}//end post_indexes each
			
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
	
	
	/*
	* Run custom validation filter, should return true if valid, or an error message string when invalid
	*/
	$psfb_custom_validation_passed = apply_filters( 'psfb_validate_form_request', true, $page_detail_atts );
	if( true !==  $psfb_custom_validation_passed){
		$_SESSION['psfb_errors'][$_POST['psfb_form_id']] = array('psfb_message' => (string)$psfb_custom_validation_passed);
		wp_safe_redirect( $_POST['psfb_pageurl'].'#planso_forms_'.$_POST['psfb_form_id'].'_'.$_POST['psfb_form_cnt'] );
		exit;
	}

	
	do_action( 'psfb_submit_after_error_check_success',$page_detail_atts );
	
	
	//send mails and notifications
	require_once( dirname(__FILE__).'/submit_notifications.php');
	
	
	
	
	do_action( 'psfb_submit_before_redirect_successfull',$page_detail_atts );
	
	if(isset($j->thankyou_page_url) && validate_url($j->thankyou_page_url)){
		
		if(!empty($mail_replace)){
			foreach($mail_replace as $k=>$v){
				$j->thankyou_page_url = str_replace('['.$k.']',$v,$j->thankyou_page_url);
			}
		}
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
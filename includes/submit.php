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
		wp_safe_redirect( $_POST['pageurl'].'#psform'.$_POST['psfb_form_id'] );
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
			wp_safe_redirect( $_POST['pageurl'].'#psform'.$_POST['psfb_form_id'] );
		}
	}
}
if(isset($j->javascript_antispam) && $j->javascript_antispam==true){
	if( !isset($_POST['psfb_js_as']) || empty($_POST['psfb_js_as'])){
		//javascript antispam field not submitted - spam
			wp_safe_redirect( $_POST['pageurl'].'#psform'.$_POST['psfb_form_id'] );
	} else {
		if($_POST['psfb_js_as']!=$_POST['psfb_hon_as2']){
			//fields dont match - spam
			wp_safe_redirect( $_POST['pageurl'].'#psform'.$_POST['psfb_form_id'] );
		}
	}
}


/****** VALIDATION *********/
//if errors register values in session and redirect
$errors = array();
$mail_replace = array();
$file_keys = array();
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
			
			if(isset($_POST[$col->name])){
				if(is_array($_POST[$col->name])){
					$tmp = implode(', ',$_POST[$col->name]);
					$_POST[$col->name] = $tmp;
				}
				$_POST[$col->name] = trim($_POST[$col->name]);
			  $_POST[$col->name] = stripslashes($_POST[$col->name]);
			  $_POST[$col->name] = htmlspecialchars($_POST[$col->name]);
			}
			
			if( isset($col->required) && ($col->required==true || $col->required=='required')){
				
				if(!isset($_POST[$col->name]) || empty($_POST[$col->name])){
					//fehler - feld muss ausgefüllt sein
					$errors[$col->name]['error'] = true;
					$errors[$col->name]['required'] = true;
					$errors[$col->name]['message'] = __('This field is required','psfbldr');
				}
				
				if($fieldtype=='email' && !is_email($_POST[$col->name])){
					$errors[$col->name]['error'] = true;
					$errors[$col->name]['message'] = __('Invalid E-Mail','psfbldr');
				}
				
				if($fieldtype=='url' && !validate_url($_POST[$col->name])){
					$errors[$col->name]['error'] = true;
					$errors[$col->name]['message'] = __('Invalid URL','psfbldr');
				}
			}//end required
			
			$mail_replace[$col->name] = $_POST[$col->name];
			if(strstr($col->type,'file')){
				$file_keys[] = $col->name;
			}
		}
	}
}

if(count($errors)<1){
	
	if(isset($j->mails)){
		if(isset($j->mails->admin_mail)){
			$mail = $j->mails->admin_mail;
			
			$subject = $mail->subject;
			$content = $mail->content;
			$reply_to = $mail->reply_to;
			$from_name = $mail->from_name;
			$from_email = $mail->from_email;
			$recipients = implode(';',$mail->recipients);
			$bcc = implode(';',$mail->bcc);
			
			foreach($mail_replace as $k=>$v){
				$subject = str_replace('['.$k.']',$v,$subject);
				$content = str_replace('['.$k.']',$v,$content);
				$reply_to = str_replace('['.$k.']',$v,$reply_to);
				$from_name = str_replace('['.$k.']',$v,$from_name);
				$from_email = str_replace('['.$k.']',$v,$from_email);
				$recipients = str_replace('['.$k.']',$v,$recipients);
				$bcc = str_replace('['.$k.']',$v,$bcc);
			}
			
			$content .= <<<EOF




======================================
Powered by PlanSo Form Builder (free version)
http://www.planso.de
EOF;
			$admin_content = $content;
			//$attachments = array( WP_CONTENT_DIR . '/uploads/file_to_attach.zip' );
			$attachments = array();
			$has_attachments = false;
			if(count($file_keys)>0){
				$wp_upload_dir = wp_upload_dir();
				if(!is_dir($wp_upload_dir['basedir'].'/ps-form-builder/')){
					mkdir( $wp_upload_dir['basedir'].'/ps-form-builder/',0777 );
				}
				//dirname(dirname(__FILE__)) == plugin root
				//$upload_dir = dirname(dirname(__FILE__)).'/uploads/'.session_id();
				//mkdir( $upload_dir,0777 );
				
				$upload_dir = $wp_upload_dir['basedir'].'/ps-form-builder/'.session_id();
				mkdir( $upload_dir,0777 );
				
				foreach($file_keys as $f){
					if(isset($_FILES[$f])){
						$has_attachments = true;
						if(is_array($_FILES[$f]['tmp_name'])){
							foreach($_FILES[$f]['tmp_name'] as $key => $tmp_name){
						    $file_name = $_FILES[$f]['name'][$key];
						    $file_size =$_FILES[$f]['size'][$key];
						    $file_tmp =$_FILES[$f]['tmp_name'][$key];
						    $file_type=$_FILES[$f]['type'][$key];  
						    
						    
						    move_uploaded_file($file_tmp, $upload_dir.'/'.$file_name);
						    $attachments[] = $upload_dir.'/'.$file_name;
							}
						} else {
							
							$file_name = $_FILES[$f]['name'];
					    $file_size =$_FILES[$f]['size'];
					    $file_tmp =$_FILES[$f]['tmp_name'];
					    $file_type=$_FILES[$f]['type'];  
					    
					    
					    move_uploaded_file($file_tmp, $upload_dir.'/'.$file_name);
					    $attachments[] = $upload_dir.'/'.$file_name;
					    
						}
					}
				}
			}
			
			$headers = array();
			

			
			if(!isset($from_email) || empty($from_email) || !is_email(trim($from_email))){
				$headers[] = 'From: "'.get_option( 'blogname', __('Your Wordpress Blog','psfbldr') ).'" <'.get_option( 'admin_email', 'form-builder@planso.de' ).'>';
			} else {
				if(trim($from_name)!=''){
					$headers[] = 'From: "'.trim($from_name).'" <'.trim($from_email).'>';
				} else {
					$headers[] = 'From: '.trim($from_email);
				}
			}
			
  		//$headers[] = 'Content-Type: text/html; charset='.get_option( 'blog_charset', 'UTF-8');
			$headers[] = 'Content-Type: text/plain; charset='.get_option( 'blog_charset', 'UTF-8');
			//$headers[] = 'Cc: John Q Codex <jqc@wordpress.org>';
			//$headers[] = 'Cc: iluvwp@wordpress.org'; // note you can just use a simple email address
			if(strstr($bcc,';')){
				$bcca = eplode(';',$bcc);
			} else {
				$bcca = array($bcc);
			}
			foreach($bcca as $b){
				if(trim($b)!=''){
					$headers[] = 'Bcc: '.trim($b);
				}
			}
			if(is_email($reply_to)){
				$headers[] = 'Reply-To: '.trim($reply_to);
			}
			
			if($has_attachments && count($attachments)>0){
				wp_mail( explode(';',$recipients), $subject, $content, $headers, $attachments );
				foreach($attachments as $f){
					unlink($f);
				}
				rmdir($upload_dir);
			} else {
				wp_mail( explode(';',$recipients), $subject, $content, $headers);//, $attachments );
			}
		}
		
		if(isset($j->mails->user_mail)){
			$mail = $j->mails->user_mail;
			
			
			$subject = $mail->subject;
			$content = $mail->content;
			$reply_to = $mail->reply_to;
			$from_name = $mail->from_name;
			$from_email = $mail->from_email;
			$recipients = implode(';',$mail->recipients);
			$bcc = implode(';',$mail->bcc);
			
			foreach($mail_replace as $k=>$v){
				$subject = str_replace('['.$k.']',$v,$subject);
				$content = str_replace('['.$k.']',$v,$content);
				$reply_to = str_replace('['.$k.']',$v,$reply_to);
				$from_name = str_replace('['.$k.']',$v,$from_name);
				$from_email = str_replace('['.$k.']',$v,$from_email);
				$recipients = str_replace('['.$k.']',$v,$recipients);
				$bcc = str_replace('['.$k.']',$v,$bcc);
			}
			
			$content .= <<<EOF




======================================
Powered by PlanSo Form Builder (free version)
http://www.planso.de
EOF;
			
			$headers = array();
			if(!isset($from_email) || empty($from_email) || !is_email(trim($from_email))){
				$headers[] = 'From: "'.get_option( 'blogname', __('Your Wordpress Blog','psfbldr') ).'" <'.get_option( 'admin_email', 'form-builder@planso.de' ).'>';
			} else {
				if(trim($from_name)!=''){
					$headers[] = 'From: "'.trim($from_name).'" <'.trim($from_email).'>';
				} else {
					$headers[] = 'From: '.trim($from_email);
				}
			}
			//$attachments = array( WP_CONTENT_DIR . '/uploads/file_to_attach.zip' );
  		//$headers[] = 'Content-Type: text/html; charset='.get_option( 'blog_charset', 'UTF-8');
			$headers[] = 'Content-Type: text/plain; charset='.get_option( 'blog_charset', 'UTF-8');
			//$headers[] = 'Cc: John Q Codex <jqc@wordpress.org>';
			//$headers[] = 'Cc: iluvwp@wordpress.org'; // note you can just use a simple email address
			if(strstr($bcc,';')){
				$bcca = eplode(';',$bcc);
			} else {
				$bcca = array($bcc);
			}
			foreach($bcca as $b){
				if(trim($b)!=''){
					$headers[] = 'Bcc: '.trim($b);
				}
			}
			
			if(is_email($reply_to)){
				$headers[] = 'Reply-To: '.trim($reply_to);
			}
			wp_mail( explode(';',$recipients), $subject, $content, $headers);//, $attachments );
		}
		
		
	}
	
	if(isset($j->pushover_user)){
		curl_setopt_array(
			$ch = curl_init(), 
			array(
			  CURLOPT_URL => "https://api.pushover.net/1/messages.json",
			  CURLOPT_POSTFIELDS => array(
			    "token" => "aM5ZE5xttJQDnNkABYjwLdxpBUQzBv",
			    "user" => $j->pushover_user,
			    "message" => $admin_content,
			    "title" => $psform->post_title.' '.__('has been submitted','psfbldr'),
			    "url" =>  $_POST['pageurl'],
			    "url_title" => get_option( 'blogname', __('Your Wordpress Blog','psfbldr'))
			  ),
			  CURLOPT_SAFE_UPLOAD => true,
			  CURLOPT_RETURNTRANSFER => true,
			)
		);
		$result = curl_exec($ch);
		curl_close($ch);
	}
	
	
	if(isset($j->thankyou_page_url) && validate_url($j->thankyou_page_url)){
		wp_redirect( $j->thankyou_page_url );
	} else {
		wp_safe_redirect( $_POST['pageurl'].'#psform'.$_POST['psfb_form_id'] );
	}
	exit();
	
}//no errors
else {
	//error: register in session
	$_SESSION['psfb_errors'][$_POST['psfb_form_id']] = $errors;
	$_SESSION['psfb_values'][$_POST['psfb_form_id']] = $_POST;
	wp_safe_redirect( $_POST['pageurl'].'#psform'.$_POST['psfb_form_id'] );
	exit();
}

?>
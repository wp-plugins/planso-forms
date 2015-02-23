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
				if(!strstr($col->type,'file')){
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
				} else {
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
				}
			}//end required
			
			$mail_replace[$col->name] = $_POST[$col->name];
			if(strstr($col->type,'file')){
				$file_keys[] = $col->name;
			} else {
				$zmail_replace[$col->name] = $_POST[$col->name];
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
			foreach($psfb_mail_tracking_map as $k=>$v){
				$content = str_replace('['.$v.']',$_REQUEST[$k],$content);
			}
			
			/********** Automatischer Mail Body **********/
			if(trim($content)==''){
				if(isset($mail->html_mail) && $mail->html_mail==true){
					$content .= '<h1>'.$subject.'</h1>';
		  		$content .= '<table>';
		  	}
				foreach($mail_replace as $k=>$v){
					if(!empty($v) && trim($v)!=''){
						if(isset($mail->html_mail) && $mail->html_mail==true){
			  			$content .= '<tr><td>'.$k.'</td><td>'.$v.'</td></tr>';
			  		} else {
							$content .= $k.': '.$v."\r\n";
						}
					}
				}
				
				if(isset($mail->html_mail) && $mail->html_mail==true){
					foreach($psfb_mail_tracking_map as $k=>$v){
						$content .= '<tr><td>'.ucwords($v).'</td><td>'.$_REQUEST[$k].'</td></tr>';
					}
				}
				
				if(isset($mail->html_mail) && $mail->html_mail==true){
		  		$content .= '</table>';
		  	}
			}
			
			
			//vars.inc.php
			if(isset($mail->html_mail) && $mail->html_mail==true){
  			//$content .= PSFB_POWERED_BY_HTML;
  		} else {
				$content .= PSFB_POWERED_BY_TXT;
			}
			
			
			if(isset($mail->html_mail) && $mail->html_mail==true){
  			$email_template = PSFB_HTML_EMAIL_TEMPLATE;
  			
  			$email_template = str_replace('<!-- mail_subject -->',$subject,$email_template);
  			$email_template = str_replace('<!-- mail_body -->',$content,$email_template);
  			$email_template = str_replace('<!-- mail_charset -->',get_option( 'blog_charset', 'UTF-8'),$email_template);
  			if(isset($j->link_love) && $j->link_love==true){
  				$email_template = str_replace('<!-- mail_footer -->',PSFB_POWERED_BY_HTML,$email_template);
  			}
  			$content = $email_template;
  		}
			
			$admin_content = $content;
			$admin_content = apply_filters('psfb_submit_admin_content',$content);
			
			
			$attachments = array();
			$zattachments = array();
			$has_attachments = false;
			if(count($file_keys)>0){
				$wp_upload_dir = wp_upload_dir();
				if(!is_dir($wp_upload_dir['basedir'].'/planso-forms/')){
					mkdir( $wp_upload_dir['basedir'].'/planso-forms/',0777 );
				}
				
				$upload_dir = $wp_upload_dir['basedir'].'/planso-forms/'.session_id();
				$upload_url = $wp_upload_dir['baseurl'].'/planso-forms/'.session_id();
				if(!is_dir($upload_dir)){
					mkdir( $upload_dir,0777 );
				}
				
				foreach($file_keys as $f){
					if(isset($_FILES[$f])){
						if(is_array($_FILES[$f]['tmp_name'])){
							foreach($_FILES[$f]['tmp_name'] as $key => $tmp_name){
								if($_FILES[$f]['error'][$key]==UPLOAD_ERR_OK){
									$has_attachments = true;
							    $file_name = $_FILES[$f]['name'][$key];
							    $file_size = $_FILES[$f]['size'][$key];
							    $file_tmp = $_FILES[$f]['tmp_name'][$key];
							    $file_type = $_FILES[$f]['type'][$key];  
							    /*
							    $wcnt = 1;
							    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
							    $orif_file_name = $file_name;
							    while(is_file($upload_dir.'/'.$file_name)){
							    	$file_name = str_replace('.'.$ext,$cnt
							    }
							    */
							    move_uploaded_file($file_tmp, $upload_dir.'/'.$file_name);
							    $attachments[] = $upload_dir.'/'.$file_name;
							    $zattachments[$f][] = $upload_dir.'/'.$file_name;
							    $zuattachments[$f][] = $upload_url.'/'.$file_name;
							  }
							}
						} else if($_FILES[$f]['error']==UPLOAD_ERR_OK){
							
							$has_attachments = true;
							$file_name = $_FILES[$f]['name'];
					    $file_size =$_FILES[$f]['size'];
					    $file_tmp =$_FILES[$f]['tmp_name'];
					    $file_type=$_FILES[$f]['type'];  
					    
					    move_uploaded_file($file_tmp, $upload_dir.'/'.$file_name);
					    $attachments[] = $upload_dir.'/'.$file_name;
						  $zattachments[$f][] = $upload_dir.'/'.$file_name;
						  $zuattachments[$f][] = $upload_url.'/'.$file_name;
					    
						}
					}
				}
			}
			
			$headers = array();
			

			
			if(!isset($from_email) || empty($from_email) || !is_email(trim($from_email))){
				$headers[] = 'From: "'.get_option( 'blogname', __('Your Wordpress Blog','psfbldr') ).'" <'.get_option( 'admin_email', 'noreply@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) ).'>';
			} else {
				if(trim($from_name)!=''){
					$headers[] = 'From: "'.trim($from_name).'" <'.trim($from_email).'>';
				} else {
					$headers[] = 'From: '.trim($from_email);
				}
			}
			
			if(isset($mail->html_mail) && $mail->html_mail==true){
  			$headers[] = 'Content-Type: text/html; charset='.get_option( 'blog_charset', 'UTF-8');
  		} else {
				$headers[] = 'Content-Type: text/plain; charset='.get_option( 'blog_charset', 'UTF-8');
			}
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
				
				$filtered_mail_contents = apply_filters('psfb_submit_before_admin_mail_send',array('recipients'=>$recipients,'subject'=>$subject,'content'=>$content,'headers'=>$headers,'attachments'=>$attachments));
				wp_mail( explode(';',$filtered_mail_contents['recipients']), $filtered_mail_contents['subject'], $filtered_mail_contents['content'], $filtered_mail_contents['headers'], $filtered_mail_contents['attachments'] );
				
			} else {
				
				$filtered_mail_contents = apply_filters('psfb_submit_before_admin_mail_send',array('recipients'=>$recipients,'subject'=>$subject,'content'=>$content,'headers'=>$headers,'attachments'=>array()));
				wp_mail( explode(';',$filtered_mail_contents['recipients']), $filtered_mail_contents['subject'], $filtered_mail_contents['content'], $filtered_mail_contents['headers']);//, $attachments );
				
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
			
			//vars.inc.php
			if(isset($mail->html_mail) && $mail->html_mail==true){
  			//$content .= PSFB_POWERED_BY_HTML;
  		} else {
				$content .= PSFB_POWERED_BY_TXT;
			}
			
			
			if(isset($mail->html_mail) && $mail->html_mail==true){
  			$email_template = PSFB_HTML_EMAIL_TEMPLATE;
  			
  			$email_template = str_replace('<!-- mail_subject -->',$subject,$email_template);
  			$email_template = str_replace('<!-- mail_body -->',$content,$email_template);
  			$email_template = str_replace('<!-- mail_charset -->',get_option( 'blog_charset', 'UTF-8'),$email_template);
  			if(isset($j->link_love) && $j->link_love==true){
  				$email_template = str_replace('<!-- mail_footer -->',PSFB_POWERED_BY_HTML,$email_template);
  			}
  			$content = $email_template;
  		}
			
			$headers = array();
			if(!isset($from_email) || empty($from_email) || !is_email(trim($from_email))){
				$headers[] = 'From: "'.get_option( 'blogname', __('Your Wordpress Blog','psfbldr') ).'" <'.get_option( 'admin_email', 'noreply@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) ).'>';
			} else {
				if(trim($from_name)!=''){
					$headers[] = 'From: "'.trim($from_name).'" <'.trim($from_email).'>';
				} else {
					$headers[] = 'From: '.trim($from_email);
				}
			}
  		if(isset($mail->html_mail) && $mail->html_mail==true){
  			$headers[] = 'Content-Type: text/html; charset='.get_option( 'blog_charset', 'UTF-8');
  		} else {
				$headers[] = 'Content-Type: text/plain; charset='.get_option( 'blog_charset', 'UTF-8');
			}
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
			
			$filtered_mail_contents = apply_filters('psfb_submit_before_user_mail_send',array('recipients'=>$recipients,'subject'=>$subject,'content'=>$content,'headers'=>$headers));
			
			
			wp_mail( explode(';',$filtered_mail_contents['recipients']), $filtered_mail_contents['subject'], $filtered_mail_contents['content'], $filtered_mail_contents['headers']);//, $attachments );
		}
		
		
	}
	
	if(isset($j->pushover_user)){
		
		$options = array(
		  CURLOPT_URL => "https://api.pushover.net/1/messages.json",
		  CURLOPT_POSTFIELDS => array(
		    "token" => "aM5ZE5xttJQDnNkABYjwLdxpBUQzBv",
		    "user" => $j->pushover_user,
		    "message" => $admin_content,
		    "title" => $psform->post_title.' '.__('has been submitted','psfbldr'),
		    "url" =>  $_POST['psfb_pageurl'],
		    "url_title" => get_option( 'blogname', __('Your Wordpress Blog','psfbldr'))
		  ),
		  CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_HEADER         => 0,
      CURLOPT_USERAGENT      => "PlanSo Forms"
		); 

    $ch      = curl_init(); 
    curl_setopt_array($ch,$options); 
		
		$result = curl_exec($ch);
		curl_close($ch);
	}
	
	if(isset($j->zapier_url) && count($j->zapier_url)>0){
		if(is_array($j->zapier_url)){
			$zurl = $j->zapier_url;
		} else if(is_object($j->zapier_url)){
			$zurl = $j->zapier_url;
		} else {
			$zurl[] = $j->zapier_url;
		}
		
		if($has_attachments && count($zattachments)>0){
			foreach($zuattachments as $k=>$a){
				for($cnt = 0;$cnt < count($a);$cnt++){
					//$zmail_replace[$k.'['.$cnt.']'] = '@' . $a[$cnt];
					$zmail_replace[$k.'['.$cnt.']'] = $a[$cnt];
				}
			}
		}
		
		foreach($zurl as $url){
			$options = array( 
					CURLOPT_URL => $url,
	        CURLOPT_RETURNTRANSFER => 1,         // return response 
	        CURLOPT_HEADER         => 0,        // don't return headers 
	        CURLOPT_USERAGENT      => "PlanSo Forms",     // who am i 
	        CURLOPT_CONNECTTIMEOUT => 60,          // timeout on connect 
	        CURLOPT_TIMEOUT        => 60,          // timeout on response 
	        CURLOPT_POST            => 1,            // i am sending post data 
	           CURLOPT_POSTFIELDS     => $zmail_replace,    // this are my post vars 
	        CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl 
	        CURLOPT_SSL_VERIFYPEER => 0        // 
	    ); 
	
	    $ch      = curl_init(); 
	    curl_setopt_array($ch,$options); 
			
			$result = curl_exec($ch);
			curl_close($ch);
		}
	}
	//print_r($attachments);
	/** CLEAN UPLOADED ATTACHMENTS **/
	if(!isset($j->clean_attachments) || $j->clean_attachments==true){
		if($has_attachments && count($attachments)>0){
			foreach($attachments as $f){
				unlink($f);
			}
			rmdir($upload_dir);
		}
	}
	
	
	$page_detail_atts = array(
		'id' => $psform->ID,
		'permalink' => $_POST['psfb_pageurl'],
		'title' => $psform->post_title
	);
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
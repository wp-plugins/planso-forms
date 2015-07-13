<?php



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
			foreach($psfb_mail_dynamic_values as $k=>$v){
				$content = str_replace('['.$k.']',$v,$content);
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
			  			$content .= '<tr><td>'.trim(str_replace('_',' ',$k)).'</td><td>'.$v.'</td></tr>';
			  		} else {
							$content .= trim(str_replace('_',' ',$k)).': '.$v."\r\n";
						}
					}
				}
				
				if(isset($mail->html_mail) && $mail->html_mail==true){
					foreach($psfb_mail_tracking_map as $k=>$v){
						$content .= '<tr><td>'.ucwords(trim(str_replace('_',' ',$v))).'</td><td>'.$_REQUEST[$k].'</td></tr>';
					}
				}
				
				if(isset($mail->html_mail) && $mail->html_mail==true){
		  		$content .= '</table>';
		  	}
			}
			
			if(trim($content)==''){
				$content = __('Nothing submitted','psfbldr');
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
				$headers[] = 'From: "'.get_option( 'blogname', __('Your Wordpress Blog','psfbldr') ).'" <'.get_option( 'admin_email', 'no-reply@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) ).'>';
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
				$bcca = explode(';',$bcc);
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
				if(isset($filtered_mail_contents['attachments']) && is_array($filtered_mail_contents['attachments']) && count($filtered_mail_contents['attachments']) > 0){
					wp_mail( explode(';',$filtered_mail_contents['recipients']), $filtered_mail_contents['subject'], $filtered_mail_contents['content'], $filtered_mail_contents['headers'], $filtered_mail_contents['attachments']);
				} else {
					wp_mail( explode(';',$filtered_mail_contents['recipients']), $filtered_mail_contents['subject'], $filtered_mail_contents['content'], $filtered_mail_contents['headers']);//, $attachments );
				}
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
			
			foreach($psfb_mail_dynamic_values as $k=>$v){
				$content = str_replace('['.$k.']',$v,$content);
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
				$headers[] = 'From: "'.get_option( 'blogname', __('Your Wordpress Blog','psfbldr') ).'" <'.get_option( 'admin_email', 'no-reply@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) ).'>';
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
				$bcca = explode(';',$bcc);
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
			
			$filtered_mail_contents = apply_filters('psfb_submit_before_user_mail_send',array('recipients'=>$recipients,'subject'=>$subject,'content'=>$content,'headers'=>$headers,'attachments'=>array()));
			
			if(isset($filtered_mail_contents['attachments']) && is_array($filtered_mail_contents['attachments']) && count($filtered_mail_contents['attachments']) > 0){
				wp_mail( explode(';',$filtered_mail_contents['recipients']), $filtered_mail_contents['subject'], $filtered_mail_contents['content'], $filtered_mail_contents['headers'], $filtered_mail_contents['attachments']);
			} else {
				wp_mail( explode(';',$filtered_mail_contents['recipients']), $filtered_mail_contents['subject'], $filtered_mail_contents['content'], $filtered_mail_contents['headers']);//, $attachments );
			}
		}
		
	}
	
	if(isset($j->pushover_user) && !empty($j->pushover_user)){
		
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
      CURLOPT_SSL_VERIFYPEER => 0, 
	    CURLOPT_SSL_VERIFYHOST => 0,
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
					//$zmail_replace[$k.'['.$cnt.']'] = $a[$cnt];
					$zmail_replace[$k][$cnt] = '@' . $a[$cnt];
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
	
	$filtered = apply_filters('psfb_submit_before_clean_attachments',array('j'=>$j,'mail_replace'=>$mail_replace,'attachments'=>$attachments));
	$attachments = $filtered['attachments'];
	
	/** CLEAN UPLOADED ATTACHMENTS **/
	if(!isset($j->clean_attachments) || $j->clean_attachments==true){
		if($has_attachments && count($attachments)>0){
			foreach($attachments as $f){
				unlink($f);
			}
			rmdir($upload_dir);
		}
	}
	
	
?>
<?php
	//wp_session_start();

	global $wp_session;
	if(!isset($_POST['psfb_global_cnt'])){
		$_POST['psfb_global_cnt'] = 0;
	}
	$_POST['psfb_global_cnt'] ++;
	
	//$wp_session = null;
	
	$framework = array();
	
  wp_register_style( 'font-awesome',plugins_url( '/css/font-awesome-4.3.0/css/font-awesome.min.css', dirname(__FILE__) ) ,array() ,'4.2.0');
  wp_enqueue_style( 'font-awesome');
  if(isset($GLOBALS['wp_scripts']) && isset($GLOBALS['wp_scripts']->queue)){
	  foreach($GLOBALS['wp_scripts']->queue as $queue){
			if(strstr($queue,'bootstrap')){
				$framework['bootstrap'] = true;
			}
			if(strstr($queue,'foundation')){
				$framework['foundation'] = true;
			}
		}
	}
	
	wp_enqueue_style( 'bootstrap-grid',plugins_url( '/css/planso_bootstrap/bootstrap.grid.css', dirname(__FILE__) ) );
	if(!isset($framework['bootstrap'])){
		
		
		wp_register_script( 'bootstrap-collapse',plugins_url( '/js/bootstrap/src/collapse.js', dirname(__FILE__) ), array('jquery'), '3.2.2', true );
		wp_register_script( 'bootstrap-transition',plugins_url( '/js/bootstrap/src/transition.js', dirname(__FILE__) ), array('jquery'), '3.2.2', true );
		wp_enqueue_script( 'bootstrap-collapse' );
		wp_enqueue_script( 'bootstrap-transition' );
	} else {
		
	}
	
	if(isset($j->planso_style) && $j->planso_style==true){
		wp_enqueue_style( 'bootstrap-form',plugins_url( '/css/planso_bootstrap/bootstrap.form.css', dirname(__FILE__) ) );
	}

	
	if(!isset($_POST['psfb_global_datepicker_styles'])){
		if(isset($j->datepicker) && $j->datepicker=='bootstrap-datetimepicker'){
			//datetimepicker
			wp_enqueue_style( 'bootstrap-datetimepicker',plugins_url( '/js/bootstrap/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css', dirname(__FILE__) ) );
			wp_enqueue_style( 'bootstrap-dropdown',plugins_url( '/css/planso_bootstrap/bootstrap.dropdown.css', dirname(__FILE__) ) );
			$_POST['psfb_global_datepicker_styles'] = 1;
		} else if(isset($j->datepicker) && $j->datepicker=='jquery-ui-datepicker'){
			wp_enqueue_style( 'jquery-datetimepicker',plugins_url( '/css/jquery-ui/jquery-ui.min.css', dirname(__FILE__) ) );
			$_POST['psfb_global_datepicker_styles'] = 1;
		} else if(isset($j->datepicker) && $j->datepicker=='bootstrap-datepicker-eternicode'){
			wp_enqueue_style( 'bootstrap-dropdown',plugins_url( '/css/planso_bootstrap/bootstrap.dropdown.css', dirname(__FILE__) ) );
			wp_enqueue_style( 'bootstrap-datepicker',plugins_url( '/js/bootstrap/eternicode-bootstrap-datepicker/css/datepicker.css', dirname(__FILE__) ) );
			$_POST['psfb_global_datepicker_styles'] = 1;
		} else if(isset($j->datepicker) && $j->datepicker=='bootstrap-datepicker') {
			//datepicker
			wp_enqueue_style( 'bootstrap-dropdown',plugins_url( '/css/planso_bootstrap/bootstrap.dropdown.css', dirname(__FILE__) ) );
			wp_enqueue_style( 'bootstrap-datepicker',plugins_url( '/js/bootstrap/eyecon-bootstrap-datepicker/css/datepicker.css', dirname(__FILE__) ) );
			$_POST['psfb_global_datepicker_styles'] = 1;
		}
	}
	//datepicker
	wp_register_script( 'moment',plugins_url( '/js/moment/moment.js', dirname(__FILE__) ), array('jquery'), '2.9.0', true );
	if(substr(get_locale(),0,2)!='en'){
		wp_register_script( 'moment-locale',plugins_url( '/js/moment/locale/'.substr(get_locale(),0,2).'.js', dirname(__FILE__) ), array('moment'), '2.9.0', true );
	}
	
	if(!isset($_POST['psfb_global_datepicker_scripts'])){
		if(isset($j->datepicker) && $j->datepicker=='bootstrap-datetimepicker'){
			//datetimepicker
			wp_enqueue_script( 'moment' );
			wp_enqueue_script( 'moment-locale' );
			wp_enqueue_script( 'bootstrap_datetimepicker',plugins_url( '/js/bootstrap/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js', dirname(__FILE__) ), array('jquery','moment'), '4.1', true );
			$_POST['psfb_global_datepicker_scripts'] = 1;
			//wp_enqueue_script( 'psfb_datepicker',plugins_url( '/js/bootstrap/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js', dirname(__FILE__) ), array('jquery','moment'), '4.1', true );
		} else if(isset($j->datepicker) && $j->datepicker=='jquery-ui-datepicker'){
			wp_enqueue_script('jquery-ui-core', array('jquery'));
			wp_enqueue_script('jquery-ui-datepicker', array('jquery-ui-core'));
			$_POST['psfb_global_datepicker_scripts'] = 1;
		} else if(isset($j->datepicker) && $j->datepicker=='bootstrap-datepicker-eternicode'){
			
			wp_enqueue_script( 'bootstrap_datepicker',plugins_url( '/js/bootstrap/eternicode-bootstrap-datepicker/js/bootstrap-datepicker.js', dirname(__FILE__) ), array('jquery'), '1.3.1', true );
			$_POST['psfb_global_datepicker_scripts'] = 1;
		} else if(isset($j->datepicker) && $j->datepicker=='bootstrap-datepicker'){
			//datepicker
			wp_enqueue_script( 'bootstrap_datepicker',plugins_url( '/js/bootstrap/eyecon-bootstrap-datepicker/js/bootstrap-datepicker.js', dirname(__FILE__) ), array('jquery'), '1.3.1', true );
			$_POST['psfb_global_datepicker_scripts'] = 1;
		}
	}
	// Register the script first.
	wp_register_script( 'planso_form_builder', plugins_url( '/js/planso-form-builder.js', dirname(__FILE__) ), array('jquery'), '1', true );
	
	wp_enqueue_script( 'planso_form_builder' );
	
	
	do_action('psfb_form_enqueue_scripts');
	
	require(dirname(__FILE__).'/vars.inc.php');
	require_once(dirname(__FILE__).'/scripts.inc.php');
	
	$out = '';
	
	//$out .= '<pre style="height:80px;overflow-y:auto;">'.print_r($j,true).'</pre>';
	//$out .= '<pre style="height:80px;overflow-y:auto;">'.print_r($wp_session['psfb_errors'][$atts['id']],true).'</pre>';
	//$out .= '<pre style="height:80px;overflow-y:auto;">'.print_r($wp_session['psfb_errors'],true).'</pre>';
	//$out .= '<pre style="height:80px;overflow-y:auto;">'.print_r($wp_session,true).'</pre>';
	//$out .= '<pre style="height:80px;overflow-y:auto;">'.print_r($wp_session['psfb_values'][$atts['id']],true).'</pre>';
	//$out .= '<pre style="height:80px;overflow-y:auto;">'.print_r($GLOBALS['wp_scripts'],true).'</pre>';
	//$out .= '<pre style="height:80px;overflow-y:auto;">'.print_r($GLOBALS['wp_styles'],true).'</pre>';
	
	$out .= '<form enctype="multipart/form-data" method="post" action="'.htmlspecialchars(get_permalink( get_the_ID() ) ).'" class="planso-form-builder';
	if(isset($j->horizontal_form) && ($j->horizontal_form!=false && $j->horizontal_form!='vertical')){
		$out .= ' form-horizontal';
	}
	$out .= '" data-id="'.$atts['id'].'" data-cnt="'.$_POST['psfb_global_cnt'].'" id="planso_forms_'.$atts['id'].'_'.$_POST['psfb_global_cnt'].'">';
	$out .= '<div class="container-fluid">';
	if(isset($wp_session['psfb_errors'][$atts['id']]) && !empty($wp_session['psfb_errors'][$atts['id']]) ){
		$out .= '<p style="padding: 15px;" class="bg-danger">'.__('Attention! There has been an error submitting the form. Please check the marked fields below.','psfbldr').'</p>';
		if(isset($wp_session['psfb_errors'][$atts['id']]['psfb_message'])){
			$out .= '<p class="bg-warning">'.$wp_session['psfb_errors'][$atts['id']]['psfb_message'].'</p>';
		}
	}
	if(isset($wp_session['psfb_success'][$atts['id']]) && !empty($wp_session['psfb_success'][$atts['id']]) ){
		$out .= '<p style="padding: 15px;" class="bg-success">'.__('Your submission was successful.','psfbldr').'</p>';
		$wp_session['psfb_success'][$atts['id']] = null;
	}
	$out .= '<input type="hidden" name="psfb_pageurl" value="'.htmlspecialchars(get_permalink( get_the_ID() ) ).'"/>';
	$out .= '<input type="hidden" name="psfb_userip" value="'.@$_SERVER['REMOTE_ADDR'].'"/>';
	$out .= '<input type="hidden" name="psfb_useragent" value="'.@$_SERVER['HTTP_USER_AGENT'].'"/>';
	$out .= '<input type="hidden" name="psfb_landingpage" value="'.htmlspecialchars(@$wp_session['LandingPage']).'"/>';
	$out .= '<input type="hidden" name="psfb_referer" value="'.htmlspecialchars(@$wp_session['OriginalRef']).'"/>';
	$out .= '<input type="hidden" name="psfb_page_id" value="'.get_the_ID().'"/>';
	$out .= '<input type="hidden" name="psfb_form_submit" value="1"/>';
	$out .= '<input type="hidden" name="psfb_form_id" value="'.$atts['id'].'"/>';
	$out .= '<input type="hidden" name="psfb_form_cnt" value="'.$_POST['psfb_global_cnt'].'"/>';
	
	
	
	$out .= '<div style="display:none"><input type="text" name="psfb_hon_as"/></div>';
	
	$psfb_anti_spam[$atts['id']][$_POST['psfb_global_cnt']] = md5('we dont like spam'.time());
	
	$wp_session['psfb_anti_spam'] = $psfb_anti_spam;
	wp_session_commit();
	//$out .= '<pre style="height:80px;overflow-y:auto;">'.print_r($wp_session,true).'</pre>';
	$out .= '<div style="display:none"><input type="text" name="psfb_hon_as2" id="psfb_hon_as2_'.$atts['id'].'_'.$_POST['psfb_global_cnt'].'" value="'.$wp_session['psfb_anti_spam'][$atts['id']][$_POST['psfb_global_cnt']].'"/></div>';
	$out .= '<div style="display:none"><input type="text" name="psfb_cnt_check" value="'.$_POST['psfb_global_cnt'].'"/></div>';
	
	if(isset($j->javascript_antispam) && $j->javascript_antispam==true){
		$out .= '<noscript>'.__('Please enable Javascript in your Browser in order to correctly submit this form.','psfbldr').'</noscript>';
		
		$out .= <<<EOF
<script type="text/javascript">document.write('<i'+'n'+'p'+'ut ty'+'pe="hi'+'dden" value="'+document.getElementById("psfb_hon_as2_{$atts['id']}_{$_POST['psfb_global_cnt']}").value+'" name="psfb_js_as">');</script>
EOF;
	}
	
	$all_atts = array(
		'id' => $atts['id'],
		'customfields' => $customfields,
		'j' => $j,
		'out' => $out
	);
	$new_atts = apply_filters('psfb_form_after_hidden_fields',$all_atts);
	
	$atts['id'] = $new_atts['id'];
	$customfields = $new_atts['customfields'];
	$j = $new_atts['j'];
	$out = $new_atts['out'];
	

	$cnt = 0;
	if(isset($j->fields) && count($j->fields)>0){
		foreach($j->fields as $row){
			$all_atts = array(
			 'row' => $row,
			 'customfields' => $customfields,
			 'j' => $j,
			 'cnt' => $cnt,
			 'out' => $out
			);
			$new_atts = apply_filters('psfb_form_before_row_handle',$all_atts);
			
			$row = $new_atts['row'];
			$customfields = $new_atts['customfields'];
			$j = $new_atts['j'];
			$cnt = $new_atts['cnt'];
			$out = $new_atts['out'];
			
			$out .= '<div class="row">';
			
			$colcnt = floor(12/count((array)$row));
			//$out .= '<pre>'.count((array)$row).print_r($row,true).'</pre>';
			
			foreach($row as $col){
				$cnt ++;
				$mytype = $col->type;
				foreach($fieldtypes as $k => $v){
					if($v['type']==$col->type){
						$mytype = $k;
					}
				}
				$fieldinfo = $fieldtypes[$mytype];
				$fieldtype = $fieldinfo['type'];
				
				if(isset($col->condition) && !empty($col->condition)){
					if(is_object($col->condition)){
						$condition = $col->condition;
					} else {
						$condition = json_decode($col->condition);
					}
					//exit(print_r($condition,true));
				} else {
					$condition = false;
				}
				
				if(!isset($col->name) || empty($col->name)){
					if(isset($col->label) && !empty($col->label)){
						$col->name = $col->label;
					} else {
						$col->name = '';
					}
				}
				$col->name = preg_replace("/[^A-Za-z0-9_]+/", '_', $col->name);
				
				if(trim($col->name)==''){
					$col->name = 'field'.$cnt;
				}
				
				if(isset($atts[strtolower($col->name)]) && !empty($atts[strtolower($col->name)])){
					if($atts[strtolower($col->name)] == 'CURRENT_DATE()'){
						$atts[strtolower($col->name)] = date_i18n(get_option( 'date_format' ));
					}
					if($atts[strtolower($col->name)] == 'CURRENT_DATETIME()'){
						$atts[strtolower($col->name)] = date_i18n(get_option( 'date_format' )).' '. date_i18n(get_option('time_format'));
					}
					if($atts[strtolower($col->name)] == 'CURRENT_TIME()'){
						$atts[strtolower($col->name)] = date_i18n(get_option('time_format'));
					}
				}
				
				
				$out .= '<div class="col-md-'.$colcnt.'">';
				$out .= '<div class="form-group psfb-single-container';
				if(isset($wp_session['psfb_errors'][$atts['id']]) && isset($wp_session['psfb_errors'][$atts['id']][$col->name]) ){
					$out .= ' has-error';
				}
				$out .= '"';
				
				if($condition!=false){
					/*
					stdClass Object ( [groups] => Array ( [0] => stdClass Object ( [groupOp] => AND [groupAction] => show [rules] => Array ( [0] => stdClass Object ( [field] => Anrede [op] => eq [data] => Herr )[1] => stdClass Object ( [field] => Vorname [op] => eq [data] => Paul )))))
					*/
					$cshow = false;
					foreach($condition->groups as $c){
						if($c->groupAction == 'hide'){
							$cshow = true;
							$groupshow = true;
							foreach($c->rules as $rule){
								if($c->groupOp=='AND'){
									if( isset($wp_session['psfb_values'][$atts['id']][$rule->field]) ){
										//check if value exists
									}
								} else if($c->groupOp=='OR'){
									
								}
							}
						} else if($c->groupAction == 'hide'){
							$cshow = false;
						}
					}
					if(!$cshow){
						$out .= ' style="display:none;"';
						$out .= ' data-formgroupid="'.$atts['id'].'_'.$cnt.'"';
						$out .= ' data-conditionalgroup="true"';
					}
				}
				
			
				$out .= '>';
				
				if($condition!=false){
					$out .= '<textarea class="psfb_condition_content" style="display:none;" data-id="psfield_'.$atts['id'].'_'.$cnt.'">'.json_encode($condition).'</textarea>';
				}
				
				if( in_array($mytype,$customelements)){
					
					$all_atts = array(
						'out' => $out,
						'col' => $col,
						'mytype' => $mytype,
						'fieldinfo' => $fieldinfo,
						'customelements' => $customelements,
						'j' => $j
					);
	
					$all_atts = apply_filters('psfb_form_customelements_handler',$all_atts);
					
					$out = $all_atts['out'];
					$col = $all_atts['col'];
					$mytype = $all_atts['mytype'];
					$fieldinfo = $all_atts['fieldinfo'];
					$customelements = $all_atts['customelements'];
					$j = $all_atts['j'];
						
				} else if( in_array($mytype,$htmlfields)){
					
				//HTML BLOCK
					$tag = $col->type;
					if(isset($fieldinfo['options']) && !empty($fieldinfo['options'])){
						if(isset($col->option) && !empty($col->option)){
							$tag .= $col->option;
						} else {
							$tag .= $fieldinfo['options'][0];
						}
					}
					if($fieldinfo['wrap'] == true){
						$out .= '<'.$tag;
						if(isset($col->style))$out .= ' style="'.$col->style.'"';
						if(isset($col->class))$out .= ' class="'.$col->class.'"';
						$out .= '>';
						$out .= $col->content;
						$out .= '</'.$tag.'>';
					} else {
						$out .= '<'.$tag.'';
						if(isset($col->style))$out .= ' style="'.$col->style.'"';
						if(isset($col->class))$out .= ' class="'.$col->class.'"';
						$out .= '>';
					}
					
				} else {
					//FIELD BLOCK
					//$out .= 'hide_label:'.$col->type.' '.print_r($col->hide_label,true).'<br/>';
					if( strstr('submit',$col->type) || strstr('button',$col->type) ){
						
						if(isset($col->force_label) && $col->force_label == true){
							$out .= '<label class="control-label';
							if(isset($j->horizontal_form) && ($j->horizontal_form!=false && $j->horizontal_form!='vertical')){
								if($j->horizontal_form=='horizontal'){
									$out .= ' col-md-2';
								} else if($j->horizontal_form=='horizontal_3'){
									$out .= ' col-md-3';
								} else if($j->horizontal_form=='horizontal_4'){
									$out .= ' col-md-4';
								} else if($j->horizontal_form=='horizontal_5'){
									$out .= ' col-md-5';
								} else if($j->horizontal_form=='horizontal_6'){
									$out .= ' col-md-6';
								} else {
									$out .= ' col-md-2';
								}
							}
							$out .= '" for="psfield_'.$atts['id'].'_'.$cnt.'">';
							$out .= '&nbsp;';
							$out .= '</label>';
						} else {
							
							if(isset($j->horizontal_form) && ($j->horizontal_form!=false && $j->horizontal_form!='vertical')){
								$out .= '<label class="';
								if($j->horizontal_form=='horizontal'){
									$out .= ' col-md-2';
								} else if($j->horizontal_form=='horizontal_3'){
									$out .= ' col-md-3';
								} else if($j->horizontal_form=='horizontal_4'){
									$out .= ' col-md-4';
								} else if($j->horizontal_form=='horizontal_5'){
									$out .= ' col-md-5';
								} else if($j->horizontal_form=='horizontal_6'){
									$out .= ' col-md-6';
								} else {
									$out .= ' col-md-2';
								}
								$out .= ' control-label">&nbsp;</label>';
							}
						}
					} else {
						if( !isset($col->hide_label) || $col->hide_label==false || $col->hide_label==''  ){
							$out .= '<label class="control-label';
							
							if(isset($j->horizontal_form) && ($j->horizontal_form!=false && $j->horizontal_form!='vertical')){
								if($j->horizontal_form=='horizontal'){
									$out .= ' col-md-2';
								} else if($j->horizontal_form=='horizontal_3'){
									$out .= ' col-md-3';
								} else if($j->horizontal_form=='horizontal_4'){
									$out .= ' col-md-4';
								} else if($j->horizontal_form=='horizontal_5'){
									$out .= ' col-md-5';
								} else if($j->horizontal_form=='horizontal_6'){
									$out .= ' col-md-6';
								} else {
									$out .= ' col-md-2';
								}
							}
							$out .= '" for="psfield_'.$atts['id'].'_'.$cnt.'">';
							
							$out .= $col->label;
							
							if(isset($col->required) && ($col->required=='required' || $col->required==true)){
								$out .= '<span class="psfb_required_mark">*</span>';
							}
							
							if(isset($wp_session['psfb_errors'][$atts['id']]) && isset($wp_session['psfb_errors'][$atts['id']][$col->name])){
								$out .= ' <small class="bg-danger">('.$wp_session['psfb_errors'][$atts['id']][$col->name]['message'].')</small>';
							}
							$out .= '</label>';
							
						} else {
							
							if(isset($j->horizontal_form) && ($j->horizontal_form!=false && $j->horizontal_form!='vertical')){
								$out .= '<label class="';
								if($j->horizontal_form=='horizontal'){
									$out .= ' col-md-2';
								} else if($j->horizontal_form=='horizontal_3'){
									$out .= ' col-md-3';
								} else if($j->horizontal_form=='horizontal_4'){
									$out .= ' col-md-4';
								} else if($j->horizontal_form=='horizontal_5'){
									$out .= ' col-md-5';
								} else if($j->horizontal_form=='horizontal_6'){
									$out .= ' col-md-6';
								} else {
									$out .= ' col-md-2';
								}
								$out .= ' control-label">&nbsp;</label>';
							}
						}
					}
					
					if(isset($j->horizontal_form) && ($j->horizontal_form!=false && $j->horizontal_form!='vertical')){
						$out .= '<div class="';
						if($j->horizontal_form=='horizontal'){
							$out .= ' col-md-10';
						} else if($j->horizontal_form=='horizontal_3'){
							$out .= ' col-md-9';
						} else if($j->horizontal_form=='horizontal_4'){
							$out .= ' col-md-8';
						} else if($j->horizontal_form=='horizontal_5'){
							$out .= ' col-md-7';
						} else if($j->horizontal_form=='horizontal_6'){
							$out .= ' col-md-6';
						} else {
							$out .= ' col-md-10';
						}
						$out .= '">';
					}
					
					
					if(isset($col->icon) && !empty($col->icon)){
						$out .= '<div class="input-group">';
				  	$out .= '<div class="input-group-addon">';
				  	if( strstr($col->icon,'fa-') ){
				  		$out .= '<span class="fa '.$col->icon.'"></span>';
				  	} else {
				  		$out .= $col->icon;
				  	}
				  	$out .= '</div>';
					}
					
					
					
					$all_field_atts = array(
						'out' => $out,
						'col' => $col,
						'mytype' => $mytype,
						'fieldinfo' => $fieldinfo,
						'customfields' => $customfields,
						'j' => $j
					);
					$added_field_attributes = '';
					$added_field_attributes_return = apply_filters('psfb_form_add_field_attributes',$all_field_atts);
					if(is_array($added_field_attributes_return)){
						//we are expecting a string from the filter
				//		foreach($added_field_attributes_return as $a){
				//			$added_field_attributes .= ' '.$a;
				//		}
					} else {
						$added_field_attributes = $added_field_attributes_return;
					}
					
					if( in_array($mytype,$customfields)){
						
						$all_atts = array(
							'out' => $out,
							'col' => $col,
							'mytype' => $mytype,
							'fieldinfo' => $fieldinfo,
							'customfields' => $customfields,
							'j' => $j
						);
						
						$all_atts = apply_filters('psfb_form_customfieds_handler',$all_atts);
						
						$out = $all_atts['out'];
						$col = $all_atts['col'];
						$mytype = $all_atts['mytype'];
						$fieldinfo = $all_atts['fieldinfo'];
						$customfields = $all_atts['customfields'];
						$j = $all_atts['j'];
						
					} else if(!in_array($col->type,$specialfields)){
						if(strstr($col->type,'file')){
							$out .= '<input type="file"';
							if(isset($fieldinfo['multiple']) && $fieldinfo['multiple']==true){
								$out .= ' multiple="multiple"';
							}
							if(isset($col->required) && ($col->required=='required' || $col->required==true)){
								$out .= ' required="required" data-required="required"';
							}
							$out .= ' name="'.$col->name.'';
							if(isset($fieldinfo['multiple']) && $fieldinfo['multiple']==true){
								$out .= '[]';
							}
							$out .= '"';
							if(isset($col->placeholder))$out .= ' placeholder="'.$col->placeholder.'"';
							$out .= ' class="form-control';
							if(isset($col->class))$out .= ' '.$col->class.'';
							$out .= '" id="psfield_'.$atts['id'].'_'.$cnt.'"';
							if(isset($col->style))$out .= ' style="'.$col->style.'"';
							if(isset($col->maxlength))$out .= ' maxlength="'.$col->maxlength.'"';
							if(isset($col->number_max))$out .= ' max="'.$col->number_max.'"';
							if(isset($col->number_min))$out .= ' min="'.$col->number_min.'"';
							$out .= '/>';
						} else {
							$out .= '<input type="'.$fieldtype.'"';
							if(isset($fieldinfo['multiple']) && $fieldinfo['multiple']==true){
								$out .= ' multiple="multiple"';
							}
							if(strstr($fieldtype,'date')){
								$out .= ' data-psfb_datepicker="'.$j->datepicker.'"';
							}
							if(isset($wp_session['psfb_values'][$atts['id']]) && isset($wp_session['psfb_values'][$atts['id']][$col->name])){
								$out .= ' value="'.$wp_session['psfb_values'][$atts['id']][$col->name].'"';
							} else if(isset($atts[strtolower($col->name)]) && !empty($atts[strtolower($col->name)])){
								$out .= ' value="'.htmlentities($atts[strtolower($col->name)], ENT_QUOTES).'"';
							} else if(isset($j->allow_prefill) && $j->allow_prefill==true && isset($_REQUEST[$col->name]) && !empty($_REQUEST[$col->name])){
								$out .= ' value="'.htmlentities($_REQUEST[$col->name], ENT_QUOTES).'"';
							}
							if(isset($col->required) && ($col->required=='required' || $col->required==true)){
								$out .= ' required="required" data-required="required"';
							}
							$out .= ' name="'.$col->name.'"';
							if(isset($col->placeholder))$out .= ' placeholder="'.$col->placeholder.'"';
							$out .= ' class="form-control ';
							if(isset($col->class))$out .= ''.$col->class.'';
							$out .= '"';
							$out .= ' id="psfield_'.$atts['id'].'_'.$cnt.'"';
							if(isset($col->style))$out .= ' style="'.$col->style.'"';
							if(isset($col->maxlength))$out .= ' maxlength="'.$col->maxlength.'"';
							if(isset($col->number_max))$out .= ' max="'.$col->number_max.'"';
							if(isset($col->number_min))$out .= ' min="'.$col->number_min.'"';
							//do_action('psfb_form_add_field_attributes');
							/*
							$all_field_atts = array(
								'out' => $out,
								'col' => $col,
								'mytype' => $mytype,
								'fieldinfo' => $fieldinfo,
								'customfields' => $customfields,
								'j' => $j
							);
						
							$all_field_atts = apply_filters('psfb_form_add_field_attributes',$all_field_atts);
							
							$out = $all_field_atts['out'];
							$col = $all_field_atts['col'];
							$mytype = $all_field_atts['mytype'];
							$fieldinfo = $all_field_atts['fieldinfo'];
							$customfields = $all_field_atts['customfields'];
							$j = $all_field_atts['j'];
							*/
							$out .= $added_field_attributes;
							$out .= '/>';
						}
					} else {
						//psfb_form_add_attribute
						if(!in_array($col->type,$selectfields)){
							//textarea, submit, ect
							
							if($col->type=='textarea'){
								$out .= '<textarea';
								if(isset($col->required) && ($col->required=='required' || $col->required==true)){
									$out .= ' required="required" data-required="required"';
								}
								$out .= ' name="'.$col->name.'"';
								if(isset($col->placeholder))$out .= ' placeholder="'.$col->placeholder.'"';
								$out .= ' class="form-control ';
								if(isset($col->class))$out .= ''.$col->class.'';
								$out .= '"';
								$out .= ' id="psfield_'.$atts['id'].'_'.$cnt.'"';
								if(isset($col->style))$out .= ' style="'.$col->style.'"';
								if(isset($col->maxlength))$out .= ' maxlength="'.$col->maxlength.'"';
								if(isset($col->number_max))$out .= ' max="'.$col->number_max.'"';
								if(isset($col->number_min))$out .= ' min="'.$col->number_min.'"';
								$out .= $added_field_attributes;
								$out .= '>';
								
								if(isset($wp_session['psfb_values'][$atts['id']]) && isset($wp_session['psfb_values'][$atts['id']][$col->name])){
									$out .= $wp_session['psfb_values'][$atts['id']][$col->name];
								} else if(isset($atts[strtolower($col->name)]) && !empty($atts[strtolower($col->name)])){
									$out .= htmlentities($atts[strtolower($col->name)], ENT_QUOTES);
								} else if(isset($j->allow_prefill) && $j->allow_prefill==true && isset($_REQUEST[$col->name]) && !empty($_REQUEST[$col->name])){
									$out .= htmlentities($_REQUEST[$col->name], ENT_QUOTES);
								}
								$out .= '</textarea>';
							} else if(strstr($col->type,'submit')){
								if($col->type=='submit'){
									if(!isset($col->label) || empty($col->label))$col->label = __('Submit','psfbldr');
									$out .= '<button type="submit" class="btn btn-primary '.$col->class.'" id="psfield_'.$atts['id'].'_'.$cnt.'" style="'.$col->style.'">'.$col->label.'</button>';
								} else if($col->type=='submitimage'){
									$out .= '<input type="image" src="'.$col->src.'" class="'.$col->class.'" id="psfield_'.$atts['id'].'_'.$cnt.'" style="'.$col->style.'"/>';
								}
							}
						} else {
							//select,radio,checkbox, etc
							
							$opts = $col->select_options;
							if($col->type=='select' || $col->type=='multiselect'){
								$out .= '<select';
								if($col->type=='multiselect'){
									$out .= ' multiple="multiple"';
								}
								if(isset($col->required) && ($col->required=='required' || $col->required==true)){
									$out .= ' required="required" data-required="required"';
								}
								$out .= ' name="'.$col->name.'';
								if($col->type=='multiselect'){
								$out .= '[]';
								}
								$out .= '" class="form-control';
								if(isset($col->class))$out .= ' '.$col->class.'';
								$out .= '" id="psfield_'.$atts['id'].'_'.$cnt.'"';
								if(isset($col->style))$out .= ' style="'.$col->style.'"';
								
								$out .= $added_field_attributes;
								$out .= '>';
								foreach($opts as $opt){
									
									if(isset($opt->empty) && $opt->empty == true){
										$opt->val = '';
									}else if(trim($opt->val)==''){
										$opt->val = $opt->label;
									}
									
									/*
									
									if(trim($opt->val)=='' && isset($opt->empty) && $opt->empty == false){
										$opt->val = $opt->label;
									}else if(isset($opt->empty) && $opt->empty == true){
										$opt->val = '';
									}*/
									$out .= '<option value="'.$opt->val.'"';
									
									
									
									if(isset($wp_session['psfb_values'][$atts['id']]) && isset($wp_session['psfb_values'][$atts['id']][$col->name]) && $wp_session['psfb_values'][$atts['id']][$col->name]==$opt->val){
										$out .= ' selected="selected"';
									} else if(isset($atts[strtolower($col->name)]) && !empty($atts[strtolower($col->name)]) && $atts[strtolower($col->name)]==$opt->val){
										$out .= ' selected="selected"';
									} else if(isset($j->allow_prefill) && $j->allow_prefill==true && isset($_REQUEST[$col->name]) && !empty($_REQUEST[$col->name]) && $_REQUEST[$col->name]==$opt->val){
										$out .= ' selected="selected"';
									}	else if(isset($opt->selected) && ($opt->selected == 'selected' || $opt->selected == true) ){
										$out .= ' selected="selected"';
									}
									
									
									$out .= '>'.$opt->label.'</option>';
								}
								$out .= '</select>';
								
							} else if($col->type == 'radio'){
								$ocnt = 0;
								$out .= '<div class="radio_wrapper">';
								if(!isset($col->name) || empty($col->name)){
									$col->name = $col->label;
								}
								$orientation = 'horizontal';
								$wrap_div = false;
								if(isset($col->orientation) && $col->orientation=='vertical'){
									$wrap_div = true;
								}
								foreach($opts as $opt){
									$ocnt ++;
									if(isset($opt->empty) && $opt->empty == true){
										$opt->val = '';
									} else if(trim($opt->val)==''){
										$opt->val = $opt->label;
									}
									if($wrap_div){
										$out .= '<div class="radio">';
									}
									
									$out .= '<label class="';
									if(!$wrap_div)$out .= 'radio-inline ';
									if(isset($col->class))$out .= ''.$col->class.'';
									$out .= '"';
									if(isset($col->style))$out .= ' style="'.$col->style.'"';
									$out .= '>';
									$out .= '<input';
									if(isset($col->required) && ($col->required=='required' || $col->required==true)){
										$out .= ' required="required" data-required="required"';
									}
									if(isset($wp_session['psfb_values'][$atts['id']]) && isset($wp_session['psfb_values'][$atts['id']][$col->name]) && $wp_session['psfb_values'][$atts['id']][$col->name]==$opt->val){
										$out .= ' checked="checked"';
									} else if(isset($atts[strtolower($col->name)]) && !empty($atts[strtolower($col->name)]) && $atts[strtolower($col->name)]==$opt->val){
										$out .= ' checked="checked"';
									} else if(isset($j->allow_prefill) && $j->allow_prefill==true && isset($_REQUEST[$col->name]) && !empty($_REQUEST[$col->name]) && $_REQUEST[$col->name]==$opt->val){
										$out .= ' checked="checked"';
									} else if(isset($opt->checked) && ($opt->checked == 'checked' || $opt->checked == true) ){
										$out .= ' checked="checked"';
									}
									$out .= ' type="radio" value="'.$opt->val.'" name="'.$col->name.'" id="psfield_'.$atts['id'].'_'.$cnt.'_'.$ocnt.'"';
									
									$out .= $added_field_attributes;
									$out .= '>';
									
									$out .= $opt->label;
									$out .= '</label>';
									if($wrap_div){
										$out .= '</div>';
									}
								}
								$out .= '</div>';
							} else if($col->type == 'checkbox'){
								$ocnt = 0;
								$out .= '<div class="checkbox_wrapper">';
								$orientation = 'horizontal';
								$wrap_div = false;
								if(isset($col->orientation) && $col->orientation=='vertical'){
									$wrap_div = true;
								}
								foreach($opts as $opt){
									$ocnt ++;
									if($opt->val==''){
										$opt->val = $opt->label;
									}
									if($wrap_div){
										$out .= '<div class="checkbox">';
									}
									$out .= '<label class="';
									if(!$wrap_div)$out .= 'checkbox-inline ';
									if(isset($col->class))$out .= ''.$col->class.'';
									$out .= '"';
									if(isset($col->style))$out .= ' style="'.$col->style.'"';
									$out .= '>';
									$out .= '<input'; 
									if(isset($col->required) && ($col->required=='required' || $col->required==true)){
										$out .= ' data-required="required"';
									}
									
									if(isset($wp_session['psfb_values'][$atts['id']]) && isset($wp_session['psfb_values'][$atts['id']][$col->name]) && is_array($wp_session['psfb_values'][$atts['id']][$col->name]) && in_array($opt->val,$wp_session['psfb_values'][$atts['id']][$col->name])){
										$out .= ' checked="checked"';
									} else if(isset($atts[strtolower($col->name)]) && !empty($atts[strtolower($col->name)]) && ((is_array($atts[strtolower($col->name)]) && in_array($opt->val,$atts[strtolower($col->name)]) ) || $atts[strtolower($col->name)] == $opt->val ) ){
										$out .= ' checked="checked"';
									} else if(isset($j->allow_prefill) && $j->allow_prefill==true && isset($_REQUEST[$col->name]) && !empty($_REQUEST[$col->name]) && ((is_array($_REQUEST[$col->name]) && in_array($opt->val,$_REQUEST[$col->name]) ) || $_REQUEST[$col->name] == $opt->val ) ){
										$out .= ' checked="checked"';
									}else if(isset($opt->checked) && ($opt->checked == 'checked' || $opt->checked == true) ){
										$out .= ' checked="checked"';
									}
									
									$out .= ' type="checkbox" value="'.$opt->val.'" name="'.$col->name.'[]" id="psfield_'.$atts['id'].'_'.$cnt.'_'.$ocnt.'"';
									
									$out .= $added_field_attributes;
									$out .= '>';
									$out .= $opt->label;
									
									$out .= '</label>';
									if($wrap_div){
										$out .= '</div>';
									}
								}
								$out .= '</div>';
							}
						}
					}
					
					if(isset($col->icon) && !empty($col->icon)){
						$out .= '</div>';//input-group
					}
					
					if(isset($col->help_text) && !empty($col->help_text)){
						$out .= '<p class="help-block">'.$col->help_text.'</p>';
					}
					
					if(isset($j->horizontal_form) && ($j->horizontal_form!=false && $j->horizontal_form!='vertical')){
						$out .= '</div>';//end div class="col-md-10
					}
					
				}//if not html
				
				$out .= '</div>';//form-group
				$out .= '</div>';//col-md
			}
			$out .= '</div>';
		} //ende foreach fields
	}// ende if fields
	
	$out .= '</div>';
	$out .= '</form>';
	
	if(isset($j->link_love) && !empty($j->link_love) && $j->link_love==true){
		$out .= '<p style="text-align:right;"><small>'.__('powered by','psfbldr').' <a href="http://forms.planso.de/">'.__('PlanSo Forms','psfbldr').'</a></small></p>';
	}
	
	$psfb_errors = $wp_session['psfb_errors'];
	$psfb_values = $wp_session['psfb_values'];
	if(isset($psfb_errors[$atts['id']])){
		$psfb_errors[$atts['id']] = null;
	}
	if(isset($psfb_values[$atts['id']])){
		$psfb_values[$atts['id']] = null;
	}
	
	$wp_session['psfb_errors'] = $psfb_errors;
	$wp_session['psfb_values'] = $psfb_values;
	wp_session_commit();
	return $out;
	
?>
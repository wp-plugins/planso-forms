<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) )
	die( '-1' );


?><div class="wrap">

<h2><?php
	
	if ( !isset($_REQUEST['post']) || empty($_REQUEST['post']) || $_REQUEST['post'] == -1 ) {
		echo esc_html( __( 'Add New Form', 'psfbldr' ) );
		$post_id = -1;
	} else {
		echo esc_html( __( 'Edit Form', 'psfbldr' ) );

		echo ' <a href="' . esc_url( menu_page_url( 'ps-form-builder-new', false ) ) . '" class="add-new-h2">' . esc_html( __( 'Add New', 'psfbldr' ) ) . '</a>';
		$post_id = $_REQUEST['post'];
		$psform = get_post( $post_id);//, $output, $filter );
		
		if(isset($psform->post_content) && !empty($psform->post_content) && strstr($psform->post_content,'{')){
			$j = json_decode($psform->post_content);
		}
	}
	echo ' <a href="' . esc_url( menu_page_url( 'ps-form-builder', false ) ) . '" class="add-new-h2">' . esc_html( __( 'Back to forms', 'psfbldr' ) ) . '</a>';
	
?></h2>

<?php do_action( 'psfb_admin_notices' ); ?>

<br class="clear" />







<script type="text/javascript">


var fieldtypes = {
	divider_templates : {label:'<?php echo __('Predefined fields','psfbldr'); ?>', type:'divider'},
	name : {label:'<?php echo __('Name','psfbldr'); ?>',type:'text',icon:'fa-user'},
	firstname : {label:'<?php echo __('First name','psfbldr'); ?>',type:'text',icon:'fa-user'},
	lastname : {label:'<?php echo __('Last name','psfbldr'); ?>',type:'text',icon:'fa-user'},
	email : {label:'<?php echo __('Email','psfbldr'); ?>',type:'email',icon:'fa-at'},
	/*landline : {label:'<?php echo __('Landline','psfbldr'); ?>',type:'tel',icon:'fa-phone'},*/
	mobil : {label:'<?php echo __('Mobile phone','psfbldr'); ?>',type:'tel',icon:'fa-mobile'},
	tel : {label:'<?php echo __('Phone','psfbldr'); ?>',type:'tel',icon:'fa-phone'},
	divider_generic : {label:'<?php echo __('Generic fields','psfbldr'); ?>', type:'divider'},
	text : {label:'<?php echo __('Text','psfbldr'); ?>',type:'text',icon:'fa-font'},
	textarea : {label:'<?php echo __('Multiline text','psfbldr'); ?>',type:'textarea',rows:true,cols:true,icon:'fa-font'},
	number : {label:'<?php echo __('Number','psfbldr'); ?>',type:'number',min:true,max:true,step:true,icon:'#'},
	divider_date : {label:'<?php echo __('Date and time fields','psfbldr'); ?>', type:'divider'},
	date : {label:'<?php echo __('Date','psfbldr'); ?>',type:'date',icon:'fa-calendar'},
	time : {label:'<?php echo __('Time','psfbldr'); ?>',type:'time',icon:'fa-clock-o'},
	datetime : {label:'<?php echo __('Date and time','psfbldr'); ?>',type:'datetime',icon:'fa-calendar'},
/*
	week : {label:'<?php echo __('Woche','psfbldr'); ?>',type:'week'},
	month : {label:'<?php echo __('Monat','psfbldr'); ?>',type:'month'},
*/
	divider_select : {label:'<?php echo __('Select fields','psfbldr'); ?>', type:'divider'},
	select : {label:'<?php echo __('Select','psfbldr'); ?>',type:'select',icon:'fa-caret-square-o-down'},
	multiselect : {label:'<?php echo __('Multiselect','psfbldr'); ?>',type:'select',multiple:true,icon:'fa-caret-square-o-down'},
	radio : {label:'<?php echo __('Radio','psfbldr'); ?>',type:'radio',icon:'fa-dot-circle-o'},
	checkbox : {label:'<?php echo __('Checkbox','psfbldr'); ?>',type:'checkbox',icon:'fa-check-square-o'},
	divider_special : {label:'<?php echo __('Special fields','psfbldr'); ?>', type:'divider'},
/*
	range : {label:'<?php echo __('Range','psfbldr'); ?>',type:'range',min:true,max:true,step:true},
	search : {label:'<?php echo __('Suche','psfbldr'); ?>',type:'search'},
	hidden : {label:'<?php echo __('Versteckt','psfbldr'); ?>',type:'hidden'},
*/
	file : {label:'<?php echo __('Single file','psfbldr'); ?>',type:'file',icon:'fa-file'},
	multifile : {label:'<?php echo __('Multiple files','psfbldr'); ?>',type:'file',multiple:true,icon:'fa-folder-open'},
	url : {label:'<?php echo __('Url','psfbldr'); ?>',type:'url',icon:'fa-link'},
/*
	color : {label:'<?php echo __('Color','psfbldr'); ?>',type:'color'},
*/
	divider_buttons : {label:'<?php echo __('Submit buttons','psfbldr'); ?>', type:'divider'},
/*
	button : {label:'<?php echo __('Button','psfbldr'); ?>',type:'button'},
*/
	submit : {label:'<?php echo __('Submit button','psfbldr'); ?>',type:'submit'}
/*
	imagesubmit : {label:'<?php echo __('Submit-Image','psfbldr'); ?>',type:'image'}
*/
};

var specialfields = [
	'select',
	'checkbox',
	'radio',
	'textarea',
	'multiselect',
	'textarea',
	'submit',
	'submitimage'
];	
var selectfields = [
	'select',
	'checkbox',
	'radio',
	'multiselect'
];	
var noplaceholderfields = [
	'select',
	'checkbox',
	'radio',
	'multiselect',
	'submit',
	'submitimage',
	'file',
	'multifile'
];	
var noiconfields = [
	'select',
	'checkbox',
	'radio',
	'multiselect',
	'submit',
	'submitimage',
	'textarea'/*,
	'file',
	'multifile'*/
];	

var dragcontroller = {};

jQuery(document).ready(function($){
	$('body').css('background-color','inherit');
	if( $('div.updated.fade').length > 0){
		$('div.updated.fade').css('opacity','1');
	}
	$.each(fieldtypes,function(key, val){
		//console.log(val.type);
		if(val.type == 'divider'){
			$( '#main_right_container').append('<h4>'+val.label+'</h4>');
		} else {
			var b = '<button data-type="'+ key +'" class="btn btn-default">';
			if(typeof val.icon!='undefined'){
				if(val.icon.indexOf('fa-')!=-1){
					b += '<span class="fa '+val.icon+'"></span> ';
				} else {
					b += '<span>'+val.icon+'</span> ';
				}
			}
			b += ''+val.label+'</button>';
			$( '#main_right_container').append(b);
		}
	});
	
	if( $('#psfb_json').val().length>0 && $('#psfb_json').val().indexOf('[{') != -1){
		var jf = JSON.parse( $('#psfb_json').val() );
		if(typeof jf.link_love!='undefined' && jf.link_love==true){
			$('#ps_link_love').prop('checked','checked');
		}
		if(typeof jf.planso_style!='undefined' && jf.planso_style==true){
			$('#planso_style').prop('checked','checked');
		}
		if(typeof jf.javascript_antispam!='undefined' && jf.javascript_antispam==true){
			$('#javascript_antispam').prop('checked','checked');
		}
		var j = jf.fields;
		//console.log(j);
		$.each(j,function(k,v){
			$.each(v,function(i,val){
				ps_field_drop( false, false, i, val, false );
			});
		});
	}
	
	$( '#main_right_container .btn' ).css({'cursor':'move'}).draggable({
		appendTo: 'body',
    helper: 'clone',
    cancel: false,
    start:function(event,ui){
    	$( '.form_builder_stage .row' ).each(function(){
    		var cont_cnt = $(this).find('.field_container').length;
    		$(this).find('.field_container').each(function(){
    			$(this).attr('class','field_container').addClass('col-md-'+ Math.ceil(12/(parseInt(cont_cnt)+1))+'');
    		});
    		$(this).append('<div class="droparea field_container col-md-'+ Math.ceil(12/(parseInt(cont_cnt)+1))+'"></div>');
    	});
    	$( '.form_builder_stage .droparea' ).each(function(){
    		$(this).height( $(this).parent().height() );
    	});
    	$( '<div class="row"><div class="droparea field_container col-md-12"></div></div>' ).insertBefore('.form_builder_stage .row');
      //console.log(ui);
      $('.form_builder_stage').append( '<div class="row"><div class="droparea field_container col-md-12"></div></div>' );
      dragcontroller.dropped = false;
      $( '.form_builder_stage .droparea' ).droppable({
		    accept: '.btn',
 //   		activeClass: 'bg-warning',
    		hoverClass: 'bg-success',
		    drop: function( event, ui ) {
		    	//console.log(event);
		    	//console.log(ui);
		    	//console.log(event.currentTarget.id);
		    	
		    	dragcontroller.dropped = true;
		      ps_field_drop( event, ui, $(this), false, false );
		      
  				ps_remove_dropareas();
		    }
		  });
			
      
    },
    stop:function(event,ui){
    	if(!dragcontroller.dropped){
    		//if not dropped remove all dropareas
	    	ps_remove_dropareas();
	    }
    }
  });
 
	$('.psfb_save_perform').click(function(){
		$('.psfb_save_html').trigger('click');
	});
	$('.psfb_save_html').click(function(){
		$('.psfb_generate_json').trigger('click');
		$('#psfb_html').val( $( '.form_builder_stage' ).html() );
	});
	
	$('.psfb_generate_json').click(function(){
		var j = [];
		$('.form_builder_stage>div.row').each(function(){

			var rind = $(this).index();
			j[rind] = {};			
			
			$(this).find('.field_container').each(function(){	
				var mytype = $(this).data('type');
				var mid = $(this).data('id');
				var ind = $(this).index();
				j[rind][ind] = {};			
				j[rind][ind].type = mytype;
				j[rind][ind].id = mid;
				
				if(mytype.indexOf('submit')!=-1){
					var label = $(this).find('#field'+mid+'').attr('value');
				} else {
					var label = $(this).find('label[for="field'+mid+'"]').html();
				}
				var help_text = $(this).find('.help-block').html();
				
				var myclass = '';
				try{
					myclass = $(this).find('#field'+mid+'').attr('class').replace('form-control','');
				}catch(e){}
					
				var placeholder = '';
				try{
					placeholder = $(this).find('#field'+mid+'').attr('placeholder');
				}catch(e){}
					
				var name = '';
				try{
					name = $(this).find('#field'+mid+'').attr('name');
				}catch(e){}
				
				var  required = $(this).find('#field'+mid+'').prop('required');
				if (typeof required != typeof 'undefined' && (required == true || required=='true' || required=='required')) {
					required = true;
					label = label.replace('*','');
				} else {
					required = false;
				}
				
				var  style = $(this).find('#field'+mid+'').attr('style');
				if (typeof style !== typeof undefined && style !== false) {
					
				} else {
					style = '';
				}
				
				var icon = '';
				if( $(this).find('.input-group').length > 0 ){
					icon = $(this).find('.input-group span.fa').attr('class').replace('fa ','');
				}
				
				j[rind][ind].label = label;
				j[rind][ind].help_text = help_text;
				j[rind][ind].class = myclass;
				j[rind][ind].style = style;
				j[rind][ind].required = required;
				j[rind][ind].placeholder = placeholder;
				j[rind][ind].icon = icon;
				j[rind][ind].name = name;
				
				
				
	 			if( $.inArray(mytype,selectfields)!= -1 ){
	 				var opts = [];
	 				if(mytype=='select' || mytype=='multiselect'){
	 					
	 					$(this).find('select option').each(function(i){
	 						opts[i] = {};
	 						opts[i].label = $(this).text();
	 						opts[i].val = $(this).attr('value');
	 					});
	 				} else if(mytype=='checkbox'){
	 					
	 					$(this).find('.checkbox_wrapper label').each(function(i){
	 						opts[i] = {};
	 						opts[i].label = $(this).text();
	 						opts[i].val = $(this).find('input').attr('value');
	 					});
	 				} else if(mytype=='radio'){
	 					
	 					$(this).find('.radio_wrapper label').each(function(i){
	 						opts[i] = {};
	 						opts[i].label = $(this).text();
	 						opts[i].val = $(this).find('input').attr('value');
	 					});
	 				}
	 				j[rind][ind].select_options = opts;
	 			} else {
	 				//no select field
	 			}
				
			});
		});
		
		
		var jj = {fields:j};
		
		jj.mails = {};
		jj.mails.admin_mail = {};
		jj.mails.admin_mail.content = $('#admin_mail_content').val();
		jj.mails.admin_mail.subject = $('#admin_mail_subject').val();
		jj.mails.admin_mail.from_name = $('#admin_mail_from_name').val();
		jj.mails.admin_mail.from_email = $('#admin_mail_from_email').val();
		jj.mails.admin_mail.reply_to = $('#admin_mail_reply_to').val();
		jj.mails.admin_mail.recipients = $('#admin_mail_recipients').val().split(';');
		jj.mails.admin_mail.bcc = $('#admin_mail_bcc').val().split(';');
		
		jj.mails.user_mail = {};
		jj.mails.user_mail.content = $('#user_mail_content').val();
		jj.mails.user_mail.subject = $('#user_mail_subject').val();
		jj.mails.user_mail.from_name = $('#user_mail_from_name').val();
		jj.mails.user_mail.from_email = $('#user_mail_from_email').val();
		jj.mails.user_mail.reply_to = $('#user_mail_reply_to').val();
		jj.mails.user_mail.recipients = $('#user_mail_recipients').val().split(';');
		jj.mails.user_mail.bcc = $('#user_mail_bcc').val().split(';');
		
		if( $('#ps_link_love').is(':checked') ){
			jj.link_love = true;
		} else {
			jj.link_love = false;
		}
		if( $('#planso_style').is(':checked') ){
			jj.planso_style = true;
		} else {
			jj.planso_style = false;
		}
		if( $('#javascript_antispam').is(':checked') ){
			jj.javascript_antispam = true;
		} else {
			jj.javascript_antispam = false;
		}
		jj.thankyou_page_url = $('#thankyou_page_url').val();
		
		$('#psfb_title').val( $('.psfb_title_input').val() );
		$('#psfb_json').val( JSON.stringify( jj ) );
	});
	
	
	$('#admin_mail_content,#user_mail_content,#admin_mail_subject,#user_mail_subject,#admin_mail_recipients,#user_mail_recipients,#admin_mail_bcc,#user_mail_bcc,#admin_mail_from_email,#user_mail_from_email,#admin_mail_from_name,#user_mail_from_name,#admin_mail_reply_to,#user_mail_reply_to').focusout(function(){
		if( $(this).attr('id').indexOf('admin') != -1){
			var mode = 'admin';
		} else {
			var mode = 'user';
		}
		$('.ps_add_variable[data-mode="'+mode+'"]').data('field', $(this).attr('id')).data('position', $(this).getCursorPosition() );
		
	});
	
	
});
function ps_remove_dropareas(){
	var $ = jQuery;
	$( '.form_builder_stage .droparea' ).each(function(){
		if( $(this).parent().find('.field_container').length > 1){
			$(this).remove();
		} else {
			$(this).parent().remove();
		}
	});
	$( '.form_builder_stage .row').each(function(){
		var cont_cnt = $(this).find('.field_container').length;
		$(this).find('.field_container').each(function(){
			$(this).attr('class','field_container').addClass('col-md-'+ Math.ceil(12/(parseInt(cont_cnt)))+'');
		});
 	});
}
function ps_field_drop( event, ui, target, j, createcol ){
	var $ = jQuery;
	if(j == false){
		var me = ui.draggable;
	  var mytype = me.data('type');
	  var myLabel = fieldtypes[mytype].label;
	  //console.log(ui);
	  //console.log(target);
	  //console.log(event);
	  if( $(target).hasClass('droparea') ){
	  	//console.log('I am in droparea');
	  	if( $(target).parent().find('.field_container').length > 1){
	  		//console.log('I am in existing row');
	  		var row_mode = 'col';
	  	} else {
	  		//console.log('I am in new row');
	  		var row_mode = 'new';
	  	}
	  }
	} else {
		var mytype = j.type;
		var myLabel = j.label;
		if(target==0){
	  	var row_mode = 'plain';
	  } else {
	  	var row_mode = 'plain_col';
	  }
	}
	//console.log(row_mode);
  var row = '';
  var dynID = $( '.form_builder_stage .form-group' ).length;
  while( $('#field'+dynID).length > 0 ){
  	dynID ++;
  }
  var myFieldType = fieldtypes[mytype];
  //var myLabel = myFieldType.label;
  //console.log(target);
  
  if(row_mode=='plain'){
 		row += '<div class="row" data-type="'+mytype+'" data-id="'+dynID+'">';
  }
  row += '<div class="col-md-12 field_container" data-type="'+mytype+'" data-id="'+dynID+'">';
  
  row += '<div class="options">'+$('.editoptions_template').html()+'</div>';
  
  row += '<div class="form-group">';
  
  if(mytype != 'submit' && mytype!='submitimage'){
  	
	  row += '<label for="field'+dynID+'" class="field_label">'+myLabel;
	  
		if(typeof j.required!='undefined' && (j.required==true || j.required=='true' || j.required=='required')){
			row += '*';
	  }
		row += '</label>';
	}
	
	
  if( $.inArray(mytype,specialfields)!= -1 ){
    //special cases
    if(mytype == 'textarea'){
    	row += '<textarea id="field'+dynID+'" class="form-control';
	    if(typeof j.class!='undefined' && j.class!=''){
	    	row += j.class;
	    }
	    row += '"';
	    if(typeof j.required!='undefined' && (j.required==true || j.required=='true' || j.required=='required')){
	    	row += ' required="required"';
	    }
	    if(typeof j.style!='undefined' && j.style!=''){
	    	row += ' style="'+j.style+'"';
	    }
	    if(typeof j.cols!='undefined' && j.cols!=''){
	    	row += ' cols="'+j.cols+'"';
	    }
	    if(typeof j.rows!='undefined' && j.rows!=''){
	    	row += ' rows="'+j.rows+'"';
	    }
	    if(typeof j.placeholder!='undefined' && j.placeholder!='' && j.placeholder!='undefined'){
	    	row += ' placeholder="'+j.placeholder+'"';
	    }
	    
	    if(typeof j.name!='undefined' && j.name!='' && j.name!='undefined'){
	    	row += ' name="'+j.name.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_')+'"';
	    } else {
	    	row += ' name="'+myLabel.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_')+'"';
	    }
	    row += '></textarea>';
	    
    } else if(mytype == 'submit' || mytype=='submitimage'){
    	
    	row += '<input type="'+myFieldType.type+'" id="field'+dynID+'"';
    	row += ' class="';
    	if(mytype=='submit' || (typeof j.src=='undefined' || j.src=='')){
    		row += 'btn btn-primary';
    	}
	    if(typeof j.class!='undefined' && j.class!=''){
	    	if(j.class.indexOf('btn-primary')!=-1){
	    		j.class = j.class.replace(/btn-primary/g,'',j.class);
	    	}
	    	if(j.class.indexOf('btn ')!=-1){
	    		j.class = j.class.replace(/btn /g,'',j.class);
	    	}
	    	row += ' '+j.class;
	    }
	    row += '"';
	    
	    if(typeof j.style!='undefined' && j.style!=''){
	    	row += ' style="'+j.style+'"';
	    }
	    if(typeof j.label!='undefined' && j.label!=''){
	    	row += ' value="'+j.label+'"';
	    } else {
	    	row += ' value="<?php echo __('Submit','psfbldr'); ?>"';
	    }
	    if(mytype=='submitimage' && typeof j.src!='undefined' && j.src!=''){
	    	row += ' src="'+j.src+'"';
	    }
	    row += '>';
    } else if(mytype == 'radio'){
    	
    	if(j==false || typeof j.select_options == 'undefined' || j.select_options.length<1){
    		row += '<div class="radio_wrapper">';
				row += '<label class="radio-inline"><input type="radio" name="optionsfield'+dynID+'" value="">';
				row += myLabel+' 1';
				row += '</label>';
				//row += '</div>';
    		//row += '<div class="radio">';
				row += '<label class="radio-inline"><input type="radio" name="optionsfield'+dynID+'" value="">';
				row += myLabel+' 2';
				row += '</label>';
				row += '</div>';
			} else {
				//select_options":[{"label":"Radio-Schaltfläche 1","val":""},{"
				row += '<div class="radio_wrapper">';
				$.each(j.select_options,function(key,value){
					row += '<label class="radio-inline"><input type="radio"  value="'+value.val+'"';
					//name="optionsfield'+dynID+'"
			    if(typeof j.name!='undefined' && j.name!='' && j.name!='undefined'){
			    	row += ' name="'+j.name.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_')+'"';
			    } else {
			    	row += ' name="'+myLabel.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_')+'"';
			    }
					row += '>';
					row += value.label;
					row += '</label>';
				});
				row += '</div>';
			}
    } else if(mytype == 'checkbox'){
    	if(j==false || typeof j.select_options == 'undefined' || j.select_options.length<1){
	    	row += '<div class="checkbox_wrapper">';
				row += '<label class="checkbox-inline"><input type="checkbox" value="">';
				row += myLabel+' 1';
				row += '</label>';
			//	row += '</div>';
	    //	row += '<div class="checkbox">';
				row += '<label class="checkbox-inline"><input type="checkbox" value="">';
				row += myLabel+' 2';
				row += '</label>';
				row += '</div>';
			} else {
				//select_options":[{"label":"Radio-Schaltfläche 1","val":""},{"
				row += '<div class="checkbox_wrapper">';
				$.each(j.select_options,function(key,value){
					row += '<label class="checkbox-inline"><input type="checkbox"';
					
			    if(typeof j.name!='undefined' && j.name!='' && j.name!='undefined'){
			    	row += ' name="'+j.name.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_')+'"';
			    } else {
			    	row += ' name="'+myLabel.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_')+'"';
			    }
					row += ' value="'+value.val+'">';
					row += value.label;
					row += '</label>';
				});
				row += '</div>';
			}
    } else if(mytype == 'select' || mytype == 'multiselect'){
    	row += '<select id="field'+dynID+'" class="form-control';
	    if(typeof j.class!='undefined' && j.class!=''){
	    	row += j.class;
	    }
	    row += '"';
    	if(typeof myFieldType.multiple!='undefined' && myFieldType.multiple==true){
	    	row += ' multiple="multiple"';
	    }
	    if(typeof j.required!='undefined' && (j.required==true || j.required=='true' || j.required=='required')){
	    	row += ' required="required"';
	    }
	    if(typeof j.style!='undefined' && j.style!=''){
	    	row += ' style="'+j.style+'"';
	    }
	    
	    if(typeof j.name!='undefined' && j.name!='' && j.name!='undefined'){
	    	row += ' name="'+j.name.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_')+'"';
	    } else {
	    	row += ' name="'+myLabel.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_')+'"';
	    }
    	row += '>';
    	if(j==false || typeof j.select_options == 'undefined' || j.select_options.length<1){
	    	row += '<option value="">'+myLabel+'</option>';
	    } else {
	    	$.each(j.select_options,function(key,value){
	    		row += '<option value="'+value.val+'">'+value.label+'</option>';
	    	});
	    }
	    row += '</select>';
    }
  } else {
  	
  	//input group
  	
    if(typeof j.icon!='undefined' && j.icon!='' && j.icon!='undefined'){
    	//console.log(j.icon);
	  	row += '<div class="input-group">';
	  	row += '<div class="input-group-addon">';
	  	row += '<span class="fa '+j.icon+'"></span>';
	  	row += '</div>';
  	}
  	//console.log(myFieldType);
    row += '<input id="field'+dynID+'" type="'+ myFieldType.type +'"';
    if(typeof myFieldType.multiple!='undefined' && myFieldType.multiple==true){
    	row += ' multiple="multiple"';
    }
    if(typeof j.required!='undefined' && (j.required==true || j.required=='true' || j.required=='required')){
    	row += ' required="required"';
    }
    row += ' class="form-control';
    if(typeof j.class!='undefined' && j.class!='' && j.class!='undefined'){
    	row += j.class;
    }
    row += '"';
    
    if(typeof j.style!='undefined' && j.style!='' && j.style!='undefined'){
    	row += ' style="'+j.style+'"';
    }
    if(typeof j.min!='undefined' && j.min!='' && j.min!='undefined'){
    	row += ' min="'+j.min+'"';
    }
    if(typeof j.max!='undefined' && j.max!='' && j.max!='undefined'){
    	row += ' max="'+j.max+'"';
    }
    if(typeof j.placeholder!='undefined' && j.placeholder!='' && j.placeholder!='undefined'){
    	row += ' placeholder="'+j.placeholder+'"';
    }
    if(typeof j.name!='undefined' && j.name!='' && j.name!='undefined'){
    	row += ' name="'+j.name.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_')+'"';
    } else {
    	row += ' name="'+myLabel.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_')+'"';
    }
    row += '>';
    
    if(typeof j.icon!='undefined' && j.icon!='' && j.icon!='undefined'){
   		row += '</div>';//input-group
   	}
  }
  
  row += '<p class="help-block">';
  if(typeof j.help_text!='undefined' && j.help_text!='' && j.help_text!='undefined'){
  	row += j.help_text;
  }
  row += '</p>';
  
  row += '</div>';
  
  row += '</div>';
  
  
  //$( this ).append( row );
  if(row_mode=='plain'){
  	row += '</div>';//end row
  	$( '.form_builder_stage' ).append( row );
  } else if(row_mode=='plain_col'){
  	$( '.form_builder_stage .row:last-child' ).append( row );
  	var colcnt = $( '.form_builder_stage .row:last-child .field_container' ).length;
  	$( '.form_builder_stage .row:last-child .field_container' ).attr('class','field_container').addClass('col-md-'+ Math.floor( 12 / colcnt) +'');
  } else {
  	$( row ).insertAfter( $(target) );
  }
  
  ps_manage_form_vars();
  
  $('.form_builder_stage').sortable({
  	appendTo: document.body,
  	axis: 'y',
  	containment: 'parent',
//  	cursorAt: { left: 5 },
  	forceHelperSize: true,
  	handle: '.options .move-v',
  	tolerance: 'pointer',
  	cursor:'ns-resize',
  	items:'>div'
  });
  $('.form_builder_stage .row').sortable({
//  	appendTo: document.body,
  	axis: 'x',
  	containment: 'parent',
  	tolerance: 'pointer',
//  	cursorAt: { right: 5 },
//  	forcePlaceholderSize: true,
  	helper: 'clone',
  	forceHelperSize: true,
  	handle: '.options .move-h',
  	cursor:'ew-resize',
  	items:'>div'
  });
  /*
  $( '.form_builder_stage .row' ).droppable({
    accept: '.btn',
    drop: function( event, ui ) {
    	//console.log(event);
    	//console.log(ui);
    	//console.log(event.currentTarget.id);
      ps_field_drop( event, ui, $(this) );
    }
  });
  */
  
  $('.form_builder_stage button.delete').unbind('click').click(function(){
  	if( $(this).closest('.row').find('.field_container').length > 1){
  		$(this).closest('.field_container').remove();
  	} else {
  		$(this).closest('.row').remove();
  	}
  	ps_remove_dropareas();
  });
  $('.form_builder_stage button.edit').unbind('click').click(function(){
  	
  	var mytype = $(this).closest('.field_container').data('type');
  	
  	if(mytype.indexOf('submit')!=-1){
  		$('#field_label').val( $(this).closest('.field_container').find('.form-group :input').attr('value') );
  	} else {
	  	$('#field_label').val( $(this).closest('.field_container').find('.form-group .field_label').html().replace('*','') );
	  }
	  if( $('#field_label').val()=='undefined'){
  		$('#field_label').val('');
  	}
  	$('#field_label').unbind('change').change(function(){
  		$('#field_name').val( $(this).val().replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_') );
  	});
  	$('#field_helptext').val( $(this).closest('.field_container').find('.form-group .help-block').html() );
  	if( $('#field_helptext').val()=='undefined'){
  		$('#field_helptext').val('');
  	}
  	$('#field_placeholder').val( $(this).closest('.field_container').find('.form-group :input').attr('placeholder') );
  	if( $('#field_placeholder').val()=='undefined'){
  		$('#field_placeholder').val('');
  	}
  	$('#field_cssstyle').val( $(this).closest('.field_container').find('.form-group :input').attr('style') );
  	if( $('#field_cssstyle').val()=='undefined'){
  		$('#field_cssstyle').val('');
  	}
  	var cssclass = $(this).closest('.field_container').find('.form-group :input').attr('class');
  	if(typeof cssclass!='undefined'){
  		if(cssclass.indexOf('form-control')!=-1){
  			cssclass=cssclass.replace('form-control','');
  		}
  		if(cssclass.indexOf('btn btn-primary')!=-1){
  			cssclass=cssclass.replace('btn btn-primary','');
  		}
  	} else {
  		cssclass = '';
  	}
  	$('#field_cssclass').val( cssclass );
  	if( $('#field_cssclass').val()=='undefined'){
  		$('#field_cssclass').val('');
  	}
  	$('#field_name').val( $(this).closest('.field_container').find('.form-group :input').attr('name') );
  	if( $('#field_name').val()=='undefined'){
  		$('#field_name').val('');
  	}
  	$('#field_name_orig').val( $('#field_name').val() );
  	
  	
  	
  	var req = $(this).closest('.field_container').find('.form-group :input').prop('required');
  	//console.log(req);
  	if(typeof req!='undefined' && (req == 'required' || req==true || req=='true') ){
  		req = true;
  		$('#field_required').prop('checked',true);
  	} else {
  		req = false;
  		$('#field_required').prop('checked',false);
  	}
  	
  	$('#field_icon').val('');
  	if( $(this).closest('.field_container').find('.input-group').length > 0){
  		$('#field_icon').val( $(this).closest('.field_container').find('.input-group .fa').attr('class').replace('fa ','') );
  	}
  	
  	
  	if( $.inArray(mytype,noiconfields)!= -1  ){
  		$('.field_icon_wrapper').hide();
  	} else {
  		$('.field_icon_wrapper').show();
  	}
  	if( $.inArray(mytype,noplaceholderfields)!= -1  ){
  		$('.field_placeholder_wrapper').hide();
  	} else {
  		$('.field_placeholder_wrapper').show();
  	}
  		
  	if( $.inArray(mytype,selectfields)!= -1 ){
    	//selectfield
    	
    	$('.selectoptionstab').show();
    	$('.selectoptions_content').html('');
    	if(mytype == 'select' || mytype == 'multiselect'){
  			$(this).closest('.field_container').find('option').each(function(){
  				var h = $('.selectoptions_template').html();
  				$('.selectoptions_content').append(h);
  				$('.selectoptions_content .field_option_value:last').val( $(this).attr('value') );
  				$('.selectoptions_content .field_option_label:last').val( $(this).text() );
  				$('.selectoptions_content .delete_selectoption:last').unbind('click').click(function(){
  					$(this).closest('.row').remove();
  				});
  			});
  		} else if(mytype == 'radio'){
  			$(this).closest('.field_container').find('input[type="radio"]').each(function(){
  				var h = $('.selectoptions_template').html();
  				$('.selectoptions_content').append(h);
  				$('.selectoptions_content .field_option_value:last').val( $(this).attr('value') );
  				$('.selectoptions_content .field_option_label:last').val( $(this).parent().text() );
  				$('.selectoptions_content .delete_selectoption:last').unbind('click').click(function(){
  					$(this).closest('.row').remove();
  				});
  			});
  		} else if(mytype == 'checkbox'){
  			$(this).closest('.field_container').find('input[type="checkbox"]').each(function(){
  				var h = $('.selectoptions_template').html();
  				$('.selectoptions_content').append(h);
  				$('.selectoptions_content .field_option_value:last').val( $(this).attr('value') );
  				$('.selectoptions_content .field_option_label:last').val( $(this).parent().text() );
  				$('.selectoptions_content .delete_selectoption:last').unbind('click').click(function(){
  					$(this).closest('.row').remove();
  				});
  			});
  		}
  		$('.selectoptions_content').sortable({
  			appendTo: document.body,
		  	axis: 'y',
		  	containment: 'parent',
		  	forceHelperSize: true,
		  	tolerance: 'pointer',
		  	cursor:'ns-resize',
		  	items:'>div'
  		});
  		$('.add_selectoption').unbind('click').click(function(){
  			var h = $('.selectoptions_template').html();
  			$('.selectoptions_content').append(h);
				$('.selectoptions_content .delete_selectoption:last').unbind('click').click(function(){
					$(this).closest('.row').remove();
				});
  		});
  		
  	} else {
  		$('.selectoptionstab').hide();
  	}
  	
  	$('#fieldeditor').modal('show').data('type',$(this).closest('.field_container').data('type') ).data('id',$(this).closest('.field_container').data('id') );
  	$('#fieldeditor a:first').tab('show');
  	/************ SAVE BUTTON *********/
  	$('#fieldeditor .savefield').unbind('click').click(function(){
  		var myID = $('#fieldeditor').data('id');
  		var mytype = $('#fieldeditor').data('type');
  		
  		$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('placeholder',$('#field_placeholder').val());
  		$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('style',$('#field_cssstyle').val());
  		
  		if(mytype.indexOf('submit')!=-1){
  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('value',$('#field_label').val());
  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('class', 'btn btn-primary '+$('#field_cssclass').val() );
  			
  		} else {
  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('name',$('#field_name').val());
  			
  			if( $('#field_name_orig').val()!=$('#field_name').val() ){
  				//achtung variablen sind nicht mehr identisch - evtl. alert oder automatisch tauschen (alter wert gegen neuen wert?
  				
  				$('#admin_mail_content').val( $('#admin_mail_content').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
  				$('#user_mail_content').val( $('#user_mail_content').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
  				
  				$('#admin_mail_subject').val( $('#admin_mail_subject').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
  				$('#user_mail_subject').val( $('#user_mail_subject').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
  				
  				$('#admin_mail_recipients').val( $('#admin_mail_recipients').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
  				$('#user_mail_recipients').val( $('#user_mail_recipients').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
  				
  				$('#admin_mail_bcc').val( $('#admin_mail_bcc').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
  				$('#user_mail_bcc').val( $('#user_mail_bcc').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
  				
  				$('#admin_mail_from_name').val( $('#admin_mail_from_name').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
  				$('#user_mail_from_name').val( $('#user_mail_from_name').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
  				
  				$('#admin_mail_from_email').val( $('#admin_mail_from_email').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
  				$('#user_mail_from_email').val( $('#user_mail_from_email').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
  				
  				$('#admin_mail_reply_to').val( $('#admin_mail_reply_to').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
  				$('#user_mail_reply_to').val( $('#user_mail_reply_to').val().replace('['+$('#field_name_orig').val()+']','['+$('#field_name').val()+']') );
  			}
  			
  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('class','form-control '+ $('#field_cssclass').val() );
  			$('.field_container[data-id="'+myID+'"]').find('.form-group .field_label').html( $('#field_label').val() );
	  		if($('#field_required').is(':checked')==1){
	  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('required','required');
	  			$('.field_container[data-id="'+myID+'"]').find('.form-group .field_label').append( '*' );
	  		} else {
	  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').removeAttr('required');
	  			$('.field_container[data-id="'+myID+'"]').find('.form-group .field_label').html( $('.field_container[data-id="'+myID+'"]').find('.form-group .field_label').html().replace('*','') );
	  		}
  		}
  		$('.field_container[data-id="'+myID+'"]').find('.form-group .help-block').html( $('#field_helptext').val() );
  		
  		if( $.inArray(mytype,selectfields)!= -1 ){
	    	//selectfield
	    	//$('#fieldeditor .selectoptions').show();
	    	
	    	if(mytype == 'select' || mytype == 'multiselect'){
  				$('.field_container[data-id="'+myID+'"]').find('.form-group select').html('');
  				$('.selectoptions_content .row').each(function(){
  					var label = $(this).find('.field_option_label').val();
  					var val = $(this).find('.field_option_value').val();
  					
  					$('select#field'+myID)
  						.append( $('<option></option>') 
				        .attr('value', val )
				        .text( label )
			        );
  				});
  				
  			} else if(mytype == 'radio'){
  				$('.field_container[data-id="'+myID+'"]').find('.radio_wrapper').html('');
  				$('.field_container[data-id="'+myID+'"]').find('.form-group .help-block').remove();
  				/*
  					$('.field_container[data-id="'+myID+'"]').find('.form-group')
  						.html( $('<div></div>')
  							.addClass('radio_wrapper') );
  				*/
  				$('.selectoptions_content .row').each(function(){
  					var label = $(this).find('.field_option_label').val();
  					var val = $(this).find('.field_option_value').val();
  					
  					$('.field_container[data-id="'+myID+'"]').find('.form-group .radio_wrapper')
  					//	.append( $('<div></div>')
  					//		.addClass('radio')
	  						.append( $('<label class="radio-inline"></label>') 
					        .append( $('<input>')
					        	.attr('type', 'radio' )
					        	.attr('name', 'field'+myID )
					        	.attr('value', val )
					        )
					        .append( label )
					    //  )
			        );
  				});
  				$('.field_container[data-id="'+myID+'"]').find('.form-group')
		        .append( $('<p></p>' )
		        	.addClass('help-block')
		        	.html( $('#field_helptext').val() )
		        );
  			} else if(mytype == 'checkbox'){
  				$('.field_container[data-id="'+myID+'"]').find('.checkbox_wrapper').html('');
  				$('.field_container[data-id="'+myID+'"]').find('.form-group .help-block').remove();
  				/*
  					$('.field_container[data-id="'+myID+'"]').find('.form-group')
  						.html( $('<div></div>')
  							.addClass('checkbox_wrapper') );
  				*/
  				$('.selectoptions_content .row').each(function(){
  					var label = $(this).find('.field_option_label').val();
  					var val = $(this).find('.field_option_value').val();
  					
  					$('.field_container[data-id="'+myID+'"]').find('.form-group .checkbox_wrapper')
  					/*	.append( $('<div></div>')
  							.addClass('checkbox')
  					*/
	  						.append( $('<label class="checkbox-inline"></label>') 
					        .append( $('<input>')
					        	.attr('type', 'checkbox' )
					        	.attr('name', 'field'+myID )
					        	.attr('value', val )
					        )
					        .append( label )
					   //   )
			        );
  				});
  				$('.field_container[data-id="'+myID+'"]').find('.form-group')
		        .append( $('<p></p>' )
		        	.addClass('help-block')
		        	.html( $('#field_helptext').val() )
		        );
  				
  			}
  		} else {
  			//no selectfield
  			if( $('.field_container[data-id="'+myID+'"]').find('.input-group').length>0){
  				//remove input-group
  				$('.field_container[data-id="'+myID+'"]').find('.input-group-addon').remove();
  				$('.field_container[data-id="'+myID+'"]').find('.form-group input').unwrap();
  			}
  			if( $('#field_icon').val()!=''){
  				//add input-group
  				$('.field_container[data-id="'+myID+'"]').find('.form-group input').wrap('<div class="input-group"></div>');
  				$('.field_container[data-id="'+myID+'"]').find('.input-group').prepend('<div class="input-group-addon"><span class="fa '+$('#field_icon').val()+'"></span></div>');
  			} else {
  				
  			}
  		}
  		
  		$('#fieldeditor').modal('hide');
  		ps_manage_form_vars();
  	});
  });
}


function ps_manage_form_vars(){
	var $ = jQuery;
	var h = '';
	var attrnames = [];
	$('.form_builder_stage .field_container .form-group').each(function(){
		$(this).find(':input').each(function(){
			var attr = $(this).attr('name');
			if( typeof attr != 'undefined' && attr.length > 0 ){
				if( $.inArray(attr,attrnames)!=-1 ){
					
				} else {
					h += '<button type="button" class="btn btn-default ps_add_variable" data-mode="admin" data-field="admin_mail_content" data-position="0" data-name="'+ attr +'">['+ attr +']</button>';
					attrnames.push(attr);
				}
			}
		});
	})
	
	$('.ps_admin_mail_variables_stage').html( h );
	$('.ps_user_mail_variables_stage').html( h.replace(/admin/g,'user') );
	
	$('.ps_add_variable').click( function() {
		var mode = 'user';
		if( $(this).data('mode') == 'admin'){
			var mode = 'admin';
		}
		var el = $('#'+ $(this).data('field') );
		
		if(el != false){
	    var caretPos = $(this).data('position');
	    var textAreaTxt = el.val();
	    var txtToAdd = '['+$(this).data('name')+'] ';
	    el.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos) );
	    //el.focus();
	    var newCaretPos = (parseInt(caretPos)+parseInt(txtToAdd.length));
	    el.setSelection(newCaretPos,newCaretPos);
	   	
	  } else {
	  	alert('<?php echo __('Please position the cursor at the position/field where you want to insert the variable','psfbldr'); ?>');
	  }
	});
	
}







jQuery.fn.getCursorPosition = function(){
    if(this.lengh == 0) return -1;
    return jQuery(this).getSelectionStart();
};
jQuery.fn.getSelection = function(){
    if(this.lengh == 0) return -1;
    var s = jQuery(this).getSelectionStart();
    var e = jQuery(this).getSelectionEnd();
    return this[0].value.substring(s,e);
};
jQuery.fn.getSelectionStart = function(){
    if(this.lengh == 0) return -1;
    input = this[0];
 
    var pos = input.value.length;
 
    if (input.createTextRange) {
        var r = document.selection.createRange().duplicate();
        r.moveEnd('character', input.value.length);
        if (r.text == '') 
        pos = input.value.length;
        pos = input.value.lastIndexOf(r.text);
    } else if(typeof(input.selectionStart)!="undefined")
    pos = input.selectionStart;
 
    return pos;
};
jQuery.fn.getSelectionEnd = function(){
    if(this.lengh == 0) return -1;
    input = this[0];
 
    var pos = input.value.length;
 
    if (input.createTextRange) {
        var r = document.selection.createRange().duplicate();
        r.moveStart('character', -input.value.length);
        if (r.text == '') 
        pos = input.value.length;
        pos = input.value.lastIndexOf(r.text);
    } else if(typeof(input.selectionEnd)!="undefined")
    pos = input.selectionEnd;
 
    return pos;
};
jQuery.fn.setSelection = function(selectionStart, selectionEnd) {
    if(this.lengh == 0) return this;
    input = this[0];
 
    if (input.createTextRange) {
        var range = input.createTextRange();
        range.collapse(true);
        range.moveEnd('character', selectionEnd);
        range.moveStart('character', selectionStart);
        range.select();
    } else if (input.setSelectionRange) {
        input.focus();
        input.setSelectionRange(selectionStart, selectionEnd);
    }
 
    return this;
};
jQuery.fn.setCursorPosition = function(position){
    if(this.lengh == 0) return this;
    return jQuery(this).setSelection(position, position);
};


</script>
<style type="text/css">

.form_builder_stage .field_container .options{
	display:none;
	margin-bottom:-40px;
	z-index:5;
	float:right;
	position:relative;
	top:0px;
	right:0px;
}
.form_builder_stage .field_container:hover .options{
	display:block;
	margin-top:-5px;
}

.droparea{
//	background:#ffffdd;
	border:1px dashed lightgray;
	min-height:50px;
	margin-bottom:0.5em;
}
.form_builder_stage .row:last-child{
	padding-bottom:25px;
}
</style>


<div class="editoptions_template" style="display:none;">
<button type="button" class="delete btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span> <?php echo __('Delete','psfbldr'); ?></button>
<button type="button" class="edit btn btn-success btn-xs"><span class="glyphicon glyphicon-edit"></span> <?php echo __('Edit','psfbldr'); ?></button>
	<div type="button" class="move-h btn btn-default btn-xs" style="cursor:ew-resize;"><span class="fa fa-arrows-h"></span> <?php echo __('Move','psfbldr'); ?></div>
	<div type="button" class="move-v btn btn-default btn-xs" style="cursor:ns-resize;"><span class="fa fa-arrows-v"></span> <?php echo __('Move','psfbldr'); ?></div>
</div>

<div class="modal fade" id="fieldeditor" tabindex="-1" role="dialog" aria-labelledby="fieldeditorlabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="fieldeditorlabel"><?php echo __('Edit field','psfbldr'); ?></h4>
      </div>
      <div class="modal-body">
        
        
        <div role="tabpanel">
				
				  <!-- Nav tabs -->
				  <ul class="nav nav-tabs" role="tablist">
				    <li role="presentation" class="active"><a href="#tab-basics" aria-controls="basics" role="tab" data-toggle="tab"><?php echo __('Basic','psfbldr'); ?></a></li>
				    <li role="presentation" class="selectoptionstab"><a href="#tab-selectoptions" aria-controls="selectoptions" role="tab" data-toggle="tab"><?php echo __('Select values','psfbldr'); ?></a></li>
				    <li role="presentation"><a href="#tab-expert" aria-controls="profile" role="tab" data-toggle="tab"><?php echo __('Advanced','psfbldr'); ?></a></li>
				  </ul>
        
        	<div class="tab-content">
        
		        <div class="basics tab-pane active" id="tab-basics" role="tabpanel">
		        	
		        	
						  <div class="form-group">
						    <label for="field_label"><?php echo __('Field label','psfbldr'); ?></label>
						    <input type="text" id="field_label" class="form-control">
						    <p class="help-block"><?php echo __('Enter the label that describes the field','psfbldr'); ?></p>
						  </div>
						  
						  <div class="form-group field_placeholder_wrapper">
						    <label for="field_placeholder"><?php echo __('Placeholder','psfbldr'); ?></label>
						    <input type="text" id="field_placeholder" class="form-control">
						    <p class="help-block"><?php echo __('The placeholder is placed within empty fields and helps your users filling out the form','psfbldr'); ?></p>
						  </div>
						  
						  <div class="form-group">
						    <label for="field_helptext"><?php echo __('Help-Text','psfbldr'); ?></label>
						    <textarea id="field_helptext" class="form-control"></textarea>
						    <p class="help-block"><?php echo __('Provide additional help for filling out this field','psfbldr'); ?></p>
						  </div>
						  
						  <div class="form-group field_icon_wrapper">
						    <label for="field_icon"><?php echo __('Icon','psfbldr'); ?></label>
						    <input type="text" id="field_icon" class="form-control">
						    <p class="help-block"><?php echo __('Please enter a Font-Awesome icon class. i.e. &quot;fa-user&quot;. Available Icons are found here: ','psfbldr'); ?><a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font-Awesome</a></p>
						  </div>
						  
						  <div class="form-group">
						    <label for="field_required"><?php echo __('Mandatory','psfbldr'); ?></label>
						  	<div class="checkbox">
						  		<label>
						  			<input type="checkbox" id="field_required" value="1"> <?php echo __('Mandatory field','psfbldr'); ?>
						  		</label>
						  	</div>
						    <p class="help-block"><?php echo __('Check this to mark the field as required','psfbldr'); ?></p>
						  </div>
		        </div><!-- ende basics -->
		        <div class="selectoptions tab-pane" id="tab-selectoptions" role="tabpanel">
		        	<div class="selectoptions_template" style="display:none;">
		        		
		        		<div class="row form-group">
		        			
		        			<div class="col-md-5">
		        				<input type="text" class="form-control field_option_label" placeholder="<?php echo __('Label','psfbldr'); ?>">
		        			</div>
		        			<div class="col-md-5">
		        				<input type="text" class="form-control field_option_value" placeholder="<?php echo __('Value','psfbldr'); ?>">
		        			</div>
		        			<div class="col-md-2">
		        				<button class="delete_selectoption btn btn-danger btn-xs" tabindex="-1"><span class="glyphicon glyphicon-trash"></span></button>
		        			</div>
		        			
		        		</div>
		        		
		        	</div>
		        	
		        	<div class="selectoptions">
		        		
							  <div class="form-group">
							    <label for="field_helptext"><?php echo __('Option value pairs','psfbldr'); ?></label>
								  <div class="row ">
			        			
			        			<div class="col-md-5" title="<?php echo __('The visible part','psfbldr'); ?>">
			        				<?php echo __('Label','psfbldr'); ?>
			        			</div>
			        			<div class="col-md-5" title="<?php echo __('The value will be submitted to you','psfbldr'); ?>">
			        				<?php echo __('Value','psfbldr'); ?>
			        			</div>
			        			<div class="col-md-2">
			        				
			        			</div>
			        			
			        		</div>
								  
			        		<div class="selectoptions_content">
			        			
			        		</div>
			        		<div class="selectoptions_option">
			        			<button class="btn btn-success btn-xs add_selectoption"><span class="glyphicon glyphicon-plus"></span> <?php echo __('Add option','psfbldr'); ?></button>
			        		</div>
			        		
							  </div>
		        	</div>
		        </div>
		        <div class="expert tab-pane" id="tab-expert" role="tabpanel">
		        	
						  <div class="form-group" style="display:none;">
						    <label for="field_name"><?php echo __('Field variable name','psfbldr'); ?></label>
						    <input type="text" id="field_name" class="form-control">
						    <input type="hidden" id="field_name_orig" >
						    <p class="help-block"><?php echo __('This variable can be used in the emails sent upon submit','psfbldr'); ?></p>
						  </div>
		        	
						  <div class="form-group">
						    <label for="field_cssclass"><?php echo __('CSS Class','psfbldr'); ?></label>
						    <input type="text" id="field_cssclass" class="form-control">
						    <p class="help-block"><?php echo __('Add an individual CSS class name to this field','psfbldr'); ?></p>
						  </div>
						  
						  <div class="form-group">
						    <label for="field_cssstyle"><?php echo __('CSS Style','psfbldr'); ?></label>
						    <input type="text" id="field_cssstyle" class="form-control">
						    <p class="help-block"><?php echo __('Format this field with custom inline CSS rules','psfbldr'); ?></p>
						  </div>
						  
		        	<!-- prefill value from get/post -->
		        </div><!-- ende expert -->
		      </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Cancel','psfbldr'); ?></button>
        <button type="button" class="btn btn-primary savefield"><?php echo __('Update','psfbldr'); ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div id="titlediv">
	<div id="titlewrap">
		<input type="text" size="30" value="<?php if(isset($psform) && isset($psform->post_title))echo $psform->post_title;?>" class="psfb_title_input" id="title" spellcheck="true" autocomplete="off" placeholder="<?php echo __('Form Title','psfbldr'); ?>">
	</div>
	<div class="inside">
		<div id="edit-slug-box" class="hide-if-no-js">
			</div>
	</div>
</div>
<!--
<section class="container-fluid">
	<section class="row">
		<div class="form-group">
			<label><?php echo __('Form Title','psfbldr'); ?></label>
			<input type="text" class="psfb_title_input form-control" value="<?php echo $psform->post_title;?>">
		</div>
	</section>
</section>
-->
<section class="container-fluid">
	<div class="row">
		<div class="form-group">
			<button class="psfb_save_perform btn btn-primary" style="float:right;"><?php echo __('Save','psfbldr'); ?></button>
			<div style="clear:both;"></div>
		</div>
	</div>
</section>

<section class="container-fluid postbox "><!-- container-fluid -->
	<section class="row">
			
		<div class="metabox-holder">
			<div class="meta-box-sortables">
				<div id="titlediv">
					<h3 class="hndle" style="cursor:default;"><span><strong><?php echo __('Form settings','psfbldr'); ?></strong></span></h3>
				</div>
				<br class="clear">
				
				<section id="main_center" class="col-md-9">
					<section id="main_center_center">
						<section id="content">
							
							<form class="form_builder_stage" onsubmit="return false;"></form>
							
							
						</section>
					</section>
				</section>
				<aside id="main_right" class="col-md-3">
					<section id="main_right_container">
						
						
					</section>
					<br class="clear">
					<br class="clear">
				</aside>
			</div>
		</div>
		
	</section><!-- ende .row -->
</section><!-- ende .container -->


<section class="container-fluid">
	<div class="row">
		<div class="form-group">
			<button class="psfb_save_perform btn btn-primary" style="float:right;"><?php echo __('Save','psfbldr'); ?></button>
			<div style="clear:both;"></div>
		</div>
	</div>
</section>



<section class="container-fluid postbox "><!-- container-fluid -->
	<section class="row">
			
		<div class="metabox-holder">
			<div class="meta-box-sortables">
				<div id="titlediv">
					<h3 class="hndle" style="cursor:default;"><span><strong><?php echo __('Thank you page','psfbldr'); ?></strong></span></h3>
				</div>
				<br class="clear">
				<section class="col-md-9">
					
						<div class="form-group">
					    <label for="thankyou_page_url"><?php echo __('Thank you page url','psfbldr'); ?></label>
					    <input type="text" placeholder="http://" id="thankyou_page_url" class="form-control thankyou_page_url" value="<?php if(isset($j->thankyou_page_url))echo $j->thankyou_page_url; ?>">
					    <p class="help-block"><?php echo __('Enter the website address (inkl. http://) of the page the user should be redirected to after successfully submitting the form','psfbldr'); ?></p>
					  </div>
					  
				</section>
			</div>
		</div>
	</section>
</section>

<section class="container-fluid">
	<div class="row">
		<div class="form-group">
			<button class="psfb_save_perform btn btn-primary" style="float:right;"><?php echo __('Save','psfbldr'); ?></button>
			<div style="clear:both;"></div>
		</div>
	</div>
</section>



<section class="container-fluid postbox ps_user_mail_wrapper"><!-- container-fluid -->
	<section class="row">
			
		<div class="metabox-holder">
			<div class="meta-box-sortables">
				<div id="titlediv">
					<h3 class="hndle" style="cursor:default;"><span><strong><?php echo __('Admin Mail settings','psfbldr'); ?></strong></span></h3>
				</div>
				<br class="clear">
				
				<section class="col-md-9">
					
					<div class="ps_admin_mail_stage">
						<?php 
							if(isset($j)){
								
								if(isset($j->mails) && isset($j->mails->admin_mail)){
									$admin_mail = $j->mails->admin_mail;
								} else {
									$admin_mail = new stdClass;
									$admin_mail->content = '';
									$admin_mail->subject = '';
									$admin_mail->from_name = '';
									$admin_mail->from_email = '';
									$admin_mail->reply_to = '';
									$admin_mail->recipients = array();
									$admin_mail->bcc = array();
								}
								if(isset($j->mails) && isset($j->mails->user_mail)){
									$user_mail = $j->mails->user_mail;
								} else {
									$user_mail = new stdClass;
									$user_mail->content = '';
									$user_mail->subject = '';
									$user_mail->from_name = '';
									$user_mail->from_email = '';
									$user_mail->reply_to = '';
									$user_mail->recipients = array();
									$user_mail->bcc = array();
								}
							} else {
								$admin_mail = new stdClass;
									$admin_mail->content = '';
									$admin_mail->subject = '';
									$admin_mail->from_name = '';
									$admin_mail->from_email = '';
									$admin_mail->reply_to = '';
									$admin_mail->recipients = array();
									$admin_mail->bcc = array();
								$user_mail = new stdClass;
									$user_mail->content = '';
									$user_mail->subject = '';
									$user_mail->from_name = '';
									$user_mail->from_email = '';
									$user_mail->reply_to = '';
									$user_mail->recipients = array();
									$user_mail->bcc = array();
							}
						?>
						<div class="form-group">
					    <label for="admin_mail_subject"><?php echo __('Admin mail subject','psfbldr'); ?></label>
					    <input type="text" id="admin_mail_subject" class="form-control admin_mail_subject" value="<?php echo $admin_mail->subject; ?>">
					    <p class="help-block"><?php echo __('This is the subject of the email sent to you/the admin','psfbldr'); ?></p>
					  </div>
						
						<?php
							//WordPress Editor
							//wp_editor( $admin_mail->content, 'admin_mail_content' , array('editor_class'=>'form-control','textarea_rows'=>'6') );//
						?> 
						
						<div class="form-group">
					    <label for="admin_mail_content"><?php echo __('Admin mail body','psfbldr'); ?></label>
					    <textarea rows="7" id="admin_mail_content" class="form-control admin_mail_content"><?php echo $admin_mail->content; ?></textarea>					    <p class="help-block"><?php echo __('This is the content of the email sent to you/the admin','psfbldr'); ?></p>
					  </div>
						
						<div class="form-group">
					    <label for="admin_mail_recipients"><?php echo __('Admin mail recipient(s)','psfbldr'); ?></label>
					    <input type="text" id="admin_mail_recipients" class="form-control admin_mail_recipients" value="<?php if(isset($admin_mail->recipients) && count($admin_mail->recipients)>0)echo implode(';',$admin_mail->recipients); ?>">
					    <p class="help-block"><?php echo __('Enter one or more recipient email address for the admin mail. Devide multiple recipients with ;','psfbldr'); ?></p>
					  </div>
						
						<div class="form-group">
					    <label for="admin_mail_bcc"><?php echo __('Admin mail bcc recipient(s)','psfbldr'); ?></label>
					    <input type="text" id="admin_mail_bcc" class="form-control admin_mail_bcc" value="<?php if(isset($admin_mail->bcc) && count($admin_mail->bcc)>0)echo implode(';',$admin_mail->bcc); ?>">
					    <p class="help-block"><?php echo __('Enter one or more bcc email address for the admin mail. Devide multiple recipients with ;','psfbldr'); ?></p>
					  </div>
					  
					  <div class="row">
					  	<div class="col-md-6">
					  		<div class="form-group">
							    <label for="admin_mail_from_name"><?php echo __('Admin mail from name','psfbldr'); ?></label>
							    <input type="text" id="admin_mail_from_name" class="form-control admin_mail_from_name" value="<?php echo $admin_mail->from_name; ?>">
							    <p class="help-block"><?php echo __('This is the senders name for the email sent to you/the admin','psfbldr'); ?></p>
							  </div>
					  	</div>
					  	<div class="col-md-6">
					  		<div class="form-group">
							    <label for="admin_mail_from_email"><?php echo __('Admin mail from email','psfbldr'); ?></label>
							    <input type="text" id="admin_mail_from_email" class="form-control admin_mail_from_email" value="<?php echo $admin_mail->from_email; ?>">
							    <p class="help-block"><?php echo __('This is the senders email adress for the email sent to you/the admin','psfbldr'); ?></p>
							  </div>
					  	</div>
					  </div>
						
						<div class="form-group">
					    <label for="admin_mail_reply_to"><?php echo __('Admin mail reply to address','psfbldr'); ?></label>
					    <input type="text" id="admin_mail_reply_to" class="form-control admin_mail_reply_to" value="<?php echo $admin_mail->reply_to; ?>">
					    <p class="help-block"><?php echo __('Enter one email address that is used as the reply adress when answering the admin mail.','psfbldr'); ?></p>
					  </div>
						
						
						
					</div>
					
				</section>
				<aside class="col-md-3">
					
					<div class="ps_admin_mail_variables_stage">
						
					</div>
					
				</aside>
			</div>
		</div>
	</section>
</section>


<section class="container-fluid">
	<div class="row">
		<div class="form-group">
			<button class="psfb_save_perform btn btn-primary" style="float:right;"><?php echo __('Save','psfbldr'); ?></button>
			<div style="clear:both;"></div>
		</div>
	</div>
</section>


<section class="container-fluid postbox "><!-- container-fluid -->
	<section class="row">
			
		<div class="metabox-holder">
			<div class="meta-box-sortables">
				<div id="titlediv">
					<h3 class="hndle" style="cursor:default;"><span><strong><?php echo __('User Mail settings','psfbldr'); ?></strong></span></h3>
				</div>
				<br class="clear">
				
				<section class="col-md-9">
					
					<div class="ps_user_mail_stage">
						
						<div class="form-group">
					    <label for="user_mail_subject"><?php echo __('User mail subject','psfbldr'); ?></label>
					    <input type="text" id="user_mail_subject" class="form-control user_mail_subject" value="<?php echo $user_mail->subject; ?>">
					    <p class="help-block"><?php echo __('This is the subject of the email sent to the user of the form','psfbldr'); ?></p>
					  </div>
						
						<?php
							//WordPress Editor
							//wp_editor( $admin_mail->content, 'admin_mail_content' , array('editor_class'=>'form-control','textarea_rows'=>'6') );//
						?> 
						
						<div class="form-group">
					    <label for="user_mail_content"><?php echo __('User mail body','psfbldr'); ?></label>
					    <textarea rows="7" id="user_mail_content" class="form-control user_mail_content"><?php echo $user_mail->content; ?></textarea>
					    <p class="help-block"><?php echo __('This is the content of the email sent to the user of the form','psfbldr'); ?></p>
					  </div>
						
						
						<div class="form-group">
					    <label for="user_mail_recipients"><?php echo __('User mail recipient(s)','psfbldr'); ?></label>
					    <input type="text" id="user_mail_recipients" class="form-control user_mail_recipients" value="<?php if(isset($user_mail->recipients) && count($user_mail->recipients)>0)echo implode(';',$user_mail->recipients); ?>">
					    <p class="help-block"><?php echo __('Enter one or more recipient email address for the user mail. Devide multiple recipients with ;','psfbldr'); ?></p>
					  </div>
						
						<div class="form-group">
					    <label for="user_mail_bcc"><?php echo __('User mail bcc recipient(s)','psfbldr'); ?></label>
					    <input type="text" id="user_mail_bcc" class="form-control user_mail_bcc" value="<?php if(isset($user_mail->bcc) && count($user_mail->bcc)>0)echo implode(';',$user_mail->bcc); ?>">
					    <p class="help-block"><?php echo __('Enter one or more bcc email address for the user mail. Devide multiple recipients with ;','psfbldr'); ?></p>
					  </div>
					  
					  <div class="row">
					  	<div class="col-md-6">
					  		<div class="form-group">
							    <label for="user_mail_from_name"><?php echo __('User mail from name','psfbldr'); ?></label>
							    <input type="text" id="user_mail_from_name" class="form-control user_mail_from_name" value="<?php echo $user_mail->from_name; ?>">
							    <p class="help-block"><?php echo __('This is the senders name for the email sent to the user','psfbldr'); ?></p>
							  </div>
					  	</div>
					  	<div class="col-md-6">
					  		<div class="form-group">
							    <label for="user_mail_from_email"><?php echo __('User mail from email','psfbldr'); ?></label>
							    <input type="text" id="user_mail_from_email" class="form-control user_mail_from_email" value="<?php echo $user_mail->from_email; ?>">
							    <p class="help-block"><?php echo __('This is the senders email adress for the email sent to the user','psfbldr'); ?></p>
							  </div>
					  	</div>
					  </div>
						
						<div class="form-group">
					    <label for="user_mail_reply_to"><?php echo __('User mail reply to address','psfbldr'); ?></label>
					    <input type="text" id="user_mail_reply_to" class="form-control user_mail_reply_to" value="<?php echo $user_mail->reply_to; ?>">
					    <p class="help-block"><?php echo __('Enter one email address that is used as the reply adress when answering the user mail.','psfbldr'); ?></p>
					  </div>
						
					</div>
					
				</section>
				<aside class="col-md-3">
					
					<div class="ps_user_mail_variables_stage">
						
					</div>
					
				</aside>
			</div>
		</div>
	</section>
</section>


<section class="container-fluid">
	<div class="row">
		<div class="form-group">
			<button class="psfb_save_perform btn btn-primary" style="float:right;"><?php echo __('Save','psfbldr'); ?></button>
			<div style="clear:both;"></div>
		</div>
	</div>
</section>


<section class="container-fluid postbox "><!-- container-fluid -->
	<section class="row">
			
		<div class="metabox-holder">
			<div class="meta-box-sortables">
				<div id="titlediv">
					<h3 class="hndle" style="cursor:default;"><span><strong><?php echo __('Additional settings','psfbldr'); ?></strong></span></h3>
				</div>
				<br class="clear">
				
				<section class="col-md-9">
					
					
						<div class="form-group checkbox">
					    <label for="ps_link_love">
					    	<input type="checkbox" id="ps_link_love" name="ps_link_love" value="1">
					    	<?php echo __('Get good karma and spread some link love','psfbldr'); ?>
					    </label>
					  </div>
					
						<div class="form-group checkbox">
					    <label for="planso_style">
					    	<input type="checkbox" id="planso_style" name="planso_style" value="1">
					    	<?php echo __('Include special Stylesheet based on bootstrap 3.0 if your form does not look good','psfbldr'); ?>
					    </label>
					  </div>
					
						<div class="form-group checkbox">
					    <label for="javascript_antispam">
					    	<input type="checkbox" id="javascript_antispam" name="javascript_antispam" value="1">
					    	<?php echo __('Enable special javascript based anti spam protection','psfbldr'); ?>
					    </label>
					    <p class="help-block"><?php echo __('If checked a special hidden field will be appended to your form via javascript. The form will break for users with javascript disabled!','psfbldr'); ?></p>
					  </div>
					  
				</section>
			</div>
		</div>
	</section>
</section>


<form method="post" class="psfb_submit_form" action="<?php echo esc_url( add_query_arg( array( 'post' => $post_id ), menu_page_url( 'ps-form-builder', false ) ) ); ?>">
<input type="hidden" name="action" value="save"/>
<div class="form-group" style="display:none;">
  <label><?php echo __('Form HTML','psfbldr'); ?></label>
  <textarea id="psfb_html" class="form-control"></textarea>
  <textarea id="psfb_title" name="title" class="form-control"></textarea>
</div>
<div class="form-group" style="display:none;">
  <label><?php echo __('JSON','psfbldr'); ?></label>
  <textarea id="psfb_json" name="json" class="form-control"><?php echo $psform->post_content;?></textarea>
</div>
<div class="form-group">
  <button class="psfb_generate_json btn btn-default" type="button" style="display:none;"><?php echo __('Generate','psfbldr'); ?></button>
  <button class="psfb_save_html btn btn-primary" style="float:right;"><?php echo __('Save','psfbldr'); ?></button>
  <div style="clear:both;"></div>
</div>
</form>
<?php 
//print_r($psform); 
?>
<div style="clear:both;"></div>
</div><!-- wrap -->
<div style="clear:both;"></div>
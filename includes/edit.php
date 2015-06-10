<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) )
	die( '-1' );

require_once( dirname(__FILE__).'/vars.inc.php' );

?><div class="wrap">
<div style="float:right;">
	<a href="https://wordpress.org/support/view/plugin-reviews/planso-forms?rate=5#postform" target="_blank" class="btn btn-success btn-xs"><i class="fa fa-heart"></i> <?php echo __('Like PlanSo Forms? Post a review!','psfbldr'); ?></a>
</div>
<h2><?php
	
	if ( !isset($_REQUEST['psfbid']) || empty($_REQUEST['psfbid']) || $_REQUEST['psfbid'] == -1 ) {
		echo esc_html( __( 'Add New Form', 'psfbldr' ) );
		$post_id = -1;
		$shortcode_out = '';
	} else {
		echo esc_html( __( 'Edit Form', 'psfbldr' ) );

		echo ' <a href="' . esc_url( menu_page_url( 'ps-form-builder-new', false ) ) . '" class="add-new-h2">' . esc_html( __( 'Add New', 'psfbldr' ) ) . '</a>';
		$post_id = $_REQUEST['psfbid'];
		$psform = get_post( $post_id);//, $output, $filter );
		
		if(isset($psform->post_content) && !empty($psform->post_content) && strstr($psform->post_content,'{')){
			$j = json_decode($psform->post_content);
		}
		
		$shortcode_out = '<div><input type="text" onfocus="this.select();" onmouseup="return false;" readonly="readonly" value="[psfb id=&quot;'.$post_id.'&quot; title=&quot;'.$psform->post_title.'&quot;]" class="shortcode-in-list-table wp-ui-text-highlight code form-control"></div>';
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
	week : {label:'<?php echo __('Week','psfbldr'); ?>',type:'week'},
	month : {label:'<?php echo __('Month','psfbldr'); ?>',type:'month'},
*/
	divider_select : {label:'<?php echo __('Select fields','psfbldr'); ?>', type:'divider'},
	select : {label:'<?php echo __('Select','psfbldr'); ?>',type:'select',icon:'fa-caret-square-o-down'},
	multiselect : {label:'<?php echo __('Multiselect','psfbldr'); ?>',type:'select',multiple:true,icon:'fa-caret-square-o-down'},
	radio : {label:'<?php echo __('Radio','psfbldr'); ?>',type:'radio',icon:'fa-dot-circle-o'},
	checkbox : {label:'<?php echo __('Checkbox','psfbldr'); ?>',type:'checkbox',icon:'fa-check-square-o'},
	divider_special : {label:'<?php echo __('Special fields','psfbldr'); ?>', type:'divider'},
/*
	range : {label:'<?php echo __('Range','psfbldr'); ?>',type:'range',min:true,max:true,step:true},
	search : {label:'<?php echo __('Search','psfbldr'); ?>',type:'search'},
	hidden : {label:'<?php echo __('Hidden','psfbldr'); ?>',type:'hidden'},
*/
	file : {label:'<?php echo __('Single file','psfbldr'); ?>',type:'file',icon:'fa-file'},
	multifile : {label:'<?php echo __('Multiple files','psfbldr'); ?>',type:'file',multiple:true,icon:'fa-folder-open'},
	url : {label:'<?php echo __('Url','psfbldr'); ?>',type:'url',icon:'fa-link'},
/*
	color : {label:'<?php echo __('Color','psfbldr'); ?>',type:'color'},
*/
	divider_html : {label:'<?php echo __('HTML Tags','psfbldr'); ?>', type:'divider'},
	
	html_div : {label:'<?php echo __('HTML Code','psfbldr'); ?>', type:'div',wrap:true,icon:'fa-terminal'},
	html_hr : {label:'<?php echo __('Horizontal Divider','psfbldr'); ?>', type:'hr',wrap:false,icon:'fa-minus'},
	html_header : {label:'<?php echo __('Headline','psfbldr'); ?>', type:'h',options:[1,2,3,4,5,6],wrap:true,icon:'fa-header'},
	html_paragraph : {label:'<?php echo __('Paragraph','psfbldr'); ?>', type:'p',wrap:true,icon:'fa-paragraph'},
	
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
var htmlfields = [
	'html_hr',
	'html_header',
	'html_paragraph',
	'html_div'
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
	'checkbox',
	'radio',
	'submit',
	'submitimage'/*,
	'select',
	'multiselect',
	'textarea',
	'file',
	'multifile'*/
];

var customfields = [];
var customelements = [];

var dragcontroller = {};
<?php do_action('psfb_edit_js_before_document_ready'); ?>

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
		if(typeof jf.mails!='undefined' && typeof jf.mails.user_mail!='undefined' && typeof jf.mails.user_mail.html_mail!='undefined' && jf.mails.user_mail.html_mail==true){
			$('#psfb_user_mail_html').prop('checked','checked');
		}
		if(typeof jf.mails!='undefined' && typeof jf.mails.admin_mail!='undefined' && typeof jf.mails.admin_mail.html_mail!='undefined' && jf.mails.admin_mail.html_mail==true){
			$('#psfb_admin_mail_html').prop('checked','checked');
		}
		if(typeof jf.link_love!='undefined' && jf.link_love==true){
			$('#ps_link_love').prop('checked','checked');
		}
		if(typeof jf.planso_style!='undefined' && jf.planso_style==true){
			$('#planso_style').prop('checked','checked');
		}
		if(typeof jf.allow_prefill!='undefined' && jf.allow_prefill==true){
			$('#allow_prefill').prop('checked','checked');
		}
		if(typeof jf.javascript_antispam=='undefined' || jf.javascript_antispam==true){
			$('#javascript_antispam').prop('checked','checked');
		}
		if(typeof jf.horizontal_form!='undefined'){
			if(jf.horizontal_form==true || jf.horizontal_form=='horizontal'){
				/*$('#horizontal_form').prop('checked','checked');*/
				$('#horizontal_form').val('horizontal');
			} else if(jf.horizontal_form==false || jf.horizontal_form=='vertical'){
				$('#horizontal_form').val('vertical');
			} else {
				$('#horizontal_form').val(jf.horizontal_form);
			}
		} else {
			$('#horizontal_form').val('vertical');
		}
		if(typeof jf.clean_attachments!='undefined' && jf.clean_attachments==true){
			$('#clean_attachments').prop('checked','checked');
		}
		if(typeof jf.datepicker!='undefined'){
			$('#psfb_datepicker').val(jf.datepicker);
		}
		if(typeof jf.date_format!='undefined'){
			$('#psfb_date_format').val(jf.date_format);
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
    addClasses: false,
    cancel: false,
    refreshPositions:true,
		start:function(event,ui){
			if( $('.form_builder_stage').is(':empty') ){
				var height = $('.form_builder_stage').height();
			} else {
				var height = 50;
			}
		  
		 	$('.form_builder_stage').append( '<div class="empty_helper_row row"><div style="height:'+height+'px;" class="droparea field_container col-md-12"></div></div>' );
		 	
		 	
			$('.field_container').droppable({
				greedy:false,
				tolerance:'intersect',
				addClasses: false,
				accept:'.btn',
				hoverClass: 'bg-success',
				over: function(event,ui){
					
  				var droppableElement = $(this);
  				var row = droppableElement.closest('.row');
  				if(droppableElement.hasClass('droparea')){
  					//ist leeres element - darf gedroppt werden
  				} else {
						ps_remove_dropareas();
	  				var cont_cnt = row.find('.field_container').length;
	  				var col_cls = Math.floor(12/(parseInt(cont_cnt)+2));
	  				
		    		row.find('.field_container').each(function(){
		    			$(this).attr('class','field_container').addClass('col-md-'+ col_cls +'');
		    		});
		    		
		    		$('<div class="droparea field_container col-md-'+ col_cls +'"></div>').insertBefore(droppableElement);
		    		$('<div class="droparea field_container col-md-'+ col_cls +'"></div>').insertAfter(droppableElement);
	  				$( '<div class="row"><div style="height:50px;" class="droparea field_container col-md-12"></div></div>' ).insertBefore(row);
	  				$( '<div class="row"><div style="height:50px;" class="droparea field_container col-md-12"></div></div>' ).insertAfter(row);
	  				
				  	$( '.form_builder_stage .droparea' ).each(function(){
				  		$(this).height( $(this).parent().height() );
				  	});
				  	
	  				$('.droparea').droppable({
	  					greedy:false,
							addClasses: false,
							hoverClass: 'bg-success',
							tolerance:'intersect',//touch,intersect,pointer,fit
							
							drop:function(event,ui){
					    	dragcontroller.dropped = true;
					      ps_field_drop( event, ui, $(this), false, false );
					      $('.droparea').droppable('destroy');
			  				ps_remove_dropareas();
							}
						});
						
	  			}
				},
				drop: function(event,ui){
					
					if( $(this).hasClass('droparea') ){
						try{$('.field_container').droppable('destroy');}catch(e){}
			    	dragcontroller.dropped = true;
			      ps_field_drop( event, ui, $(this), false, false );
			      
	  			}
	  			ps_remove_dropareas();
  				
				}
				
			});
		},
		stop:function(){
			if( $('.empty_helper_row').is(':empty') ){
				$('.empty_helper_row').remove();
			} else {
				$('.empty_helper_row').removeClass('empty_helper_row');
			}
			ps_remove_dropareas();
		}
	});
	
	$('.psfb_open_help_modal').click(function(){
		$('#psfb_help_modal').modal('show');
	});
	$('.psfb_save_perform').click(function(){
		$('.psfb_save_html').trigger('click');
	});
	$('.psfb_save_html').click(function(){
		$('.psfb_generate_json').trigger('click');
		$('#psfb_html').val( $( '.form_builder_stage' ).html() );
	});
	
	$('.psfb_test_form_submit').click(function(){
		$('.psfb_generate_json').trigger('click');
		
		var data = {
			'action': 'psfb_form_submit_test',
			'psfb_form_submit_test_values': 'doit',
			'psfb_test_json': $('#psfb_json').val()
		};
		
		$.post(ajaxurl, data, function(response) {
			alert('<?php echo __('Test submission sent. Please check your email or API target to validate the result.','psfbldr'); ?>');
		});
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
				
				if( $.inArray(mytype,htmlfields) != -1){
					
					var tag_details = fieldtypes[mytype];
					j[rind][ind].type=tag_details.type;
					var tag = tag_details.type;
					if(typeof tag_details.options != 'undefined' && tag_details.options.length>0){
						j[rind][ind].option = tag_details.options[0];
						tag += tag_details.options[0];
					}
					
					
					if(typeof tag_details.wrap != 'undefined' && tag_details.wrap===true){
						j[rind][ind].content = $(this).find('#field'+mid+'').find(tag).first().html();
					} else {
						
					}
					
					var myclass = '';
					try{
						myclass = $(this).find('#field'+mid+'').find(tag).first().attr('class').replace('form-control','');
					}catch(e){}
					
					var style = '';
					try{
						style = $(this).find('#field'+mid+'').find(tag).first().attr('style');
					}catch(e){}
						
						
				} else {
				
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
					
					if(mytype=='checkbox' || mytype=='radio'){
						var required = $(this).find('#field'+mid+'').data('required');
						var orientation = $(this).find('#field'+mid+'').data('orientation');
					} else {
						var required = $(this).find('#field'+mid+'').prop('required');
						var orientation = false;
					}
					if (typeof required != 'undefined' && (required == true || required=='true' || required=='required')) {
						required = true;
						label = label.replace('*','');
					} else {
						required = false;
					}
					
					var  hide_label = $(this).find('#field'+mid+'').data('hide_label');
					if (typeof hide_label != 'undefined' && (hide_label == true || hide_label=='true' || hide_label=='1')) {
						hide_label = true;
					} else {
						hide_label = false;
					}
					
					var  style = $(this).find('#field'+mid+'').attr('style');
					if (typeof style !== 'undefined' && style !== false) {
						
					} else {
						style = '';
					}
					
					var icon = '';
					if( $(this).find('.input-group').length > 0 ){
						icon = $(this).find('.input-group span.fa').attr('class').replace('fa ','');
					}
					
					
				}
				var  cond = $(this).find('#field'+mid+'').data('condition');
				if (typeof cond != 'undefined') {
					j[rind][ind].condition = cond;
				}
				
				if(typeof orientation != 'undefined' && orientation !== false){
					j[rind][ind].orientation = orientation;
				}
				if(typeof label != 'undefined'){
					j[rind][ind].label = label;
				}
				if(typeof help_text != 'undefined'){
					j[rind][ind].help_text = help_text;
				}
				if(typeof myclass != 'undefined'){
					j[rind][ind].class = myclass;
				}
				if(typeof style != 'undefined'){
					j[rind][ind].style = style;
				}
				if(typeof required != 'undefined'){
					j[rind][ind].required = required;
				}
				if(typeof hide_label != 'undefined'){
					j[rind][ind].hide_label = hide_label;
				}
				if(typeof placeholder != 'undefined'){
					j[rind][ind].placeholder = placeholder;
				}
				if(typeof icon != 'undefined'){
					j[rind][ind].icon = icon;
				}
				if(typeof name != 'undefined'){
					j[rind][ind].name = name;
				}
				
				
				
	 			if( $.inArray(mytype,customfields)!= -1 ){
	 				
	 				<?php do_action( 'psfb_save_customfields' ); ?>
	 			} else if( $.inArray(mytype,customelements)!= -1 ){
	 				
	 				<?php do_action( 'psfb_save_customelements' ); ?>
	 			} else if( $.inArray(mytype,selectfields)!= -1 ){
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
	 						j[rind][ind].name = $(this).find('input').attr('name');
	 					});
	 				} else if(mytype=='radio'){
	 					
	 					$(this).find('.radio_wrapper label').each(function(i){
	 						opts[i] = {};
	 						opts[i].label = $(this).text();
	 						opts[i].val = $(this).find('input').attr('value');
	 						j[rind][ind].name = $(this).find('input').attr('name');
	 						
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
		
		if( $('#psfb_admin_mail_html').length>0 ){
			if( $('#psfb_admin_mail_html').is(':checked') ){
				jj.mails.admin_mail.html_mail = true;
			} else {
				jj.mails.admin_mail.html_mail = false;
			}
		}
		if( $('#psfb_user_mail_html').length>0){
			if( $('#psfb_user_mail_html').is(':checked') ){
				jj.mails.user_mail.html_mail = true;
			} else {
				jj.mails.user_mail.html_mail = false;
			}
		}
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
		if( $('#allow_prefill').is(':checked') ){
			jj.allow_prefill = true;
		} else {
			jj.allow_prefill = false;
		}
		if( $('#javascript_antispam').is(':checked') ){
			jj.javascript_antispam = true;
		} else {
			jj.javascript_antispam = false;
		}
		if( $('#clean_attachments').is(':checked') ){
			jj.clean_attachments = true;
		} else {
			
		}
		
		jj.horizontal_form = $('#horizontal_form').val();
		
		jj.datepicker = $('#psfb_datepicker').val();
		
		jj.date_format = $('#psfb_date_format').val();
		
		jj.thankyou_page_url = $('#thankyou_page_url').val();
		
		if( $('input.zapier_url').length > 0){
			jj.zapier_url = [];
			$('input.zapier_url').each(function(i){
				if( $(this).val() != ''){
					jj.zapier_url[i] = $(this).val();
				}
			});
		}
		if( $('input.pushover_user').length > 0){
			jj.pushover_user =  $('input.pushover_user').val();
		}
		<?php do_action('psfb_edit_js_before_save_json'); ?>
		
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
	
	<?php do_action('psfb_edit_js_document_ready'); ?>
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
			$(this).attr('class','field_container').addClass('col-md-'+ Math.floor(12/(parseInt(cont_cnt)))+'');
		});
 	});
}
function psfb_handle_edit_special_tabs_closing(){
	var $ = jQuery;
	$('#fieldeditor .selectoptionstab').hide();
	
	
	<?php do_action('psfb_edit_js_close_edit_tabs'); ?>
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
		//INTRODUCE new mytype property to be absolutely sure
		var mytype = j.type;
		$.each(fieldtypes,function(k,v){
			if( v.type == j.type && k.indexOf('html_')!=-1){
				mytype = k;
			}
		});
		
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
  //console.log(mytype);
  var myFieldType = fieldtypes[mytype];
  //var myLabel = myFieldType.label;
  //console.log(target);
  
  if(row_mode=='plain'){
 		row += '<div class="row" data-type="'+mytype+'" data-id="'+dynID+'">';
  }
  row += '<div class="col-md-12 field_container" data-type="'+mytype+'" data-id="'+dynID+'">';
  
  row += '<div class="options">'+$('.editoptions_template').html()+'</div>';
  
  row += '<div class="form-group">';
  
  if(mytype != 'submit' && mytype!='submitimage' && mytype.substr(0,5)!='html_'){
  	
	  row += '<label for="field'+dynID+'" class="field_label"';
	  if(typeof j.hide_label!='undefined' && j.hide_label==true){
	  	row += ' style="display:none;"';
	  }
	  row += '>'+myLabel;
	  
		if(typeof j.required!='undefined' && (j.required==true || j.required=='true' || j.required=='required')){
			row += '*';
	  }
		row += '</label>';
	}
	
  if( $.inArray(mytype,customfields) != -1 ){
  	
  	<?php do_action('psfb_edit_js_customfields_create'); ?>
  	
  } else if( $.inArray(mytype,customelements) != -1 ){
  	
  	<?php do_action('psfb_edit_js_customelements_create'); ?>
  	
  } else if( $.inArray(mytype,htmlfields)!= -1 ){
		
		var tag_details = fieldtypes[mytype];
		//console.log(tag_details);
		row += '<div class="psfb_html_content" id="field'+dynID+'" data-tag="'+tag_details.type+'"';
		if(typeof tag_details.options != 'undefined'){
			row += ' data-options="'+JSON.stringify(tag_details.options)+'"';
		}
		row += '>';
		if(typeof tag_details.wrap != 'undefined' && tag_details.wrap===true){
			row += '<'+tag_details.type+'';
			
			if(typeof tag_details.options != 'undefined'){
				if(typeof j.option != 'undefined'){
					row += j.option;
				} else {
					row += tag_details.options[0];
				}
			}
	    if(typeof j.style!='undefined' && j.style!=''){
	    	row += ' style="'+j.style+'"';
	    }
	    if(typeof j.class!='undefined' && j.class!=''){
	    	row += ' class="'+j.class+'"';
	    }
			row += '>';
			if(typeof j.content != 'undefined'){
				row += j.content;
			} else {
				row += '<?php echo __('YOUR CONTENT GOES HERE','psfbldr'); ?>';
			}
			row += '</'+tag_details.type+'';
			if(typeof tag_details.options != 'undefined'){
				if(typeof j.option != 'undefined'){
					row += j.option;
				} else {
					row += tag_details.options[0];
				}
			}
			row += '>';
			
		} else {
			row += '<'+tag_details.type+'';
			
	    if(typeof j.style!='undefined' && j.style!=''){
	    	row += ' style="'+j.style+'"';
	    }
	    if(typeof j.class!='undefined' && j.class!=''){
	    	row += ' class="'+j.class+'"';
	    }
	    row += '/>';
		}
		
		
		row += '</div>';
    	
		
  } else {
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
		    if(typeof j.hide_label!='undefined' && (j.hide_label==true || j.hide_label=='true' || j.hide_label=='1')){
		    	row += ' data-hide_label="true"';
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
		    
		    if(typeof j.force_label!='undefined' && (j.force_label==true || j.force_label=='true' || j.force_label=='1')){
		    	row += ' data-force_label="true"';
		    }
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
	    	if(typeof j.orientation!='undefined' && j.orientation!==false){
	    		var orientation = j.orientation;
	    	} else {
	    		var orientation = 'horizontal';
	    	}
	    	if(orientation=='horizontal'){
	    		var wrap_div = false;
	    	} else {
	    		var wrap_div = true;
	    	}
	    	
	    	
			  if(typeof j.name!='undefined' && j.name!='' && j.name!='undefined'){
			  	var tmp_name = j.name.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_');
			  	console.log('name');
			  } else {
			  	var tmp_name = myLabel.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_');
			  	console.log('label');
			  }
			  
			  if( $('.form_builder_stage .field_container .form-group :input[name="'+tmp_name+'"]').length > 0 ){
			  	
			  	var inc_cnt = 1;
			  	while( $('.form_builder_stage .field_container .form-group :input[name="'+tmp_name+'_'+inc_cnt+'"]').length > 0 ){
			  		inc_cnt ++;
			  	}
			  	tmp_name = tmp_name +'_' + inc_cnt;
			  }
	    	
	    	if(j==false || typeof j.select_options == 'undefined' || j.select_options.length<1){
	    		row += '<div class="radio_wrapper" id="field'+dynID+'"';
					if(typeof j.required!='undefined' && (j.required==true || j.required=='true' || j.required=='required')){
			    	row += ' data-required="required"';
			    }
			    if(typeof j.hide_label!='undefined' && (j.hide_label==true || j.hide_label=='true' || j.hide_label=='1')){
			    	row += ' data-hide_label="true"';
			    }
			    if(typeof j.orientation!='undefined' && j.orientation!==false){
			    	row += ' data-orientation="'+j.orientation+'"';
			    } else {
			    	row += ' data-orientation="horizontal"';
			    }
			    row += '>';
			    
		   		if(wrap_div){
						row += '<div class="radio">';
						
					}
					row += '<label';
					if(!wrap_div){
						row += ' class="radio-inline"';
						
					}
					row += '><input type="radio" name="'+tmp_name+'" value="">';
					row += myLabel+' 1';
					row += '</label>';
					
					
		   		if(wrap_div){
						row += '</div>';
					}
		   		if(wrap_div){
						row += '<div class="radio">';
					}
					row += '<label';
					if(!wrap_div){
						row += ' class="radio-inline"';
					}
					row += '><input type="radio" name="'+tmp_name+'" value="">';
					row += myLabel+' 2';
					row += '</label>';
					
		   		if(wrap_div){
						row += '</div>';
					}
					row += '</div>';
				} else {
					//select_options":[{"label":"Radio-Schaltfläche 1","val":""},{"
					row += '<div class="radio_wrapper" id="field'+dynID+'"';
					if(typeof j.required!='undefined' && (j.required==true || j.required=='true' || j.required=='required')){
			    	row += ' data-required="required"';
			    }
			    if(typeof j.hide_label!='undefined' && (j.hide_label==true || j.hide_label=='true' || j.hide_label=='1')){
			    	row += ' data-hide_label="true"';
			    }
			    if(typeof j.orientation!='undefined' && j.orientation!==false){
			    	row += ' data-orientation="'+j.orientation+'"';
			    } else {
			    	row += ' data-orientation="horizontal"';
			    }
			    row += '>';
			    
					$.each(j.select_options,function(key,value){
						
			   		if(wrap_div){
							row += '<div class="radio">';
						}
						row += '<label';
						if(!wrap_div){
							row += ' class="radio-inline"';
						}
						row += '><input type="radio"  value="'+value.val+'"';
						//name="optionsfield'+dynID+'"
				    row += ' name="'+tmp_name+'"';
				    
						row += '>';
						row += value.label;
						row += '</label>';
						
			   		if(wrap_div){
							row += '</div>';
						}
					});
					row += '</div>';
				}
	    } else if(mytype == 'checkbox'){
	    	if(typeof j.orientation!='undefined' && j.orientation!==false){
	    		var orientation = j.orientation;
	    	} else {
	    		var orientation = 'horizontal';
	    	}
	    	if(orientation=='horizontal'){
	    		var inner_class = 'checkbox-inline';
	    		var wrap_div = false;
	    	} else {
	    		var inner_class = '';
	    		var wrap_div = true;
	    	}
	    	if(typeof j.name!='undefined' && j.name!='' && j.name!='undefined'){
			  	var tmp_name_chk = j.name.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_');
			  } else {
			  	var tmp_name_chk = myLabel.replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_');
			  }
			  
			  if( $('.form_builder_stage .field_container .form-group :input[name="'+tmp_name_chk+'"]').length > 0 ){
			  	var inc_cnt_chk = 1;
			  	while( $('.form_builder_stage .field_container .form-group :input[name="'+tmp_name_chk+'_'+inc_cnt_chk+'"]').length > 0 ){
			  		inc_cnt_chk ++;
			  	}
			  	tmp_name_chk = tmp_name_chk +'_' + inc_cnt_chk;
			  }
	    	
	    	if(j==false || typeof j.select_options == 'undefined' || j.select_options.length<1){
		    	row += '<div class="checkbox_wrapper" id="field'+dynID+'"';
					if(typeof j.required!='undefined' && (j.required==true || j.required=='true' || j.required=='required')){
			    	row += ' data-required="required"';
			    }
			    if(typeof j.hide_label!='undefined' && (j.hide_label==true || j.hide_label=='true' || j.hide_label=='1')){
			    	row += ' data-hide_label="true"';
			    }
			    if(typeof j.orientation!='undefined' && j.orientation!==false){
			    	row += ' data-orientation="'+j.orientation+'"';
			    } else {
			    	row += ' data-orientation="horizontal"';
			    }
			    row += '>';
			    
		   		if(wrap_div){
						row += '<div class="checkbox">';
					}
					row += '<label';
					if(!wrap_div){
						row += ' class="checkbox-inline"';
					}
					row += '><input type="checkbox" name="'+tmp_name_chk+'" value="">';
					row += myLabel+' 1';
					row += '</label>';
					if(wrap_div){
						row += '</div>';
					}
		   		if(wrap_div){
						row += '<div class="checkbox">';
					}
					row += '<label';
					if(!wrap_div){
						row += ' class="checkbox-inline"';
					}
					row += '><input type="checkbox" name="'+tmp_name_chk+'" value="">';
					row += myLabel+' 2';
					row += '</label>';
					if(wrap_div){
						row += '</div>';
					}
					row += '</div>';
				} else {
					//select_options":[{"label":"Radio-Schaltfläche 1","val":""},{"
					row += '<div class="checkbox_wrapper" id="field'+dynID+'"';
					if(typeof j.required!='undefined' && (j.required==true || j.required=='true' || j.required=='required')){
			    	row += ' data-required="required"';
			    }
			    if(typeof j.hide_label!='undefined' && (j.hide_label==true || j.hide_label=='true' || j.hide_label=='1')){
			    	row += ' data-hide_label="true"';
			    }
			    if(typeof j.orientation!='undefined' && j.orientation!==false){
			    	row += ' data-orientation="'+j.orientation+'"';
			    } else {
			    	row += ' data-orientation="horizontal"';
			    }
			    row += '>';
			    
					$.each(j.select_options,function(key,value){
						if(wrap_div){
							row += '<div class="checkbox">';
						}
						row += '<label';
						if(!wrap_div){
							row += ' class="checkbox-inline"';
						}
						row += '><input type="checkbox"';
						
				    row += ' name="'+tmp_name_chk+'"';
				    
						row += ' value="'+value.val+'">';
						row += value.label;
						row += '</label>';
						if(wrap_div){
							row += '</div>';
						}
					});
					row += '</div>';
				}
	    } else if(mytype == 'select' || mytype == 'multiselect'){
	    	if(typeof j.icon!='undefined' && j.icon!='' && j.icon!='undefined'){
		    	//console.log(j.icon);
			  	row += '<div class="input-group">';
			  	row += '<div class="input-group-addon">';
			  	row += '<span class="fa '+j.icon+'"></span>';
			  	row += '</div>';
		  	}
	    	row += '<select id="field'+dynID+'" class="form-control';
		    if(typeof j.class!='undefined' && j.class!=''){
		    	row += j.class;
		    }
		    row += '"';
		    
	    	if(typeof myFieldType.multiple!='undefined' && myFieldType.multiple==true){
		    	row += ' multiple="multiple"';
		    }
		    if(typeof j.hide_label!='undefined' && (j.hide_label==true || j.hide_label=='true' || j.hide_label=='1')){
		    	row += ' data-hide_label="true"';
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
		    if(typeof j.icon!='undefined' && j.icon!='' && j.icon!='undefined'){
			  	row += '</div>';
		  	}
	    }
	  } else if(typeof myFieldType != 'undefined'){
	  	
	  	//input group
	  	
	    if(typeof j.icon!='undefined' && j.icon!='' && j.icon!='undefined'){
	    	//console.log(j.icon);
		  	row += '<div class="input-group">';
		  	row += '<div class="input-group-addon">';
		  	row += '<span class="fa '+j.icon+'"></span>';
		  	row += '</div>';
	  	}
	  	
	    row += '<input id="field'+dynID+'"';
	    
	    if(typeof myFieldType.type!='undefined'){
	    	row += ' type="'+ myFieldType.type +'"';
	    }
	    if(typeof myFieldType.multiple!='undefined' && myFieldType.multiple==true){
	    	row += ' multiple="multiple"';
	    }
	    if(typeof j.required!='undefined' && (j.required==true || j.required=='true' || j.required=='required')){
	    	row += ' required="required"';
	    }
	    if(typeof j.hide_label!='undefined' && (j.hide_label==true || j.hide_label=='true' || j.hide_label=='1')){
	    	row += ' data-hide_label="true"';
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
	}
  
  row += '<p class="help-block">';
  if(typeof j.help_text!='undefined' && j.help_text!='' && j.help_text!='undefined'){
  	row += j.help_text;
  }
  row += '</p>';
  
  row += '</div>';
  
  row += '</div>';
  
  //console.log(row_mode);
  if(row_mode=='plain'){
  	row += '</div>';//end row
  	$( '.form_builder_stage' ).append( row );
  	if( $.inArray(mytype,htmlfields)!=-1){
  		$( '.form_builder_stage' ).find('.psfb_html_content').last().data('condition',j.condition);
  	} else {
  		$( '.form_builder_stage' ).find('.form-group :input').last().data('condition',j.condition);
  	}
  } else if(row_mode=='plain_col'){
  	$( '.form_builder_stage .row:last-child' ).append( row );
  	if( $.inArray(mytype,htmlfields)!=-1){
	  	$( '.form_builder_stage .row:last-child' ).find('.psfb_html_content').last().data('condition',j.condition);
	  } else {
	  	$( '.form_builder_stage .row:last-child' ).find('.form-group :input').last().data('condition',j.condition);
	  }
  	var colcnt = $( '.form_builder_stage .row:last-child .field_container' ).length;
  	$( '.form_builder_stage .row:last-child .field_container' ).attr('class','field_container').addClass('col-md-'+ Math.floor( 12 / colcnt) +'');
  	if( $.inArray(mytype,htmlfields)!=-1){
  		$( '.form_builder_stage .row:last-child .field_container' ).find('.psfb_html_content').last().data('condition',j.condition);
  	} else {
  		$( '.form_builder_stage .row:last-child .field_container' ).find('.form-group :input').last().data('condition',j.condition);
  	}
  } else {
  	$( row ).insertAfter( $(target) );
  	if( $.inArray(mytype,htmlfields)!=-1){
  		$(target).find('.psfb_html_content').last().data('condition',j.condition);
  	} else {
  		$(target).find('.form-group :input').last().data('condition',j.condition);
  	}
  	
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
  
  
  $('.form_builder_stage .row .field_container').draggable({
  	appendTo: 'body',
    helper: 'original',
    addClasses: false,
    cancel: false,
    refreshPositions:true,
    revert:true,
    zIndex:5555,
  	cursorAt:{left: 25,top:50},
    handle: '.options .move-hv',
  	start:function(event,ui){
				var height = 50;
		  
		 	$('.form_builder_stage').append( '<div class="empty_helper_row row"><div style="height:'+height+'px;" class="droparea field_container col-md-12"></div></div>' );
		 	
			$('.field_container').droppable({
				greedy:false,
				tolerance:'pointer',
				addClasses: false,
				accept:'.field_container',
				hoverClass: 'bg-success',
				over: function(event,ui){
					
  				var droppableElement = $(this);
  				var row = droppableElement.closest('.row');
  				if(droppableElement.hasClass('droparea')){
  					//is empty - allowed to be dropped
  				} else {
						ps_remove_dropareas();
	  				var cont_cnt = row.find('.field_container').length;
	  				var col_cls = Math.floor(12/(parseInt(cont_cnt)+2));
	  				
		    		row.find('.field_container').each(function(){
		    			$(this).attr('class','field_container').addClass('col-md-'+ col_cls +'');
		    		});
		    		
		    		$('<div class="droparea field_container col-md-'+ col_cls +'"></div>').insertBefore(droppableElement);
		    		$('<div class="droparea field_container col-md-'+ col_cls +'"></div>').insertAfter(droppableElement);
	  				$( '<div class="row"><div style="height:50px;" class="droparea field_container col-md-12"></div></div>' ).insertBefore(row);
	  				$( '<div class="row"><div style="height:50px;" class="droparea field_container col-md-12"></div></div>' ).insertAfter(row);
	  				
				  	$( '.form_builder_stage .droparea' ).each(function(){
				  		$(this).height( $(this).parent().height() );
				  	});
				  	
	  				$('.droparea').droppable({
	  					greedy:false,
							addClasses: false,
							hoverClass: 'bg-success',
							tolerance:'pointer',//touch,intersect,pointer,fit
							
							drop:function(event,ui){
					    	dragcontroller.dropped = true;
					      ui.draggable.insertAfter($(this));
					      $('.droparea').droppable('destroy');
			  				ps_remove_dropareas();
							}
						});
						
	  			}
				},
				drop: function(event,ui){
					
					if( $(this).hasClass('droparea') ){
						try{$('.field_container').droppable('destroy');}catch(e){}
			    	dragcontroller.dropped = true;
			      
					  ui.draggable.insertAfter($(this));
			      
	  			}
	  			ps_remove_dropareas();
					$('.form_builder_stage .field_container').removeAttr('style');
  				
				}
				
			});
		},
		revert : function(event, ui) {
      
      $(this).data("uiDraggable").originalPosition = {
          top : 0,
          left : 0
      };
      // return boolean
      return !event;
    },
		stop:function(){
			if( $('.empty_helper_row').is(':empty') ){
				$('.empty_helper_row').remove();
			} else {
				$('.empty_helper_row').removeClass('empty_helper_row');
			}
			$('.form_builder_stage .row:empty').remove();
			ps_remove_dropareas();
			$('.form_builder_stage .field_container').removeAttr('style');
		}
  });
  
  
  $('.form_builder_stage button.delete').unbind('click').click(function(){
  	if( $(this).closest('.row').find('.field_container').length > 1){
  		$(this).closest('.field_container').remove();
  	} else {
  		$(this).closest('.row').remove();
  	}
  	ps_remove_dropareas();
  });
  ////////////// MODAL EDIT CLICk \\\\\\\\\\\\\
  
  $('.form_builder_stage button.edit').unbind('click').click(function(){
  	
  	var mytype = $(this).closest('.field_container').data('type');
  	
  	
  	if( $.inArray(mytype,htmlfields)!=-1){
  		
  		
  		var tag_details = fieldtypes[mytype];
			if(typeof tag_details.wrap!='undefined' && tag_details.wrap===true){
  			var tag = tag_details.type;
  			if(typeof tag_details.options!='undefined'){
  				//ACHTUNG AUSWAHL BEREITSTELLEN
  				tag += tag_details.options[0];
  			}
  			$('#field_html_content').val( $(this).closest('.field_container').find('.psfb_html_content').find(tag).first().html() );
  		}
  		
	  	$('#field_cssstyle').val( $(this).closest('.field_container').find('.psfb_html_content').find(tag).first().attr('style') );
	  	if( $('#field_cssstyle').val()=='undefined'){
	  		$('#field_cssstyle').val('');
	  	}
  		var cssclass = $(this).closest('.field_container').find('.psfb_html_content').find(tag).first().attr('class');
	  	if(typeof cssclass!='undefined'){
	  		if(cssclass.indexOf('psfb_html_content')!=-1){
	  			cssclass=cssclass.replace('psfb_html_content','');
	  		}
	  	} else {
	  		cssclass = '';
	  	}
	  	$('#field_cssclass').val( cssclass );
	  	if( $('#field_cssclass').val()=='undefined'){
	  		$('#field_cssclass').val('');
	  	}
  		
  		
  		
  	} else {
	  	var my_field_container = $(this).closest('.field_container');
	  	if(mytype.indexOf('submit')!=-1){
	  		$('#field_label').val( my_field_container.find('.form-group :input').attr('value') );
	  	} else {
		  	$('#field_label').val( my_field_container.find('.form-group .field_label').html().replace('*','') );
		  }
		  if( $('#field_label').val()=='undefined'){
	  		$('#field_label').val('');
	  	}
	  	$('#field_label').unbind('change').change(function(){
	  		$('#field_name').val( $(this).val().replace(/(?!\w)[\x00-\xC0]/g,'_').replace(/[^\x00-\x7F]/g,'_') );
	  		
	  		var current_name = $('#field_name').val();
	  		var current_original_name = $('#field_name_orig').val();
	  		if( current_name == current_original_name ){
	  			var check_cnt = 1;
	  		} else {
	  			var check_cnt = 0;
	  		}
	  		var test_name = current_name+'_1';
	  		if( $('.form_builder_stage .field_container .form-group :input[name="'+current_name+'"]').closest('.form-group').length > check_cnt ){
	  			
	  			var inc_cnt = 1;
	  			var test_name = current_name+'_'+inc_cnt;
	  			while( $('.form_builder_stage .field_container .form-group :input[name="'+current_name+'_'+inc_cnt+'"]').closest('.form-group').length > 0 && test_name!=current_original_name ){
	  				inc_cnt ++;
	  				test_name = current_name+'_'+inc_cnt;
	  			}
	  			$('#field_name').val(current_name+'_'+inc_cnt);
	  		}
	  	});
	  	$('#field_helptext').val( my_field_container.find('.form-group .help-block').html() );
	  	if( $('#field_helptext').val()=='undefined'){
	  		$('#field_helptext').val('');
	  	}
	  	$('#field_placeholder').val( my_field_container.find('.form-group :input').attr('placeholder') );
	  	if( $('#field_placeholder').val()=='undefined'){
	  		$('#field_placeholder').val('');
	  	}
	  	$('#field_cssstyle').val( my_field_container.find('.form-group :input').attr('style') );
	  	if( $('#field_cssstyle').val()=='undefined'){
	  		$('#field_cssstyle').val('');
	  	}
	  	var cssclass = my_field_container.find('.form-group :input').attr('class');
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
	  	$('#field_cssclass').val( $.trim( cssclass ) );
	  	if( $('#field_cssclass').val()=='undefined'){
	  		$('#field_cssclass').val('');
	  	}
	  	$('#field_name').val( my_field_container.find('.form-group :input').attr('name') );
	  	if( $('#field_name').val()=='undefined'){
	  		$('#field_name').val('');
	  	}
	  	$('#field_name_orig').val( $('#field_name').val() );
	  	
	  	
	  	if( my_field_container.find('.checkbox_wrapper').length > 0 ){
	  		var req = my_field_container.find('.checkbox_wrapper').data('required');
	  		var orientation = my_field_container.find('.checkbox_wrapper').data('orientation');
	  	} else if( my_field_container.find('.radio_wrapper').length > 0 ){
	  		
	  		var req = my_field_container.find('.radio_wrapper').data('required');
	  		var orientation = my_field_container.find('.radio_wrapper').data('orientation');
	  	} else {
	  		var req = my_field_container.find('.form-group :input').prop('required');
	  		var orientation = false;
	  	}
	  	//console.log(req);
	  	if(typeof req!='undefined' && (req == 'required' || req==true || req=='true') ){
	  		req = true;
	  		$('#field_required').prop('checked',true);
	  	} else {
	  		req = false;
	  		$('#field_required').prop('checked',false);
	  	}
	  	if(typeof orientation!='undefined' && orientation !== false ){
	  		$('#field_orientation').val( orientation );
	  	}
	  	if( my_field_container.find('.checkbox_wrapper').length > 0 ){
	  		var hide_label = my_field_container.find('.checkbox_wrapper').data('hide_label');
	  	} else if( my_field_container.find('.radio_wrapper').length > 0 ){
	  		var hide_label = my_field_container.find('.radio_wrapper').data('hide_label');
	  	} else {
	  		var hide_label = my_field_container.find('.form-group :input').data('hide_label');
	  	}
	  	//console.log(req);
	  	if(typeof hide_label!='undefined' && (hide_label == '1' || hide_label==true || hide_label=='true') ){
	  		hide_label = true;
	  		$('#field_hide_label').prop('checked',true);
	  	} else {
	  		hide_label = false;
	  		$('#field_hide_label').prop('checked',false);
	  	}
	  	
	  	$('#field_icon').val('');
	  	if( my_field_container.find('.input-group').length > 0){
	  		$('#field_icon').val( my_field_container.find('.input-group .fa').attr('class').replace('fa ','') );
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
	  	if( mytype=='radio' || mytype=='checkbox'){
	  		$('.field_orientation_wrapper').show();
	  	} else {
	  		$('.field_orientation_wrapper').hide();
	  	}
	  	
	  	psfb_handle_edit_special_tabs_closing();
	  	if( $.inArray(mytype,customfields)!= -1 ){
	  		//console.log(mytype);
	  		<?php do_action( 'psfb_edit_js_handle_edit_customfields' ); ?>
	  		
	  		
	  	} else if( $.inArray(mytype,customelements)!= -1 ){
	  		//console.log(mytype);
	  		<?php do_action( 'psfb_edit_js_handle_edit_customelements' ); ?>
	  		
	  		
	  	} else if( $.inArray(mytype,selectfields)!= -1 ){
	    	//selectfield
	    	
	    	$('.selectoptionstab').show();
	    	$('.selectoptions_content').html('');
	    	if(mytype == 'select' || mytype == 'multiselect'){
	  			my_field_container.find('option').each(function(){
	  				var h = $('.selectoptions_template').html();
	  				$('.selectoptions_content').append(h);
	  				$('.selectoptions_content .field_option_value:last').val( $(this).attr('value') );
	  				$('.selectoptions_content .field_option_label:last').val( $(this).text() );
	  				$('.selectoptions_content .delete_selectoption:last').unbind('click').click(function(){
	  					$(this).closest('.row').remove();
	  				});
	  			});
	  		} else if(mytype == 'radio'){
	  			my_field_container.find('input[type="radio"]').each(function(){
	  				var h = $('.selectoptions_template').html();
	  				$('.selectoptions_content').append(h);
	  				$('.selectoptions_content .field_option_value:last').val( $(this).attr('value') );
	  				$('.selectoptions_content .field_option_label:last').val( $(this).parent().text() );
	  				$('.selectoptions_content .delete_selectoption:last').unbind('click').click(function(){
	  					$(this).closest('.row').remove();
	  				});
	  			});
	  		} else if(mytype == 'checkbox'){
	  			my_field_container.find('input[type="checkbox"]').each(function(){
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
	  		
	  		
	  		$('.toggle_selectoption').unbind('click').click(function(){
	  			if( $('.selectoptions_content').is(':hidden') ){
	  				//build rows from textarea
	  				
	  				$('.selectoptions_quick_content').trigger('blur');
	  				$('.selectoptions_quick_content').hide();
	  				$('.selectoptions_quick_content_desc').hide();
	  				$('.add_selectoption').show();
	  				$('.selectoptions_content').show();
	  				$('.selectoptions_content_desc').show();
	  			} else {
	  				
	  				//fill texarea from rows
	  				var h = '';
	  				$('.selectoptions_content .row').each(function(i){
	  					if(i>0)h += '\n';
	  					h += $(this).find('.field_option_label').val();
	  					if( $(this).find('.field_option_value').val()!='' ){
	  						h += '@=';
	  						h += $(this).find('.field_option_value').val();
	  					}
	  				});
	  				$('.selectoptions_quick_content textarea').val( h );
	  				$('.selectoptions_content').hide();
	  				$('.selectoptions_content_desc').hide();
	  				$('.add_selectoption').hide();
	  				$('.selectoptions_quick_content').show();
	  				$('.selectoptions_quick_content_desc').show();
	  			}
	  		});
	  		$('.selectoptions_quick_content textarea').unbind('blur').blur(function(){
	  			var lines = $(this).val().split('\n');
	  			
	  			$('.selectoptions_content').html('');
	  			
	  			if(typeof lines != 'undefined' && lines.length > 0){
	  				
	  				$.each(lines,function(i,line){
	  					$('.add_selectoption').trigger('click');
	  					if(line.indexOf('@=')!=-1){
	  						var labelval = line.split('@=');
	  						$('.selectoptions_content .field_option_label:last').val( labelval[0] );
	  						$('.selectoptions_content .field_option_value:last').val( labelval[1] );
	  					} else {
	  						$('.selectoptions_content .field_option_label:last').val( line );
	  					}
	  				});
	  			}
	  		});
	  		
	  	} else {
	  		psfb_handle_edit_special_tabs_closing();
	  	}
	  	
	  }//end if not html
  	
  	$('.add_conditionset').unbind('click').click(function(){
			var h = $('.condition_set_template').html();
			$('.condition_content').append(h);
			$('.condition_content .delete_conditionset:last').unbind('click').click(function(){
				$(this).closest('.condition_set').remove();
			});
			
	  	$('.add_conditionoption').unbind('click').click(function(){
				var h = $('.condition_template').html();
				$(this).closest('.condition_set').find('.condition_set_content').append(h);
				$('.condition_set_content .delete_conditionoption').unbind('click').click(function(){
					$(this).closest('.row').remove();
				});
			});
			$('.add_conditionoption:last').trigger('click');
		});
		var h = '';
		$('.form_builder_stage .form-group').each(function(){
			var cname = $(this).find('label:first').html();
			var cvalue = $(this).find(':input').attr('name');
			if(typeof cname!='undefined' && cname!=''){
	
			} else {
				if(typeof cvalue!='undefined' && cvalue!=''){
					cname = cvalue;
				}
			}
			
			if(typeof cvalue!='undefined' && cvalue!='' && cvalue!='undefined'){
				h += '<option value="'+cvalue+'">'+cname+'</option>';
			}
		});
		$('.condition_template .condition_field_select').html(h);
  	
  	$('.condition_content').html('');
  	if( $.inArray(mytype,htmlfields) != -1){
			var cond = $(this).closest('.field_container').find('.psfb_html_content').data('condition');
		} else {
			var cond = $(this).closest('.field_container').find('.form-group :input').data('condition');
		}
		if(typeof cond!='undefined'){
			if( $.type(cond)=='object'){
				var c = cond;
			} else {
				var c = JSON.parse(cond);
			}
			
			if(typeof c.groups!='undefined' && c.groups.length > 0){
				$.each(c.groups,function(k,v){
					$('.add_conditionset').trigger('click');
					$('.condition_set_type:last').val( v.groupOp );
					$('.condition_set_action:last').val( v.groupAction );
					
					$('.condition_set_content:last').html('');
					if(typeof v.rules != 'undefined' && v.rules.length > 0){
						$.each(v.rules, function(kk,vv){
							$('.add_conditionoption:last').trigger('click');
							$('.condition_field_select:last').val( vv.field );
							$('.condition_field_condition:last').val( vv.op );
							$('.condition_field_value:last').val( vv.data );
						});
					}
				});
			}
		}
  	
  	
  	
  	$('#fieldeditor').modal('show').data('type',$(this).closest('.field_container').data('type') ).data('id',$(this).closest('.field_container').data('id') );
  	
		if( $.inArray(mytype,htmlfields)!=-1){
			$('#fieldeditor .basicstab').hide();
			
			psfb_handle_edit_special_tabs_closing();
			var tag_details = fieldtypes[mytype];
 			if(typeof tag_details.wrap!='undefined' && tag_details.wrap===true){
				$('#fieldeditor .basicshtmltab').show();
			$('#fieldeditor a[href="#tab-basicshtml"]').tab('show');
			} else {
				$('#fieldeditor .basicshtmltab').hide();
				$('#fieldeditor a[href="#tab-expert"]').tab('show');
			}
		} else {
			$('#fieldeditor .basicshtmltab').hide();
			$('#fieldeditor .basicstab').show();
			$('#fieldeditor a[href="#tab-basics"]').tab('show');
		}
		//$('#fieldeditor li a:visible').first().tab('show');
  	//$('#fieldeditor a:first').tab('show');
  	/************ MODAL SAVE BUTTON *********/
  	$('#fieldeditor .savefield').unbind('click').click(function(){
  		var myID = $('#fieldeditor').data('id');
  		var mytype = $('#fieldeditor').data('type');
  		
  		$('#field_label').trigger('change');
  		
  		if( $.inArray(mytype,htmlfields)!=-1){
  			var tag_details = fieldtypes[mytype];
  			if(typeof tag_details.wrap!='undefined' && tag_details.wrap===true){
	  			var tag = tag_details.type;
	  			if(typeof tag_details.options!='undefined'){
	  				//ACHTUNG AUSWAHL BEREITSTELLEN
	  				tag += tag_details.options[0];
	  			}
	  			$('.field_container[data-id="'+myID+'"] .psfb_html_content').find(tag).html( $('#field_html_content').val() );
	  		}
	  		
	  		$('.field_container[data-id="'+myID+'"]').find('.psfb_html_content').find(tag).first().attr('style', $('#field_cssstyle').val() );
	  		$('.field_container[data-id="'+myID+'"]').find('.psfb_html_content').find(tag).first().attr('class', $('#field_cssclass').val() );
	  			
	  		
	  		//ende if html
  		} else {
	  		
	  		$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('placeholder',$('#field_placeholder').val());
	  		$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('style', $.trim( $('#field_cssstyle').val() ) );
	  		
	  		if(mytype.indexOf('submit')!=-1){
	  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('value',$('#field_label').val());
	  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('class', 'btn btn-primary '+$.trim( $('#field_cssclass').val() ) );
	  			
	  		} else {
	  			
	  			var name = $('#field_name').val();
	  			var new_name = name;
	  			var wcnt = 1;
	  			while( $('.field_container .form-group [name="'+new_name+'"]').length > 1){
	  				new_name = name+'_'+wcnt;
	  				wcnt ++;
	  			}
	  			if(new_name!=name){
	  				name = new_name;
	  			}
	  			
	  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('name',name);
	  			
	  			if( $('#field_name_orig').val()!=name ){
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
		  			if( $('.field_container[data-id="'+myID+'"]').find('.checkbox_wrapper').length > 0){
		  				$('.field_container[data-id="'+myID+'"]').find('.checkbox_wrapper').data('required','required');
		  			} else if( $('.field_container[data-id="'+myID+'"]').find('.radio_wrapper').length > 0){
		  				$('.field_container[data-id="'+myID+'"]').find('.radio_wrapper').data('required','required');
		  			} else {
		  				$('.field_container[data-id="'+myID+'"]').find('.form-group :input').attr('required','required');
		  			}
		  			$('.field_container[data-id="'+myID+'"]').find('.form-group .field_label').append( '*' );
		  		} else {
		  			$('.field_container[data-id="'+myID+'"]').find('.checkbox_wrapper').data('required',false);
		  			$('.field_container[data-id="'+myID+'"]').find('.radio_wrapper').data('required',false);
		  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').removeAttr('required');
		  			$('.field_container[data-id="'+myID+'"]').find('.form-group .field_label').html( $('.field_container[data-id="'+myID+'"]').find('.form-group .field_label').html().replace('*','') );
		  		}
		  		
		  		
		  		if($('#field_hide_label').is(':checked')==1){
		  			if( $('.field_container[data-id="'+myID+'"]').find('.checkbox_wrapper').length > 0){
		  				$('.field_container[data-id="'+myID+'"]').find('.checkbox_wrapper').data('hide_label',true);
		  			} else if( $('.field_container[data-id="'+myID+'"]').find('.radio_wrapper').length > 0){
		  				$('.field_container[data-id="'+myID+'"]').find('.radio_wrapper').data('hide_label',true);
		  			} else {
		  				$('.field_container[data-id="'+myID+'"]').find('.form-group :input').data('hide_label',true);
		  			}
		  			$('.field_container[data-id="'+myID+'"]').find('.form-group .field_label').hide();
		  		} else {
		  			$('.field_container[data-id="'+myID+'"]').find('.checkbox_wrapper').data('hide_label',false);
		  			$('.field_container[data-id="'+myID+'"]').find('.radio_wrapper').data('hide_label',false);
		  			$('.field_container[data-id="'+myID+'"]').find('.form-group :input').data('hide_label',false);
		  			$('.field_container[data-id="'+myID+'"]').find('.form-group .field_label').show();
		  		}
		  		
  				
	  		}
	  		$('.field_container[data-id="'+myID+'"]').find('.form-group .help-block').html( $('#field_helptext').val() );
	  	} //ende if not html	
  		
  		if( $('.condition_wrapper .condition_set').length > 0){
  			var c = {};
  			c.groups = [];
  			var i = 0;
  			$('.condition_wrapper .condition_set').each(function(){
  				c.groups[i] = {};
  				c.groups[i].groupOp = $(this).find('.condition_set_type').val();
  				c.groups[i].groupAction = $(this).find('.condition_set_action').val();
  				if( $('.condition_wrapper .condition_set_content .form-group').length > 0){
  					c.groups[i].rules = [];
  					var ii = 0;
  					$(this).find('.condition_set_content .form-group').each(function(){
  						c.groups[i].rules[ii] = {};
  						c.groups[i].rules[ii].field = $(this).find('.condition_field_select').val();
  						//console.log('here');
  					//	console.log($(this).find('.condition_field_select').val());
  						c.groups[i].rules[ii].op = $(this).find('.condition_field_condition').val();
  						c.groups[i].rules[ii].data = $(this).find('.condition_field_value').val();
  						ii ++;
  					});
  				}
  				i ++;
  			});
  			if( $.inArray(mytype,htmlfields) != -1 ){
  				$('.field_container[data-id="'+myID+'"]').find('.psfb_html_content').data('condition',JSON.stringify(c) );
  			} else {
  				$('.field_container[data-id="'+myID+'"]').find('.form-group :input').data('condition',JSON.stringify(c) );
  			}
  		} else {
  			if( $.inArray(mytype,htmlfields) != -1 ){
  				$('.field_container[data-id="'+myID+'"]').find('.psfb_html_content').data('condition',null).removeData('condition');
  			} else {
  				$('.field_container[data-id="'+myID+'"]').find('.form-group :input').data('condition',null).removeData('condition');
  			}
  		}
  		
  		if( $.inArray(mytype,customfields)!= -1 ){
	  		
	  		
	  		<?php do_action( 'psfb_edit_js_handle_save_customfields' ); ?>
	  		
	  		
	  	} else if( $.inArray(mytype,customelements)!= -1 ){
	  		
	  		
	  		<?php do_action( 'psfb_edit_js_handle_save_customelements' ); ?>
	  		
	  		
	  	} else if( $.inArray(mytype,selectfields)!= -1 ){
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
  				
  				
  				if( $('.field_container[data-id="'+myID+'"]').find('.input-group').length>0){
	  				//remove input-group
	  				$('.field_container[data-id="'+myID+'"]').find('.input-group-addon').remove();
	  				$('.field_container[data-id="'+myID+'"]').find('.form-group select').unwrap();
	  			}
	  			if( $('#field_icon').val()!=''){
	  				//add input-group
	  				$('.field_container[data-id="'+myID+'"]').find('.form-group select').wrap('<div class="input-group"></div>');
	  				$('.field_container[data-id="'+myID+'"]').find('.input-group').prepend('<div class="input-group-addon"><span class="fa '+$('#field_icon').val()+'"></span></div>');
	  			} else {
	  				
	  			}
  				
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
					        	//.attr('name', 'field'+myID ) //$('#field_label').val()
					        	.attr('name', $('#field_name').val() ) //$('#field_label').val()
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
  							.append( $('<label class="checkbox-inline"></label>') 
					        .append( $('<input>')
					        	.attr('type', 'checkbox' )
					        	//.attr('name',  'field'+myID )//$('#field_label').val())
					        	.attr('name',  $('#field_name').val() )//$('#field_label').val())
					        	.attr('value', val )
					        )
					        .append( label )
			        );
  				});
  				$('.field_container[data-id="'+myID+'"]').find('.form-group')
		        .append( $('<p></p>' )
		        	.addClass('help-block')
		        	.html( $('#field_helptext').val() )
		        );
  				
  			}
  			
  			if(mytype == 'checkbox' || mytype == 'radio'){
	  			var orientation = $('#field_orientation').val();
		  		if(typeof orientation != 'undefined'){
	  				$('.field_container[data-id="'+myID+'"]').find('.checkbox_wrapper').data('orientation', orientation );
	  				$('.field_container[data-id="'+myID+'"]').find('.radio_wrapper').data('orientation', orientation );
	  				if(orientation == 'horizontal'){
	  					//Do nothing - fields have just been built in horizontal mode
	  				} else {
	  					$('.field_container[data-id="'+myID+'"]').find('.checkbox_wrapper').find('label').removeClass('checkbox-inline').wrap('<div class="checkbox"></div>');
	  					$('.field_container[data-id="'+myID+'"]').find('.radio_wrapper').find('label').removeClass('radio-inline').wrap('<div class="radio"></div>');
	  				}
	  			}
	  		}
  			
  			
  		} else {
  			//no selectfield
  			if( $('.field_container[data-id="'+myID+'"]').find('.input-group').length>0){
  				//remove input-group
  				$('.field_container[data-id="'+myID+'"]').find('.input-group-addon').remove();
  				$('.field_container[data-id="'+myID+'"]').find('.form-group :input').unwrap();
  			}
  			if( $('#field_icon').val()!=''){
  				//add input-group
  				$('.field_container[data-id="'+myID+'"]').find('.form-group :input').wrap('<div class="input-group"></div>');
  				$('.field_container[data-id="'+myID+'"]').find('.input-group').prepend('<div class="input-group-addon"><span class="fa '+$('#field_icon').val()+'"></span></div>');
  			} else {
  				
  			}
  		}
  		
  		$('#fieldeditor').modal('hide');
  		ps_manage_form_vars();
			psfb_on_stage_change();
  	});
  });//Edit click finish
	
	psfb_on_stage_change();
	
}


function psfb_on_stage_change(){
	var $ = jQuery;
	
	<?php do_action( 'psfb_edit_js_on_stage_change' ); ?>
	return;
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
	});
	
	<?php do_action( 'psfb_edit_javascript_add_form_vars' ); ?>
	
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


<?php do_action( 'psfb_edit_javascript' ); ?>

</script>
<?php do_action( 'psfb_edit_after_javascript' ); ?>
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
	border:1px dashed lightgray;
	min-height:50px;
	margin-bottom:0.5em;
}
.form_builder_stage .row:last-child{
	padding-bottom:25px;
}



.form_builder_stage:empty{
	
	min-height:590px;
	border:1px dashed lightgray;
	vertical-align: middle;
	text-align:center;
	position:relative;
}
.form_builder_stage:empty:before { content: '<?php echo __('1. Start by dragging fields to your form','psfbldr'); ?>'; color: #666; font-size: 2em;z-index:8;padding:0; position:relative;top:80px;width:inherit;padding-bottom:400px;background-image:url(<?php echo plugins_url( '/images/arrow-drag-in-up-left.png', (dirname(__FILE__)) ); ?>);background-position:100% 20%;background-repeat:no-repeat;}

.form_builder_stage:empty:after { content: '<?php echo __('2. Continue by saving afterwards','psfbldr'); ?>'; color: #999; font-size: 1.5em;z-index:9;padding:0; position:absolute;bottom:20px;right:20px;width:inherit;padding-bottom:100px;background-image:url(<?php echo plugins_url( '/images/arrow-drag-in-down-right.png', (dirname(__FILE__)) ); ?>);background-position:100% 100%;background-repeat:no-repeat;}


<?php do_action( 'psfb_edit_cssstyles' ); ?>
</style>


<div class="editoptions_template" style="display:none;">
<button type="button" class="delete btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span> <?php echo __('Delete','psfbldr'); ?></button>
<button type="button" class="edit btn btn-success btn-xs"><span class="glyphicon glyphicon-edit"></span> <?php echo __('Edit','psfbldr'); ?></button>
	<!--<div type="button" class="move-h btn btn-default btn-xs" style="cursor:ew-resize;" title="<?php echo __('Move','psext'); ?>"><span class="fa fa-arrows-h"></span></div>-->
	<div type="button" class="move-v btn btn-default btn-xs" style="cursor:ns-resize;" title="<?php echo __('Move','psext'); ?>"><span class="fa fa-arrows-v"></span></div>
	<div type="button" class="move-hv btn btn-default btn-xs" style="cursor:move;" title="<?php echo __('Move','psext'); ?>"><span class="fa fa-arrows"></span></div>
</div>


<div class="modal fade" id="psfb_help_modal" tabindex="-1" role="dialog" aria-labelledby="psfb_help_modal_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo __('Close','psfbldr'); ?>"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="psfb_help_modal_label"><?php echo __('Help &amp; Hints','psfbldr'); ?></h4>
      </div>
      <div class="modal-body">
      	
      	
      	<?php 
      	if ( is_plugin_active( 'postman-smtp/postman-smtp.php' ) ) {
      		//plugin is activated
      	} else if(is_dir( dirname(dirname(dirname(__FILE__))).'/postman-smtp' )){
      		//plugin is installed but inactive
      		?>
      		<div class="form-group">
	      		<label><?php echo __('Postman SMTP installed but inactive','psfbldr'); ?></label>
	      		<a href="<?php 
	      			echo admin_url( 'plugins.php' ).'?plugin_status=inactive';
	      			/*
	      			echo wp_nonce_url(
					    add_query_arg(
					        array(
					            'action' => 'activate',
					            'plugin' => 'postman-smtp/postman-smtp.php',
					            'plugin_status' => 'all',
					            'paged' => 1
					            
					        ),
					        admin_url( 'plugins.php' )
					    ),
					    'activate_postman-smtp%2Fpostman-smtp.php_all_1'
						);*/
	      		?>" target="_blank" class="btn btn-success btn-xs"><?php echo __('Activate Postman SMTP','psfbldr'); ?></a>
	      		<p class="help-block"><?php echo __('By clicking the button you will be transfered to your plugin-section. Please find Postman SMTP there and click on &#39;activate&#39;. Don&#39;nt forget to configure the plugin afterwards.','psfbldr'); ?></p>
	      	</div>
      	<?php
				} else {
					//plugin is not installed
					?>
					<div class="form-group">
      		<label><?php echo __('Trouble sending emails?','psfbldr'); ?></label>
      		<a href="<?php
					echo wp_nonce_url(
					    add_query_arg(
					        array(
					            'action' => 'install-plugin',
					            'plugin' => 'postman-smtp'
					        ),
					        admin_url( 'update.php' )
					    ),
					    'install-plugin_postman-smtp'
					);
					?>" target="_blank" class="btn btn-success btn-xs"><?php echo __('Install Postman SMTP','psfbldr'); ?></a>
      		<p class="help-block"><?php echo __('The button above will lead you to the plugin installation of','psfbldr'); ?> <a href="https://wordpress.org/plugins/postman-smtp/" target="_blank">Postman SMTP</a></p>
      	</div>
      	<?php
				}
      	?>
      	
      	
      	<div class="form-group">
      		<label><?php echo __('Visit support forum','psfbldr'); ?></label>
      		<a href="https://wordpress.org/support/plugin/planso-forms" target="_blank" class="btn btn-success btn-xs"><?php echo __('Support forum','psfbldr'); ?></a>
      		<p class="help-block"><?php echo __('The button above will open a new window and lead you to the PlanSo Forms section of the WordPress support forum.','psfbldr'); ?></p>
      	</div>
      	
      	<div class="form-group">
      		<label><?php echo __('Test form submission using dummy values','psfbldr'); ?></label>
      		<button type="button" class="psfb_test_form_submit btn btn-success btn-xs"><?php echo __('Test submission','psfbldr'); ?></button>
      		<p class="help-block"><?php echo __('By clicking the above button PlanSo Forms will simulate a form submission. This is usefull for testing email settings or other API connections.','psfbldr'); ?></p>
      	</div>
      	
      	<form method="post" target="_blank" action="http://plugin-api.planso.net/submit-form-settings.php">
	      	<div style="display:none;">
      			<?php
      				$current_user = wp_get_current_user();
      				
      				echo '<div class="form-group"><input type="text" name="email" value="'.$current_user->user_email.'"></div>';
      				echo '<div class="form-group"><input type="text" name="first_name" value="'.$current_user->user_firstname.'"></div>';
      				echo '<div class="form-group"><input type="text" name="last_name" value="'.$current_user->user_lastname .'"></div>';
      				echo '<div class="form-group"><input type="text" name="url" value="http://'.$_SERVER['HTTP_HOST'].'"></div>';
      				echo '<div class="form-group"><textarea name="server">'.print_r($_SERVER,true).'</textarea></div>';
      			?>
      			<div class="form-group"><textarea name="json" id="psfb_submit_settings_json"></textarea></div>
      		</div>
	      	<div class="form-group">
	      		<label><?php echo __('Request support','psfbldr'); ?></label>
	      		<textarea name="msg" class="form-control" rows="7" placeholder="<?php echo __('Please describe your problem as detailed as possible','psfbldr'); ?>"></textarea>
	      		<button class="psfb_submit_form_settings_to_planso btn btn-danger btn-xs" type="submit" onmousedown="jQuery('#psfb_submit_settings_json').val( jQuery('#psfb_json').val() );"><?php echo __('Submit message and settings','psfbldr'); ?></button>
	      		<p class="help-block"><?php echo __('By clicking the button you will send your name, email, form settings and server details to PlanSo. With the data submitted the PlanSo Forms Team can offer you better support.','psfbldr'); ?></p>
	      	</div>
      	</form>
      	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close','psfbldr'); ?></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div class="modal fade" id="fieldeditor" tabindex="-1" role="dialog" aria-labelledby="fieldeditorlabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo __('Close','psfbldr'); ?>"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="fieldeditorlabel"><?php echo __('Edit field','psfbldr'); ?></h4>
      </div>
      <div class="modal-body">
        
        
        <div role="tabpanel">
				
				  <!-- Nav tabs -->
				  <ul class="nav nav-tabs" role="tablist">
				    <li role="presentation" class="active basicstab"><a href="#tab-basics" aria-controls="basics" role="tab" data-toggle="tab"><?php echo __('Basic','psfbldr'); ?></a></li>
				    <li role="presentation" class="basicshtmltab"><a href="#tab-basicshtml" aria-controls="basicshtml" role="tab" data-toggle="tab"><?php echo __('Basic','psfbldr'); ?></a></li>
				    <li role="presentation" class="selectoptionstab"><a href="#tab-selectoptions" aria-controls="selectoptions" role="tab" data-toggle="tab"><?php echo __('Select values','psfbldr'); ?></a></li>
				    <?php do_action('psfb_edit_modal_after_selectoptions_tab'); ?>
				    <li role="presentation" class="experttab"><a href="#tab-expert" aria-controls="profile" role="tab" data-toggle="tab"><?php echo __('Advanced','psfbldr'); ?></a></li>
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
						  
						  <div class="form-group field_orientation_wrapper">
						    <label for="field_orientation"><?php echo __('Orientation','psfbldr'); ?></label>
						    <select id="field_orientation" class="form-control">
						    	<option value="horizontal"><?php echo __('Horizontal (inline)','psfbldr'); ?></option>
						    	<option value="vertical"><?php echo __('Vertical (one per line)','psfbldr'); ?></option>
						    </select>
						    <p class="help-block"><?php echo __('This lets you choose the orientation of your checkbox and radio fields','psfbldr'); ?></p>
						  </div>
						  
						  <div class="row">
						  	
							  <div class="form-group col-md-6">
							    <label for="field_required"><?php echo __('Mandatory','psfbldr'); ?></label>
							  	<div class="checkbox">
							  		<label>
							  			<input type="checkbox" id="field_required" value="1"> <?php echo __('Mandatory field','psfbldr'); ?>
							  		</label>
							  	</div>
							    <p class="help-block"><?php echo __('Check this to mark the field as required','psfbldr'); ?></p>
							  </div>
							  
							  <div class="form-group col-md-6">
							    <label for="field_hide_label"><?php echo __('Hide label','psfbldr'); ?></label>
							  	<div class="checkbox">
							  		<label>
							  			<input type="checkbox" id="field_hide_label" value="1"> <?php echo __('Hide label of field','psfbldr'); ?>
							  		</label>
							  	</div>
							    <p class="help-block"><?php echo __('Check this to hide the label of the field','psfbldr'); ?></p>
							  </div>
						  
						</div>
						  
		        </div><!-- ende basics -->
		        <div class="basicshtml tab-pane" id="tab-basicshtml" role="tabpanel">
		        	
						  
						  <div class="form-group">
						    <label for="field_html_content"><?php echo __('Content','psfbldr'); ?></label>
						    <textarea id="field_html_content" class="form-control"></textarea>
						    <p class="help-block"><?php echo __('Enter your content here. You can use HTML tags to enrich this block.','psfbldr'); ?></p>
						  </div>
						  
		        </div><!-- ende basicshtml -->
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
								  <div class="row selectoptions_content_desc">
			        			
			        			<div class="col-md-5" title="<?php echo __('The visible part','psfbldr'); ?>">
			        				<?php echo __('Label','psfbldr'); ?>
			        			</div>
			        			<div class="col-md-5" title="<?php echo __('The value will be submitted to you','psfbldr'); ?>">
			        				<?php echo __('Value','psfbldr'); ?>
			        			</div>
			        			<div class="col-md-2">
			        				
			        			</div>
			        			
			        		</div>
			        		<div class="row selectoptions_quick_content_desc" style="display:none;">
			        			
			        			<div class="col-md-12">
			        				<?php echo __('Please enter one option per line. If you want to use a value different from the label please divide your label value pairs with &quot;<em>@=</em>&quot; like &quot;mylabel@=myvalue&quot;.','psfbldr'); ?>
			        			</div>
			        			
			        		</div>
								  
			        		<div class="selectoptions_content">
			        			
			        		</div>
			        		<div class="selectoptions_quick_content" style="display:none;">
			        			<textarea class="form-control" rows="10"></textarea>
			        		</div>
			        		<div class="selectoptions_option">
			        			<button class="btn btn-success btn-xs add_selectoption"><span class="glyphicon glyphicon-plus"></span> <?php echo __('Add option','psfbldr'); ?></button>
			        			<button class="btn btn-default btn-xs toggle_selectoption"><span class="glyphicon glyphicon-random"></span> <?php echo __('Toggle option edit mode','psfbldr'); ?></button>
			        		</div>
			        		
							  </div>
		        	</div>
		        </div>
		        
		        <?php do_action('psfb_edit_modal_after_selectoptions_tab_content'); ?>
		        
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
						  
						  <?php do_action( 'psfb_edit_modal_advanced_after_style' ); ?>
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
	<?php echo $shortcode_out; ?>
	</div>
	<div class="inside">
		<div id="edit-slug-box" class="hide-if-no-js">
			</div>
	</div>
</div>

<?php do_action( 'psfb_edit_after_title_input' ); ?>

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
			<div class="btn-group" role="group" style="float:right;">
				<button class="psfb_open_help_modal btn btn-default" title="<?php echo __('Help','psfbldr'); ?>"><span class="fa fa-question-circle"></span></button>
				<button class="psfb_save_perform btn btn-primary"><?php echo __('Save','psfbldr'); ?></button>
			</div>
			<div style="clear:both;"></div>
		</div>
	</div>
</section>


 <div role="tabpanel">
				
	  <!-- Nav tabs -->
	  <ul class="nav nav-tabs" role="tablist">
	    <li role="presentation" class="active"><a href="#psfb_tab_form_stages" aria-controls="form_stage" role="tab" data-toggle="tab"><?php echo __('Form fields','psfbldr'); ?></a></li>
	    <li role="presentation"><a href="#psfb_tab_admin_email" aria-controls="admin_email" role="tab" data-toggle="tab"><?php echo __('Admin email','psfbldr'); ?></a></li>
	    <li role="presentation"><a href="#psfb_tab_user_email" aria-controls="user_email" role="tab" data-toggle="tab"><?php echo __('User email','psfbldr'); ?></a></li>
	    <li role="presentation"><a href="#psfb_tab_thankyou_page" aria-controls="thankyou_page" role="tab" data-toggle="tab"><?php echo __('Thankyou page','psfbldr'); ?></a></li>
	    <li role="presentation"><a href="#psfb_tab_additional_settings" aria-controls="additional_settings" role="tab" data-toggle="tab"><?php echo __('Additional settings','psfbldr'); ?></a></li>
	  	<li role="presentation"><a href="#psfb_tab_pro" aria-controls="psfb_pro" role="tab" data-toggle="tab"><?php echo __('PlanSo Forms Pro','psfbldr'); ?></a></li>
	  	<?php do_action( 'psfb_edit_tabs_link' ); ?>
	  </ul>
  
  	<div class="tab-content">
  
      <div class="form_stage tab-pane active" id="psfb_tab_form_stages" role="tabpanel">
			

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

<?php do_action( 'psfb_edit_after_form_stage' ); ?>



			</div>
			<div class="thankyou_page tab-pane" id="psfb_tab_thankyou_page" role="tabpanel">
			

<section class="container-fluid postbox "><!-- container-fluid -->
	<section class="row">
			
		<div class="metabox-holder">
			<div class="meta-box-sortables">
				<div id="titlediv">
					<h3 class="hndle" style="cursor:default;"><span><strong><?php echo __('Thank you page','psfbldr'); ?></strong></span></h3>
				</div>
				<br class="clear">
				<section class="col-md-12">
					
					  <?php do_action( 'psfb_edit_in_thankyoupage_content_top' ); ?>
						<div class="form-group">
					    <label for="thankyou_page_url"><?php echo __('Thank you page url','psfbldr'); ?></label>
					    <input type="text" placeholder="http://" id="thankyou_page_url" class="form-control thankyou_page_url" value="<?php if(isset($j->thankyou_page_url))echo $j->thankyou_page_url; ?>">
					    <p class="help-block"><?php echo __('Enter the website address (inkl. http://) of the page the user should be redirected to after successfully submitting the form','psfbldr'); ?></p>
					  </div>
					  <?php do_action( 'psfb_edit_in_thankyoupage_content' ); ?>
				</section>
			</div>
		</div>
	</section>
</section>

<?php do_action( 'psfb_edit_after_thankyoupage' ); ?>



			</div>
			<div class="admin_email tab-pane" id="psfb_tab_admin_email" role="tabpanel">
			

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
									$admin_mail->from_name = get_option( 'blogname', __('Your Wordpress Blog','psfbldr') );
									$admin_mail->from_email = get_option( 'admin_email', 'no-reply@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) );
									$admin_mail->reply_to = '';
									$admin_mail->recipients = array(get_option( 'admin_email', 'info@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) ));
									$admin_mail->bcc = array();
								}
								if(isset($j->mails) && isset($j->mails->user_mail)){
									$user_mail = $j->mails->user_mail;
								} else {
									$user_mail = new stdClass;
									$user_mail->content = '';
									$user_mail->subject = '';
									$user_mail->from_name = get_option( 'blogname', __('Your Wordpress Blog','psfbldr') );
									$user_mail->from_email = get_option( 'admin_email', 'no-reply@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) );
									$user_mail->reply_to = get_option( 'admin_email', 'info@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) );
									$user_mail->recipients = array();
									$user_mail->bcc = array();
								}
							} else {
								$admin_mail = new stdClass;
									$admin_mail->content = '';
									$admin_mail->subject = '';
									$admin_mail->from_name = get_option( 'blogname', __('Your Wordpress Blog','psfbldr') );
									$admin_mail->from_email = get_option( 'admin_email', 'no-reply@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) );
									$admin_mail->reply_to = '';
									$admin_mail->recipients = array(get_option( 'admin_email', 'info@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) ));
									$admin_mail->bcc = array();
								$user_mail = new stdClass;
									$user_mail->content = '';
									$user_mail->subject = '';
									$user_mail->from_name = get_option( 'blogname', __('Your Wordpress Blog','psfbldr') );
									$user_mail->from_email = get_option( 'admin_email', 'no-reply@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) );
									$user_mail->reply_to = get_option( 'admin_email', 'info@'.parse_url($_SERVER['HTTP_HOST'],PHP_URL_HOST) );
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
						
						<?php do_action( 'psfb_edit_after_admin_mail_reply_to' ); ?>
						
						
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


<?php do_action( 'psfb_edit_after_admin_mail' ); ?>




			</div>
			<div class="user_email tab-pane" id="psfb_tab_user_email" role="tabpanel">
			

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
						
						<?php do_action( 'psfb_edit_after_user_mail_reply_to' ); ?>
						
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

<?php do_action( 'psfb_edit_after_user_mail' ); ?>




			</div>
			<div class="psfb_pro tab-pane" id="psfb_tab_pro" role="tabpanel">
				
				<?php do_action( 'psfb_edit_pro_settings' ); ?>
				<?php 
				if(!defined('PLANSO_FORMS_PRO')){
					echo $psfb_pro_teaser;
				}
			//	echo psfb_globals::pro_teaser;
				?>
			</div>
			<div class="additional_settings tab-pane" id="psfb_tab_additional_settings" role="tabpanel">
			

<section class="container-fluid postbox "><!-- container-fluid -->
	<section class="row">
			
		<div class="metabox-holder">
			<div class="meta-box-sortables">
				<div id="titlediv">
					<h3 class="hndle" style="cursor:default;"><span><strong><?php echo __('Additional settings','psfbldr'); ?></strong></span></h3>
				</div>
				<br class="clear">
				
				<section class="col-md-12">
					
					<div class="row">
						<div class="col-md-6">
							
							<div class="form-group checkbox">
						    <label for="ps_link_love">
						    	<input type="checkbox" id="ps_link_love" name="ps_link_love" value="1">
						    	<?php echo __('Get good karma and spread some link love','psfbldr'); ?>
						    </label>
						    <!-- <p class="help-block"><?php echo __('When checked, a powered by link will appear below this form.','psfbldr'); ?></p> -->
						  </div>
						
							<div class="form-group checkbox">
						    <label for="planso_style">
						    	<input type="checkbox" id="planso_style" name="planso_style" value="1">
						    	<?php echo __('Include special Stylesheet based on bootstrap 3.0 if your form does not look good','psfbldr'); ?>
						    </label>
						    <!-- <p class="help-block"><?php echo __('This will add a stylesheet to make your form look as close to the preview as possible.','psfbldr'); ?></p> -->
						  </div>
						
							<div class="form-group checkbox">
						    <label for="clean_attachments">
						    	<input type="checkbox" id="clean_attachments" name="clean_attachments" value="1">
						    	<?php echo __('Do not delete submitted attachments after mailing them','psfbldr'); ?>
						    </label>
						    <!-- <p class="help-block"><?php echo __('If checked all attachments will reside on your server and will not be deleted anymore after they have been attached to the admin email.','psfbldr'); ?></p> -->
						  </div>
						
							<div class="form-group checkbox">
						    <label for="allow_prefill">
						    	<input type="checkbox" id="allow_prefill" name="allow_prefill" value="1">
						    	<?php echo __('Allow fields to be pre populated with a value using $_GET or $_POST','psfbldr'); ?>
						    </label>
						    <!-- <p class="help-block"><?php echo __('If checked all attachments will reside on your server and will not be deleted anymore after they have been attached to the admin email.','psfbldr'); ?></p> -->
						  </div>
						
							<div class="form-group checkbox">
						    <label for="javascript_antispam">
						    	<input type="checkbox" id="javascript_antispam" name="javascript_antispam" value="1">
						    	<?php echo __('Enable special javascript based anti spam protection','psfbldr'); ?>
						    </label>
						    <p class="help-block"><?php echo __('If checked a special hidden field will be appended to your form via javascript. The form will break for users with javascript disabled!','psfbldr'); ?></p>
						  </div>
							
						</div>
						<div class="col-md-6">
							
						<!-- 
							<div class="form-group checkbox">
						    <label for="horizontal_form">
						    	<input type="checkbox" id="horizontal_form" name="horizontal_form" value="1">
						    	<?php echo __('Place labels side by side with fields','psfbldr'); ?>
						    </label>
						    <p class="help-block"><?php echo __('If checked all attachments will reside on your server and will not be deleted anymore after they have been attached to the admin email.','psfbldr'); ?></p>
						  </div>
						  -->
						  <div class="form-group">
						    <label for="horizontal_form">
						    	<?php echo __('Place labels side by side with fields','psfbldr'); ?>
						    </label>
						    <select id="horizontal_form" name="horizontal_form" class="form-control">
						    	<option value="vertical"><?php echo __('Place field labels above fields','psfbldr'); ?></option>
						    	<option value="horizontal"><?php echo __('Place labels side by side with fields (Label width: 16%)','psfbldr'); ?></option>
						    	<option value="horizontal_3"><?php echo __('Place labels side by side with fields (Label width: 25%)','psfbldr'); ?></option>
						    	<option value="horizontal_4"><?php echo __('Place labels side by side with fields (Label width: 33%)','psfbldr'); ?></option>
						    	<option value="horizontal_5"><?php echo __('Place labels side by side with fields (Label width: 41%)','psfbldr'); ?></option>
						    	<option value="horizontal_6"><?php echo __('Place labels side by side with fields (Label width: 50%)','psfbldr'); ?></option>
						    </select>
						    <p class="help-block"><?php echo __('Labels can placed beside the fields according to your theme using different types of widths.','psfbldr'); ?></p>
						  </div>
						  
							<div class="form-group">
						    <label for="psfb_datepicker"><?php echo __('Select the Datepicker that best fits your theme','psfbldr'); ?></label>
					    	<select id="psfb_datepicker" name="psfb_datepicker" class="form-control">
					    		<option value="bootstrap-datetimepicker">Bootstrap DateTimePicker (Eonasdan)</option>
					    		<option value="bootstrap-datepicker-eternicode">Bootstrap Datepicker(Eternicode)</option>
					    		<option value="jquery-ui-datepicker">jQuery UI Datepicker</option>
					    <!--
					    		<option value="bootstrap-datepicker">Bootstrap Datepicker(Eyecon)</option>
					    -->
					    	</select>
						    
						    <p class="help-block"><?php echo __('Depending on your theme you might want to choose a different datepicker for date fields.','psfbldr'); ?></p>
						  </div>
						  
						  
							<div class="form-group">
						    <label for="psfb_date_format">
						    	<?php echo __('Date format','psfbldr'); ?>
						    </label>
						    	<input type="text" id="psfb_date_format" name="psfb_date_format" class="form-control" value="<?php
						    	if( isset($j) && isset($j->date_format) && !empty($j->date_format)){
						    		echo $j->date_format;
						    	} else {
						    		echo get_option('date_format');
						    	}
						    	
						    	?>">
						    	
						    <p class="help-block"><?php echo __('Enter the date format that you want to be used in datepicker fields.','psfbldr'); ?></p>
						  </div>
						  
							
						</div>
					</div>
						
					  
				</section>
			</div>
		</div>
	</section>
</section>

<?php do_action( 'psfb_edit_after_additional_settings' ); ?>


			</div>
			<?php do_action( 'psfb_edit_tabs_content' ); ?>
		</div><!-- tab-content -->
	</div><!-- tabpanel -->


<form method="post" class="psfb_submit_form" action="<?php echo esc_url( add_query_arg( array( 'psfbid' => $post_id ), menu_page_url( 'ps-form-builder', false ) ) ); ?>">
<input type="hidden" name="action" value="save"/>
<div class="form-group" style="display:none;">
  <label><?php echo __('Form HTML','psfbldr'); ?></label>
  <textarea id="psfb_html" class="form-control"></textarea>
  <textarea id="psfb_title" name="title" class="form-control"></textarea>
</div>
<div class="form-group" style="display:none;">
  <label><?php echo __('JSON','psfbldr'); ?></label>
  <textarea id="psfb_json" name="json" class="form-control"><?php echo $psform->post_content;?></textarea>
  
  <button class="psfb_test_form_submit btn btn-default" type="button"><?php echo __('Test form submission','psfbldr'); ?></button>
  <button class="psfb_generate_json btn btn-default" type="button" ><?php echo __('Generate','psfbldr'); ?></button>
</div>
<div class="form-group">
  <button class="psfb_save_html btn btn-primary" style="float:right;"><?php echo __('Save','psfbldr'); ?></button>
  <div style="clear:both;"></div>
</div>
</form>

<div style="clear:both;"></div>
</div><!-- wrap -->
<div style="clear:both;"></div>
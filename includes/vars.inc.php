<?php



$specialfields = array(
	'select',
	'checkbox',
	'radio',
	'textarea',
	'multiselect',
	'textarea',
	'submit',
	'submitimage'
);
$selectfields = array(
	'select',
	'checkbox',
	'radio',
	'multiselect'
);	
$htmlfields = array(
	'html_div',
	'html_hr',
	'html_header',
	'html_paragraph'
);

$field_configurations = apply_filters('psfb_vars_field_configurations',
	array(
		'specialfields' => $specialfields,
		'selectfields' => $selectfields,
		'htmlfields' => $htmlfields
	)
);
$specialfields = $field_configurations['specialfields'];
$selectfields = $field_configurations['selectfields'];
$htmlfields = $field_configurations['htmlfields'];

$fieldtypes = array(
	'divider_templates' => array('label'=> __('Predefined fields','psfbldr'),'type'=>'divider'),
	'name' => array('label'=>__('Name','psfbldr'),'type'=>'text'),
	'firstname' => array('label'=>__('First name','psfbldr'),'type'=>'text'),
	'lastname' => array('label'=>__('Last name','psfbldr'),'type'=>'text'),
	'email' => array('label'=>__('Email','psfbldr'),'type'=>'email'),
	'landline' => array('label'=>__('Landline','psfbldr'),'type'=>'tel'),
	'mobil' => array('label'=>__('Mobile phone','psfbldr'),'type'=>'tel'),
	'tel' => array('label'=>__('Phone','psfbldr'),'type'=>'tel'),
	'divider_generic' => array('label'=>__('Generic fields','psfbldr'),'type'=>'divider'),
	'text' => array('label'=>__('Text','psfbldr'),'type'=>'text'),
	'textarea' => array('label'=>__('Multiline text','psfbldr'),'type'=>'textarea','rows'=>true,'cols'=>true),
	'number' => array('label'=>__('Number','psfbldr'),'type'=>'number','min'=>true,'max'=>true,'step'=>true),
	'divider_date' => array('label'=>__('Date and time fields','psfbldr'),'type'=>'divider'),
	'date' => array('label'=>__('Date','psfbldr'),'type'=>'date'),
	'datetime' => array('label'=>__('Date and time','psfbldr'),'type'=>'datetime'),
	'time' => array('label'=>__('Time','psfbldr'),'type'=>'time'),
	'week' => array('label'=>__('Week','psfbldr'),'type'=>'week'),
	'month' => array('label'=>__('Month','psfbldr'),'type'=>'month'),
	'divider_select' => array('label'=>__('Select fields','psfbldr'),'type'=>'divider'),
	'select' => array('label'=>__('Select','psfbldr'),'type'=>'select'),
	'multiselect' => array('label'=>__('Multiselect','psfbldr'),'type'=>'select','multiple'=>true),
	'radio' => array('label'=>__('Radio','psfbldr'),'type'=>'radio'),
	'checkbox' => array('label'=>__('Checkbox','psfbldr'),'type'=>'checkbox'),
	'divider_special' => array('label'=>__('Special fields','psfbldr'),'type'=>'divider'),
	'range' => array('label'=>__('Range','psfbldr'),'type'=>'range','min'=>true,'max'=>true,'step'=>true),
	'search' => array('label'=>__('Search','psfbldr'),'type'=>'search'),
	'hidden' => array('label'=>__('Hidden','psfbldr'),'type'=>'hidden'),
	'file' => array('label'=>__('Single file','psfbldr'),'type'=>'file'),
	'multifile' => array('label'=>__('Multiple files','psfbldr'),'type'=>'file','multiple'=>true),
	'url' => array('label'=>__('Url','psfbldr'),'type'=>'url'),
	'color' => array('label'=>__('Color','psfbldr'),'type'=>'color'),
	
	'divider_html' => array('label' => __('HTML Tags','psfbldr'), 'type' => 'divider'),
	
	'html_div' => array('label' => __('HTML Code','psfbldr'), 'type'=>'div','wrap'=>true,'icon'=>'fa-terminal'),
	'html_hr' => array('label' => __('Horizontal Divider','psfbldr'), 'type'=>'hr','wrap'=>false,'icon'=>'fa-minus'),
	'html_header' => array('label' => __('Headline','psfbldr'), 'type'=>'h','options'=>array(1,2,3,4,5,6),'wrap'=>true,'icon'=>'fa-header'),
	'html_paragraph' => array('label' => __('Paragraph','psfbldr'), 'type'=>'p','wrap'=>true,'icon'=>'fa-paragraph'),
	
	'divider_buttons' => array('label'=>__('Submit buttons','psfbldr'),'type'=>'divider'),
	'button' => array('label'=>__('Button','psfbldr'),'type'=>'button'),
	'submit' => array('label'=>__('Submit-Button','psfbldr'),'type'=>'submit'),
	'imagesubmit' => array('label'=>__('Submit-Image','psfbldr'),'type'=>'image')
);

$fieldtypes = apply_filters('psfb_vars_fieldtypes',$fieldtypes);

$powered_by_txt_email = <<<EOF




=====================
Powered by PlanSo Forms
http://forms.planso.de/
EOF;

if(!defined('PSFB_POWERED_BY_TXT')){
	define('PSFB_POWERED_BY_TXT',$powered_by_txt_email);
}

$powered_by_html_email = <<<EOF

<table class="footer-wrap">
	<tr>
		<td></td>
		<td class="container">
			<div class="content">
				<table>
					<tr>
						<td align="center">
							<p><a href="http://forms.planso.de/">Powered by PlanSo Forms</a></p>
						</td>
					</tr>
				</table>
			</div>
		</td>
		<td></td>
	</tr>
</table>

EOF;

if(!defined('PSFB_POWERED_BY_HTML')){
	define('PSFB_POWERED_BY_HTML',$powered_by_html_email);
}



$psfb_html_email_template = <<<EOF

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=<!-- mail_charset -->" />
<title><!-- mail_subject --></title>
<style>
/* -------------------------------------
GLOBAL
------------------------------------- */
* {
margin: 0;
padding: 0;
font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
font-size: 100%;
line-height: 1.6;
}
img {
max-width: 100%;
}
body {
font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
-webkit-font-smoothing: antialiased;
-webkit-text-size-adjust: none;
width: 100%!important;
height: 100%;
}
/* -------------------------------------
ELEMENTS
------------------------------------- */
a {
color: #348eda;
}
.btn-primary {
text-decoration: none;
color: #FFF;
background-color: #348eda;
border: solid #348eda;
border-width: 10px 20px;
line-height: 2;
font-weight: bold;
margin-right: 10px;
text-align: center;
cursor: pointer;
display: inline-block;
border-radius: 25px;
}
.btn-secondary {
text-decoration: none;
color: #FFF;
background-color: #aaa;
border: solid #aaa;
border-width: 10px 20px;
line-height: 2;
font-weight: bold;
margin-right: 10px;
text-align: center;
cursor: pointer;
display: inline-block;
border-radius: 25px;
}
.last {
margin-bottom: 0;
}
.first {
margin-top: 0;
}
.padding {
padding: 10px 0;
}
/* -------------------------------------
BODY
------------------------------------- */
table.body-wrap {
width: 100%;
padding: 20px;
}
table.body-wrap .container {
border: 1px solid #f0f0f0;
}
/* -------------------------------------
FOOTER
------------------------------------- */
table.footer-wrap {
width: 100%;
clear: both!important;
}
.footer-wrap .container p {
font-size: 12px;
color: #666;
}
table.footer-wrap a {
color: #999;
}
/* -------------------------------------
TYPOGRAPHY
------------------------------------- */
h1, h2, h3 {
font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
line-height: 1.1;
margin-bottom: 15px;
color: #000;
margin: 40px 0 10px;
line-height: 1.2;
font-weight: 200;
}
h1 {
font-size: 36px;
}
h2 {
font-size: 28px;
}
h3 {
font-size: 22px;
}
p, ul, ol {
margin-bottom: 10px;
font-weight: normal;
font-size: 14px;
}
ul li, ol li {
margin-left: 5px;
list-style-position: inside;
}
/* ---------------------------------------------------
RESPONSIVENESS
Nuke it from orbit. It's the only way to be sure.
------------------------------------------------------ */
/* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
.container {
display: block!important;
max-width: 600px!important;
margin: 0 auto!important; /* makes it centered */
clear: both!important;
}
/* Set the padding on the td rather than the div for Outlook compatibility */
.body-wrap .container {
padding: 20px;
}
/* This should also be a block element, so that it will fill 100% of the .container */
.content {
max-width: 600px;
margin: 0 auto;
display: block;
}
/* Let's make sure tables in the content area are 100% wide */
.content table {
width: 100%;
}
</style>
</head>
<body bgcolor="#f6f6f6">
<table class="body-wrap">
	<tr>
		<td></td>
		<td class="container" bgcolor="#FFFFFF">
			<div class="content">
			
			<!-- mail_body -->
			
			</div>
		</td>
		<td></td>
	</tr>
</table>

<!-- mail_footer -->

</body>
</html>
EOF;


if(!defined('PSFB_HTML_EMAIL_TEMPLATE')){
	define('PSFB_HTML_EMAIL_TEMPLATE',$psfb_html_email_template);
}

//_POST KEY => MAIL VARIABLE NAME
$psfb_mail_tracking_map = array(
	'psfb_pageurl' => 'pageurl',
	'psfb_userip' => 'userip',
	'psfb_useragent' => 'useragent',
	'psfb_landingpage' => 'landingpage',
	'psfb_referer' => 'referer'
);

$psfb_current_date =  date_i18n(get_option( 'date_format' ));
$psfb_current_datetime = date_i18n(get_option( 'date_format' )).' '. date_i18n(get_option('time_format'));
$psfb_current_time = date_i18n(get_option('time_format'));

$psfb_mail_dynamic_values = array(
	'psfb_current_date' => $psfb_current_date,
	'psfb_current_datetime' => $psfb_current_datetime,
	'psfb_current_time' => $psfb_current_time
);

$psfb_pro_teaser = '

<section class="container-fluid postbox "><!-- container-fluid -->
	<section class="row">
			
		<div class="metabox-holder">
			<div class="meta-box-sortables">
				<div id="titlediv">
					<h3 class="hndle" style="cursor:default;"><span><strong>'. __('Upgrade to PlanSo Forms Pro and get:','psfbldr').'</strong></span></h3>
				</div>
				<br class="clear">
				
				<section class="col-md-12">
					

					<ul class="fa-ul">
						<li><i class="fa-li fa fa-check-square"></i>'. __('All Free Features','psfbldr').'</li>
						<li><i class="fa-li fa fa-check-square"></i>'. __('PayPal Payment Forms','psfbldr').'</li>
						<li><i class="fa-li fa fa-check-square"></i>'. __('HTML Email & Tracking Information','psfbldr').'</li>
						<li><i class="fa-li fa fa-check-square"></i>'. __('Easyly send attachments via autoresponder to your users','psfbldr').'</li>
						<li><i class="fa-li fa fa-check-square"></i>'. __('Conditional logic for intelligent field handling','psfbldr').'</li>
						<li><i class="fa-li fa fa-check-square"></i>'. __('Zapier.com & Pushover.net Integration','psfbldr').'</li>
						<li><i class="fa-li fa fa-check-square"></i>'. __('Google Analytics Conversion Tracking','psfbldr').'</li>
						<li><i class="fa-li fa fa-check-square"></i>'. __('1 Year Priority Support  & Updates','psfbldr').'</li>
						<li><i class="fa-li fa fa-check-square"></i>'. __('No reference to PlanSo','psfbldr').'</li>
						<li><i class="fa-li fa fa-check-square"></i>'. __('And much more..','psfbldr').'.</li>
					</ul>
					
					<p>
						<!-- <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2JD3JD2J5Q72Q" target="_blank" class="btn btn-primary btn-xl btn-large">'.__('Upgrade now','psfbldr').'</a> -->
						<a href="http://forms.planso.de/pricing/" target="_blank" class="btn btn-primary btn-xl btn-large">'.__('Upgrade to PlanSo Forms Pro now','psfbldr').'</a>
					</p>
					<p>
						<a href="http://forms.planso.de/" target="_blank">'.__('Check out PlanSo Forms Pro before upgrading','psfbldr').'</a>
					</p>
					
				</section>
			</div>
		</div>
	</section>
</section>

';



?>
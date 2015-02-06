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
	'divider_buttons' => array('label'=>__('Submit buttons','psfbldr'),'type'=>'divider'),
	'button' => array('label'=>__('Button','psfbldr'),'type'=>'button'),
	'submit' => array('label'=>__('Submit-Button','psfbldr'),'type'=>'submit'),
	'imagesubmit' => array('label'=>__('Submit-Image','psfbldr'),'type'=>'image')
);

$powered_by_txt_email = <<<EOF




=====================
Powered by PlanSo Forms
http://forms.planso.de/
EOF;

if(!defined('PSFB_POWERED_BY_TXT')){
	define('PSFB_POWERED_BY_TXT',$powered_by_txt_email);
}

?>
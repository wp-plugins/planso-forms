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
	'divider_templates' => array('label'=> __('Vordefinierte Felder','psfbldr'),'type'=>'divider'),
	'name' => array('label'=>__('Name','psfbldr'),'type'=>'text'),
	'firstname' => array('label'=>__('Vorname','psfbldr'),'type'=>'text'),
	'lastname' => array('label'=>__('Nachname','psfbldr'),'type'=>'text'),
	'email' => array('label'=>__('E-Mail','psfbldr'),'type'=>'email'),
	'landline' => array('label'=>__('Festnetz','psfbldr'),'type'=>'tel'),
	'mobil' => array('label'=>__('Mobilnummer','psfbldr'),'type'=>'tel'),
	'tel' => array('label'=>__('Telefon','psfbldr'),'type'=>'tel'),
	'divider_generic' => array('label'=>__('Generische Felder','psfbldr'),'type'=>'divider'),
	'text' => array('label'=>__('Text','psfbldr'),'type'=>'text'),
	'textarea' => array('label'=>__('Mehrzeiliger Text','psfbldr'),'type'=>'textarea','rows'=>true,'cols'=>true),
	'number' => array('label'=>__('Nummer','psfbldr'),'type'=>'number','min'=>true,'max'=>true,'step'=>true),
	'divider_date' => array('label'=>__('Felder f&uuml;r Datum und Zeit','psfbldr'),'type'=>'divider'),
	'date' => array('label'=>__('Datum','psfbldr'),'type'=>'date'),
	'datetime' => array('label'=>__('Datum mit Uhrzeit','psfbldr'),'type'=>'datetime'),
	'time' => array('label'=>__('Uhrzeit','psfbldr'),'type'=>'time'),
	'week' => array('label'=>__('Woche','psfbldr'),'type'=>'week'),
	'month' => array('label'=>__('Monat','psfbldr'),'type'=>'month'),
	'divider_select' => array('label'=>__('Felder f&uuml;r Auswahl','psfbldr'),'type'=>'divider'),
	'select' => array('label'=>__('Auswahlfeld','psfbldr'),'type'=>'select'),
	'multiselect' => array('label'=>__('Mehrfachauswahlfeld','psfbldr'),'type'=>'select','multiple'=>true),
	'radio' => array('label'=>__('Radio-Schaltfl&auml;che','psfbldr'),'type'=>'radio'),
	'checkbox' => array('label'=>__('Checkbox','psfbldr'),'type'=>'checkbox'),
	'divider_special' => array('label'=>__('Spezialfelder','psfbldr'),'type'=>'divider'),
	'range' => array('label'=>__('Range','psfbldr'),'type'=>'range','min'=>true,'max'=>true,'step'=>true),
	'search' => array('label'=>__('Suche','psfbldr'),'type'=>'search'),
	'hidden' => array('label'=>__('Versteckt','psfbldr'),'type'=>'hidden'),
	'file' => array('label'=>__('Datei','psfbldr'),'type'=>'file'),
	'multifile' => array('label'=>__('Dateien','psfbldr'),'type'=>'file','multiple'=>true),
	'url' => array('label'=>__('Website','psfbldr'),'type'=>'url'),
	'color' => array('label'=>__('Color','psfbldr'),'type'=>'color'),
	'divider_buttons' => array('label'=>__('Schaltfl&auml;chen','psfbldr'),'type'=>'divider'),
/*	'button' => array('label'=>__('Button','psfbldr'),'type'=>'button'),*/
	'submit' => array('label'=>__('Submit-Button','psfbldr'),'type'=>'submit'),
	'imagesubmit' => array('label'=>__('Submit-Image','psfbldr'),'type'=>'image')
);
	
	
?>
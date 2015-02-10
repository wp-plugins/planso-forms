<?php
/**
 * Plugin Name: PlanSo Forms
 * Plugin URI: http://forms.planso.de/
 * Description: Build forms and manage forms with the PlanSo Form Builder forms management plugin. PlanSo Form Builder makes it easy to create professional forms with drag and drop and all forms can be customnized in an easy and streamlined way.
 * Version: 1.0.3
 * Author: PlanSo.de
 * Author URI: http://forms.planso.de/
 * Text Domain: psfbldr
 * Domain Path: /locale/
 * License: GPL2
 */
/*  Copyright 2015  Stephan Helbig  (email : tech@planso.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

load_plugin_textdomain( 'psfbldr', false, dirname( plugin_basename( __FILE__ ) ).'/locale' );


function ps_echo_form($atts, $content= ''){
	$atts = shortcode_atts(
		array(
			'id' => false,
			'title' => __('Unnamed','psfbldr')
		),
		$atts, 'psfb'
	);
	
	$psform = get_post( $atts['id'] );
	
	$j = json_decode($psform->post_content);
	
	$out = require(dirname(__FILE__).'/includes/form.php');
	
	return $out;
}

add_shortcode( 'psfb', 'ps_echo_form'  );


add_filter('widget_text', 'do_shortcode'); 

// Original Referrer 
function psfb_set_session_values() 
{
	if (!session_id()) 
	{
		session_start();
	}

	if (!isset($_SESSION['OriginalRef'])) 
	{
		$_SESSION['OriginalRef'] = $_SERVER['HTTP_REFERER']; 
	}

	if (!isset($_SESSION['LandingPage'])) 
	{
		$_SESSION['LandingPage'] = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]; 
	}
	if(isset($_SESSION['psfb_track_successfull_submission'])){
		$_SESSION['psfb_track_successfull_submission'] = null;
		add_action('wp_footer', 'psfb_track_successfull_submission');
	}
}
add_action('init', 'psfb_set_session_values');

function psfb_track_successfull_submission(){
?>
<script type="text/javascript">
var _gaq = _gaq || [];_gaq.push(['_trackEvent', 'PlanSo Forms', '<?php echo $_SESSION['psfb_track_successfull_submission_form_label']; ?>', '<?php echo $_SESSION['psfb_track_successfull_submission_permalink']; ?>']);
try{ga('send', 'event', 'PlanSo Forms', '<?php echo $_SESSION['psfb_track_successfull_submission_form_label']; ?>', '<?php echo $_SESSION['psfb_track_successfull_submission_form_id']; ?>', {'page': '<?php echo $_SESSION['psfb_track_successfull_submission_permalink']; ?>'});}catch(e){console.log('analytics.js not loaded');}
</script>
<?php
	$_SESSION['psfb_track_successfull_submission_form_id'] = null;
	$_SESSION['psfb_track_successfull_submission_permalink'] = null;
}

/** Register Admin Menu */
add_action( 'admin_menu', 'ps_form_builder_admin_menu' );

/** Hook Admin Menu */
function ps_form_builder_admin_menu() {
	
	$edit = add_menu_page( __('PlanSo Form Builder','psfbldr'), __('PlanSo Form Builder','psfbldr'), 'manage_options', 'ps-form-builder', 'ps_form_builder_list' );
	add_submenu_page( 'ps-form-builder', __('Create new PlanSo Form','psfbldr'), __('New Form','psfbldr'), 'manage_options', 'ps-form-builder-new', 'ps_form_builder_options');
	
	add_action( 'load-' . $edit, 'psfb_load_contact_form_admin' );
}

function psfb_submit_form(){
	if(!isset($_POST['psfb_form_submit'])){
		return;
	}
	
	require( dirname(__FILE__).'/includes/submit.php' );
	
}
add_action('init', 'psfb_submit_form');


add_action('admin_init', 'psfb_register');
 
function psfb_register() {
    $args = array(
        'label' => __('PlanSo Forms'),
        'singular_label' => __('PlanSo Form'),
        'public' => false,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => true,
        'supports' => array('title', 'editor')
    );
 
    register_post_type( 'psfb' , $args );
}

function psfb_current_action() {
	if ( isset( $_REQUEST['action'] ) && -1 != $_REQUEST['action'] )
		return $_REQUEST['action'];

	if ( isset( $_REQUEST['action2'] ) && -1 != $_REQUEST['action2'] )
		return $_REQUEST['action2'];

	return false;
}

add_action( 'psfb_admin_notices', 'psfb_admin_updated_message' );
function psfb_admin_updated_message() {
	if ( empty( $_REQUEST['message'] ) )
		return;

	if ( 'created' == $_REQUEST['message'] )
		$updated_message = esc_html( __( 'PlanSo form created.', 'psfbldr' ) );
	elseif ( 'saved' == $_REQUEST['message'] )
		$updated_message = esc_html( __( 'PlanSo form saved.', 'psfbldr' ) );
	elseif ( 'deleted' == $_REQUEST['message'] )
		$updated_message = esc_html( __( 'PlanSo form deleted.', 'psfbldr' ) );

	if ( empty( $updated_message ) )
		return;

?>
<div id="message" class="updated"><p><?php echo $updated_message; ?></p></div>
<?php
}


function psfb_save_form(){
	
	$id = $_REQUEST['post'];
	$title = $_REQUEST['title'];
	$post_content = $_REQUEST['json'];
	
	if ( !isset($id) || empty($id) || $id == -1 ) {
		$id = wp_insert_post( array(
			'post_type' => 'psfb',
			'post_status' => 'publish',
			'post_title' => $title,
			'post_content' => trim( $post_content ) ) );
	} else {
		$post_id = wp_update_post( array(
			'ID' => (int) $id,
			'post_status' => 'publish',
			'post_title' => $title,
			'post_content' => trim( $post_content ) ) );
	}
	
	$query = array(
		'message' => ( -1 == $_POST['post_ID'] ) ? 'created' : 'saved',
		'post' => $id );

	$redirect_to = add_query_arg( $query, menu_page_url( 'ps-form-builder-new', false ) );
	wp_safe_redirect( $redirect_to );
	exit();
}

function psfb_load_contact_form_admin(){
	global $plugin_page;

	$action = psfb_current_action();
	
	if ( 'save' == $action ) {
		psfb_save_form();
		
		exit();
	}
	if ( 'copy' == $action ) {
		
		$psform = get_post($_REQUEST['post']);
		
		$id = wp_insert_post(
			array(
				'post_type' => 'psfb',
				'post_status' => 'publish',
				'post_title' => __('[Copy]','psfbldr').' '.$psform->post_title,
				'post_content' => trim( $psform->post_content ) 
			) 
		);
	
	
		$query = array(
			'message' => 'created',
			'post' => $id 
		);
		
		$redirect_to = add_query_arg( $query, menu_page_url( 'ps-form-builder', false ) );
		wp_safe_redirect( $redirect_to );
		exit();
	}
	
	if ( 'delete' == $action ) {
		
		wp_delete_post( $_REQUEST['post'], true );
		$query = array();
		$redirect_to = add_query_arg( $query, menu_page_url( 'ps-form-builder', false ) );
		wp_safe_redirect( $redirect_to );
		exit();
	}
}

function ps_form_builder_enqueue($hook) {
	//echo('<h1>'.$hook.'</h1>');
  if ( !strstr($hook,'ps-form-builder')) {
      return;
  }
	wp_register_style( 'font-awesome',plugins_url( '/css/font-awesome-4.2.0/css/font-awesome.min.css', (__FILE__) ) ,array() ,'4.2.0');
	wp_enqueue_style( 'font-awesome');
	wp_enqueue_style( 'bootstrap',plugins_url( '/css/bootstrap/full/bootstrap.min.css', (__FILE__) ) );
	wp_enqueue_style( 'bootstrap-theme',plugins_url( '/css/bootstrap/full/bootstrap-theme.min.css', (__FILE__) ) );
	
	wp_enqueue_script( 'jquery-ui-core' );
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'jquery-ui-droppable' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	
	
	wp_register_script( 'bootstrap-tooltip',plugins_url( '/js/bootstrap/src/tooltip.js', (__FILE__) ), array('jquery'), '3.2.2', true );
	wp_register_script( 'bootstrap-modal',plugins_url( '/js/bootstrap/src/modal.js', (__FILE__) ), array('jquery'), '3.2.2', true );
	wp_register_script( 'bootstrap-collapse',plugins_url( '/js/bootstrap/src/collapse.js', (__FILE__) ), array('jquery'), '3.2.2', true );
	wp_register_script( 'bootstrap-tab',plugins_url( '/js/bootstrap/src/tab.js', (__FILE__) ), array('jquery'), '3.2.2', true );
	wp_register_script( 'bootstrap-transition',plugins_url( '/js/bootstrap/src/transition.js', (__FILE__) ), array('jquery'), '3.2.2', true );
	wp_enqueue_script( 'bootstrap-tooltip' );
	wp_enqueue_script( 'bootstrap-modal' );
	wp_enqueue_script( 'bootstrap-collapse' );
	wp_enqueue_script( 'bootstrap-tab' );
	wp_enqueue_script( 'bootstrap-transition' );
		
}
add_action( 'admin_enqueue_scripts', 'ps_form_builder_enqueue' );


function ps_form_builder_list() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	require_once(dirname(__FILE__).'/includes/list.php');
//	exit();
}
/** Generate Admin Page */
function ps_form_builder_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	require_once(dirname(__FILE__).'/includes/edit.php');
}







// add new buttons
add_filter('mce_buttons', 'psfb_register_buttons');
function psfb_register_buttons($buttons) {
	array_push($buttons, 'separator', 'planso_forms_shortcodes');
	return $buttons;
}
 
// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
add_filter('mce_external_plugins', 'psfb_register_tinymce_javascript');

function psfb_register_tinymce_javascript($plugin_array) {
	psfb_add_tinymce_button();
	$plugin_array['planso_forms_shortcodes'] = plugins_url('/js/mce-button.js',__file__);
	return $plugin_array;
}




function psfb_add_tinymce_button(){
	
	$out = '<script type="text/javascript">';
	$out .= 'var planso = {};planso.forms = {};';
	
	$out .= 'planso.forms.shortcodes = [';
	
	$shortcodes = query_posts( 'post_type=psfb&posts_per_page=20');
	$fcnt = 0;
	foreach($shortcodes as $row){
		if($fcnt > 0)$out .= ',';
		$id = $row->ID;
		if(isset($row->post_title) && !empty($row->post_title)){
			$title = $row->post_title; 
		} else {
			$title = __('Unnamed form','psfbldr');
		}
		
		$out .= '{';
		$out .= "text:'".$title."',onclick:function(){planso.forms.editor.insertContent( '[psfb id=\"".$id."\" title=\"".$title."\"]');}";
		$out .= '}';
		$fcnt ++;
	}
	
	$out .= '];';
	$out .= '</script><style type="text/css">.mce-i-planso-gears-icon{background-image:url(\''.plugins_url( 'images/planso-logo-gears-transparent-72x72.png', (__FILE__)).'\');}</style>';
	echo $out;
}

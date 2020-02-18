<?php
/*
Plugin Name: Mos Courier
Description: A Courier plugin by Md. Mostak Shahid.
Version: 0.0.16
Author: Md. Mostak Shahid
*/
require_once('plugins/update/plugin-update-checker.php');
$pluginInit = Puc_v4_Factory::buildUpdateChecker(
	'https://raw.githubusercontent.com/mostak-shahid/update/master/mos-courier.json',
	__FILE__,
	'mos-courier'
);

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define MOS_COURIER_FILE.
if ( ! defined( 'MOS_COURIER_FILE' ) ) {
	define( 'MOS_COURIER_FILE', __FILE__ );
}
// Define MOS_COURIER_SETTINGS.
if ( ! defined( 'MOS_COURIER_SETTINGS' ) ) {
	define( 'MOS_COURIER_SETTINGS', admin_url('/options-general.php?page=mos_courier_settings') );
}
$mos_courier_option = get_option( 'mos_courier_option' );
$plugin = plugin_basename(MOS_COURIER_FILE); 
require_once ( plugin_dir_path( MOS_COURIER_FILE ) . 'mos-courier-functions.php' );
require_once ( plugin_dir_path( MOS_COURIER_FILE ) . 'mos-courirer-user.php' );
require_once ( plugin_dir_path( MOS_COURIER_FILE ) . 'mos-courier-hooks.php' );
require_once ( plugin_dir_path( MOS_COURIER_FILE ) . 'mos-courier-settings.php' );
require_once ( plugin_dir_path( MOS_COURIER_FILE ) . 'mos-courier-variables.php' );
require_once ( plugin_dir_path( MOS_COURIER_FILE ) . 'mos-courier-post-type.php' );
// require_once ( plugin_dir_path( MOS_COURIER_FILE ) . 'mos-courier-taxonomy.php' );
require_once ( plugin_dir_path( MOS_COURIER_FILE ) . 'aq_resizer.php' );
require_once ( plugin_dir_path( MOS_COURIER_FILE ) . 'QR_BarCode.php' );

require_once ( plugin_dir_path( MOS_COURIER_FILE ) . 'barcode/BarcodeGeneratorPNG.php' );

require_once( plugin_dir_path( MOS_COURIER_FILE ) . 'plugins/metabox/init.php'); 
// require_once( plugin_dir_path( MOS_COURIER_FILE ) . 'plugins/metabox/custom-cmb2-fields.php'); 
// require_once( plugin_dir_path( MOS_COURIER_FILE ) . 'plugins/metabox/extensions/cmb-field-sorter/cmb-field-sorter.php');
// require_once( plugin_dir_path( MOS_COURIER_FILE ) . 'plugins/metabox/extensions/cmb2-conditionals/cmb2-conditionals.php');
require_once( plugin_dir_path( MOS_COURIER_FILE ) . 'mos-courier-metaboxes.php'); 

// require_once('plugins/update/plugin-update-checker.php');
// $pluginInit = Puc_v4_Factory::buildUpdateChecker(
//   'https://raw.githubusercontent.com/mostak-shahid/update/master/mos-courier.json',
//   MOS_COURIER_FILE,
//   'mos-courier'
// );


// register_activation_hook(MOS_COURIER_FILE, 'mos_courier_activate');
// add_action('admin_init', 'mos_courier_redirect');

// function mos_courier_activate() {
//   $mos_courier_option = array();
//     // $mos_courier_option['mos_login_type'] = 'basic';
//     // update_option( 'mos_courier_option', $mos_courier_option, false );
//   add_option('mos_courier_do_activation_redirect', true);
// }

// function mos_courier_redirect() {
//   if (get_option('mos_courier_do_activation_redirect', false)) {
//     delete_option('mos_courier_do_activation_redirect');
//     if(!isset($_GET['activate-multi'])){
//       wp_safe_redirect(MOS_COURIER_SETTINGS);
//     }
//   }
// }

// // Add settings link on plugin page
// function mos_courier_settings_link($links) { 
//   $settings_link = '<a href="'.MOS_COURIER_SETTINGS.'">Settings</a>'; 
//   array_unshift($links, $settings_link); 
//   return $links; 
// } 
// add_filter("plugin_action_links_$plugin", 'mos_courier_settings_link' );


add_filter( 'page_template', 'mos_courier_page_template' );
function mos_courier_page_template( $page_template ) {
	if ( is_page( 'admin' ) ) {
		$page_template = dirname( __FILE__ ) . '/page-admin.php';
	}
	if ( is_page( 'register' ) ) {
		$page_template = dirname( __FILE__ ) . '/page-register.php';
	}
	if ( is_page( 'invoice-print' ) ) {
		$page_template = dirname( __FILE__ ) . '/page-invoice-print.php';
	}
	if ( is_page( 'delivery-print' ) ) {
		$page_template = dirname( __FILE__ ) . '/page-delivery-print.php';
	}
	if ( is_page( 'bill-print' ) ) {
		$page_template = dirname( __FILE__ ) . '/page-bill-print.php';
	}
	if ( is_page( 'checkin-print' ) ) {
		$page_template = dirname( __FILE__ ) . '/page-checkin-print.php';
	}
	return $page_template;
}
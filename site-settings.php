<?php
/*
Plugin Name: Sites Settings
Plugin URI: 
Description: Sites Info Settings
Version: 1.0.0
Author: Coderstime
License: GPLv2 or later
Text Domain: sitesettings

 */

// oge_manage
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}


class CTSiteSettings {

	public function __construct ( ) {
		add_action( "admin_menu", [ $this, "ctss_admin_page" ] );	
		add_action('admin_enqueue_scripts', [$this,'ctss_scripts'] );
	}

	public function ctss_admin_page ( ) {

		/*create submenu under settings Menu*/
		add_submenu_page(
			'options-general.php', 
			__( 'Sites Info', 'sesender' ),
			__( 'Sites Info', 'sesender' ),
			'administrator', 
			'sites-info',
			[ $this, 'ctss_display_settings_info']
		);
	}

	public function ctss_scripts( $hook ) {

        if ('settings_page_sites-info' == $hook) {

            $asset_file_link = plugins_url( '', __FILE__ );
            $folder_path= __DIR__ ;

            wp_enqueue_style('select2', $asset_file_link . '/../woocommerce/assets/css/select2.css', array(), filemtime($folder_path.'/../woocommerce/assets/css/select2.css'));
            wp_enqueue_style('ctss-style', $asset_file_link . '/assets/css/style.css', array(), filemtime($folder_path.'/assets/css/style.css'));            
            wp_enqueue_script('select2', $asset_file_link . '/../woocommerce/assets/js/select2/select2.js', array('jquery'), filemtime($folder_path.'/../woocommerce/assets/js/select2/select2.full.js'), true);
            wp_enqueue_script('ctss-script', $asset_file_link . '/assets/js/ctss.js', array('jquery', 'thickbox'), filemtime($folder_path.'/assets/js/ctss.js'), true);

        }
    }
	

	public function ctss_display_settings_info ( ) {
		include('settings-form.php');
	}


}

new CTSiteSettings;
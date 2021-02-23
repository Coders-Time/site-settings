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

	public function ctss_display_settings_info ( ) {
		echo 'something';
	}

}

new CTSiteSettings;
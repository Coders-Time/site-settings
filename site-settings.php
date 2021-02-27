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
		add_action('admin_post_ctss_form', [$this,'ctss_form_submit'] );
		add_action('ss_site_name', [$this,'ss_site_name'] );
		add_action('ss_site_copyright', [$this,'ss_site_copyright'] );
	}

	public function ss_site_name ( ) {
		echo get_option('blogname');
	}

	public function ss_site_copyright ( ) {
		echo get_option('site_copyright');
	}

	public function ctss_form_submit ( ) {

		if (isset($_POST['submit']) ) {

			if ( wp_verify_nonce(sanitize_text_field($_POST['ctss_form_nonce']), 'ctss_form'  ) ) {
				$response = [];

				$site_logo = trim(sanitize_text_field($_POST['site_logo']));
				$blogname = trim(sanitize_text_field($_POST['blogname']));
				$blogdescription = trim(sanitize_text_field($_POST['blogdescription']));
				$site_email = trim(sanitize_text_field($_POST['site_email']));
				$site_phone = trim(sanitize_text_field($_POST['site_phone']));
				$site_address = trim(sanitize_text_field($_POST['site_address']));
				$site_copyright = trim(sanitize_text_field($_POST['site_copyright']));
				$tags = maybe_serialize($_POST['tags']);

				if ( get_option('product_tags') != $tags ) {
					update_option( 'product_tags', $tags );
					$response['msg'] = 'Site tags updated';
				}

				if ( strlen($site_logo) > 0 && get_option('site_logo') != $site_logo ) {
					update_option( 'site_logo', $site_logo );
					$response['msg'] = 'Site logo updated';
				}

				if ( strlen($blogname) > 1 && get_option('blogname') != $blogname ) {
					update_option( 'blogname', $blogname );
					$response['msg'] = 'Site title updated';
				}
				
				if ( strlen($blogdescription) > 2 && get_option('blogdescription') != $blogdescription ) {
					update_option( 'blogdescription', $blogdescription );
					$response['msg'] = 'Site tagline updated';
				}
				
				if ( strlen( $site_email) > 3 && get_option('site_email') != $site_email ) {
					update_option( 'site_email', $site_email );
					$response['msg'] = 'Site Email updated';
				}
				
				if ( strlen($site_phone) > 2 && get_option('site_phone') != $site_phone ) {
					update_option( 'site_phone', $site_phone );
					$response['msg'] = 'Site Phone updated';
				}
				
				if ( strlen($site_address) > 2 && get_option('site_address') != $site_address ) {
					update_option( 'site_address', $site_address );
					$response['msg'] = 'Site Address updated';
				}
				
				if ( strlen($site_copyright) > 2 && get_option('site_copyright') != $site_copyright ) {
					update_option( 'site_copyright', $site_copyright );
					$response['msg'] = 'Site Copyright info updated';
				}



			}
            
            wp_safe_redirect(
                esc_url_raw(
                    add_query_arg('msg', $response['msg'], admin_url('admin.php?page=sites-info'))
                )
            );
        }
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

            wp_enqueue_style('bootstrap-min-css-style', $asset_file_link .'/../woocommerce/assets/css/bootstrap.min.css', [], '4.5.3');
            wp_enqueue_style('select2', $asset_file_link . '/../woocommerce/assets/css/select2.css',[]);
            wp_enqueue_style('ctss', $asset_file_link . '/assets/css/style.css', array(), filemtime($folder_path.'/assets/css/style.css'));            
            wp_enqueue_script('select2', $asset_file_link . '/../woocommerce/assets/js/select2/select2.js', array('jquery'));
            wp_enqueue_media(); /*media upload*/
            wp_enqueue_script('ctss', $asset_file_link . '/assets/js/ctss.js', array('jquery', 'thickbox'), filemtime($folder_path.'/assets/js/ctss.js'), true);
            add_thickbox();

        }
    }
	

	public function ctss_display_settings_info ( ) {
		$post_tags = get_tags(['hide_empty' => false]); 
		$product_tags = get_terms( 'product_tag'); 
		$tags= maybe_unserialize(get_option('product_tags'));
		$tags_name = $this->tags_name_by_id($tags);

		include('settings-form.php');
	}

	public function tags_name_by_id ( $tags, $taxonomy='product_tag' ) {
		return array_map(function($tag){global $taxonomy;return get_term( $tag, $taxonomy )->name;}, $tags);
	}


}

new CTSiteSettings;
<?php
/*
Plugin Name: Sites Settings
Plugin URI: https://github.com/Coders-Time/site-settings
Description: Sites Info Settings
Version: 1.0.0
Author: Coderstime
License: GPLv2 or later
Text Domain: sitesettings

 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}


class CTSiteSettings {

	public array $response = [];

	public function __construct ( ) {
		add_action( "admin_menu", [ $this, "ctss_admin_page" ] );	
		add_action('admin_enqueue_scripts', [$this,'ctss_scripts'] );
		add_action('admin_post_ctss_form', [$this,'ctss_form_submit'] );
		add_action('ss_site_name', [$this,'ss_site_name'] );
		add_action('ss_site_copyright', [$this,'ss_site_copyright'] );
		add_action('ctss_processing_complete', [$this,'ctss_processing_complete'] );
	}

	public function ctss_processing_complete ( $response ) {
		$msgs = explode('-',$response);
		if (count($msgs)>0) {
			foreach ($msgs as $msg) {
				$res_msg = get_transient("ss_" . $msg);
				$message =  __("<p class='success_msg text-success text-center'> %s </p>", 'sitesettings');
        		printf($message, $res_msg );
			}
		}
        
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
				$site_logo = trim(sanitize_text_field($_POST['site_logo']));
				$blogname = trim(sanitize_text_field($_POST['blogname']));
				$blogdescription = trim(sanitize_text_field($_POST['blogdescription']));
				$site_email = trim(sanitize_email($_POST['site_email']));

				if ( class_exists( 'WooCommerce' ) ) {
				  $site_phone = trim(wc_sanitize_phone_number($_POST['site_phone']));
				} else {
				  $site_phone = trim(sanitize_text_field($_POST['site_phone']));
				}
				
				$site_address = trim(sanitize_text_field($_POST['site_address']));
				$site_copyright = trim(sanitize_text_field($_POST['site_copyright']));
				$tags = maybe_serialize($_POST['tags']);

				if ( get_option('product_tags') != $tags ) {
					update_option( 'product_tags', $tags );
					set_transient("ss_tag", 'Site tags updated', 500);
					$this->response[] = 'tag';
				}

				if ( strlen($site_logo) > 0 && get_option('site_logo') != $site_logo ) {
					update_option( 'site_logo', $site_logo );
					$this->response[] = 'logo';
					set_transient("ss_logo", 'Site logo updated', 500);
				}

				if ( strlen($blogname) > 1 && get_option('blogname') != $blogname ) {
					update_option( 'blogname', $blogname );
					$this->response[] = 'title';
					set_transient("ss_title", 'Site title updated', 500);
				}
				
				if ( strlen($blogdescription) > 2 && get_option('blogdescription') != $blogdescription ) {
					update_option( 'blogdescription', $blogdescription );
					$this->response[] = 'tagline';
					set_transient("ss_tagline", 'Site tagline updated', 500);
				}
				
				if ( strlen( $site_email) > 3 && get_option('site_email') != $site_email ) {
					update_option( 'site_email', $site_email );
					$this->response[] = 'email';
					set_transient("ss_email", 'Site email updated', 500);
				}
				
				if ( strlen($site_phone) > 2 && get_option('site_phone') != $site_phone ) {
					update_option( 'site_phone', $site_phone );
					$this->response[] = 'phone';
					set_transient("ss_phone", 'Site phone updated', 500);
				}
				
				if ( strlen($site_address) > 2 && get_option('site_address') != $site_address ) {
					update_option( 'site_address', $site_address );
					$this->response[] = 'address';
					set_transient("ss_address", 'Site Address updated', 500);
				}
				
				if ( strlen($site_copyright) > 2 && get_option('site_copyright') != $site_copyright ) {
					update_option( 'site_copyright', $site_copyright );
					$this->response[] = 'copyright';
					set_transient("ss_copyright", 'Site copyright updated', 500);
				}

			}
            
            wp_safe_redirect(
                esc_url_raw(
                    add_query_arg('msg', implode('-',$this->response), admin_url('admin.php?page=sites-info'))
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

            wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css', [], '4.5.3');
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
		if ($tags) {
			$tags_name = $this->tags_name_by_id($tags);
		}
		

		include('settings-form.php');
	}

	public function tags_name_by_id ( $tags, $taxonomy='product_tag' ) {
		return array_map(function($tag){global $taxonomy;return get_term( $tag, $taxonomy )->name;}, $tags);
	}


}

new CTSiteSettings;
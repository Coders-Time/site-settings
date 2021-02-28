<?php
/*
Plugin Name: Sites Settings
Plugin URI: https://github.com/Coders-Time/site-settings
Description: A simple and nice plugin to set and update your site basic settings by admin on dashboard settings menu
Version: 1.0.0
Author: Coderstime
Author URI: https://profiles.wordpress.org/coderstime/
License: GPLv2 or later
Text Domain: sitesettings

 */

defined( 'ABSPATH' ) || exit;

class CTSiteSettings {

	public function __construct ( ) {
		add_action( "admin_menu", [ $this, "ctss_admin_page" ] );	
		add_action('admin_enqueue_scripts', [$this,'ctss_scripts'] );
		add_action('admin_post_ctss_form', [$this,'ctss_form_submit'] );
		add_action('ss_show', [$this,'ss_site_settings_info_show'] );
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

	public function ss_site_settings_info_show ( $key ) {
		if ($key=='product_tags') {
			$tags= maybe_unserialize(get_option($key));
			if ($tags) {
				$tag_names = $this->tags_name_by_id($tags);
				echo implode(', ', $tag_names);
			}
		}else {
			echo get_option( $key );
		}
		
	}

	public function ss_site_copyright ( $key ) {
		echo get_option( $key );
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

				$site_facebook = esc_url($_POST['site_facebook']);
				$site_twitter = esc_url($_POST['site_twitter']);
				$site_instagram = esc_url($_POST['site_instagram']);
				$site_youtube = esc_url($_POST['site_youtube']);

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
				
				$this->save_option_table('blogdescription',$blogdescription);
				$this->save_option_table('site_email',$site_email);
				$this->save_option_table('site_phone',$site_phone);
				$this->save_option_table('site_address',$site_address);
				$this->save_option_table('site_copyright',$site_copyright);
				$this->save_option_table('site_facebook',$site_facebook);
				$this->save_option_table('site_twitter',$site_twitter);
				$this->save_option_table('site_instagram',$site_instagram);
				$this->save_option_table('site_youtube',$site_youtube);
			}
            
            wp_safe_redirect(
                esc_url_raw(
                    add_query_arg('msg', implode('-',$this->response), admin_url('admin.php?page=site-settings'))
                )
            );
        }
	}

	public function save_option_table ( $field, $value ){
		if ( strlen($value) > 2 && get_option($field) != $value ) {
			update_option( $field, $value );
			$this->response[] = $field;
			set_transient("ss_" . $field, 'Site '.ucfirst(str_replace('_', ' ', $field)).' updated', 200);
		}
	}

	public function ctss_admin_page ( ) {

		/*create submenu under settings Menu*/
		add_submenu_page(
			'options-general.php', 
			__( 'Site Settings', 'sesender' ),
			__( 'Site Settings', 'sesender' ),
			'administrator', 
			'site-settings',
			[ $this, 'ctss_display_settings_info']
		);
	}

	public function ctss_scripts( $hook ) {

        if ('settings_page_site-settings' == $hook) {

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
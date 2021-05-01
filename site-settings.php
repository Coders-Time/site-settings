<?php
/*
	Plugin Name: Sites Settings
	Plugin URI: https://github.com/Coders-Time/site-settings
	Description: A simple and nice plugin to set and update your site basic settings by admin on dashboard settings menu
	Version: 1.1.0
	Author: Coderstime
	Author URI: https://profiles.wordpress.org/coderstime/
	Domain Path: /languages
	License: GPLv2 or later
	Text Domain: sitesettings
 */


defined( 'ABSPATH' ) || exit;

define( 'WP_SS_FILE', __FILE__ );
define( 'WP_SS_ASSET_FILE', plugins_url( '/assets/', WP_SS_FILE ) );

class CTSiteSettings {

	public function __construct ( ) 
	{
        register_activation_hook ( WP_SS_FILE, [ $this, 'activation'] );
        register_deactivation_hook( WP_SS_FILE, [ $this, 'deactivate'] );

        /*Localize our plugin*/
        add_action( "plugins_loaded", [ $this,'ctss_load_textdomain'] );

		add_action( "admin_menu", [ $this, "ctss_admin_page" ] );	
		add_action('admin_enqueue_scripts', [$this,'ctss_scripts'] );
		add_action('admin_post_ctss_form', [$this,'ctss_form_submit'] );
		add_action('ctss_processing_complete', [$this,'ctss_processing_complete'] );
		
		add_shortcode( 'ss_option', [$this,'sitesettings_show_func'] );
		/*settings link on plugin section*/
		add_filter( 'plugin_action_links_' . plugin_basename( WP_SS_FILE ), [ $this, 'action_links' ] );

	}

	public function ctss_load_textdomain ( ) {
	    load_plugin_textdomain( 'sitesettings', false, dirname( WP_SS_FILE ) . "/languages" );
	}

	public function ctss_processing_complete ( $response ) {
		$msgs = explode('-',$response);
		if ( count( $msgs )>0 ) {
			foreach ( $msgs as $msg ) {
        		printf('<p class="success_msg">Copy and paste this code in PHP block to show %1$s</p><pre><code>echo do_shortcode("[ss_option]%1$s[/ss_option]");</code></pre>',$msg);
			}
		}        
	}


	public function sitesettings_show_func( $atts, $key = "" ) {

	    switch ( trim($key) ) {
			case 'site_tags':
				$tags= get_option($key);
				if ($tags && (!isset($atts['link']) || $atts['link'] != 'true'  )  ) {
					$tag_names = $this->tags_name_by_id($tags);
					return implode(', ', $tag_names);
				}
				if ( isset($atts['link']) && $atts['link'] == 'true' && $tags ) {
					$tag_names = $this->tags_name_link_by_id( $tags );
					return implode(PHP_EOL, $tag_names);
				}
				break;
			case 'product_tags':
				$tags= get_option($key);
				if ($tags) {
					$tag_names = $this->tags_name_by_id($tags);
					return implode(', ', $tag_names);
				}
				break;
			case 'site_logo': /*logo image key*/
				$site_logo = get_option('site_logo'); /*get logo value from option table*/

				$size = 'full';	/*logo default size*/		
				if ( isset($atts) && isset( $atts['size']) ) {
					$size = $atts['size']; /*get user defined size*/
				}

				if ( $site_logo ) {
					return wp_get_attachment_image_src( $site_logo, $size )[0];
				} else {
					return get_option('blogname');
				}
				break;		
			default:
				return get_option( $key );
				break;
		}
		return false;	
	}

	public function ctss_form_submit ( ) {

		if (isset($_POST['submit']) ) {

			if ( wp_verify_nonce(sanitize_text_field($_POST['ctss_form_nonce']), 'ctss_form'  ) ) {

				$site_logo = sanitize_text_field($_POST['site_logo']);
				$blogname = sanitize_text_field($_POST['blogname']);
				$blogdescription = trim(sanitize_text_field($_POST['blogdescription']));
				$site_email = trim(sanitize_email($_POST['site_email']));

				if ( class_exists( 'WooCommerce' ) ) {
				  $site_phone = trim(wc_sanitize_phone_number($_POST['site_phone']));
				} else {
				  $site_phone = trim(sanitize_text_field($_POST['site_phone']));
				}
				
				$site_address = trim(sanitize_text_field($_POST['site_address']));
				$site_copyright = trim(sanitize_text_field($_POST['site_copyright']));
				$site_facebook = esc_url($_POST['site_facebook']);
				$site_twitter = esc_url($_POST['site_twitter']);
				$site_instagram = esc_url($_POST['site_instagram']);
				$site_youtube = esc_url($_POST['site_youtube']);

				$tags = array_map( 'esc_attr', isset( $_POST['tags'] ) ? (array) $_POST['tags'] : [] );

				if ( null !== get_option('site_tags') && array_diff($tags, get_option('site_tags') ) ) {
					update_option( 'site_tags', $tags );
					set_transient("ss_tag", 'Site tags updated', 500);
					$this->response[] = 'site_tags';
				}

				if ( false === get_option('site_tags') ) {
					add_option( 'site_tags', $tags );
				}

				if ( strlen($site_logo) > 0 && get_option('site_logo') != $site_logo ) {
					update_option( 'site_logo', $site_logo );
					$this->response[] = 'site_logo';
					set_transient("ss_logo", 'Site logo media id updated', 500);
				}

				if ( strlen($blogname) > 1 && get_option('blogname') != $blogname ) {
					update_option( 'blogname', $blogname );
					$this->response[] = 'blogname';
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

            $folder_path= __DIR__ ;

            wp_enqueue_style('bootstrap', WP_SS_ASSET_FILE . 'css/bootstrap.min.css', [], '5.0.0');
            wp_enqueue_style('select2', WP_SS_ASSET_FILE . 'css/select2.css',[]);
            wp_enqueue_style('ctss', WP_SS_ASSET_FILE . 'css/style.css', array(), filemtime($folder_path.'/assets/css/style.css'));            
            wp_enqueue_script('select2', WP_SS_ASSET_FILE . 'js/select2.js', array('jquery'));
            wp_enqueue_media(); /*media upload*/
            wp_enqueue_script('ctss', WP_SS_ASSET_FILE . 'js/ctss.js', array('jquery', 'thickbox'), filemtime($folder_path.'/assets/js/ctss.js'), true);
            add_thickbox();
        }
    }
	

	public function ctss_display_settings_info ( ) {
		$post_tags = get_tags(['hide_empty' => false]); 
		$product_tags = get_terms( 'product_tag'); 
		$tags= get_option('site_tags');
		
		include('settings-form.php');
	}

	public function tags_name_by_id ( $tags, $taxonomy='product_tag' ) {
		return array_map(function($tag){global $taxonomy;return get_term( $tag, $taxonomy )->name;}, $tags);
	}

	public function tags_name_link_by_id ( $tags, $taxonomy='product_tag' ) {
		return array_map(function( $tag ){
			global $taxonomy;
			return '<li><a href="' . get_tag_link( $tag ) . '">' . get_term( $tag, $taxonomy )->name . '</a></li>';
		}, $tags);
	}

	/**
     *
     * run when plugin install
     * install time store on option table
     */
    
    public function activation ( ) 
    {
    	if ( version_compare( get_bloginfo( 'version' ), '4.9', ' < ' ) ) {
            deactivate_plugins( basename( __FILE__ ) );
        }

        if ( false === get_option('site_logo') ) {
            $default_bg_img = WP_SS_ASSET_FILE . 'images/logo.png';
            $default_bg_img_id = $this->upload_image_file( $default_bg_img );
            add_option( 'site_logo', $default_bg_img_id); 
        }

        if ( false === get_option('site_email') ) {
            add_option( 'site_email', 'contact@' . $_SERVER['SERVER_NAME'] ); 
        }

        if ( false === get_option('site_phone') ) {
        	$default_phone = get_user_meta( get_current_user_id(), 'billing_phone', true);
            add_option( 'site_phone', $default_phone ); 
        }

        if ( false === get_option('site_facebook') ) {
            add_option( 'site_facebook', 'https://facebook.com'); 
        }

        if ( false === get_option('site_twitter') ) {
            add_option( 'site_twitter', 'https://twitter.com'); 
        }  

        if ( false === get_option('site_instagram') ) {
            add_option( 'site_instagram', 'https://instagram.com'); 
        }

        if ( false === get_option('site_youtube') ) {
            add_option( 'site_youtube', 'https://youtube.com'); 
        }

        if ( false === get_option('site_tags') ) {
            add_option( 'site_tags', []); 
        }

        if ( false === get_option('sitesettings_active') ) {
        	add_option('sitesettings_active', [time(),'1.1.0']);    
        }       
    }

    /**
     *
     * run when deactivate the plugin
     * store deactivate time on database option table
     */

    public function deactivate ( ) 
    {
        update_option('sitesettings_deactive', time());

        // if ( get_option('site_logo') ) {
        //     delete_option( 'site_logo' ); 
        // }

        // if ( get_option('site_tags') ) {
        //     delete_option( 'site_tags' ); 
        // }

        // if ( get_option('site_email') ) {
        //     delete_option( 'site_email' ); 
        // }

        // if ( get_option('site_phone') ) {
        //     delete_option( 'site_phone' ); 
        // }

        // if ( get_option('site_address') ) {
        //     delete_option( 'site_address' ); 
        // }

        // if ( get_option('site_copyright') ) {
        //     delete_option( 'site_copyright' ); 
        // }

        // if ( get_option('site_facebook') ) {
        //     delete_option( 'site_facebook' ); 
        // }

        // if ( get_option('site_twitter') ) {
        //     delete_option( 'site_twitter' ); 
        // }

        // if ( get_option('site_instagram') ) {
        //     delete_option( 'site_instagram' ); 
        // }

        // if ( get_option('site_youtube') ) {
        //     delete_option( 'site_youtube' ); 
        // }

    }

    /*Upload image file from local path*/

    public function upload_image_file ( $image_url ) {

        $upload_dir = wp_upload_dir();
        $image_data = file_get_contents( $image_url );

        $filename = basename( $image_url );

        if ( wp_mkdir_p( $upload_dir['path'] ) ) {
          $file = $upload_dir['path'] . '/' . $filename;
        } else {
          $file = $upload_dir['basedir'] . '/' . $filename;
        }

        file_put_contents( $file, $image_data );

        $wp_filetype = wp_check_filetype( $filename, null );

        $attachment = array(
          'post_mime_type' => $wp_filetype['type'],
          'post_title' => sanitize_file_name( $filename ),
          'post_content' => 'Site Logo',
          'post_status' => 'inherit'
        );

        $attach_id = wp_insert_attachment( $attachment, $file );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
        wp_update_attachment_metadata( $attach_id, $attach_data );
        
        return $attach_id;
    }

    /**
     * Show action links on the plugin screen
     *
     * @param mixed $links
     * @return array
     */
    public function action_links( $links ) {
        return array_merge(
            [
                '<a href="' . admin_url( 'options-general.php?page=site-settings' ) . '">' . __( 'Settings', 'sitesettings' ) . '</a>',
                '<a href="' . esc_url( 'https://wordpress.org/plugins/sites-settings/#description' ) . '">' . __( 'Documentation', 'sitesettings' ) . '</a>',
                '<a href="' . esc_url( 'https://wordpress.org/support/plugin/site-settings/reviews/#new-post' ) . '">' . __( 'Review', 'sitesettings' ) . '</a>',
            ], $links );
    }


}

new CTSiteSettings;

// add_action( 'plugins_loaded', function(){ } );



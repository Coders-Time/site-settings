<?php
    defined( 'ABSPATH' ) || exit;
    $test_tags = get_tags(['hide_empty' => false,'term_id']);

?>

<div class="wrap">
    <div class="container">
        <div class="row">
            <div class="col-sm m-auto">

                <div class="col-sm-12 col-md-8 m-auto my-5 text-center">
                    <h2 class="site_title"><?php _e('Site Settings Panel', 'ctss'); ?></h2>
                    <hr>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-8 m-auto">
                        <form action='<?php echo esc_url(admin_url('admin-post.php')); ?>' method='POST'>
                            <div class="row g-3 mb-3">
                                <?php $label = __('Site logo', 'ctss'); ?>
                                <label for='site_logo'><?php echo esc_html($label); ?> </label>
                                <div class="col site_logo_preview">
                                    <?php if ( $img_id= get_option('site_logo')) {
                                        $image_url = wp_get_attachment_image_src( $img_id, 'thumbnail');
                                        printf('<img class="preview" src=%s />',esc_url($image_url[0]));
                                    }?>
                                </div>
                                <div class="col">
                                <button id="site_logo" type="button" class="btn btn-secondary btn-lg btn-block"> <?php echo get_option('site_logo') ? 'Change Image' : 'Select Image'; ?> </button>
                                    <input type="hidden" id="site_logo_val" name="site_logo">
                                </div>    
                                <small>
                                    Shortcode <code>[ss_option size="full"]site_logo[/ss_option]</code>
                                </small>                          
                            </div>

                            <div class="row m-auto logo_file"></div>


                            <div class="mb-3 mt-5">
                                <?php $label = __('Site Title', 'ctss'); ?>
                                <label for="site_title" class="form-label"><?php echo esc_html($label); ?></label>
                                <input type="text" name='blogname' class="form-control" id="site_title" aria-describedby="nameHelp" value='<?php echo esc_html(get_option('blogname')); ?>'>
                                <div id="nameHelp" class="form-text"><?php echo esc_html(__('Your website title here', 'ctss')); ?></div>
                                <small>
                                    Shortcode <code>[ss_option]blogname[/ss_option]</code>
                                </small> 
                            </div>

                            <div class="mb-3">
                                <?php $label = __('Site Description', 'ctss'); ?>
                                <label for="site_tagline" class="form-label"><?php echo esc_html($label); ?></label>
                                <input type="text" name='blogdescription' class="form-control" id="site_tagline" aria-describedby="descriptionHelp" value='<?php echo get_option('blogdescription'); ?>'>
                                <div id="descriptionHelp" class="form-text"><?php echo esc_html(__('Your Site description here', 'ctss')); ?></div>
                                <small>
                                    Shortcode <code>[ss_option]blogdescription[/ss_option]</code>
                                </small> 
                            </div>

                            <div class="mb-3">
                                <?php $label = __('Site Email', 'ctss'); ?>
                                <label for="site_email" class="form-label"><?php echo esc_html($label); ?></label>
                                <input type="email" name='site_email' class="form-control" id="site_email" aria-describedby="emailHelp" value='<?php echo get_option('site_email'); ?>'>
                                <div id="emailHelp" class="form-text"><?php echo esc_html(__('Your Site Email here', 'ctss')); ?></div>
                                <small>
                                    Shortcode <code>[ss_option]site_email[/ss_option]</code>
                                </small>
                            </div>

                            <div class="mb-3">
                                <?php $label = __('Site Phone', 'ctss'); ?>
                                <label for="site_phone" class="form-label"><?php echo esc_html($label); ?></label>
                                <input type="tel" name='site_phone' class="form-control" id="site_phone" aria-describedby="phoneHelp" value='<?php echo esc_html(get_option('site_phone')); ?>'>
                                <div id="phoneHelp" class="form-text"><?php echo esc_html(__('Your Site Phone number here', 'ctss')); ?></div>
                                <small>
                                    Shortcode <code>[ss_option]site_phone[/ss_option]</code>
                                </small>
                            </div>

                            <div class="mb-3">
                                <?php $label = __('Site Address', 'ctss'); ?>
                                <label for="site_address" class="form-label"><?php echo esc_html($label); ?></label>
                                <input type="text" name='site_address' class="form-control" id="site_address" aria-describedby="addressHelp" value='<?php echo esc_html(get_option('site_address')); ?>'>
                                <div id="addressHelp" class="form-text"><?php echo esc_html(__('Your Site Address here', 'ctss')); ?></div>
                                <small>
                                    Shortcode <code>[ss_option]site_address[/ss_option]</code>
                                </small>
                            </div>

                            <div class="mb-3">
                                <?php $label = __('Site copyright text', 'ctss'); ?>
                                <label for="site_copyright" class="form-label"><?php echo esc_html($label); ?></label>
                                <input type="text" name='site_copyright' class="form-control" id="site_copyright" aria-describedby="copyrightHelp" value='<?php echo esc_html(get_option('site_copyright')); ?>'>
                                <div id="copyrightHelp" class="form-text"><?php echo esc_html(__('Your Site copyright information here', 'ctss')); ?></div>
                                <small>
                                    Shortcode <code>[ss_option]site_copyright[/ss_option]</code>
                                </small>
                            </div>

                            <div class="mb-1">
                                <?php $label = __('Site Tag List', 'ctss'); ?>
                                <label for='tag_name' class="form-label"><?php echo esc_html($label); ?></label>
                            </div>

                            <div class='mb-3'>
                                <select class='form-select form-control' multiple name='tags[]' id='tag_name' aria-label="Default select example">
                                    <optgroup label="<?php echo get_post_type_object( 'product' )->labels->singular_name;?> tags">
                                        <?php  if ( count($product_tags)>0 ) {
                                            foreach ( $product_tags as $key => $tag ) {
                                                if ( in_array($tag->term_id,$tags)) {
                                                    printf('<option value="%d" selected>%s</option>',$tag->term_id,$tag->name);
                                                } else {
                                                    printf('<option value="%d">%s</option>',$tag->term_id,$tag->name);
                                                }                                                
                                            }
                                        } ?>
                                    </optgroup>  
                                    <optgroup label="<?php echo get_post_type_object( 'post' )->labels->singular_name;?> tags">
                                        <?php  if ( count($post_tags)>0 ) {
                                            foreach ( $post_tags as $key => $tag ) {
                                                if ( in_array($tag->term_id,$tags)) {
                                                    printf('<option value="%d" selected>%s</option>',$tag->term_id,$tag->name);
                                                } else {
                                                    printf('<option value="%d">%s</option>',$tag->term_id,$tag->name);
                                                }                                                
                                            }
                                        } ?>
                                    </optgroup>                                    
                                </select>

                                <small>
                                    Shortcode <code>[ss_option link='true']site_tags[/ss_option]</code>
                                </small>

                            </div>

                            <div class="mb-3">
                                <?php $label = __('Site Facebook link', 'ctss'); ?>
                                <label for="site_facebook" class="form-label"><?php echo esc_html($label); ?></label>
                                <input type="url" name='site_facebook' class="form-control" id="site_facebook" aria-describedby="facebookHelp" value='<?php echo esc_url(get_option('site_facebook')); ?>'>
                                <div id="facebookHelp" class="form-text"><?php echo esc_html(__('Your Site facebook page link here', 'ctss')); ?></div>

                                <small>
                                    Shortcode <code>[ss_option]site_facebook[/ss_option]</code>
                                </small>

                            </div>

                            <div class="mb-3">
                                <?php $label = __('Site Twitter link', 'ctss'); ?>
                                <label for="site_twitter" class="form-label"><?php echo esc_html($label); ?></label>
                                <input type="url" name='site_twitter' class="form-control" id="site_twitter" aria-describedby="twitterHelp" value='<?php echo esc_url(get_option('site_twitter')); ?>'>
                                <div id="twitterHelp" class="form-text"><?php echo esc_html(__('Your Site twitter page link here', 'ctss')); ?></div>
                                <small>
                                    Shortcode <code>[ss_option]site_twitter[/ss_option]</code>
                                </small>
                            </div>

                            <div class="mb-3">
                                <?php $label = __('Site Instagram link', 'ctss'); ?>
                                <label for="site_instagram" class="form-label"><?php echo esc_html($label); ?></label>
                                <input type="url" name='site_instagram' class="form-control" id="site_instagram" aria-describedby="instagramHelp" value='<?php echo esc_url(get_option('site_instagram')); ?>'>
                                <div id="instagramHelp" class="form-text"><?php echo esc_html(__('Your Site instagram page link here', 'ctss')); ?></div>
                                <small>
                                    Shortcode <code>[ss_option]site_instagram[/ss_option]</code>
                                </small>
                            </div>

                            <div class="mb-3">
                                <?php $label = __('Site Youtube link', 'ctss'); ?>
                                <label for="site_youtube" class="form-label"><?php echo esc_html($label); ?></label>
                                <input type="url" name='site_youtube' class="form-control" id="site_youtube" aria-describedby="youtubeHelp" value='<?php echo esc_url(get_option('site_youtube')); ?>'>
                                <div id="youtubeHelp" class="form-text"><?php echo esc_html(__('Your Site youtube channel link here', 'ctss')); ?></div>
                                <small>
                                    Shortcode <code>[ss_option]site_youtube[/ss_option]</code>
                                </small>
                            </div>

                                <div class='pure-control-group' style='margin:20px auto;width: fit-content;'>
                                    <button type='submit' name='submit' class='button button-primary button-hero' value="submit">
                                        <?php _e('Submit Settings', 'ctss'); ?>
                                    </button>
                                </div>

                            <input type="hidden" name="action" value="ctss_form">
                            <input type="hidden" name="ctss_identifier" value="<?php echo md5(time()); ?>">
                            <?php wp_nonce_field('ctss_form', 'ctss_form_nonce'); ?>
                        </form>                        
                    </div>
                </div>
                

                
            </div>
        </div>
    </div>


    

</div>

<div id="ctss-modal">
    <div class="ctss-modal-content">
        <?php
        if (isset($_GET['msg'])) {
            do_action('ctss_processing_complete', sanitize_text_field($_GET['msg']));
        }
        ?>
    </div>
</div>

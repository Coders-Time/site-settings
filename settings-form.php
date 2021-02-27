<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}
?>

<div class="ctss-form-wrapper">
    <div class="ctss-form-title">
        <h4 class="site_title"><?php _e('Site Settings Panel', 'ctss'); ?></h4>
    </div>
    <div class='ctss-form-container'>
        <div class="ctss-form">
            <form action='<?php echo esc_url(admin_url('admin-post.php')); ?>' class='pure-form pure-form-aligned' method='POST'>
                <fieldset>
                    <!-- <input type='hidden' name='customer_id' id='customer_id' value='0'> -->
                    <div class="row pure-control-group">
                            <?php $label = __('Site logo', 'ctss'); ?>
                            <label for='site_logo'><?php echo $label; ?></label>
                        <div class='pure-control-group logo_file col-lg-4'>
                            <?php if ( $img_id= get_option('site_logo')) {
                                $image_url = wp_get_attachment_image_src( $img_id, 'thumbnail');
                                printf('<img class="preview" src=%s />',$image_url[0]);
                            }?>                            
                        </div>
                        <div class="col-lg-4 mt-4">
                            <button id="site_logo" type="button" class="btn btn-secondary btn-lg btn-block"> <?=get_option('site_logo') ? 'Change Image' : 'Select Image'; ?> </button>
                            <input type="hidden" id="site_logo_val" name="site_logo">
                        </div>

                        <div style="clear:both"></div>
                    </div>

                    <div class='pure-control-group'>
                        <?php $label = __('Site Title', 'ctss'); ?>
                        <label for='site_title'><?php echo $label; ?></label>
                        <input class='ctss-control' name='blogname' id='site_title' type='text' value='<?php echo get_option('blogname'); ?>'>
                    </div>

                    <div class='pure-control-group'>
                        <?php $label = __('Site Tagline', 'ctss'); ?>
                        <label for='site_tagline'><?php echo $label; ?></label>
                        <input class='ctss-control' name='blogdescription' id='site_tagline' type='text' value='<?php echo get_option('blogdescription'); ?>'>
                    </div>

                    <div class='pure-control-group'>
                        <?php $label = __('Site Email', 'ctss'); ?>
                        <label for='site_email'><?php echo $label; ?></label>
                        <input class='ctss-control' name='site_email' id='site_email' type='text' value='<?php echo get_option('site_email'); ?>'>
                    </div>

                    <div class='pure-control-group'>
                        <?php $label = __('Site Phone', 'ctss'); ?>
                        <label for='site_phone'><?php echo $label; ?></label>
                        <input class='ctss-control' name='site_phone' id='site_phone' type='text' value='<?php echo get_option('site_phone'); ?>'>
                    </div>

                    <div class='pure-control-group'>
                        <?php $label = __('Site Address', 'ctss'); ?>
                        <label for='site_address'><?php echo $label; ?></label>
                        <input class='ctss-control' required name='site_address' id='site_address' type='text' value='<?php echo get_option('site_address'); ?>'>
                    </div>

                    <div class='pure-control-group'>
                        <?php $label = __('Site copyright text', 'ctss'); ?>
                        <label for='site_copyright'><?php echo $label; ?></label>
                        <input class='ctss-control' name='site_copyright' id='site_copyright' type='text' value='<?php echo get_option('site_copyright'); ?>'>
                    </div>

                    <div class='pure-control-group'>
                        <?php $label = __('Tag Name', 'ctss'); ?>
                        <label for='tag_name'><?php echo $label; ?></label>
                        <select class='ctss-control select_product' multiple name='tags[]' id='tag_name'>
                            <optgroup label="<?=get_post_type_object( 'product' )->labels->singular_name;?> tags">
                                <?php 
                                if (count($product_tags)>0) {
                                    foreach ($product_tags as $key => $tag) {
                                        if ( in_array($tag->term_id,$tags)) {
                                            printf('<option value="%d" selected>%s</option>',$tag->term_id,$tag->name);
                                        } else {
                                            printf('<option value="%d">%s</option>',$tag->term_id,$tag->name);
                                        }
                                        
                                    }
                                }
                                ?>
                            </optgroup>

                            
                        </select>
                    </div>

                    <div class='pure-control-group'>
                        <?php $label = __('Site Facebook link', 'ctss'); ?>
                        <label for='site_copyright'><?php echo $label; ?></label>
                        <input class='ctss-control' name='site_facebook' id='site_facebook' type='url' value='<?php echo get_option('site_facebook'); ?>'>
                    </div>

                    <div class='pure-control-group'>
                        <?php $label = __('Site Twitter link', 'ctss'); ?>
                        <label for='site_copyright'><?php echo $label; ?></label>
                        <input class='ctss-control' name='site_twitter' id='site_twitter' type='url' value='<?php echo get_option('site_twitter'); ?>'>
                    </div>

                    <div class='pure-control-group'>
                        <?php $label = __('Site Instagram link', 'ctss'); ?>
                        <label for='site_copyright'><?php echo $label; ?></label>
                        <input class='ctss-control' name='site_instagram' id='site_instagram' type='url' value='<?php echo get_option('site_instagram'); ?>'>
                    </div>

                    <div class='pure-control-group'>
                        <?php $label = __('Site Youtube link', 'ctss'); ?>
                        <label for='site_copyright'><?php echo $label; ?></label>
                        <input class='ctss-control' name='site_youtube' id='site_youtube' type='url' value='<?php echo get_option('site_youtube'); ?>'>
                    </div>

                    <div class='pure-control-group' style='margin:20px auto;width: fit-content;'>
                        <button type='submit' name='submit' class='button button-primary button-hero' value="submit">
                            <?php _e('Submit Settings', 'ctss'); ?>
                        </button>
                    </div>

                </fieldset>
                <input type="hidden" name="action" value="ctss_form">
                <input type="hidden" name="ctss_identifier" value="<?php echo md5(time()); ?>">
                <?php wp_nonce_field('ctss_form', 'ctss_form_nonce'); ?>
            </form>
        </div>
        <div class="ctss-info"> </div>
        <div class="ctss-clearfix"></div>
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

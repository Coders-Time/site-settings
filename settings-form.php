<div class="ctss-form-wrapper">
    <div class="ctss-form-title">
        <h4><?php _e('Woocommerce Settings Form', 'ctss'); ?></h4>
    </div>
    <div class='ctss-form-container'>
        <div class="ctss-form">
            <form action='<?php echo esc_url(admin_url('admin-post.php')); ?>' class='pure-form pure-form-aligned' method='POST'>
                <fieldset>
                    <!-- <input type='hidden' name='customer_id' id='customer_id' value='0'> -->
                    <div class='pure-control-group'>
                        <?php $label = __('Change Footer logo', 'ctss'); ?>
                        <label for='footer_logo'><?php echo $label; ?></label>
                        <input type="file" class="custom-file-input" id="customFile" name="footer_logo">
                        <label class="custom-file-label" for="customFile">Browse</label>
                    </div>

                    <div class='pure-control-group'>
                        <?php $label = __('Change Footer Title', 'ctss'); ?>
                        <label for='footer_title'><?php echo $label; ?></label>
                        <input class='ctss-control' required name='footer_title' id='footer_title' type='text' placeholder='<?php echo $label; ?>'>
                    </div>

                    <div class='pure-control-group'>
                        <?php $label = __('Change Footer Subtitle', 'ctss'); ?>
                        <label for='footer_subtitle'><?php echo $label; ?></label>
                        <input class='ctss-control' required name='footer_subtitle' id='footer_subtitle' type='text' placeholder='<?php echo $label; ?>'>
                    </div>

                    <div class='pure-control-group'>
                        <?php $label = __('Change Footer Address', 'ctss'); ?>
                        <label for='footer_address'><?php echo $label; ?></label>
                        <input class='ctss-control' required name='footer_address' id='footer_address' type='text' placeholder='<?php echo $label; ?>'>
                    </div>

                    <div class='pure-control-group'>
                        <?php $label = __('Change Footer Address 2 (optional)', 'ctss'); ?>
                        <label for='footer_address_2'><?php echo $label; ?></label>
                        <input class='ctss-control' required name='footer_address_2' id='footer_address_2' type='text' placeholder='<?php echo $label; ?>'>
                    </div>

                    <div class='pure-control-group'>
                        <?php $label = __('Phone Number', 'ctss'); ?>
                        <label for='phone'><?php echo $label; ?></label>
                        <input class='ctss-control' name='phone' id='phone' type='text' placeholder='<?php echo $label; ?>'>
                    </div>

                    <div class='pure-control-group'>
                        <?php $label = __('Change copyright option', 'ctss'); ?>
                        <label for='copyright_option'><?php echo $label; ?></label>
                        <input class='ctss-control' name='copyright_option' id='copyright_option' type='text' placeholder='<?php echo $label; ?>'>
                    </div>

                    <div class='pure-control-group'>
                        <?php $label = __('Tag Name', 'ctss'); ?>
                        <label for='tag_name'><?php echo $label; ?></label>
                        <select class='ctss-control select_product' multiple name='tag_name' id='tag_name'>
                            <option value="0"><?php _e('Select Tag', 'ctss'); ?></option>
                        </select>
                    </div>

                    <div class='pure-control-group' style='margin-top:20px;'>
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
        if (isset($_GET['order_id'])) {
            do_action('ctss_order_processing_complete', sanitize_text_field($_GET['order_id']));
        }
        ?>
    </div>
</div>
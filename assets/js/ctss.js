; (function ($) {
    $(document).ready(function () {
        $("#tag_name").select2({
            // tags:["red", "green", "blue"],
            maximumInputLength: 10
        });

        /*Media upload */
        var mediaUploader;
        $('#site_logo').on('click',function(e) {
            e.preventDefault();
            if( mediaUploader ){
                mediaUploader.open();
                return;
            }
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose a Image',
                button: {
                    text: 'Choose image'
                },
                multiple: false
            });
            
            mediaUploader.on('select', function(){
                attachment = mediaUploader.state().get('selection').first().toJSON();
                console.log(attachment);
                $('#site_logo_val').val(attachment.id);
                $('.site_logo_preview .preview').attr('src',attachment.url);
                $("#site_logo").hide();
                $('.logo_file').append(`<button class="notice notice-success is-dismissible col-md-12 mt-3">
                                <p> Logo uploaded Done!</p> </button>`);
            });
            mediaUploader.open();
        });
        /*Media upload */

        if ($('.ctss-modal-content .success_msg').length > 0) {
            tb_show($(".site_title").text(), "#TB_inline?inlineId=ctss-modal&width=700");
        }

        $( 'body' ).on( 'thickbox:removed', function() {
            var url = window.location.href;
            var a = url.indexOf("?");
            var b = url.substring(a);
            var c = url.replace(b,"?page=site-settings");
            window.history.pushState({}, document.title, c );
        });


    });
})(jQuery);
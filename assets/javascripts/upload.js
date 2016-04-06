jQuery(document).ready(function($){
    if($('.nk_upload_button').length >= 1) {
        window.azkaban_uploadfield = '';

        $('.nk_upload_button').live('click', function() {
            window.azkaban_uploadfield = $('.upload_field', $(this).parent());
            tb_show('Upload', 'media-upload.php?type=image&TB_iframe=true', false);

            return false;
        });

        window.azkaban_send_to_editor_backup = window.send_to_editor;
        window.send_to_editor = function(html) {
            if(window.azkaban_uploadfield) {
                if($('img', html).length >= 1) {
                    var image_url = $('img', html).attr('src');
                } else {
                    var image_url = $($(html)[0]).attr('href');
                }
                $(window.azkaban_uploadfield).val(image_url);
                window.azkaban_uploadfield = '';
                
                tb_remove();
            } else {
                window.azkaban_send_to_editor_backup(html);
            }
        }
    }
});
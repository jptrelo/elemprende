// JavaScript Document
jQuery(document).ready(function($){
  var photoshoot_custom_uploader;
 $('#upload_image_button_widget').click(function(e) {
 
        e.preventDefault();
 
        //If the uploader object has already been created, reopen the dialog
        if (photoshoot_custom_uploader) {
            photoshoot_custom_uploader.open();
            return;
        }
 
        //Extend the wp.media object
        photoshoot_custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        photoshoot_custom_uploader.on('select', function() {
            attachment = photoshoot_custom_uploader.state().get('selection').first().toJSON();
            $('#upload_image').val(attachment.id);
			$('#image').attr('src', attachment.url);
        });
 
        //Open the uploader dialog
        photoshoot_custom_uploader.open();
 
    }); 
 
});

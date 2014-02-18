jQuery(document).ready(function(){
	jQuery('.tdminimal-color-picker').wpColorPicker();
	
	jQuery('.post-background-image-add').click(function(e) {
		e.preventDefault();
		
		var send_attachment_bkp = wp.media.editor.send.attachment;
		
		var custom_uploader = wp.media({
			title: 'Post Background Image',
			button: {
				text: 'Set Background Image',
			},
			multiple: false 
		})
		.on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			jQuery('.post-background-image').attr('src', attachment.url);
			jQuery('.post-background-image-id').val(attachment.id).trigger('change');
			jQuery('.post-background-image-add').hide();
			jQuery('.post-background-image-remove').show();
		})
		.open();
	});
	
	jQuery('.post-background-image-remove').click(function(e) {
		e.preventDefault();
		jQuery(this).hide();
		jQuery('.post-background-image').attr('src', 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==');
		jQuery('.post-background-image-id').val('').trigger('change');
		jQuery('.post-background-image-add').show();
	});

}); 

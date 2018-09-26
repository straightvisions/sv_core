jQuery('body').on('click','.sv_setting_group_add_new_button', function(){
	var form_new	= jQuery(this).
	parent().
	children('.sv_setting_group_new_draft');

	var form_clone	= form_new.clone();

	form_clone.find('.sv_form_field').each(function(e) {
		jQuery(this).attr('name', jQuery(this).attr('id').replace("sv_form_field_index", form_new.closest('.sv_setting_group_wrapper').data('sv_form_field_index')));
		jQuery(this).attr('id', jQuery(this).attr('name'));
		jQuery(this).closest('label').attr('for', jQuery(this).attr('name'));
	});

	form_new.closest('.sv_setting_group_wrapper').data('sv_form_field_index', form_new.closest('.sv_setting_group_wrapper').data('sv_form_field_index')+1);
	form_clone.removeClass('sv_setting_group_new_draft').addClass('sv_setting_group');
	form_clone.appendTo('.sv_setting_group_new_entries');
	form_clone.show('slow');
});
jQuery('body').on('click', '.sv_setting_group_delete', function(){
	jQuery(this).parent().parent().hide('slow', function(){
		jQuery(this).remove();
	});
});
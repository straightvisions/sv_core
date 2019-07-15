jQuery( document ).ready( function() {
	jQuery( '.sv_setting_group_wrapper' ).parent( '.sv_setting' ).addClass( 'sv_setting_group_parent' );
});

jQuery( 'body' ).on( 'click','.sv_setting_group_add_new_button', function() {
	let parent 		= jQuery( this ).parents( '.sv_setting_group_parent' );
	var form_new	= parent.find( '.sv_setting_group_new_draft' );
	var form_clone	= form_new.clone();
	var index		= parent.find( '.sv_setting_group' ).length;

	form_clone.find('.data_sv_type_sv_form_field, [data-sv_type="sv_form_field"]').each(function(e) {
		let id = jQuery(this).attr('id').replace("sv_form_field_index", index );
		let name = jQuery(this).parents('.sv_setting_group_input').data('sv_input_name').replace("sv_form_field_index", index );

		if ( jQuery( this ).is( 'input[type="file"]' ) ) {
			name += '[file]';
		}

		jQuery( this ).attr( 'name', name );
		jQuery( this ).attr( 'id', id );
		jQuery( this ).closest( 'label' ).attr( 'for', id );
		jQuery( this ).next( 'label' ).attr( 'for', id );
	});

	form_clone.find( '.sv_setting_group_header h4' ).append(  parent.find( '.sv_setting_group_header' ).length );

	form_clone.removeClass( 'sv_setting_group_new_draft' ).addClass( 'sv_setting_group' );
	form_clone.appendTo( parent.find( '.sv_setting_group_wrapper' ) );

	form_clone.show('slow');

	update_option( jQuery( this ).parents( 'form' ) );
});

jQuery( 'body' ).on( 'click', '.sv_setting_group_delete', function() {
	jQuery( this ).parents( '.sv_setting_group' ).hide('slow', function() {
		var form = jQuery( this ).parents( 'form' );

		jQuery( this ).remove();
		update_option( form );
	});
});

jQuery( 'body' ).on('click', '.sv_setting_group_title', function() {
	jQuery( this ).find( '.fa-angle-right' ).toggleClass( 'open' );
	jQuery( this ).parents( '.sv_setting_group' ).find( '.sv_setting_group_settings_wrapper' ).slideToggle( 400 );
});
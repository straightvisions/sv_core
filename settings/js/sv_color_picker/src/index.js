import React from 'react';
import ReactDOM from 'react-dom';
import SVColorPicker from './SVColorPicker';
window.SVColorPicker = SVColorPicker;

// For Setting Groups
jQuery( 'body' ).on( 'click','.sv_setting_group_add_new_button', function() {
    const parent            = jQuery( this ).parents( '.sv_setting_group_parent' );
    const entries		    = parent.find( '.sv_setting_group' );
    const color_settings    = parent.find( '.sv_setting_group_new_draft input[data-sv_type="sv_form_field"][type="color"]' );
    
    // Checks all entries for the highest index and sets the next new index
	let index		        = 0;

	entries.each( function() {
		if ( parseInt( jQuery(this).attr('sv_setting_group_entry_id') ) > index ) {
			index = parseInt( jQuery(this).attr('sv_setting_group_entry_id') );
		}
    } );

    if ( color_settings && typeof sv_core_color_picker !== 'undefined' ) {
        color_settings.each( function() {
            const id = jQuery( this ).attr('id').replace("sv_form_field_index", index );
            const defaultColor = '0,0,0,1';
            const colorPalette = sv_core_color_picker.color_palette ? sv_core_color_picker.color_palette : false;

            SVColorPicker.renderColorPicker( id, defaultColor, true, colorPalette );
        });
    }
});

jQuery( document ).ready( function() {
    if ( SVColorPicker && typeof sv_core_color_picker !== 'undefined' ) {
        SVColorPicker.loadColorPicker( sv_core_color_picker );
    }
} );
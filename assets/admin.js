function sv_admin_load_page(target){
	if(jQuery(target).length) {
		jQuery('.sv_admin_menu_item.active').removeClass('active');
		jQuery('*[data-target="' + target + '"]').addClass('active');
		jQuery('.sv_admin_section').hide();
		jQuery(target).fadeIn();
		window.location.hash = target;
	}
}

jQuery(document).on('click', '.sv_admin_menu_item', function() {
	if(!jQuery(this).hasClass('active')) {
		if(jQuery(document).width() < 800) {
			jQuery(jQuery('.sv_admin_mobile_toggle').attr('data-target')).toggle();
		}
		sv_admin_load_page(jQuery(this).data('target'));
	}
});
jQuery(document).on('click', '.sv_admin_mobile_toggle', function() {
	jQuery(jQuery(this).attr('data-target')).toggle();
});
jQuery(document).ready(function(){
	sv_admin_load_page(window.location.hash);
});

/* Input - Radio checkbox style */
jQuery(document).on('click', '.sv_radio.checkbox', function() {

});

/* Description (Tooltip) */
jQuery(document).on('click', '.sv_tooltip', function() {
	jQuery(this).next().toggleClass('open');
});

/* Module: Log */

/* Select All */
jQuery( document ).on( 'click', 'div.log_list input[type="checkbox"]#logs_select', function() {
	jQuery( 'div.log_list input[type="checkbox"]' ).prop( 'checked', this.checked );
});

/* Select Log */
jQuery( document ).on( 'click', '.log_list input[type="checkbox"]', function() {
	if ( jQuery( '.log_list input[type="checkbox"]:checked:not(#logs_select)' ).length > 0) {
		jQuery( '.log_list #logs_delete' ).css( 'visibility', 'visible' );
		jQuery( '.log_list #logs_delete' ).css( 'opacity', '1' );
	} else {
		jQuery( '.log_list #logs_delete' ).css( 'visibility', 'hidden' );
		jQuery( '.log_list #logs_delete' ).css ('opacity', '0' );
	}
});

/* Click Log */
jQuery( document ).on( 'click', 'div.log_list tr.log', function() {
	var log_id		= jQuery( this ).attr( 'ID' )
	var table 		= jQuery( 'div.log_details table#log_' + log_id );

	jQuery( 'div.log_list tr.log' ).removeClass( 'active' );
	jQuery( 'div.sv_log' ).removeClass( 'show_filter' );

	if( jQuery( 'div.sv_log' ).hasClass( 'show_details' ) ) {
		var table_id	= jQuery( 'div.log_details table.show' ).attr( 'ID' );

		if( 'log_' + log_id != table_id ) {
			jQuery( this ).addClass( 'active' );
			jQuery( 'div.log_details table.show' ).toggleClass( 'show' );
			table.toggleClass( 'show' );
		} else {
			table.toggleClass( 'show' );
			jQuery( 'div.sv_log' ).toggleClass( 'show_details' );
		}
	} else {
		jQuery( this ).addClass( 'active' );
		jQuery( 'div.sv_log' ).toggleClass( 'show_details' );
		table.toggleClass( 'show' );
	}
});

/* Click Filter */
jQuery( document ).on( 'click', 'div.log_summary button#logs_filter', function() {
	jQuery( 'div.log_list tr.log' ).removeClass( 'active' );
	jQuery( 'div.sv_log' ).removeClass( 'show_details' );
	jQuery( 'div.log_details table' ).removeClass( 'show' );
	jQuery( 'div.sv_log' ).toggleClass( 'show_filter' );
});
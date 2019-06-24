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
	jQuery( 'body' ).toggleClass( 'sv_admin_menu_open' );
});
jQuery(document).ready(function(){
	sv_admin_load_page(window.location.hash);
});

/* Input - Radio checkbox style */
jQuery(document).on('click', '.sv_radio_switch_wrapper .switch_field input[type="radio"]:checked', function() {
	jQuery( '.sv_radio_switch_wrapper .switch_field input[type="radio"]:not(:checked)' ).prop( 'checked', true );
	jQuery( this ).removeProp( 'checked' );
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

/* set form referer for redirect to current subpage on submit */
jQuery( document ).on('submit', 'section.sv_admin_section form', function(e){
	jQuery(this).find('input[name="_wp_http_referer"]').val(jQuery(location).attr('href'));
});

/* Ajax Save Settings */
function show_notice( msg, type = 'info' ) {
	var types 	= [ 'info', 'success', 'warning', 'error' ];

	if ( jQuery.inArray( type, types ) >= 0 ) {
		var el = jQuery( '.sv_100_notice' );
		type = 'notice-' + type;

		el.html( msg );
		el.toggleClass( type );
		el.toggleClass( 'show' );

		setTimeout( function () {
			el.toggleClass( 'show' );
		}, 5000 );

		setTimeout( function () {
			el.toggleClass( type );
			el.html( '' );
		}, 6000 );
	}
}

/**
 * This part prevents spamming of the ajax request.
 * When update_option is called, it starts a timeout with the duration define in the timeout var,
 * if the save_option function is called in this time window, the timeout will reset and start again.
 */
var timeout			= 2000;
var forms			= {};
var timeout_handle	= setTimeout( save_settings , timeout );

function update_option( form ) {
	forms[ form.attr( 'id' ) ] = form;

	window.clearTimeout( timeout_handle );
	timeout_handle = setTimeout( save_settings , timeout );
}

function save_settings() {
	for ( const [ id, form ] of Object.entries( forms ) ) {
		jQuery( form ).ajaxSubmit({
			success: function () {
				show_notice( 'Settings Saved!', 'success' );
			},
		});
	}

	forms 	= [];
}

jQuery('.sv_dashboard_content form').submit( function ( e ) {
	e.preventDefault();

	update_option( jQuery( this ) );
});

jQuery( '.sv_dashboard_content input[type="checkbox"]' ).on( 'click', function() {
	update_option( jQuery( this ).parents( 'form' ) );
});

jQuery( '.sv_dashboard_content input, .sv_dashboard_content select' ).on( 'focusin', function() {
	jQuery( this ).data( 'val', jQuery( this ).val() );
});


jQuery( '.sv_dashboard_content input, .sv_dashboard_content select' ).on( 'change', function() {
	var prev 	= jQuery( this ).data( 'val' );
	var current = jQuery( this ).val();

	if ( current !== prev ) {
		update_option( jQuery( this ).parents( 'form' ) );
	}
});


jQuery( '.sv_dashboard_content textarea' ).on( 'change', function() {
	var prev 	= jQuery( this ).data( 'text' );
	var current = jQuery( this ).val();

	if ( current !== prev ) {
		update_option( jQuery( this ).parents( 'form' ) );
	}
});
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

/* ===== Ajax Save Settings ===== */
function show_notice( msg, type = 'info' ) {
	var types 	= [ 'info', 'success', 'warning', 'error' ];

	if ( jQuery.inArray( type, types ) >= 0 ) {
		var el = jQuery( '.sv_admin_notice' );
		type = 'notice-' + type;

		// Removes old message and replaces it with the new one
		el.html( msg );

		if ( ! el.hasClass( type ) ) {
			el.attr( 'class', 'sv_admin_notice' );
			el.toggleClass( type );
		}

		el.toggleClass( 'show' );

		setTimeout( function () {
			el.toggleClass( 'show' );
		}, 3000 );
	}
}

/**
 * This part prevents spamming of the ajax request.
 * When update_option is called, it starts a timeout with the duration define in the timeout var,
 * if the save_option function is called in this time window, the timeout will reset and start again.
 */
var timeout			= 1000;
var forms			= {};
var timeout_handle	= setTimeout( save_settings , timeout );

function update_option( form ) {
	if ( ! jQuery( form ).find( 'input[type="file"]' ).length > 0
		&& (
			jQuery( form ).data( 'ajax' ) === undefined
			|| jQuery( form ).data( 'ajax' ) === '1'
		)
	) {
		forms[ form.attr('id') ] = form;

		window.clearTimeout( timeout_handle );
		timeout_handle = setTimeout( save_settings, timeout );
	}
}

function save_settings() {
	for ( const [ id, form ] of Object.entries( forms ) ) {
		jQuery( form ).ajaxSubmit({
			success: function () {
				show_notice( sv_core_admin.settings_saved, 'success' );
			},
		});
	}

	forms 	= [];
}

/*
jQuery('.sv_dashboard_content form').submit( function ( e ) {
	if ( jQuery( this ).find( 'input[type="file"]' ).length < 1 ) {
		e.preventDefault();
		update_option( jQuery( this ) )
	}
});
*/

jQuery( '.sv_dashboard_content input[type="checkbox"], .sv_dashboard_content input[type="radio"]' ).on( 'click', function() {
	update_option( jQuery( this ).parents( 'form' ) );
});

jQuery( '.sv_dashboard_content input, .sv_dashboard_content select' ).on( 'focusin', function() {
	if ( ! jQuery( this ).is( 'input[type="radio"]' ) ) {
		jQuery( this ).data( 'val', jQuery( this ).val() );
	}
});


jQuery( '.sv_dashboard_content input, .sv_dashboard_content select' ).on( 'change', function() {
	if ( ! jQuery( this ).is( 'input[type="radio"]' ) ) {
		var prev 	= jQuery( this ).data( 'val' );
		var current = jQuery( this ).val();

		if ( current !== prev ) {
			update_option( jQuery( this ).parents( 'form' ) );
		}
	}
});


jQuery( '.sv_dashboard_content textarea' ).on( 'change', function() {
	var prev 	= jQuery( this ).data( 'text' );
	var current = jQuery( this ).val();

	if ( current !== prev ) {
		update_option( jQuery( this ).parents( 'form' ) );
	}
});

jQuery('#sv_core_expert_mode .sv_setting_checkbox input').on('change', function(){
	jQuery.post( sv_core_admin.ajaxurl, {
			action : 'sv_core_expert_mode',
			nonce : sv_core_admin.nonce_expert_mode,
			state : jQuery(this).val()
		},
		function(response) {
			response = JSON.parse(response);

			if(response.status === 'success') {
				show_notice( response.message, 'success' );
			} else {
				show_notice( response.message, 'error' );
			}
	});
});

jQuery(document).ready(function(){
	jQuery('.sv_setting_subpages').each(function() {
		jQuery( this ).children('.sv_setting_subpage').each(function( i ) {
			jQuery( this ).parent().children('.sv_setting_subpages_nav').append( '<li data-id="'+(i+1)+'">'+jQuery( this ).children('h2').text()+'</li>' );
		});
	});
	jQuery('.sv_setting_subpages_nav li:first-child').addClass('active');
	jQuery('body').on('click', '.sv_setting_subpages_nav > *', function(){
		jQuery( this ).parent().children('*').removeClass('active');
		jQuery( this ).addClass('active');
		jQuery( this ).parent().parent().children('.sv_setting_subpage').hide();
		jQuery( this ).parent().parent().children('.sv_setting_subpage:nth-of-type('+jQuery(this).data('id')+')').fadeIn();
	});
});

/* ===== Ajax Check ===== */
jQuery( 'button[data-sv_admin_ajax], input[data-sv_admin_ajax]' ).on( 'click', function() {
	let ajax = jQuery( this ).data( 'sv_admin_ajax' );
	let is_modal = jQuery( this ).data( 'sv_admin_modal' ) ? true : false;

	if ( ! is_modal ) {
		sv_admin_ajax_call( ajax );
	}
} );

function sv_admin_ajax_call( data, modal = false ) {
	if ( data[0].nonce === 'undefined' ) return false;

	if ( modal ) {
		jQuery( '.sv_admin_modal' ).removeClass( 'show' );
	}

	jQuery.post( ajaxurl, data[0], function( response ) {
		let data = JSON.parse( response );

		if ( data.notice ) {
			show_notice( data.msg, data.type )
		} else {
			console.log( response );
		}
	} );
}

/* ===== Modals ===== */
function show_modal( title, desc, type, args ) {
	let el		= jQuery( '.sv_admin_modal' );
	let content = get_modal_content( type, args );

	if ( content ) {
		el.find( '.sv_admin_modal_title' ).html( title );
		el.find( '.sv_admin_modal_description' ).html( desc );
		el.find( '.sv_admin_modal_content' ).html( content );

		el.addClass( 'show' );
	}
}

function get_modal_content( type, args ) {
	let content = '';
	let ajax_call = args.ajax
		? "onclick='sv_admin_ajax_call( " + JSON.stringify( args.ajax ) + ", true )'"
		: '';
	let form_call = args.form
		? 'onclick=jQuery("form#' + args.form + '").submit()'
		: '';

	switch ( type ) {
		case 'confirm':
			content = '<button class="sv_dashboard_button" ' + ajax_call + ' ' + form_call + '>Proceed</button>';
			break;
		default:
			return false;
	}

	return content;
}

jQuery( 'button[data-sv_admin_modal], input[data-sv_admin_modal]' ).on( 'click', function() {
	let title 	= jQuery( this ).data( 'sv_admin_modal' )[0].title;
	let desc 	= jQuery( this ).data( 'sv_admin_modal' )[0].desc;
	let type	= jQuery( this ).data( 'sv_admin_modal' )[0].type;
	let args	= jQuery( this ).data( 'sv_admin_modal' )[0].args ? jQuery( this ).data( 'sv_admin_modal' )[0].args : {};
	let ajax	= jQuery( this ).data( 'sv_admin_ajax' );

	if ( ajax !== 'undefined' ) {
		args.ajax = ajax;
	}

	show_modal( title, desc, type, args );
} );

jQuery( '.sv_admin_modal .sv_admin_modal_close, .sv_admin_modal .sv_admin_modal_cancel, .sv_admin_modal .sv_admin_modal_submit' ).on( 'click', function() {
	jQuery( this ).parents( '.sv_admin_modal' ).removeClass( 'show' );
} );
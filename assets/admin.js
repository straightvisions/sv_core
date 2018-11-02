function sv_admin_load_page(target){
	if(jQuery(target).length) {
		console.log(target);
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

/* Description (Tooltip) */
jQuery(document).on('click', '.sv_tooltip', function() {
	jQuery(this).next().toggleClass('open');
});
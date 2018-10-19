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

jQuery('.sv_admin_menu_item').click(function() {
	if(!jQuery(this).hasClass('active')) {
		sv_admin_load_page(jQuery(this).data('target'));
	}
});
jQuery('.sv_admin_mobile_toggle').click(function() {
	jQuery(jQuery(this).attr('data-target')).toggle();
});
jQuery(document).ready(function(){
	sv_admin_load_page(window.location.hash);
});
jQuery('.sv_admin_menu_item').click(function() {
	if(!jQuery(this).hasClass('active')) {
		jQuery('.sv_admin_menu_item.active').removeClass('active');
		jQuery(this).addClass('active');
		jQuery('.sv_admin_section').css('display', 'none');
		jQuery(jQuery(this).attr('href')).css('display', 'flex');
	}
});
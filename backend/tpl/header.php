<?php
	if( current_user_can( 'activate_plugins' ) ) {

	$section_title_desc	= array(
		'settings'		=> 'Configuration & Settings',
		'docs'			=> 'Complete Documentation',
		'tools'			=> 'Helpfull tools & helper'
	);
?>

<div class="sv_admin_menu">
	<a href="https://straightvisions.com" target="_blank" class="sv_admin_brand">
		<img src="<?php echo $this->get_url_lib_core('assets/logo.png'); ?>">
	</a>
	<a href="#section_about" class="sv_admin_menu_item active">
		<h4>About</h4>
		<span>General info & description</span>
	</a>
	<?php $this->load_section_menu(); ?>
</div>
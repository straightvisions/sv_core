<?php if( current_user_can( 'activate_plugins' ) ) { ?>
<div class="sv_admin_menu">
	<a href="https://straightvisions.com" target="_blank" class="sv_admin_brand">
		<img src="<?php echo $this->get_url_lib_core('assets/logo.png'); ?>">
	</a>
	<div data-target="#section_about" class="sv_admin_menu_item section_about active">
		<h4>About</h4>
		<span>General info & description</span>
	</div>
	<?php $this->load_section_menu(); ?>
	<div data-target="#section_legal" class="sv_admin_menu_item section_legal">
		<h4>Legal</h4>
		<span>Copyright & Usage</span>
	</div>
</div>
<?php }
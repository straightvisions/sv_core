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

	<section id="section_about" class="sv_admin_section">
		<h1 class="section_title">About</h1>
		<div class="section_content">
			<h3 class="divider">Info</h3>
			<ul class="info_list">
				<li>Name: <span><?php echo get_admin_page_title(); ?></span></li>
				<li>Version: <span>1.0.3</span></li>
				<li>Status: <span>active</span></li>
			</ul>
		</div>
	</section>
<?php
	}

    $this->load_section_html();
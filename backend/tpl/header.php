<?php
	if( current_user_can( 'activate_plugins' ) ) {
	wp_enqueue_script( 'jquery-form' );
	?>
<div class="sv_wrapper">
	<div class="sv_admin_menu">
		<div class="sv_admin_menu_head">
			<a href="https://straightvisions.com" target="_blank" class="sv_admin_brand">
				<img src="<?php echo $this->get_url_core('assets/logo.png'); ?>">
			</a>
			<div class="sv_admin_mobile_toggle" data-target="#sv_admin_menu_body"></div>
		</div>
		<div id="sv_admin_menu_body">
			<div data-target="#section_about" class="sv_admin_menu_item section_about active">
				<h4><?php _e('About', 'sv_core'); ?></h4>
				<span><?php _e('General info & description', 'sv_core'); ?></span>
			</div>
            <?php if($this->get_is_expert_mode() && defined('WP_DEBUG') && WP_DEBUG === true) { ?>
			<div data-target="#section_core_docs" class="sv_admin_menu_item section_core_docs">
				<h4><?php _e('Core Docs', 'sv_core'); ?></h4>
				<span><?php _e('Instance Core Methods', 'sv_core'); ?></span>
			</div>
			<?php } ?>
			<?php if(isset($_GET['page']) && $_GET['page'] !== 'straightvisions') { $this->load_section_menu(); } ?>
			<div data-target="#section_legal" class="sv_admin_menu_item section_legal">
				<h4><?php _e('Legal Information', 'sv_core'); ?></h4>
				<span><?php _e('Copyright & Usage', 'sv_core'); ?></span>
			</div>
		</div>
	</div>
	<div class="sv_dashboard_content">
<?php }
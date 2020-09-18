<?php
	if( current_user_can( 'activate_plugins' ) ) {
	wp_enqueue_script( 'jquery-form' );
	?>
<div class="sv_wrapper">
	<div class="sv_admin_menu">
		<div class="sv_admin_menu_head">
			<a href="https://straightvisions.com" target="_blank" class="sv_admin_brand">
				<img src="<?php echo $this->get_url_core('../assets/logo.png'); ?>">
			</a>
			<div class="sv_admin_mobile_toggle" data-sv_admin_menu_target="#sv_admin_menu_body"></div>
		</div>
		<div id="sv_admin_menu_body">
			<div data-sv_admin_menu_target="#section_about" class="sv_admin_menu_item section_about active">
				<h4><?php _e('About', 'sv_core'); ?></h4>
				<span><?php _e('General info & description', 'sv_core'); ?></span>
			</div>
			<?php if(isset($_GET['page']) && $_GET['page'] !== 'straightvisions') { $this->load_section_menu(); } ?>
			<div data-sv_admin_menu_target="#section_legal" class="sv_admin_menu_item section_legal">
				<h4><?php _e('Legal Information', 'sv_core'); ?></h4>
				<span><?php _e('Copyright & Usage', 'sv_core'); ?></span>
			</div>
		</div>
	</div>
	<div class="sv_dashboard_content">
        <div class="sv_dashboard_ajax_loader">
            <svg width="38" height="38" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg" stroke="#000">
                <g fill="none" fill-rule="evenodd">
                    <g transform="translate(1 1)" stroke-width="2">
                        <circle stroke-opacity=".5" cx="18" cy="18" r="18"/>
                        <path d="M36 18c0-9.94-8.06-18-18-18">
                            <animateTransform
                                    attributeName="transform"
                                    type="rotate"
                                    from="0 18 18"
                                    to="360 18 18"
                                    dur="1s"
                                    repeatCount="indefinite"/>
                        </path>
                    </g>
                </g>
            </svg>
        </div>
<?php }
<?php if ( current_user_can( apply_filters('sv_admin_menu_capability', 'manage_options') ) ) { ?>
	<div class="sv_section_description"><?php echo $module->get_section_desc(); ?></div>
	<div class="sv_setting_subpages">
		<ul class="sv_setting_subpages_nav"></ul>
		<?php
			require_once( $module->get_path_core( 'lib/tpl/settings/scripts_general.php' ) );
			require_once( $module->get_path_core( 'lib/tpl/settings/scripts_list.php' ) );
		?>
	</div>
	<?php
}
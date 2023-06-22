<?php if ( current_user_can( apply_filters('sv_admin_menu_capability', 'manage_options') ) ) { ?>
	<div class="sv_setting_subpage">
		<h2><?php _e('Scripts Overview', 'sv100'); ?></h2>
		<?php foreach($module->get_list() as $settings) { ?>
		<div class="sv_setting_flex">
			<?php
				echo $settings['attached']->form();

				if($settings['cache']['active']){
					echo $settings['cache']['invalidated']['gutenberg']->form();
					echo $settings['cache']['invalidated']['frontend']->form();
				}
			?>
		</div>
		<?php } ?>
	</div>
<?php } ?>
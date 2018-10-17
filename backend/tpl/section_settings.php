<?php
	if(current_user_can('activate_plugins')){
		?>
		<div id="section_<?php echo $section_name; ?>" class="sv_content_wrapper">
			<div class="sv_content">
				<div class="sv_settings">
					<div class="bypass_sv_content_wrapper">
						<div class="sv_content_title">
							<h1><?php echo get_admin_page_title(); ?></h1>
						</div>
						<div class="sv_content">
							<?php
								echo static::$settings->get_module_settings_form($section['object']);
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
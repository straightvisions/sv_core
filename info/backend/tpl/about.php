<?php if( current_user_can( 'activate_plugins' ) ) { ?>
<section id="section_about" class="sv_admin_section">
	<div class="section_head section_about">
		<div class="textbox">
			<h1 class="section_title"><?php _e( 'About', 'sv_core' ); ?></h1>
		</div>
	</div>
	<div class="section_content">
		<div class="col-50">
			<h3 class="divider"><?php _e( 'Info', 'sv_core' ); ?></h3>
			<ul class="info_list">
				<li>
					<?php _e('Name:', 'sv_core'); ?>
					<span><?php echo get_admin_page_title(); ?></span>
				</li>
				<li>
					<?php _e('Active Core Version:', 'sv_core'); ?>
					<span><?php echo $this->get_version_core(true); ?></span>
				</li>
				<li>
					<?php _e('Active Core Path:', 'sv_core'); ?>
					<span><?php echo $this->get_path_core(); ?></span>
				</li>
			</ul>
		</div>
		<div class="col-50">
			<h3 class="divider"><?php _e('Description', 'sv_core'); ?></h3>
			<p class="instance_description">
			<?php
			_e(
			'Our themes and plugins share a core which provides commonly used features.
				The core is included and shared within each plugin or theme, so make sure if you update one product,
				to update all others too.'
				, 'sv_core'
			);
			?>
			</p>
		</div>
		<div class="col-50">
			<h3 class="divider"><?php _e('Primary Instance', 'sv_core'); ?></h3>
			<a href="/wp-admin/<?php echo ($this->is_theme_instance() ? 'themes' : 'admin'); ?>
			.php?page=<?php echo $this->get_name() ?>"><?php echo $this->get_section_title(); ?></a>
		</div>

		<div class="col-50">
			<form id="<?php echo 'sv_core_expert_mode'; ?>" method="POST">
				<?php
					// @todo Add description to describe what expert mode does
					echo $this->get_setting()
						->set_ID('sv_expert_mode')
						->set_title( __('Expert Mode', 'sv_core'))
						->set_is_no_prefix()
                        ->load_type('checkbox')
                        ->set_data(get_user_meta(get_current_user_id(), 'sv_core_expert_mode', true))
                        ->form();
				?>
			</form>
		</div>
		<div>
			<h3 class="divider"><?php _e('Instances', 'sv_core'); ?></h3>
			<ul class="instance_list">
			<?php
				foreach( $this->get_instances() as $name => $instance ) {
					if($this->is_instance_active($instance->get_name())) {
						$instance_msg = '';
					} else {
						$instance_msg = __('This plugin version is outdated, please update this plugin!', 'sv_core');
					}
			?>
			<a href="/wp-admin/<?php echo ($instance->is_theme_instance() ? 'themes' : 'admin'); ?>
				.php?page=<?php echo $instance->get_name() ?>"
			   class="<?php echo (($this->is_instance_active($instance->get_name())) ? '' : 'disabled'); ?>">
				<h1 class="instance_title
					<?php
					echo $instance->is_theme_instance()
						? 'instance_theme'
						: 'instance_plugin';
					?>">
					<?php echo $instance->get_section_title(); ?>
				</h1>
				<p class="instance_desc"><?php echo $instance->get_section_desc(); ?></p>
				<div class="instance_type">
					<?php echo $instance->is_theme_instance() ? __('Theme', 'sv_core') : __('Plugin', 'sv_core'); ?>
				</div>
				<div class="instance_version">v<?php echo $instance->get_version( true ); ?></div>
				<div class="instance_version_core">v<?php echo $instance->get_version_core( true ); ?></div>
				<div class="instance_version_core_match">v<?php echo $instance->get_version_core_match( true ); ?></div>
				<div class="instance_status">
					<?php
					echo (($this->is_instance_active($instance->get_name()))
						? __('Active', 'sv_core')
						: __('Disabled', 'sv_core'));
					?>
				</div>
				<div class="instance_msg"><?php echo $instance_msg; ?></div>
			</a>
			<?php } ?>
			</ul>
		</div>
	</div>
</section>
<?php }
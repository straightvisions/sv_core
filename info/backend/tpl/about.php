<?php if( current_user_can( 'activate_plugins' ) ) { ?>
<section id="section_about" class="sv_admin_section">
	<h1 class="section_title section_about">About</h1>
	<div class="section_content">
		<div class="col-50">
			<h3 class="divider">Info</h3>
			<ul class="info_list">
				<li>Name: <span><?php echo get_admin_page_title(); ?></span></li>
				<li>Core Version: <span><?php echo $this->get_version_core(true); ?></span></li>
			</ul>
		</div>
		<div class="col-50">
			<h3 class="divider">Description</h3>
			<p class="instance_description">
			<?php _e('Our themes and plugins share a core which provides commonly used features. The core is included and shared within each plugin or theme, so make sure if you update one product, to update all others too.', $this->get_module_name()); ?>
			</p>
		</div>
		<div>
			<h3 class="divider"><?php _e('Instances', $this->get_module_name()); ?></h3>
			<ul class="instance_list">
			<?php
				foreach( $this->get_instances() as $name => $instance ) {
					if($this->is_instance_active($instance->get_name())) {
						$instance_msg = '';
					} else {
						$instance_msg = 'This plugin version is outdated, please update this plugin!';
					}
			?>
			<a href="/wp-admin/admin.php?page=<?php echo $instance->get_name() ?>" class="<?php echo (($this->is_instance_active($instance->get_name())) ? '' : 'disabled'); ?>">
				<h1 class="instance_title instance_plugin"><?php echo $instance->get_section_title(); ?></h1>
				<p class="instance_desc"><?php echo $instance->get_section_desc(); ?></p>
				<div class="instance_type">Plugin</div>
				<div class="instance_version">v<?php echo $instance->get_version(true); ?></div>
				<div class="instance_status"><?php echo (($this->is_instance_active($instance->get_name())) ? 'Active' : 'Disabled'); ?></div>
				<div class="instance_msg"><?php echo $instance_msg; ?></div>
			</a>
			<?php } ?>
			</ul>
		</div>
	</div>
</section>
<?php }
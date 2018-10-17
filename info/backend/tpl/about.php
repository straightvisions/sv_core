<?php if( current_user_can( 'activate_plugins' ) ) { ?>
<section id="section_about" class="sv_admin_section">
	<h1 class="section_title section_about">About</h1>
	<div class="section_content">
		<div class="col-50">
			<h3 class="divider">Info</h3>
			<ul class="info_list">
				<li>Name: <span><?php echo get_admin_page_title(); ?></span></li>
				<li>Version: <span><?php echo $this->get_version_core(true); ?></span></li>
			</ul>
		</div>
		<div class="col-50">
			<h3 class="divider">Description</h3>
			<p class="instance_description">
			<?php _e('Our themes and plugins share a core which provides commonly used features. The core is included and shared within each plugin or theme, so make sure if you update one product, to update all others too.', $this->get_module_name()); ?>
			</p>
		</div>
		<div>
			<h3 class="divider"><?php _e('Active Instances', $this->get_module_name()); ?></h3>
			<ul class="instance_list">
			<?php
				foreach( $this->get_instances() as $name => $instance ) {
					echo '
					<li>
						<a href="/wp-admin/admin.php?page='.$instance->get_name().'">
						<div class="'.$this->get_prefix('instance_name').'">' . $name . '</div>
						<div class="'.$this->get_prefix('instance_version').'">v'.$instance->get_version(true).'</div>
						<div>'.(($instance->get_root()->get_version_core_match() != $this->get_version_core()) ? 'Core Version mismatch: '.$instance->get_root()->get_version_core_match(true) : '').'</div>
						</a>
					</li>';
				}
			?>
			</ul>
		</div>
	</div>
</section>
<?php }
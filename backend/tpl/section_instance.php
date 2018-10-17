<?php if( current_user_can( 'activate_plugins' ) ) { ?>
<section id="section_<?php echo $section_name; ?>" class="sv_admin_section">
	<h1 class="section_title section_instance"><?php echo ucfirst($section['type']) ;?></h1>
	<div class="section_content">
		<div class="col-50">
			<h3 class="divider"><?php _e('get current url', $section['object']->get_module_name()); ?></h3>
			<p><?php _e('URL to current location', $section['object']->get_module_name()); ?></p>
			<code>$this->get_current_url();</code>
			<blockquote><?php echo $section['object']->get_current_url(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get name</h3>
			<p><?php _e('Name of current instance:', $section['object']->get_module_name()); ?></p>
			<code>$this->get_root()->get_name();</code>
			<blockquote><?php echo $section['object']->get_root()->get_name(); ?></blockquote>
			<p><?php _e('Full hierarchy name:', $section['object']->get_module_name()); ?></p>
			<code>$this->get_name();</code>
			<blockquote><?php echo $section['object']->get_name(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get module name</h3>
			<p><?php _e('Shortname (without namespace) of current hierarchy class', $section['object']->get_module_name()); ?></p>
			<code>$this->get_module_name();</code>
			<blockquote><?php echo $section['object']->get_module_name(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get prefix</h3>
			<p><?php _e('Prefix by get_name:', $section['object']->get_module_name()); ?></p>
			<code>$this->get_prefix($append='');</code>
			<blockquote><?php echo $section['object']->get_prefix(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get relative prefix</h3>
			<p><?php _e('Prefix by get_name, but root class replaced with sv_common:', $section['object']->get_module_name()); ?></p>
			<code>$this->get_relative_prefix($append='');</code>
			<blockquote><?php echo $section['object']->get_relative_prefix(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get path</h3>
			<p><?php _e('Absolute path to instance:', $section['object']->get_module_name()); ?></p>
			<code>$this->get_path($suffix='',$check_if_exists=false);</code>
			<blockquote><?php echo $section['object']->get_path(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get url</h3>
			<p><?php _e('Absolute url to instance:', $section['object']->get_module_name()); ?></p>
			<code>$this->get_url($suffix='',$check_if_exists=false);</code>
			<blockquote><?php echo $section['object']->get_url(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get core path</h3>
			<p><?php _e('Absolute path to core:', $section['object']->get_module_name()); ?></p>
			<code>$this->get_path_lib_core($suffix='',$check_if_exists=false);</code>
			<blockquote><?php echo $section['object']->get_path_lib_core(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get core url</h3>
			<p><?php _e('Absolute url to core:', $section['object']->get_module_name()); ?></p>
			<code>$this->get_url_lib_core($suffix='',$check_if_exists=false);</code>
			<blockquote><?php echo $section['object']->get_url_lib_core(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider"><?php _e('Enqueue Admin Styles', $section['object']->get_module_name()); ?></h3>
			<p><?php _e('Enqueue SV style for admin menu pages:', $section['object']->get_module_name()); ?></p>
			<code>$this->acp_style($hook=false);</code>
		</div>
		<div>
			<h3 class="divider"><?php _e('Path Structure', $section['object']->get_module_name()); ?></h3>
			<p><?php _e('To encourage following default path structures and to make maintenance easier, the following methods follow the default structure.', $section['object']->get_module_name()); ?></p>
			<code>$this->get_path_lib($suffix='',$check_if_exists=false);</code>
			<blockquote><?php echo $section['object']->get_path_lib(); ?></blockquote>
			<code>$this->get_url_lib($suffix='',$check_if_exists=false);</code>
			<blockquote><?php echo $section['object']->get_url_lib(); ?></blockquote>
			<code>$this->get_path_lib_modules();</code>
			<blockquote><?php echo $section['object']->get_path_lib_modules(); ?></blockquote>
			<code>$this->get_path_lib_section($section=false,$dir=false,$suffix='',$check_if_exists=false);</code>
			<blockquote><?php echo $section['object']->get_path_lib_section(); ?></blockquote>
			<code>$this->get_url_lib_section($section=false,$dir=false,$suffix='',$check_if_exists=false);</code>
			<blockquote><?php echo $section['object']->get_url_lib_section(); ?></blockquote>
		</div>
	</div>
</section>
<?php }
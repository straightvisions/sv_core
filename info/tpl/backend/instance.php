<?php
	$instance = $this->get_root()->get_instances()[$section_name];
?>
<div id="section_<?php echo $section_name; ?>" class="sv_content_wrapper">
	<div class="sv_content">
		<h1 class="sv_content_title"><?php echo $instance->get_name(); ?></h1>
		<h4><?php _e('get current url', $instance->get_module_name()); ?></h4>
		<p><?php _e('URL to current location', $instance->get_module_name()); ?></p>
		<code>$instance->get_current_url();</code>
		<blockquote><?php echo $instance->get_current_url(); ?></blockquote>
		<h4>get name</h4>
		<p><?php _e('Name of current instance:', $instance->get_module_name()); ?></p>
		<code>$instance->get_root()->get_name();</code>
		<blockquote><?php echo $instance->get_root()->get_name(); ?></blockquote>
		<p><?php _e('Full hierarchy name:', $instance->get_module_name()); ?></p>
		<code>$instance->get_name();</code>
		<blockquote><?php echo $instance->get_name(); ?></blockquote>
		<h4>get module name</h4>
		<p><?php _e('Shortname (without namespace) of current hierarchy class', $instance->get_module_name()); ?></p>
		<code>$instance->get_module_name();</code>
		<blockquote><?php echo $instance->get_module_name(); ?></blockquote>
		<h4>get prefix</h4>
		<p><?php _e('Prefix by get_name:', $instance->get_module_name()); ?></p>
		<code>$instance->get_prefix($append='');</code>
		<blockquote><?php echo $instance->get_prefix(); ?></blockquote>
		<h4>get relative prefix</h4>
		<p><?php _e('Prefix by get_name, but root class replaced with sv_common:', $instance->get_module_name()); ?></p>
		<code>$instance->get_relative_prefix($append='');</code>
		<blockquote><?php echo $instance->get_relative_prefix(); ?></blockquote>
		<h4>get path</h4>
		<p><?php _e('Absolute path to instance:', $instance->get_module_name()); ?></p>
		<code>$instance->get_path($suffix='',$check_if_exists=false);</code>
		<blockquote><?php echo $instance->get_path(); ?></blockquote>
		<h4>get url</h4>
		<p><?php _e('Absolute url to instance:', $instance->get_module_name()); ?></p>
		<code>$instance->get_url($suffix='',$check_if_exists=false);</code>
		<blockquote><?php echo $instance->get_url(); ?></blockquote>
		<h4>get core path</h4>
		<p><?php _e('Absolute path to core:', $instance->get_module_name()); ?></p>
		<code>$instance->get_path_lib_core($suffix='',$check_if_exists=false);</code>
		<blockquote><?php echo $instance->get_path_lib_core(); ?></blockquote>
		<h4>get core url</h4>
		<p><?php _e('Absolute url to core:', $instance->get_module_name()); ?></p>
		<code>$instance->get_url_lib_core($suffix='',$check_if_exists=false);</code>
		<blockquote><?php echo $instance->get_url_lib_core(); ?></blockquote>
		<h4><?php _e('Enqueue Admin Styles', $instance->get_module_name()); ?></h4>
		<p><?php _e('Enqueue SV style for admin menu pages:', $instance->get_module_name()); ?></p>
		<code>$instance->acp_style($hook=false);</code>
		<h3><?php _e('Path Structure', $instance->get_module_name()); ?></h3>
		<p><?php _e('To encourage following default path structures and to make maintenance easier, the following methods follow the default structure.', $instance->get_module_name()); ?></p>
		<code>$instance->get_path_lib($suffix='',$check_if_exists=false);</code>
		<blockquote><?php echo $instance->get_path_lib(); ?></blockquote>
		<code>$instance->get_url_lib($suffix='',$check_if_exists=false);</code>
		<blockquote><?php echo $instance->get_url_lib(); ?></blockquote>
		<code>$instance->get_path_lib_modules();</code>
		<blockquote><?php echo $instance->get_path_lib_modules(); ?></blockquote>
		<code>$instance->get_path_lib_section($section=false,$dir=false,$suffix='',$check_if_exists=false);</code>
		<blockquote><?php echo $instance->get_path_lib_section(); ?></blockquote>
		<code>$instance->get_url_lib_section($section=false,$dir=false,$suffix='',$check_if_exists=false);</code>
		<blockquote><?php echo $instance->get_url_lib_section(); ?></blockquote>
	</div>
</div>
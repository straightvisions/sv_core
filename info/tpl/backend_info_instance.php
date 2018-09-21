<?php
	$this->get_root()->acp_style();
?>
<h1><?php echo $this->get_name(); ?></h1>
<h4><?php _e('get current url', $this->get_module_name()); ?></h4>
<p><?php _e('URL to current location', $this->get_module_name()); ?></p>
<code>$this->get_current_url();</code>
<blockquote><?php echo $this->get_current_url(); ?></blockquote>
<h4>get name</h4>
<p><?php _e('Name of current instance:', $this->get_module_name()); ?></p>
<code>$this->get_root()->get_name();</code>
<blockquote><?php echo $this->get_root()->get_name(); ?></blockquote>
<p><?php _e('Full hierarchy name:', $this->get_module_name()); ?></p>
<code>$this->get_name();</code>
<blockquote><?php echo $this->get_name(); ?></blockquote>
<h4>get module name</h4>
<p><?php _e('Shortname (without namespace) of current hierarchy class', $this->get_module_name()); ?></p>
<code>$this->get_module_name();</code>
<blockquote><?php echo $this->get_module_name(); ?></blockquote>
<h4>get prefix</h4>
<p><?php _e('Prefix by get_name:', $this->get_module_name()); ?></p>
<code>$this->get_prefix($append='');</code>
<blockquote><?php echo $this->get_prefix(); ?></blockquote>
<h4>get relative prefix</h4>
<p><?php _e('Prefix by get_name, but root class replaced with sv_common:', $this->get_module_name()); ?></p>
<code>$this->get_relative_prefix($append='');</code>
<blockquote><?php echo $this->get_relative_prefix(); ?></blockquote>
<h4>get path</h4>
<p><?php _e('Absolute path to instance:', $this->get_module_name()); ?></p>
<code>$this->get_path($suffix='',$check_if_exists=false);</code>
<blockquote><?php echo $this->get_path(); ?></blockquote>
<h4>get url</h4>
<p><?php _e('Absolute url to instance:', $this->get_module_name()); ?></p>
<code>$this->get_url($suffix='',$check_if_exists=false);</code>
<blockquote><?php echo $this->get_url(); ?></blockquote>
<h4>get core path</h4>
<p><?php _e('Absolute path to core:', $this->get_module_name()); ?></p>
<code>$this->get_path_lib_core($suffix='',$check_if_exists=false);</code>
<blockquote><?php echo $this->get_path_lib_core(); ?></blockquote>
<h4>get core url</h4>
<p><?php _e('Absolute url to core:', $this->get_module_name()); ?></p>
<code>$this->get_url_lib_core($suffix='',$check_if_exists=false);</code>
<blockquote><?php echo $this->get_url_lib_core(); ?></blockquote>
<h4><?php _e('Enqueue Admin Styles', $this->get_module_name()); ?></h4>
<p><?php _e('Enqueue SV style for admin menu pages:', $this->get_module_name()); ?></p>
<code>$this->acp_style($hook=false);</code>
<h3><?php _e('Path Structure', $this->get_module_name()); ?></h3>
<p><?php _e('To encourage following default path structures and to make maintenance easier, the following methods follow the default structure.', $this->get_module_name()); ?></p>
<code>$this->get_path_lib($suffix='',$check_if_exists=false);</code>
<blockquote><?php echo $this->get_path_lib(); ?></blockquote>
<code>$this->get_url_lib($suffix='',$check_if_exists=false);</code>
<blockquote><?php echo $this->get_url_lib(); ?></blockquote>
<code>$this->get_path_lib_modules();</code>
<blockquote><?php echo $this->get_path_lib_modules(); ?></blockquote>
<code>$this->get_path_lib_section($section=false,$dir=false,$suffix='',$check_if_exists=false);</code>
<blockquote><?php echo $this->get_path_lib_section(); ?></blockquote>
<code>$this->get_url_lib_section($section=false,$dir=false,$suffix='',$check_if_exists=false);</code>
<blockquote><?php echo $this->get_url_lib_section(); ?></blockquote>
<?php if( current_user_can( 'edit_posts' ) ) { ?>
<section id="section_core_docs" class="sv_admin_section">
	<div class="section_head section_core_docs">
		<div class="textbox">
			<h1 class="section_title">Core Docs</h1>
		</div>
	</div>
	<div class="section_content">
		<h3 class="divider">Description</h3>
		<p>A detailed documentation of all helpful core methods and the returned values, that are used in all our products.</p>
		<div class="col-50">
			<h3 class="divider"><?php _e('get current url', $this->get_module_name()); ?></h3>
			<p><?php _e('URL to current location', $this->get_module_name()); ?></p>
			<code>$this->get_current_url();</code>
			<blockquote><?php echo $this->get_current_url(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get name</h3>
			<p><?php _e('Name of current instance:', $this->get_module_name()); ?></p>
			<code>$this->get_root()->get_name();</code>
			<blockquote><?php echo $this->get_root()->get_name(); ?></blockquote>
			<p><?php _e('Full hierarchy name:', $this->get_module_name()); ?></p>
			<code>$this->get_name();</code>
			<blockquote><?php echo $this->get_name(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get module name</h3>
			<p><?php _e('Shortname (without namespace) of current hierarchy class', $this->get_module_name()); ?></p>
			<code>$this->get_module_name();</code>
			<blockquote><?php echo $this->get_module_name(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get prefix</h3>
			<p><?php _e('Prefix by get_name:', $this->get_module_name()); ?></p>
			<code>$this->get_prefix($append='');</code>
			<blockquote><?php echo $this->get_prefix(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get relative prefix</h3>
			<p><?php _e('Prefix by get_name, but root class replaced with sv_common:', $this->get_module_name()); ?></p>
			<code>$this->get_relative_prefix($append='');</code>
			<blockquote><?php echo $this->get_relative_prefix(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get path</h3>
			<p><?php _e('Absolute path to instance:', $this->get_module_name()); ?></p>
			<code>$this->get_path($suffix='',$check_if_exists=false);</code>
			<blockquote><?php echo $this->get_path(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get url</h3>
			<p><?php _e('Absolute url to instance:', $this->get_module_name()); ?></p>
			<code>$this->get_url($suffix='',$check_if_exists=false);</code>
			<blockquote><?php echo $this->get_url(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get core path</h3>
			<p><?php _e('Absolute path to core:', $this->get_module_name()); ?></p>
			<code>$this->get_path_core($suffix='',$check_if_exists=false);</code>
			<blockquote><?php echo $this->get_path_core(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get core url</h3>
			<p><?php _e('Absolute url to core:', $this->get_module_name()); ?></p>
			<code>$this->get_url_core($suffix='',$check_if_exists=false);</code>
			<blockquote><?php echo $this->get_url_core(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider"><?php _e('Enqueue Admin Styles', $this->get_module_name()); ?></h3>
			<p><?php _e('Enqueue SV style for admin menu pages:', $this->get_module_name()); ?></p>
			<code>$this->acp_style($hook=false);</code>
		</div>
	</div>
</section>
<?php }
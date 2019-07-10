<?php if( current_user_can( 'activate_plugins' ) ) { ?>
<section id="section_core_docs" class="sv_admin_section">
	<div class="section_head section_core_docs">
		<div class="textbox">
			<h1 class="section_title">Core Docs</h1>
		</div>
	</div>
	<div class="section_content">
		<h3 class="divider">Description</h3>
		<p><?php _e('This cheat sheet lists core methods you may want to use when developing own modules.', 'sv_core'); ?></p>
		<div class="col-50">
			<h3 class="divider"><?php _e('get current url', 'sv_core'); ?></h3>
			<p><?php _e('URL to current location', 'sv_core'); ?></p>
			<code>$this->get_current_url();</code>
			<blockquote><?php echo $this->get_current_url(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get name</h3>
			<p><?php _e('Name of current instance:', 'sv_core'); ?></p>
			<code>$this->get_root()->get_name();</code>
			<blockquote><?php echo $this->get_root()->get_name(); ?></blockquote>
			<p><?php _e('Full hierarchy name:', 'sv_core'); ?></p>
			<code>$this->get_name();</code>
			<blockquote><?php echo $this->get_name(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get module name</h3>
			<p><?php _e('Shortname (without namespace) of current hierarchy class', 'sv_core'); ?></p>
			<code>'sv_core';</code>
			<blockquote><?php echo 'sv_core'; ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get prefix</h3>
			<p><?php _e('Prefix by get_name:', 'sv_core'); ?></p>
			<code>$this->get_prefix($append='');</code>
			<blockquote><?php echo $this->get_prefix(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get relative prefix</h3>
			<p><?php _e('Prefix by get_name, but root class replaced with sv_common:', 'sv_core'); ?></p>
			<code>$this->get_relative_prefix($append='');</code>
			<blockquote><?php echo $this->get_relative_prefix(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get path</h3>
			<p><?php _e('Absolute path to instance:', 'sv_core'); ?></p>
			<code>$this->get_path($suffix='',$check_if_exists=false);</code>
			<blockquote><?php echo $this->get_path(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get url</h3>
			<p><?php _e('Absolute url to instance:', 'sv_core'); ?></p>
			<code>$this->get_url($suffix='',$check_if_exists=false);</code>
			<blockquote><?php echo $this->get_url(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get core path</h3>
			<p><?php _e('Absolute path to core:', 'sv_core'); ?></p>
			<code>$this->get_path_core($suffix='',$check_if_exists=false);</code>
			<blockquote><?php echo $this->get_path_core(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider">get core url</h3>
			<p><?php _e('Absolute url to core:', 'sv_core'); ?></p>
			<code>$this->get_url_core($suffix='',$check_if_exists=false);</code>
			<blockquote><?php echo $this->get_url_core(); ?></blockquote>
		</div>
		<div class="col-50">
			<h3 class="divider"><?php _e('Enqueue Admin Styles', 'sv_core'); ?></h3>
			<p><?php _e('Enqueue SV style for admin menu pages:', 'sv_core'); ?></p>
			<code>$this->acp_style($hook=false);</code>
		</div>
	</div>
</section>
<?php }
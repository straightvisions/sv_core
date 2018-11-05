<?php if( current_user_can( 'activate_plugins' ) ) { ?>
<section id="section_about" class="sv_admin_section">
	<h1 class="section_title section_about">About</h1>
	<div class="section_content">
		<div class="col-50">
			<h3 class="divider">Info</h3>
			<ul class="info_list">
				<li>Name: <span><?php echo $this->get_section_title(); ?></span></li>
				<li>Version: <span><?php echo $this->get_version(true); ?></span></li>
			</ul>
		</div>
		<div class="col-50">
			<h3 class="divider">Description</h3>
			<p class="instance_description"><?php echo $this->get_section_desc(); ?></p>
		</div>
		<div>
			<h3 class="divider">Active Modules</h3>
			<!-- <ul class="instance_list">
				<a href="/wp-admin/admin.php?page=" class="">
					<h1 class="instance_title instance_plugin">[ Module Name ]</h1>
					<p class="instance_desc">This is the module description.</p>
					<div class="instance_type">Plugin</div>
					<div class="instance_version">v1.003</div>
					<div class="instance_status">Active</div>
					<div class="instance_msg"></div>
				</a>
				<a href="/wp-admin/admin.php?page=" class="">
					<h1 class="instance_title instance_plugin">[ Module Name ]</h1>
					<p class="instance_desc">This is the module description.</p>
					<div class="instance_type">Plugin</div>
					<div class="instance_version">v1.003</div>
					<div class="instance_status">Active</div>
					<div class="instance_msg"></div>
				</a>
				<a href="/wp-admin/admin.php?page=" class="">
					<h1 class="instance_title instance_theme">[ Module Name ]</h1>
					<p class="instance_desc">This is the module description.</p>
					<div class="instance_type">Theme</div>
					<div class="instance_version">v1.003</div>
					<div class="instance_status">Active</div>
					<div class="instance_msg"></div>
				</a>
				<a href="/wp-admin/admin.php?page=" class="">
					<h1 class="instance_title instance_plugin">[ Module Name ]</h1>
					<p class="instance_desc">This is the module description.</p>
					<div class="instance_type">Plugin</div>
					<div class="instance_version">v1.003</div>
					<div class="instance_status">Active</div>
					<div class="instance_msg"></div>
				</a>
			</ul> -->
		</div>
	</div>
</section>
<?php }
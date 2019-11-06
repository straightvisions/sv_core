<?php if( current_user_can( 'edit_posts' ) ) { ?>
	<section id="section_about" class="sv_admin_section">
		<div class="section_head section_settings">
			<div class="textbox">
				<h1 class="section_title">About</h1>
			</div>
		</div>
		<div class="section_content">
			<div class="col-50">
				<h3 class="divider">Info</h3>
				<ul class="info_list">
					<li>Name: <span><?php echo get_admin_page_title(); ?></span></li>
					<li>Active Core Version: <span><?php echo $this->get_version_core(true); ?></span></li>
					<li>Active Core Path: <span><?php echo $this->get_path_core(); ?></span></li>
				</ul>
			</div>
			<div class="col-50">
				<h3 class="divider">Description</h3>
				<p class="instance_description">Here you canSee and manage the log.</p>
			</div>
		</div>
	</section>
<?php }
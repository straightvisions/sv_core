<?php if( current_user_can( 'activate_plugins' ) ) { ?>
<section id="section_about" class="sv_admin_section">
	<div class="section_head section_about">
		<div class="textbox">
			<h1 class="section_title">About</h1>
		</div>
	</div>
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
	</div>
</section>
<?php }
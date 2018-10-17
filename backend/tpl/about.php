<?php if( current_user_can( 'activate_plugins' ) ) { ?>
<section id="section_about" class="sv_admin_section">
	<h1 class="section_title">About</h1>
	<div class="section_content">
		<div class="col-50">
			<h3 class="divider">Info</h3>
			<ul class="info_list">
				<li>Name: <span><?php echo get_admin_page_title(); ?></span></li>
				<li>Version: <span>1.0.3</span></li>
				<li>Status: <span>active</span></li>
			</ul>
		</div>
		<div class="col-50">
			<h3 class="divider">Description</h3>
			<p class="instance_description">
				Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
			</p>
		</div>
		<div>
			<h3 class="divider">Active Modules</h3>
			<ul class="instance_list">
				<li>[ Module Name ]</li>
				<li>[ Module Name ]</li>
				<li>[ Module Name ]</li>
				<li>[ Module Name ]</li>
				<li>[ Module Name ]</li>
				<li>[ Module Name ]</li>
				<li>[ Module Name ]</li>
				<li>[ Module Name ]</li>
			</ul>
		</div>
	</div>
</section>
<?php }
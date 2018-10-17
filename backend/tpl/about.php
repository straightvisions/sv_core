<?php if( current_user_can( 'activate_plugins' ) ) { ?>
<section id="section_about" class="sv_admin_section">
	<h1 class="section_title">About</h1>
	<div class="section_content">
		<h3 class="divider">Info</h3>
		<ul class="info_list">
			<li>Name: <span><?php echo get_admin_page_title(); ?></span></li>
			<li>Version: <span>1.0.3</span></li>
			<li>Status: <span>active</span></li>
		</ul>
	</div>
</section>
<?php }
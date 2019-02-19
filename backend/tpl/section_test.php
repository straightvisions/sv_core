<?php if( current_user_can( 'activate_plugins' ) ) { ?>
<section id="section_<?php echo $section_name; ?>" class="sv_admin_section">
	<div class="section_head section_test">
		<div class="textbox">
			<h1 class="section_title"><?php echo $section['object']->get_section_title(); ?></h1>
			<h4 class="section_desc"><?php echo $section['object']->get_section_desc(); ?></h4>
		</div>
	</div>
	<div class="section_content">
		<h3 class="divider">Überschrift</h3>
	</div>
</section>
<?php }
<?php if( current_user_can( 'activate_plugins' ) ) { ?>
<section id="section_<?php echo $section_name; ?>" class="sv_admin_section">
	<div class="section_head section_tools">
		<div class="textbox">
			<h1 class="section_title"><?php echo $section['object']->get_section_title(); ?></h1>
		</div>
	</div>
	<div class="section_content">
		<?php require_once($section['object']->get_section_template_path()); ?>
	</div>
</section>
<?php }
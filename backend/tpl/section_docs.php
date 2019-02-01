<?php if( current_user_can( 'activate_plugins' ) ) { ?>
	<section id="section_<?php echo $section_name; ?>" class="sv_admin_section">
		<h1 class="section_title section_docs"><?php echo $section['object']->get_section_title(); ?></h1>
		<div class="section_content">
			<?php require_once($section['object']->get_section_template_path()); ?>
		</div>
	</section>
<?php }
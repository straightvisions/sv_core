<?php if( current_user_can( 'activate_plugins' ) ) { ?>
<section id="section_<?php echo $section_name; ?>" class="sv_admin_section">
	<h1 class="section_title section_settings"><?php echo ucfirst($section['type']) ;?></h1>
	<div class="section_content">
	<?php echo static::$settings->get_module_settings_form($section['object']); ?>
	</div>
</section>
<?php }
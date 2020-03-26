<?php $tpl_path = $this->get_path_core( 'settings/tpl/' . $this->run_type()->get_module_name() . '_tpl/' ); ?>

<h4><?php echo $title; ?></h4>
<div class="description"><?php echo $description; ?></div>

<div class="sv_setting_flex">
	<?php 
		require_once( $tpl_path . 'font_family.php' ); 
		require_once( $tpl_path . 'font_size.php' ); 
		require_once( $tpl_path . 'font_weight.php' ); 
		require_once( $tpl_path . 'line_height.php' ); 
	?>
</div>
<div class="sv_setting_flex">
	<?php 
		require_once( $tpl_path . 'color.php' ); 
		require_once( $tpl_path . 'text_decoration.php' ); 
		require_once( $tpl_path . 'text_transform.php' );
		require_once( $tpl_path . 'letter_spacing.php' );
	?>
</div>
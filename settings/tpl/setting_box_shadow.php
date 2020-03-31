<?php 
$tpl_path	= $this->get_path_core( 'settings/tpl/' . $this->run_type()->get_module_name() . '_tpl/' ); 
$value 		= $value ? $value : '0px 0px 0px 0px #000000';
$values		= explode( ' ', $value );
?>

<h4><?php echo $title; ?></h4>
<div class="description"><?php echo $description; ?></div>
<div class="sv_setting_box_shadow">
	<div class="sv_setting_flex">
	<?php 
		require( $tpl_path . 'horizontal_length.php' ); 
		require( $tpl_path . 'vertical_length.php' ); 
	?>
	</div>
	<div class="sv_setting_flex">
	<?php 
		require( $tpl_path . 'blur_radius.php' ); 
		require( $tpl_path . 'spread_radius.php' ); 
	?>
	</div>
	<div class="sv_setting_flex">
	<?php 
		require( $tpl_path . 'color.php' );
		require( $tpl_path . 'inset.php' );
	?>
	</div>
	<div class="sv_setting sv_setting_preview">
		<h3><?php _e( 'Preview', 'sv100' ); ?></h3>
	</div>
	<input
		data-sv_type="sv_form_field"
		class="sv_input"
		id="<?php echo $ID; ?>"
		name="<?php echo $name; ?>"
		type="hidden"
		value="<?php echo esc_attr( $value ); ?>"
	/>
</div>

<style>
.sv_setting_range {
	display: flex;
}

.sv_setting_range input[type="number"].sv_input_range_indicator {
	width: 60px !important;
	margin-left: 10px;
}

.sv_setting_box_shadow .sv_setting_preview {
	width: 50%;
    margin: 20px auto;
	text-align: center;
	box-shadow: <?php echo esc_attr( $value ); ?>;
}

.sv_setting_box_shadow .sv_setting_preview > * {
	margin: 0;
}
</style>
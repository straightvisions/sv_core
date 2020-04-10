<?php 
$tpl_path	= $this->get_path_core( 'settings/tpl/' . $this->run_type()->get_module_name() . '_tpl/' ); 
$props['value'] 		= $props['value'] ? $props['value'] : '0px 0px 0px 0px #000000';
$props['value']s		= explode( ' ', $props['value'] );
?>

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
		id="<?php echo $props['ID']; ?>"
		name="<?php echo $props['name']; ?>"
		type="hidden"
		value="<?php echo esc_attr( $props['value'] ); ?>"
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
	box-shadow: <?php echo esc_attr( $props['value'] ); ?>;
}

.sv_setting_box_shadow .sv_setting_preview > * {
	margin: 0;
}
</style>
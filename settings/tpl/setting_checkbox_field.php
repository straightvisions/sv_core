<?php
	$icon_disable = '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" class="svg-inline--fa fa-times fa-w-11" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg>';
	$icon_enable = '<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" class="svg-inline--fa fa-check fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>';
	$classes = 'sv_setting_checkbox'.($props['disabled'] ? ' sv_disabled' : '');
	/*if(strlen($props['title']) > 0){
		$props['title'] = '<div class="sv_setting_checkbox_title">'.$props['title'].'</div>';
	}*/

	if($sub && isset($props['value'][$sub])){
		$props['value'] = $props['value'][$sub];
	}
?>
<div class="<?php echo $classes; ?>">
	<input
		data-sv_type="sv_form_field"
		class="sv_input sv_input_off"
		id="<?php echo $props['ID']; ?>_off"
		name="<?php echo $props['name']; ?>"
		type="radio"
		value="0"
		<?php echo
	(($props['value'] == '' || $props['value'] == '0') ? ' checked="checked"' : '') . '
	' . $props['required'] . '
	' . $props['disabled']; ?>
	/>
	<label for="<?php echo $props['ID']; ?>_off " class="button" title="<?php _e( 'Disable', 'sv_core' ); ?>"><i class="icon_disable"><?php echo $icon_disable; ?></i></label>
	<input
		data-sv_type="sv_form_field"
		class="button sv_input sv_input_on"
		id="<?php echo $props['ID']; ?>_on"
		name="<?php echo $props['name']; ?>"
		type="radio"
		value="1"
<?php echo (($props['value'] != '' && $props['value'] != '0') ? ' checked="checked"' : '') . '
	' . $props['required'] . '
	' . $props['disabled']; ?>
	/>
	<label for="<?php echo $props['ID']; ?>_on" class="button" title="<?php _e( 'Enable', 'sv_core' ); ?>"><i class="icon_enable"><?php echo $icon_enable; ?></i></label>
	<?php //echo $props['title']; ?>
</div>
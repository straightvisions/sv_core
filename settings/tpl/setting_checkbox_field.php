<?php
	$classes = 'sv_setting_checkbox'.($props['disabled'] ? ' sv_disabled' : '');
	if(strlen($props['title']) > 0){
		$props['title'] = '<div class="sv_setting_checkbox_title">'.$props['title'].'</div>';
	}

	if($sub && isset($props['value'][$sub])){
		$props['value'] = $props['value'][$sub];
	}
?>
<div class="<?php echo $classes; ?>">
	<input
		data-sv_type="sv_form_field"
		class="sv_input sv_input_off"
		id="<?php echo $props['name']; ?>_off"
		name="<?php echo $props['name']; ?>"
		type="radio"
		value="0"
		<?php echo
	(($props['value'] == '' || $props['value'] == '0') ? ' checked="checked"' : '') . '
	' . $props['required'] . '
	' . $props['disabled']; ?>
	/>
	<label for="<?php echo $props['ID']; ?>_off" class="button"><i class="fa fa-times"></i></label>
	<input
		data-sv_type="sv_form_field"
		class="button sv_input sv_input_on"
		id="<?php echo $props['name']; ?>_on"
		name="<?php echo $props['name']; ?>"
		type="radio"
		value="1"
<?php echo (($props['value'] != '' && $props['value'] != '0') ? ' checked="checked"' : '') . '
	' . $props['required'] . '
	' . $props['disabled']; ?>
	/>
	<label for="<?php echo $props['name']; ?>_on" class="button"><i class="fa fa-check"></i></label>
	<?php echo $props['title']; ?>
</div>
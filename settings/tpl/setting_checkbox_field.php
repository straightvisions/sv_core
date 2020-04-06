<?php
	$classes = 'sv_setting_checkbox'.($disabled ? ' sv_disabled' : '');
	if(strlen($title) > 0){
		$title = '<div class="sv_setting_checkbox_title">'.$title.'</div>';
	}

	if($sub && isset($value[$sub])){
		$value = $value[$sub];
	}
?>
<div class="<?php echo $classes; ?>">
	<input
		data-sv_type="sv_form_field"
		class="sv_input sv_input_off"
		id="<?php echo $name; ?>_off"
		name="<?php echo $name; ?>"
		type="radio"
		value="0"
		<?php echo
	(($value == '' || $value == '0') ? ' checked="checked"' : '') . '
	' . $required . '
	' . $disabled; ?>
	/>
	<label for="<?php echo $ID; ?>_off" class="button"><i class="fa fa-times"></i></label>
	<input
		data-sv_type="sv_form_field"
		class="button sv_input sv_input_on"
		id="<?php echo $name; ?>_on"
		name="<?php echo $name; ?>"
		type="radio"
		value="1"
<?php echo (($value != '' && $value != '0') ? ' checked="checked"' : '') . '
	' . $required . '
	' . $disabled; ?>
	/>
	<label for="<?php echo $name; ?>_on" class="button"><i class="fa fa-check"></i></label>
	<?php echo $title; ?>
</div>
<label for="<?php echo $props['ID']; ?>">
	<input
		data-sv_type="sv_form_field"
		class="sv_input"
		id="<?php echo $props['ID']; ?>"
		name="<?php echo $props['name']; ?>"
		type="email"
		placeholder="<?php echo $props['placeholder']; ?>"
		value="<?php echo esc_attr($props['value']); ?>"
<?php echo ($props['maxlength'] ? 'maxlength="'.$props['maxlength'].'"' :  ''). '"
	' . $props['minlength'] . '
	' . $props['required'] . '
	' . $props['disabled']; ?> />
</label>
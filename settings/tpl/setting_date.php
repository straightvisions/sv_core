<h4><?php echo $title; ?></h4>
<label for="<?php echo $ID; ?>">
	<input
		data-sv_type="sv_form_field"
		class="sv_input"
		id="<?php echo $ID; ?>"
		name="<?php echo $name; ?>"
		type="date"
		value="<?php echo esc_attr($value); ?>"
<?php echo $required; ?>
<?php echo $disabled; ?> />
</label>
<div class="description"><?php echo $description; ?></div>
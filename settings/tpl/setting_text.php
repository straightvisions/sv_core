<h4><?php echo $title; ?></h4>
<label for="<?php echo $ID; ?>">
	<input
		data-sv_type="sv_form_field"
		class="sv_input"
		id="<?php echo $ID; ?>"
		name="<?php echo $name; ?>"
		type="text"
		placeholder="<?php echo $placeholder; ?>"
		value="<?php echo esc_attr($value); ?>"
<?php echo ($maxlength ? 'maxlength="'.$maxlength.'"' :  ''). '
	' . $minlength . '
	' . $required . '
	' . $disabled; ?>/>
</label>
<div class="description"><?php echo $description; ?></div>
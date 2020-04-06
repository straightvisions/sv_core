<h4><?php echo $title; ?></h4>
<label for="<?php echo $ID; ?>">
	<?php 
		if ( $this->get_is_units() ) { 
			$val_number = intval( esc_attr( preg_replace('/[^0-9]/', '', $value ) ) );
			$val_unit = esc_attr( preg_replace('/[0-9]+/', '', $value ) );
	?>
		<input
			class="sv_input"
			type="number"
			placeholder="<?php echo $placeholder; ?>"
			value="<?php echo $val_number; ?>"
			<?php echo $max ? 'max="'.$max.'"' : ''; ?>
            <?php echo $min ? 'min="'.$min.'"' : ''; ?>
			<?php 
				echo ( $maxlength ? 'maxlength="'.$maxlength.'"' :  '' ). '"
				' . $minlength . '
				' . $required . '
				' . $disabled; 
			?> 
		/>
	<select class="sv_input_units">
	<?php 
		foreach( $this->get_units() as $unit ) {
			echo '<option value="' . $unit . '"';
			echo $val_unit === $unit ? ' selected' : '';
			echo '>' . $unit . '</option>';
		}
	?>
	</select>
	<input
		data-sv_type="sv_form_field"
		class="sv_input"
		id="<?php echo $ID; ?>"
		name="<?php echo $name; ?>"
		type="hidden"
		value="<?php echo esc_attr( $value ); ?>"
	/>
	<?php } else { ?>
		<input
			data-sv_type="sv_form_field"
			class="sv_input"
			id="<?php echo $ID; ?>"
			name="<?php echo $name; ?>"
			type="number"
			placeholder="<?php echo $placeholder; ?>"
			value="<?php echo esc_attr($value); ?>"
			max="<?php echo $max; ?>"
			min="<?php echo $min; ?>"
			<?php 
				echo ($maxlength ? 'maxlength="'.$maxlength.'"' :  ''). '"
				' . $minlength . '
				' . $required . '
				' . $disabled; 
			?> 
		/>
	<?php } ?>
</label>
<div class="description"><?php echo $description; ?></div>
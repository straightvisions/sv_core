<h4><?php echo $title; ?></h4>
<label for="<?php echo $ID; ?>">
	<select
		data-sv_type="sv_form_field"
		class="sv_input"
		id="<?php echo $ID; ?>"
		name="<?php echo $name; ?>"
<?php echo $required; ?>
<?php echo $disabled; ?>>
<?php
	foreach( $this->get_parent()->get_options() as $o_value => $o_name ) {
		echo '<option
		' . ( ( $value == $o_value ) ? ' selected="selected"' : '' ) . '
		value="' . $o_value . '">' . $o_name . '</option>';
	}
?>
	</select>
</label>
<div class="description"><?php echo $description; ?></div>
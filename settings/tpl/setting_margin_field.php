<label for="<?php echo $ID; ?>_<?php echo $sub; ?>">
	<input
		data-sv_type="sv_form_field"
		class="sv_input"
		id="<?php echo $ID; ?>_<?php echo $sub; ?>"
		name="<?php echo $name; ?>[<?php echo $sub; ?>]"
		type="text"
		placeholder="<?php echo __($sub, 'sv_core'); ?>"
		value="<?php echo $value[$sub]; ?>"
		<?php echo  ($maxlength ? 'maxlength="'.$maxlength.'"' :  ''); ?>
		<?php echo $minlength; ?>
		<?php echo $required; ?>
		<?php echo $disabled; ?>
	/>
</label>
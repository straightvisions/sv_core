<label for="<?php echo $props['ID']; ?>_<?php echo $sub; ?>">
	<input
		data-sv_type="sv_form_field"
		class="sv_input"
		id="<?php echo $props['ID']; ?>_<?php echo $sub; ?>"
		name="<?php echo $props['name']; ?>[<?php echo $sub; ?>]"
		type="text"
		placeholder="<?php echo __($sub, 'sv_core'); ?>"
		value="<?php echo $props['value'][$sub]; ?>"
		<?php echo  ($props['maxlength'] ? 'maxlength="'.$props['maxlength'].'"' :  ''); ?>
		<?php echo $props['minlength']; ?>
		<?php echo $props['required']; ?>
		<?php echo $props['disabled']; ?>
	/>
</label>
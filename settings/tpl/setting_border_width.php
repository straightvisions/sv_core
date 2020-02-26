<h4><?php echo $title; ?></h4>
<div class="description"><?php echo $description; ?></div>

<style>
	.sv_setting_border_width{
		width:100%;
	}
	.sv_setting_border_width td{
		border:1px solid #000;
		min-width:50px;
		height:20px;
		text-align:center;
	}
	.sv_setting_border_width td label{
		margin:10px !important;
	}
	.sv_setting_border_width td input{
		text-align:center;
	}
</style>

<table class="sv_setting_border_width">
	<tr>
		<td colspan="3">
			<?php $this->print_sub_field($ID, $title, $description, $name, $value, $required, $disabled, $placeholder, $maxlength, $minlength, 'top'); ?>
		</td>
	</tr>
	<tr>
		<td>
			<?php $this->print_sub_field($ID, $title, $description, $name, $value, $required, $disabled, $placeholder, $maxlength, $minlength, 'left'); ?>
		</td>
		<td style="width:40%;height:100px;">Content</td>
		<td>
			<?php $this->print_sub_field($ID, $title, $description, $name, $value, $required, $disabled, $placeholder, $maxlength, $minlength, 'right'); ?>
		</td>
	</tr>
	<tr>
		<td colspan="3">
			<?php $this->print_sub_field($ID, $title, $description, $name, $value, $required, $disabled, $placeholder, $maxlength, $minlength, 'bottom'); ?>
		</td>
	</tr>
</table>
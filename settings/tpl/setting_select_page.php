<h4><?php echo $title; ?></h4>
<label for="<?php echo $ID; ?>">
<?php
	$args		= array(
	'echo'					=> 0,
	'selected'				=> $value,
	'name'					=> $name,
	'id'                    => $ID,
	'class'					=> 'data_sv_type_sv_form_field sv_input',
	'show_option_none'		=> __('No Page selected', 'sv_core')
	);
	echo wp_dropdown_pages($args);
?>
</label>
<div class="description"><?php echo $description ?></div>
<h4><?php echo $title; ?></h4>
<div class="description"><?php echo $description; ?></div>
<?php

if($this->has_options()) {
	foreach ( $this->get_options() as $o_value => $o_name ) {

		$new_ID = $new_name = $name.'['.$o_value.']';

		echo $this->print_sub_field($new_ID, $o_name, $description, $new_ID, $value, $required, $disabled, $placeholder, $maxlength, $minlength, $o_value);
	}
}else{
	$this->print_sub_field($ID, $title, $description, $name, $value, $required, $disabled, $placeholder, $maxlength, $minlength, $name);
}
?>
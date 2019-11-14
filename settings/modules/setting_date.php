<?php

namespace sv_core;

class setting_date extends settings{
    private $parent				= false;

    /**
     * @desc			initialize
     * @author			Dennis Heiden
     * @since			3.123
     * @ignore
     */
    public function __construct($parent=false){
        $this->parent			= $parent;
    }
    public function html($ID, $title, $description, $name, $value, $required, $disabled, $placeholder, $maxlength, $minlength, $max, $min){
        return '
				<h4>' . $title . '</h4>
				<label for="' . $ID . '">
					<input
					data-sv_type="sv_form_field"
					class="sv_input"
					id="' . $ID . '"
					name="' . $name . '"
					type="date"
					value="' . esc_attr($value) . '"
					' . $required . '
					' . $disabled . '/>
				</label>
				<div class="description">' . $description . '</div>';
    }
}
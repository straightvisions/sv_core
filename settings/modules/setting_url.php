<?php
	
	namespace sv_core;
	
	class setting_url extends settings{
		private $parent				= false;
		
		/**
		 * @desc			initialize
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct($parent=false){
			$this->parent			= $parent;
		}
		public function get($value,$format,$object){
			return $this->$format($value,$object);
		}
		public function widget($value,$object){
			return '
<p>
	<label for="' . $object->get_field_id($this->parent->get_ID()) . '">
		'.$this->parent->get_title().'
		<input
		class="widefat"
		id="' . $object->get_field_id($this->parent->get_ID()) . '"
		name="' . $object->get_field_name($this->parent->get_ID()) . '"
		type="url"
		value="' . esc_attr($value) . '"/>
		'.$this->parent->get_description().'
	</label>
</p>';
		}
		public function form(){
			return '
			<div>
				<label for="' . $this->get_field_id() . '">
					<input
					class="widefat"
					id="' . $this->get_field_id() . '"
					name="' . $this->get_field_id() . '"
					type="url"
					value="' . esc_attr(get_option($this->get_field_id())) . '"/>
					<p>'.$this->get_parent()->get_description().'</p>
				</label>
			</div>
			';
		}
	}
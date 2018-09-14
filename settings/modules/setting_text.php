<?php
	
	namespace sv_core;
	
	class setting_text extends settings{
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
		type="text"
		value="' . esc_attr($value) . '"/>
		'.$this->parent->get_description().'
	</label>
</p>';
		}
		public function form(){
			return '
			<p>
				<label for="' . $object->get_field_id($this->parent->get_ID()) . '">
					'.$this->parent->get_title().'
					<input
					class="widefat"
					id="' . $object->get_field_id($this->parent->get_ID()) . '"
					name="' . $object->get_field_name($this->parent->get_ID()) . '"
					type="text"
					value="' . esc_attr($value) . '"/>
					'.$this->parent->get_description().'
				</label>
			</p>
			';
		}
	}
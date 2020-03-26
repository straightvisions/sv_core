<?php
	
	namespace sv_core;
	
	class setting_number extends settings{
		private $parent	= false;
		
		/**
		 * @desc			initialize
		 * @author			Matthias Bathke
		 * @since			1.0
		 * @ignore
		 */
		public function __construct($parent=false){
			$this->parent = $parent;
		}

		public function sanitize($meta_value, $meta_key, $object_type) {
			return intval($meta_value);
		}
	}
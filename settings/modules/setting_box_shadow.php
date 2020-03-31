<?php

namespace sv_core;

class setting_box_shadow extends settings{
	private $parent	= false;

	public function __construct($parent=false){
		$this->parent = $parent;
	}
}
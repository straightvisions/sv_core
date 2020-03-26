<?php
namespace sv_core;

class setting_font extends settings {
	private $parent	= false;

	public function __construct( $parent = false ) {
		$this->parent = $parent;
	}
}
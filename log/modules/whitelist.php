<?php

namespace sv_core;

class whitelist extends log {
	protected $whitelist          = array();

	/**
	 * @desc			initialize
	 * @author			Matthias Bathke
	 * @since			1.5
	 * @ignore
	 */
	public function __construct() {
		require_once ( 'whitelist_entry.php' );
	}
	public function init() {
		foreach ( $this->get_instances_active() as $instance ) {
			$this->whitelist[ $instance->get_prefix() ] = new whitelist_entry;
		}
	}
	public function get_entry( string $name ): whitelist_entry {
		return $this->whitelist[ $name ];
	}
}
<?php
namespace sv_core;

class log_docs extends log {
	public function __construct() {
		$this->get_root()->set_section_title( 'Documentation' );
		$this->get_root()->set_section_type( 'docs' );
	}
	/**
	 * @desc			initialize actions and filters
	 * @return	void
	 * @author			Matthias Bathke
	 * @since			1.0
	 */
	public function init() {
		$this->get_parent()->add_section( $this )
		     ->set_section_template_path( $this->get_path_lib_core( 'log/backend/tpl/log_docs.php' ) );
	}
}
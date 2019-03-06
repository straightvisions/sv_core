<?php
namespace sv_core;

class log_list extends log {
	public function __construct() {
		$this->get_root()->set_section_title( 'Log' );
		$this->get_root()->set_section_desc( 'See all errors, warnings and other messages in a detailed log.' );
		$this->get_root()->set_section_type( 'tools' );
	}
	/**
	 * @desc			initialize actions and filters
	 * @return	void
	 * @author			Matthias Bathke
	 * @since			1.0
	 */
	public function init() {
		$this->get_parent()->add_section( $this )
		                   ->set_section_template_path( $this->get_path_core( 'log/backend/tpl/log_list.php' ) );
	}
}
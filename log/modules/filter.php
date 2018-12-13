<?php

namespace sv_core;

class filter extends log {
	private $filtered_logs                          = array();

	public function __construct() {
		$this->filtered_logs                        = $this->get_logs();
	}

	public function groups( $groups ): filter {
		$logs                                       = array();

		if( isset( $groups ) ) {
			if( is_array( $groups ) ) {
				foreach ( $groups as $group ) {
					if( is_string( $group ) ) {
						foreach ( $this->filtered_logs as $log ) {
							if( has_term( $group, 'sv_log_group', $log ) ) {
								array_push( $logs, $log );
							}
						}
					}
				}
			} else if( is_string( $groups ) ) {
				foreach ( $this->filtered_logs as $log ) {
					if( has_term( $groups, 'sv_log_group', $log ) ) {
						array_push( $logs, $log );
					}
				}
			}
		} else {
			foreach ( $this->filtered_logs as $log ) {
				if( has_term( $this->get_root()->get_prefix(), 'sv_log_group', $log ) ) {
					array_push( $logs, $log );
				}
			}
		}

		$this->filtered_logs                        = $logs;

		return $this;
	}

	public function states( $states ): filter {
		$logs                                       = array();

		if( is_array( $states ) ) {
			foreach ( $states as $state ) {
				if( is_integer( $state ) ) {
					foreach ( $this->filtered_logs as $log ) {
						if( has_term( $this->get_state_title( $state ), 'sv_log_state', $log ) ) {
							array_push( $logs, $log );
						}
					}
				}
			}
		} else if( is_integer( $states ) ) {
			foreach ( $this->filtered_logs as $log ) {
				if( has_term( $this->get_state_title( $states ), 'sv_log_state', $log ) ) {
					array_push( $logs, $log );
				}
			}
		}

		$this->filtered_logs                        = $logs;

		return $this;
	}

	public function output() {
		$logs                                       = $this->filtered_logs;
		$this->filtered_logs                        = $this->get_logs();

		return $logs;
	}
}
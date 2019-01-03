<?php

namespace sv_core;

class whitelist_entry {
	protected $success          = false;
	protected $info             = false;
	protected $warning          = false;
	protected $error            = false;
	protected $critical         = false;

	/**
	 * @desc			initialize
	 * @author			Matthias Bathke
	 * @since			1.5
	 * @ignore
	 */
	public function __construct() {

	}
	public function get_success(): bool {
		return $this->success;
	}
	public function set_success( bool $bool ): whitelist_entry {
		$this->success = $bool;

		return $this;
	}
	public function get_info(): bool {
		return $this->info;
	}
	public function set_info( bool $bool ): whitelist_entry {
		$this->info = $bool;

		return $this;
	}
	public function get_warning(): bool {
		return $this->warning;
	}
	public function set_warning( bool $bool ): whitelist_entry {
		$this->warning = $bool;

		return $this;
	}
	public function get_error(): bool {
		return $this->success;
	}
	public function set_error( bool $bool ): whitelist_entry {
		$this->error = $bool;

		return $this;
	}
	public function get_critical(): bool {
		return $this->success;
	}
	public function set_critical( bool $bool ): whitelist_entry {
		$this->critical = $bool;

		return $this;
	}
}
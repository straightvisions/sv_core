<?php
	namespace sv_core;

	class curl extends sv_abstract {
		private $handler             = false;

		/**
		 * @desc			initialize
		 * @author			Matthias Reuter
		 * @since			1.5
		 * @ignore
		 */
		public function __construct() {

		}
		public static function create( $parent ){
			$new						= new static();

			$new->prefix				= $parent->get_prefix() . '_';
			$new->set_root( $parent->get_root() );
			$new->set_parent( $parent );

			return $new;
		}
		public function get_handler() {
			if( !$this->handler ) {
				$this->set_handler()->get_handler();
			}

			return $this->handler;
		}
		private function set_handler() {
			if( function_exists( 'curl_init' ) && !$this->handler ) {
				$this->handler     = curl_init();
			} else {
				//@todo Add Error Message to Notices
			}

			return $this;
		}
		public function set_timeout( int $timeout ): curl {
			curl_setopt( $this->get_handler(), CURLOPT_TIMEOUT, $timeout );

			return $this;
		}
		public function set_returntransfer( bool $bool ): curl {
			curl_setopt( $this->get_handler(), CURLOPT_RETURNTRANSFER, $bool );

			return $this;
		}
		public function set_ssl_verifypeer( bool $bool ): curl {
			curl_setopt( $this->get_handler(), CURLOPT_SSL_VERIFYPEER, $bool );

			return $this;
		}
		public function set_url( string $url ): curl {
			curl_setopt( $this->get_handler(), CURLOPT_URL, $url );

			return $this;
		}
		public function set_userpwd( string $userpwd ): curl {
			curl_setopt( $this->get_handler(), CURLOPT_USERPWD, $userpwd );

			return $this;
		}
		public function set_ipresolve( int $ipresolve ): curl {
			curl_setopt( $this->get_handler(), CURLOPT_IPRESOLVE, $ipresolve );

			return $this;
		}

	}
<?php
	namespace sv_core;
	
	class remote_get extends sv_abstract {
		protected $url		= '';
		protected $args		= array();
		
		public function create( $parent ) {
			$new 			= new static();
			
			$new->prefix 	= $parent->get_prefix() . '_';
			$new->set_root( $parent->get_root() );
			$new->set_parent( $parent );
			
			return $new;
		}
		
		public function set_request_url( string $url ): remote_get {
			$this->url = $url;
			
			return $this;
		}
		
		public function get_request_url(): string {
			return $this->url;
		}
		
		public function set_args( array $args ): remote_get {
			$this->args = $args;
			
			return $this;
		}
		
		public function get_args(): array {
			return $this->args;
		}
		
		public function get_response(): array {
			return wp_remote_get( $this->get_request_url(), $this->get_args() );
		}
		
		public function get_response_body(): string {
			return wp_remote_retrieve_body( $this->get_response() );
		}
	}
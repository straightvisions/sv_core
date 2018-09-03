<?php
	
	namespace sv_core;
	
	class woocommerce extends notices{
		private $notice								= false;
		
		/**
		 * @desc			initialize
		 * @author			Matthias Reuter
		 * @since			1.0
		 * @ignore
		 */
		public function __construct($parent){
			$this->notice								= $parent;
		}
		public function order_note($order){
			if(function_exists('wc_get_order')){
				$order									= is_int($order) ? wc_get_order($order) : $order;
				
				if($order){
					$order->add_order_note($this->notice->output_full());
				}else{
					$error								= $this->create();
					$error->set_state(3);
					$error->set_title(__('WooCommerce Order Note creation failed.', $this->get_name()));
					$error->set_desc_public(__('Order does not exist', $this->get_name()));
				}
			}else{
				$error									= $this->create();
				$error->set_state(3);
				$error->set_title(__('WooCommerce Order Note creation failed.', $this->get_name()));
				$error->set_desc_public(__('WooCommerce is not available.', $this->get_name()));
			}
		}
	}
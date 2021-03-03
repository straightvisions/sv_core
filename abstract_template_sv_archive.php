<?php
	namespace sv_core;

	// this class could be part of different products
	if(!class_exists('abstract_template_sv_archive')) {
		// complete template logic should be here
		class abstract_template_sv_archive{
			protected $path				= '';
			protected $url				= '';

			protected $prefix			= '';

			protected $instance			= false;
			protected $setting_prefix	= '';

			protected $parts			= array();

			public function __construct($instance, string $setting_prefix){
				$this->init($instance, $setting_prefix);
			}
			protected function init($instance, string $setting_prefix): abstract_template_sv_archive{
				$this->parts		= array(
					'common'				=> array(
						'loaded'			=> true,
						'label'				=> __('Common', 'template_sv_archive_list')
					),
					'entry'					=> array(
						'loaded'			=> true,
						'label'				=> __('Entry', 'template_sv_archive_list')
					),
					'empty'					=> array(
						'loaded'			=> false,
						'label'				=> __('Empty', 'template_sv_archive_list')
					),
					'header'				=> array(
						'loaded'			=> false,
						'label'				=> __('Header', 'template_sv_archive_list')
					),
					'footer'				=> array(
						'loaded'			=> false,
						'label'				=> __('Footer', 'template_sv_archive_list')
					),
					'featured_image'		=> array(
						'loaded'			=> false,
						'label'				=> __('Featured Image', 'template_sv_archive_list')
					),
					'title'					=> array(
						'loaded'			=> false,
						'label'				=> __('Title', 'template_sv_archive_list')
					),
					'excerpt'				=> array(
						'loaded'			=> false,
						'label'				=> __('Excerpt', 'template_sv_archive_list')
					),
					'read_more'				=> array(
						'loaded'			=> false,
						'label'				=> __('Read More', 'template_sv_archive_list')
					),
					'author'				=> array(
						'loaded'			=> false,
						'label'				=> __('Author', 'template_sv_archive_list')
					),
					'date'					=> array(
						'loaded'			=> false,
						'label'				=> __('Date', 'template_sv_archive_list')
					),
					'date_modified'			=> array(
						'loaded'			=> false,
						'label'				=> __('Date Modified', 'template_sv_archive_list')
					),
					'categories'			=> array(
						'loaded'			=> false,
						'label'				=> __('Categories', 'template_sv_archive_list')
					)
				);

				$this->set_prefix(get_called_class())->set_instance($instance);

				// templates are always within this path structure: /path-to-instance/path-to-object/lib/template-dir/
				$this->set_path(trailingslashit($this->get_instance()->get_path('lib/'.$this->get_prefix())));
				$this->set_url(trailingslashit($this->get_instance()->get_url('lib/'.$this->get_prefix())));

				// $instance is a SV-instance extending the SV core
				$this->set_setting_prefix($setting_prefix)->load_settings();

				foreach($this->get_parts() as $part => $properties){
					$this->get_script($this->get_prefix($part))
						->set_path($this->get_path('lib/css/common/parts/'.$part.'.css'), true, $this->get_url('lib/css/common/parts/'.$part.'.css'));
				}

				$this->get_script('config')
					->set_path($this->get_path('lib/css/config/init.php'));

				$this->get_script('common')
					->set_path($this->get_path('lib/css/common/parts/common.css'));

				return $this;
			}
			protected function get_instance(){
				return $this->instance;
			}
			protected function set_instance($instance): abstract_template_sv_archive{
				$this->instance	= $instance;

				return $this;
			}
			public function get_setting_prefix( string $suffix = ''): string{
				if( strlen($this->setting_prefix) > 0 && strlen( $suffix ) > 0 ) {
					$suffix = '_' . $suffix;
				}

				return $this->setting_prefix . $suffix;
			}
			protected function set_setting_prefix(string $setting_prefix): abstract_template_sv_archive{
				$this->setting_prefix	= $setting_prefix;

				return $this;
			}
			protected function get_script( string $ID = ''){
				return $this->get_instance()->get_script($this->get_prefix($this->get_setting_prefix($ID)));
			}
			public function get_setting( string $ID = '', string $cluster = ''){
				return $this->get_instance()->get_setting($this->get_setting_prefix($ID), $cluster);
			}
			public function get_settings(){
				return $this->get_instance()->get_settings();
			}
			public function get_parts(): array{
				return $this->parts;
			}
			protected function set_part_loaded(string $part): abstract_template_sv_archive{
				$this->parts[$part]['loaded']	= true;

				return $this;
			}
			protected function set_path( string $path = ''): abstract_template_sv_archive {
				$this->path = $path;

				return $this;
			}
			protected function set_url( string $url = ''): abstract_template_sv_archive {
				$this->url = $url;

				return $this;
			}
			protected function get_path( string $suffix = ''): string {
				return $this->path . $suffix;
			}
			protected function get_url( string $suffix = ''): string {
				return $this->url . $suffix;
			}
			protected function set_prefix( string $prefix = ''): abstract_template_sv_archive{
				$this->prefix	= $prefix;

				return $this;
			}
			protected function get_prefix( string $suffix = ''): string {
				if( strlen( $suffix ) > 0 ) {
					$suffix = '_' . $suffix;
				}

				return $this->prefix . $suffix;
			}
			protected function get_post_class( $class = '', $post_id = null ): string{
				// Separates classes with a single space, collates classes for post DIV.
				return 'class="'. $this->get_prefix('entry') . ' ' . esc_attr( implode( ' ', get_post_class( $class, $post_id ) ) ) . '"';
			}
			protected function get_html(){
				ob_start();
				require($this->get_path('lib/tpl/frontend/loop.php'));
				return ob_get_clean();
			}
			public function get_output(): string{
				$output = $this->get_html();

				// register styles for each part in use
				foreach($this->get_parts() as $part => $properties){
					if($properties['loaded'] === true){
						$this->get_script($this->get_prefix($part))->set_is_enqueued();
					}
				}

				$script		= $this->get_script('config')
					->set_path(
						$this->get_instance()->get_path_cached($this->get_prefix().'/'.$this->get_setting_prefix().'/frontend.css'),
						true,
						$this->get_instance()->get_url_cached($this->get_prefix().'/'.$this->get_setting_prefix().'/frontend.css')
					)
					->set_is_enqueued();

				add_action( 'wp_footer', function() use($script) {
					$this->cache_config_css($script);
				}, 2 );

				return $output;
			}
			protected function get_part(string $part): string{
				if(boolval($this->get_setting('show_'.$part)->get_data()) !== true){
					return '';
				}

				$this->set_part_loaded($part);

				ob_start();
				require($this->get_path('lib/tpl/frontend/parts/'.$part.'.php'));
				return '<div class="'.$this->get_prefix($part).'">'.ob_get_clean().'</div>';
			}
			// We want to serve cached CSS depending on active configuration
			protected function cache_config_css(\sv_core\scripts $script): abstract_template_sv_archive{
				if ($script->get_css_cache_invalidated()) {
					ob_start();
					require($this->get_path('lib/css/config/init.php'));
					$css = ob_get_clean();

					file_put_contents($this->get_instance()->get_path_cached($this->get_prefix() . '/' . $this->get_setting_prefix() . '/frontend.css'), $css);

					$script->set_css_cache_invalidated(false);
				}
				return $this;
			}
			protected function load_settings(): abstract_template_sv_archive{
				// logic comes from child
				
				return $this;
			}
			protected function load_settings_extra_styles(): abstract_template_sv_archive{
				$this->get_instance()->get_setting( 'extra_styles' )
					->set_title( __( 'Extra Styles', 'template_sv_archive_pba_default' ) )
					->load_type( 'group' );

				$this->get_instance()->get_setting( 'extra_styles' )
					->run_type()
					->add_child()
					->set_ID( 'entry_label' )
					->set_title( __( 'Style Label', 'template_sv_archive_pba_default' ) )
					->set_description( __( 'A label to differentiate your Styles.', 'template_sv_archive_pba_default' ) )
					->load_type( 'text' )
					->set_placeholder( __( 'Label', 'template_sv_archive_pba_default' ) );

				$this->get_instance()->get_setting( 'extra_styles' )
					->run_type()
					->add_child()
					->set_ID( 'slug' )
					->set_title( __( 'Slug', 'template_sv_archive_pba_default' ) )
					->set_description( __( 'This slug is used for the helper classes.', 'template_sv_archive_pba_default' ) )
					->load_type( 'text' );

				foreach($this->get_settings() as $setting) {
					if(strpos($setting->get_ID(), 'extra_styles') !== false) {
						continue;
					}
					if(strpos($setting->get_ID(), 'archive_') !== 0) {
						continue;
					}
					if($setting->get_ID() != 'extra_styles') {
						$this->get_instance()->get_setting( 'extra_styles' )
							->run_type()
							->add_child($setting);
					}
				}

				return $this;
			}
		}
	}
<?php
/**
* Bizplan: Cusomizer
*
* @since 0.1
*/
require_once get_parent_theme_file_path( '/modules/customizer/framework/sanitize.php' );
require_once get_parent_theme_file_path( '/modules/customizer/framework/category-control.php' );
require_once get_parent_theme_file_path( '/modules/customizer/framework/post-control.php' );

if( ! class_exists( 'Bizplan_Customizer' ) ):

	class Bizplan_Customizer{

		/**
		* Stores the user supplied form fields
		*
		* @since  Bizplan 0.1
		* @access public
		* @var    array 
		*/
		public $fields = array();

		/**
		* Stores the wp_customize object for later use inside the class
		* 
		* @since  Bizplan 0.1
		* @access protected
		* @var    object
		*/
		protected $customize;

		/**
		* Stores all the user's sections
		* 
		* @since  Bizplan 0.1
		* @access protected
		* @var    array
		*/
		protected $sections = array();

		# Some Default sections ids in WordPress 
		# 'title_tagline', 
		# 'colors', 
		# 'header_image', 
		# 'background_image', 
		# 'nav', 
		# 'static_front_page'

		/**
		* Stores all the user's panels
		* 
		* @since  Bizplan 0.1
		* @access protected
		* @var    array
		*/
		protected $panels = array();

		/**
		* Stores all the default values
		* 
		* @since  Bizplan 0.1
		* @access public
		* @var    array
		*/
		public $defaults = array();

		/**
		* Stores class instance
		* 
		* @since  Bizplan 0.1
		* @access protected
		* @var    object
		*/
	    protected static $instance = NULL;


	    /**
		* Retrives the instance of this class
		* 
		* @since  Bizplan 0.1
		* @access public
		* @return object
		*/
		public static function get_instance() {

			if ( ! self::$instance ) {
				self::$instance = new self();
			}

			return self::$instance;
	    } 

	    /**
		* Retrives all default values
		* 
		* @since  Bizplan 0.1
		* @uses   filter "bizplan_customizer_defaults"
		* @access public
		* @return array
		*/
	    public function get_defaults(){

	    	return apply_filters( 'bizplan_customizer_defaults', array() );
	    }

	    /**
		* Retrives value by id
		* 
		* @since  Bizplan 0.1
		* @uses   get_defaults()
		* @uses   filter "bizplan_customizer_fields"
		* @access public
		* @param  string $id
		* @return string | false
		*/
		public function get( $id ){

			$fields   = apply_filters( 'bizplan_customizer_fields', array() );
			$defaults = self::get_defaults();

			if( isset( $defaults[ $id ] ) ){
				$default = $defaults[ $id ];
			}else{
				$default = false;
			}

			return get_theme_mod( $id, $default );
		}

		/**
		* Registers Customizer's panels, sections, controls, settings
		* 
		* @since  Bizplan 0.1
		* @uses   add_panels()
		* @uses   add_sections()
		* @uses   add_setting()
		* @uses   add_control()
		* @access public
		* @param  object $wp_customize
		* @return void
		*/
		public function register( $wp_customize ){

			do_action( 'bizplan_customize_register', $wp_customize );

			$this->customize = $wp_customize;

			$this->fields   = apply_filters( 'bizplan_customizer_fields', array() );
			$this->panels   = apply_filters( 'bizplan_customizer_panels', $this->panels );
			$this->sections = apply_filters( 'bizplan_customizer_sections', $this->sections );

			$this->defaults = apply_filters( 'bizplan_customizer_defaults', $this->defaults );
			
			$this->add_panels();

			$this->add_sections();

			foreach( $this->fields as $key => $field ){

				$field[ 'id' ] = $key;

				switch ( $field[ 'type' ] ) {
					
					case 'url':

						if( ! isset( $field[ 'sanitize_callback' ] ) || empty( $field[ 'sanitize_callback' ] ) ){
							$field[ 'sanitize_callback' ] = 'esc_url_raw';
						}

						break;

					case 'email':

						if( ! isset( $field[ 'sanitize_callback' ] ) || empty( $field[ 'sanitize_callback' ] ) ){
							$field[ 'sanitize_callback' ] = 'sanitize_email';
						}

						break;

					case 'number':

						if( ! isset( $field[ 'sanitize_callback' ] ) || empty( $field[ 'sanitize_callback' ] ) ){
							$field[ 'sanitize_callback' ] = 'bizplan_sanitize_number';
						}

						break;

					case 'checkbox':

						if( ! isset( $field[ 'sanitize_callback' ] ) || empty( $field[ 'sanitize_callback' ] ) ){
							$field[ 'sanitize_callback' ] = 'bizplan_sanitize_checkbox';
						}

						break;

					case 'text':

						if( ! isset( $field[ 'sanitize_callback' ] ) || empty( $field[ 'sanitize_callback' ] ) ){
							$field[ 'sanitize_callback' ] = 'sanitize_text_field';
						}

						break;
					case 'select':	
					case 'radio':

						if( ! isset( $field[ 'sanitize_callback' ] ) || empty( $field[ 'sanitize_callback' ] ) ){
							$field[ 'sanitize_callback' ] = 'bizplan_sanitize_choice';
						}

						break;

					case 'textarea':

						if( ! isset( $field[ 'sanitize_callback' ] ) || empty( $field[ 'sanitize_callback' ] ) ){
							$field[ 'sanitize_callback' ] = 'wp_kses_post';
						}
						
						break;

					case 'colors':

						if( ! isset( $field[ 'sanitize_callback' ] ) || empty( $field[ 'sanitize_callback' ] ) ){
							$field[ 'sanitize_callback' ] = 'sanitize_hex_color';
						}

						break;

					case 'dropdown-pages':
					case 'dropdown-posts':
					case 'dropdown-categories':

						if( ! isset( $field[ 'sanitize_callback' ] ) || empty( $field[ 'sanitize_callback' ] ) ){
							$field[ 'sanitize_callback' ] = 'absint';
						}

						break;
				}

				$this->add_setting( $field );
				$this->add_control( $field );
			}
		}

		/**
		* adds Customizer's panels.
		* 
		* @since  Bizplan 0.1
		* @access protected
		* @link   https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_panel 
		* @return void
		*/
		protected function add_panels(){

			foreach ( $this->panels as $id => $panel ) {

				$args = array(
				    'title' => empty( $panel[ 'title' ] ) ? esc_html( 'No Title Specified.' ) : $panel[ 'title' ],
				);

				if( isset( $panel[ 'description' ] ) ){
					$args[ 'description' ] = $panel[ 'description' ];
				}

				if( isset( $panel[ 'priority' ] ) ){
					$args[ 'priority' ] = $panel[ 'priority' ];
				}

				if( isset( $panel[ 'active_callback' ] ) ) {
		            $args[ 'active_callback' ] = $panel[ 'active_callback' ];
		        }

		        if( isset( $panel[ 'theme_supports' ] ) ) {
		            $args[ 'theme_supports' ] = $panel[ 'theme_supports' ];
		        }

		        if( isset( $panel[ 'capability' ] ) ) {
		            $args[ 'capability' ] = $panel[ 'capability' ];
		        }

				$this->customize->add_panel( $id, $args );
			}
		}

		/**
		* adds Customizer's sections.
		* 
		* @since  Bizplan 0.1
		* @access protected
		* @link   https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_section 
		* @return void
		*/
		protected function add_sections(){

			foreach ( $this->sections as $id => $section ){

				$args = array(
					'title' => empty( $section[ 'title' ] ) ? esc_html( 'No Title Specified.' ) : $section[ 'title' ],
				);

				if( isset( $section[ 'priority' ] ) ){
					$args[ 'priority' ] = $section[ 'priority' ];
				}

				if( isset( $section[ 'description' ] ) ){
					$args[ 'description' ] = $section[ 'description' ];
				}

				if( isset( $section[ 'active_callback' ] ) ){
					$args[ 'active_callback' ] = $section[ 'active_callback' ];
				}

				if( isset( $section[ 'panel' ] ) ){
					$args[ 'panel' ] = $section[ 'panel' ];
				}
				
				$this->customize->add_section( $id, $args );
			}
		}

		/**
		* adds Customizer's setting.
		* 
		* @since  Bizplan 0.1
		* @access protected
		* @link   https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_setting
		* @return void
		*/
		protected function add_setting( $field ){

			$args = array();

			if( isset( $this->defaults[ $field[ 'id' ] ] ) )
				$args[ 'default' ] = $this->defaults[ $field[ 'id' ] ];
			else
				$args[ 'default' ] = '';

			if( isset( $field[ 'setting_type' ] ) || !empty( $field[ 'setting_type' ] ) ){
				$args[ 'type' ] = $field[ 'setting_type' ];
			}

			if( isset( $field[ 'capability' ] ) && !empty( $field[ 'capability' ] ) ){
				$args[ 'capability' ] = $field[ 'capability' ];
			}

			if( isset( $field[ 'theme_supports' ] ) && !empty( $field[ 'theme_supports' ] ) ){
				$args[ 'theme_supports' ] = $field[ 'theme_supports' ];
			}

			if( isset( $field[ 'transport' ] ) || !empty( $field[ 'transport' ] ) ){
				$args[ 'transport' ] = $field[ 'transport' ];
			}

			if( isset( $field[ 'sanitize_callback' ] ) && !empty( $field[ 'sanitize_callback' ] ) ){
				$args[ 'sanitize_callback' ] = $field[ 'sanitize_callback' ];
			}

			if( isset( $field[ 'sanitize_js_callback' ] ) && !empty( $field[ 'sanitize_js_callback' ] ) ){
				$args[ 'sanitize_js_callback' ] = $field[ 'sanitize_js_callback' ];
			}
			
			$this->customize->add_setting( $field[ 'id' ], $args );
		}

		/**
		* adds Customizer's control.
		* 
		* @since  Bizplan 0.1
		* @access protected
		* @link   https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
		* @return void
		*/
		protected function add_control( $field ){

			$args = array();

			if( isset( $field[ 'label' ] ) && !empty( $field[ 'label' ] ) ){
				$args[ 'label' ] = $field[ 'label' ];
			}

			if( isset( $field[ 'description' ] ) && !empty( $field[ 'description' ] ) ){
				$args[ 'description' ] = $field[ 'description' ];
			}

			if( isset( $field[ 'section' ] ) && !empty( $field[ 'section' ] ) ){
				$args[ 'section' ] = $field[ 'section' ];
			}

			if( isset( $field[ 'priority' ] ) && !empty( $field[ 'priority' ] ) ){
				$args[ 'priority' ] = $field[ 'priority' ];
			}

			if( isset( $field[ 'active_callback' ] ) ) {
	            $args[ 'active_callback' ] = $field[ 'active_callback' ];
	        }

			if( isset( $field[ 'settings' ] ) && !empty( $field[ 'settings' ] ) ){
				$args[ 'settings' ] = $field[ 'settings' ];
			}

			if( isset( $field[ 'choices' ] ) && is_array( $field[ 'choices' ] ) ){
				$args[ 'choices' ] = $field[ 'choices' ];
			}

			if( isset( $field[ 'height' ] ) && !empty( $field[ 'height' ] ) ){
				$args[ 'height' ] = $field[ 'height' ];
			}

			if( isset( $field[ 'width' ] ) && !empty( $field[ 'width' ] ) ){
				$args[ 'width' ] = $field[ 'width' ];
			}

			if( isset( $field[ 'input_attrs' ] ) && !empty( $field[ 'input_attrs' ] ) ){
				$args[ 'input_attrs' ] = $field[ 'input_attrs' ];
			}

			$control_id = $field[ 'id' ];

			switch( $field[ 'type' ] ){

				case 'colors':
						
					unset( $args[ 'type' ] );

					$this->customize->add_control( new WP_Customize_Color_Control( 
						$this->customize, 
						$control_id, 
						$args
					));

					break;

				case 'file':

					$this->customize->add_control( new WP_Customize_Upload_Control( 
						$this->customize, 
						$control_id, 
						$args
					) );

					break;	

				case 'image':

					$this->customize->add_control( new WP_Customize_Image_Control(
			           $this->customize,
			           $control_id,
			           $args
			        ) );

					break;

				case 'dropdown-categories':

					$this->customize->add_control( new Bizplan_Customize_Category_Control( 
						$this->customize, 
						$control_id, 
						$args 
					) );

					break;

				case 'dropdown-posts':

					$this->customize->add_control( new Bizplan_Customize_Post_Control( 
						$this->customize, 
						$control_id, 
						$args 
					) );

					break;

				default:

					if( isset( $field[ 'type' ] ) && !empty( $field[ 'type' ] ) ){
						$args[ 'type' ] = $field[ 'type' ];
					}else{
						$args[ 'type' ] = 'text';
					}

					$this->customize->add_control( $control_id, $args );

					break;
			}
		}
	}

endif;

/**
* Retrives customizer setting by id.
* 
* @since  Bizplan 0.1
* @uses   Bizplan_Customizer::get_instance()->get( $id )
* @return string | bool
*/
if( !function_exists( 'bizplan_get_option' ) ){

	function bizplan_get_option( $id ){

		return Bizplan_Customizer::get_instance()->get( $id );
	}

}

/**
* Retrives default value of Bizplan_Customizer.
* 
* @since  Bizplan 0.1
* @uses   Bizplan_Customizer::get_instance()->get_defaults()
* @return object
*/
if( !function_exists( 'bizplan_customizer_get_defaults' ) ){

	function bizplan_customizer_get_defaults(){

		return Bizplan_Customizer::get_instance()->get_defaults();
	}

}

add_action( 'customize_register', array ( Bizplan_Customizer::get_instance(), 'register' ) );

<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

/**
 * Class RTEC_Form
 */
class RTEC_Form
{
	/**
	 * @var RTEC_Form
	 * @since 1.0
	 */
    private static $instance;

	/**
	 * @var array
	 * @since 1.0
	 */
	private $event_meta;

	/**
	 * @var int
	 */
	private $event_form = 1;

	/**
	 * @var array
	 */
	private $form_field_attributes = array();

	/**
	 * @var array
	 */
	private $event_form_field_names = array();

	/**
	 * @var array
	 */
	private $event_form_required_field_names = array();

	/**
	 * @var array
	 */
	private $custom_fields_label_name_pairs = array();

	/**
	 * @var array
	 */
	private $custom_column_keys = array();

	/**
	 * @var array
	 */
	private $column_keys = array();

	/**
	 * @var array
	 */
	private $field_labels = array();

	/**
	 * @var
	 */
	private $hidden_initially;

	/**
	 * @var array
	 * @since 1.0
	 */
	private $submission_data = array();

	/**
	 * @var array
	 * @since 1.0
	 */
	private $errors = array();

	/**
	 * @var int
	 * @since 1.0
	 */
	private $max_registrations;

	/**
	 * @var array
	 * @since 1.1
	 */
	private $ical_url;

	/**
	 * Get the one true instance of RTEC_Form.
	 *
	 * @since  1.0
	 * @return object $instance
	 */
	static public function instance()
	{
		if ( !self::$instance ) {
			self::$instance = new RTEC_Form();
		}
		return self::$instance;
	}

	/**
	 * Creates the basic form object based on the event id
	 *
	 * @param string $event_id
	 */
	public function build_form( $event_id = '' )
	{
		if ( $event_id !== '' ) {
			$this->set_event_meta( $event_id );
		}

		$fields_results = $this->get_form_field_data_from_db();
		$manually_added_fields = array();
		$manually_added_fields = apply_filters( 'rtec_add_new_field', $manually_added_fields );
		$this->set_form_field_attributes( $fields_results, $manually_added_fields );
	}

	/**
	 * Simulates the raw results if they were retrieved from the database in "Pro"
	 *
	 * @param $fields_results
	 * @param $manually_added_fields
	 */
	protected function set_form_field_attributes( $fields_results, $manually_added_fields )
	{
		global $rtec_options;
		$show_fields = $fields_results;
		$field_attributes = array();
		$show_field_names = array();
		$required_field_names = array();

		foreach ( $show_fields as $field ) {

			$field_attributes[ $field['field_name'] ]['name']    = 'rtec_' . $field['field_name'];
			$show_field_names[] = $field['field_name'];
			$field_attributes[ $field['field_name'] ]['type']    = isset( $field['field_type'] ) ? $field['field_type'] : 'text';
			$field_attributes[ $field['field_name'] ]['label']   = isset( $field['label'] ) ? $field['label'] : '';
			$field_attributes[ $field['field_name'] ]['default'] = isset( $field['default_value'] ) ? $field['default_value'] : '';
			$field_attributes[ $field['field_name'] ]['error_message'] = isset( $field['error_text'] ) ? $field['error_text'] : __( 'This is required', 'registrations-for-the-events-calendar' );
			$field_attributes[ $field['field_name'] ]['placeholder']   = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
			$field_attributes[ $field['field_name'] ]['meta']   = isset( $field['meta'] ) ? maybe_unserialize( $field['meta'] ) : array();
			$field_attributes[ $field['field_name'] ]['html']  = isset( $field['meta']['html'] ) ? $field['meta']['html'] : '';

			if ( $field_attributes[ $field['field_name'] ]['type'] === 'checkbox' || $field_attributes[ $field['field_name'] ]['type'] === 'radio' || $field_attributes[ $field['field_name'] ]['type'] === 'select') {
				$field_attributes[ $field['field_name'] ]['meta']['options'] = isset( $field_attributes[ $field['field_name'] ]['meta']['options'] ) ? $field_attributes[ $field['field_name'] ]['meta']['options'] : array();
			}

			if ( $field['is_required'] == 1 ) {
				$required_field_names[] = $field['field_name'];
				$field_attributes[ $field['field_name'] ]['required'] = true;
				$field_attributes[ $field['field_name'] ]['label'] .= '&#42;';
				$field_attributes[ $field['field_name'] ]['data_atts'] = array(
					'aria-required' => 'true',
					'aria-invalid'  => 'false'
				);

			} else {
				$field_attributes[ $field['field_name'] ]['required'] = false;
				$field_attributes[ $field['field_name'] ]['data_atts'] = array(
					'aria-required' => 'false',
					'aria-invalid'  => 'false'
				);
				$field_attributes[ $field['field_name'] ]['valid_type']   = 'none';
				$field_attributes[ $field['field_name'] ]['valid_params'] = array();

			}

			if ( in_array( $field_attributes[ $field['field_name'] ]['type'], array( 'checkbox', 'radio', 'select' ), true ) ) {
				$field_attributes[ $field['field_name'] ]['valid_type'] = 'none';
			} else {
				$field_attributes[ $field['field_name'] ]['valid_type']   = isset( $field['valid_type'] ) ? $field['valid_type'] : 'length';
			}

			switch ( $field_attributes[ $field['field_name'] ]['valid_type'] ) {
				case 'count' :
					$field_attributes[ $field['field_name'] ]['valid_params'] = isset( $field['valid_params'] ) && ! empty( $field['valid_params'] ) ? $field['valid_params'] : array( 'count' => '7,10', 'count_what' => 'numbers' );
					break;
				case 'email' :
					$field_attributes[ $field['field_name'] ]['valid_params'] = array( 'email' => 'true' );
					break;
				default :
					$field_attributes[ $field['field_name'] ]['valid_params'] = isset( $field['valid_params'] ) && ! empty( $field['valid_params'] ) ? $field['valid_params'] : array( 'min' => 1, 'max' => 'no-max' );
			}

			// recaptcha stuff to be changed
			if ( $field_attributes[ $field['field_name'] ]['valid_type'] === 'recaptcha' ) {
				$field_attributes[ $field['field_name'] ]['required'] = true;
				$value1 = rand(2,5);
				$value2 = rand(2,5);
				$field_attributes[ $field['field_name'] ]['valid_params'] = array(
					'value_1' => $value1,
					'value_2' => $value2
				);
				$field_attributes[ $field['field_name'] ]['valid_params']['sum'] = (int)$field_attributes[$field['field_name']]['valid_params']['value_1'] + (int)$field_attributes[ $field['field_name'] ]['valid_params']['value_2'];
				$recaptcha_label = isset( $rtec_options['recaptcha_label'] ) ? $rtec_options['recaptcha_label'] : __( 'What is', 'registrations-for-the-events-calendar' );
				$field_attributes[ $field['field_name'] ]['label'] = trim( rtec_get_text( $recaptcha_label, __( 'What is', 'registrations-for-the-events-calendar' ) ) ) . ' ' . $field_attributes['recaptcha']['valid_params']['value_1'] . ' &#43; ' . $field_attributes['recaptcha']['valid_params']['value_2'] .'&#42;';
				$recaptcha_error = isset( $rtec_options['recaptcha_error'] ) ? $rtec_options['recaptcha_error'] : __( 'Please try again', 'registrations-for-the-events-calendar' );
				$field_attributes[ $field['field_name'] ]['error_message'] = rtec_get_text( $recaptcha_error, __( 'Please try again', 'registrations-for-the-events-calendar' ) );
			}

			$standard_fields = rtec_get_standard_form_fields();
			$no_template_fields = rtec_get_no_template_fields();
			$no_backend_label_fields = rtec_get_no_backend_column_fields();

			if ( ! in_array( $field['field_name'], $standard_fields, true ) &&  ! in_array( $field['field_name'], $no_template_fields, true ) ) {
				$this->add_custom_label_name_pair( $field['label'], $field['field_name'] );
			}

			if ( ! in_array( $field['field_name'], $standard_fields, true ) && ! in_array( $field['field_name'], $no_backend_label_fields, true ) ) {
				$this->add_custom_column_key( $field['field_name'] );
			}

			if ( ! in_array( $field['field_name'], $no_backend_label_fields, true ) ) {
				$field_name = $field['field_name'] === 'first' || $field['field_name'] === 'last' ? $field['field_name'] . '_name' : $field['field_name'];
				$this->add_column_key( $field_name );
			}

			if ( ! in_array( $field['field_name'], $no_backend_label_fields, true ) ) {
				$this->add_field_label( $field['label'] );
			}


		}

		$manual_to_merge = ! empty( $manually_added_fields ) ? $manually_added_fields : array();
		$field_attributes = array_merge( $field_attributes, $manual_to_merge );

		if ( $rtec_options['message_source'] === 'translate' ) {
			if ( isset( $field_attributes['first'] ) ) {
				$field_attributes['first']['label'] = __( 'First', 'registrations-for-the-events-calendar' );
				$field_attributes['first']['label'] .= in_array( 'first', $required_field_names ) ? '&#42;' : '';
				$field_attributes['first']['error_message'] = __( 'Please enter your first name', 'registrations-for-the-events-calendar' );
			}
			if ( isset( $field_attributes['last'] ) ) {
				$field_attributes['last']['label'] = __( 'Last', 'registrations-for-the-events-calendar' );
				$field_attributes['last']['label'] .= in_array( 'last', $required_field_names ) ? '&#42;' : '';
				$field_attributes['last']['error_message'] = __( 'Please enter your last name', 'registrations-for-the-events-calendar' );
			}
			if ( isset( $field_attributes['email'] ) ) {
				$field_attributes['email']['label'] = __( 'Email', 'registrations-for-the-events-calendar' );
				$field_attributes['email']['label'] .= in_array( 'email', $required_field_names ) ? '&#42;' : '';
				$field_attributes['email']['error_message'] = __( 'Please enter a valid email address', 'registrations-for-the-events-calendar' );
			}
			if ( isset( $field_attributes['other'] ) ) {
				$field_attributes['other']['label'] = __( 'Other', 'registrations-for-the-events-calendar' );
				$field_attributes['other']['label'] .= in_array( 'other', $required_field_names ) ? '&#42;' : '';
				$field_attributes['other']['error_message'] = __( 'This is required', 'registrations-for-the-events-calendar' );
			}
			if ( isset( $field_attributes['phone'] ) ) {
				$field_attributes['phone']['label'] = __( 'Phone', 'registrations-for-the-events-calendar' );
				$field_attributes['phone']['label'] .= in_array( 'phone', $required_field_names ) ? '&#42;' : '';
				$field_attributes['phone']['error_message'] = __( 'Please enter a valid phone number', 'registrations-for-the-events-calendar' );
			}
			if ( isset( $field_attributes['guests'] ) ) {
				$field_attributes['guests']['label'] = __( 'Guests', 'registrations-for-the-events-calendar' );
				$field_attributes['guests']['label'] .= in_array( 'guests', $required_field_names ) ? '&#42;' : '';
				$field_attributes['guests']['error_message'] = __( 'This is required', 'registrations-for-the-events-calendar' );
			}
		}

		$field_attributes = apply_filters( 'rtec_fields_attributes', $field_attributes );
		$this->event_form_field_names = $show_field_names;
		$this->event_form_required_field_names = $required_field_names;

		$this->form_field_attributes = $field_attributes;
	}

	/**
	 * Used to display custom fields in the admin area and process form submissions
	 *
	 * @param $label
	 * @param $name
	 *
	 * @since 2.0
	 */
	private function add_custom_label_name_pair( $label, $name )
	{
		$this->custom_fields_label_name_pairs[ $label ] = $name;
	}

	/**
	 * @param $field_name
	 *
	 * @since 2.0
	 */
	private function add_custom_column_key( $field_name )
	{
		$this->custom_column_keys[] = $field_name;
	}

	/**
	 * @param $field_name
	 *
	 * @since 2.0
	 */
	private function add_column_key( $field_name )
	{
		$this->column_keys[] = $field_name;
	}

	/**
	 * @param $label
	 *
	 * @since 2.0
	 */
	private function add_field_label( $label )
	{
		$this->field_labels[] = $label;
	}

	/**
	 * Set user input errors for the form
	 *
	 * @param array $errors names of fields that have validation errors
	 * @since 1.0
	 */
	public function set_errors( $errors )
	{
		$this->errors = $errors;
	}

	/**
	 * @param array $submission submitted data from user
	 * @since 1.0
	 */
	public function set_submission_data( $submission )
	{
		$this->submission_data = $submission;
	}

	/**
	 * @param string $id    optional manual input of post ID
	 * @since 1.0
	 */
	public function set_event_meta( $id = '' )
	{
		$this->event_meta = rtec_get_event_meta( $id );
	}

	/**
	 * Get final data array of all fields that are going to be used in the
	 * registration form
	 *
	 * @since   1.0
	 */
	public function get_field_attributes()
	{
		return $this->form_field_attributes;
	}

	/**
	 * @return array
	 */
	public function get_custom_column_keys()
	{
		return $this->custom_column_keys;
	}

	/**
	 * @return array
	 */
	public function get_column_keys()
	{
		return $this->column_keys;
	}

	/**
	 * @return array
	 */
	public function get_field_labels()
	{
		return $this->field_labels;
	}

	/**
	 * @return array
	 */
	public function get_custom_fields_label_name_pairs()
	{
		return $this->custom_fields_label_name_pairs;
	}

	/**
	 * @return array
	 */
	public function get_form_field_data_from_db()
	{
		global $rtec_options;

		if ( isset( $rtec_options[ 'first_show' ] ) ) {
			$field_types = rtec_get_standard_form_fields();

			if ( isset( $rtec_options['custom_field_names'] ) && ! is_array( $rtec_options['custom_field_names'] ) ) {
				$custom_field_names = explode( ',', $rtec_options['custom_field_names'] );
			} else {
				$custom_field_names = array();
			}

			foreach ( $custom_field_names as $custom_field_name ) {
				$field_types[] = $custom_field_name;
			}

			$i = 0;
			$fields_data = array();

			foreach ( $field_types as $type ) {

				if ( $rtec_options[ $type . '_show' ] ) {
					$fields_data[ $i ] = array();

					$fields_data[ $i ]['field_name'] = $type;
					$fields_data[ $i ]['field_type'] = 'text';

					if ( $type === 'phone' ) {
						$fields_data[ $i ]['valid_type'] = 'count';
						$fields_data[ $i ]['valid_params'] = isset( $rtec_options['phone_valid_count'] ) ? array( 'count' => $rtec_options['phone_valid_count'], 'count_what' => 'numbers' ) :  array( 'count' => '7,10', 'count_what' => 'numbers' );
					} elseif ( $type === 'email' ) {
						$fields_data[ $i ]['valid_type'] = 'email';
						$fields_data[ $i ]['valid_params'] = array();
					} else {
						$fields_data[ $i ]['valid_type'] = 'length';
						$fields_data[ $i ]['valid_params'] = array();
					}

					$fields_data[ $i ]['placeholder'] = '';
					$fields_data[ $i ]['meta'] = '';
					$fields_data[ $i ]['default_value'] = '';
					$fields_data[ $i ]['is_required'] = $rtec_options[ $type . '_require' ];

					switch( $type ) {
						case 'first':
							$first_label = isset( $rtec_options['first_label'] ) ? $rtec_options['first_label'] : __( 'First', 'registrations-for-the-events-calendar' );
							$fields_data[ $i ]['label'] = rtec_get_text( $first_label, __( 'First', 'registrations-for-the-events-calendar' ) );
							$error_text = isset( $rtec_options[ $type . '_error' ] ) ? $rtec_options[ $type . '_error' ] : __( 'Please enter your first name', 'registrations-for-the-events-calendar' );
							$fields_data[ $i ]['error_text'] = rtec_get_text( $error_text, __( 'Please enter your first name', 'registrations-for-the-events-calendar' ) );
							break;
						case 'last':
							$last_label = isset( $rtec_options['last_label'] ) ? $rtec_options['last_label'] : __( 'Last', 'registrations-for-the-events-calendar' );
							$fields_data[ $i ]['label'] = rtec_get_text( $last_label, __( 'Last', 'registrations-for-the-events-calendar' ) );
							$error_text = isset( $rtec_options[ $type . '_error' ] ) ? $rtec_options[ $type . '_error' ] : __( 'Please enter your last name', 'registrations-for-the-events-calendar' );
							$fields_data[ $i ]['error_text'] = rtec_get_text( $error_text, __( 'Please enter your last name', 'registrations-for-the-events-calendar' ) );
							break;
						case 'email':
							$email_label = isset( $rtec_options['email_label'] ) ? $rtec_options['email_label'] : __( 'Email', 'registrations-for-the-events-calendar' );
							$fields_data[ $i ]['label'] = rtec_get_text( $email_label, __( 'Email', 'registrations-for-the-events-calendar' ) );
							$error_text = isset( $rtec_options[ $type . '_error' ] ) ? $rtec_options[ $type . '_error' ] : __( 'Please enter a valid email address', 'registrations-for-the-events-calendar' );
							$fields_data[ $i ]['error_text'] = rtec_get_text( $error_text, __( 'Please enter a valid email address', 'registrations-for-the-events-calendar' ) );
							break;
						case 'phone':
							$phone_label = isset( $rtec_options['phone_label'] ) ? $rtec_options['phone_label'] : __( 'Phone', 'registrations-for-the-events-calendar' );
							$fields_data[ $i ]['label'] = rtec_get_text( $phone_label, __( 'Phone', 'registrations-for-the-events-calendar' ) );
							$error_text = isset( $rtec_options[ $type . '_error' ] ) ? $rtec_options[ $type . '_error' ] : __( 'Please enter a valid phone number', 'registrations-for-the-events-calendar' );
							$fields_data[ $i ]['error_text'] = rtec_get_text( $error_text, __( 'Please enter a valid phone number', 'registrations-for-the-events-calendar' ) );
							break;
						default:
							$fields_data[ $i ]['label'] = isset( $rtec_options[ $type . '_label' ] ) ? __( $rtec_options[ $type . '_label' ], 'registrations-for-the-events-calendar' ) : '';
							$error_text = isset( $rtec_options[ $type . '_error' ] ) ? $rtec_options[ $type . '_error' ] : __( 'This is required', 'registrations-for-the-events-calendar' );
							$fields_data[ $i ]['error_text'] = rtec_get_text( $error_text, __( 'This is required', 'registrations-for-the-events-calendar' ) );
					}

					$i++;
				}

			}

			// recaptcha field calculations for spam check
			if ( isset( $rtec_options['recaptcha_require'] ) && $rtec_options['recaptcha_require'] )  {
				$i++;
				$fields_data[ $i ]['field_name'] = 'recaptcha';
				$fields_data[ $i ]['field_type'] = 'text';
				$fields_data[ $i ]['valid_type'] = 'recaptcha';
				$fields_data[ $i ]['valid_params'] = array();
				$fields_data[ $i ]['placeholder'] = '';
				$fields_data[ $i ]['meta'] = '';
				$fields_data[ $i ]['default_value'] = '';
				$fields_data[ $i ]['is_required'] = true;
				$fields_data[ $i ]['error_text'] = rtec_get_text( $rtec_options[ 'recaptcha_error' ], __( 'Error', 'registrations-for-the-events-calendar' ) );
			}

		} else {
			$default = array(
				0 => array(
					'field_id' => 1,
					'field_name' => 'first',
					'label' => 'First',
					'field_type' => 'text',
					'valid_type' => 'length',
					'valid_params' => '',
					'placeholder' => '',
					'meta' => '',
					'default_value' => '',
					'error_text' => 'Please enter your first name',
					'is_required' => 1
				),
				1 => array(
					'field_id' => 2,
					'field_name' => 'last',
					'label' => 'Last',
					'field_type' => 'text',
					'valid_type' => 'length',
					'valid_params' => '',
					'placeholder' => '',
					'meta' => '',
					'default_value' => '',
					'error_text' => 'Please enter your last name',
					'is_required' => 1
				),
				2 => array(
					'field_id' => 3,
					'field_name' => 'email',
					'label' => 'Email',
					'field_type' => 'text',
					'valid_type' => 'email',
					'valid_params' => '',
					'placeholder' => '',
					'meta' => '',
					'default_value' => '',
					'error_text' => 'Please enter your email',
					'is_required' => 1
				)
			);

			return $default;
		}

		return $fields_data;
	}

	/**
	 * @param string $url    url retrieved using tribe_get_single_ical_link
	 * @since 1.1
	 */
	public function set_ical_url( $url )
	{
		$this->ical_url = $url;
	}

	/**
	 * Hides or shows the registration form initially depending on shortcode settings
	 *
	 * @param array $atts   shortcode settings
	 * @since 1.5
	 */
	public function set_display_type( $atts )
	{
		$this->hidden_initially = isset( $atts['hidden'] ) ? $atts['hidden'] === 'true' : true;
	}

	/**
	 * @param string $id    optional manual input of post ID
	 * @since 1.1
	 *
	 * @return array
	 */
	public function get_event_meta( $id = '' )
	{
		if ( ! isset( $this->event_meta ) ) {
			$this->event_meta = rtec_get_event_meta( $id );
			return $this->event_meta;
		} else {
			return $this->event_meta;
		}
	}

	/**
	 * @since 1.1
	 *
	 * @return bool
	 */
	public function registrations_are_disabled()
	{
		return ( $this->event_meta['registrations_disabled'] === '1' || $this->event_meta['registrations_disabled'] === true );
	}

	/**
	 * @since 1.1
	 *
	 * @return bool
	 */
	public function registration_deadline_has_passed()
	{
		if ( $this->event_meta['registration_deadline'] !== 'none' ) {
			return( $this->event_meta['registration_deadline'] < time() );
		} else {
			return false;
		}
	}

	/**
	 * @since 1.0
	 */
	public function set_max_registrations()
	{
		global $rtec_options;

		$this->max_registrations = isset( $rtec_options['default_max_registrations'] ) ? $rtec_options['default_max_registrations'] : 30;
	}

	/**
	 * Combine required and included fields to use in a loop later
	 *
	 * @since 1.0
	 */
    public function set_input_fields_data()
    {
        global $rtec_options;

        $input_fields_data = array();
        $show_fields = $this->show_fields;
        $required_fields = $this->required_fields;
        
        $standard_field_types = array( 'first', 'last', 'email', 'phone' );
        
        foreach ( $standard_field_types as $type ) {

            if ( in_array( $type, $show_fields ) ) {
                $input_fields_data[$type]['name'] = $type;
                $input_fields_data[$type]['require'] = in_array( $type, $required_fields );
	            $input_fields_data[$type]['valid_count'] = isset( $rtec_options[$type . '_valid_count'] ) ? ' data-rtec-valid-count="' . $rtec_options[$type . '_valid_count'].'"' : '';
	            $input_fields_data[$type]['error_message'] = rtec_get_text( $rtec_options[$type . '_error'], __( 'Error', 'registrations-for-the-events-calendar' ) );

                switch( $type ) {
                    case 'first':
                        $input_fields_data['first']['label'] = rtec_get_text( $rtec_options['first_label'], __( 'First', 'registrations-for-the-events-calendar' ) );
                        break;
                    case 'last':
                        $input_fields_data['last']['label'] = rtec_get_text( $rtec_options['last_label'], __( 'Last', 'registrations-for-the-events-calendar' ) );
                        break;
                    case 'email':
                        $input_fields_data['email']['label'] = rtec_get_text( $rtec_options['email_label'], __( 'Email', 'registrations-for-the-events-calendar' ) );
                        break;
	                case 'phone':
		                $input_fields_data['phone']['label'] = rtec_get_text( $rtec_options['phone_label'], __( 'Phone', 'registrations-for-the-events-calendar' ) );
		                break;
                }

            }

        }

        // the "other" fields is handled slightly differently
        if ( in_array( 'other', $show_fields ) ) {
            $input_fields_data['other']['name'] = 'other';
            $input_fields_data['other']['require'] = isset( $rtec_options['other_require'] ) ? $rtec_options['other_require'] : true;
            $input_fields_data['other']['error_message'] = rtec_get_text( $rtec_options['other_error'], __( 'Error', 'registrations-for-the-events-calendar' ) );
            $input_fields_data['other']['label'] = rtec_get_text( $rtec_options['other_label'], __( 'Other', 'registrations-for-the-events-calendar' ) );
	        $input_fields_data['other']['valid_count'] = isset( $rtec_options['other_valid_count'] ) ? ' data-rtec-valid-count="' . $rtec_options['other_valid_count'].'"' : '';
        }

        $this->input_fields_data = $input_fields_data;
    }

	/**
	 * Are there still registration spots available?
	 *
	 * @since 1.0
	 * @return bool
	 */
    public function registrations_available()
    {
	    if ( ! $this->event_meta['limit_registrations'] ) {
	    	return true;
	    }

	    $max_registrations = $this->event_meta['max_registrations'];
	    if ( ( $max_registrations - $this->event_meta['num_registered'] ) > 0 ) {
    		return true;
	    } else {
	    	return false;
	    }
    }

	/**
	 * Message if registrations are closed
	 *
	 * @since 1.0
	 * @return string   the html for registrations being closed
	 */
	public function registrations_closed_message()
	{
		global $rtec_options;

		$message = isset( $rtec_options['registrations_closed_message'] ) ? $rtec_options['registrations_closed_message'] : __( 'Registrations are closed for this event', 'registrations-for-the-events-calendar' );
		$message = rtec_get_text( $message, __( 'Registrations are closed for this event', 'registrations-for-the-events-calendar' ) );

		return '<p class="rtec-success-message tribe-events-notices">' . esc_html( $message ) . '</p>';
	}

	/**
	 * The html that creates the feed is broken into parts and pieced together
	 *
	 * @since 1.0
	 * @return string
	 */
    public function get_beginning_html()
    {
	    global $rtec_options;

	    $button_text = isset( $rtec_options['register_text'] ) ? $rtec_options['register_text'] : __( 'Register', 'registrations-for-the-events-calendar' );
        $button_text = rtec_get_text( $button_text, __( 'Register', 'registrations-for-the-events-calendar' ) );
	    $button_bg_color = isset( $rtec_options['button_bg_color'] ) ? esc_attr( $rtec_options['button_bg_color'] ) : '';
	    $button_text_color = isset( $rtec_options['button_text_color'] ) ? esc_attr( $rtec_options['button_text_color'] ) : '';
	    $button_styles = isset( $button_bg_color ) && ! empty( $button_bg_color ) ? 'background-color: ' . $button_bg_color . ';' : '';
	    if ( !empty( $button_text_color ) ) {
	    	$button_styles .= ' color: ' . $button_text_color . ';';
	    }
	    $button_hover_class = ! empty( $button_bg_color ) ? ' rtec-custom-hover' : '';
	    $button_classes = ! empty( $button_hover_class ) ? $button_hover_class : '';
	    $form_bg_color = isset( $rtec_options['form_bg_color'] ) && ! empty( $rtec_options['form_bg_color'] ) ? 'background-color: ' . esc_attr( $rtec_options['form_bg_color'] ) . ';' : '';
	    $width_unit = isset( $rtec_options['width_unit'] ) ? esc_attr( $rtec_options['width_unit'] ) : '%';
        $width = isset( $rtec_options['width'] ) ? 'width: ' . esc_attr( $rtec_options['width'] ) . $width_unit . ';' : '';
	    $classes = '';

	    $success_message = isset( $rtec_options['success_message'] ) ? $rtec_options['success_message'] : __( 'Success! Please check your email inbox for a confirmation message', 'registrations-for-the-events-calendar' );
	    $data = ' data-rtec-success-message="' . esc_attr( rtec_get_text( $success_message , __( 'Success! Please check your email inbox for a confirmation message', 'registrations-for-the-events-calendar' ) ) ) . '"';
	    $data .= ' data-event="' . esc_attr( $this->event_meta['post_id'] ) . '"';

		    $html = '<div id="rtec" class="rtec rtec-form-' . $this->event_meta['form_id'] . $classes .  '"' . $data . '>';
	    if ( $this->hidden_initially ) {
		    $html .= '<button type="button" id="rtec-form-toggle-button" class="rtec-register-button rtec-form-toggle-button rtec-js-show' . $button_classes . '" style="' . $button_styles . '">' . esc_html( $button_text ). '<span class="tribe-bar-toggle-arrow"></span></button>';
		    $html .= '<h3 class="rtec-js-hide">' . esc_html( $button_text ) . '</h3>';
	    }

	    if ( $this->hidden_initially ) {
		    $js_hide_class = ' rtec-js-hide';
	    } else {
		    $js_hide_class = '';
	    }

            $html .= '<div class="rtec-form-wrapper rtec-toggle-on-click' . $js_hide_class . '"' . ' style="'. $width . $form_bg_color . '">';

            if ( ! empty( $this->errors ) ) {
                $html .= '<div class="rtec-screen-reader" role="alert">';
                $html .= __( 'There were errors with your submission. Please try again.', 'registrations-for-the-events-calendar' );
                $html .= '</div>';
            }

            if ( ! isset( $rtec_options['include_attendance_message'] ) || $rtec_options['include_attendance_message'] ) {
                $html .= $this->get_attendance_html();
            }
                $html .= '<form method="post" action="" id="rtec-form" class="rtec-form">';

        return $html;
    }

	/**
	 * The html that creates the feed is broken into parts and pieced together
	 *
	 * @since 1.0
	 * @return string
	 */
    public function get_attendance_html()
    {
	    global $rtec_options;

	    $attendance_message_type = isset( $rtec_options['attendance_message_type'] ) ? $rtec_options['attendance_message_type'] : 'up';

	    // a "count down" type of message won't work if there isn't a limit so we check to see if that's true here
	    if ( ! $this->event_meta['limit_registrations'] ) {
		    $attendance_message_type = 'up';
	    }

        $html = '';

            if ( $attendance_message_type === 'up' ) {
                $display_num = $this->event_meta['num_registered'];
	            $text_before_def = isset( $rtec_options['attendance_text_before_up'] ) ? $rtec_options['attendance_text_before_up'] : __( 'Join', 'registrations-for-the-events-calendar' );
                $text_before = rtec_get_text( $text_before_def, __( 'Join', 'registrations-for-the-events-calendar' ) );
                $text_after_def = isset( $rtec_options['attendance_text_after_up'] ) ? $rtec_options['attendance_text_after_up'] : __( 'others!', 'registrations-for-the-events-calendar' );
	            $text_after = rtec_get_text( $text_after_def, __( 'others!', 'registrations-for-the-events-calendar' ) );
            } else {
                $display_num = $this->event_meta['max_registrations'] - $this->event_meta['num_registered'];
	            $text_before = rtec_get_text( $rtec_options['attendance_text_before_down'], __( 'Only', 'registrations-for-the-events-calendar' ) );
	            $text_after = rtec_get_text( $rtec_options['attendance_text_after_down'], __( 'spots left', 'registrations-for-the-events-calendar' ) );
            }

            $text_string = sprintf( '%s %s %s', $text_before, (string)$display_num, $text_after );
            if ( $display_num == '1' && $attendance_message_type === 'up' ) {
	            $def_text = isset( $rtec_options['attendance_text_one_up'] ) ? $rtec_options['attendance_text_one_up'] : __( 'Join one other person', 'registrations-for-the-events-calendar' );
	            $text_string = rtec_get_text( $def_text, __( 'Join one other person', 'registrations-for-the-events-calendar' ) );
            } elseif ( $display_num == '1' && $attendance_message_type === 'down' ) {
	            $def_text = isset( $rtec_options['attendance_text_one_down'] ) ? $rtec_options['attendance_text_one_down'] : __( 'Only one spot left!', 'registrations-for-the-events-calendar' );
			    $text_string = rtec_get_text( $def_text, __( 'Only one spot left!', 'registrations-for-the-events-calendar' ) );
		    }

            if ( $display_num < 1 ) {
	            $def_text = isset( $rtec_options['attendance_text_none_yet'] ) ? $rtec_options['attendance_text_none_yet'] : __( 'Be the first!', 'registrations-for-the-events-calendar' );
                $text_string = rtec_get_text( $def_text, __( 'Be the first!', 'registrations-for-the-events-calendar' ) );
            }

            $html .= '<div class="rtec-attendance tribe-events-notices">';
                $html .= '<p>' . esc_html( $text_string ) . '</p>';
            $html .= '</div>';

        return $html;
    }

	/**
	 * Data about the event is also included
	 *
	 * @since 1.0
	 * @return string
	 */
    public function get_hidden_fields_html()
    {
        $html = '';

        $event_meta = $this->event_meta;

        $html .= wp_nonce_field( 'rtec_form_nonce', '_wpnonce', true, false );
        $html .= '<input type="hidden" name="rtec_email_submission" value="1" />';
        $html .= '<input type="hidden" name="rtec_event_id" value="' . esc_attr( $event_meta['post_id'] ) . '" />';

        return $html;
    }

	/**
	 * @param $field_name
	 * @param $field_attributes
	 * @param $errors
	 * @param $submission_data
	 * @param $registrations_left
	 *
	 * @return string
	 */
	public function get_field_html( $field_name, $field_attributes, $errors, $submission_data, $registrations_left )
	{
		global $rtec_options;

		$field_settings = array();

		$field_settings['type'] = $field_attributes['type'];
		$field_settings['label'] = $field_attributes['label'];
		$field_settings['field_name'] = $field_attributes['name'];
		$type_atts = $this->get_field_types();

		$label_classes = '';
		if ( isset( $type_atts[ $field_settings['type'] ]['special']['hide-label'] ) && $type_atts[ $field_settings['type'] ]['special']['hide-label'] === 'true' ) {
			$label_classes .= ' rtec-screen-reader';
		}

		if ( isset( $submission_data[ $field_name ] ) && $submission_data[ $field_name ] !== '' ) {
			$field_settings['value'] = $submission_data[ $field_name ];
		} elseif( is_user_logged_in() && in_array( $field_settings['field_name'], array( 'rtec_first', 'rtec_last', 'rtec_email' ), true ) ) {

			if ( $field_settings['field_name'] === 'rtec_first' ) {
				$user_meta = get_user_meta( get_current_user_id(), '', true );
				$field_settings['value'] = isset( $user_meta['first_name'] ) ? $user_meta['first_name'][0] : '';
			} elseif ( $field_settings['field_name'] === 'rtec_last' ) {
				$user_meta = get_user_meta( get_current_user_id() );
				$field_settings['value'] = isset( $user_meta['last_name'] ) ? $user_meta['last_name'][0] : '';
			} elseif ( $field_settings['field_name'] === 'rtec_email' ) {
				$user = wp_get_current_user();
				$field_settings['value'] = isset( $user->data->user_email ) ? $user->data->user_email : '';
			}

		} else {
			if ( isset( $user_data[ $field_name ] ) ) {
				$field_settings['value'] = $user_data[ $field_name ];
			} elseif ( isset( $user_data['custom'][ $field_name ]['value'] ) ) {
				$field_settings['value'] = $user_data['custom'][ $field_name ]['value'];
			} else {
				$field_settings['value'] = $field_attributes['default'];
			}
		}

		$field_settings['placeholder'] = isset( $field_attributes['placeholder'] ) && $field_attributes['placeholder'] !== '' ? ' placeholder="'.esc_attr( $field_attributes['placeholder'] ). '"' : '';

		$field_settings['error'] =  in_array( $field_name, $errors, true ) ?  true : false;

		if ( $field_settings['error'] ) {
			$field_attributes['data_atts']['aria-invalid'] = 'true';
		}

		$field_settings['error_message'] = isset( $field_attributes['error_message'] ) ? $field_attributes['error_message'] : '';

		if ( $field_name === 'email' && in_array( 'email_duplicate', $errors, true ) && !in_array( 'email', $errors, true ) ) {
			$field_settings['error'] = true;
			$field_attributes['data_atts']['aria-invalid'] = 'true';

			$message = isset( $rtec_options['error_duplicate_message'] ) ? $rtec_options['error_duplicate_message'] : __( 'You have already registered for this event', 'registrations-for-the-events-calendar' );
			$field_settings['error_message'] = rtec_get_text( $message, __( 'You have already registered for this event', 'registrations-for-the-events-calendar' ) );
		}

		$field_settings['data_atts_string'] = '';
		$field_settings['data_atts_string'] = $this->get_data_atts_string_for_type( $field_attributes['valid_type'], $field_attributes['valid_params'] );

		foreach ( $field_attributes['data_atts'] as $att_key => $att_value ) {
			$field_settings['data_atts_string'] .= ' ' . $att_key . '="' . $att_value . '"';
		}

		if ( isset( $field_attributes['meta'] ) ) {
			$field_settings['meta'] = $field_attributes['meta'];
		} else {
			$field_settings['meta'] = array();
		}

		if ( isset( $field_settings['meta']['options'] ) && ( isset( $user_data['custom'][ $field_name ] ) || isset( $user_data[ $field_name ] ) ) ) {
			$this_data = isset( $user_data[ $field_name ] ) ? $user_data[ $field_name ] : $user_data['custom'][ $field_name ]['value'];
			$field_settings['values'] = explode( ',', str_replace( ' ', '', $this_data ) );

			$i = 0;
			foreach ( $field_settings['meta']['options'] as $option ) {
				if ( in_array( $option[1], $field_settings['values'], true ) ) {
					$field_settings['meta']['options'][$i][2] = true;
				}
				$i++;
			}
		}

		$field_settings['html'] = isset( $field_settings['meta']['html'] ) ? $field_settings['meta']['html'] : '';
		if ( isset( $field_attributes['valid_type'] ) && $field_attributes['valid_type'] === 'recaptcha'  ) {
			$field_settings['html'] .= '<input type="hidden" name="' . esc_attr( $field_settings['field_name'] ) . '_sum" value="' . esc_attr( $field_attributes['valid_params']['sum'] ) . '" />';
			$field_settings['type'] = 'text';
		}
		ob_start();

		include RTEC_PLUGIN_DIR . 'templates/form/field.php';

		$html = ob_get_contents();
		ob_get_clean();

		return $html;
	}

	/**
	 * @param $type
	 * @param $params
	 *
	 * @return string
	 */
	protected function get_data_atts_string_for_type( $type, $params )
	{
		switch ( $type ) {
			case 'numval' :
				$min = isset( $params['min'] ) ? ' min="' . $params['min'] . '"' : ' min="0"';
				$max = isset( $params['max'] ) ? ' max="' . $params['max'] . '"' : '';
				$step = isset( $params['step'] ) ? ' step="' . $params['step'] . '"' : ' step="1"';
				$data_atts_string = $min . $max . $step;
				break;
			case 'count' :
				$data_atts_string = ' data-rtec-valid-count="' . esc_attr( str_replace( ' ' , '', $params['count'] ) ) . '"  data-rtec-count-what="' . esc_attr( str_replace( ' ' , '', $params['count_what'] ) ) . '"';
				break;
			case 'email' :
				$data_atts_string = ' data-rtec-valid-email="true"';
				break;
			case 'recaptcha' :
				$data_atts_string = ' data-rtec-recaptcha="true"';
				break;
			case 'none' :
				$data_atts_string = ' data-rtec-valid-options="true"';
				break;
			default :
				$data_atts_string = ' data-rtec-min="' . $params['min'] . '" data-rtec-max="' . $params['max'] . '"';
		}

		return $data_atts_string;
	}

	/**
	 * @param $field_settings
	 */
	protected function get_input_html_for_field_type( $field_settings )
	{
		?>
	<?php if ( in_array( $field_settings['type'], array( 'text', 'number', 'tel', 'email' ), true ) ) : ?>
		<input type="<?php echo esc_attr( $field_settings['type'] ); ?>" name="<?php echo esc_attr( $field_settings['field_name'] ); ?>" value="<?php echo esc_attr( stripslashes( $field_settings['value'] ) ); ?>"<?php echo $field_settings['placeholder']; ?> class="rtec-field-input" id="<?php echo esc_attr( $field_settings['field_name'] ); ?>"<?php echo $field_settings['data_atts_string']; ?> />
	<?php elseif ( $field_settings['type'] === 'select' ) : ?>
		<select type="select" name="<?php echo esc_attr( $field_settings['field_name'] ); ?>" class="rtec-field-input" id="<?php echo esc_attr( $field_settings['field_name'] ); ?>"<?php echo $field_settings['data_atts_string']; ?>>
			<?php foreach ( $field_settings['meta']['options'] as $option ) : ?>
				<option id="<?php echo esc_attr( $field_settings['field_name'] ) . $option[1]; ?>"<?php echo $field_settings['data_atts_string']; ?> value="<?php echo $option[1]; ?>"<?php if ( $option[2] ) { echo ' selected="selected"'; } ?> ><?php echo esc_html( $option[0] ); ?></option>
			<?php endforeach; ?>
		</select>
	<?php else : ?>
		<?php do_action( 'rtec_field_html_' . $field_settings['type'], $field_settings ); ?>
	<?php endif;
	}

	/**
	 * @return array|mixed|void
	 */
	public function get_field_types()
	{
		$types = array(
			'text' => array(
				'label' => 'Text',
				'icon' => 'fa-text-width',
				'default_valid_type' => 'length',
				'default_valid_params' => array(
					'min' => 1,
					'max' => 'no-max'
				),
				'uses' => array( 'all' ),
				'special' => array()
			),
			'number' => array(
				'label' => 'Number (quantity)',
				'icon' => 'fa-hashtag',
				'default_valid_type' => 'numval',
				'default_valid_params' => array(
					'min' => 1,
					'max' => 'no-max'
				),
				'uses' => array( 'all' ),
				'special' => array()
			),
			'select' => array(
				'label' => 'Select',
				'icon' => 'fa-caret-down',
				'default_valid_type' => 'length',
				'default_valid_params' => array(
					'min' => 1,
					'max' => 'no-max'
				),
				'uses' => array( 'all' ),
				'special' => array()
			)
		);

		$types = apply_filters( 'rtec_field_types', $types );

		return $types;
	}

	/**
	 * The html that creates the feed is broken into parts and pieced together
	 *
	 * @since 1.0
	 * @since 1.5   will set first, last, and email fields if user is logged-in and data is available
	 * @since 2.0   added fields_atts, uses template
	 * @return string
	 */

	public function get_regular_fields_html( $fields_atts )
	{

		$html = '<div class="rtec-form-fields-wrapper">';

		foreach ( $fields_atts as $field_name => $field_attributes ) {
			$html .= $this->get_field_html( $field_name, $field_attributes, $this->errors, $this->submission_data, $this->event_meta['registrations_left'] );
		}

		$html .= '</div>'; // rtec-form-fields-wrapper

		return $html;
	}

	/**
	 * Backup in case javascript is unavailable
	 *
	 * @since 1.0
	 * @return string
	 */
    public static function get_success_message_html() {
		global $rtec_options;
	    $success_message = isset( $rtec_options['success_message'] ) ? $rtec_options['success_message'] : __( 'Success! Please check your email inbox for a confirmation message', 'registrations-for-the-events-calendar' );

        $success_html = '<p class="rtec-success-message tribe-events-notices">';
        $success_html .= rtec_get_text( $success_message, __( 'Success! Please check your email inbox for a confirmation message', 'registrations-for-the-events-calendar' ) );
        $success_html .= '</p>';

	    return $success_html;
    }

	/**
	 * The html that creates the feed is broken into parts and pieced together
	 *
	 * @since 1.0
	 * @return string
	 */
    public function get_ending_html()
    {
	    global $rtec_options;

	    $button_text = isset( $rtec_options['submit_text'] ) ? $rtec_options['submit_text'] : __( 'Submit', 'registrations-for-the-events-calendar' );
        $button_text = rtec_get_text( $button_text, __( 'Submit', 'registrations-for-the-events-calendar' ) );
	    $button_bg_color = isset( $rtec_options['button_bg_color'] ) ? esc_attr( $rtec_options['button_bg_color'] ) : '';
	    $button_text_color = isset( $rtec_options['button_text_color'] ) ? esc_attr( $rtec_options['button_text_color'] ) : '';
	    $button_styles = isset( $button_bg_color ) && ! empty( $button_bg_color ) ? 'background-color: ' . $button_bg_color . ';' : '';

	    if ( !empty( $button_text_color ) ) {
		    $button_styles .= ' color: ' . $button_text_color . ';';
	    }

	    $button_hover_class = ! empty( $button_bg_color ) ? ' rtec-custom-hover' : '';
	    $button_classes = ! empty( $button_hover_class ) ? $button_hover_class : '';
	    $html = '';
				    $html .= '<div class="rtec-form-field rtec-user-address" style="display: none;">';
				    $html .= '<label for="rtec_user_comments" class="rtec_text_label">Comments</label>';
					    $html .= '<div class="rtec-input-wrapper">';
						    $html .= '<input type="text" name="rtec_user_comments" value="" id="rtec_user_comments" />';
	                        $html .= '<p>' . __( 'If you are a human, do not fill in this field', 'registrations-for-the-events-calendar' ) .'</p>';
					    $html .= '</div>';
				    $html .= '</div>';
                    $html .= '<div class="rtec-form-buttons">';
                        $html .= '<input type="submit" class="rtec-submit-button' . $button_classes . '" name="rtec_submit" value="' . $button_text . '" style="' . $button_styles . '"/>';
                    $html .= '</div>';
                $html .= '</form>';
                $html .= '<div class="rtec-spinner">';
                    $html .= '<img title="Tribe Loading Animation Image" alt="Tribe Loading Animation Image" class="tribe-events-spinner-medium" src="' . plugins_url() . '/the-events-calendar/src/resources/images/tribe-loading.gif' . '">';
                $html .= '</div>';
            $html .= '</div>'; // rtec-form-wrapper
        $html .= '</div>'; // rtec

        return $html;
    }

	/**
	 * Assembles the html in the proper order and returns it
	 *
	 * @return string   complete html for the form
	 *
	 * @since 1.0
	 * @since 2.0       added $fields_atts
	 */
	public function get_form_html( $fields_atts )
	{
		$html = '';
		$html .= $this->get_beginning_html();
		$html .= $this->get_hidden_fields_html();
		$html .= $this->get_regular_fields_html( $fields_atts );
		$html .= $this->get_ending_html();

		return $html;
	}

	/**
	 * @param $registrants_data
	 */
	public static function get_registrants_data_html( $registrants_data )
	{
		global $rtec_options;

		$title = isset( $rtec_options['attendee_list_title'] ) ? $rtec_options['attendee_list_title'] : __( 'Currently Registered', 'registrations-for-the-events-calendar' );
		$title = rtec_get_text( $title, __( 'Currently Registered', 'registrations-for-the-events-calendar' ) );
		$return_html = '<div class="tribe-events-event-meta rtec-event-meta rtec-attendee-list-meta"><h3 class="rtec-section-title">' . esc_html( $title ) . '</h3>';

		// to prevent looping through the data twice, two columns are created by alternating appending of qualified registrations
		$column_1_html = '<div class="rtec-attendee-list rtec-list-column-2">';
		$column_2_html = '<div class="rtec-attendee-list rtec-list-column-2">';
		$i = 1;

		foreach ( $registrants_data as $registrant ) {

			$single_html = '<span class="rtec-attendee">';

			if ( isset( $registrant['first_name'] ) ) {
				$single_html .= $registrant['first_name'] . ' ';
			}
			if ( isset( $registrant['last_name'] ) ) {
				$single_html .= $registrant['last_name'] . ' ';
			}

			$single_html .= '</span>';

			if ( $i % 2 === 0 ) {
				$column_2_html .= stripslashes( $single_html );
			} else {
				$column_1_html .= stripslashes( $single_html );
			}
			$i++;

		}

		$column_1_html .= '</div>';
		$column_2_html .= '</div>';
		$return_html .= $column_1_html . $column_2_html;

		$return_html .= '</div>'; // rtec-event-meta

		echo $return_html;
	}

	public function the_event_header()
	{
		$html = '<h2 class="rtec-header">'.$this->event_meta['title'].'</h2>';
		if ( function_exists( 'tribe_events_event_schedule_details' ) ) {
			$html .= tribe_events_event_schedule_details( $this->event_meta['post_id'], '<h3 class="rtec-header">', '</h3>' );
		}

		echo $html;
	}

	/**
	 * @return string
	 */
	public function get_event_header_html()
	{
		$html = '<h2 class="rtec-header">'.$this->event_meta['title'].'</h2>';
		if ( function_exists( 'tribe_events_event_schedule_details' ) ) {
			$html .= tribe_events_event_schedule_details( $this->event_meta['post_id'], '<h3 class="rtec-header">', '</h3>' );
		}

		return $html;
	}
}
RTEC_Form::instance();
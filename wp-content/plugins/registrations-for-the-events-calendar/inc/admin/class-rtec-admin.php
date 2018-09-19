<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
    die( '-1' );
}

/**
 * Class RTEC_Admin
 * 
 * Just your standard settings pages with a tab to view current registrations
 * 
 * @since 1.0
 */
class RTEC_Admin
{
	/**
	 * RTEC_Admin constructor.
	 * 
	 * Create the basic admin pages
	 */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_tribe_submenu' ) );
        add_action( 'admin_init', array( $this, 'options_page_init' ) );
    }

    /**
     * Add the menu with new registration count alert
     * 
     * @since 1.0
     */
    public function add_tribe_submenu()
    {
        $menu_title = __( 'Registrations', 'registrations-for-the-events-calendar' );

        $new_registrations_count = rtec_get_existing_new_reg_count();

        if ( $new_registrations_count > 0 ) {
            $menu_title .= ' <span class="update-plugins rtec-notice-admin-reg-count"><span>' . esc_html( $new_registrations_count ) . '</span></span>';
        } else {
            if ( get_transient( 'rtec_new_messages' ) === 'yes' ) {
                $menu_title .= ' <span class="update-plugins rtec-notice-admin-reg-count"><span>' . __( 'New!', 'registrations-for-the-events-calendar' ) . '</span></span>';
            }
        }

        add_submenu_page(
	        'edit.php?post_type=' . RTEC_TRIBE_EVENTS_POST_TYPE,
            'Registrations',
            $menu_title,
            'edit_posts',
	        RTEC_MENU_SLUG,
            array( $this, 'create_options_page' )
        );
	    add_submenu_page(
		    '',
		    esc_html__( 'Registrations', 'registrations-for-the-events-calendar' ),
		    esc_html__( 'Registrations', 'registrations-for-the-events-calendar' ),
		    'edit_posts',
		    RTEC_PLUGIN_DIR . '_settings',
		    array( $this, 'create_options_page' )
	    );
    }
    
    /**
     * Validates the $_GET field with tab information
     * 
     * @param string $tab   current selected tab
     *
     * @return string       name of the tab to navigate to
     * @since 1.0
     */
    public static function get_active_tab( $tab = '' )
    {
        switch( $tab ) {
            case 'single':
                return 'single';
            case 'form':
                return 'form';
            case 'email':
                return 'email';
	        case 'support':
		        return 'support';
            default:
                return 'registrations';
        }
    }
    
    public function create_options_page()
    {
        require_once RTEC_PLUGIN_DIR . '/inc/admin/templates/main.php';
    }
    
    public function blank() {
        // none needed
    }

    public function options_page_init() {

        /**
         * Form Settings
         */

        register_setting(
            'rtec_options',
            'rtec_options',
            array( $this, 'validate_options' )
        );

        /* Form Settings Section */

        add_settings_section(
            'rtec_timezone',
            '',
            array( $this, 'blank' ),
            'rtec_timezone'
        );

        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'timezone',
            'title' => '<label for="rtec_timezone">Select your timezone</label>',
            'callback'  => 'timezone',
            'class' => '',
            'page' => 'rtec_timezone',
            'section' => 'rtec_timezone',
        ));

        add_settings_section(
            'rtec_form_form_fields',
            'Form Fields',
            array( $this, 'blank' ),
            'rtec_form_form_fields'
        );

        $first_error = __( 'Please enter your first name', 'registrations-for-the-events-calendar' );
        $last_error = __( 'Please enter your last name', 'registrations-for-the-events-calendar' );
        $email_error = __( 'Please enter a valid email address', 'registrations-for-the-events-calendar' );
        $phone_error = __( 'Please enter a valid phone number', 'registrations-for-the-events-calendar' );
        $form_fields_array = array(
            array( 'first', __( 'First', 'registrations-for-the-events-calendar' ), $first_error, true, true, '', '{first}', true, true ),
            array( 'last', __( 'Last', 'registrations-for-the-events-calendar' ), $last_error, true, true, '', '{last}', true, true ),
            array( 'email', __( 'Email', 'registrations-for-the-events-calendar' ), $email_error, true, true, '', '{email}', true, false ),
            array( 'phone', __( 'Phone', 'registrations-for-the-events-calendar' ), $phone_error, false, false, '7, 10', '{phone}', false, false )
        );

        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'form_fields',
            'title' => 'Select Form Fields',
            'callback'  => 'form_field_select',
            'class' => '',
            'page' => 'rtec_form_form_fields',
            'section' => 'rtec_form_form_fields',
            'fields' => $form_fields_array
        ));

        /* Registration Messages */
        add_settings_section(
            'rtec_attendee_data',
            __( 'Attendee Display', 'registrations-for-the-events-calendar' ),
            array( $this, 'blank' ),
            'rtec_attendee_data'
        );

        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'show_registrants_data',
            'title' => '<label for="rtec_show_registrants_data">' . __( 'Show Attendee List Above Form', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => '',
            'description' => '',
            'callback'  => 'default_checkbox',
            'class' => '',
            'page' => 'rtec_attendee_data',
            'section' => 'rtec_attendee_data',
            'default' => false
        ));

	    // show who
	    $who_options = array(
		    array( 'any', __( 'Any registration', 'registrations-for-the-events-calendar' ) ),
            array( 'users_and_confirmed', __( 'Reviewed', 'registrations-for-the-events-calendar' ) )
	    );
	    $this->create_settings_field( array(
		    'option' => 'rtec_options',
		    'name' => 'registrants_data_who',
		    'title' => '<label for="rtec_registrants_who_include">' . __( 'What Registrations Will Display', 'registrations-for-the-events-calendar' ) . '</label>',
		    'example' => '',
		    'description' => __( 'Choosing "Reviewed" will only display registrations after they have been reviewed in the admin dashboard of your site (do not have a "new tag" next to them).', 'registrations-for-the-events-calendar' ),
		    'callback'  => 'default_radio',
		    'values' => $who_options,
		    'class' => 'rtec-show-registrant-options',
		    'page' => 'rtec_attendee_data',
		    'section' => 'rtec_attendee_data',
		    'default' => 'users_and_confirmed',
		    'new_line' => true
	    ));

        // attendee text
        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'attendee_list_title',
            'title' => '<label for="rtec_attendee_list_title">' . __( 'Attendee List Title', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => '',
            'description' => '',
            'callback'  => 'default_text',
            'class' => 'rtec-show-registrant-options',
            'input_class' => 'default-text',
            'page' => 'rtec_attendee_data',
            'section' => 'rtec_attendee_data',
            'type' => 'text',
            'default' => __( 'Currently Registered', 'registrations-for-the-events-calendar' )
        ));

        /* Registration Messages */
        add_settings_section(
            'rtec_form_registration_availability',
            __( 'General Registration Options', 'registrations-for-the-events-calendar' ),
            array( $this, 'blank' ),
            'rtec_form_registration_availability'
        );

        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'disable_by_default',
            'title' => '<label for="rtec_disable_by_default">' . __( 'Disable Registrations by Default', 'registrations-for-the-events-calendar' ) . '<span class="rtec-individual-available">&#42;</span></label>',
            'example' => '',
            'description' => __( 'New events and existing events will not allow registrations until enabled manually', 'registrations-for-the-events-calendar' ),
            'callback'  => 'default_checkbox',
            'class' => '',
            'page' => 'rtec_form_registration_availability',
            'section' => 'rtec_form_registration_availability',
            'default' => false
        ));

        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'limit_registrations',
            'title' => '<label for="rtec_limit_registrations">' . __( 'Limit Registrations by Default', 'registrations-for-the-events-calendar' ) . '<span class="rtec-individual-available">&#42;</span></label>',
            'example' => '',
            'description' => __( 'Only allow a certain amount of registrations for each event', 'registrations-for-the-events-calendar' ),
            'callback'  => 'default_checkbox',
            'class' => '',
            'page' => 'rtec_form_registration_availability',
            'section' => 'rtec_form_registration_availability',
            'default' => false
        ));

        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'default_max_registrations',
            'title' => '<label for="rtec_default_max_registrations">' . __( 'Default Max Registrations', 'registrations-for-the-events-calendar' ) . '<span class="rtec-individual-available">&#42;</span></label>',
            'example' => '',
            'description' => __( 'Maximum allowed registrants for every event (if any limit)', 'registrations-for-the-events-calendar' ),
            'callback'  => 'default_text',
            'class' => 'small-text',
            'page' => 'rtec_form_registration_availability',
            'section' => 'rtec_form_registration_availability',
            'type' => 'number',
            'default' => 30
        ));

        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'check_for_duplicates',
            'title' => '<label for="rtec_check_for_duplicate">' . __( 'Check for Duplicate Emails', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => '',
            'description' => __( 'Only allow one registration per event per submitted email', 'registrations-for-the-events-calendar' ),
            'callback'  => 'default_checkbox',
            'class' => '',
            'page' => 'rtec_form_registration_availability',
            'section' => 'rtec_form_registration_availability',
            'default' => false
        ));

        // error duplicate
        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'error_duplicate_message',
            'title' => '<label>' . __( 'Duplicate Email Error Message', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => '',
            'default' => __( 'You have already registered for this event', 'registrations-for-the-events-calendar' ),
            'description' => __( 'Enter an error message if the visitor has already registered', 'registrations-for-the-events-calendar' ),
            'callback'  => 'default_text',
            'class' => 'regular-text',
            'page' => 'rtec_form_registration_availability',
            'section' => 'rtec_form_registration_availability',
        ));

        // Registration Deadline
        $this->create_settings_field( array(
            'name' => 'registration_deadline',
            'title' => '<label for="rtec_registration_deadline">' . __( 'Default Deadline for Registration', 'registrations-for-the-events-calendar' ) . '<span class="rtec-individual-available">&#42;</span></label>', // label for the input field
            'callback'  => 'deadline_offset', // name of the function that outputs the html
            'page' => 'rtec_form_registration_availability', // matches the section name
            'section' => 'rtec_form_registration_availability', // matches the section name
            'option' => 'rtec_options', // matches the options name
            'class' => 'short-text', // class for the wrapper and input field
        ) );
        
        /* Form Custom Text */

        add_settings_section(
            'rtec_form_custom_text',
            __( 'Custom Text/Labels', 'registrations-for-the-events-calendar' ),
            array( $this, 'blank' ),
            'rtec_form_custom_text'
        );

        // register text
        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'register_text',
            'title' => '<label for="rtec_register_text">' . __( '"Register" Button Text', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => '',
            'description' => __( 'The text displayed on the button that reveals the form', 'registrations-for-the-events-calendar' ),
            'callback'  => 'default_text',
            'class' => 'default-text',
            'page' => 'rtec_form_custom_text',
            'section' => 'rtec_form_custom_text',
            'type' => 'text',
            'default' => __( 'Register', 'registrations-for-the-events-calendar' )
        ));

        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'num_registrations_messages',
            'title' => '<label>' . __( 'Event Attendance Messages', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => '',
            'default' => '',
            'description' => '',
            'callback'  => 'num_registrations_messages',
            'class' => '',
            'page' => 'rtec_form_custom_text',
            'section' => 'rtec_form_custom_text'
        ));
        
        // submit text
        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'submit_text',
            'title' => '<label for="rtec_submit_text">' . __( '"Submit" Button Text', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => '',
            'description' => __( 'The text displayed on the button that submits the form', 'registrations-for-the-events-calendar' ),
            'callback'  => 'default_text',
            'class' => 'default-text',
            'page' => 'rtec_form_custom_text',
            'section' => 'rtec_form_custom_text',
            'type' => 'text',
            'default' => __( 'Submit', 'registrations-for-the-events-calendar' )
        ));

        // success message
        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'success_message',
            'title' => '<label>' . __( 'Website Success Message', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => '',
            'default' => __( 'Success! Please check your email inbox for a confirmation message', 'registrations-for-the-events-calendar' ),
            'description' => __( 'Enter the message you would like to display on your site after a successful form completion', 'registrations-for-the-events-calendar' ),
            'callback'  => 'message_text_area',
            'rows' => '3',
            'class' => '',
            'page' => 'rtec_form_custom_text',
            'section' => 'rtec_form_custom_text',
            'legend' => false
        ));

	    // unregister text
	    $this->create_settings_field( array(
		    'option' => 'rtec_options',
		    'name' => 'unregister_link_text',
		    'title' => '<label for="rtec_unregister_link_text">' . __( '"Unregister" Link Text', 'registrations-for-the-events-calendar' ) . '</label>',
		    'example' => '',
		    'description' => __( 'used for link in emails added using the template {unregister-link}', 'registrations-for-the-events-calendar' ),
		    'callback'  => 'default_text',
		    'class' => '',
		    'input_class' => 'regular-text',
		    'page' => 'rtec_form_custom_text',
		    'section' => 'rtec_form_custom_text',
		    'type' => 'text',
		    'default' => __( 'Unregister from this event', 'registrations-for-the-events-calendar' )
	    ));

	    // unregister success message
	    $this->create_settings_field( array(
		    'option' => 'rtec_options',
		    'name' => 'success_unregistration',
		    'title' => '<label>' . __( 'Website Unregister Success Message', 'registrations-for-the-events-calendar' ) . '</label>',
		    'example' => '',
		    'default' => __( 'You have been unregistered.', 'registrations-for-the-events-calendar' ),
		    'description' => __( 'Enter the message you would like to display on your site after an unregistration', 'registrations-for-the-events-calendar' ),
		    'callback'  => 'message_text_area',
		    'rows' => '3',
		    'class' => '',
		    'page' => 'rtec_form_custom_text',
		    'section' => 'rtec_form_custom_text',
		    'legend' => false
	    ));

	    add_settings_section(
		    'rtec_form_visitors_options',
		    'Registration Management Tool',
		    array( $this, 'blank' ),
		    'rtec_form_visitors_options'
	    );

	    $this->create_settings_field( array(
		    'option' => 'rtec_options',
		    'name' => 'visitors_can_edit_what_status',
		    'title' => '<label for="rtec_add_send_unregister_button">' . __( 'Add Unregister Tool', 'registrations-for-the-events-calendar' ) . '</label>',
		    'example' => '',
		    'description' => __( 'This will add a button to the event page that will allow attendees to send an "unregister" link by entering the email address they registered with.', 'registrations-for-the-events-calendar' ),
		    'callback'  => 'default_checkbox',
		    'class' => '',
		    'page' => 'rtec_form_visitors_options',
		    'section' => 'rtec_form_visitors_options',
		    'default' => false
	    ));

	    $this->create_settings_field( array(
		    'option' => 'rtec_options',
		    'name' => 'already_registered_question',
		    'title' => '<label>' . __( 'Already Registered Text', 'registrations-for-the-events-calendar' ) . '</label>',
		    'example' => '',
		    'default' => __( 'Already registered?', 'registrations-for-the-events-calendar' ),
		    'description' => '',
		    'callback'  => 'default_text',
		    'class' => 'rtec-visitor-can-edit',
		    'page' => 'rtec_form_visitors_options',
		    'section' => 'rtec_form_visitors_options',
	    ));

	    // Tool directions
	    $this->create_settings_field( array(
		    'option' => 'rtec_options',
		    'name' => 'already_registered_directions',
		    'title' => '<label>' . __( 'Tool Directions', 'registrations-for-the-events-calendar' ) . '</label>',
		    'example' => '',
		    'default' => __( 'Use this tool to manage your registration.', 'registrations-for-the-events-calendar' ),
		    'description' => '',
		    'callback'  => 'message_text_area',
		    'rows' => '2',
		    'class' => 'rtec-visitor-can-edit',
		    'page' => 'rtec_form_visitors_options',
		    'section' => 'rtec_form_visitors_options',
		    'legend' => false
	    ));

	    // edit entries text
	    $this->create_settings_field( array(
		    'option' => 'rtec_options',
		    'name' => 'enter_your_email_text',
		    'title' => '<label for="rtec_enter_your_email_text">' . __( 'Registered Email Label', 'registrations-for-the-events-calendar' ) . '</label>',
		    'example' => '',
		    'description' => '',
		    'callback'  => 'default_text',
		    'class' => 'large-text',
		    'page' => 'rtec_form_visitors_options',
		    'section' => 'rtec_form_visitors_options',
		    'type' => 'text',
		    'default' => __( 'Enter your registered email address', 'registrations-for-the-events-calendar' ),
	    ));

	    // edit entries text
	    $this->create_settings_field( array(
		    'option' => 'rtec_options',
		    'name' => 'send_unregister_link_text',
		    'title' => '<label for="rtec_send_unregister_link_text">' . __( '"Send unregister link" Text', 'registrations-for-the-events-calendar' ) . '</label>',
		    'example' => '',
		    'description' => '',
		    'callback'  => 'default_text',
		    'class' => 'rtec-visitor-can-edit',
		    'page' => 'rtec_form_visitors_options',
		    'section' => 'rtec_form_visitors_options',
		    'type' => 'text',
		    'default' => __( 'Send unregister link', 'registrations-for-the-events-calendar' ),
	    ));

        /* Form Styling */

        add_settings_section(
            'rtec_form_styles',
            'Styling',
            array( $this, 'blank' ),
            'rtec_form_styles'
        );

        // Template Location
        $this->create_settings_field( array(
            'name' => 'template_location',
            'title' => __( 'Form Location', 'registrations-for-the-events-calendar' ), // label for the input field
            'callback'  => 'default_select', // name of the function that outputs the html
            'page' => 'rtec_form_styles', // matches the section name
            'section' => 'rtec_form_styles', // matches the section name
            'option' => 'rtec_options', // matches the options name
            'class' => 'default-text', // class for the wrapper and input field
            'fields' => array(
                1 => array( 'tribe_events_single_event_before_the_content', __( 'Before the content (near top)', 'registrations-for-the-events-calendar' ) ),
                2 => array( 'tribe_events_single_event_after_the_content', __( 'After the content(middle top)', 'registrations-for-the-events-calendar' ) ),
                3 => array( 'tribe_events_single_event_before_the_meta', __( 'Before the meta (middle bottom)', 'registrations-for-the-events-calendar' ) ),
                4 => array( 'tribe_events_single_event_after_the_meta', __( 'After the meta (near bottom)', 'registrations-for-the-events-calendar' ) )
            ),
            'description' => __( "Location where the form will appear in the single event template", 'registrations-for-the-events-calendar' ) // what is this? text
        ) );

        // width
        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'width',
            'title' => '<label for="rtec_form_width">' . __( 'Width of Form', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => '',
            'description' => 'The width of the form',
            'callback'  => 'width_and_height_settings',
            'class' => 'small-text',
            'default' => '100',
            'page' => 'rtec_form_styles',
            'section' => 'rtec_form_styles',
            'type' => 'text',
            'default_unit' => '%'
        ));

        // form background color
        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'form_bg_color',
            'title' => '<label for="rtec_form_bg_color">' . __( 'Form Background Color', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => '',
            'callback'  => 'default_color',
            'class' => 'small-text',
            'page' => 'rtec_form_styles',
            'section' => 'rtec_form_styles'
        ));

        // button background color
        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'button_bg_color',
            'title' => '<label for="rtec_button_bg_color">' . __( 'Button Background Color', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => '',
            'callback'  => 'default_color',
            'class' => 'small-text',
            'page' => 'rtec_form_styles',
            'section' => 'rtec_form_styles'
        ));

        // button text color
        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'button_text_color',
            'title' => '<label for="rtec_button_text_color">' . __( 'Button Text Color', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => '',
            'callback'  => 'default_color',
            'class' => 'small-text',
            'page' => 'rtec_form_styles',
            'section' => 'rtec_form_styles'
        ));

        // Custom CSS
        $this->create_settings_field( array(
            'name' => 'custom_css',
            'title' => '<label for="rtec_custom_css">' . __( 'Custom CSS', 'registrations-for-the-events-calendar' ) . '</label>', // label for the input field
            'callback'  => 'custom_code', // name of the function that outputs the html
            'page' => 'rtec_form_styles', // matches the section name
            'section' => 'rtec_form_styles', // matches the section name
            'option' => 'rtec_options', // matches the options name
            'class' => 'default-text', // class for the wrapper and input field
            'description' => __( 'Enter your own custom CSS in the box below', 'registrations-for-the-events-calendar' )
        ));

        // Custom JS
        $this->create_settings_field( array(
            'name' => 'custom_js',
            'title' => '<label for="rtec_custom_js">' . __( 'Custom JavaScript', 'registrations-for-the-events-calendar' ) . '</label>', // label for the input field
            'callback'  => 'custom_code', // name of the function that outputs the html
            'page' => 'rtec_form_styles', // matches the section name
            'section' => 'rtec_form_styles', // matches the section name
            'option' => 'rtec_options', // matches the options name
            'class' => 'default-text', // class for the wrapper and input field
            'description' => __( 'Enter your own custom Javascript/JQuery in the box below', 'registrations-for-the-events-calendar' ),
        ));

        /* Advanced */

        add_settings_section(
            'rtec_advanced',
            __( 'Advanced', 'registrations-for-the-events-calendar' ),
            array( $this, 'blank' ),
            'rtec_advanced'
        );

	    // translate
	    $translation_options = array(
		    0 => array( 'custom', 'Custom' ),
		    1 => array( 'translate', 'Translations (if available)' )
	    );
	    $this->create_settings_field( array(
		    'option' => 'rtec_options',
		    'name' => 'message_source',
		    'title' => '<label for="use_translations">' . __( 'Messaging Source', 'registrations-for-the-events-calendar' ) . '</label>',
		    'example' => '',
		    'values' => $translation_options,
		    'description' => __( 'Select "Custom" for text saved in the Settings pages, "Translate" to use strictly language files (French, German, Spanish, Dutch, Italian and Russian are available. Contact support to offer your translations)', 'registrations-for-the-events-calendar' ),
		    'callback'  => 'default_radio',
		    'class' => 'default-text',
		    'page' => 'rtec_advanced',
		    'section' => 'rtec_advanced',
		    'default' => 'custom'
	    ));

	    $pformat_select = array(
		    array( '1', '(123) 456-7890' ),
		    array( '2', '12 3456 7890' ),
		    array( '3', '(12) 3456 7890' )
	    );

	    $this->create_settings_field( array(
		    'name' => 'phone_format',
		    'title' => '<label for="phone_format">' . __( 'Phone Number Format', 'registrations-for-the-events-calendar' ) . '</label>', // label for the input field
		    'callback'  => 'default_select', // name of the function that outputs the html
		    'page' => 'rtec_advanced', // matches the section name
		    'section' => 'rtec_advanced', // matches the section name
		    'option' => 'rtec_options', // matches the options name
		    'class' => 'default-text', // class for the wrapper and input field
		    'fields' => $pformat_select,
		    'description' => __( "Formatting for 10 digit phone numbers", 'registrations-for-the-events-calendar' ),
		    'after' => '<a href="https://roundupwp.com/faq/format-phone-numbers/" target="_blank">Custom Formatting Options</a>'
	    ) );

        // preserve database  preserve_db
        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'preserve_registrations',
            'title' => '<label for="rtec_preserve_registrations">' . __( 'Preserve registrations on uninstall', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => '',
            'description' => __( 'Keep your registration records preserved in the database when you uninstall the plugin', 'registrations-for-the-events-calendar' ),
            'callback'  => 'preserve_checkbox',
            'class' => 'default-text',
            'page' => 'rtec_advanced',
            'section' => 'rtec_advanced',
            'default' => true
        ));

	    // preserve settings
	    $this->create_settings_field( array(
		    'option' => 'rtec_options',
		    'name' => 'preserve_settings',
		    'title' => '<label for="rtec_preserve_settings">' . __( 'Preserve settings on uninstall', 'registrations-for-the-events-calendar' ) . '</label>',
		    'example' => '',
		    'description' => __( 'Keep your form and email settings preserved when you uninstall the plugin', 'registrations-for-the-events-calendar' ),
		    'callback'  => 'preserve_checkbox',
		    'class' => 'default-text',
		    'page' => 'rtec_advanced',
		    'section' => 'rtec_advanced',
		    'default' => true
	    ));

        /**
         * Email Settings
         */

        /* General Email Options */

        add_settings_section(
            'rtec_email_all',
            __( 'General Email Options', 'registrations-for-the-events-calendar' ),
            array( $this, 'blank' ),
            'rtec_email_all'
        );

        // confirmation from address
        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'confirmation_from_address',
            'title' => '<label>' . __( 'Confirmation/Notification From Address', 'registrations-for-the-events-calendar' ) . '<span class="rtec-individual-available">&#42;</span></label>',
            'example' => __( 'example', 'registrations-for-the-events-calendar' ) . ': registrations@yoursite.com',
            'description' => __( 'Enter an email address that you would like emails to be sent from', 'registrations-for-the-events-calendar' ),
            'callback'  => 'default_text',
            'class' => 'regular-text',
            'page' => 'rtec_email_all',
            'section' => 'rtec_email_all',
            'default' => get_option( 'admin_email' )
        ));

        // date format
        $this->create_settings_field( array(
            'name' => 'custom_date_format',
            'title' => '<label for="rtec_custom_date_format">' . __( 'Custom Date Format', 'registrations-for-the-events-calendar' ) . '</label>', // label for the input field
            'callback'  => 'customize_custom_date_format', // name of the function that outputs the html
            'page' => 'rtec_email_all', // matches the section name
            'section' => 'rtec_email_all', // matches the section name
            'option' => 'rtec_options', // matches the options name
            'class' => 'default-text', // class for the wrapper and input field
            'description' => __( 'If you would like a custom date format in your messages, enter it here using the examples as a guide', 'registrations-for-the-events-calendar' ),
            'default' => rtec_get_date_time_format()
        ));

        /* Notification Email Settings Section */

        add_settings_section(
            'rtec_email_notification',
            'Notification Email',
            array( $this, 'blank' ),
            'rtec_email_notification'
        );

        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'disable_notification',
            'title' => '<label for="rtec_disable_notification">' . __( 'Disable Notification Email', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => '',
            'description' => '',
            'callback'  => 'default_checkbox',
            'class' => '',
            'page' => 'rtec_email_notification',
            'section' => 'rtec_email_notification',
            'default' => false
        ));

        // notification recipients
        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'recipients',
            'title' => '<label>' . __( 'Notification Recipient(s) Email', 'registrations-for-the-events-calendar' ) . '<span class="rtec-individual-available">&#42;</span></label>',
            'example' => __( 'example', 'registrations-for-the-events-calendar' ) . ': one@yoursite.com, two@yoursite.com',
            'description' => 'Enter the email addresses you would like notification emails to go to separated by commas',
            'callback'  => 'default_text',
            'class' => 'large-text',
            'page' => 'rtec_email_notification',
            'section' => 'rtec_email_notification',
            'default' => get_option( 'admin_email' )
        ));

        // notification from
        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'notification_from',
            'title' => '<label>' . __( 'Notification From', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => __( 'example', 'registrations-for-the-events-calendar' ) . ': ' . __( 'New Registration', 'registrations-for-the-events-calendar' ),
            'description' => __( 'Enter the name you would like the notification email to come from', 'registrations-for-the-events-calendar' ),
            'callback'  => 'default_text',
            'class' => 'regular-text',
            'page' => 'rtec_email_notification',
            'section' => 'rtec_email_notification',
            'default' => 'WordPress'
        ));

        // notification subject
        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'notification_subject',
            'title' => '<label>' . __( 'Notification Subject', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => __( 'example', 'registrations-for-the-events-calendar' ) . ': ' . __( 'Registration Notification', 'registrations-for-the-events-calendar' ),
            'description' => __( 'Enter a subject for the notification email', 'registrations-for-the-events-calendar' ),
            'callback'  => 'default_text',
            'class' => 'regular-text',
            'page' => 'rtec_email_notification',
            'section' => 'rtec_email_notification',
            'default' => __( 'Registration Notification', 'registrations-for-the-events-calendar' )
        ));

        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'use_custom_notification',
            'title' => '<label for="rtec_disable_notification">' . __( 'Use Custom Notification Message', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => '',
            'description' => __( 'Click to reveal and use a custom message that you can configure', 'registrations-for-the-events-calendar' ),
            'callback'  => 'default_checkbox',
            'class' => '',
            'page' => 'rtec_email_notification',
            'section' => 'rtec_email_notification',
            'default' => false
        ));

        // notification message
        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'notification_message',
            'title' => '<label>' . __( 'Notification Message', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => '',
            'default' => '<p>The following submission was made for: {event-title} at {venue} on {event-date}</p><table><tbody><tr><td>First&#58;</td><td>{first}</td></tr><tr><td>Last&#58;</td><td>{last}</td></tr><tr><td>Email&#58;</td><td>{email}</td></tr><tr><td>Phone&#58;</td><td>{phone}</td></tr><tr><td>Other&#58;</td><td>{other}</td></tr></tbody></table>',
            'description' => '',
            'callback'  => 'rich_editor',
            'settings' => array(
                'quicktags' => array( 'buttons' => 'strong,em,del,ul,ol,li,close,link,img' ),
                'tinymce' => array(
                    'toolbar1' => 'formatselect,bold,italic,underline,blockquote,bullist,numlist,link,unlink,forecolor,undo,redo,spellchecker',
                    'toolbar2' => ''),
                'textarea_rows' => '15',
                'media_buttons' => false,
                'wpautop' => true
            ),
            'class' => 'rtec-notification-message-tr',
            'page' => 'rtec_email_notification',
            'section' => 'rtec_email_notification',
            'columns' => '60',
            'preview' => true,
            'legend' => true,
        ));

        /* Confirmation Email Settings Section */

        add_settings_section(
            'rtec_email_confirmation',
            __( 'Confirmation Email', 'registrations-for-the-events-calendar' ),
            array( $this, 'blank' ),
            'rtec_email_confirmation'
        );

        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'disable_confirmation',
            'title' => '<label for="rtec_disable_confirmation">' . __( 'Disable Confirmation Email', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => '',
            'description' => '',
            'callback'  => 'default_checkbox',
            'class' => '',
            'page' => 'rtec_email_confirmation',
            'section' => 'rtec_email_confirmation',
            'default' => false
        ));

        // confirmation from name
        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'confirmation_from',
            'title' => '<label>' . __( 'Confirmation From', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => __( 'example: Your Site', 'registrations-for-the-events-calendar' ),
            'description' => __( 'Enter the name you would like visitors to get the email from', 'registrations-for-the-events-calendar' ),
            'callback'  => 'default_text',
            'class' => 'regular-text',
            'page' => 'rtec_email_confirmation',
            'section' => 'rtec_email_confirmation',
            'default' => get_bloginfo( 'name' )
        ));

        // confirmation subject
        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'confirmation_subject',
            'title' => '<label>' . __( 'Confirmation Subject', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => __( 'example: Registration Confirmation', 'registrations-for-the-events-calendar' ),
            'description' => __( 'Enter a subject for the confirmation email', 'registrations-for-the-events-calendar' ),
            'callback'  => 'default_text',
            'class' => 'regular-text',
            'page' => 'rtec_email_confirmation',
            'section' => 'rtec_email_confirmation',
            'default' => '{event-title}'
        ));

        // confirmation message
        $this->create_settings_field( array(
            'option' => 'rtec_options',
            'name' => 'confirmation_message',
            'title' => '<label>' . __( 'Confirmation Message', 'registrations-for-the-events-calendar' ) . '</label>',
            'example' => '',
            'default' => __( 'Hey {first},<br /><br />You are registered for {event-title} at {venue} on {event-date}. We are looking forward to having you there. The event will be held at this location:<br /><br />{venue-address}<br />{venue-city}, {venue-state} {venue-zip}<br /><br />See you there!', 'registrations-for-the-events-calendar' ),
            'description' => '',
            'callback'  => 'rich_editor',
            'settings' => array(
                'quicktags' => array( 'buttons' => 'strong,em,del,ul,ol,li,close,link,img' ),
                'tinymce' => array(
                    'toolbar1' => 'formatselect,bold,italic,underline,blockquote,bullist,numlist,link,unlink,forecolor,undo,redo,spellchecker',
                    'toolbar2' => ''),
                'textarea_rows' => '15',
                'media_buttons' => FALSE,
                'wpautop' => true
            ),
            'class' => '',
            'page' => 'rtec_email_confirmation',
            'section' => 'rtec_email_confirmation',
            'columns' => '60',
            'preview' => true,
            'legend' => true,
        ));

    }

    public function the_description( $description ) {
        ?>
        <span class="description"><?php echo esc_html( $description ); ?></span>
        <?php
    }

    public function default_text( $args )
    {
        // get option 'text_string' value from the database
        $options = get_option( $args['option'] );
        $default = isset( $args['default'] ) ? esc_attr( $args['default'] ) : '';
        $option_string = ( isset( $options[ $args['name'] ] ) ) ? esc_attr( $options[ $args['name'] ] ) : $default;
        $type = ( isset( $args['type'] ) ) ? 'type="'. $args['type'].'"' : 'type="text"';
        ?>
        <input id="rtec-<?php echo $args['name']; ?>" class="<?php echo $args['class']; ?>" name="<?php echo $args['option'].'['.$args['name'].']'; ?>" <?php echo $type; ?> value="<?php echo $option_string; ?>"/>
        <br><?php $this->the_description( $args['description'] ); ?>
        <?php
    }

    public function default_select( $args )
    {
        $options = get_option( $args['option'] );
        $selected = ( isset( $options[ $args['name'] ] ) ) ? esc_attr( $options[ $args['name'] ] ) : '';
        ?>
        <select name="<?php echo $args['option'].'['.$args['name'].']'; ?>" id="rtec_<?php echo $args['name']; ?>" class="<?php echo $args['class']; ?>">
            <?php foreach ( $args['fields'] as $field ) : ?>
                <option value="<?php echo $field[0]; ?>" id="rtec-<?php echo $args['name']; ?>" class="<?php echo $args['class']; ?>"<?php if( $selected == $field[0] ) { echo ' selected'; } ?>><?php _e( $field[1], 'registrations-for-the-events-calendar' ); ?></option>
            <?php endforeach; ?>
        </select>
	    <?php
	    if ( isset( $args['after'] ) ) {
		    echo $args['after'];
	    }
	    ?>
        <br><?php $this->the_description( $args['description'] ); ?>
        <?php
    }

    public function default_checkbox( $args )
    {
        $options = get_option( $args['option'] );
        $option_checked = ( isset( $options[ $args['name'] ] ) ) ? esc_attr( $options[ $args['name'] ] ) : $args['default'];
        ?>
        <input name="<?php echo $args['option'].'['.$args['name'].']'; ?>" id="rtec_<?php echo $args['name']; ?>" type="checkbox" <?php if ( $option_checked == true ) echo "checked"; ?> />
        <br><?php $this->the_description( $args['description'] ); ?>
        <?php
    }

    public function default_radio( $args )
    {
        $options = get_option( $args['option'] );
        $option_checked = ( isset( $options[ $args['name'] ] ) ) ? esc_attr( $options[ $args['name'] ] ) : $args['default'];
        ?>
        <?php foreach ( $args['values'] as $value ) : ?>
        <input name="<?php echo $args['option'].'['.$args['name'].']'; ?>" id="rtec_<?php echo $args['name']; ?>" type="radio" value="<?php echo $value[0]; ?>" <?php if ( $option_checked == $value[0] ) echo "checked"; ?> /><label class="rtec-radio-label"><?php echo $value[1]; ?></label>
        <?php endforeach; ?>
        <br><?php $this->the_description( $args['description'] ); ?>
        <?php
    }

	public function preserve_checkbox( $args )
	{
		$options = get_option( $args['option'] );
		if ( isset( $options['preserve_db'] ) && $options['preserve_db'] == true ) {
			$option_checked = true;
		} else {
			$option_checked = ( isset( $options[ $args['name'] ] ) ) ? esc_attr( $options[ $args['name'] ] ) : $args['default'];
		}
		?>
		<input name="<?php echo $args['option'].'['.$args['name'].']'; ?>" id="rtec_<?php echo $args['name']; ?>" type="checkbox" <?php if ( $option_checked == true ) echo "checked"; ?> />
		<br><?php $this->the_description( $args['description'] ); ?>
		<?php
	}

    public function default_color( $args )
    {
        $options = get_option( $args['option'] );
        $option_string = ( isset( $options[ $args['name'] ] ) ) ? esc_attr( $options[ $args['name'] ] ) : '';
        ?>
        <input name="<?php echo $args['option'].'['.$args['name'].']'; ?>" id="rtec_<?php echo $args['name']; ?>" value="#<?php esc_attr_e( str_replace('#', '', $option_string ) ); ?>" class="rtec-colorpicker" />
        <?php
    }
    
    public function width_and_height_settings( $args )
    {
        $options = get_option( $args['option'] );
        $default = isset( $args['default'] ) ? $args['default'] : '';
        $option_string = ( isset( $options[ $args['name'] ] ) ) ? esc_attr( $options[ $args['name'] ] ) : $default;
        $selected = ( isset( $options[ $args['name'] . '_unit' ] ) ) ? esc_attr( $options[ $args['name'] . '_unit' ] ) : $args['default_unit'];
        ?>
        <input name="<?php echo $args['option'].'['.$args['name'].']'; ?>" id="rtec-<?php echo $args['name']; ?>" class="<?php echo $args['class']; ?>" type="number" value="<?php echo $option_string; ?>" />
        <select name="<?php echo $args['option'].'['.$args['name'].'_unit]'; ?>" id="rtec-<?php echo $args['name'].'_unit'; ?>">
            <option value="px" <?php if ( $selected == "px" ) echo 'selected="selected"' ?> >px</option>
            <option value="%" <?php if ( $selected == "%" ) echo 'selected="selected"' ?> >%</option>
        </select>
        
        <?php
    }

    public function form_field_select( $args )
    {
        $options = get_option( $args['option'] );
        foreach( $args['fields'] as $field ) {
            $label = isset( $field[1] ) ? $field[1] : '';
            $custom_label = isset( $options[ $field[0].'_label' ] ) ? esc_attr( $options[ $field[0].'_label' ]  ) : $label;
            $show = isset( $options[ $field[0].'_show' ] ) ? esc_attr( $options[ $field[0].'_show' ] ) : $field[3];
            $require = isset( $options[ $field[0].'_require' ] ) ? esc_attr( $options[ $field[0].'_require' ] ) : $field[4];
            $error = isset( $options[ $field[0].'_error' ] ) ? esc_attr( $options[ $field[0].'_error' ] ) : $field[2];
            $valid_count = isset( $options[ $field[0].'_valid_count' ] ) ? esc_attr( $options[ $field[0].'_valid_count' ] ) : $field[5];
            ?>
            <div class="rtec-field-options-wrapper rtec-field-wrapper-<?php echo $field[0]; ?>">
                <h4><?php _e( $label, 'registrations-for-the-events-calendar' ); ?></h4>
                <p>
                    <label><?php _e( 'Label', 'registrations-for-the-events-calendar' ); ?>:</label><input type="text" name="<?php echo $args['option'].'['.$field[0].'_label]'; ?>" value="<?php echo $custom_label; ?>" class="large-text">
                </p>
                <p class="rtec-checkbox-row">
                    <input type="checkbox" class="rtec_include_checkbox" name="<?php echo $args['option'].'['.$field[0].'_show]'; ?>" <?php if ( $show == true ) { echo 'checked'; } ?>>
                    <label><?php _e( 'include', 'registrations-for-the-events-calendar' ); ?></label>

                    <input type="checkbox" class="rtec_require_checkbox" name="<?php echo $args['option'].'['.$field[0].'_require]'; ?>" <?php if ( $require == true ) { echo 'checked'; } ?>>
                    <label><?php _e( 'require', 'registrations-for-the-events-calendar' ); ?></label><br>
                </p>
                <p class="rtec-e-message rtec-e-message-<?php echo $field[0]; ?>">
                    <label><?php _e( 'Error Message:', 'registrations-for-the-events-calendar' ); ?></label>
                    <input type="text" name="<?php echo $args['option'].'['.$field[0].'_error]'; ?>" value="<?php echo $error; ?>" class="large-text rtec-other-input">
                </p>
                <?php if ( $field[0] === 'phone' ) : ?>
                <p>
                    <label><?php _e( 'Required length for validation:', 'registrations-for-the-events-calendar' ); ?></label>
                    <input type="text" name="<?php echo $args['option'].'['.$field[0].'_valid_count]'; ?>" value="<?php echo $valid_count; ?>" class="large-text rtec-valid-count-input">
                    <a class="rtec-tooltip-link" href="JavaScript:void(0);"><i class="fas fa fa-question-circle"></i></a>
                    <span class="rtec-tooltip rtec-notice"><?php _e( 'Enter the length or lengths of the responses that are valid for this field separated by commas. For example, to accept North American phone numbers with and without area codes you would enter "7, 10". If area code is required, enter "10"' ); ?></span>
                </p>
                <?php endif; ?>
                <a href="javascript:void(0);" class="rtec-reveal-field-atts button-secondary">+ <?php _e( 'Show Notes', 'registrations-for-the-events-calendar' ); ?></a>
                <div class="rtec-field-atts">
                    <ul>
                    <?php if ( isset( $field[7] ) && $field[7] ) : ?>
                    <li><?php _e( 'prefilled with user data for logged-in users', 'registrations-for-the-events-calendar' ); ?></li>
                    <?php endif; ?>
                    <?php if ( isset( $field[8] ) && $field[8] ) : ?>
                        <li><?php _e( 'used in attendee list', 'registrations-for-the-events-calendar' ); ?></li>

                    <?php endif; ?>
                    <?php if ( isset( $field[6] ) ) : ?>
                        <li><?php _e( 'Email template text', 'registrations-for-the-events-calendar' ); ?>: <?php echo $field[6]; ?></li>
                    <?php endif; ?>
                    </ul>
                </div>
            </div>
        <?php
        } // endforeach
        // the other field is treated specially
        $label = isset( $options[ 'other_label' ] ) ? esc_attr( $options[ 'other_label' ] ) : __( 'Other', 'registrations-for-the-events-calendar' );
        $show = isset( $options[ 'other_show' ] ) ? esc_attr( $options[ 'other_show' ] ) : false;
        $require = isset( $options[ 'other_require' ] ) ? $options[ 'other_require' ] : false;
        $error = isset( $options[ 'other_error' ] ) ? $options[ 'other_error' ] : __( 'This is required', 'registrations-for-the-events-calendar' );
        ?>
        <div class="rtec-field-options-wrapper">
            <h4><?php _e( 'Other', 'registrations-for-the-events-calendar' ); ?> <span>(<?php _e( 'will create a plain text field with your label', 'registrations-for-the-events-calendar' ); ?>)</span></h4>
            <p>
                <label><?php _e( 'Label', 'registrations-for-the-events-calendar' ); ?>:</label>
                <input type="text" name="<?php echo $args['option'].'[other_label]'; ?>" value="<?php echo $label; ?>" class="large-text">
            </p>
            <p class="rtec-checkbox-row">
                <input type="checkbox" class="rtec_include_checkbox" name="<?php echo $args['option'].'[other_show]'; ?>" <?php if( $show == true ) { echo 'checked'; } ?>>
                <label><?php _e( 'include', 'registrations-for-the-events-calendar' ); ?></label>

                <input type="checkbox" class="rtec_require_checkbox" name="<?php echo $args['option'].'[other_require]'; ?>" <?php if( $require == true ) { echo 'checked'; } ?>>
                <label><?php _e( 'require', 'registrations-for-the-events-calendar' ); ?></label>
            </p>
            <p>
                <label><?php _e( 'Error Message:', 'registrations-for-the-events-calendar' ); ?></label>
                <input type="text" name="<?php echo $args['option'].'[other_error]'; ?>" value="<?php esc_attr_e( $error ); ?>" class="large-text rtec-other-input">
            </p>
            <a href="javascript:void(0);" class="rtec-reveal-field-atts button-secondary">+ <?php _e( 'Show Notes', 'registrations-for-the-events-calendar' ); ?></a>
            <div class="rtec-field-atts">
                <ul>
                    <li><?php _e( 'Email template text', 'registrations-for-the-events-calendar' ); ?>: {other}</li>
                </ul>
            </div>
        </div>

        <?php
            $custom_field_names = isset( $options['custom_field_names'] ) ? explode( ',', $options['custom_field_names'] ) : array();
            $custom_field_string = isset( $options['custom_field_names'] ) ? $options['custom_field_names'] : '';
        ?>
        <?php foreach( $custom_field_names as $custom_field ) : ?>
            <?php if ( !empty( $custom_field) ) : ?>
            <?php
            $custom_field_id = str_replace( 'custom', '', $custom_field );
            $label = isset( $options[$custom_field . '_label'] ) ? $options[$custom_field . '_label'] : 'Custom '.$custom_field_id;
            $error = isset( $options[$custom_field . '_error'] ) ? $options[$custom_field . '_error'] : __( 'This is required', 'registrations-for-the-events-calendar' );
            $show = isset( $options[$custom_field . '_show'] ) ? $options[$custom_field . '_show'] : false;
            $require = isset( $options[$custom_field . '_require'] ) ? $options[$custom_field . '_require'] : false;
            ?>
        <div id="rtec-custom-field-<?php echo $custom_field_id; ?>" class="rtec-field-options-wrapper rtec-custom-field"  data-name="<?php echo $custom_field; ?>">
            <a href="JavaScript:void(0);" class="rtec-custom-field-remove"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
            <h4><?php _e( 'Custom Field', 'registrations-for-the-events-calendar' ); ?> <?php echo $custom_field_id; ?></h4>
            <p>
                <label><?php _e( 'Label', 'registrations-for-the-events-calendar' ); ?>:</label><input type="text" name="rtec_options[<?php echo $custom_field; ?>_label]" value="<?php echo $label; ?>" class="large-text">
            </p>
            <p class="rtec-checkbox-row">
                <input type="checkbox" class="rtec_include_checkbox" name="rtec_options[<?php echo $custom_field; ?>_show]" <?php if ( $show ) { echo 'checked=checked'; } ?>>
                <label><?php _e( 'include', 'registrations-for-the-events-calendar' ); ?></label>

                <input type="checkbox" class="rtec_require_checkbox" name="rtec_options[<?php echo $custom_field; ?>_require]" <?php if ( $require ) { echo 'checked=checked'; } ?>>
                <label><?php _e( 'require', 'registrations-for-the-events-calendar' ); ?></label>
            </p>
            <p>
                <label><?php _e( 'Error Message', 'registrations-for-the-events-calendar' ); ?>:</label>
                <input type="text" name="rtec_options[<?php echo $custom_field; ?>_error]" value="<?php echo $error; ?>" class="large-text rtec-other-input">
            </p>
            <?php if ( isset( $options[$custom_field . '_label'] ) ) : ?>
                <a href="javascript:void(0);" class="rtec-reveal-field-atts button-secondary">+ <?php _e( 'Show Notes', 'registrations-for-the-events-calendar' ); ?></a>
                <div class="rtec-field-atts">
                    <ul>
                        <li><?php _e( 'Email template text', 'registrations-for-the-events-calendar' ); ?>: {<?php echo $options[$custom_field . '_label']; ?>}</li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
	    <?php endif; ?>
        <?php endforeach; ?>
        <div class="rtec-green-bg"><a href="JavaScript:void(0);" class="rtec-add-field"><i class="fa fa-plus" aria-hidden="true"></i> <?php _e( 'Add Field', 'registrations-for-the-events-calendar'  ); ?></a></div>
        <input type="hidden" id="rtec_custom_field_names" name="rtec_options[custom_field_names]" value="<?php echo $custom_field_string; ?>"/>
	    <?php
	    // the other field is treated specially
	    $label = isset( $options[ 'terms_conditions_label' ] ) ? esc_attr( $options[ 'terms_conditions_label' ] ) : __( 'I accept the terms and conditions', 'registrations-for-the-events-calendar' );
	    $require = isset( $options[ 'terms_conditions_require' ] ) ? $options[ 'terms_conditions_require' ] : false;
	    $error = isset( $options[ 'terms_conditions_error' ] ) ? esc_attr( $options[ 'terms_conditions_error' ] ) :  __( 'This is required', 'registrations-for-the-events-calendar' );
	    $link = isset( $options[ 'terms_conditions_link' ] ) ? esc_attr( $options[ 'terms_conditions_link' ] ) :  '';
	    $link_label = isset( $options[ 'terms_conditions_link_label' ] ) ? esc_attr( $options[ 'terms_conditions_link_label' ] ) :  __( 'Terms and Conditions Page', 'registrations-for-the-events-calendar' );

	    ?>
        <div class="rtec-field-options-wrapper rtec-custom-field" style="margin-top: 0.5em;">
            <h4><?php _e( 'Terms and Conditions', 'registrations-for-the-events-calendar' ); ?> <span>(<?php _e( 'Checkbox field useful for GDPR compliance', 'registrations-for-the-events-calendar' ); ?>)</span></h4>
            <p>
                <label><?php _e( 'Label', 'registrations-for-the-events-calendar' ); ?>:</label><input type="text" name="<?php echo $args['option'].'[terms_conditions_label]'; ?>" value="<?php echo $label; ?>"  class="large-text"/>
            </p>
            <p class="rtec-checkbox-row">
                <input type="checkbox" class="rtec_require_checkbox" name="<?php echo $args['option'].'[terms_conditions_require]'; ?>" <?php if( $require == true ) { echo 'checked'; } ?>>
                <label><?php _e( 'require and include', 'registrations-for-the-events-calendar' ); ?></label>
            </p>
            <p>
                <label><?php _e( 'Terms and Conditions Page URL:', 'registrations-for-the-events-calendar' ); ?></label>
                <input type="text" name="<?php echo $args['option'].'[terms_conditions_link]'; ?>" value="<?php echo $link; ?>" class="large-text rtec-terms_conditions-input">
            </p>
            <p>
                <label><?php _e( 'Link Text:', 'registrations-for-the-events-calendar' ); ?></label>
                <input type="text" name="<?php echo $args['option'].'[terms_conditions_link_label]'; ?>" value="<?php echo $link_label; ?>" class="large-text rtec-terms_conditions-input">
            </p>
            <p>
                <label><?php _e( 'Error Message:', 'registrations-for-the-events-calendar' ); ?></label>
                <input type="text" name="<?php echo $args['option'].'[terms_conditions_error]'; ?>" value="<?php echo $error; ?>" class="large-text rtec-terms_conditions-input">
            </p>
        </div>
        <?php
        // the other field is treated specially
        $label = isset( $options[ 'recaptcha_label' ] ) ? esc_attr( $options[ 'recaptcha_label' ] ) : __( 'What is', 'registrations-for-the-events-calendar' );
        $require = isset( $options[ 'recaptcha_require' ] ) ? $options[ 'recaptcha_require' ] : false;
        $error = isset( $options[ 'recaptcha_error' ] ) ? esc_attr( $options[ 'recaptcha_error' ] ) :  __( 'Please try again', 'registrations-for-the-events-calendar' );
        ?>
        <div class="rtec-field-options-wrapper" style="margin-top: 0.5em;">
            <h4><?php _e( 'Recaptcha', 'registrations-for-the-events-calendar' ); ?> <span>(<?php _e( 'Simple math question to avoid spam entries. Spam "honey pot" field is in the form by default', 'registrations-for-the-events-calendar' ); ?>)</span></h4>
            <p>
                <label><?php _e( 'Label', 'registrations-for-the-events-calendar' ); ?>:</label><input type="text" name="<?php echo $args['option'].'[recaptcha_label]'; ?>" value="<?php echo $label; ?>" />
                <span> 2 + 5</span>
            </p>
            <p class="rtec-checkbox-row">
                <input type="checkbox" class="rtec_require_checkbox" name="<?php echo $args['option'].'[recaptcha_require]'; ?>" <?php if( $require == true ) { echo 'checked'; } ?>>
                <label><?php _e( 'require and include', 'registrations-for-the-events-calendar' ); ?></label>
            </p>
            <p>
                <label><?php _e( 'Error Message:', 'registrations-for-the-events-calendar' ); ?></label>
                <input type="text" name="<?php echo $args['option'].'[recaptcha_error]'; ?>" value="<?php echo $error; ?>" class="large-text rtec-recaptcha-input">
            </p>
        </div>
        <?php
    }

    public function custom_code( $args )
    {
        $options = get_option( $args['option'] );
        $option_string = ( isset( $options[ $args['name'] ] ) ) ? esc_attr( $options[ $args['name'] ] ) : '';
        ?>
        <p><?php _e( $args['description'], 'registrations-for-the-events-calendar' ) ; ?></p>
        <textarea name="<?php echo $args['option'].'['.$args['name'].']'; ?>" id="rtec_<?php echo $args['name']; ?>" style="width: 70%;" rows="7"><?php esc_attr_e( stripslashes( $option_string ) ); ?></textarea>
        <?php
    }

    public function deadline_offset( $args )
    {
        $options = get_option( $args['option'] );
        $default = 0;
        $option_string = ( isset( $options[ $args['name'] ] ) ) ? esc_attr( $options[ $args['name'] ] ) : $default;
        $selected = ( isset( $options[ $args['name'] . '_unit' ] ) ) ? esc_attr( $options[ $args['name'] . '_unit' ] ) : '3600';
        ?>
        <span><?php _e( 'Accept registrations up until', 'registrations-for-the-events-calendar' ); ?></span>
        <input name="<?php echo $args['option'].'['.$args['name'].']'; ?>" id="rtec_<?php echo $args['name']; ?>" type="number" value="<?php echo $option_string; ?>"/>
        <select name="<?php echo $args['option'].'['.$args['name'].'_unit]'; ?>">
            <option value="60" <?php if ( $selected == "60" ) echo 'selected="selected"' ?> ><?php esc_attr_e( 'Minutes' ); ?></option>
            <option value="3600" <?php if ( $selected == "3600" ) echo 'selected="selected"' ?> ><?php esc_attr_e( 'Hours' ); ?></option>
            <option value="86400" <?php if ( $selected == "86400" ) echo 'selected="selected"' ?> ><?php esc_attr_e( 'Days' ); ?></option>
        </select>
        <span><?php _e( 'before event start time', 'registrations-for-the-events-calendar' ); ?></span>
     <?php
    }
    
    public function num_registrations_messages( $args ) {
        $options = get_option( $args['option'] );
        $text_before_up = ( isset( $options['attendance_text_before_up'] ) ) ? esc_attr( $options['attendance_text_before_up'] ) : __( 'Join', 'registrations-for-the-events-calendar' );
        $text_after_up = ( isset( $options['attendance_text_after_up'] ) ) ? esc_attr( $options['attendance_text_after_up'] ) : __( 'others!', 'registrations-for-the-events-calendar' );
        $one_up = ( isset( $options['attendance_text_one_up'] ) ) ? esc_attr( $options['attendance_text_one_up'] ) : __( 'Join one other person', 'registrations-for-the-events-calendar' );
        $text_before_down = ( isset( $options['attendance_text_before_down'] ) ) ? esc_attr( $options['attendance_text_before_down'] ) : __( 'Only', 'registrations-for-the-events-calendar' );
        $text_after_down = ( isset( $options['attendance_text_after_down'] ) ) ? esc_attr( $options['attendance_text_after_down'] ) : __( 'spots left', 'registrations-for-the-events-calendar' );
        $one_down = ( isset( $options['attendance_text_one_down'] ) ) ? esc_attr( $options['attendance_text_one_down'] ) : __( 'Only one spot left!', 'registrations-for-the-events-calendar' );
        $none_yet = ( isset( $options['attendance_text_none_yet'] ) ) ? esc_attr( $options['attendance_text_none_yet'] ) : __( 'Be the first!', 'registrations-for-the-events-calendar' );
        $closed = ( isset( $options['registrations_closed_message'] ) ) ? esc_attr( $options['registrations_closed_message'] ) : __( 'Registrations are closed for this event', 'registrations-for-the-events-calendar' );
        $option_checked = ( isset( $options['include_attendance_message'] ) ) ? $options['include_attendance_message'] : true;
        $option_selected = ( isset( $options['attendance_message_type'] ) ) ? $options['attendance_message_type'] : 'up';
        ?>
        <input name="<?php echo $args['option'].'[include_attendance_message]'; ?>" id="rtec_include_attendance_message" type="checkbox" <?php if ( $option_checked ) echo "checked"; ?> />
        <label for="rtec_include_attendance_message"><?php _e( 'include registrations availability message', 'registrations-for-the-events-calendar' ); ?></label>
        <div class="rtec-message-group-wrap">
            <div class="rtec-availability-options-wrapper" id="rtec-message-type-wrapper">
                <div class="rtec-checkbox-row">
                    <h4><?php _e( 'Message Type', 'registrations-for-the-events-calendar' ); ?></h4>
                    <div class="rtec-input-group">
                        <div class="rtec-admin-row">
                            <div class="rtec-admin-2-columns">
                                <input class="rtec_attendance_message_type" id="rtec_guests_attending_type" name="<?php echo $args['option'].'[attendance_message_type]'; ?>" type="radio" value="up" <?php if ( $option_selected == 'up' ) echo "checked"; ?> />
                                <label for="rtec_guests_attending_type"><?php _e( 'guests attending (count up)', 'registrations-for-the-events-calendar' ); ?></label>
                            </div>
                        <div class="rtec-admin-2-columns"><input class="rtec_attendance_message_type" id="rtec_spots_remaining_type" name="<?php echo $args['option'].'[attendance_message_type]'; ?>" type="radio" value="down" <?php if ( $option_selected == 'down' ) echo "checked"; ?>/>
                            <label for="rtec_spots_remaining_type"><?php _e( 'spots remaining (count down, <strong>only for events with limits</strong>)', 'registrations-for-the-events-calendar' ); ?></label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="rtec-admin-row">
                <div class="rtec-availability-options-wrapper rtec-admin-2-columns" id="rtec-message-text-wrapper-up">

                    <h4><?php _e( 'Guests Attending Message Text', 'registrations-for-the-events-calendar' ); ?></h4>
                    <div class="rtec-input-group">
                        <label for="rtec_text_before_up"><?php _e( 'Text Before: ', 'registrations-for-the-events-calendar' ); ?></label><input id="rtec_text_before_up" type="text" name="<?php echo $args['option'].'[attendance_text_before_up]'; ?>" value="<?php echo $text_before_up; ?>"/></br>
                        <label for="rtec_text_after_up"><?php _e( 'Text After: ', 'registrations-for-the-events-calendar' ); ?></label><input id="rtec_text_after_up" type="text" name="<?php echo $args['option'].'[attendance_text_after_up]'; ?>" value="<?php echo $text_after_up; ?>"/>
                        <p class="description">Example: "<strong>Join</strong> 20 <strong>others.</strong>"</p>
                        <br>
                        <label for="rtec_text_one_up"><?php _e( 'Message if exactly 1 registration: ', 'registrations-for-the-events-calendar' ); ?></label>
                        <input id="rtec_text_one_up" type="text" class="large-text" name="<?php echo $args['option'].'[attendance_text_one_up]'; ?>" value="<?php echo $one_up; ?>"/>
                    </div>
                </div>

                <div class="rtec-availability-options-wrapper rtec-admin-2-columns" id="rtec-message-text-wrapper-down">
                    <h4><?php _e( 'Spots Remaining Message Text', 'registrations-for-the-events-calendar' ); ?></h4>
                    <div class="rtec-input-group">
                        <label for="rtec_text_before_down"><?php _e( 'Text Before: ', 'registrations-for-the-events-calendar' ); ?></label><input id="rtec_text_before_down" type="text" name="<?php echo $args['option'].'[attendance_text_before_down]'; ?>" value="<?php echo $text_before_down; ?>"/></br>
                        <label for="rtec_text_after_down"><?php _e( 'Text After: ', 'registrations-for-the-events-calendar' ); ?></label><input id="rtec_text_after_down" type="text" name="<?php echo $args['option'].'[attendance_text_after_down]'; ?>" value="<?php echo $text_after_down; ?>"/>
                        <p class="description">Example: "<strong>Only</strong> 5 <strong>spots left.</strong>"</p>
                        <br>
                        <label for="rtec_text_one_down"><?php _e( 'Message if exactly 1 spot left: ', 'registrations-for-the-events-calendar' ); ?></label>
                        <input id="rtec_text_one_down" type="text" class="large-text" name="<?php echo $args['option'].'[attendance_text_one_down]'; ?>" value="<?php echo $one_down; ?>"/>
                    </div>
                </div>
            </div>
            <div class="rtec-availability-options-wrapper" id="rtec-message-text-wrapper-other">

                <h4><?php _e( 'Other Messages', 'registrations-for-the-events-calendar' ); ?></h4>
                <div class="rtec-input-group">
                    <label for="rtec_text_none_yet"><?php _e( 'Message if no registrations yet: ', 'registrations-for-the-events-calendar' ); ?></label>
                    <input id="rtec_text_none_yet" type="text" class="large-text" name="<?php echo $args['option'].'[attendance_text_none_yet]'; ?>" value="<?php echo $none_yet; ?>"/>
                    <br><br>
                    <label for="rtec_registrations_closed_message"><?php _e( 'Message if registrations are closed or filled: ', 'registrations-for-the-events-calendar' ); ?></label>
                    <input id="rtec_registrations_closed_message" type="text" class="large-text" name="<?php echo $args['option'].'[registrations_closed_message]'; ?>" value="<?php echo $closed; ?>"/>
                </div>
            </div>
        </div>
        <?php
    }

    public function rich_editor( $args )
    {
        // get option 'text_string' value from the database
        $options = get_option( $args['option'] );
        $default = isset( $args['default'] ) ? $args['default'] : false;
        $option_string = isset( $options[ $args['name'] ] ) ? str_replace( '{nl}', '<br />', $options[ $args['name'] ] ) : $default;

        $settings = $args['settings'];
        $settings['textarea_name'] = $args['option'] . '[' . $args['name'] . ']';
        wp_editor( $option_string, $args['name'], $settings );

        if ( $args['legend'] ) : ?>
            <br />
            <a class="rtec-tooltip-link" href="JavaScript:void(0);">+ <?php _e( 'Template Text (find and replace)' ); ?></a>
            <span class="rtec-tooltip-table rtec-tooltip rtec-availability-options-wrapper">
            <span class="rtec-col-1">{venue}</span><span class="rtec-col-2"><?php _e( 'Event venue/location', 'registrations-for-the-events-calendar' ); ?></span>
            <span class="rtec-col-1">{venue-address}</span><span class="rtec-col-2"><?php _e( 'Venue street address', 'registrations-for-the-events-calendar' ); ?></span>
            <span class="rtec-col-1">{venue-city}</span><span class="rtec-col-2"><?php _e( 'Venue city', 'registrations-for-the-events-calendar' ); ?></span>
            <span class="rtec-col-1">{venue-state}</span><span class="rtec-col-2"><?php _e( 'Venue state/province', 'registrations-for-the-events-calendar' ); ?></span>
            <span class="rtec-col-1">{venue-zip}</span><span class="rtec-col-2"><?php _e( 'Venue zip code', 'registrations-for-the-events-calendar' ); ?></span>
            <span class="rtec-col-1">{event-title}</span><span class="rtec-col-2"><?php _e( 'Title of event', 'registrations-for-the-events-calendar' ); ?></span>
            <span class="rtec-col-1">{event-date}</span><span class="rtec-col-2"><?php _e( 'Event start date', 'registrations-for-the-events-calendar' ); ?></span>
            <span class="rtec-col-1">{first}</span><span class="rtec-col-2"><?php _e( 'First name of registrant', 'registrations-for-the-events-calendar' ); ?></span>
            <span class="rtec-col-1">{last}</span><span class="rtec-col-2"><?php _e( 'Last name of registrant', 'registrations-for-the-events-calendar' ); ?></span>
            <span class="rtec-col-1">{email}</span><span class="rtec-col-2"><?php _e( 'Email of registrant', 'registrations-for-the-events-calendar' ); ?></span>
            <span class="rtec-col-1">{phone}</span><span class="rtec-col-2"><?php _e( 'Phone number of registrant', 'registrations-for-the-events-calendar' ); ?></span>
            <span class="rtec-col-1">{other}</span><span class="rtec-col-2"><?php _e( 'Value entered in the "other" field', 'registrations-for-the-events-calendar' ); ?></span>
            <span class="rtec-col-1">{unregister-link}</span><span class="rtec-col-2"><?php _e( 'Link for user to remove their registration from an event', 'registrations-for-the-events-calendar' ); ?></span>
            <span class="rtec-col-1">{ical-url}</span><span class="rtec-col-2">Plain text web address to download ical file for event</span>
            <?php
            if ( isset( $options['custom_field_names'] ) ) {

                if ( is_array( $options['custom_field_names'] ) ) {
                    $custom_field_names = $options['custom_field_names'];
                } else {
                    $custom_field_names = explode( ',', $options['custom_field_names'] );
                }

            } else {
                $custom_field_names = array();
            }

            foreach ( $custom_field_names as $field ) {
                if ( isset( $options[ $field . '_label' ] ) && ! empty( $options[ $field . '_label' ] ) ) {
                    echo '<span class="rtec-col-1">{' . esc_html( stripslashes( $options[ $field . '_label' ] ) ) . '}</span><span class="rtec-col-2">Value entered in the '. esc_html( stripslashes( $options[ $field . '_label' ] ) ).' field</span>';
                }
            }
            ?>
        </span>
        <?php endif; ?>

        <br><?php $this->the_description( $args['description'] ); ?>
        <?php
    }

    public function message_text_area( $args )
    {
        // get option 'text_string' value from the database
        $options = get_option( $args['option'] );
        $option_string = ( isset( $options[ $args['name'] ] ) ) ? esc_attr( $options[ $args['name'] ] ) : $args['default'];
        $rows = isset( $args['rows'] ) ? $args['rows'] : '10';
	    $columns = isset( $args['columns'] ) ? $args['columns'] : '70';
	    $preview = isset( $args['preview'] ) ? $args['preview'] : false;
        ?>
        <textarea id="confirmation_message_textarea" class="<?php echo $args['class']; ?> confirmation_message_textarea" name="<?php echo $args['option'].'['.$args['name'].']'; ?>" cols="<?php echo $columns; ?>" rows="<?php echo $rows; ?>"><?php echo $option_string; ?></textarea>

        <?php if ( $args['legend'] ) : ?>
        <a class="rtec-tooltip-link" href="JavaScript:void(0);"><?php _e( 'Template Text (find and replace)', 'registrations-for-the-events-calendar' ); ?></a>
        <span class="rtec-tooltip-table rtec-tooltip rtec-availability-options-wrapper">
            <span class="rtec-col-1">{venue}</span><span class="rtec-col-2">Event venue/location</span>
            <span class="rtec-col-1">{venue-address}</span><span class="rtec-col-2">Venue street address</span>
            <span class="rtec-col-1">{venue-city}</span><span class="rtec-col-2">Venue city</span>
            <span class="rtec-col-1">{venue-state}</span><span class="rtec-col-2">Venue state/province</span>
            <span class="rtec-col-1">{venue-zip}</span><span class="rtec-col-2">Venue zip code</span>
            <span class="rtec-col-1">{event-title}</span><span class="rtec-col-2">Title of event</span>
            <span class="rtec-col-1">{event-date}</span><span class="rtec-col-2">Event start date</span>
            <span class="rtec-col-1">{first}</span><span class="rtec-col-2">First name of registrant</span>
            <span class="rtec-col-1">{last}</span><span class="rtec-col-2">Last name of registrant</span>
            <span class="rtec-col-1">{email}</span><span class="rtec-col-2">Email of registrant</span>
            <span class="rtec-col-1">{phone}</span><span class="rtec-col-2">Phone number of registrant</span>
            <span class="rtec-col-1">{other}</span><span class="rtec-col-2">Information submitted in the "other" field</span>
            <span class="rtec-col-1">{ical-url}</span><span class="rtec-col-2">Plain text web address to download ical file for event</span>
            <?php
            // add custom
            if ( isset( $options['custom_field_names'] ) ) {

                if ( is_array( $options['custom_field_names'] ) ) {
                    $custom_field_names = $options['custom_field_names'];
                } else {
                    $custom_field_names = explode( ',', $options['custom_field_names'] );
                }

            } else {
                $custom_field_names = array();
            }

            foreach ( $custom_field_names as $field ) {
                if ( $options[$field . '_show'] ) {
                    echo '<span class="rtec-col-1">' . '{' . $options[$field . '_label'] . '}' . '</span><span class="rtec-col-2">Custom field</span>';
                }
            }
            ?>
        </span>
        <?php endif; ?>

        <br><?php $this->the_description( $args['description'] ); ?>
        <?php if ( $preview ) : ?>
	    <td>
		    <h4>Preview:</h4>
		    <div class="rtec_js_preview">
			    <pre></pre>
		    </div>
	    </td>
	    <?php endif; ?>
        <?php
    }

    public function timezone()
    {
    }

    public function customize_custom_date_format( $args )
    {
        $options = get_option( $args['option'] );
        $default = rtec_get_date_time_format();
        $option_string = ( isset( $options[ $args['name'] ] ) ) ? $options[ $args['name'] ] : $default;
        //echo rtec_get_date_time_format();
        ?>
        <input name="<?php echo $args['option'].'['.$args['name'].']'; ?>" id="rtec_<?php echo $args['name']; ?>" type="text" value="<?php echo esc_attr( $option_string ); ?>" size="10" placeholder="Eg. F jS, Y" />
        <a href="https://www.roundupwp.com/products/registrations-for-the-events-calendar/docs/date-formatting-guide/" target="_blank"><?php _e( 'Examples' , 'registrations-for-the-events-calendar' ); ?></a>
        <br><?php $this->the_description( $args['description'] ); ?>
        <?php
    }

    /**
     * Makes creating settings easier
     * 
     * @param array $args   extra arguments to create parts of the form fields
     */
    public function create_settings_field( $args = array() )
    {
        add_settings_field(
            $args['name'],
            $args['title'],
            array( $this, $args['callback'] ),
            $args['page'],
            $args['section'],
            $args
        );
    }

    private function get_allowed_tags() {
        $allowed_tags = array(
            'a' => array(
                'class' => array(),
                'href'  => array(),
                'rel'   => array(),
                'title' => array(),
            ),
            'abbr' => array(
                'title' => array(),
            ),
            'b' => array(),
            'blockquote' => array(
                'cite'  => array(),
            ),
            'br' => array(),
            'cite' => array(
                'title' => array(),
            ),
            'code' => array(),
            'del' => array(
                'datetime' => array(),
                'title' => array(),
            ),
            'dd' => array(),
            'div' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'dl' => array(),
            'dt' => array(),
            'em' => array(),
            'h1' => array(),
            'h2' => array(),
            'h3' => array(),
            'h4' => array(),
            'h5' => array(),
            'h6' => array(),
            'i' => array(),
            'img' => array(
                'alt'    => array(),
                'class'  => array(),
                'height' => array(),
                'src'    => array(),
                'width'  => array(),
            ),
            'li' => array(
                'class' => array(),
            ),
            'ol' => array(
                'class' => array(),
            ),
            'p' => array(
                'class' => array(),
            ),
            'q' => array(
                'cite' => array(),
                'title' => array(),
            ),
            'span' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'strike' => array(),
            'strong' => array(),
            'ul' => array(
                'class' => array(),
            ),
            'table' => array(
                'style'  => array(),
                'class'  => array(),
                'cellpadding' => array(),
                'cellspacing' => array(),
                'border' => array(),
            ),
            'tbody' => array(
                'style'  => array(),
                'class'  => array(),
            ),
            'td' => array(
                'style'  => array(),
                'class'  => array(),
            ),
            'th' => array(
                'style'  => array(),
                'class'  => array(),
            ),
            'tr' => array(
                'style'  => array(),
                'class'  => array(),
            )
        );

        return $allowed_tags;
    }

    /**
     * Validate and sanitize form entries
     *
     * This is used for settings not involved in email
     *
     * @param array $input raw input data from the user
     * @return array valid and sanitized data
     * @since 1.0
     */
    public function validate_options( $input )
    {
        $tab = isset( $_GET["tab"] ) ? $_GET["tab"] : 'registrations';

        $updated_options = get_option( 'rtec_options', false );
        $checkbox_settings = array();
        $leave_spaces = array();
        $allowed_tags = $this->get_allowed_tags();
        $rich_editor_settings = array();

        if ( isset( $input['default_max_registrations'] ) ) {
            $checkbox_settings = array( 'first_show', 'first_require', 'last_show', 'last_require', 'email_show', 'email_require', 'phone_show', 'phone_require', 'other_show', 'other_require', 'terms_conditions_require', 'recaptcha_require', 'disable_by_default', 'visitors_can_edit_what_status', 'show_registrants_data', 'limit_registrations', 'include_attendance_message', 'preserve_db', 'preserve_registrations', 'preserve_settings', 'check_for_duplicates' );
            $leave_spaces = array( 'custom_js', 'custom_css', 'notification_message' );
        } elseif ( isset( $input['confirmation_message'] ) ) {
            $rich_editor_settings = array( 'confirmation_message', 'notification_message' );
            $checkbox_settings = array( 'disable_notification', 'disable_confirmation', 'use_custom_notification' );
        }

        if ( isset( $input['custom_field_names'] ) ) {
            $custom_field_names = explode( ',', $input['custom_field_names'] );
        } else {
            $custom_field_names = array();
        }

        foreach ( $checkbox_settings as $checkbox_setting ) {
            $updated_options[$checkbox_setting] = false;
        }

        if ( isset( $updated_options['visitors_can_edit_what_status'] ) && $updated_options['visitors_can_edit_what_status'] ) {
	        $updated_options['add_registration_management_tool'] = true;
        } else {
	        $updated_options['add_registration_management_tool'] = false;
        }

        foreach ( $input as $key => $val ) {
            if ( in_array( $key, $checkbox_settings ) ) {
                if ( $val == 'on' ) {
                    $updated_options[$key] = true;
                }
            } elseif ( in_array( $key, $rich_editor_settings, true ) ) {

                $working_text = wp_kses( str_replace( '{nl}', '<br />', $val ), $allowed_tags );
                $updated_options[$key] = $working_text;
            } else {
                if ( in_array( $key, $leave_spaces ) ) {
                    $updated_options[$key] = $val;
                } else {
                    $updated_options[$key] = sanitize_text_field( $val );
                }
            }
            if ( $tab === 'email' ) {
                $updated_options[$key] = $this->check_malicious_headers( $val );
            }
        }

        foreach ( $custom_field_names as $field ) {

            if ( isset( $input[$field . '_require'] ) ) {
                $updated_options[$field . '_require'] = true;
            } else {
                $updated_options[$field . '_require'] = false;
            }

            if ( isset( $input[$field . '_show'] ) ) {
                $updated_options[$field . '_show'] = true;
            } else {
                $updated_options[$field . '_show'] = false;
            }

            if ( isset( $input[$field . '_label'] ) ) {
                $updated_options[$field . '_label'] = sanitize_text_field( str_replace( "'", '`', $input[$field . '_label'] ) );
            }

        }

        return $updated_options;
    }

    /**
     * Checks for malicious headers
     *
     * Since these settings are used as part of an email message, the data is
     * checked for potential header injections
     *
     * @param string $value value of an option submitted from the plugin options page
     * @return string sanitized data string or if validation fails, empty string
     * @since 1.0
     */
    public function check_malicious_headers( $value )
    {
        $malicious = array( 'to:', 'cc:', 'bcc:', 'content-type:', 'mime-version:', 'multipart-mixed:', 'content-transfer-encoding:' );

        foreach ( $malicious as $m ) {
            if( stripos( $value, $m ) !== false ) {
                add_settings_error( '', 'setting-error', 'Your entries contain dangerous input', 'error' );
                return '';
            }
        }

        $value = str_replace( array( '%0a', '%0d' ), ' ' , $value );
        return trim( $value );
    }
}

/**
 * Create the admin menus and pages
 * 
 * @since 1.0
 */
function RTEC_ADMIN() {

    $admin = new RTEC_Admin();
}
RTEC_ADMIN();

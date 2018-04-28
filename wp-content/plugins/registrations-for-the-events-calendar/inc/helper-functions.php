<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Will return all relevant meta for an event
 *
 * @param   $id string
 * @since   1.0
 * @since   1.0 added limit registrations, deadline type, max registrations
 * @return array
 */
function rtec_get_event_meta( $id = '' ) {
	global $rtec_options;

	$event_meta = array();

	// construct post object
	if ( ! empty( $id ) ) {
		$post_obj = get_post( $id );
	} else {
		$post_obj = get_post();
	}

	// set post meta
	$meta = isset( $post_obj->ID ) ? get_post_meta( $post_obj->ID ) : array();

	// set venue meta
	$venue_meta = isset( $meta['_EventVenueID'][0] ) ? get_post_meta( $meta['_EventVenueID'][0] ) : array();

	$event_meta['post_id'] = isset( $post_obj->ID ) ? $post_obj->ID : '';
	$event_meta['title'] = ! empty( $id ) ? get_the_title( $id ) : get_the_title();
	$event_meta['start_date'] = isset( $meta['_EventStartDate'][0] ) ? $meta['_EventStartDate'][0] : '';
	$event_meta['end_date'] = isset( $meta['_EventEndDate'][0] ) ? $meta['_EventEndDate'][0] : '';
	$event_meta['start_date_utc'] = isset( $meta['_EventStartDateUTC'][0] ) ? $meta['_EventStartDateUTC'][0] : '';
	$event_meta['end_date_utc'] = isset( $meta['_EventEndDateUTC'][0] ) ? $meta['_EventEndDateUTC'][0] : '';
	$event_meta['venue_id'] = isset( $meta['_EventVenueID'][0] ) ? $meta['_EventVenueID'][0] : '';
	$venue = isset( $post_obj->ID ) ? rtec_get_venue( $post_obj->ID ) : array();
	$event_meta['venue_title'] = ! empty( $venue ) ? $venue : '(no location)';
	$event_meta['venue_address'] = isset( $venue_meta['_VenueAddress'][0] ) ? $venue_meta['_VenueAddress'][0] : '';
	$event_meta['venue_city'] = isset( $venue_meta['_VenueCity'][0] ) ? $venue_meta['_VenueCity'][0] : '';
	$event_meta['venue_state'] = isset( $venue_meta['_VenueStateProvince'][0] ) ? $venue_meta['_VenueStateProvince'][0] : '';
	$event_meta['venue_zip'] = isset( $venue_meta['_VenueZip'][0] ) ? $venue_meta['_VenueZip'][0] : '';

	$default_disabled = isset( $rtec_options['disable_by_default'] ) ? $rtec_options['disable_by_default'] : false;
	$event_meta['registrations_disabled'] = isset( $meta['_RTECregistrationsDisabled'][0] ) ? ( (int)$meta['_RTECregistrationsDisabled'][0] === 1 ) : $default_disabled;
	$event_meta['show_registrants_data'] = isset( $rtec_options['show_registrants_data'] ) && ! $event_meta['registrations_disabled'] ? $rtec_options['show_registrants_data'] : false;
	$default_limit_registrations = isset( $rtec_options['limit_registrations'] ) ? $rtec_options['limit_registrations'] : false;
	$event_meta['limit_registrations'] = isset( $meta['_RTEClimitRegistrations'][0] ) ? ( (int)$meta['_RTEClimitRegistrations'][0] === 1 ) : $default_limit_registrations;
	$default_max_registrations = isset( $rtec_options['default_max_registrations'] ) ? (int)$rtec_options['default_max_registrations'] : 30;
	$event_meta['max_registrations'] = isset( $meta['_RTECmaxRegistrations'][0] ) ? (int)$meta['_RTECmaxRegistrations'][0] : $default_max_registrations;
	$event_meta['num_registered'] = isset( $meta['_RTECnumRegistered'][0] ) ? (int)$meta['_RTECnumRegistered'][0] : 0;
	$event_meta['form_id'] = isset( $meta['_RTECformID'][0] ) ? (int)$meta['_RTECformID'][0] : 1;

	if ( $event_meta['limit_registrations'] ) {
		$registrations_left = max( $event_meta['max_registrations'] - $event_meta['num_registered'], 0 );
		$event_meta['registrations_left'] = $registrations_left;
	} else {
		$event_meta['registrations_left'] = '';
	}

	$default_deadline_type = isset( $rtec_options['default_deadline_type'] ) ? $rtec_options['default_deadline_type'] : 'start';
	$event_meta['deadline_type'] = isset( $meta['_RTECdeadlineType'][0] ) ? $meta['_RTECdeadlineType'][0] : $default_deadline_type;
	$event_meta['deadline_other_timestamp'] = isset( $meta['_RTECdeadlineTimeStamp'][0] ) ? $meta['_RTECdeadlineTimeStamp'][0] : strtotime( $event_meta['start_date'] );
	$event_meta['registration_deadline'] = rtec_get_event_deadline_utc( $event_meta );

	$db = new RTEC_Db();
	$db_num_registered = $db->get_registration_count( $event_meta['post_id'], $event_meta['form_id'] );

	if ( (int)$db_num_registered !== (int)$event_meta['num_registered'] ) {
		$event_meta['num_registered'] = $db_num_registered;

		update_post_meta( $event_meta['post_id'], '_RTECnumRegistered', $db_num_registered );
	}

	$event_meta = apply_filters( 'rtec_event_meta', $event_meta );

	return $event_meta;
}

/**
 * Calculates a deadline relative to timezone
 *
 * @param   $event_meta array
 * @since   1.5
 * @since   2.0 added specific deadline
 * @return  mixed   int if deadline, 'none' if no deadline
 */
function rtec_get_event_deadline_utc( $event_meta ) {
	global $rtec_options;

	$deadline_time = 'none';

	$WP_offset = get_option( 'gmt_offset' );

	if ( ! empty( $WP_offset ) ) {
		$tz_offset = $WP_offset * HOUR_IN_SECONDS;
	} else {
		$options = get_option( 'rtec_options' );

		$timezone = isset( $options['timezone'] ) ? $options['timezone'] : 'America/New_York';
		// use php DateTimeZone class to handle the date formatting and offsets
		$date_obj = new DateTime( date( 'm/d g:i a' ), new DateTimeZone( "UTC" ) );
		$date_obj->setTimeZone( new DateTimeZone( $timezone ) );
		$tz_offset = $date_obj->getOffset();
	}

	if ( $event_meta['deadline_type'] === 'start' ) {

		if ( $event_meta['start_date'] !== '' ) {
			$deadline_multiplier = isset( $rtec_options['registration_deadline'] ) ? sanitize_text_field( $rtec_options['registration_deadline'] ) : 0;
			$deadline_unit = isset( $rtec_options['registration_deadline_unit'] ) ? sanitize_text_field( $rtec_options['registration_deadline_unit'] ) : 0;
			$offset_start_time = strtotime( $event_meta['start_date'] ) - $tz_offset;
			$deadline_time = $offset_start_time - ($deadline_multiplier * $deadline_unit);
		}

	} elseif ( $event_meta['deadline_type'] === 'end' ) {
		$deadline_time = strtotime( $event_meta['end_date'] ) - $tz_offset;
	} elseif ( $event_meta['deadline_type'] === 'other' ) {
		$deadline_time = $event_meta['deadline_other_timestamp'] - $tz_offset;
	}

	return $deadline_time;
}

function rtec_get_time_zone_offset() {
	$WP_offset = get_option( 'gmt_offset' );

	if ( ! empty( $WP_offset ) ) {
		$tz_offset = $WP_offset * HOUR_IN_SECONDS;
	} else {
		$options = get_option( 'rtec_options' );

		$timezone = isset( $options['timezone'] ) ? $options['timezone'] : 'America/New_York';
		// use php DateTimeZone class to handle the date formatting and offsets
		$date_obj = new DateTime( date( 'm/d g:i a' ), new DateTimeZone( "UTC" ) );
		$date_obj->setTimeZone( new DateTimeZone( $timezone ) );
		$tz_offset = $date_obj->getOffset();
	}

	return $tz_offset;
}

/**
 * Converts raw phone number strings into a properly formatted one
 *
 * @param $raw_number string    telephone number from database with no
 * @since 1.1
 * @since 2.1                   added support for custom formats through filter or setting
 *
 * @return string               telephone number formatted for display
 */
function rtec_format_phone_number( $raw_number ) {
	global $rtec_options;
	$phone_option = isset( $rtec_options['phone_format'] ) ? $rtec_options['phone_format'] : '1';

	$rules = array(
		7 => array(
			'pattern' => '/([0-9]{3})([0-9]{4})/',
			'replacement' => '$1-$2'
		),
		10 => array(
			'pattern' => '/([0-9]{3})([0-9]{3})([0-9]{4})/',
			'replacement' => '($1) $2-$3'
		),
		11 => array(
			'pattern' => '/([0-9]{3})([0-9]{4})([0-9]{4})/',
			'replacement' => '($1) $2-$3'
		),
	);

	if ( $phone_option === '2' ) {
		$rules[10]['pattern'] = '/([0-9]{2})([0-9]{4})([0-9]{4})/';
		$rules[10]['replacement'] = '$1 $2 $3';
	} elseif ( $phone_option === '3' ) {
		$rules[10]['pattern'] = '/([0-9]{2})([0-9]{4})([0-9]{4})/';
		$rules[10]['replacement'] = '($1) $2 $3';
	}

	$rules = apply_filters( 'rtec_phone_formatting_rules', $rules );
	$number_length = strlen( $raw_number );

	if ( isset( $rules[ $number_length ] ) ) {

		if ( isset( $rules[ $number_length ]['pattern'] ) && isset( $rules[ $number_length ]['replacement'] ) ) {
			return preg_replace( $rules[ $number_length ]['pattern'], $rules[ $number_length ]['replacement'], $raw_number );
		}

	}

	return $raw_number;
}

/**
 * Retrieves venue title using TEC function. Checks to make sure it exists first
 *
 * @param   $event_id   mixed  id of the event
 * @since   1.1
 *
 * @return string           venue title
 */
function rtec_get_venue( $event_id = NULL ) {
	if ( function_exists( 'tribe_get_venue' ) ) {
		$venue = tribe_get_venue( $event_id );

		return $venue;
	} else {
		return '';
	}
}
function rtec_get_parsed_custom_field_data( $raw_data ) {
	global $rtec_options;

	$custom_data = maybe_unserialize( $raw_data );

	if ( isset( $rtec_options['custom_field_names'] ) ) {
		$custom_field_names = explode( ',', $rtec_options['custom_field_names'] );
	} else {
		$custom_field_names = array();
	}

	$parsed_data = array();
	foreach ( $custom_field_names as $field ) {

		if ( isset( $rtec_options[$field . '_label'] ) && isset( $custom_data[$rtec_options[$field . '_label']] ) ) {
			$parsed_data[$rtec_options[$field . '_label']] = $custom_data[$rtec_options[$field . '_label']];
		} elseif ( isset( $rtec_options[$field . '_label'] ) ) {
			$parsed_data[$rtec_options[$field . '_label']] = '';
		} else {
			$parsed_data = '';
		}

	}

	return $parsed_data;
}

function rtec_get_parsed_custom_field_data_full_structure( $raw_data ) {
	return maybe_unserialize( $raw_data );
}
/**
 * Takes the custom data array and converts to serialized data for
 * adding to the db
 *
 * @param $submission_data
 * @param bool $from_form
 *
 * @return mixed
 */
function rtec_serialize_custom_data( $submission_data, $field_attributes, $from_form = true ) {
	$standard_fields = array(
		'first',
		'last',
		'email',
		'phone',
		'other',
		'guests',
		'recaptcha'
	);
	$custom_data = array();
	if ( $from_form ) {
		foreach ( $field_attributes as $field_name => $atts ) {
			if ( ! in_array( $field_name, $standard_fields, true ) ) {
				$custom_data[ $field_name ] = array(
					'label' => $atts['label'],
					'value' => $submission_data[ $field_name ]
				);
			}
		}
	} else {
		foreach ( $submission_data['custom'] as $key => $value ) {
			if ( isset( $field_attributes[ $key ] ) ) {
				$custom_data[ $key ] = array(
					'label' => $field_attributes[ $key ]['label'],
					'value' => $value
				);
			}
		}

	}

	return maybe_serialize( $custom_data );
}


function rtec_get_event_meta_confirmation_email( $event_meta, $user_data) {

	$return_data['title'] = $event_meta['title'];

	if ( $event_meta['mvt_enabled'] && isset( $user_data['venue'] ) ) {
		$return_data['venue'] = sanitize_text_field( $user_data['venue'] );
		$return_data['venue_mvt'] = sanitize_text_field( $user_data['venue'] );

		if ( $event_meta['mvt_fields'][ $return_data['venue'] ]['type'] === 'venue' ) {
			$venue_meta = rtec_get_venue_meta( $return_data['venue'] );
			$return_data['venue_title'] = $venue_meta['venue_title'];
			$return_data['venue_address'] = $venue_meta['venue_address'];
			$return_data['venue_city'] = $venue_meta['venue_city'];
			$return_data['venue_state'] = $venue_meta['venue_state'];
			$return_data['venue_zip'] = $venue_meta['venue_zip'];
		} else {
			$return_data['venue_title'] = $event_meta['venue_title'];
			$return_data['venue_address'] = $event_meta['venue_address'];
			$return_data['venue_city'] = $event_meta['venue_city'];
			$return_data['venue_state'] = $event_meta['venue_state'];
			$return_data['venue_zip'] = $event_meta['venue_zip'];
		}
		$return_data['mvt_label'] = $event_meta['mvt_fields'][ $user_data['venue'] ]['label'];

	} else {
		$return_data['venue'] = $event_meta['venue_id'];
		$return_data['venue_title'] = $event_meta['venue_title'];
		$return_data['venue_address'] = $event_meta['venue_address'];
		$return_data['venue_city'] = $event_meta['venue_city'];
		$return_data['venue_state'] = $event_meta['venue_state'];
		$return_data['venue_zip'] = $event_meta['venue_zip'];
	}

	$return_data['ical_url'] = isset( $event_meta['ical_url'] ) ? $event_meta['ical_url']: '';
	$return_data['date'] = $event_meta['start_date'];
	$return_data['event_id'] = $user_data['event_id'];

	$return_data = array_merge( $return_data, $user_data );

	$return_data['first'] = $return_data['first_name'];
	$return_data['last'] = $return_data['last_name'];

	return $return_data;
}

function rtec_has_deprecated_data_structure( $custom ) {

	if ( is_array($custom) ) {
		foreach( $custom as $key ) {
			if ( ! isset( $key['value'] ) ) {
				return true;
			} else {
				return false;
			}
		}
	} else {
		return false;
	}

}

function rtec_generate_action_key() {
	return sha1( uniqid( '', true ) );
}

function rtec_generate_unregister_link( $event_id, $action_key, $email, $unregister_link_text ) {
	$permalink = get_permalink( $event_id );
	$unregister_url = add_query_arg( 'action' , 'unregister', $permalink );
	$unregister_url = add_query_arg( 'token' ,  $action_key, $unregister_url );
	$unregister_url = add_query_arg( 'email' , $email, $unregister_url );

	return '<span class="rtec-unregister-link-wrap"><a data-event-id="'.esc_attr( $event_id ).'" class="rtec-unregister-link" href="'.esc_url( $unregister_url ).'">' . $unregister_link_text . '</a></span>';
}

function rtec_get_date_time_format() {
	global $rtec_options;

	if ( isset( $rtec_options['custom_date_format'] ) ) {
		$date_time_format = $rtec_options['custom_date_format'];
	} else {
		$date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );
		if ( $date_format && $time_format ) {
			$date_time_format = $date_format . ' ' . $time_format;
		} else {
			$date_time_format = 'F j, Y g:i a';
		}
	}

	return $date_time_format;
}

function rtec_get_time_format() {

	$time_format = get_option( 'time_format' );

	$time_format = $time_format ? $time_format : 'g:i a';

	return $time_format;
}
/**
 * Returns the appropriate translation/custom/default text
 *
 * @param   $custom         string  the custom translation of text
 * @param   $translation    string  the translation or default of text
 * @since   1.4
 *
 * @return  string                  the appropriate text
 */
function rtec_get_text( $custom, $translation ) {
	global $rtec_options;
	$text = $translation;

	if ( isset( $rtec_options['message_source'] ) && $rtec_options['message_source'] === 'custom' ) {
		$text = isset( $custom ) ? $custom : $translation;
	}

	return $text;

}

function rtec_using_translations() {
	global $rtec_options;

	if ( isset( $rtec_options['message_source'] ) && $rtec_options['message_source'] === 'custom' ) {
		return false;
	}

	return true;
}

function rtec_sanitize_outputted_html( $input ) {
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

	return wp_kses( $input, $allowed_tags );
}

function rtec_get_notification_email_recipients( $event_id, $blank = false ) {
	global $rtec_options;

	$notification_recipients = get_post_meta( $event_id, '_RTECnotificationEmailRecipient' );

	if ( !empty( $notification_recipients[0] ) ) {
		return $notification_recipients[0];
	} elseif( $blank ) {
		return '';
	} else {
		$notification_recipients = isset( $rtec_options['recipients'] ) ? $rtec_options['recipients'] : get_option( 'admin_email' );
		return $notification_recipients;
	}

}

function rtec_get_confirmation_from_address( $event_id, $blank = false ) {
	global $rtec_options;

	$confirmation_address = get_post_meta( $event_id, '_RTECconfirmationEmailFrom' );

	if ( !empty( $confirmation_address[0] ) ) {
		return $confirmation_address[0];
	} elseif( $blank ) {
		return '';
	} else {
		$confirmation_address = isset( $rtec_options['confirmation_from_address'] ) ? $rtec_options['confirmation_from_address'] : get_option( 'admin_email' );
		return $confirmation_address;
	}

}

function rtec_get_standard_form_fields() {
	$standard_fields = array(
		'first',
		'last',
		'email',
		'phone',
		'other'
	);

	return $standard_fields;
}

function rtec_get_custom_name_label_pairs() {
	global $rtec_options;
	$custom_name_label_pairs = array();

	if ( isset( $rtec_options['custom_field_names'] ) && ! is_array( $rtec_options['custom_field_names'] ) ) {
		$custom_field_names = explode( ',', $rtec_options['custom_field_names'] );
	} else {
		$custom_field_names = array();
	}

	foreach ( $custom_field_names as $custom_field_name ) {
		$custom_name_label_pairs[ $custom_field_name ]['label'] = isset( $rtec_options[ $custom_field_name . '_label' ] ) ? $rtec_options[ $custom_field_name . '_label' ] : '';
	}

	return $custom_name_label_pairs;
}

function rtec_get_no_template_fields() {
	$no_template_fields = array(
		'recaptcha'
	);

	return $no_template_fields;
}

function rtec_get_no_backend_column_fields() {
	$no_backend_column_fields = array(
		'recaptcha'
	);

	return $no_backend_column_fields;
}

/**
 * Generates the registration form with a shortcode
 *
 * @param   $atts        array  settings for the form
 * @since   1.5
 */
add_shortcode( 'rtec-registration-form', 'rtec_the_registration_form_shortcode' );
function rtec_the_registration_form_shortcode( $atts ) {
	$post_id = isset( $atts['event'] ) ? (int)$atts['event'] : false;

	if ( ! $post_id ) {
		$post_id = isset( $_GET['event_id'] ) ? sanitize_text_field( (int)$_GET['event_id'] ) : false;

		if ( $post_id ) {
			$atts['event'] = $post_id;
		}
	}

	$atts['doing_shortcode'] = true;

	if ( $post_id !== get_the_ID() || ! is_singular( 'tribe_events' ) ) {

		if ( function_exists( 'rtec_the_registration_form' ) && $post_id !== false ) {
			$html = rtec_the_registration_form( $atts );

			return $html;
		} else {

			if ( current_user_can( 'edit_posts' ) ) {
				echo '<div class="rtec-yellow-message">';
				echo '<span>This message is only visible to logged-in editors:<br /><strong>Please enter a valid event ID in the shortcode to show a registration form here.</strong></span>';
				echo '<span>For example: </span><code>[rtec-registration-form event=321]</code>';
				echo '</div>';
			}

		}

	} else {

		if ( current_user_can( 'edit_posts' ) ) {
			echo '<div class="rtec-yellow-message">';
			echo '<span>This message is only visible to logged-in editors:<br /><strong>Shortcode not used. There is already a registration form on this page for this event.</strong></span>';
			echo '</div>';
		}

	}

}

/**
 * Generates the attendee list with a shortcode
 *
 * @param   $atts        array  settings for the form, $atts['event'] required
 * @since   2.1
 */
add_shortcode( 'rtec-attendee-list', 'rtec_the_attendee_list_shortcode' );
function rtec_the_attendee_list_shortcode( $atts ) {
	$post_id = isset( $atts['event'] ) ? (int)$atts['event'] : false;

	if ( ! $post_id ) {
		$post_id = isset( $_GET['event_id'] ) ? sanitize_text_field( (int)$_GET['event_id'] ) : false;

		if ( $post_id ) {
			$atts['event'] = $post_id;
		}
	}

	if ( (int)$post_id > 0 ) {
		$rtec = RTEC();
		$event_id = (int)$post_id;
		$rtec->form->build_form( $event_id );
		$fields_atts = $rtec->form->get_field_attributes();

		$event_meta = $rtec->form->get_event_meta();

		$to_include = apply_filters( 'rtec_attendee_list_fields', array() );
		$attendee_list_fields = apply_filters( 'rtec_attendee_list_fields', $to_include );
		$registrants_data = $rtec->db_frontend->get_registrants_data( $event_meta, $attendee_list_fields );
		ob_start();
		echo '<div class="rtec-attendee-list-wrap">';
		if ( isset( $atts['showheader'] ) && $atts['showheader'] === 'true' ) {
			$rtec->form->the_event_header();
		}

		if ( !empty( $registrants_data ) ) {
			do_action( 'rtec_the_attendee_list', $registrants_data );
		} else {
			global $rtec_options;

			$f_text = isset( $rtec_options['attendance_text_none_yet'] ) ? $rtec_options['attendance_text_none_yet'] : __( 'Be the first!', 'registrations-for-the-events-calendar' );
			$f_message = rtec_get_text( $f_text, __( 'Be the first!', 'registrations-for-the-events-calendar' ) );
			$text_string = $f_message;

			$title = isset( $rtec_options['attendee_list_title'] ) ? $rtec_options['attendee_list_title'] : __( 'Currently Registered', 'registrations-for-the-events-calendar' );
			$title = rtec_get_text( $title,  __( 'Currently Registered', 'registrations-for-the-events-calendar' ) );
			?>

			<div class="tribe-events-event-meta rtec-event-meta"><h3 class="rtec-section-title"><?php esc_html_e( $title ); ?></h3>
				<p style="padding-left: 4%;"><?php esc_html_e( $text_string ); ?></p>
			</div>
			<?php
		}
		echo '</div>';
		$html = ob_get_contents();
		ob_get_clean();
	} else {
		$html = '<p>'.__( 'Please enter an Event ID to view the attendee list', 'registrations-for-the-events-calendar' ) . '</p>';
	}

	return $html;
}
<?php
/**
 * New registrations are counted and added as alerts to the menu items
 *
 * @return false|int    false if no new registrations, else the count
 * @since 1.0
 */
function rtec_get_existing_new_reg_count() {

	$existing_new_reg_data = get_transient( 'rtec_new_registrations' );

	if ( $existing_new_reg_data ) {
		$new_registrations_count = $existing_new_reg_data;
	} else {
		$db = new RTEC_Db_Admin();
		$new_registrations_count = $db->check_for_new();

		if ( ! $existing_new_reg_data ) {
			set_transient( 'rtec_new_registrations', $new_registrations_count, 60 * 15 );
		}

	}

	return $new_registrations_count;
}

/**
 * Creates the alert next to the menu item
 *
 * @since 1.0
 */
function rtec_registrations_bubble() {

	$new_registrations_count = rtec_get_existing_new_reg_count();

	if ( $new_registrations_count > 0 ) {
		global $menu;

		foreach ( $menu as $key => $value ) {
			if ( $menu[$key][2] === RTEC_TRIBE_MENU_PAGE ) {
				$menu[$key][0] .= ' <span class="update-plugins rtec-notice-admin-reg-count"><span>' . $new_registrations_count . '</span></span>';
				return;
			}
		}
	} elseif ( get_transient( 'rtec_new_messages' ) === 'yes' ) {
		global $menu;

		foreach ( $menu as $key => $value ) {
			if ( $menu[$key][2] === RTEC_TRIBE_MENU_PAGE ) {
				$menu[$key][0] .= ' <span class="update-plugins rtec-notice-admin-reg-count"><span>' . __( 'Registrations', 'registrations-for-the-events-calendar' ) . '</span></span>';
				return;
			}
		}
	}

}
add_action( 'admin_menu', 'rtec_registrations_bubble' );

function rtec_the_admin_notices() {
	global $rtec_options;

	if ( ! isset( $rtec_options['default_max_registrations'] ) ) : ?>
		<div class="rtec-notice-all-admin notice notice-info is-dismissible">
			<div class="rtec-img-wrap">
				<img src="<?php echo RTEC_PLUGIN_URL . 'img/RTEC-Logo-150x150.png'; ?>" alt="Registrations for the Events Calendar">
			</div>
			<div class="rtec-msg-wrap">
				<p><?php _e( 'Registration forms are now added to all of your single event pages. Check out the <a href="' . RTEC_ADMIN_URL . '&tab=form">"Form" tab</a> to configure options</p>' , 'registrations-for-the-events-calendar' ); ?>
				<p><?php _e( 'You can also view setup directions <a href="https://roundupwp.com/products/registrations-for-the-events-calendar/setup/" target="_blank">on our website</a></p>' , 'registrations-for-the-events-calendar' ); ?>
			</div>
		</div>
	<?php endif;
}

/**
 * Updates the individual event options with ajax
 *
 * @since 1.2
 */
function rtec_update_event_options() {
	$nonce = $_POST['rtec_nonce'];

	if ( ! wp_verify_nonce( $nonce, 'rtec_nonce' ) ) {
		die ( 'You did not do this the right way!' );
	}

	$cleaned_array = array();

	foreach ( $_POST['event_options_data'] as $data ) {
		$cleaned_array[ sanitize_text_field( $data['name'] ) ] = sanitize_text_field( $data['value'] );
	}

	$event_id = $cleaned_array['rtec_event_id'];
	$registrations_disabled_status = 0;
	$use_limit_status = 0;
	$registrations_deadline_type = 'start';
	$max_reg = 30;

	if ( isset( $cleaned_array['_RTECregistrationsDisabled'] ) ){
		$registrations_disabled_status = $cleaned_array['_RTECregistrationsDisabled'];
	}

	if ( isset( $cleaned_array['_RTECdeadlineType'] ) ){
		$registrations_deadline_type = $cleaned_array['_RTECdeadlineType'];

		if ( $registrations_deadline_type === 'other' ) {
			$deadline_date = isset( $cleaned_array['_RTECdeadlineDate'] ) ? $cleaned_array['_RTECdeadlineDate'] : date( "m/d/Y" );
			$deadline_time = isset( $cleaned_array['_RTECdeadlineTime'] ) ? $cleaned_array['_RTECdeadlineTime'] : '8:00:00';
			$parsed_date = date_parse( $deadline_time );

			$deadline_time_stamp = ( (int)$parsed_date['hour'] * 60 * 60 ) + ( (int)$parsed_date['minute'] * 60 ) + strtotime( $deadline_date );

			if ( isset( $event_id ) ) {
				update_post_meta( $event_id, '_RTECdeadlineTimeStamp', $deadline_time_stamp );
			}
		}
	}

	if ( isset( $cleaned_array['_RTEClimitRegistrations'] ) ){
		$use_limit_status = $cleaned_array['_RTEClimitRegistrations'];
	}

	if ( isset( $cleaned_array['_RTECmaxRegistrations'] ) ){
		$max_reg = $cleaned_array['_RTECmaxRegistrations'];
	}

	if ( isset( $cleaned_array['_RTECnotificationEmailRecipient'] ) && !empty( $cleaned_array['_RTECnotificationEmailRecipient'] ) ){
		$not_email = $cleaned_array['_RTECnotificationEmailRecipient'];
		update_post_meta( $event_id, '_RTECnotificationEmailRecipient', $not_email );
	} else {
		delete_post_meta( $event_id, '_RTECnotificationEmailRecipient' );
	}

	if ( isset( $cleaned_array['_RTECconfirmationEmailFrom'] ) && !empty( $cleaned_array['_RTECconfirmationEmailFrom'] ) ){
		$con_email = $cleaned_array['_RTECconfirmationEmailFrom'];
		update_post_meta( $event_id, '_RTECconfirmationEmailFrom', $con_email );
	} else {
		delete_post_meta( $event_id, '_RTECconfirmationEmailFrom' );
	}

	if ( isset( $event_id ) ) {
		update_post_meta( $event_id, '_RTECregistrationsDisabled', $registrations_disabled_status );
		update_post_meta( $event_id, '_RTECdeadlineType', $registrations_deadline_type );
		update_post_meta( $event_id, '_RTEClimitRegistrations', $use_limit_status );
		update_post_meta( $event_id, '_RTECmaxRegistrations', $max_reg );
	}

	$event_meta = rtec_get_event_meta( $event_id );

	if ( $use_limit_status == 1 ) {
		echo $event_meta['num_registered'] . ' / ' . $max_reg;
	} else {
		echo $event_meta['num_registered'];
	}

	die();
}
add_action( 'wp_ajax_rtec_update_event_options', 'rtec_update_event_options' );

/**
 * Adds the meta box for the plugins individual event options
 *
 * @since 1.1
 */
function rtec_meta_boxes_init(){
	add_meta_box( 'rtec-event-details',
		__( 'Registrations for The Events Calendar', 'registrations-for-the-events-calendar' ),
		'rtec_meta_boxes_html',
		'tribe_events',
		'normal',
		'high'
	);
}
add_action( 'admin_init', 'rtec_meta_boxes_init' );

/**
 * Generates the html for the plugin's individual event options meta boxes
 *
 * @since 1.1
 */
function rtec_meta_boxes_html(){
	global $post;

	$event_meta = rtec_get_event_meta( $post->ID );
	$limit_disabled_att = '';
	$limit_disabled_class = '';
	$max_disabled_att = '';
	$max_disabled_class = '';
	$deadline_disabled_att = '';
	$deadline_disabled_class = '';
	$deadline_other_disabled_class = '';

	$notification_email = rtec_get_notification_email_recipients( $post->ID, true );
	$confirmation_from = rtec_get_confirmation_from_address( $post->ID, true );
	$deadline_time = isset( $event_meta['deadline_other_timestamp'] ) ? $event_meta['deadline_other_timestamp'] : strtotime( $event_meta['start_date'] );
	if ( $deadline_time == 0 ) {
		$deadline_time = strtotime( date( 'Y/m/d' ) ) + 28800;
	}

	if ( $event_meta['registrations_disabled'] ) {
		$limit_disabled_att = ' disabled="true"';
		$limit_disabled_class = ' rtec-fade';
		$deadline_disabled_att = ' disabled="true"';
		$deadline_disabled_class = ' rtec-fade';
	}

	if ( $event_meta['deadline_type'] !== 'other' ) {
		$deadline_other_disabled_class = ' rtec-fade';
	}

	if ( $event_meta['registrations_disabled'] || ! $event_meta['limit_registrations'] ) {
		$max_disabled_att = ' disabled="true"';
		$max_disabled_class = ' rtec-fade';
	}

	?>
	<div id="eventDetails" class="inside eventForm">
		<table cellspacing="0" cellpadding="0" id="EventInfo">
			<tbody>
			<tr>
				<td colspan="2" class="tribe_sectionheader">
					<div class="tribe_sectionheader" style="">
						<h4><?php _e( 'General', 'registrations-for-the-events-calendar' ); ?></h4>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table class="eventtable rtec-eventtable">
						<tbody>
							<tr class="rtec-hidden-option-wrap">
								<td class="tribe-table-field-label"><?php _e( 'Disable Registrations:', 'registrations-for-the-events-calendar' ); ?></td>
								<td>
									<input type="checkbox" id="rtec-disable-checkbox" name="_RTECregistrationsDisabled" <?php if ( $event_meta['registrations_disabled'] ) { echo 'checked'; } ?> value="1"/>
								</td>
							</tr>
							<tr class="rtec-hidden-option-wrap<?php echo $limit_disabled_class; ?>">
								<td class="tribe-table-field-label"><?php _e( 'Limit Registrations:', 'registrations-for-the-events-calendar' ); ?></td>
								<td>
									<input type="checkbox" id="rtec-limit-checkbox" class="" name="_RTEClimitRegistrations" <?php if( $event_meta['limit_registrations'] ) { echo 'checked'; } ?> value="1"<?php echo $limit_disabled_att; ?>/>
								</td>
							</tr>
							<tr class="rtec-hidden-option-wrap<?php echo $max_disabled_class; ?>">
								<td class="tribe-table-field-label"><?php _e( 'Maximum Registrations:', 'registrations-for-the-events-calendar' ); ?></td>
								<td>
									<input type="text" size="3" id="rtec-max-input" name="_RTECmaxRegistrations" value="<?php echo esc_attr( $event_meta['max_registrations'] ); ?>"<?php echo $max_disabled_att;?>/>
								</td>
							</tr>
							<tr class="rtec-hidden-option-wrap<?php echo $deadline_disabled_class; ?>">
								<td class="tribe-table-field-label"><?php _e( 'Deadline Type:', 'registrations-for-the-events-calendar' ); ?></td>
								<td class="tribe-datetime-block">
									<div class="rtec-sameline">
										<input type="radio" id="rtec-start-<?php echo esc_attr( $event_meta['post_id'] ); ?>" name="_RTECdeadlineType" <?php if( $event_meta['deadline_type'] === 'start' ) { echo 'checked'; } ?> value="start"<?php echo $deadline_disabled_att;?>/>
										<label for="rtec-start-<?php echo esc_attr( $event_meta['post_id'] ); ?>"><?php _e( 'Start Time', 'registrations-for-the-events-calendar' ); ?></label>
									</div>
									<div class="rtec-sameline">
										<input type="radio" id="rtec-end-<?php echo esc_attr( $event_meta['post_id'] ); ?>" name="_RTECdeadlineType" <?php if( $event_meta['deadline_type'] === 'end' ) { echo 'checked'; } ?> value="end"<?php echo $deadline_disabled_att;?>/>
										<label for="rtec-end-<?php echo esc_attr( $event_meta['post_id'] ); ?>"><?php _e( 'End Time', 'registrations-for-the-events-calendar' ); ?></label>
									</div>
									<div class="rtec-sameline">
										<input type="radio" id="rtec-none-<?php echo esc_attr( $event_meta['post_id'] ); ?>" name="_RTECdeadlineType" <?php if( $event_meta['deadline_type'] === 'none' ) { echo 'checked'; } ?> value="none"<?php echo $deadline_disabled_att;?>/>
										<label for="rtec-none-<?php echo esc_attr( $event_meta['post_id'] ); ?>"><?php _e( 'No deadline', 'registrations-for-the-events-calendar' ); ?></label>
									</div>
									<br />
									<input type="radio" id="rtec-other-<?php echo esc_attr( $event_meta['post_id'] ); ?>" name="_RTECdeadlineType" <?php if( $event_meta['deadline_type'] === 'other' ) { echo 'checked'; } ?> value="other"<?php echo $deadline_disabled_att;?>/>
									<label for="rtec-other-<?php echo esc_attr( $event_meta['post_id'] ); ?>"><?php _e( 'Other:', 'registrations-for-the-events-calendar' ); ?></label>
									<input type="text" id="rtec-date-picker-deadline" name="_RTECdeadlineDate" value="<?php echo date( 'Y-m-d', $deadline_time ); ?>" data-rtec-deadline="<?php echo $deadline_time; ?>" class="rtec-date-picker<?php echo $deadline_other_disabled_class; ?>" style="width: 100px;"/>
									<input autocomplete="off" tabindex="2001" type="text" class="rtec-time-picker tribe-timepicker tribe-field-end_time ui-timepicker-input<?php echo $deadline_other_disabled_class; ?>" name="_RTECdeadlineTime" id="rtec-time-picker" data-step="30" data-round="" value="<?php echo date( "H:i:s", $deadline_time ); ?>" style="width: 80px;">
								</td>
							</tr>

						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="tribe_sectionheader">
					<div class="tribe_sectionheader" style="">
						<h4><?php _e( 'Email', 'registrations-for-the-events-calendar' ); ?></h4>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table class="eventtable rtec-eventtable">
						<tbody>
						<tr class="rtec-hidden-option-wrap">
							<td class="tribe-table-field-label"><?php _e( 'Notification Email Recipients:', 'registrations-for-the-events-calendar' ); ?></td>
							<td>
								<input type="text" style="width: 100%; max-width: 400px;" id="rtec-not-email" name="_RTECnotificationEmailRecipient" value="<?php echo esc_attr( $notification_email ); ?>" placeholder="leave blank for default"/>
							</td>
						</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<table class="eventtable rtec-eventtable">
						<tbody>
						<tr class="rtec-hidden-option-wrap">
							<td class="tribe-table-field-label"><?php _e( 'Confirmation From Address:', 'registrations-for-the-events-calendar' ); ?></td>
							<td>
								<input type="text" size="30" id="rtec-conf-from" name="_RTECconfirmationEmailFrom" value="<?php echo esc_attr( $confirmation_from ); ?>" placeholder="leave blank for default"/>
							</td>
						</tr>
						</tbody>
					</table>
				</td>
			</tr>
            <tr>
                <td colspan="2" class="tribe_sectionheader">
                    <div class="tribe_sectionheader" style="">
                        <h4><?php _e( 'Shortcodes', 'registrations-for-the-events-calendar' ); ?></h4>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
					<?php _e( 'Copy and paste these shortcodes to display the registration form and the attendee list on a page outside of the single event view.', 'registrations-for-the-events-calendar' ); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <h4><?php _e( 'Registration Form: ', 'registrations-for-the-events-calendar' ); ?></h4><code>[rtec-registration-form event=<?php echo $event_meta['post_id']; ?>]</code><br /><small><?php _e( 'Note that the registration form appears on the single event view automatically.', 'registrations-for-the-events-calendar' ); ?></small>
                    <span class="rtec-tooltip-table">
                        <strong><?php _e( 'Shortcode Settings:', 'registrations-for-the-events-calendar' ); ?></strong></br>
                        <span class="rtec-col-1">event="123"</span><span class="rtec-col-2"><?php _e( 'Show registration form by event ID', 'registrations-for-the-events-calendar' ); ?></span>
			            <span class="rtec-col-1">hidden="true"</span><span class="rtec-col-2"><?php _e( 'Use "false" to show the form initially', 'registrations-for-the-events-calendar' ); ?></span>
                        <span class="rtec-col-1">showheader="false"</span><span class="rtec-col-2"><?php _e( 'Use "true" to add the event title and start/end time information above the form', 'registrations-for-the-events-calendar' ); ?></span>
                        <span class="rtec-col-1">attendeelist="false"</span><span class="rtec-col-2"><?php _e( 'Use "true" to add the attendee list above the form if enabled for this event', 'registrations-for-the-events-calendar' ); ?></span>
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <h4><?php _e( 'Attendee List: ', 'registrations-for-the-events-calendar' ); ?></h4><code>[rtec-attendee-list event=<?php echo $event_meta['post_id']; ?>]</code>
                    <span class="rtec-tooltip-table">
                        <strong><?php _e( 'Shortcode Settings:', 'registrations-for-the-events-calendar' ); ?></strong></br>
                        <span class="rtec-col-1">event="123"</span><span class="rtec-col-2"><?php _e( 'Show attendee list by event ID', 'registrations-for-the-events-calendar' ); ?></span>
                        <span class="rtec-col-1">showheader="false"</span><span class="rtec-col-2"><?php _e( 'Use "true" to add the event title and start/end time information above the list', 'registrations-for-the-events-calendar' ); ?></span>
					</span>
                </td>
            </tr>
			<tr>
				<td colspan="2" class="tribe_sectionheader">
					<div class="tribe_sectionheader" style="">
						<h4><?php _e( 'More Single Event Options', 'registrations-for-the-events-calendar' ); ?></h4>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p><?php _e( 'More single event options like custom confirmation email templates, multiple venues/tier registration, settings for logged-in users and others in', 'registrations-for-the-events-calendar' ); ?> <a href="https://roundupwp.com/products/registrations-for-the-events-calendar-pro/" target="_blank"><?php _e( 'Registrations for The Events Calendar', 'registrations-for-the-events-calendar' ) ?> Pro</a></p>
				</td>
			</tr>
			</tbody>
		</table>
	</div>

	<?php
}

/**
 * This saves the meta when the event post is updated
 *
 * @since 1.1
 */
function rtec_save_meta(){
    // do not save for non tribe_events post types
	if ( ! isset( $_POST['_RTECdeadlineType'] ) && ! isset( $_POST['_RTECregistrationsDisabled'] ) ) {
		return;
	}

	global $post;

	$registrations_disabled_status = 0;
	$use_limit_status = 0;
	$max_reg = 30;
	$registrations_deadline_type = 'start';

	if ( isset( $_POST['_RTECregistrationsDisabled'] ) ){
		$registrations_disabled_status = sanitize_text_field( $_POST['_RTECregistrationsDisabled'] );
	}

	if ( isset( $_POST['_RTECdeadlineType'] ) ){
		$registrations_deadline_type = sanitize_text_field( $_POST['_RTECdeadlineType'] );

		if ( $registrations_deadline_type === 'other' ) {
			$deadline_date = isset( $_POST['_RTECdeadlineDate'] ) ? sanitize_text_field( $_POST['_RTECdeadlineDate'] ) : date( "m/d/Y" );
			$deadline_time = isset( $_POST['_RTECdeadlineTime'] ) ? sanitize_text_field( $_POST['_RTECdeadlineTime'] ) : '8:00:00';
			$parsed_date = date_parse( $deadline_time );

			$deadline_time_stamp = ( (int)$parsed_date['hour'] * 60 * 60 ) + ( (int)$parsed_date['minute'] * 60 ) + strtotime( $deadline_date );

			if ( isset( $post->ID ) ) {
				update_post_meta( $post->ID, '_RTECdeadlineTimeStamp', $deadline_time_stamp );
			}
		}
	}

	if ( isset( $_POST['_RTEClimitRegistrations'] ) ){
		$use_limit_status = sanitize_text_field( $_POST['_RTEClimitRegistrations'] );
	}

	if ( isset( $_POST['_RTECmaxRegistrations'] ) ){
		$max_reg = sanitize_text_field( $_POST['_RTECmaxRegistrations'] );
	}

	if ( isset( $_POST['_RTECnotificationEmailRecipient'] ) && !empty( $_POST['_RTECnotificationEmailRecipient'] ) && isset( $post->ID ) ){
		$not_email = sanitize_text_field( $_POST['_RTECnotificationEmailRecipient'] );
		update_post_meta( $post->ID, '_RTECnotificationEmailRecipient', $not_email );
	} elseif ( isset( $post->ID ) ) {
		delete_post_meta( $post->ID, '_RTECnotificationEmailRecipient' );
	}

	if ( isset( $_POST['_RTECconfirmationEmailFrom'] ) && !empty( $_POST['_RTECconfirmationEmailFrom'] ) && isset( $post->ID ) ){
		$con_email = sanitize_text_field( $_POST['_RTECconfirmationEmailFrom'] );
		update_post_meta( $post->ID, '_RTECconfirmationEmailFrom', $con_email );
	} elseif ( isset( $post->ID ) ) {
		delete_post_meta( $post->ID, '_RTECconfirmationEmailFrom' );
	}

	if ( isset( $post->ID ) ) {
		update_post_meta( $post->ID, '_RTECregistrationsDisabled', $registrations_disabled_status );
		update_post_meta( $post->ID, '_RTECdeadlineType', $registrations_deadline_type );
		update_post_meta( $post->ID, '_RTEClimitRegistrations', $use_limit_status );
		update_post_meta( $post->ID, '_RTECmaxRegistrations', $max_reg );
	}

}
add_action( 'save_post', 'rtec_save_meta' );

/**
 * Due to bug in meta_query, this function manually removes events that have no meta set
 * but events are disabled by default
 *
 * @since 1.4
 * @since 2.0 now works with added filtering
 */
function rtec_should_show( $with, $disabled_status ) {

	if ( $with === 'either' ) {
		return true;
	} else {
		return ( $disabled_status === false );
	}
}

/**
 * Used to edit, add, and delete registrations from the dashboard
 *
 * @since 2.0
 */
function rtec_records_edit()
{
	$nonce = $_POST['rtec_nonce'];
	$action = sanitize_text_field( $_POST['edit_action'] );

	if ( ! wp_verify_nonce( $nonce, 'rtec_nonce' ) ) {
		die ( 'You did not do this the right way!' );
	}

	$event_id = (int)$_POST['event_id'];
	$entry_id = isset( $_POST['entry_id'] ) ? (int)$_POST['entry_id'] : false;
	$event_meta = rtec_get_event_meta( $event_id );
	$venue = isset( $_POST['venue'] ) ? (string)$_POST['venue'] : $event_meta['venue_id'];

	require_once RTEC_PLUGIN_DIR . 'inc/form/class-rtec-form.php';

	$form = new RTEC_Form();

	$form->build_form( $event_id );
	$fields_atts = $form->get_field_attributes();

	require_once RTEC_PLUGIN_DIR . 'inc/class-rtec-db.php';
	require_once RTEC_PLUGIN_DIR . 'inc/admin/class-rtec-db-admin.php';
	$db = new RTEC_Db_Admin();

	switch ( $action ) {
		case 'delete' :
			$registrations_to_be_deleted = array();

			foreach ( $_POST['registrations_to_be_deleted'] as $registration ) {
				$registrations_to_be_deleted[] = sanitize_text_field( $registration );
			}

			$db->remove_records( $registrations_to_be_deleted );
			break;
		case 'add' :
			$data = array();

			foreach( $_POST as $key => $value ) {
				if ( $key === 'custom' ) {
					$data['custom'] = json_decode( stripslashes( $_POST['custom'] ), true );
				} elseif ( $key === 'standard' ) {
					$standard = json_decode( stripslashes( $_POST['standard'] ), true );
					foreach ( $standard as $key_2 => $value_2 ) {
						$data[ $key_2 ] = sanitize_text_field( $value_2 );
					}
				}
			}

			$data['status'] = 'c';
			$data['event_id'] = $event_id;

			if ( !isset( $data['venue'] ) ) {
				$data['venue'] = $venue;
			}

			$db->insert_entry( $data, $fields_atts, false );
			break;
		case 'edit' :
			$data = array();

			foreach( $_POST as $key => $value ) {
				if ( $key === 'custom' ) {
					$data['custom'] = json_decode( stripslashes( $_POST['custom'] ), true );
				} elseif ( $key === 'standard' ) {
					$standard = json_decode( stripslashes( $_POST['standard'] ), true );
					foreach ( $standard as $key_2 => $value_2 ) {
						$data[ $key_2 ] = sanitize_text_field( $value_2 );
					}
				}
			}

			$data['event_id'] = $event_id;

			if ( !isset( $data['venue'] ) ) {
				$data['venue'] = $venue;
			}
			$db->update_entry( $data, $entry_id, $fields_atts );

			break;
		default :
			die( 'incorrect action' );
	}

	$db->update_num_registered_meta_for_event( $event_id );
	$new_count = $db->get_registration_count( $event_id, 0 );

	echo $new_count;

	die();
}
add_action( 'wp_ajax_rtec_records_edit', 'rtec_records_edit' );

function rtec_update_status() {
	$nonce = $_POST['rtec_nonce'];

	if ( ! wp_verify_nonce( $nonce, 'rtec_nonce' ) ) {
		die ( 'You did not do this the right way!' );
	}

	$db = new RTEC_Db();

	$entry_ids = $_POST['entry_ids'];

	$db->update_statuses( $entry_ids, $current = 'p', $updated = 'c', $column = 'id' );

	die();
}
add_action( 'wp_ajax_rtec_update_status', 'rtec_update_status' );

/**
 * Export registrations for a single event
 *
 * @since 2.0
 */
function rtec_event_csv() {
	if ( isset( $_POST['rtec_event_csv'] ) && current_user_can( 'edit_posts' ) ) {

		$nonce = $_POST['rtec_csv_export_nonce'];

		if ( ! wp_verify_nonce( $nonce, 'rtec_csv_export' ) ) {
			die ( 'You did not do this the right way!' );
		}
		$use_utf8_fix = apply_filters( 'rtec_utf8_fix', false );

		if ( $use_utf8_fix ) {
			require_once RTEC_PLUGIN_DIR . 'vendor/ForceUTF8/Encoding.php';
			$encoding = new RTEC_Encoding();
		}

		$rtec = RTEC();
		$form = $rtec->form->instance();

		$event_obj = new RTEC_Admin_Event();
		$form->build_form( (int)$_GET['id'] );

		$event_obj->build_admin_event( (int)$_GET['id'], 'csv', '', $form );
		$event_meta = $event_obj->event_meta;
		$venue_title = $event_meta['venue_title'];

		$event_meta_string = array(
			array( $event_meta['title'] ) ,

			array( date_i18n( str_replace( ',', ' ', rtec_get_date_time_format() ), strtotime( $event_meta['start_date'] ) ) ),
			array( date_i18n( str_replace( ',', ' ', rtec_get_date_time_format() ), strtotime( $event_meta['end_date'] ) ) ),
			array( $venue_title ),
			array_map( 'stripslashes', $event_obj->labels )
		);

		$file_name = str_replace( ' ', '-', substr( $event_meta['title'], 0, 10 ) ) . '_' . str_replace( ' ', '-', substr( $event_meta['venue_title'], 0, 10 ) ) . '_'  . date_i18n( 'm.d', strtotime( $event_meta['start_date'] ) );

		// output headers so that the file is downloaded rather than displayed
		header( 'Content-Encoding: UTF-8' );
		header( 'Content-type: text/csv; charset=UTF-8' );
		header( 'Content-Disposition: attachment; filename="' . str_replace( ',', '', $file_name ) . '.csv"' );
		echo "\xEF\xBB\xBF"; // UTF-8 BOM

		// create a file pointer connected to the output stream
		$output = fopen( 'php://output', 'w' );
		foreach ( $event_meta_string as $meta ) {
			fputcsv( $output, $meta );
		}
		foreach ( $event_obj->registrants_data as $registration ) {

			$time_format = rtec_get_time_format();
			$formatted_registration = array( 'registration_date' => date_i18n( 'F jS, ' . $time_format, strtotime( $registration['registration_date'] ) + rtec_get_time_zone_offset() ) );

			foreach ( $event_obj->column_label as $column => $label ) {

				if ( isset( $registration[$column] ) ) {
					$value = stripslashes( $registration[$column] );
				} else if ( isset( $registration[$column.'_name'] ) ) {
					$value = stripslashes( $registration[$column.'_name'] );
				} else if ( isset( $registration['custom'][$column] ) ) {
					$value = stripslashes( $registration['custom'][$column]['value'] );
				} else if ( isset( $registration['custom'][$label] ) ) {
					$value = stripslashes( $registration['custom'][$label] );
				}

				$value = $use_utf8_fix ? $encoding->fixUTF8( $value ) : $value;

				$formatted_registration[$column] = $value;

			}

			fputcsv( $output, $formatted_registration );

		}

		fclose( $output );

		die();
	}
}
add_action( 'admin_init', 'rtec_event_csv' );

/**
 * Accessed with AJAX from admin pages to show search results for matching
 * first, last, email, and phone fields
 *
 * @since 2.0
 */
function rtec_get_search_results() {
	global $rtec_options;

	$nonce = $_POST['rtec_nonce'];

	if ( ! wp_verify_nonce( $nonce, 'rtec_nonce' ) ) {
		die ( 'You did not do this the right way!' );
	}

	$db = new RTEC_Db_Admin();
	$term = sanitize_text_field( $_POST['term'] );

	$search_array = array( 'last_name', 'first_name' );
	if ( preg_match( '/\.|@/', $term ) === 1 ) {
		$search_array = array( 'email' );
	}
	if ( preg_match( '/\d/', $term ) === 1 ) {
		$search_array[] = 'phone';
	}

	$matches = $db->get_matches( $term, $search_array );

	$table_columns = array( 'first_name', 'last_name', 'email', 'phone' ); //, 'event_id', 'venue'
	$labels = array();
	foreach ( $table_columns as $table_column ) {
		$labels[] = isset( $rtec_options[ str_replace( '_name', '', $table_column ) . '_label' ] ) ? $rtec_options[ str_replace( '_name', '', $table_column )  . '_label' ] : '';
	}

	$WP_offset = get_option( 'gmt_offset' );

	if ( ! empty( $WP_offset ) ) {
		$tz_offset = $WP_offset * HOUR_IN_SECONDS;
	} else {
		$timezone = isset( $options['timezone'] ) ? $rtec_options['timezone'] : 'America/New_York';
		// use php DateTimeZone class to handle the date formatting and offsets
		$date_obj = new DateTime( date( 'm/d g:i a' ), new DateTimeZone( "UTC" ) );
		$date_obj->setTimeZone( new DateTimeZone( $timezone ) );
		$utc_offset = $date_obj->getOffset();
		$tz_offset = $utc_offset;
	}

	?>
	<table class="widefat rtec-registrations-data">
		<thead>
		<tr>
			<th><?php _e( 'Registration Date', 'registrations-for-the-events-calendar' ) ?></th>
			<?php foreach ( $labels as $label ) : ?>
				<th><?php echo esc_html( $label ); ?></th>
			<?php endforeach; ?>
			<th><?php _e( 'Event', 'registrations-for-the-events-calendar' ) ?></th>
			<th><?php _e( 'Start Date', 'registrations-for-the-events-calendar' ) ?></th>
		</tr>
		</thead>
		<tbody>
		<?php

		if ( ! empty( $matches ) ) : foreach( $matches as $registration ) :
			$event_meta = rtec_get_event_meta( $registration['event_id'] );

			?>

			<tr>
				<td class="rtec-first-data">
					<?php if ( $registration['status'] == 'n' ) {
						echo '<span class="rtec-notice-new">' . _( 'new' ) . '</span>';
					}
					echo esc_html( date_i18n( 'm/d ' . rtec_get_time_format(), strtotime( $registration['registration_date'] ) + $tz_offset ) ); ?>
				</td>
				<?php foreach ( $table_columns as $column ) : ?>
					<td><?php
						if ( isset( $registration[$column] ) ) {

							if ( $column === 'phone' ) {
								echo esc_html( rtec_format_phone_number( str_replace( '\\', '', $registration[ $column ] ) ) );
							} else {
								echo esc_html( str_replace( '\\', '', $registration[ $column ] ) );
							}

						}
						?></td>
				<?php endforeach; ?>
				<td><a href="<?php echo RTEC_ADMIN_URL; ?>&tab=single&id=<?php echo esc_attr( $event_meta['post_id'] ); ?>&record=<?php echo esc_attr( $registration['id'] ) ?>mvt=<?php echo esc_attr( '' ); ?>"><?php echo  esc_html( $event_meta['title'] ); ?></a></td>
				<td><?php echo date_i18n( rtec_get_date_time_format(), strtotime( $event_meta['start_date'] ) ); ?></td>
			</tr>
		<?php endforeach; // registration ?>

		<?php else: ?>

			<tr>
				<td colspan="4"><?php _e( 'No Registrations Found', 'registrations-for-the-events-calendar' ); ?></td>
			</tr>

		<?php endif; // registrations not empty ?>
		</tbody>
	</table>
	<?php
	die();
}
add_action( 'wp_ajax_rtec_get_search_results', 'rtec_get_search_results' );

function rtec_dismiss_new() {
	$nonce = $_POST['rtec_nonce'];

	if ( ! wp_verify_nonce( $nonce, 'rtec_nonce' ) ) {
		die ( 'You did not do this the right way!' );
	}

	$rtec = RTEC();
	$rtec->db_frontend->dismiss_new();

	die();
}
add_action( 'wp_ajax_rtec_dismiss_new', 'rtec_dismiss_new' );

/**
 * Some CSS and JS needed in the admin area as well
 *
 * @since 1.0
 */
function rtec_admin_scripts_and_styles() {
	wp_enqueue_style( 'rtec_admin_styles', trailingslashit( RTEC_PLUGIN_URL ) . 'css/rtec-admin-styles.css', array(), RTEC_VERSION );

	if ( isset( $_GET['page'] ) && ($_GET['page'] === RTEC_MENU_SLUG || $_GET['page'] === 'registrations-for-the-events-calendar/_settings' ) ) {

		wp_enqueue_script( 'rtec_admin_scripts', trailingslashit( RTEC_PLUGIN_URL ) . 'js/rtec-admin-scripts.js', array( 'jquery', 'jquery-ui-datepicker','tribe-jquery-timepicker' ), RTEC_VERSION, false );
		wp_localize_script( 'rtec_admin_scripts', 'rtecAdminScript',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'rtec_nonce' => wp_create_nonce( 'rtec_nonce' )
			)
		);
		wp_enqueue_style( 'rtec_font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css', array(), '4.6.3' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'jquery-ui-core ');
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'tribe-jquery-timepicker' );
		wp_enqueue_style( 'tribe-jquery-timepicker-css' );

	} else {
		wp_enqueue_script( 'rtec_admin_edit_event_scripts', trailingslashit( RTEC_PLUGIN_URL ) . 'js/rtec-admin-edit-event-scripts.js', array( 'jquery' ), RTEC_VERSION, false );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'tribe-jquery-timepicker' );
		wp_enqueue_style( 'tribe-jquery-timepicker-css' );
	}
}
add_action( 'admin_enqueue_scripts', 'rtec_admin_scripts_and_styles' );

/**
 * Add links to the plugin action links
 *
 * @since 1.0
 */
function rtec_plugin_action_links( $links ) {
	$links[] = '<a href="'. esc_url( get_admin_url( null, RTEC_ADMIN_URL . '&tab=form' ) ) .'">Settings</a>';
	return $links;
}
add_filter( 'plugin_action_links_' . RTEC_PLUGIN_BASENAME, 'rtec_plugin_action_links' );

/**
 * Add links to setup and pro versions
 *
 * @since 1.0
 */
function rtec_plugin_meta_links( $links, $file ) {
	$plugin = RTEC_PLUGIN_BASENAME;
	// create link
	if ( $file == $plugin ) {
		return array_merge(
			$links,
			array( '<a href="https://www.roundupwp.com/products/registrations-for-the-events-calendar/setup/" target="_blank">Setup Instructions</a>', '<a href="https://www.roundupwp.com/products/registrations-for-the-events-calendar-pro/" target="_blank">Buy Pro</a>' )
		);
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'rtec_plugin_meta_links', 10, 2 );

/**
 * Returns the columns for the particular event
 *
 * @param bool $full    type of set to return
 *
 * @since 1.3
 * @return array    columns for registrations view
 */
function rtec_get_event_columns( $full = false ) {
	global $rtec_options;

	$first_label = isset( $rtec_options['first_label'] ) && ! empty( $rtec_options['first_label'] ) ? esc_html( $rtec_options['first_label'] ) : __( 'First', 'registrations-for-the-events-calendar' );
	$last_label = isset( $rtec_options['last_label'] ) && ! empty( $rtec_options['last_label'] ) ? esc_html( $rtec_options['last_label'] ) : __( 'Last', 'registrations-for-the-events-calendar' );
	$email_label = isset( $rtec_options['email_label'] ) && ! empty( $rtec_options['email_label'] ) ? esc_html( $rtec_options['email_label'] ) : __( 'Email', 'registrations-for-the-events-calendar' );
	$phone_label = isset( $rtec_options['phone_label'] ) && ! empty( $rtec_options['phone_label'] ) ? esc_html( $rtec_options['phone_label'] ) : __( 'Phone', 'registrations-for-the-events-calendar' );
	$other_label = isset( $rtec_options['other_label'] ) && ! empty( $rtec_options['other_label'] ) ? esc_html( $rtec_options['other_label'] ) : __( 'Other', 'registrations-for-the-events-calendar' );

	$labels = array( $last_label, $first_label, $email_label, $phone_label, $other_label );

	if ( ! $full ) {
		// add custom labels
		if ( isset( $rtec_options['custom_field_names'] ) ) {
			$custom_field_names = explode( ',', $rtec_options['custom_field_names'] );
		} else {
			$custom_field_names = array();
		}

		foreach ( $custom_field_names as $field ) {
			if( isset( $rtec_options[$field . '_label'] ) ) {
				$labels[] = $rtec_options[$field . '_label'];
			}
		}
	} else {
		$labels[] = 'custom';
	}


	return $labels;
}

/**
 * Check db version and update if necessary
 *
 * @since 1.1   added check and add for "phone" column
 * @since 1.3   added check and add for index on event_id and add "custom" column,
 *              raise character limit for "other" column
 * @since 1.3.2 raise character limit for most fields to match "post" table
 * @since 1.4   added check and add for indices
 */
function rtec_db_update_check() {
	$db_ver = get_option( 'rtec_db_version', 0 );

	// adds "phone" column to database
	if ( $db_ver < 1.1 ) {
		update_option( 'rtec_db_version', RTEC_DBVERSION );

		$db = new RTEC_Db_Admin();
		$db->maybe_add_column_to_table( 'phone', 'VARCHAR(40)' );
	}

	// adds "custom" column
	if ( $db_ver < 1.2 ) {
		update_option( 'rtec_db_version', RTEC_DBVERSION );

		$db = new RTEC_Db_Admin();
		$db->maybe_add_index( 'event_id', 'event_id' );
		$db->maybe_add_column_to_table( 'custom', 'longtext' );
		$db->maybe_update_column( "VARCHAR(1000) NOT NULL", 'other' );
	}

	if ( $db_ver < 1.3 ) {
		update_option( 'rtec_db_version', RTEC_DBVERSION );

		$db = new RTEC_Db_Admin();
		$db->maybe_update_column( "BIGINT(20) UNSIGNED NOT NULL", 'event_id' );
		$db->maybe_update_column( "VARCHAR(1000) NOT NULL", 'first_name' );
		$db->maybe_update_column( "VARCHAR(1000) NOT NULL", 'last_name' );
		$db->maybe_update_column( "VARCHAR(1000) NOT NULL", 'email' );
		$db->maybe_update_column( "VARCHAR(1000) NOT NULL", 'venue' );
	}

	if ( $db_ver < 1.4 ) {
		update_option( 'rtec_db_version', RTEC_DBVERSION );

		$db = new RTEC_Db_Admin();
		$db->maybe_add_index( 'event_id', 'event_id' );
		$db->maybe_add_index( 'status', 'status' );
	}

	if ( $db_ver < 1.5 ) {
		update_option( 'rtec_db_version', RTEC_DBVERSION );

		$db = new RTEC_Db_Admin();
		$db->maybe_add_column_to_table_no_string( 'guests', 'INT(11) UNSIGNED' );
		$db->maybe_add_column_to_table( 'reminder', 'VARCHAR(40)', 'pending', true );
		$db->maybe_add_index( 'reminder', 'reminder' );
		$db->maybe_add_column_to_table( 'action_key', 'VARCHAR(40)', '', true );
		$db->maybe_add_column_to_table_no_string( 'user_id', 'BIGINT(20) UNSIGNED' );
	}

}
add_action( 'plugins_loaded', 'rtec_db_update_check' );


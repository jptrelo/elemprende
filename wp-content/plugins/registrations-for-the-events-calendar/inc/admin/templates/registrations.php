<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
require_once RTEC_PLUGIN_DIR . 'inc/class-rtec-db.php';
$db = new RTEC_Db();

//RTEC_ADMIN_URL
$rtec = RTEC();
$form = $rtec->form->instance();
$db = $rtec->db_frontend->instance();

$admin_registrations = new RTEC_Admin_Registrations();
$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'registrations';

$view_type = isset( $_GET['v'] ) ? sanitize_text_field( $_GET['v'] ) : 'grid';
$query_type = isset( $_GET['qtype'] ) ? sanitize_text_field( $_GET['qtype'] ) : 'upcoming';
$start_date = isset( $_GET['start'] ) ? date( 'Y-m-d H:i:s', strtotime( $_GET['start'] ) ) : date( 'Y-m-d H:i:s' );
$reg_status = isset( $_GET['with'] ) ? sanitize_text_field( $_GET['with'] ) : 'with';
$query_offset = isset( $_GET['off'] ) ? max( (int)$_GET['off'], 0 ) : 0;

$settings = array(
	'v' => $view_type,
	'qtype' => $query_type,
	'with' => $reg_status,
	'off' => $query_offset,
	'start' => $start_date
);

$admin_registrations->build_admin_registrations( $tab, $settings );

$admin_registrations->the_registrations_overview();

rtec_the_admin_notices();

?>
	<h1><?php _e( 'Overview', 'registrations-for-the-events-calendar' ); ?></h1>

<?php do_action( 'rtec_registrations_tab_after_the_title' ); ?>

	<div class="rtec-wrapper rtec-overview rtec-overview-<?php echo $view_type; ?>">

		<?php do_action( 'rtec_registrations_tab_before_events' ); ?>

		<?php do_action( 'rtec_registrations_tab_events' ); ?>

		<div class="rtec-clear"></div>

		<?php do_action( 'rtec_registrations_tab_pagination' ); ?>

	</div> <!-- rtec-wrapper -->

<?php do_action( 'rtec_registrations_tab_events_loaded', $admin_registrations->get_ids_on_page() );// if ( isset( $event_ids_on_page ) ) { $db->update_statuses( $event_ids_on_page ); }
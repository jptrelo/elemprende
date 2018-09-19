<?php
/**
 * WCFM plugin view
 *
 * WCFM Header Panels View
 *
 * @author 		WC Lovers
 * @package 	wcfm/view
 * @since     2.3.2
 */

global $WCFM, $wpdb, $wp, $blog_id;

$wcfm_options = get_option('wcfm_options');

$is_headpanel_disabled = isset( $wcfm_options['headpanel_disabled'] ) ? $wcfm_options['headpanel_disabled'] : 'no';
$is_responsive_float_menu_disabled = isset( $wcfm_options['responsive_float_menu_disabled'] ) ? $wcfm_options['responsive_float_menu_disabled'] : 'no';
if( $is_headpanel_disabled == 'yes' ) return;

$is_menu_disabled = isset( $wcfm_options['menu_disabled'] ) ? $wcfm_options['menu_disabled'] : 'no';

$wcfm_is_allow_headpanels = apply_filters( 'wcfm_is_allow_headpanels', true );
if( !$wcfm_is_allow_headpanels ) {
	return;
}

$user_id = get_current_user_id();
$wp_user_avatar_id = get_user_meta( $user_id, $wpdb->get_blog_prefix($blog_id).'user_avatar', true );
$wp_user_avatar = wp_get_attachment_url( $wp_user_avatar_id );
if( !$wp_user_avatar && apply_filters( 'wcfm_is_pref_buddypress', true ) && WCFM_Dependencies::wcfm_biddypress_plugin_active_check() ) {
	$wp_user_avatar = bp_core_fetch_avatar( array( 'html' => false, 'item_id' => $user_id ) );
}
if ( !$wp_user_avatar ) {	
	$wp_user_avatar = apply_filters( 'wcfm_defaut_user_avatar', $WCFM->plugin_url . 'assets/images/user.png' );
}

$unread_notice = $WCFM->wcfm_notification->wcfm_direct_message_count( 'notice' );
$unread_message = $WCFM->wcfm_notification->wcfm_direct_message_count( 'message' ); 
$unread_enquiry = $WCFM->wcfm_notification->wcfm_direct_message_count( 'enquiry' );

$store_name = apply_filters( 'wcfm_store_name', __( 'My Store', 'wc-frontend-manager' ) );
//$store_name = __( 'My Store', 'wc-frontend-manager' );
?>

<?php if( $is_menu_disabled != 'yes' ) { ?>
  <span class="wcfm_menu_toggler fa fa-bars text_tip" data-tip="<?php _e( 'Toggle Menu', 'wc-frontend-manager' ); ?>"></span>
<?php } ?>
<?php if( $is_responsive_float_menu_disabled == 'yes' ) { ?>
	<span class="wcfm_responsive_menu_toggler fa fa-bars" title="<?php _e( 'Toggle Menu', 'wc-frontend-manager' ); ?>"></span>
<?php } ?>
<span class="wcfm-store-name-heading-text"><?php _e( $store_name );?></span>

<div class="wcfm_header_panel">
  <?php do_action( 'wcfm_before_header_panel_item' ); ?>
  
  <?php if( apply_filters( 'wcfm_is_pref_profile', true ) && apply_filters( 'wcfm_is_allow_profile', true ) ) { ?>
    <a href="<?php echo get_wcfm_profile_url(); ?>" class="wcfm_header_panel_profile <?php if( isset( $wp->query_vars['wcfm-profile'] ) ) echo 'active'; ?>"><img class="wcfm_header_panel_profile_img  text_tip" src="<?php echo $wp_user_avatar; ?>" data-tip="<?php _e( 'Profile', 'wc-frontend-manager' ); ?>" /></a>
  <?php } ?>
  
  <?php if( apply_filters( 'wcfm_is_pref_direct_message', true ) && apply_filters( 'wcfm_is_allow_notifications', true ) ) { ?>
    <a href="<?php echo get_wcfm_messages_url( ); ?>" class="wcfm_header_panel_messages text_tip <?php if( isset( $wp->query_vars['wcfm-messages'] ) ) echo 'active'; ?>" data-tip="<?php _e( 'Notification Board', 'wc-frontend-manager' ); ?>"><i class="fa fa-bell-o"></i><span class="unread_notification_count message_count"><?php echo $unread_message; ?></span><div class="notification-ring"></div></a>
  <?php } ?>
  
  <?php if( apply_filters( 'wcfm_is_pref_enquiry', true ) && apply_filters( 'wcfm_is_allow_enquiry', true ) ) { ?>
    <a href="<?php echo get_wcfm_enquiry_url(); ?>" class="wcfm_header_panel_enquiry text_tip <?php if( isset( $wp->query_vars['wcfm-enquiry'] ) || isset( $wp->query_vars['wcfm-enquiry-manage'] ) ) echo 'active'; ?>" data-tip="<?php _e( 'Enquiry Board', 'wc-frontend-manager' ); ?>"><i class="fa fa-question-circle fa-question-circle-o"></i><span class="unread_notification_count enquiry_count"><?php echo $unread_enquiry; ?></span><div class="notification-ring"></div></a>
  <?php } ?>
  
  <?php if( apply_filters( 'wcfm_is_pref_notice', true ) && apply_filters( 'wcfm_is_allow_notice', true ) ) { ?>
    <a href="<?php echo get_wcfm_notices_url( ); ?>" class="wcfm_header_panel_notice text_tip <?php if( isset( $wp->query_vars['wcfm-notices'] ) || isset( $wp->query_vars['wcfm-notice-manage'] ) || isset( $wp->query_vars['wcfm-notice-view'] ) ) echo 'active'; ?>" data-tip="<?php _e( 'Notice Board', 'wc-frontend-manager' ); ?>"><i class="fa fa-bullhorn"></i><?php if( wcfm_is_vendor() ) { ?><span class="unread_notification_count notice_count"><?php echo $unread_notice; ?></span><?php } ?><div class="notification-ring"></div></a>
  <?php } ?>
  
  <?php if( apply_filters( 'wcfm_is_pref_knowledgebase', true ) && apply_filters( 'wcfm_is_allow_knowledgebase', true ) ) { ?>
    <a href="<?php echo get_wcfm_knowledgebase_url(); ?>" class="wcfm_header_panel_knowledgebase text_tip <?php if( isset( $wp->query_vars['wcfm-knowledgebase'] ) || isset( $wp->query_vars['wcfm-knowledgebase-manage'] ) ) echo 'active'; ?>" data-tip="<?php _e( 'Knowledgebase', 'wc-frontend-manager' ); ?>"><i class="fa fa-book"></i></a>
  <?php } ?>
  
  <?php do_action( 'wcfm_after_header_panel_item' ); ?>
  
  <?php if( apply_filters( 'wcfm_is_allow_header_logout', false ) ) { ?>
    <a href="<?php echo esc_url(wp_logout_url( apply_filters( 'wcfm_logout_url', get_wcfm_url() ) ) ); ?>" class="fa fa-power-off text_tip" data-tip="<?php _e( 'Logout', 'wc-frontend-manager' ); ?>"></a>
  <?php } ?>
</div>
<?php
/**
 * WCFMu plugin view
 *
 * Marketplace WC Marketplace Support
 *
 * @author 		WC Lovers
 * @package 	wcfm/views/dashboards
 * @version   3.3.5
 */
 
global $WCFM, $wpdb, $blog_id;

$user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
$wp_user_avatar_id = get_user_meta( $user_id, $wpdb->get_blog_prefix($blog_id).'user_avatar', true );
$wp_user_avatar = wp_get_attachment_url( $wp_user_avatar_id );
if( !$wp_user_avatar && apply_filters( 'wcfm_is_pref_buddypress', true ) && WCFM_Dependencies::wcfm_biddypress_plugin_active_check() ) {
	$wp_user_avatar = bp_core_fetch_avatar( array( 'html' => false, 'item_id' => $user_id ) );
}
if ( !$wp_user_avatar ) {	
	$wp_user_avatar = apply_filters( 'wcfm_defaut_user_avatar', $WCFM->plugin_url . 'assets/images/avatar.png' );
}

$userdata = get_userdata( $user_id );
$first_name = $userdata->first_name;
$last_name  = $userdata->last_name;
$display_name  = $userdata->display_name;
$previous_login = get_user_meta( $user_id, '_previous_login', true );

?>

<?php do_action( 'wcfm_before_dashboard_welcome_box' ); ?>

<?php if( apply_filters( 'wcfm_is_pref_welcome_box', true ) ) { ?>
	
	<div class="wcfm_welcomebox_header">
		<div class="lft wcfm_welcomebox_user">
			<div class="wcfm_welcomebox_user_profile lft">
				<?php if( $wcfm_is_allow_profile = apply_filters( 'wcfm_is_allow_profile', true ) ) { ?>
					<a href="<?php echo get_wcfm_profile_url(); ?>">
				<?php } ?>
					<img src="<?php echo $wp_user_avatar; ?>" alt="user"/>
				<?php if( $wcfm_is_allow_profile = apply_filters( 'wcfm_is_allow_profile', true ) ) { ?>
					</a>
				<?php } ?>
			</div>
			<div class="wcfm_welcomebox_user_details rgt">
				<h3><?php echo apply_filters( 'wcfm_dashboard_welcometext', sprintf( __('Welcome to %s Dashboard', 'wc-frontend-manager' ), get_bloginfo() ) ); ?></h3>
				<div class="wcfm_welcomebox_membership">
					<span>
					<?php
					if( $first_name ) {
						echo apply_filters( 'wcfm_welcomebox_username', $first_name . ' ' . $last_name );
					} else {
						echo apply_filters( 'wcfm_welcomebox_username', $display_name );
					}
					?>
					</span> 
					<?php do_action( 'wcfm_dashboard_after_username', $user_id ); ?>
				</div>
				<?php if( $previous_login ) { ?>
					<div class="wcfm_welcomebox_last_time"><span class="fa fa-clock-o"></span><?php _e( 'Last Login:', 'wc-frontend-manager' ); ?> <span><?php echo date_i18n( wc_time_format(), $previous_login ) . ' (' . date_i18n( wc_date_format(), $previous_login ) . ') '; ?></span></div>
				<?php } ?>                                       
			</div>
			<div class="spacer"></div>      
		</div>
		<?php if( wcfm_is_vendor() ) { ?>
			<div class="rgt wcfm_welcomebox_user_right">
				<div class="wcfm_welcomebox_user_right_box"><span class="fa fa-cube img_tip" data-tip="<?php _e( 'Product Limit Stats', 'wc-frontend-manager' ); ?>"></span><span><mark><?php echo $WCFM->wcfm_vendor_support->wcfm_vendor_product_limit_stat( $user_id ); ?></mark></span></div>
				<div class="wcfm_welcomebox_user_right_box"><span class="fa fa-hdd-o img_tip" data-tip="<?php _e('Disk Space Usage Stats', 'wc-frontend-manager' ); ?>"></span><span><mark><?php echo $WCFM->wcfm_vendor_support->wcfm_vendor_space_limit_stat( $user_id ); ?></mark></span></div>
			</div>
		<?php } ?>			
	  <div class="spacer"></div>    
	</div>
	<div class="wcfm-clearfix"></div>
<?php } ?>

<?php do_action( 'wcfm_after_dashboard_welcome_box' ); ?>
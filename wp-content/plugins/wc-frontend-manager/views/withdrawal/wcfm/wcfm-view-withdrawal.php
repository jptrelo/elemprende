<?php
/**
 * WCFM plugin view
 *
 * WCFM Marketplace Withdrawal List View
 *
 * @author 		WC Lovers
 * @package 	wcfm/views/wothdrawal/wcfm
 * @version   5.0.0
 */
 
global $WCFM, $woocommerce, $WCFMmp;

$wcfm_is_allow_withdrawal = apply_filters( 'wcfm_is_allow_withdrawal', true );
if( !$wcfm_is_allow_withdrawal ) {
	wcfm_restriction_message_show( "Withdrawal" );
	return;
}

$vendor_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
$withdrawal_limit = $WCFMmp->wcfmmp_withdraw->get_withdrawal_limit( $vendor_id );
$pending_withdrawal = $WCFM->wcfm_vendor_support->wcfm_get_pending_withdrawal_by_vendor( $vendor_id, 'all' );
?>
<div class="collapse wcfm-collapse" id="wcfm_withdrawal_listing">
  <div class="wcfm-page-headig">
		<span class="fa fa-currency"><?php echo get_woocommerce_currency_symbol(); ?></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Withdrawal Request', 'wc-frontend-manager' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>
	  
		<div class="wcfm-container wcfm-top-element-container">
			<h2 style="text-align: left;">
				<?php _e( 'Pending Withdrawals: ', 'wc-frontend-manager' ); ?> 
				<span class=""><?php echo wc_price($pending_withdrawal); ?></span>
				<?php if( $withdrawal_limit ) { ?>
					<br />
					<?php _e( 'Threshold for withdrawals: ', 'wc-frontend-manager' ); ?> 
					<span class=""><?php echo wc_price($withdrawal_limit); ?></span>
				<?php } ?>
			</h2>
			<?php
			if( $wcfm_is_allow_payments = apply_filters( 'wcfm_is_allow_payments', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.wcfm_payments_url().'" data-tip="'. __('Transaction History', 'wc-frontend-manager') .'"><span class="fa fa-credit-card"></span><span class="text">' . __('Transactions', 'wc-frontend-manager' ) . '</span></a>';
			}
			?>
			<div class="wcfm-clearfix"></div>
		</div>
	  <div class="wcfm-clearfix"></div><br />
	  
	  <?php do_action( 'before_wcfm_withdrawal' ); ?>
		
		<form metod="post" id="wcfm_withdrawal_manage_form">
		  <div class="wcfm-container">
				<div id="wcfm_withdrawal_listing_expander" class="wcfm-content">
					<table id="wcfm-withdrawal" class="display" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th><span class="wcicon-status-processing text_tip" data-tip="<?php _e( 'Send Request', 'wc-frontend-manager' ); ?>"></span></th>
								<th><?php printf( apply_filters( 'wcfm_commission_order_label', __( 'Order ID', 'wc-frontend-manager' ) ) ); ?></th>
								<th><?php _e( 'Commission ID', 'wc-frontend-manager' ); ?></th>
								<th><?php _e( 'My Earnings', 'wc-frontend-manager' ); ?></th>
								<th><?php _e( 'Charges', 'wc-frontend-manager' ); ?></th>
								<th><?php _e( 'Payment', 'wc-frontend-manager' ); ?></th>
								<th><?php _e( 'Date', 'wc-frontend-manager' ); ?></th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th><span class="wcicon-status-processing text_tip" data-tip="<?php _e( 'Send Request', 'wc-frontend-manager' ); ?>"></span></th>
								<th><?php printf( apply_filters( 'wcfm_commission_order_label', __( 'Order ID', 'wc-frontend-manager' ) ) ); ?></th>
								<th><?php _e( 'Commission ID', 'wc-frontend-manager' ); ?></th>
								<th><?php _e( 'My Earnings', 'wc-frontend-manager' ); ?></th>
								<th><?php _e( 'Charges', 'wc-frontend-manager' ); ?></th>
								<th><?php _e( 'Payment', 'wc-frontend-manager' ); ?></th>
								<th><?php _e( 'Date', 'wc-frontend-manager' ); ?></th>
							</tr>
						</tfoot>
					</table>
					<div class="wcfm-clearfix"></div>
				</div>	
			</div>	
			<div class="wcfm-clearfix"></div>
			
			<div class="withdrawal_charge_help">** <?php _e( 'Withdrawal charges will be re-calculated depending upon total withdrawal amount.', 'wc-frontend-manager' ); ?></div>
			<div class="wcfm-clearfix"></div>
			
			<div id="wcfm_products_simple_submit" class="wcfm_form_simple_submit_wrapper">
			  <div class="wcfm-message" tabindex="-1"></div>
			  
			  <?php
					if ( (float) $pending_withdrawal >= (float) $withdrawal_limit ) {
					?>
					  <input type="submit" name="withdrawal-data" value="<?php _e( 'Request', 'wc-frontend-manager' ); ?>" id="wcfm_withdrawal_request_button" class="wcfm_submit_button" />
				<?php } ?>
			</div>
			<div class="wcfm-clearfix"></div>
		</form>
		<?php
		do_action( 'after_wcfm_withdrawal' );
		?>
	</div>
</div>
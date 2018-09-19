<?php
/**
 * WCFM plugin controllers
 *
 * Plugin WCfM Marketplace Withdrawal Requests Dashboard Controller
 *
 * @author 		WC Lovers
 * @package 	wcfm/controllers/withdrawal/wcfm
 * @version   5.0.0
 */

class WCFM_Withdrawal_Requests_Controller {
	
	public function __construct() {
		global $WCFM;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $wpdb, $_POST, $WCFMmp;
		
		$length = $_POST['length'];
		$offset = $_POST['start'];
		
		$the_orderby = ! empty( $_POST['orderby'] ) ? sanitize_text_field( $_POST['orderby'] ) : 'ID';
		$the_order   = ( ! empty( $_POST['order'] ) && 'asc' === $_POST['order'] ) ? 'ASC' : 'DESC';
		
		$transaction_id = ! empty( $_POST['transaction_id'] ) ? sanitize_text_field( $_POST['transaction_id'] ) : '';
		
		$withdrawal_vendor_filter = '';
		if ( ! empty( $_POST['withdrawal_vendor'] ) ) {
			$withdrawal_vendor = esc_sql( $_POST['withdrawal_vendor'] );
			$withdrawal_vendor_filter = " AND commission.`vendor_id` = {$withdrawal_vendor}";
		}
		
		$status_filter = 'requested';
    if( isset($_POST['status_type']) ) {
    	$status_filter = $_POST['status_type'];
    }
    if( $status_filter ) {
    	$status_filter = " AND `withdraw_status` = '" . $status_filter . "'";
    }

		$sql = 'SELECT COUNT(commission.ID) FROM ' . $wpdb->prefix . 'wcfm_marketplace_withdraw_request AS commission';
		$sql .= ' WHERE 1=1';
		if( $transaction_id ) $sql .= " AND commission.ID = $transaction_id";
		$sql .= $status_filter; 
		if ( $withdrawal_vendor_filter ) {
			$sql .= $withdrawal_vendor_filter;
		} else {
			$sql .= " AND commission.vendor_id != 0";
		}
		
		$filtered_withdrawal_requests_count = $wpdb->get_var( $sql );
		if( !$filtered_withdrawal_requests_count ) $filtered_withdrawal_requests_count = 0;

		$sql = 'SELECT * FROM ' . $wpdb->prefix . 'wcfm_marketplace_withdraw_request AS commission';
		$sql .= ' WHERE 1=1';
		if( $transaction_id ) $sql .= " AND commission.ID = $transaction_id";
		$sql .= $status_filter; 
		if ( $withdrawal_vendor_filter ) {
			$sql .= $withdrawal_vendor_filter;
		} else {
			$sql .= " AND commission.vendor_id != 0";
		}
		$sql .= " ORDER BY `{$the_orderby}` {$the_order}";
		$sql .= " LIMIT {$length}";
		$sql .= " OFFSET {$offset}";
		
		$wcfm_withdrawal_requests_array = $wpdb->get_results( $sql );
		
		if ( WC()->payment_gateways() ) {
			$payment_gateways = WC()->payment_gateways->payment_gateways();
		} else {
			$payment_gateways = array();
		}
		
		// Generate Payments JSON
		$wcfm_payments_json = '';
		$wcfm_payments_json = '{
															"draw": ' . $_POST['draw'] . ',
															"recordsTotal": ' . $filtered_withdrawal_requests_count . ',
															"recordsFiltered": ' . $filtered_withdrawal_requests_count . ',
															"data": ';
		if(!empty($wcfm_withdrawal_requests_array)) {
			$index = 0;
			$wcfm_withdrawal_requests_json_arr = array();
			foreach( $wcfm_withdrawal_requests_array as $wcfm_withdrawal_request_single ) {
				
				// Status
				if( $wcfm_withdrawal_request_single->withdraw_status == 'completed' ) {
					$wcfm_withdrawal_requests_json_arr[$index][] =  '<span class="payment-status tips wcicon-status-completed text_tip" data-tip="' . __( 'Withdrawal Completed', 'wc-frontend-manager') . '"></span>';
				} elseif( $wcfm_withdrawal_request_single->withdraw_status == 'cancelled' ) {
					$wcfm_withdrawal_requests_json_arr[$index][] =  '<span class="payment-status tips wcicon-status-cancelled text_tip" data-tip="' . __( 'Withdrawal Cancelled', 'wc-frontend-manager') . '"></span>';
				} elseif( $wcfm_withdrawal_request_single->is_auto_withdrawal == 1 ) {
					$wcfm_withdrawal_requests_json_arr[$index][] =  '<span class="payment-status tips wcicon-status-processing text_tip" data-tip="' . __( 'Withdrawal Processing', 'wc-frontend-manager') . '"></span>';
				} else {
					$wcfm_withdrawal_requests_json_arr[$index][] =  '<input name="withdrawals[]" value="' . $wcfm_withdrawal_request_single->ID . '" class="wcfm-checkbox select_withdrawal_requests" type="checkbox" >';
				}
				
				// Transc.ID
				$wcfm_withdrawal_requests_json_arr[$index][] = apply_filters( 'wcfm_withdrawal_requests_label', '<a href="' . wcfm_transaction_details_url( $wcfm_withdrawal_request_single->ID ) . '" class="wcfm_dashboard_item_title"># ' . sprintf( '%06u', $wcfm_withdrawal_request_single->ID ) . '</a>', $wcfm_withdrawal_request_single->ID, $wcfm_withdrawal_request_single->order_ids, $wcfm_withdrawal_request_single->commission_ids );
				
				// Store
				$wcfm_withdrawal_requests_json_arr[$index][] = $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( absint($wcfm_withdrawal_request_single->vendor_id) );
				
				// Amount
				$withdraw_amount = (float) $wcfm_withdrawal_request_single->withdraw_amount; 
				$wcfm_withdrawal_requests_json_arr[$index][] = wc_price( $withdraw_amount );
				
				// Charges
				$withdraw_charges = (float) $wcfm_withdrawal_request_single->withdraw_charges;  
				$wcfm_withdrawal_requests_json_arr[$index][] = wc_price( $withdraw_charges );
				
				// Payment
				$amount = wc_price( $withdraw_amount - $withdraw_charges );  
				$amount .= '<br /><small class="meta">' . __( 'Via', 'wc-frontend-manager' ) . ' ';
				if ( $wcfm_withdrawal_request_single->payment_method == 'paypal' ) {
					$amount .= __( 'PayPal', 'wc-frontend-manager' );
				} else if ($wcfm_withdrawal_request_single->payment_method == 'stripe') {
					$amount .= __( 'Stripe', 'wc-frontend-manager' );
				} else if ($wcfm_withdrawal_request_single->payment_method == 'skrill') {
					$amount .= __( 'Skrill', 'wc-multivendor-marketplace' );
				} else if ($wcfm_withdrawal_request_single->payment_method == 'bank_transfer') {
					$amount .= __( 'Bank Transfer', 'wc-multivendor-marketplace' );
				} else if ($wcfm_withdrawal_request_single->payment_method == 'by_cash') {
					$amount .= __( 'Cash Pay', 'wc-multivendor-marketplace' );
				} else {
					$amount .= ( isset( $payment_gateways[ $wcfm_withdrawal_request_single->payment_method ] ) ? esc_html( $payment_gateways[ $wcfm_withdrawal_request_single->payment_method ]->get_title() ) : esc_html( $wcfm_withdrawal_request_single->payment_method ) );
				}
				$amount .= '</small>';
				$wcfm_withdrawal_requests_json_arr[$index][] = $amount;
				
				// Order IDs
				$wcfm_withdrawal_requests_json_arr[$index][] =  '<span class="wcfm_dashboard_item_title transaction_commission_ids">#'.  $wcfm_withdrawal_request_single->order_ids . '</span>';
				
				// Commission IDs
				$wcfm_withdrawal_requests_json_arr[$index][] =  '<span class="wcfm_dashboard_item_title transaction_commission_ids">#'.  $wcfm_withdrawal_request_single->commission_ids . '</span>';
				
				// Note
				$wcfm_withdrawal_requests_json_arr[$index][] = $wcfm_withdrawal_request_single->withdraw_note;
				
				// Date
				if( in_array( $wcfm_withdrawal_request_single->withdraw_status, array('completed', 'cancelled') ) ) {
					$wcfm_withdrawal_requests_json_arr[$index][] = date( wc_date_format() . ' ' . wc_time_format(), strtotime( $wcfm_withdrawal_request_single->withdraw_paid_date ) );
				} else {
					$wcfm_withdrawal_requests_json_arr[$index][] = date( wc_date_format() . ' ' . wc_time_format(), strtotime( $wcfm_withdrawal_request_single->created ) );
				}
				
				
				$index++;
			}												
		}
		if( !empty($wcfm_withdrawal_requests_json_arr) ) $wcfm_payments_json .= json_encode($wcfm_withdrawal_requests_json_arr);
		else $wcfm_payments_json .= '[]';
		$wcfm_payments_json .= '
													}';
													
		echo $wcfm_payments_json;
	}
}
<?php
/**
 * WCFM plugin controllers
 *
 * Plugin WCfM Marketplace Withdrawal Dashboard Controller
 *
 * @author 		WC Lovers
 * @package 	wcfm/controllers/withdrawal/wcfm
 * @version   5.0.0
 */

class WCFM_Withdrawal_Controller {
	
	private $vendor_id;
	
	public function __construct() {
		global $WCFM, $WCFMmp;
		
		$this->vendor_id  = $WCFMmp->vendor_id;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $wpdb, $_POST, $WCFMmp;
		
		$length = $_POST['length'];
		$offset = $_POST['start'];
		
		$the_orderby = ! empty( $_POST['orderby'] ) ? sanitize_text_field( $_POST['orderby'] ) : 'order_id';
		$the_order   = ( ! empty( $_POST['order'] ) && 'asc' === $_POST['order'] ) ? 'ASC' : 'DESC';

		$withdrawal_thresold = $WCFMmp->wcfmmp_withdraw->get_withdrawal_thresold( $this->vendor_id );
		$status = get_wcfm_marketplace_active_withdrwal_order_status_in_comma();

		$sql = 'SELECT COUNT(commission.ID) FROM ' . $wpdb->prefix . 'wcfm_marketplace_orders AS commission';
		$sql .= ' WHERE 1=1';
		$sql .= " AND `vendor_id` = {$this->vendor_id}";
		$sql .= " AND commission.order_status IN ({$status})";
		$sql .= " AND commission.withdraw_status IN ('pending', 'cancelled')";
		$sql .= " AND commission.refund_status != 'requested'";
		$sql .= ' AND `is_withdrawable` = 1 AND `is_auto_withdrawal` = 0 AND `is_refunded` = 0 AND `is_trashed` = 0';
		if( $withdrawal_thresold ) $sql .= " AND commission.created <= NOW() - INTERVAL {$withdrawal_thresold} DAY";
		
		$filtered_withdrawal_count = $wpdb->get_var( $sql );
		if( !$filtered_withdrawal_count ) $filtered_withdrawal_count = 0;

		$sql = 'SELECT * FROM ' . $wpdb->prefix . 'wcfm_marketplace_orders AS commission';
		$sql .= ' WHERE 1=1';
		$sql .= " AND `vendor_id` = {$this->vendor_id}";
		$sql .= " AND commission.order_status IN ({$status})";
		$sql .= " AND commission.withdraw_status IN ('pending', 'cancelled')";
		$sql .= " AND commission.refund_status != 'requested'";
		$sql .= ' AND `is_withdrawable` = 1 AND `is_auto_withdrawal` = 0 AND `is_refunded` = 0 AND `is_trashed` = 0';
		if( $withdrawal_thresold ) $sql .= " AND commission.created <= NOW() - INTERVAL {$withdrawal_thresold} DAY";
		$sql .= " ORDER BY `{$the_orderby}` {$the_order}";
		$sql .= " LIMIT {$length}";
		$sql .= " OFFSET {$offset}";
		
		$wcfm_withdrawals_array = $wpdb->get_results( $sql );
		
		// Generate Withdrawals JSON
		$wcfm_withdrawals_json = '';
		$wcfm_withdrawals_json = '{
															"draw": ' . $_POST['draw'] . ',
															"recordsTotal": ' . $filtered_withdrawal_count . ',
															"recordsFiltered": ' . $filtered_withdrawal_count . ',
															"data": ';
		if(!empty($wcfm_withdrawals_array)) {
			$index = 0;
			$wcfm_withdrawals_json_arr = array();
			foreach($wcfm_withdrawals_array as $wcfm_withdrawals_single) {
				$order_id = $wcfm_withdrawals_single->order_id;
				
				if( apply_filters( 'wcfm_is_show_commission_restrict_check', false, $order_id, $wcfm_withdrawals_single ) ) continue;
				
				// Status
				$wcfm_withdrawals_json_arr[$index][] =  '<input name="commissions[]" value="' . $wcfm_withdrawals_single->ID . '" class="wcfm-checkbox select_withdrawal" type="checkbox" >';
				
				// Order ID
				$wcfm_withdrawals_json_arr[$index][] = apply_filters( 'wcfm_commission_order_label_display', '<span class="wcfm_dashboard_item_title"># ' . $order_id . '</span>', $order_id, $wcfm_withdrawals_single );
				
				// Commission ID
				$wcfm_withdrawals_json_arr[$index][] = '<span class="wcfm_dashboard_item_title withdrawal_order_ids"># ' . $wcfm_withdrawals_single->ID . '</span>'; 
				
				// My Earnings
				$wcfm_withdrawals_json_arr[$index][] = wc_price( $wcfm_withdrawals_single->total_commission );  
				
				// Charges
				$wcfm_withdrawals_json_arr[$index][] = wc_price( $wcfm_withdrawals_single->withdraw_charges );  
				
				// Payment
				$wcfm_withdrawals_json_arr[$index][] = wc_price( (float) $wcfm_withdrawals_single->total_commission - (float) $wcfm_withdrawals_single->withdraw_charges );  
				
				// Date
				$wcfm_withdrawals_json_arr[$index][] = apply_filters( 'wcfm_commission_date_display', date( wc_date_format() . ' ' . wc_time_format(), strtotime( $wcfm_withdrawals_single->created ) ), $order_id, $wcfm_withdrawals_single );
				
				$index++;
			}												
		}
		if( !empty($wcfm_withdrawals_json_arr) ) $wcfm_withdrawals_json .= json_encode($wcfm_withdrawals_json_arr);
		else $wcfm_withdrawals_json .= '[]';
		$wcfm_withdrawals_json .= '
													}';
													
		echo $wcfm_withdrawals_json;
	}
}
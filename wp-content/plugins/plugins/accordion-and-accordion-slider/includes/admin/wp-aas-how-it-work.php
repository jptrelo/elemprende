<?php
/**
 * Pro Designs and Plugins Feed
 *
 * @package Accordion and Accordion Slider
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Action to add menu
add_action('admin_menu', 'wp_aas_register_design_page');

/**
 * Register plugin design page in admin menu
 * 
 * @package Accordion and Accordion Slider
 * @since 1.0.0
 */
function wp_aas_register_design_page() {
	add_submenu_page( 'edit.php?post_type='.WP_AAS_POST_TYPE, __('How it works, our plugins and offers', 'accordion-and-accordion-slider'), __('How It Works', 'accordion-and-accordion-slider'), 'manage_options', 'wp-aas-designs', 'wp_aas_designs_page' );
}

/**
 * Function to display plugin design HTML
 * 
 * @package Accordion and Accordion Slider
 * @since 1.0.0
 */
function wp_aas_designs_page() {

	$wpos_feed_tabs = wp_aas_help_tabs();
	$active_tab 	= isset($_GET['tab']) ? $_GET['tab'] : 'how-it-work';
?>
		
	<div class="wrap wp-aas-wrap">

		<h2 class="nav-tab-wrapper">
			<?php
			foreach ($wpos_feed_tabs as $tab_key => $tab_val) {
				$tab_name	= $tab_val['name'];
				$active_cls = ($tab_key == $active_tab) ? 'nav-tab-active' : '';
				$tab_link 	= add_query_arg( array( 'post_type' => WP_AAS_POST_TYPE, 'page' => 'wp-aas-designs', 'tab' => $tab_key), admin_url('edit.php') );
			?>

			<a class="nav-tab <?php echo $active_cls; ?>" href="<?php echo $tab_link; ?>"><?php echo $tab_name; ?></a>

			<?php } ?>
		</h2>
		
		<div class="wp-aas-tab-cnt-wrp">
		<?php
			if( isset($active_tab) && $active_tab == 'how-it-work' ) {
				wp_aas_howitwork_page();
			}
			else if( isset($active_tab) && $active_tab == 'plugins-feed' ) {
				echo wp_aas_get_plugin_design( 'plugins-feed' );
			} else {
				echo wp_aas_get_plugin_design( 'offers-feed' );
			}
		?>
		</div><!-- end .wp-aas-tab-cnt-wrp -->

	</div><!-- end .wp-aas-wrap -->

<?php
}

/**
 * Gets the plugin design part feed
 *
 * @package Accordion and Accordion Slider
 * @since 1.0.0
 */
function wp_aas_get_plugin_design( $feed_type = '' ) {
	
	$active_tab = isset($_GET['tab']) ? $_GET['tab'] : '';
	
	// If tab is not set then return
	if( empty($active_tab) ) {
		return false;
	}

	// Taking some variables
	$wpos_feed_tabs = wp_aas_help_tabs();
	$transient_key 	= isset($wpos_feed_tabs[$active_tab]['transient_key']) 	? $wpos_feed_tabs[$active_tab]['transient_key'] 	: 'wp_aas_' . $active_tab;
	$url 			= isset($wpos_feed_tabs[$active_tab]['url']) 			? $wpos_feed_tabs[$active_tab]['url'] 				: '';
	$transient_time = isset($wpos_feed_tabs[$active_tab]['transient_time']) ? $wpos_feed_tabs[$active_tab]['transient_time'] 	: 172800;
	$cache 			= get_transient( $transient_key );
	
	if ( false === $cache ) {
		
		$feed 			= wp_remote_get( esc_url_raw( $url ), array( 'timeout' => 120, 'sslverify' => false ) );
		$response_code 	= wp_remote_retrieve_response_code( $feed );
		
		if ( ! is_wp_error( $feed ) && $response_code == 200 ) {
			if ( isset( $feed['body'] ) && strlen( $feed['body'] ) > 0 ) {
				$cache = wp_remote_retrieve_body( $feed );
				set_transient( $transient_key, $cache, $transient_time );
			}
		} else {
			$cache = '<div class="error"><p>' . __( 'There was an error retrieving the data from the server. Please try again later.', 'accordion-and-accordion-slider' ) . '</div>';
		}
	}
	return $cache;	
}

/**
 * Function to get plugin feed tabs
 *
 * @package Accordion and Accordion Slider
 * @since 1.0.0
 */
function wp_aas_help_tabs() {
	$wpos_feed_tabs = array(
						'how-it-work' 	=> array(
													'name' => __('How It Works', 'accordion-and-accordion-slider'),
												),
						'plugins-feed' 	=> array(
													'name' 				=> __('Our Plugins', 'accordion-and-accordion-slider'),
													'url'				=> 'http://wponlinesupport.com/plugin-data-api/plugins-data.php',
													'transient_key'		=> 'wpos_plugins_feed',
													'transient_time'	=> 172800
												),
						'offers-feed' 	=> array(
													'name'				=> __('WPOS Offers', 'accordion-and-accordion-slider'),
													'url'				=> 'http://wponlinesupport.com/plugin-data-api/wpos-offers.php',
													'transient_key'		=> 'wpos_offers_feed',
													'transient_time'	=> 86400,
												)
					);
	return $wpos_feed_tabs;
}

/**
 * Function to get 'How It Works' HTML
 *
 * @package Accordion and Accordion Slider
 * @since 1.0.0
 */
function wp_aas_howitwork_page() { ?>
	
	<style type="text/css">
		.wpos-pro-box .hndle{background-color:#0073AA; color:#fff;}
		.wpos-pro-box .postbox{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
		.postbox-container .wpos-list li:before{font-family: dashicons; content: "\f139"; font-size:20px; color: #0073aa; vertical-align: middle;}
		.wp-aas-wrap .wpos-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
		.wp-aas-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
	</style>

	<div class="post-box-container">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
			
				<!--How it workd HTML -->
				<div id="post-body-content">
					<div class="metabox-holder">
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox">
								
								<h3 class="hndle">
									<span><?php _e( 'How It Works - Display and shortcode', 'accordion-and-accordion-slider' ); ?></span>
								</h3>
								
								<div class="inside">
									<table class="form-table">
										<tbody>
											<tr>
												<th>
													<label><?php _e('Geeting Started with Accordion Slider', 'accordion-and-accordion-slider'); ?>:</label>
												</th>
												<td>
													<ul>
														<li><?php _e('Step-1. Go to "Accordion Slider --> Add New".', 'accordion-and-accordion-slider'); ?></li>
														<li><?php _e('Step-2. Enter Accordion Slider Title.', 'accordion-and-accordion-slider'); ?></li>
														<li><?php _e('Step-3. Under "Choose Gallery Images" click on "Gallery Images" button and select multiple images from WordPress media and click on "Add to Gallery" button..', 'accordion-and-accordion-slider'); ?></li>
														<li><?php _e('Step-4. You can use accordion slider parameters as per your need.', 'accordion-and-accordion-slider'); ?></li>
														<li><?php _e('Step-5. You can find out shortcode for accordion slider under "Accordion Slider" list view.', 'accordion-and-accordion-slider'); ?></li>
													</ul>
												</td>
											</tr>

											<tr>
												<th>
													<label><?php _e('How Shortcode Works', 'accordion-and-accordion-slider'); ?>:</label>
												</th>
												<td>
													<ul>
														<li><?php _e('Step-1. Create a page like accordion slider OR add the shortcode in any page.', 'accordion-and-accordion-slider'); ?></li>
														
													</ul>
												</td>
											</tr>

											<tr>
												<th>
													<label><?php _e('Shortcodes', 'accordion-and-accordion-slider'); ?>:</label>
												</th>
												<td>
													<span class="wp-aas-shortcode-preview">[aas_slider id="XX"]</span> – <?php _e('Accordion Slider Shortcode.', 'accordion-and-accordion-slider'); ?> <br />
												</td>
											</tr>						
												
											<tr>
												<th>
													<label><?php _e('Need Support?', 'accordion-and-accordion-slider'); ?></label>
												</th>
												<td>
													<p><?php _e('Check plugin demo for designs.', 'accordion-and-accordion-slider'); ?></p> <br/>
													<a class="button button-primary" href="http://demo.wponlinesupport.com/accordion-and-accordion-slider-demo/" target="_blank"><?php _e('Domo Link', 'accordion-and-accordion-slider'); ?></a>	
												</td>
											</tr>
										</tbody>
									</table>
								</div><!-- .inside -->
							</div><!-- #general -->
						</div><!-- .meta-box-sortables ui-sortable -->
					</div><!-- .metabox-holder -->
				</div><!-- #post-body-content -->
				
				<!--Upgrad to Pro HTML -->
				<div id="postbox-container-1" class="postbox-container">
					<div class="metabox-holder wpos-pro-box">
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox" style="">
									
								<h3 class="hndle">
									<span><?php  _e( 'Upgrate to Pro', 'accordion-and-accordion-slider' ); ?></span>
								</h3>
								<div class="inside">										
									<ul class="wpos-list">
										<li>Fancy Box option.</li>
										<li>Added caption field to display image description.</li>
										<li>Drag & Drop images order change</li>										
										<li>Fully responsive</li>										
									</ul>
									<a class="button button-primary wpos-button-full" href="https://www.wponlinesupport.com/wp-plugin/accordion-accordion-slider/" target="_blank"><?php  _e('Go Premium ', 'accordion-and-accordion-slider'); ?></a>	
									<p><a class="button button-primary wpos-button-full" href="http://demo.wponlinesupport.com/prodemo/accordion-and-accordion-slider-pro-demo/" target="_blank"><?php  _e('View PRO Demo ', 'accordion-and-accordion-slider'); ?></a>			</p>								
								</div><!-- .inside -->
							</div><!-- #general -->
						</div><!-- .meta-box-sortables ui-sortable -->
					</div><!-- .metabox-holder -->

					<!-- Help to improve this plugin! -->
					<div class="metabox-holder">
						<div class="meta-box-sortables ui-sortable">
							<div class="postbox">
									<h3 class="hndle">
										<span><?php _e( 'Help to improve this plugin!', 'accordion-and-accordion-slider' ); ?></span>
									</h3>									
									<div class="inside">										
										<p>Enjoyed this plugin? You can help by rate this plugin <a href="https://wordpress.org/support/plugin/accordion-and-accordion-slider/reviews/?filter=5" target="_blank">5 stars!</a></p>
									</div><!-- .inside -->
							</div><!-- #general -->
						</div><!-- .meta-box-sortables ui-sortable -->
					</div><!-- .metabox-holder -->
				</div><!-- #post-container-1 -->

			</div><!-- #post-body -->
		</div><!-- #poststuff -->
	</div><!-- #post-box-container -->
<?php }
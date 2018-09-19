<?php
/*
Plugin Name: Registrations for The Events Calendar
Description: Collect and manage event registrations with a customizable form and email template. This plugin requires The Events Calendar by Modern Tribe to work.
Version: 2.2
Author: Roundup WP
Author URI: roundupwp.com
License: GPLv2 or later
Text Domain: registrations-for-the-events-calendar
*/

/*
Copyright 2017 by Roundup WP LLC

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
/**
* @package RTEC
* @author Roundup WP
* @version 1.0
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

// Check for The Events Calendar to be active
function rtec_TEC_check() {
	if ( ! class_exists( 'Tribe__Events__Main' ) ) {
		add_action( 'admin_notices', 'rtec_no_tec_notice' );
		function rtec_no_tec_notice() {
			?>
			<div class="rtec-notice-all-admin rtec-all-admin-error">
				<div class="rtec-img-wrap">
					<img src="<?php echo plugin_dir_url( __FILE__ ) . 'img/RTEC-Logo-150x150.png'; ?>" alt="Registrations for the Events Calendar">
				</div>
				<div class="rtec-msg-wrap">
					<p><?php _e( 'It looks like The Events Calendar plugin is not currently active.', 'registrations-for-the-events-calendar' ); ?></p>
					<p class="rtec-instructions"><?php _e( 'Please install The Events Calendar by Modern Tribe to get started with your registrations.', 'registrations-for-the-events-calendar' ); ?></p>
					<p><a href="https://roundupwp.com/products/registrations-for-the-events-calendar/setup/" target="_blank"><?php _e( 'Setup Instructions', 'registrations-for-the-events-calendar' ); ?></a></p>
				</div>
			</div>
		<?php
		}
	}
}
add_action( 'plugins_loaded', 'rtec_TEC_check' );

if ( ! class_exists( 'Registrations_For_The_Events_Calendar' ) ) :

    /**
     * Main Registrations_For_The_Events_Calendar Class.
     *
     * Design pattern inspired by Pippin Williamson's Easy Digital Downloads
     *
     * @since 1.0
     */
    final class Registrations_For_The_Events_Calendar {
        /** Singleton *************************************************************/
        /**
         * @var Registrations_For_The_Events_Calendar
         * @since 1.0
         */
        private static $instance;

	    /**
	     * @var Registrations_For_The_Events_Calendar
	     * @since 1.0
	     */
	    public $form;

	    /**
	     * @var Registrations_For_The_Events_Calendar
	     * @since 1.0
	     */
	    public $submission;

	    /**
	     * @var Registrations_For_The_Events_Calendar
	     * @since 1.0
	     */
	    public $db_frontend;

        /**
         * Main Registrations_For_The_Events_Calendar Instance.
         *
         * Only on instance of the form and functions at a time
         *
         * @since 1.0
         * @return object|Registrations_For_The_Events_Calendar
         */
        public static function instance() {
            if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Registrations_For_The_Events_Calendar ) ) {
                self::$instance = new Registrations_For_The_Events_Calendar;
                self::$instance->constants();
                self::$instance->includes();
	            self::$instance->form = new RTEC_Form();
	            self::$instance->db_frontend = new RTEC_Db();
	            if ( isset( $_POST['rtec_email_submission'] ) && '1' === $_POST['rtec_email_submission'] ) {
		            $sanitized_post = array();
		            foreach ( $_POST as $post_key => $raw_post_value ) {
			            $sanitized_post[$post_key] = sanitize_text_field( $raw_post_value );
		            }
		            self::$instance->submission = new RTEC_Submission( $sanitized_post );
		            self::$instance->db_frontend = new RTEC_Db();
	            }
            }
            return self::$instance;
        }

        /**
         * Throw error on object clone.
         *
         * @since 1.0
         * @return void
         */
        public function __clone() {
            // Cloning instances of the class is forbidden.
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'registrations-for-the-events-calendar' ), '1.0' );
        }

        /**
         * Disable unserializing of the class.
         *
         * @since 1.0
         * @access protected
         * @return void
         */
        public function __wakeup() {
            // Unserializing instances of the class is forbidden.
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'registrations-for-the-events-calendar' ), '1.0' );
        }

        /**
         * Setup plugin constants.
         *
         * @access private
         * @since 1.0
         * @return void
         */
        private function constants() {
            // Plugin version.
            if ( ! defined( 'RTEC_VERSION' ) ) {
                define( 'RTEC_VERSION', '2.2' );
            }
            // Plugin Folder Path.
            if ( ! defined( 'RTEC_PLUGIN_DIR' ) ) {
                define( 'RTEC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
            }
	        // Plugin Folder Path.
	        if ( ! defined( 'RTEC_PLUGIN_URL' ) ) {
		        define( 'RTEC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	        }
	        // Plugin Base Name
	        if ( ! defined( 'RTEC_PLUGIN_BASENAME') ) {
		        define( 'RTEC_PLUGIN_BASENAME', plugin_basename(__FILE__) );
	        }
            // Plugin Title.
            if ( ! defined( 'RTEC_TITLE' ) ) {
                define( 'RTEC_TITLE' , 'Registrations for the Events Calendar' );
            }
            // Db version.
            if ( ! defined( 'RTEC_DBVERSION' ) ) {
                define( 'RTEC_DBVERSION' , '1.5' );
            }
            // Table Name.
            if ( ! defined( 'RTEC_TABLENAME' ) ) {
                define( 'RTEC_TABLENAME' , 'rtec_registrations' );
            }
            // Tribe Events Post Type
            if ( ! defined( 'RTEC_TRIBE_EVENTS_POST_TYPE' ) ) {
                define( 'RTEC_TRIBE_EVENTS_POST_TYPE', 'tribe_events' );
            }
            // Tribe Menu Page.
            if ( ! defined( 'RTEC_TRIBE_MENU_PAGE' ) ) {
                define( 'RTEC_TRIBE_MENU_PAGE', 'edit.php?post_type=tribe_events' );
            }
	        if ( ! defined( 'RTEC_ADMIN_URL' ) ) {
		        define( 'RTEC_ADMIN_URL', 'edit.php?post_type=tribe_events&page=registrations-for-the-events-calendar' );
	        }
	        if ( ! defined( 'RTEC_MENU_SLUG' ) ) {
		        define( 'RTEC_MENU_SLUG', 'registrations-for-the-events-calendar' );
	        }
        }

	    /**
	     * Include required files.
	     *
	     * @access private
	     * @since 1.0
	     * @return void
	     */
	    private function includes() {
		    global $rtec_options;
            $rtec_options = get_option( 'rtec_options', array() );
		    require_once RTEC_PLUGIN_DIR . 'inc/class-rtec-db.php';
		    require_once RTEC_PLUGIN_DIR . 'inc/helper-functions.php';
		    require_once RTEC_PLUGIN_DIR . 'inc/form/class-rtec-form.php';
		    require_once RTEC_PLUGIN_DIR . 'inc/form/form-functions.php';
		    require_once RTEC_PLUGIN_DIR . 'inc/class-rtec-submission.php';
		    if ( is_admin() ) {
			    require_once RTEC_PLUGIN_DIR . 'inc/admin/class-rtec-db-admin.php';
			    require_once RTEC_PLUGIN_DIR . 'inc/admin/admin-functions.php';
			    require_once RTEC_PLUGIN_DIR . 'inc/admin/class-rtec-admin.php';
			    require_once RTEC_PLUGIN_DIR . 'inc/admin/class-rtec-admin-registrations.php';
			    require_once RTEC_PLUGIN_DIR . 'inc/admin/class-rtec-admin-event.php';
		    }
	    }

	    /**
	     * Add default settings and create the table in db
	     *
	     * @access public
	     * @since 1.0
	     * @return void
	     */
	    public static function install() {
		    $rtec_options = get_option( 'rtec_options', false );
		    require_once plugin_dir_path( __FILE__ ) . 'inc/class-rtec-db.php';
		    require_once plugin_dir_path( __FILE__ ) . 'inc/admin/class-rtec-db-admin.php';

		    $db           = new RTEC_Db_Admin();
		    $db->create_table();

		    if ( ! $rtec_options ) {
			    $defaults = array(
				    'first_show' => true,
				    'first_require' => true,
				    'last_show' => true,
				    'last_require' => true,
				    'email_show' => true,
				    'email_require' => true,
				    'phone_show' => false,
				    'phone_require' => false,
				    'phone_valid_count' => '7, 10',
				    'recaptcha_require' => false,
				    'other_show' => false,
				    'other_require' => false,
				    'message_source' => 'custom'
			    );
			    // get form options from the db
			    update_option( 'rtec_options', $defaults );
			    // add cues to find the plugin for three days
			    set_transient( 'rtec_new_messages', 'yes', 60 * 60 * 24 * 3 );
		    }

	    }

    }
endif; // End if class_exists check.
register_activation_hook( __FILE__, array( 'Registrations_For_The_Events_Calendar', 'install' ) );

function rtec_text_domain() {
	load_plugin_textdomain( 'registrations-for-the-events-calendar', false, basename( dirname(__FILE__) ) . '/lang' );
}
add_action( 'plugins_loaded', 'rtec_text_domain' );

/**
 * The main function for Registrations_For_The_Events_Calendar
 *
 * The main function responsible for returning the one true Registrations_For_The_Events_Calendar
 * Instance to functions everywhere.
 *
 * @since 1.0
 * @return object|Registrations_For_The_Events_Calendar The one true Registrations_For_The_Events_Calendar Instance.
 */
function RTEC() {
	return Registrations_For_The_Events_Calendar::instance();
}
// Get rtec Running.
RTEC();

<?php
/**
 * Plugin Name: PLVR Woocommerce Products Slider
 * Plugin URI:  http://pluginever.com
 * Description: The best WordPress plugin ever made!
 * Version:     1.0.0
 * Author:      PluginEver
 * Author URI:  http://pluginever.com
 * Donate link: http://pluginever.com
 * License:     GPLv2+
 * Text Domain: plvrwps
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2018 PluginEver (email : support@pluginever.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * Main initiation class
 *
 * @since 1.0.0
 */
class PLVR_WC_Products_Slider {

    /**
     * Add-on Version
     *
     * @since 1.0.0
     * @var  string
     */
	public $version = '1.0.0';

	/**
     * Minimum PHP version required
     *
     * @var string
     */
	private $min_php = '5.4.0';


	/**
	 * Constructor for the class
	 *
	 * Sets up all the appropriate hooks and actions
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		// dry check on older PHP versions, if found deactivate itself with an error
		register_activation_hook( __FILE__, array( $this, 'auto_deactivate' ) );

		if ( ! $this->is_supported_php() ) {
			return;
		}

		// Define constants
		$this->define_constants();

		// Include required files
		$this->includes();

		// instantiate classes
		$this->instantiate();

		// Initialize the action hooks
		$this->init_hooks();

	}

	/**
	 * Initializes the class
	 *
	 * Checks for an existing instance
	 * and if it does't find one, creates it.
	 *
	 * @since 1.0.0
	 *
	 * @return object Class instance
	 */
	public static function init() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Define constants
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function define_constants() {
		define( 'PLVR_WPS_VERSION', $this->version );
		define( 'PLVR_WPS_FILE', __FILE__ );
		define( 'PLVR_WPS_PATH', dirname( PLVR_WPS_FILE ) );
		define( 'PLVR_WPS_INCLUDES', PLVR_WPS_PATH . '/includes' );
		define( 'PLVR_WPS_URL', plugins_url( '', PLVR_WPS_FILE ) );
		define( 'PLVR_WPS_ASSETS', PLVR_WPS_URL . '/assets' );
		define( 'PLVR_WPS_VIEWS', PLVR_WPS_PATH . '/views' );
		define( 'PLVR_WPS_TEMPLATES_DIR', PLVR_WPS_PATH . '/templates' );
	}

	/**
	 * Include required files
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function includes( ) {
		require PLVR_WPS_INCLUDES .'/functions.php';
		require PLVR_WPS_INCLUDES .'/class-cpt.php';
	}

	 /**
     * Do plugin upgrades
     *
     * @since 1.0.0
     *
     * @return void
     */
    function plugin_upgrades() {

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        require_once PLVR_WPS_INCLUDES . '/class-upgrades.php';

        $upgrader = new PLVR_WC_Products_Slider_Upgrades();

        if ( $upgrader->needs_update() ) {
            $upgrader->perform_updates();
        }
    }

	/**
	 * Init Hooks
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function init_hooks() {
		// Localize our plugin
		add_action( 'init', [ $this, 'localization_setup' ] );

		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
	}

	/**
	 * Initialize plugin for localization
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function localization_setup() {
		load_plugin_textdomain( 'plvrwps', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Instantiate classes
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function instantiate() {

	}

	/**
     * Plugin action links
     *
     * @param  array $links
     *
     * @return array
     */
    function plugin_action_links( $links ) {

        //$links[] = '<a href="' . admin_url( 'admin.php?page=' ) . '">' . __( 'Settings', '' ) . '</a>';

        return $links;
    }


	/**
     * Check if the PHP version is supported
     *
     * @return bool
     */
    public function is_supported_php( $min_php = null ) {

        $min_php = $min_php ? $min_php : $this->min_php;

        if ( version_compare( PHP_VERSION, $min_php , '<=' ) ) {
            return false;
        }

        return true;
    }

	/**
     * Show notice about PHP version
     *
     * @return void
     */
    function php_version_notice() {

        if ( $this->is_supported_php() || ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $error = __( 'Your installed PHP Version is: ', 'plvrwps' ) . PHP_VERSION . '. ';
        $error .= __( 'The <strong>PLVR Woocommerce Products Slider</strong> plugin requires PHP version <strong>', 'plvrwps' ) . $this->min_php . __( '</strong> or greater.', 'plvrwps' );
        ?>
        <div class="error">
            <p><?php printf( $error ); ?></p>
        </div>
        <?php
    }

    /**
     * Bail out if the php version is lower than
     *
     * @return void
     */
    function auto_deactivate() {
        if ( $this->is_supported_php() ) {
            return;
        }

        deactivate_plugins( plugin_basename( __FILE__ ) );

        $error = __( '<h1>An Error Occured</h1>', 'plvrwps' );
        $error .= __( '<h2>Your installed PHP Version is: ', 'plvrwps' ) . PHP_VERSION . '</h2>';
        $error .= __( '<p>The <strong>PLVR Woocommerce Products Slider</strong> plugin requires PHP version <strong>', 'plvrwps' ) . $this->min_php . __( '</strong> or greater', 'plvrwps' );
        $error .= __( '<p>The version of your PHP is ', 'plvrwps' ) . '<a href="http://php.net/supported-versions.php" target="_blank"><strong>' . __( 'unsupported and old', 'plvrwps' ) . '</strong></a>.';
        $error .= __( 'You should update your PHP software or contact your host regarding this matter.</p>', 'plvrwps' );

        wp_die( $error, __( 'Plugin Activation Error', 'plvrwps' ), array( 'back_link' => true ) );
    }

	/**
	 * Logger for the plugin
	 *
	 * @since	1.0.0
	 *
	 * @param  $message
	 *
	 * @return  string
	 */
	public static function log($message){
		if( WP_DEBUG !== true ) return;
		if (is_array($message) || is_object($message)) {
			$message = print_r($message, true);
		}
		$debug_file = WP_CONTENT_DIR . '/custom-debug.log';
		if (!file_exists($debug_file)) {
			@touch($debug_file);
		}
		return error_log(date("Y-m-d\tH:i:s") . "\t\t" . strip_tags($message) . "\n", 3, $debug_file);
	}

}

/**
 * Initialize the plugin
 *
 * @return object
 */
function plvr_wc_products_slider() {
    return PLVR_WC_Products_Slider::init();
}

// kick-off
plvr_wc_products_slider();

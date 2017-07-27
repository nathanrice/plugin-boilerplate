<?php
/*
Plugin Name: Plugin Boilerplate
Plugin URI: https://github.com/copyblogger/plugin-boilerplate
Description: A simple boilerplate for new WordPress plugins.
Author: Nathan Rice
Author URI: http://nathanrice.net/

Version: 0.9.0

Text Domain: plugin-boilerplate
Domain Path: /languages

License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/**
 * The main class.
 *
 * @since 0.9.0
 */
final class Plugin_Boilerplate {

	/**
	 * Plugin version
	 */
	public $plugin_version = '0.9.0';


	/**
	 * Minimum WordPress version.
	 */
	public $min_wp_version = '4.8';

	/**
	 * Minimum Genesis version.
	 */
	public $min_genesis_version = '2.5';

	/**
	 * The plugin textdomain, for translations.
	 */
	public $plugin_textdomain = 'plugin-boilerplate';

	/**
	 * The url to the plugin directory.
	 */
	public $plugin_dir_url;

	/**
	 * The path to the plugin directory.
	 */
	public $plugin_dir_path;

	/**
	 * Boilerplate feature object.
	 */
	public $plugin_boilerplate_feature;

	/**
	 * Boilerplate AJAX object.
	 */
	public $plugin_boilerplate_ajax;

	/**
	 * Constructor.
	 *
	 * @since 0.9.0
	 */
	public function __construct() {

		$this->plugin_dir_url  = plugin_dir_url( __FILE__ );
		$this->plugin_dir_path = plugin_dir_path( __FILE__ );

	}

	/**
	 * Initialize.
	 *
	 * @since 0.9.0
	 */
	public function init() {

		//register_activation_hook( $this->plugin_dir_path . 'plugin.php', array( $this, 'activation' ) );
		//add_action( 'admin_notices', array( $this, 'requirements_notice' ) );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		/**
		 * Plugin or theme depencencies should be hooked to actions available in
		 * the theme or plugin which they depend on.
		 *
		 * Otherwise, you can call the includes/instantiate methods directly.
		 */
		$this->includes();
		//add_action( 'genesis_setup', array( $this, 'includes' ) );
		$this->instantiate();
		//add_action( 'genesis_setup', array( $this, 'instantiate' ) );

	}

	/**
	 * Plugin activation hook. Runs when plugin is activated.
	 *
	 * @since 0.9.0
	 */
	public function activation() {}


	/**
	 * Show admin notice if minimum requirements aren't met.
	 *
	 * @since 0.9.0
	 */
	public function requirements_notice() {

		if ( ! defined( 'PARENT_THEME_VERSION' ) || ! version_compare( PARENT_THEME_VERSION, $this->min_genesis_version, '>=' ) ) {

			$action = defined( 'PARENT_THEME_VERSION' ) ? __( 'upgrade to', 'plugin-boilerplate' ) : __( 'install and activate', 'plugin-boilerplate' );

			$message = sprintf( __( 'This plugin requires WordPress %s and <a href="%s" target="_blank">Genesis %s</a>, or greater. Please %s the latest version of Genesis to use this plugin.', 'plugin-boilerplate' ), $this->min_wp_version, 'http://my.studiopress.com/?download_id=91046d629e74d525b3f2978e404e7ffa', $this->min_genesis_version, $action );
			echo '<div class="notice notice-warning"><p>' . $message . '</p></div>';

		}

	}

	/**
	 * Load the plugin textdomain, for translation.
	 *
	 * @since 0.9.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( $this->plugin_textdomain, false, dirname( plugin_basename( __FILE__ ) ) . 'languages/' );
	}

	/**
	 * Use this method to to general includes such as functions files or classes that won't be instantiated.
	 *
	 * @since 0.9.0
	 */
	public function includes() {

		require_once( $this->plugin_dir_path . 'includes/functions.php' );

	}

	/**
	 * Include the class file, instantiate the classes, create objects.
	 *
	 * @since 0.9.0
	 */
	public function instantiate() {

		/**
		 * For each feature, or naturally related groups of features, create a class file that contains a single class.
		 *
		 * Use this section to create the object after including the class file.
		 */
		require_once( $this->plugin_dir_path . 'includes/class-plugin-boilerplate-feature.php' );
		$this->plugin_boilerplate_feature = new Plugin_Boilerplate_Feature;

		/**
		 * If you need to an a WP-CLI command, do it this way.
		 */
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			require_once( $this->plugin_dir_path . 'includes/class-plugin-boilerplate-cli-command.php' );
			WP_CLI::add_command( 'plugin-boilerplate', 'Plugin_Boilerplate_CLI_Command' );
		}

	}

}

/**
 * Helper function to retrieve the static object without using globals.
 *
 * @since 0.9.0
 */
function Plugin_Boilerplate() {

	static $object;

	if ( null == $object ) {
		$object = new Plugin_Boilerplate;
	}

	return $object;

}

/**
 * Initialize the object on	`plugins_loaded`.
 */
add_action( 'plugins_loaded', array( Plugin_Boilerplate(), 'init' ) );

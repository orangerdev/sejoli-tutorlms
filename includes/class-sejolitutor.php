<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://ridwan-arifandi.com
 * @since      1.0.0
 *
 * @package    Sejolitutor
 * @subpackage Sejolitutor/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Sejolitutor
 * @subpackage Sejolitutor/includes
 * @author     Ridwan Arifandi <orangerdigiart@gmail.com>
 */
class Sejolitutor {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Sejolitutor_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SEJOLITUTOR_VERSION' ) ) {
			$this->version = SEJOLITUTOR_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'sejolitutor';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Sejolitutor_Loader. Orchestrates the hooks of the plugin.
	 * - Sejolitutor_i18n. Defines internationalization functionality.
	 * - Sejolitutor_Admin. Defines all hooks for the admin area.
	 * - Sejolitutor_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sejolitutor-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sejolitutor-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sejolitutor-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sejolitutor-order.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sejolitutor-product.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-sejolitutor-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-sejolitutor-course.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-sejolitutor-member.php';

		/**
		 * Routine functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'functions/course.php';

		$this->loader = new Sejolitutor_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Sejolitutor_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Sejolitutor_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$admin = new SejoliTutor\Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'plugins_loaded',	$admin, 'check_needed_plugins', 999);
		$this->loader->add_action( 'admin_notices',		$admin, 'display_notice_if_sejoli_not_activated',   10);
		$this->loader->add_action( 'admin_notices',		$admin, 'display_notice_if_tutorlms_not_activated', 10);

		$product = new SejoliTutor\Admin\Product( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_filter( 'sejoli/product/fields', 	$product, 'set_product_fields', 11);
		$this->loader->add_filter( 'sejoli/product/meta-data',	$product, 'set_product_metadata', 100, 2);

		$order 	= new SejoliTutor\Admin\Order( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_filter( 'sejoli/order/meta-data', 					$order, 'set_order_metadata', 		100, 2);
		$this->loader->add_filter( 'sejoli/order/set-status/completed',			$order, 'create_tutorlms_order',  	200);
		$this->loader->add_filter( 'sejoli/order/set-status/on-hold',			$order, 'cancel_tutorlms_order',  	200);
		$this->loader->add_filter( 'sejoli/order/set-status/cancelled',			$order, 'cancel_tutorlms_order',	200);
		$this->loader->add_filter( 'sejoli/order/set-status/refunded',			$order, 'cancel_tutorlms_order',	200);
		$this->loader->add_filter( 'sejoli/order/set-status/in-progress',		$order, 'cancel_tutorlms_order',	200);
		$this->loader->add_filter( 'sejoli/order/set-status/shipped',			$order, 'cancel_tutorlms_order',	200);

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$public = new SejoliTutor\Front( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts',		$public, 'enqueue_scripts', 194);

		$course = new SejoliTutor\Front\Course( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_filter( 'is_course_purchasable',		$course, 'check_if_course_is_purchasable', 999, 2);
		$this->loader->add_filter( 'tutor_course_sell_by', 		$course, 'check_course_sell_by', 	   	   999);
		$this->loader->add_filter( 'tutor_get_template_path',	$course, 'set_template_path', 		   	   999, 2);

		$member 	= new SejoliTutor\Front\Member( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_filter( 'sejoli/member-area/menu',			$member, 'add_course_menu', 999);
		$this->loader->add_filter( 'sejoli/member-area/menu-url',		$member, 'modify_member_area_url',	1, 2);
		$this->loader->add_filter( 'sejoli/member-area/backend/menu',	$member, 'add_course_menu_in_backend', 999);
		$this->loader->add_filter( 'sejoli/template-file',				$member, 'set_template_file', 999, 2);

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Sejolitutor_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

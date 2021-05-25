<?php

namespace SejoliTutor;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ridwan-arifandi.com
 * @since      1.0.0
 *
 * @package    Sejolitutor
 * @subpackage Sejolitutor/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sejolitutor
 * @subpackage Sejolitutor/admin
 * @author     Ridwan Arifandi <orangerdigiart@gmail.com>
 */
class Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $is_sejoli_active = true;

	private $is_tutorlms_active = true;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Check if needed plugins are active
	 * @uses 	plugins_loaded, priority 999
	 * @since 	1.0.0
	 * @return 	void
	 */
	public function check_needed_plugins() {

		if( !function_exists('tutor') ) :
			$this->is_tutorlms_active = false;
		endif;

		if( !defined('SEJOLISA_VERSION') ) :
			$this->is_sejoli_active = false;
		endif;
	}

	/**
	 * Display notice if tutor LMS not activated
	 * @uses 	admin_notices, (action), 10
	 * @since 	1.0.0
	 * @return 	void
	 */
	public function display_notice_if_tutorlms_not_activated() {

		if(  false === $this->is_tutorlms_active ) :

	    	?><div class='notice notice-error'>
	    		<p><?php _e('Anda belum menginstall atau mengaktifkan Tutor LMS terlebih dahulu.', 'sejolp'); ?></p>
			</div><?php

	    endif;

	}

	/**
	 * Display notice if sejoli not activated
	 * @uses 	admin_notices, (action), 10
	 * @since 	1.0.0
	 * @return 	void
	 */
	public function display_notice_if_sejoli_not_activated() {

		if( false === $this->is_sejoli_active ) :

	    	?><div class='notice notice-error'>
	    		<p><?php _e('Anda belum menginstall atau mengaktifkan Sejoli terlebih dahulu.', 'sejolp'); ?></p>
			</div><?php

	    endif;

	}

}

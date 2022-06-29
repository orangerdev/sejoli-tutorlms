<?php

namespace SejoliTutor\Front;

/**
 * The course public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sejolitutor
 * @subpackage Sejolitutor/public
 * @author     Ridwan Arifandi <orangerdigiart@gmail.com>
 */
class Course {

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

    /**
     * Course is purchasable
     * @since   1.0.0
     * @var     boolean
     */
    protected $is_purchasable = null;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

    /**
     * Check if course is purchasable
     * @uses        is_course_purchasable, filter, 999
     * @since       1.0.0
     * @param       bool        $is_purchaseble
     * @param       integer     $course_id
     * @return      boolean
     */
    public function check_if_course_is_purchasable( bool $is_purchaseble, $course_id ) {

        $related_products = sejolitutor_get_products( $course_id );

        if( is_array( $related_products ) && 0 < count( $related_products ) ) :

            $this->is_purchasable = true;

            return true;

        else :

            $this->is_purchasable = false;

            return false;

        endif;

        return $is_purchasable;

    }

    /**
     * Check course sell_by module
     * @uses    tutor_course_sell_by, (filter), priority 999
     * @param   string     $sell_by
     * @return  string
     */
    public function check_course_sell_by( $sell_by ) {

        if( $this->is_purchasable ) :
            return 'sejoli';
        endif;

        return $sell_by;

    }

    public function set_template_path( $template_location, $template ) {
        
        if( 'single\course\add-to-cart' === str_replace("/", "\\", $template) ) :
            return SEJOLITUTOR_DIR . 'template/' . $template . '.php';
        endif;

        if( 'single\course\course-entry-box' === str_replace("/", "\\", $template) ) :
            return SEJOLITUTOR_DIR . 'template/' . $template . '.php';
        endif;

        return $template_location;

    }

}

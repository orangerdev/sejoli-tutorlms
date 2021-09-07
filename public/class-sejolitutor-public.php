<?php

namespace SejoliTutor;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ridwan-arifandi.com
 * @since      1.0.0
 *
 * @package    Sejolitutor
 * @subpackage Sejolitutor/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sejolitutor
 * @subpackage Sejolitutor/public
 * @author     Ridwan Arifandi <orangerdigiart@gmail.com>
 */
class Front {

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
	 * Enable semantic theme
	 * @since 	1.0.0
	 * @var 	boolean
	 */
	protected $enable_semantic = true;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
     * Remove default tutor lms hooks that related to checkout actions
     * Hooked via plugins_loaded, priority 1
     * @since   1.0.0
     * @return  void
     */
    public function remove_unneeded_hooks() {

        add_filter('tutor_course_price', array($this, 'remove_price'));

    } 

    /**
     * @param $html
     * @return string
     *
     * Removed course price at single course
     *
     * @since 1.0.0
     */
	public function remove_price($html){
	
	    $should_removed = apply_filters('should_remove_price_if_enrolled', true);

	    if ($should_removed){
        
	        $html = '';

        }
	    
	    return $html;
    
    }

	/**
	 * Enqueue needed CSS and JS files
	 * @uses 	wp_enqueue_scripts, action, 194
	 * @since 	1.0.0
	 * @return 	void
	 */
	public function enqueue_scripts() {

		if(is_singular(TLMS_COURSE_CPT)) :
		
			wp_enqueue_style( 'sejoli-tutor', SEJOLITUTOR_URL . 'public/css/sejolitutor-public.css', $this->version, 'all');

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sejolitutor-public.js', array( 'jquery' ), $this->version, false );
		
		endif;

	}


	/**
	 * Check if current page is using sejoli-member-page.php
	 * Hooked via filter template_include, priority 10
	 * @since 	1.0.0
	 * @since 	1.0.0 	Change priority from 1 to 10
	 * @param  	string	$template	Template file
	 * @return 	string
	 */
	public function view_member_template($template) {

		global $post, $wp_query;

		// Return template if post is empty
		if ( ! $post ) :
			return $template;
		endif;
		
		$get_page_template = get_page_template_slug();

		// Return default template if we don't have a custom one defined
		if(false !== $this->enable_semantic) :

			if($wp_query->query_vars['post_type'] == 'courses' && $get_page_template == 'sejoli-member-page.php') :
				$template = plugin_dir_path( dirname( __FILE__ ) ) . 'template/single-course-template.php';
			elseif($wp_query->query_vars['post_type'] == 'courses') :
				return $template;
			else:
				return $template;
			endif;

			return $template;

		endif;

		return $template;
	}

	/**
	 * Enable semantic
	 * Hooked via filter sejoli/enable, priority 100
	 * @since 	1.1.7
	 * @return 	boolean
	 */
	public function enable_semantic($enable_semantic) {
		return (true === $enable_semantic) ? true : $this->enable_semantic;
	}

}

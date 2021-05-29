<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://ridwan-arifandi.com
 * @since             1.0.0
 * @package           Sejolitutor
 *
 * @wordpress-plugin
 * Plugin Name:       Sejoli - Tutor LMS
 * Plugin URI:        https://sejoli.co.id
 * Description:       Integrates SEJOLI premium membership WordPress plugin with Tutor LMS ( an LMS WordPress plugin )
 * Version:           1.0.0
 * Author:            Ridwan Arifandi
 * Author URI:        https://ridwan-arifandi.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sejolitutor
 * Domain Path:       /languages
 */

 global $sejolitutor;

 $sejolitutor = array(
     'course'   => null
 );

 // If this file is called directly, abort.
 if ( ! defined( 'WPINC' ) ) :
 	die;
endif;

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SEJOLITUTOR_VERSION',      '1.0.0' );
define( 'SEJOLITUTOR_DIR',	        plugin_dir_path(__FILE__));
define( 'SEJOLITUTOR_URL',	        plugin_dir_url(__FILE__));
define( 'TLMS_COURSE_CPT',          'courses');
define( 'TLMS_COURSE_ENROLLED_CPT', 'tutor_enrolled');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sejolitutor-activator.php
 */
function activate_sejolitutor() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sejolitutor-activator.php';
	Sejolitutor_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sejolitutor-deactivator.php
 */
function deactivate_sejolitutor() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-sejolitutor-deactivator.php';
	Sejolitutor_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sejolitutor' );
register_deactivation_hook( __FILE__, 'deactivate_sejolitutor' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'third-parties/autoload.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-sejolitutor.php';

/**
 * Create tutor_pro function
 * @since   1.0.0
 */
if( !function_exists('tutor_pro') ) :

    function tutor_pro() {

        $info = array(
            'path' => SEJOLITUTOR_DIR
        );

        return (object) $info;
    }
endif;

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sejolitutor() {

	$plugin = new Sejolitutor();
	$plugin->run();

}

require_once(SEJOLITUTOR_DIR . 'third-parties/yahnis-elsts/plugin-update-checker/plugin-update-checker.php');

$update_checker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/orangerdev/sejoli-tutorlms',
	__FILE__,
	'sejoli-tutorlms'
);

$update_checker->setBranch('master');

run_sejolitutor();

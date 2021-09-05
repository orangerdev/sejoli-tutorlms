<?php
namespace SejoliTutor\Admin;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ridwan-arifandi.com
 * @since      1.0.0
 *
 * @package    SejoliLP
 * @subpackage SejoliLP/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    SejoliLP
 * @subpackage SejoliLP/admin
 * @author     Ridwan Arifandi <orangerdigiart@gmail.com>
 */
class Order {

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
     * Set learnpress metadata to order
     * Hooked via filter sejoli/order/meta-data, priority 100
     * @since   1.0.0
     * @param   array   $metadata   [description]
     * @param   array   $order_data [description]
     * @return  array
     */
    public function set_order_metadata(array $metadata, array $order_data) {

        $product = sejolisa_get_product($order_data['product_id']);

        if(property_exists($product, 'tutorlms') && is_array($product->tutorlms)) :
            $metadata['tutorlms'] = $product->tutorlms;
        endif;

        return $metadata;
    }

    /**
     * Set learnpress buyer order ID
     * Hooked via filter learn-press/checkcout/default-user, priority
     * @since   1.0.0
     * @param   integer $buyer_id
     */
    public function set_buyer_id( $buyer_id ) {

        if(0 !== $this->buyer_id) :
            return $this->buyer_id;
        endif;

        return $buyer_id;
    }

	/**
	 * Update enroll status
	 * @since 	1.0.0
	 * @param  	integer $course_id
	 * @param  	integer $enrolled_id
	 * @param  	integer $user_id
	 * @return 	void
	 */
	protected function update_enroll_complete( $course_id, $enrolled_id, $user_id ) {

		wp_update_post(array(
			'ID'          => $enrolled_id,
			'post_status' => 'completed'
		));

		do_action('tutor_after_enrolled', $course_id, $user_id, $enrolled_id);
	}

    /**
     * Create Tutor LMS order when sejoli order completed
     * Hooked via sejoli/order/set-status/completed, prioirty 200
     * @since   1.0.0
     * @param   array  $order_data
     * @return  void
     */
    public function create_tutorlms_order(array $order_data) {

        if(
            isset($order_data['meta_data']['tutorlms']) &&
            !isset($order_data['meta_data']['tutorlms_order'])
        ) :

            $user_id  = $this->buyer_id = $order_data['user_id'];
            $order_id = $order_data['ID'];
            $courses  = $order_data['meta_data']['tutorlms'];

			$order_data['meta_data']['tutorlms_order'] = array();

            foreach( (array) $courses as $course_id) :

				tutor_utils()->do_enroll($course_id, $order_id, $user_id);

				$enrolled_ids = tutor_utils()->get_course_enrolled_ids_by_order_id($order_data['ID']);
				$enrolled_id  =  $enrolled_ids[0]['enrolled_id'];

				$this->update_enroll_complete(
					$course_id,
					$enrolled_id,
					$user_id
				);

				do_action(
					'sejoli/log/write',
					'tutorlms-enroll-courses',
					sprintf(
						__('Set course ID %s from order ID %s user ID %s', 'sejolitutor'),
						$course_id, $order_data['ID'], $user_id
					)
				);

				update_post_meta( $enrolled_id, '_tutor_enrolled_by_order_id', 	 $order_data['ID'] );
				update_post_meta( $enrolled_id, '_tutor_enrolled_by_product_id', $order_data['product_id'] );

				$order_data['meta_data']['tutorlms_order'][$course_id] = $enrolled_id;

            endforeach;

            sejolisa_update_order_meta_data($order_data['ID'], $order_data['meta_data']);

		// Enrolled has been set, so we need to reset again
		elseif(isset($order_data['meta_data']['tutorlms_order'])) :

			$user_id  = $this->buyer_id = $order_data['user_id'];
			$order_id = $order_data['ID'];

			foreach($order_data['meta_data']['tutorlms_order'] as $course_id => $enrolled_id) :

				tutor_utils()->do_enroll($course_id, $order_id, $user_id);

				$enrolled_ids = tutor_utils()->get_course_enrolled_ids_by_order_id($order_data['ID']);
				$enrolled_id  =  $enrolled_ids[0]['enrolled_id'];

				$this->update_enroll_complete(
					$course_id,
					$enrolled_id,
					$user_id
				);

				do_action(
					'sejoli/log/write',
					'tutorlms-re-enroll-courses',
					sprintf(
						__('Reset course ID %s from order ID %s user ID %s', 'sejolitutor'),
						$course_id, $order_data['ID'], $user_id
					)
				);

			endforeach;

        endif;
    }

    /**
     * Cancel learnpress order
     * @since   1.0.0
     * @param   array  $order_data [description]
     * @return  void
     */
    public function cancel_tutorlms_order(array $order_data) {

        if(isset($order_data['meta_data']['tutorlms_order'])) :

			$user_id = $this->buyer_id = $order_data['user_id'];

			foreach($order_data['meta_data']['tutorlms_order'] as $course_id => $enrolled_id) :

				tutor_utils()->cancel_course_enrol( $course_id, $user_id, 'pending');

				do_action(
					'sejoli/log/write',
					'tutorlms-cancel-enroll-courses',
					sprintf(
						__('Cancel course ID %s from order ID %s user ID %s', 'sejolitutor'),
						$course_id, $order_data['ID'], $user_id
					)
				);

			endforeach;

        endif;

    }

}

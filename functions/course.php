<?php
/**
 * Get products that related to learnpress course
 * @since   1.0.0
 * @since   1.0.3           Add conditional check to product post status
 * @param   integer         $check_course_id    (Optional) ID of course
 * @return  array|false     Will return false if there is no product for given course id or no related product
 */
function sejolitutor_get_products($check_course_id = 0) {

    global $wpdb, $sejolitutor;

    if( !is_array($sejolitutor['course'])) :

        $data    = array();
        $results = $wpdb->get_results(
                    "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key LIKE '_tutorlms_course|||_|id'"
                   );

        foreach( (array) $results as $row ) :

            $product_id = (int) $row->post_id;
            $course_id  = (int) $row->meta_value;
            $product    = get_post( $product_id );

            if( !isset($data[$course_id]) && 'publish' === $product->post_status ) :
                $data[$course_id] = $product_id;
            endif;

        endforeach;

        $sejolitutor['course'] = $data;

    else :

        $data = $sejolitutor['course'];

    endif;

    // check if there is related product to giver course ID
    if( 0 < $check_course_id ) :
        return ( !isset($data[$check_course_id]) ) ? false : $data[$check_course_id];
    endif;

    // return false if there is no related product
    if( 0 === count($data) ) :
        return false;
    endif;

    return $data;
    
}

/**
 * Get enrolled course by course_id and user_id
 * @since   1.0.0
 * @param   integer         $course_id
 * @param   integer         $user_id
 * @param   integer         $order_id   (optional)
 * @return  integer|false   return with tutor_enrolled post ID
 */
function sejolitutor_get_enrolled_course_by_user( $course_id = 0, $user_id = 0, $order_id = 0 ) {

    if( empty($user_id) ) :
        $user_id = get_current_user_id();
    endif;

    $course_ids = tutor_utils()->get_enrolled_courses_ids_by_user( $user_id );
    $posts = new \WP_Query(array(
        'post_type'              => TLMS_COURSE_ENROLLED_CPT,
        'post__in'               => $course_id,
        'post_author'            => $user_id,
        'post_status'            => array('publish', 'completed'),
        'posts_per_page'         => 300,
        'fields'                 => 'ids',
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false
    ));

    if( 0 < count($posts->posts) ) :
        return $posts->posts;
    endif;

    return false;

}

/**
 * Get all enrolled course by user_id
 * @since   1.0.0
 * @param   integer         $user_id
 * @param   integer         $order_id   (optional)
 * @return  integer|false   return with tutor_enrolled post ID
 */
function sejolitutor_get_all_enrolled_courses_by_user( $user_id = 0 ) {

    if( empty($user_id) ) :
        $user_id = get_current_user_id();
    endif;


    $course_ids = tutor_utils()->get_enrolled_courses_ids_by_user( $user_id );
    error_log(print_r($course_ids, true));
    $posts = new \WP_Query(array(
        'post_type'              => TLMS_COURSE_CPT,
        'post_author'            => $user_id,
        'post_status'            => array('publish', 'completed'),
        'post__in'               => $course_ids,
        'posts_per_page'         => 300,
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false
    ));

    if( 0 < count($posts->posts) && !empty($course_ids) ) :
        return $posts->posts;
    endif;

    return false;

}

/**
 * Get all available course
 * @since   1.0.0
 * @return  array|false
 */
function sejolitutor_get_available_courses() {

    $query = new \WP_Query(array(
        'post_type'              => TLMS_COURSE_CPT,
        'posts_per_page'         => 300,
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false
    ));

    if( isset($query->posts) && 0 < count($query->posts)) :
        return $query->posts;
    endif;

    return false;

}

<?php sejoli_header(); ?>
<h2 class="ui header"><?php _e('Kelas Anda', 'sejolitutor'); ?></h2>
<?php
    $user_courses = sejolitutor_get_all_enrolled_courses_by_user();
?>
<div class="ui three column doubling stackable cards item-holder masonry grid">
<?php
foreach( (array) $user_courses as $user_course ) :

    $course = $user_course;
    
    setup_postdata($course);

    if(!empty($course)) {
    	include( plugin_dir_path( __FILE__ ) . 'course-card.php' );
    } else {
    	echo '<div class="column">';
    	echo esc_attr__('You Have No Enrolled Course!', 'sejoli-tutorlms');
    	echo '</div>';
    }

endforeach;
?>
</div>
<?php
    wp_reset_query();
    sejoli_footer();
?>
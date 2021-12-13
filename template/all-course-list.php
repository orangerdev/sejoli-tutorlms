<?php sejoli_header(); ?>
<h2 class="ui header"><?php _e('Semua Kelas', 'sejolitutor'); ?></h2>
<?php
	$course_ids   = sejolitutor_get_available_courses();
?>
<div class="ui three column doubling stackable cards item-holder masonry grid">
<?php
foreach( (array) $course_ids as $course_id ) :
	
	$course = $course_id;
    
    setup_postdata($course);

     if(!empty($course)) {
    	include( plugin_dir_path( __FILE__ ) . 'course-card.php' );
    } else {
    	echo '<div class="column">';
    	echo esc_attr__('You Have No Course!', 'sejoli-tutorlms');
    	echo '</div>';
    }

endforeach;
?>
</div>
<?php
	wp_reset_query();
	sejoli_footer();
?>
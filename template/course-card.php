<div class="column">
    <div class="ui fluid card">
        <div class="image">
            <?php echo get_the_post_thumbnail( $course->ID, 'full', array( 'class' => 'thumbnail' ) ); ?>
        </div>
        <div class="content">
            <span class='left floated'>
                <a href='#' class='section'>
                    <i class='bookmark icon'></i>
                    <?php
                        $course_level = get_post_meta($course->ID, '_tutor_course_level', true);
                        echo tutor_utils()->course_levels($course_level);
                    ?>
                </a>
            </span>
            <span class='right floated'>
                <a href='#' class='lesson'>
                    <i class='pencil icon'></i>
                    <?php
                    printf(
                        _n(
                            '%s ',
                            '%s materi',
                            tutor_utils()->get_lesson_count_by_course($course->ID),
                            'sejolitutor'
                        ),
                        tutor_utils()->get_lesson_count_by_course($course->ID)
                    );
                    ?>
                </a>
            </span>
        </div>
        <div class="content">
            <h3 class='header'><?php echo $course->post_title; ?></h3>
            <div class="description">
                <?php echo $course->post_excerpt; ?>
            </div>
        </div>
        <div class="extra content">
            <a href='#'>
                <i class="users icon"></i>
                <?php
                printf(
                    _n(
                        '%s peserta',
                        '%s peserta',
                        tutor_utils()->count_enrolled_users_by_course($course->ID),
                        'sejolitutor'
                    ),
                    tutor_utils()->count_enrolled_users_by_course($course->ID)
                );
                ?>
            </a>
        </div>
        <a href='<?php echo get_permalink($course->ID); ?>' class="ui bottom attached button">
            <?php _e('Lihat Kelas', 'sejolitutor'); ?>
        </a>
    </div>
</div>

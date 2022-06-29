<div class="tutor-course-purchase-box">
<?php
$products = sejolitutor_get_products(get_the_ID());

if($products) :
    $get_product = sejolisa_get_product($products);
?>
    <div class="tutor-course-sidebar-card-pricing tutor-d-flex tutor-align-end tutor-justify-between">
        <div class="price">
            <?php echo sejolisa_price_format($get_product->price); ?>
        </div>
    </div>
    <a href="<?php echo get_permalink($products); ?>" target="new" class="tutor-btn tutor-btn-icon tutor-btn-primary tutor-btn-lg tutor-btn-block tutor-mt-24 tutor-add-to-cart-button">
        <span class="btn-icon tutor-icon-cart-filled"></span>
        <span><?php echo __('Buy This Course', 'sejolitutor'); ?></span>
    </a>
<?php 
else:
?>
<div class="tutor-course-single-pricing">
    <div class="price">
        <?php esc_html_e( 'Free', 'sejolitutor' ); ?>
    </div>
</div>

<div class="tutor-course-single-btn-group <?php echo is_user_logged_in() ? '' : 'tutor-course-entry-box-login'; ?>" data-login_url="<?php echo $login_url; ?>">
    <form class="tutor-enrol-course-form" method="post">
        <?php wp_nonce_field( tutor()->nonce_action, tutor()->nonce ); ?>
        <input type="hidden" name="tutor_course_id" value="<?php echo esc_attr( get_the_ID() ); ?>">
        <input type="hidden" name="tutor_course_action" value="_tutor_course_enroll_now">
        <button type="submit" class="tutor-btn tutor-btn-primary tutor-btn-lg tutor-btn-block tutor-mt-24 tutor-enroll-course-button tutor-static-loader">
            <?php esc_html_e( 'Enroll now', 'sejolitutor' ); ?>
        </button>
    </form>
</div>
<br>
<div class="tutor-fs-7 tutor-color-muted tutor-mt-20 tutor-text-center">
    <?php esc_html_e( 'Free access this course', 'sejolitutor' ); ?>
</div>
<?php
endif;
?>
</div>
<?php
$exclude = array();

/**
 * 1 Days
 */
$query = new WP_Query(array(
    'post_type'     => 'booking',
    'post_status'   => 'publish',
    'post__not_in'  => $exclude,
    'meta_query'    => array(
        'relation' => 'AND',
        array(
            'key'   => 'payment_status',
            'value' => 'unpaid',
        ),
        array(
            'key' => 'cancellation_policy_0_from',
            'value' => array( date( 'Ymd', time() ), date( 'Ymd', time() + 1 * 60 * 60 * 24 ) ),
            'compare' => 'BETWEEN',
        )
    )
));

while( $query->have_posts() ) {
    $query->the_post();
    $exclude[] = get_the_ID();
}

/**
 * 2 days
 */
$query = new WP_Query(array(
    'post_type'     => 'booking',
    'post_status'   => 'publish',
    'post__not_in'  => $exclude,
    'meta_query'    => array(
        'relation' => 'AND',
        array(
            'key'   => 'payment_status',
            'value' => 'unpaid',
        ),
        array(
            'key' => 'cancellation_policy_0_from',
            'value' => array( date( 'Ymd', time() ), date( 'Ymd', time() + 2 * 60 * 60 * 24 ) ),
            'compare' => 'BETWEEN',
        )
    )
));

while( $query->have_posts() ) {
    $query->the_post();
    $exclude[] = get_the_ID();
}

/**
 * 3 days
 */
$query = new WP_Query(array(
    'post_type'     => 'booking',
    'post_status'   => 'publish',
    'post__not_in'  => $exclude,
    'meta_query'    => array(
        'relation' => 'AND',
        array(
            'key'   => 'payment_status',
            'value' => 'unpaid',
        ),
        array(
            'key' => 'cancellation_policy_0_from',
            'value' => array( date( 'Ymd', time() ), date( 'Ymd', time() + 3 * 60 * 60 * 24 ) ),
            'compare' => 'BETWEEN',
        )
    )
));

while( $query->have_posts() ) {
    $query->the_post();
    $exclude[] = get_the_ID();
}
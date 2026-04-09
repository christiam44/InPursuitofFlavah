<?php
/*
Plugin Name: Flavah AJAX Search
Description: The official real-time search engine for In Pursuit of Flavah.
Version: 1.0
Author: Ellis Loubon
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// Register the AJAX hooks inside the plugin
add_action('wp_ajax_flavah_live_search', 'flavah_plugin_live_search');
add_action('wp_ajax_nopriv_flavah_live_search', 'flavah_plugin_live_search');

function flavah_plugin_live_search() {
    $keyword = sanitize_text_field($_POST['keyword']);

    $food_query = new WP_Query(array(
        'post_type'      => 'food',
        'posts_per_page' => 5,
        's'              => $keyword
    ));

    $results = array();

    if ($food_query->have_posts()) {
        while ($food_query->have_posts()) {
            $food_query->the_post();
            $results[] = array(
                'title' => get_the_title(),
                'link'  => get_permalink()
            );
        }
        wp_send_json_success($results);
    } else {
        wp_send_json_error('No results.');
    }
    wp_die();
}

// Shortcode to display the search form anywhere
add_shortcode('flavah_search_bar', 'display_flavah_search_bar');
function display_flavah_search_bar() {
    ob_start();
    get_search_form(); // This pulls your searchform.php
    return ob_get_clean();
}
<?php
/**
 * Nook functions and definitions
 */

declare( strict_types = 1 );

if ( ! function_exists( 'nook_support' ) ) :
    function nook_support() {
        add_editor_style( 'style.css' );
        load_theme_textdomain( 'nook' );
        
        // This line enables the Featured Image box in the dashboard
        add_theme_support('post-thumbnails');
    }
endif;
add_action( 'after_setup_theme', 'nook_support' );

if ( ! function_exists( 'nook_styles' ) ) :
    /**
     * Enqueue styles.
     */
    function nook_styles() {
        // Register theme stylesheet.
        wp_register_style(
            'nook-style',
            get_stylesheet_directory_uri() . '/style.css',
            array(),
            wp_get_theme()->get( 'Version' )
        );

        // Enqueue theme stylesheet.
        wp_enqueue_style( 'nook-style' );
    }
endif;
add_action( 'wp_enqueue_scripts', 'nook_styles' );

// updater for WordPress.com themes
if ( is_admin() )
    include dirname( __FILE__ ) . '/inc/updater.php';

// NEW: Enqueue Font Awesome for Icons
function flavah_enqueue_icons() {
    wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
}
add_action('wp_enqueue_scripts', 'flavah_enqueue_icons');
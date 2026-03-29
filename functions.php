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


// Add Full Screen Search Overlay to Footer
function flavah_search_overlay() { ?>
    <div id="search-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 9999; justify-content: center; align-items: center; color: #fff;">
        
        <div id="close-search" style="position: absolute; top: 30px; right: 40px; font-size: 3rem; cursor: pointer; color: #aaa;">&times;</div>
        
        <div style="width: 60%; max-width: 700px; text-align: center;">
            <h2 style="font-family: 'Playfair Display', serif; margin-bottom: 20px; font-size: 2.5rem;">Search In Pursuit of Flavah</h2>
            <?php get_search_form(); ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchTriggers = document.querySelectorAll('.js-search-trigger');
            const overlay = document.getElementById('search-overlay');
            const closeBtn = document.getElementById('close-search');
            const searchInput = document.querySelector('.search-field');

            // Open Overlay
            searchTriggers.forEach(trigger => {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    overlay.style.display = 'flex';
                    setTimeout(() => { searchInput.focus(); }, 100); // Auto-focus the cursor
                });
            });

            // Close Overlay
            closeBtn.addEventListener('click', function() {
                overlay.style.display = 'none';
            });

            // Close on 'Esc' key
            document.addEventListener('keydown', function(e) {
                if (e.key === "Escape") {
                    overlay.style.display = 'none';
                }
            });
        });
    </script>
<?php }

add_action('wp_footer', 'flavah_search_overlay');
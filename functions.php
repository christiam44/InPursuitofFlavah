<?php
/**
 * Nook functions and definitions
 */

declare( strict_types = 1 );

if ( ! function_exists( 'nook_support' ) ) :
    function nook_support() {
        add_editor_style( 'style.css' );
        load_theme_textdomain( 'nook' );
        
        // Enables Featured Images for Food and Vendor posts
        add_theme_support('post-thumbnails');
    }
endif;
add_action( 'after_setup_theme', 'nook_support' );

if ( ! function_exists( 'nook_styles' ) ) :
    // Enqueue the main stylesheet
    function nook_styles() {
        wp_register_style(
            'nook-style',
            get_stylesheet_directory_uri() . '/style.css',
            array(),
            wp_get_theme()->get( 'Version' )
        );

        wp_enqueue_style( 'nook-style' );
    }
endif;
add_action( 'wp_enqueue_scripts', 'nook_styles' );

// WordPress.com theme updater
if ( is_admin() )
    include dirname( __FILE__ ) . '/inc/updater.php';

// Load Font Awesome icons for the search and menu triggers
function flavah_enqueue_icons() {
    wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
}
add_action('wp_enqueue_scripts', 'flavah_enqueue_icons');


/**
 * SEARCH OVERLAY
 * This handles the USER INTERFACE of the search. 
 * Note: The LOGIC (AJAX) has been moved to the Flavah Search Plugin.
 */
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

            // Open overlay and focus the input field
            searchTriggers.forEach(trigger => {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    overlay.style.display = 'flex';
                    setTimeout(() => { if(searchInput) searchInput.focus(); }, 100); 
                });
            });

            // Close triggers
            closeBtn.addEventListener('click', () => { overlay.style.display = 'none'; });
            document.addEventListener('keydown', (e) => { if (e.key === "Escape") overlay.style.display = 'none'; });
        });
    </script>
<?php }
add_action('wp_footer', 'flavah_search_overlay');


/**
 * CUSTOM LOGIN BRANDING
 * Customizes the WP-Admin login screen to match the 'In Pursuit of Flavah' identity.
 */
function flavah_login_css() { ?>
    <style type="text/css">
        body.login { background-color: #fcfbf7 !important; }
        .login h1 a {
            background-image: url('<?php echo get_theme_file_uri("assets/images/IPOF-logo.jpg"); ?>') !important;
            background-size: cover !important;
            width: 100px !important;
            height: 100px !important;
            border-radius: 50% !important;
            border: 3px solid #ff5722 !important;
        }
        .login form { border-radius: 12px !important; border: 1px solid #eadecc !important; }
        .login .button-primary { background: #ff5722 !important; border-color: #e64a19 !important; }
    </style>
<?php }
add_action('login_enqueue_scripts', 'flavah_login_css');

function flavah_login_url() { return home_url(); }
add_filter('login_headerurl', 'flavah_login_url');

function flavah_login_title() { return 'In Pursuit of Flavah - Portal'; }
add_filter('login_headertext', 'flavah_login_title');


/**
 * MOBILE MENU
 * Toggles the visibility of the navigation on mobile devices.
 */
function flavah_mobile_menu_script() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var menuTrigger = document.querySelector('.site-header__menu-trigger');
        var siteMenu = document.querySelector('.site-header__menu');

        if (menuTrigger && siteMenu) {
            menuTrigger.addEventListener('click', function() {
                siteMenu.classList.toggle('is-visible');
                this.style.transform = siteMenu.classList.contains('is-visible') ? 'rotate(90deg)' : 'rotate(0deg)';
            });
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'flavah_mobile_menu_script');


/**
 * CUSTOM USER ROLES
 * Defines 'Customer' and 'Vendor Owner' roles for the application's ecosystem.
 */
function flavah_custom_user_roles() {
    add_role('customer', 'Customer', array(
        'read' => true,
        'edit_posts' => false,
    ));

    add_role('vendor_owner', 'Vendor Owner', array(
        'read' => true,
        'edit_posts' => true, 
        'upload_files' => true, 
    ));
}
add_action('init', 'flavah_custom_user_roles');

// NOTE: AJAX Search Callback moved to Flavah Search Plugin for modularity.

//Adjust the number of reviews shown on the archive page to 5 per page
function flavah_adjust_queries($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('review')) {
        $query->set('posts_per_page', 5);
    }
}
add_action('pre_get_posts', 'flavah_adjust_queries');

<?php
/**
 * Nook functions and definitions
 */

declare( strict_types = 1 );

if ( ! function_exists( 'nook_support' ) ) :
    function nook_support() {
        add_editor_style( 'style.css' );
        load_theme_textdomain( 'nook' );
        
        // This line enables the Featured Image box in the dashboard for our posts
        add_theme_support('post-thumbnails');
    }
endif;
add_action( 'after_setup_theme', 'nook_support' );

if ( ! function_exists( 'nook_styles' ) ) :
    // Pulling in our main style.css sheet
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

// This handles the updater for the WordPress.com themes
if ( is_admin() )
    include dirname( __FILE__ ) . '/inc/updater.php';

// Grabbing Font Awesome so we can use icons across the site
function flavah_enqueue_icons() {
    wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
}
add_action('wp_enqueue_scripts', 'flavah_enqueue_icons');


// Putting the full screen search overlay into the footer
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

            // Open the overlay when search triggers are clicked
            searchTriggers.forEach(trigger => {
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    overlay.style.display = 'flex';
                    setTimeout(() => { searchInput.focus(); }, 100); // Tosses the cursor in the box automatically
                });
            });

            // Closes it when you click the 'X'
            closeBtn.addEventListener('click', function() {
                overlay.style.display = 'none';
            });

            // Closes it if the user hits the 'Esc' key
            document.addEventListener('keydown', function(e) {
                if (e.key === "Escape") {
                    overlay.style.display = 'none';
                }
            });
        });
    </script>
<?php }

add_action('wp_footer', 'flavah_search_overlay');


// Only searching for Vendors so we don't get duplicate results or mess up our layout
function flavah_search_filter($query) {
    if ($query->is_search && !is_admin()) {
        // We are strictly limiting the query to find only Vendors using the exact slug 'vendor'
        $query->set('post_type', array('vendor'));
    }
    return $query;
}
add_filter('pre_get_posts', 'flavah_search_filter');

// Spicing up the default WordPress login page so it matches our brand
function flavah_login_css() { ?>
    <style type="text/css">
        /* Setting the background to match our site's cream color */
        body.login {
            background-color: #fcfbf7 !important;
        }

        /* Making the logo box clean and round */
        .login h1 a {
            background-image: url('<?php echo get_theme_file_uri("assets/images/IPOF-logo.jpg"); ?>') !important;
            background-size: cover !important;
            width: 100px !important;
            height: 100px !important;
            border-radius: 50% !important;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important;
            border: 3px solid #ff5722 !important; /* Our brand's deep orange */
        }

        /* Styling the actual login form card */
        .login form {
            border-radius: 12px !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05) !important;
            border: 1px solid #eadecc !important;
        }

        /* Swapping the login button to our primary brand color */
        .login .button-primary {
            background: #ff5722 !important;
            border-color: #e64a19 !important;
            text-shadow: none !important;
            box-shadow: 0 2px 4px rgba(255, 87, 34, 0.2) !important;
        }
        .login .button-primary:hover {
            background: #e64a19 !important;
        }
    </style>
<?php }
add_action('login_enqueue_scripts', 'flavah_login_css');

// Changing the login logo link from WordPress.org back to our actual site
function flavah_login_url() {
    return home_url();
}
add_filter('login_headerurl', 'flavah_login_url');

// Tweaking the tooltip text when hovering over the logo
function flavah_login_title() {
    return 'In Pursuit of Flavah - Portal';
}
add_filter('login_headertext', 'flavah_login_title');

// JavaScript to toggle the mobile navigation menu on/off
function flavah_mobile_menu_script() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var menuTrigger = document.querySelector('.site-header__menu-trigger');
        var siteMenu = document.querySelector('.site-header__menu');

        if (menuTrigger && siteMenu) {
            menuTrigger.addEventListener('click', function() {
                siteMenu.classList.toggle('is-visible');
                
                // Animates the hamburger slightly when clicked just for a little extra polish
                this.style.transform = siteMenu.classList.contains('is-visible') ? 'rotate(90deg)' : 'rotate(0deg)';
            });
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'flavah_mobile_menu_script');


//Setting up 3 custom user roles for our site: Customers(critics), Vendor Owners, and Admins (which WP makes by default)

function flavah_custom_user_roles() {
    
    // Role 1: The Customer. They basically just need to read posts and leave reviews on the front end.
    add_role('customer', 'Customer', array(
        'read' => true,
        'edit_posts' => false, // Keep them out of the backend!
        'delete_posts' => false,
    ));

    // Role 2: The Vendor Owner. They need to be able to log in and upload/edit their own food items.
    add_role('vendor_owner', 'Vendor Owner', array(
        'read' => true,
        'edit_posts' => true, 
        'upload_files' => true, // So they can upload photos of their dishes
    ));
    
    // Note: The 3rd required role is just the standard Administrator (us) which WP makes by default!
}
add_action('init', 'flavah_custom_user_roles');
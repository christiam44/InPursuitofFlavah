<?php
/*
Plugin Name: Flavah Food Map
Description: Displays a grid of food vendors with hover effects using the [flavah_food_map] shortcode.
Version: 1.0
Author: Varun Ramchune
*/

// Security check: prevents direct access to the file
if ( ! defined( 'ABSPATH' ) ) exit;

// Register the shortcode so we can use [flavah_food_map] in pages or code
add_shortcode('flavah_food_map', 'display_flavah_food_map');

function display_flavah_food_map() {
    // Start buffering so the HTML stays inside the function until called
    ob_start(); ?>

    <style>
        /* Styling for the grid layout */
        .vendor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }
        /* Card styling and hover animations */
        .vendor-card {
            border: 1px solid #eee;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease !important;
        }
        .vendor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.12) !important;
        }
        /* Ensures images look consistent in size */
        .vendor-photo {
            height: 250px;
            transition: transform 0.3s ease-in-out;
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
        }
        .vendor-card:hover .vendor-photo {
            transform: scale(1.05);
        }
    </style>

    <div class="food-map-container" style="max-width: 1200px; margin: 50px auto; padding: 20px;">
        <div class="vendor-grid">
            <?php
            // Custom query to pull all posts from the 'vendor' post type
            $vendor_query = new WP_Query(array(
                'post_type'      => 'vendor',
                'posts_per_page' => -1, // -1 means show all vendors
                'orderby'        => 'title',
                'order'          => 'ASC'
            ));

            // Check if any vendors exist
            if ($vendor_query->have_posts()) : 
                while ($vendor_query->have_posts()) : $vendor_query->the_post(); 
                    
                    // Fetch the ACF custom field for the image
                    $venue_image = get_field('venue_photo');
                    $thumbnail = '';

                    // Logic to decide which image to show (ACF, Featured, or Fallback)
                    if (is_array($venue_image) && isset($venue_image['url'])) {
                        $thumbnail = $venue_image['url'];
                    } else {
                        $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large');
                    }
                    
                    // Final backup image if nothing else is found
                    if (!$thumbnail) {
                        $thumbnail = get_template_directory_uri() . '/assets/images/food-hero.jpg';
                    }
                    ?>
                    
                    <div class="vendor-card">
                        <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: inherit;">
                            <div class="vendor-photo" style="background-image: url('<?php echo esc_url($thumbnail); ?>');"></div>
                            <div class="vendor-info" style="padding: 20px; background: #fff; text-align: center;">
                                <h2 style="margin: 0; font-size: 1.4rem; color: #333;"><?php the_title(); ?></h2>
                                <span style="color: #e67e22; font-weight: 600; font-size: 0.9rem;">View Menu & Details →</span>
                            </div>
                        </a>
                    </div>

                <?php endwhile; 
                // Reset post data so the rest of the page doesn't get confused
                wp_reset_postdata(); 
            else : ?>
                <p style="text-align: center; grid-column: 1 / -1;">No vendors found! Please add some in the dashboard.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php
    // Return the buffered HTML
    return ob_get_clean();
}
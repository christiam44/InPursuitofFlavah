<?php
/*
Template Name: Food Map
*/

get_header(); ?>

<div class="food-map-container" style="max-width: 1200px; margin: 50px auto; padding: 20px;">
    
    <header style="text-align: center; margin-bottom: 40px;">
        <h1 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; margin-bottom: 10px;">Our Food Map</h1>
        <p style="color: #666; font-size: 1.1rem;">A visual guide to the best flavors and restaurant venues!</p>
    </header>

    <div class="vendor-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
        
        <?php
        // Query all of your Vendor posts
        $vendor_query = new WP_Query(array(
            'post_type'      => 'vendor',
            'posts_per_page' => -1, // Pulls all of them
            'orderby'        => 'title',
            'order'          => 'ASC'
        ));

        if ($vendor_query->have_posts()) : 
            while ($vendor_query->have_posts()) : $vendor_query->the_post(); 
                
                // Grab the featured image of the restaurant venue
                $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large');
                
                // Fallback image if the vendor doesn't have one uploaded
                if (!$thumbnail) {
                    $thumbnail = get_template_directory_uri() . '/assets/images/food-hero.jpg';
                }
                ?>

                <div class="vendor-card" style="border: 1px solid #eee; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: transform 0.2s;">
                    <a href="<?php the_permalink(); ?>" style="text-decoration: none; color: inherit;">
                        
                        <div class="vendor-photo" style="height: 250px; background: url('<?php echo esc_url($thumbnail); ?>') center/cover no-repeat;"></div>
                        
                        <div class="vendor-info" style="padding: 20px; background: #fff; text-align: center;">
                            <h2 style="margin: 0; font-size: 1.4rem; color: #333;"><?php the_title(); ?></h2>
                            <span style="color: #e67e22; font-weight: 600; font-size: 0.9rem;">View Menu & Details →</span>
                        </div>
                        
                    </a>
                </div>

            <?php endwhile; wp_reset_postdata(); ?>
        <?php else : ?>
            <p style="text-align: center; grid-column: 1 / -1;">No vendors found! Please add some in the dashboard.</p>
        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>
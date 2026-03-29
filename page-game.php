<?php
/*
Template Name: This or That Game
*/

get_header(); ?>

<div class="game-container" style="max-width: 1200px; margin: 50px auto; padding: 20px; text-align: center;">
    
    <h1 style="font-family: 'Playfair Display', serif; margin-bottom: 10px;">This or That: Culinary Edition</h1>
    <p style="color: #666; margin-bottom: 40px;">Click on the dish you prefer to find your ultimate flavor!</p>

    <div class="game-arena" style="display: flex; justify-content: space-around; gap: 30px; align-items: stretch;">

        <?php
        // 1. Query random Food Items from your Custom Post Type
        $game_query = new WP_Query(array(
            'post_type'      => 'food', // Matches your CPT name
            'posts_per_page' => 2,      // We only need 2 at a time to compare
            'orderby'        => 'rand',   // Randomizes the matchup every refresh
        ));

        if ($game_query->have_posts()) : 
            while ($game_query->have_posts()) : $game_query->the_post(); 
                
                // Get the linked vendor if you need to show who makes it
                $vendor = get_field('linked_vendor'); 
                $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large');
                
                // Fallback image if the food item doesn't have one
                if (!$thumbnail) {
                    $thumbnail = get_template_directory_uri() . '/assets/images/food-hero.jpg';
                }
                ?>

                <div class="food-card" style="flex: 1; border: 2px solid #eaeaea; border-radius: 12px; overflow: hidden; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;" 
                     onclick="alert('You chose <?php the_title(); ?>!'); location.reload();">
                    
                    <div class="food-image" style="height: 300px; background: url('<?php echo esc_url($thumbnail); ?>') center/cover no-repeat;">
                    </div>
                    
                    <div class="food-info" style="padding: 20px; background: #fff;">
                        <h2 style="margin: 0 0 5px 0; font-size: 1.5rem;"><?php the_title(); ?></h2>
                        
                        <?php if ($vendor) : 
                                // If ACF returns an array of posts, we grab the first one
                                if (is_array($vendor)) {
                                    $vendor = $vendor[0];
                                }
                                ?>
                                <span style="color: #e67e22; font-weight: 600;">by <?php echo esc_html($vendor->post_title); ?></span>
                            <?php endif; ?>
                    </div>
                </div>

            <?php endwhile; wp_reset_postdata(); ?>
        <?php else : ?>
            <p>Please add at least 2 Food Items in the dashboard to play the game!</p>
        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>
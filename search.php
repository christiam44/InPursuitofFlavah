<?php 
/**
 * The Search Results Template
 * Displays custom food items matching a user's search query, styled to match the site's brand.
 */
get_header(); 

// FORCING THE SEARCH PAGE TO ONLY SHOW FOOD ITEMS
$args = array(
    'post_type' => 'food',
    's'         => get_search_query()
);
$food_search_query = new WP_Query($args);
?>

<div class="container" style="max-width: 1200px; margin: 50px auto; padding: 20px;">
    <h1 style="font-family: 'Playfair Display', serif; margin-bottom: 30px;">
        Search Results for: "<?php echo esc_html(get_search_query()); ?>"
    </h1>

    <div class="search-results-list">
        <?php if ($food_search_query->have_posts()) : while ($food_search_query->have_posts()) : $food_search_query->the_post(); ?>
            
            <div style="border-bottom: 1px solid #eadecc; padding: 20px 0;">
                <h2 style="margin-bottom: 5px; font-family: 'Playfair Display', serif;">
                    <a href="<?php the_permalink(); ?>" style="color: #222; text-decoration: none; transition: color 0.2s ease;"><?php the_title(); ?></a>
                </h2>
                
                <p style="color: #555; line-height: 1.6; margin-bottom: 12px; font-size: 0.95rem;">
                    <?php echo wp_trim_words(get_the_excerpt(), 25); ?>
                </p>
                
                <a href="<?php the_permalink(); ?>" style="color: #ff5722; font-weight: bold; font-size: 0.9rem; text-decoration: none;">
                    Read More <i class="fa fa-arrow-circle-right" aria-hidden="true" style="margin-left: 3px;"></i>
                </a>
            </div>

        <?php endwhile; wp_reset_postdata(); ?>
        <?php else : ?>
            
            <div style="background: #fcfbf7; border: 1px solid #eadecc; padding: 30px; border-radius: 8px; text-align: center;">
                <p style="color: #666; font-size: 1.1rem; margin-bottom: 0;">
                    No food items found for "<strong><?php echo esc_html(get_search_query()); ?></strong>". Try searching for another menu item or dish!
                </p>
            </div>
            
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
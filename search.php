<?php get_header(); ?>

<div class="container" style="max-width: 1200px; margin: 50px auto; padding: 20px;">
    <h1 style="font-family: 'Playfair Display', serif; margin-bottom: 30px;">
        Search Results for: "<?php echo get_search_query(); ?>"
    </h1>

    <div class="search-results-list">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            
            <div style="border-bottom: 1px solid #eee; padding: 20px 0;">
                <h2 style="margin-bottom: 5px;">
                    <a href="<?php the_permalink(); ?>" style="color: #333; text-decoration: none;"><?php the_title(); ?></a>
                </h2>
                <p style="color: #666;"><?php echo wp_trim_words(get_the_excerpt(), 25); ?></p>
                <a href="<?php the_permalink(); ?>" style="color: #e67e22; font-weight: bold; font-size: 0.9rem;">Read More →</a>
            </div>

        <?php endwhile; ?>
        <?php else : ?>
            <p>No results found for "<?php echo get_search_query(); ?>". Try searching for something else!</p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
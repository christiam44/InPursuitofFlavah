<?php get_header(); ?>

<?php while(have_posts()) {
    the_post(); 
    
    // Get Custom Fields (Make sure these match your ACF field names!)
    $price = get_field('price');
    $spicy_level = get_field('spicy_level');
    $linked_vendor = get_field('linked_vendor'); // Relationship field
    ?>

    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('assets/images/food-hero.jpg'); ?>);"></div>
        <div class="page-banner__content container t-center c-white">
            <h1 class="headline headline--large"><?php the_title(); ?></h1>
            <h2 class="headline headline--medium">Delicious details inside!</h2>
        </div>
    </div>

    <div class="container container--narrow page-section" style="max-width: 800px; margin: 50px auto; padding: 20px;">
        
        <div class="metabox metabox--position-up metabox--with-home-link" style="margin-bottom: 30px;">
            <p><a class="metabox__blog-home-link" href="<?php echo site_url('/food-map'); ?>"><i class="fa fa-map" aria-hidden="true"></i> Back to Food Map</a> <span class="metabox__main"><?php the_title(); ?></span></p>
        </div>

        <div class="generic-content" style="display: flex; gap: 30px; align-items: flex-start;">
            
            <div style="flex: 1;">
                <?php if (has_post_thumbnail()) {
                    the_post_thumbnail('medium', array('style' => 'width: 100%; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);'));
                } else { ?>
                    <div style="width: 100%; height: 200px; background: #eee; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #aaa;">No Image</div>
                <?php } ?>
            </div>

            <div style="flex: 1.5;">
                <h2 style="font-family: 'Playfair Display', serif; margin-bottom: 10px;"><?php the_title(); ?></h2>
                
                <p style="font-size: 1.2rem; font-weight: bold; color: #e67e22; margin-bottom: 15px;">
                    <?php echo $price ? '$' . esc_html($price) : 'Price Varies'; ?>
                </p>

                <div style="color: #666; line-height: 1.6; margin-bottom: 20px;">
                    <?php the_content(); ?>
                </div>

                <?php if ($linked_vendor) : ?>
                    <div style="background: #f9f9f9; padding: 15px; border-radius: 8px; border: 1px solid #eee;">
                        <span style="font-size: 0.9rem; color: #888;">Served By:</span>
                        <?php foreach ($linked_vendor as $vendor) : ?>
                            <h4 style="margin: 5px 0 0 0;">
                                <a href="<?php echo get_permalink($vendor->ID); ?>" style="color: #333; text-decoration: none;">
                                    <?php echo get_the_title($vendor->ID); ?> →
                                </a>
                            </h4>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php } ?>

<?php get_footer(); ?>
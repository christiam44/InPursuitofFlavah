<?php get_header(); ?>

<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/food-hero.jpg'); ?>);"></div>
    <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title">Foodie Feed</h1>  
        <div class="page-banner__intro">
            <p>The latest honest reviews from the In Pursuit of Flavah community.</p>
        </div>
    </div>     
</div>

<div class="container container--narrow page-section" style="margin-top: 60px;">
    <?php
    while(have_posts()){
        the_post(); 
        $rating = get_field('rating'); // Pulls the ACF rating number
        
        // Grab the linked vendor data
        $linked_vendor = get_field('linked_vendor');
        $linked_vendor_id = null;
        
        // Maps linked vendor data to a usable ID, whether it's coming as an array, object, or raw ID
        if (!empty($linked_vendor)) {
            if (is_array($linked_vendor)) {
                // If it's a relationship field returning an array
                $linked_vendor_id = is_object($linked_vendor[0]) ? $linked_vendor[0]->ID : $linked_vendor[0];
            } elseif (is_object($linked_vendor)) {
                // If it's returning a single post object
                $linked_vendor_id = $linked_vendor->ID;
            } else {
                // If it's returning a raw ID integer/string created using the form on the front-end
                $linked_vendor_id = $linked_vendor;
            }
        }
    ?>
        <div class="event-summary" style="display: flex; align-items: center; margin-bottom: 20px;">
            
            <a class="event-summary__date t-center" href="<?php the_permalink(); ?>" style="flex-shrink: 0;">
                <span class="event-summary__month">Rating</span>
                <span class="event-summary__day"><?php echo $rating ? $rating : '—'; ?></span>
            </a>
            
            <div class="event-summary__content" style="flex-grow: 1; padding-left: 20px;">
                
                <?php if ($linked_vendor_id) : 
                    $vendor_name = get_the_title($linked_vendor_id);
                    $vendor_logo = get_the_post_thumbnail_url($linked_vendor_id, 'thumbnail');
                ?>
                    <div class="review-vendor-tag" style="display: flex; align-items: center; margin-bottom: 5px;">
                        <?php if ($vendor_logo) : ?>
                            <img src="<?php echo esc_url($vendor_logo); ?>" style="width: 25px; height: 25px; border-radius: 50%; object-fit: cover; margin-right: 8px; border: 1px solid #ddd;">
                        <?php endif; ?>
                        <span style="font-size: 0.85rem; color: #777;">Review for: <strong style="color: #ff5722;"><?php echo esc_html($vendor_name); ?></strong></span>
                    </div>
                <?php endif; ?>

                <h5 class="event-summary__title headline headline--tiny" style="margin-top: 0; margin-bottom: 5px;">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h5>
                <p><?php echo wp_trim_words(get_the_content(), 25); ?> 
                    <a href="<?php the_permalink(); ?>" class="nu gray">Read full critique</a>
                </p>
            </div>
        </div>
    <?php }
    echo paginate_links();
    ?>
</div>

<?php get_footer(); ?>
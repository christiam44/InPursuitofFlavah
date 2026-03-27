<?php get_header();

while(have_posts()) {
    the_post(); 
    $vendor_id = get_the_ID();
    ?>

    <div class="page-banner">
        <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/food-hero.jpg'); ?>);"></div>
        <div class="page-banner__content container t-center c-white">
            <h1 class="headline headline--large"><?php the_title(); ?></h1>
            <h2 class="headline headline--medium">Vendor Profile</h2>
        </div>
    </div>

    <div class="container container--narrow page-section">
        <div class="generic-content">
            <?php the_content(); ?>
        </div>

        <hr class="section-break">
        <h2 class="headline headline--medium">Menu Highlights</h2>

        <?php 
          $vendorMenu = new WP_Query(array(
            'post_type' => 'food',
            'meta_query' => array(
              array(
                'key' => 'linked_vendor',
                'compare' => 'LIKE',
                'value' => '"' . $vendor_id . '"'
              )
            )
          ));

          if ($vendorMenu->have_posts()) {
            echo '<ul class="link-list min-list" style="padding: 0; margin: 0;">';
            while ($vendorMenu->have_posts()) {
              $vendorMenu->the_post(); ?>
              <li style="list-style: none; margin-bottom: 1.5rem;">
                <div class="event-summary">
                  <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
                     <?php if(has_post_thumbnail()) {
                         the_post_thumbnail('thumbnail', array('style' => 'width: 100%; height: 100%; object-fit: cover; border-radius: 5px;'));
                     } else {
                         echo '<i class="fa fa-cutlery" aria-hidden="true" style="font-size: 2rem; padding-top: 10px;"></i>';
                     } ?>
                  </a>
                  <div class="event-summary__content">
                    <h5 class="event-summary__title headline headline--tiny">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h5>
                    <p><?php echo wp_trim_words(get_the_content(), 15); ?> 
                        <a href="<?php the_permalink(); ?>" class="nu gray">View Item</a>
                    </p>
                  </div>
                </div>
              </li>
            <?php }
            echo '</ul>';
          } else {
            echo '<p class="t-center">No menu items listed yet for ' . get_the_title() . '.</p>';
          }
          wp_reset_postdata(); 
        ?>

        <hr class="section-break">
        <h2 class="headline headline--medium">Customer Reviews</h2>

        <?php 
        $reviews = new WP_Query(array(
            'post_type' => 'review',
            'meta_query' => array(
                array(
                    'key' => 'linked_vendor', 
                    'compare' => 'LIKE',
                    'value' => '"' . $vendor_id . '"' 
                )
            )
        ));

        if($reviews->have_posts()) {
            while($reviews->have_posts()) {
                $reviews->the_post();
                $rating = get_field('rating'); 
                ?>
                
                <div class="event-summary">
                    <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
                        <span class="event-summary__month">Rating</span>
                        <span class="event-summary__day"><?php echo $rating ? $rating : 'N/A'; ?></span>
                    </a>
                    <div class="event-summary__content">
                        <h5 class="event-summary__title headline headline--tiny">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h5>
                        <p><?php echo wp_trim_words(get_the_content(), 25); ?> 
                            <a href="<?php the_permalink(); ?>" class="nu gray">Read full review</a>
                        </p>
                    </div>
                </div>

            <?php }
            wp_reset_postdata();
        } else {
            echo '<p class="t-center">No reviews yet for ' . get_the_title() . '. Be the first to write one!</p>';
        }
        ?>

    </div>

<?php }

get_footer(); ?>
<?php get_header(); ?>

<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('assets/images/IPOF-logo.jpg'); ?>);"></div>
  <div class="page-banner__content container t-center c-white">
    <h1 class="headline headline--large"><?php bloginfo('name'); ?></h1>
    <h2 class="headline headline--medium"><?php bloginfo('description'); ?></h2>
    <a href="<?php echo site_url('/this-or-that'); ?>" class="btn btn--large btn--blue">Play This or That</a>
  </div>
</div>

<div class="full-width-split group">
  <div class="full-width-split__one">
    <div class="full-width-split__inner">
      <h2 class="headline headline--small-plus t-center">Latest Reviews</h2>

      <?php
      $homepageReviews = new WP_Query(array(
        'posts_per_page' => 2,
        'post_type'      => 'review'
      ));

      if ($homepageReviews->have_posts()) {
        while ($homepageReviews->have_posts()) {
          $homepageReviews->the_post(); 
          $rating = get_field('rating'); 
          ?>
          <div class="event-summary">
            <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
              <span class="event-summary__month">Rating</span>
              <span class="event-summary__day"><?php echo $rating ? $rating : 'N/A'; ?></span>
            </a>
            <div class="event-summary__content">
              <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
              <p><?php echo wp_trim_words(get_the_content(), 18); ?> <a href="<?php the_permalink(); ?>" class="nu gray">Read more</a></p>
            </div>
          </div>
        <?php }
      } else {
        echo '<p class="t-center">No reviews yet. Be the first to leave one!</p>';
      }
      wp_reset_postdata();
      ?>
    </div>
  </div>

  <div class="full-width-split__two">
    <div class="full-width-split__inner">
      <h2 class="headline headline--small-plus t-center">Vendor Spotlights</h2>

      <?php
      $homepageVendors = new WP_Query(array(
        'posts_per_page' => 2,
        'post_type'      => 'vendor'
      ));

      if ($homepageVendors->have_posts()) {
        while ($homepageVendors->have_posts()) {
          $homepageVendors->the_post();
          $price = get_field('price_range');
          
          // MAP TEXT LABELS TO DOLLAR SIGNS
          $price_display = '?';
          if ($price) {
              switch (strtolower($price)) {
                  case 'cheap':
                      $price_display = '$';
                      break;
                  case 'moderate':
                      $price_display = '$$';
                      break;
                  case 'expensive':
                      $price_display = '$$$';
                      break;
                  default:
                      $price_display = $price; // Fallback to raw text if it's already a number or custom
              }
          }
          ?>
          <div class="event-summary">
            <a class="event-summary__date event-summary__date--beige t-center" href="<?php the_permalink(); ?>">
              <span class="event-summary__month">Price</span>
              <span class="event-summary__day"><?php echo $price_display; ?></span>
            </a>
            <div class="event-summary__content">
              <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
              <p><?php echo wp_trim_words(get_the_content(), 18); ?> <a href="<?php the_permalink(); ?>" class="nu gray">View Vendor</a></p>
            </div>
          </div>
        <?php }
      } else {
        echo '<p class="t-center">Exploring the local scene... new vendors coming soon!</p>';
      }
      wp_reset_postdata();
      ?>
    </div>
  </div>
</div>

<?php get_footer(); ?>
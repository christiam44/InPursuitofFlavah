<?php 

//Checking to see if the form was submitted and handling the review creation process
$message = '';
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty( $_POST['action'] ) && $_POST['action'] == 'submit_custom_review' ) {
    
    // Grab and clean up what the user typed in the boxes
    $title = sanitize_text_field( $_POST['review_title'] );
    $content = sanitize_textarea_field( $_POST['review_content'] );
    $vendor_id = get_the_ID(); // Grabs the ID of the vendor profile the user is currently viewing!

    // Error handling if they forgot to fill out one of the boxes
    if ( empty( $title ) || empty( $content ) ) {
        $message = '<div style="background: #ffebee; color: #c62828; padding: 10px; border-radius: 6px; margin-bottom: 20px;">Please fill out the form completely</div>';
    } else {
        $new_review = array(
            'post_title'   => $title,
            'post_content' => $content,
            'post_status'  => 'publish', // Published the review automatically so that it shows up on the site right away
            'post_type'    => 'review',
            'post_author'  => get_current_user_id() // Links the review to the user who wrote it
        );

        //Adds the post to the database and gives us back the new review's post ID 

        if ( $post_id ) {
            // Maps review to vendor by saving the vendor's ID in the review's post meta 
            update_post_meta( $post_id, 'linked_vendor', $vendor_id ); 
            $message = '<div style="background: #e8f5e9; color: #2e7d32; padding: 10px; border-radius: 6px; margin-bottom: 20px;">Review submitted successfully! Thanks for sharing your flavah!</div>';
        }
    }
}

get_header();

//Loop through the vendor post that was clicked on and display its content
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
          // Query to show food items linked to the vendor
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
          wp_reset_postdata(); // Clean up after our custom query
        ?>

        <hr class="section-break">
        <h2 class="headline headline--medium">Customer Reviews</h2>

        <?php 
        // Query to show reviews linked to the vendor
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
                $rating = get_field('rating'); // Pulls the rating used in the review acf field
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
            wp_reset_postdata(); // Cleans up the loop again
        } else {
            echo '<p class="t-center">No reviews yet for ' . get_the_title() . '. Be the first to write one!</p>';
        }
        ?>

    </div>

<?php } ?>

<div class="container" style="margin-top: 50px; margin-bottom: 50px;">
    <div class="review-form-container" style="max-width: 600px; padding: 30px; background: #fff; border: 1px solid #eadecc; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
        
        <h2 style="font-family: 'Playfair Display', serif; color: #ff5722; margin-bottom: 20px;">Leave a Review for <?php the_title(); ?></h2>

        <?php if ( ! is_user_logged_in() ) { ?>
            <p style="background: #fcfbf7; padding: 15px; border-radius: 6px; border: 1px solid #eadecc;">
                Want to leave a review? <a href="<?php echo wp_login_url(); ?>" style="color: #ff5722; font-weight: bold;">Log in here</a> to share your experience!
            </p>
        <?php } else { ?>
            
            <?php echo $message; ?>

            <form method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
                
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Review Title</label>
                    <input type="text" name="review_title" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;" placeholder="e.g., Best Doubles Ever!" required>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Your Review</label>
                    <textarea name="review_content" rows="5" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px;" placeholder="Tell us about the vibes and the food..." required></textarea>
                </div>

                <input type="hidden" name="action" value="submit_custom_review">
                
                <button type="submit" style="width: 100%; padding: 12px; background: #ff5722; color: #fff; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">Submit Review</button>
            </form>

        <?php } ?>
    </div>
</div>

<?php get_footer(); ?>
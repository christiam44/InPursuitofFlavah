<?php 
// 1. FORMS REDIRECT: Handle form processing BEFORE the theme loads the page header
if ( $_SERVER['REQUEST_METHOD'] == 'POST' && !empty( $_POST['action'] ) ) {
    
    // Handling the Review Submission
    if ($_POST['action'] == 'submit_custom_review') {
        $title = sanitize_text_field( $_POST['review_title'] );
        $content = sanitize_textarea_field( $_POST['review_content'] );
        $rating = intval( $_POST['review_rating'] ); 
        $vendor_id = intval( $_POST['vendor_id'] ); 

        if ( !empty( $title ) && !empty( $content ) && !empty( $rating ) && !empty( $vendor_id ) ) {
            $new_review = array(
                'post_title'   => $title,
                'post_content' => $content,
                'post_status'  => 'publish', 
                'post_type'    => 'review',
                'post_author'  => get_current_user_id() 
            );

            $post_id = wp_insert_post( $new_review );
            if ( $post_id ) {
                update_post_meta( $post_id, 'linked_vendor', $vendor_id ); 
                update_post_meta( $post_id, 'rating', $rating );

                wp_redirect( add_query_arg( 'review_status', 'success', $_SERVER['REQUEST_URI'] ) );
                exit;
            }
        }
    }

    // Handling Administrator Delete Request
    if ($_POST['action'] == 'delete_custom_review' && current_user_can('edit_theme_options')) {
        $review_to_delete = intval($_POST['review_id']);
        if ($review_to_delete) {
            wp_delete_post($review_to_delete, true); 
            wp_redirect( add_query_arg( 'review_status', 'deleted', $_SERVER['REQUEST_URI'] ) );
            exit;
        }
    }
}

get_header();

$message = '';
if (isset($_GET['review_status'])) {
    if ($_GET['review_status'] == 'success') {
        $message = '<div style="background: #e8f5e9; color: #2e7d32; padding: 10px; border-radius: 6px; margin-bottom: 20px;">Review submitted successfully! Thanks for sharing the flavah!</div>';
    } elseif ($_GET['review_status'] == 'deleted') {
        $message = '<div style="background: #ffebee; color: #c62828; padding: 10px; border-radius: 6px; margin-bottom: 20px;">Review deleted successfully.</div>';
    }
}

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

    <div class="container container--narrow page-section" style="margin-top: 40px; margin-bottom: 60px;">
        
        <div style="display: flex; flex-wrap: wrap; gap: 40px;">
            
            <div style="flex: 1 1 600px;">
                
                <h2 class="headline headline--medium" style="margin-top: 0;">Menu Highlights</h2>

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
                        <div class="event-summary" style="border: 1px solid #eadecc; border-radius: 8px; padding: 15px; background: #fff;">
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
                    echo '<p>No menu items listed yet for ' . get_the_title() . '.</p>';
                  }
                  wp_reset_postdata(); 
                ?>

                <hr class="section-break" style="margin: 40px 0;">
                
                <h2 class="headline headline--medium">Customer Reviews</h2>

                <?php echo $message; ?>

                <?php 
                $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

                $reviews = new WP_Query(array(
                    'post_type' => 'review',
                    'posts_per_page' => 4, 
                    'paged' => $paged,
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
                        $current_review_id = get_the_ID();
                        ?>
                        
                        <div class="event-summary" style="display: flex; align-items: center; margin-bottom: 20px; border: 1px solid #eadecc; border-radius: 8px; padding: 15px; background: #fff;">
                            <a class="event-summary__date t-center" href="<?php the_permalink(); ?>" style="flex-shrink: 0; background-color: #ff5722; color: #fff; border-radius: 6px;">
                                <span class="event-summary__month" style="color: #fff; opacity: 0.9;">Rating</span>
                                <span class="event-summary__day" style="color: #fff; font-weight: bold;"><?php echo $rating ? $rating : 'N/A'; ?></span>
                            </a>
                            <div class="event-summary__content" style="flex-grow: 1; padding-left: 20px;">
                                <h5 class="event-summary__title headline headline--tiny" style="margin-top: 0; margin-bottom: 5px;">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h5>
                                <p><?php echo wp_trim_words(get_the_content(), 25); ?> 
                                    <a href="<?php the_permalink(); ?>" class="nu gray">Read full review</a>
                                </p>
                            </div>

                            <?php if (current_user_can('edit_theme_options')) : ?>
                                <form method="POST" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" style="margin: 0; padding-left: 10px;" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                    <input type="hidden" name="action" value="delete_custom_review">
                                    <input type="hidden" name="review_id" value="<?php echo $current_review_id; ?>">
                                    <button type="submit" style="background: #ffebee; color: #c62828; border: 1px solid #c62828; border-radius: 4px; padding: 5px 10px; cursor: pointer; font-size: 0.8rem;">Delete</button>
                                </form>
                            <?php endif; ?>
                        </div>

                    <?php }
                    
                        echo '<div class="pagination" style="display: flex; gap: 5px; margin-top: 20px;">';
                        echo paginate_links(array(
                            'total' => $reviews->max_num_pages,
                            'current' => $paged,
                            'prev_text' => '<span style="padding: 8px 12px; border: 1px solid #ff5722; color: #ff5722; border-radius: 4px;">&laquo; Prev</span>',
                            'next_text' => '<span style="padding: 8px 12px; border: 1px solid #ff5722; color: #ff5722; border-radius: 4px;">Next &raquo;</span>',
                        ));
                        echo '</div>';
                    wp_reset_postdata(); 
                } else {
                    echo '<p>No reviews yet for ' . get_the_title() . '. Be the first to write one!</p>';
                }
                ?>
            </div>


            <div style="flex: 0 1 350px;">
                
                <div style="position: sticky; top: 20px;">
                    
                    <div style="background: #fcfbf7; border: 1px solid #eadecc; padding: 25px; border-radius: 12px; margin-bottom: 30px;">
                        <h3 style="font-family: 'Playfair Display', serif; color: #ff5722; margin-top: 0; margin-bottom: 10px;">About the Vendor</h3>
                        <div class="generic-content" style="font-size: 0.95rem; line-height: 1.6; color: #555;">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <div class="review-form-container" style="padding: 25px; background: #fff; border: 1px solid #eadecc; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.03);">
                        
                        <h3 style="font-family: 'Playfair Display', serif; color: #ff5722; margin-top: 0; margin-bottom: 15px;">Leave a Review</h3>

                        <?php if ( ! is_user_logged_in() ) { ?>
                            <p style="background: #fcfbf7; padding: 15px; border-radius: 6px; border: 1px solid #eadecc; font-size: 0.9rem;">
                                Want to leave a review? <a href="<?php echo wp_login_url(); ?>" style="color: #ff5722; font-weight: bold;">Log in here</a> to share your experience!
                            </p>
                        <?php } else { ?>
                            
                            <form method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
                                
                                <div style="margin-bottom: 12px;">
                                    <label style="display: block; font-weight: bold; font-size: 0.85rem; margin-bottom: 5px;">Review Title</label>
                                    <input type="text" name="review_title" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 6px;" placeholder="e.g., Delicious BBQ" required>
                                </div>

                                <div style="margin-bottom: 12px;">
                                    <label style="display: block; font-weight: bold; font-size: 0.85rem; margin-bottom: 5px;">Your Rating</label>
                                    <select name="review_rating" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 6px;" required>
                                        <option value="">Select a rating</option>
                                        <option value="5">5 - Excellent</option>
                                        <option value="4">4 - Good</option>
                                        <option value="3">3 - Average</option>
                                        <option value="2">2 - Poor</option>
                                        <option value="1">1 - Terrible</option>
                                    </select>
                                </div>

                                <div style="margin-bottom: 15px;">
                                    <label style="display: block; font-weight: bold; font-size: 0.85rem; margin-bottom: 5px;">Your Review</label>
                                    <textarea name="review_content" rows="4" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 6px;" placeholder="Let us know how the food was..." required></textarea>
                                </div>

                                <input type="hidden" name="action" value="submit_custom_review">
                                <input type="hidden" name="vendor_id" value="<?php echo $vendor_id; ?>">
                                
                                <button type="submit" style="width: 100%; padding: 10px; background: #ff5722; color: #fff; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 0.9rem;">Submit Review</button>
                            </form>

                        <?php } ?>
                    </div> </div> </div> </div> </div>

<?php } ?>

<?php get_footer(); ?>
<?php get_header();

while(have_posts()) {
    the_post(); ?>

    <div class="page-banner">
        <?php 
        // Show the featured image or a default as a fallback
        $bannerImage = get_the_post_thumbnail_url() ? get_the_post_thumbnail_url(get_the_ID(), 'full') : get_theme_file_uri('images/food-hero.jpg');
        ?>
        <div class="page-banner__bg-image" style="background-image: url(<?php echo $bannerImage; ?>);"></div>
        <div class="page-banner__content container t-center">
            <h1 class="headline headline--large"><?php the_title(); ?></h1>
        </div>
    </div>

    <div class="container container--narrow page-section" style="margin-top: 40px; margin-bottom: 60px;">
        
        <div style="display: flex; flex-wrap: wrap; gap: 40px;">
            
            <div style="flex: 1 1 600px;">
                
                <?php 
                $rating = get_field('rating'); 
                if ($rating) { ?>
                    <div style="display: inline-flex; align-items: center; background: #fff8f6; border: 1px solid #ff5722; padding: 10px 20px; border-radius: 50px; margin-bottom: 25px;">
                        <span style="color: #555; font-weight: bold; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">Score:</span>
                        <span style="color: #ff5722; font-size: 1.5rem; font-weight: 800; margin-left: 8px; font-family: 'Playfair Display', serif;">
                            <?php echo esc_html($rating); ?> / 5
                        </span>
                    </div>
                <?php } ?>

                <div class="generic-content" style="font-size: 1.1rem; line-height: 1.8; color: #333;">
                    <?php the_content(); ?>
                </div>
            </div>

            <?php 
            $linked_vendor = get_field('linked_vendor'); 
            $vendor_id = null;
            
            // Reusing our robust check to grab the correct ID
            if (!empty($linked_vendor)) {
                if (is_array($linked_vendor)) {
                    $vendor_id = is_object($linked_vendor[0]) ? $linked_vendor[0]->ID : $linked_vendor[0];
                } elseif (is_object($linked_vendor)) {
                    $vendor_id = $linked_vendor->ID;
                } else {
                    $vendor_id = $linked_vendor;
                }
            }
            
            if ($vendor_id) { 
                $vendor_name = get_the_title($vendor_id);
                $vendor_img = get_the_post_thumbnail_url($vendor_id, 'medium');
                $vendor_excerpt = get_the_excerpt($vendor_id);
            ?>
                <div style="flex: 0 1 320px;">
                    <div style="background: #fff; border: 1px solid #eadecc; border-radius: 12px; overflow: hidden; position: sticky; top: 20px; box-shadow: 0 10px 20px rgba(0,0,0,0.03);">
                        
                        <div style="background: #ff5722; color: #fff; padding: 15px; text-align: center;">
                            <h4 style="margin: 0; font-size: 1rem; text-transform: uppercase; letter-spacing: 1px; color: #fff;">About the Vendor</h4>
                        </div>
                        
                        <?php if ($vendor_img) : ?>
                            <div style="height: 180px; overflow: hidden;">
                                <img src="<?php echo esc_url($vendor_img); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        <?php else : ?>
                            <div style="height: 120px; background: #fcfbf7; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-cutlery" style="font-size: 2rem; color: #ff5722;"></i>
                            </div>
                        <?php endif; ?>

                        <div style="padding: 20px; text-align: center;">
                            <h3 style="font-family: 'Playfair Display', serif; margin-top: 0; margin-bottom: 10px; color: #222;">
                                <?php echo esc_html($vendor_name); ?>
                            </h3>
                            
                            <?php if ($vendor_excerpt) : ?>
                                <p style="font-size: 0.9rem; color: #666; margin-bottom: 20px;">
                                    <?php echo wp_trim_words($vendor_excerpt, 15); ?>
                                </p>
                            <?php endif; ?>

                            <a href="<?php echo get_permalink($vendor_id); ?>" style="display: block; background: #222; color: #fff; padding: 12px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 0.9rem; transition: background 0.3s ease;">
                                <i class="fa fa-cutlery" aria-hidden="true" style="margin-right: 5px;"></i> Visit Profile
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>

        </div>
        </div>

<?php }

get_footer(); ?>
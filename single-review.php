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

    <div class="container container--narrow page-section">
        
        <?php 
        $rating = get_field('rating'); 
        if ($rating) { ?>
            <div style="margin-bottom: 20px; font-size: 1.2rem;">
                <strong>Rating:</strong> 
                <span style="color: #ff5722; font-weight: bold;"><?php echo esc_html($rating); ?> / 5</span>
            </div>
        <?php } ?>

        <div class="generic-content">
            <?php the_content(); ?>
        </div>

        <?php 
        // Checking if linked_vendor field has a value and showing a link to the Vendor profile if it does
        $vendor_id = get_field('linked_vendor'); 
        
        // If there is a linked vendor, we show a link to their profile page at the bottom of the review
        if($vendor_id) { ?>
            <hr class="section-break">
            <h2 class="headline headline--small">About the Vendor:</h2>
            <ul class="link-list min-list">
                <li>
                    <a href="<?php echo get_the_permalink($vendor_id); ?>">
                        <i class="fa fa-cutlery" aria-hidden="true"></i> Visit <?php echo get_the_title($vendor_id); ?>
                    </a>
                </li>
            </ul>
        <?php } ?>
    </div>

<?php }

get_footer(); ?>
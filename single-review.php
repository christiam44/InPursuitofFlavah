<?php get_header();

while(have_posts()) {
    the_post(); ?>

    <div class="page-banner">
        <?php 
        // Logic to show the Review's featured image or a default
        $bannerImage = get_the_post_thumbnail_url() ? get_the_post_thumbnail_url(get_the_ID(), 'full') : get_theme_file_uri('images/food-hero.jpg');
        ?>
        <div class="page-banner__bg-image" style="background-image: url(<?php echo $bannerImage; ?>);"></div>
        <div class="page-banner__content container t-center">
            <h1 class="headline headline--large"><?php the_title(); ?></h1>
        </div>
    </div>

    <div class="container container--narrow page-section">
        <div class="generic-content">
            <?php the_content(); ?>
        </div>

        <?php 
        // LOGIC: Check for the 'Linked Vendor' Relationship field
        $linkedVendors = get_field('linked_vendor'); 
        
        if($linkedVendors) { ?>
            <hr class="section-break">
            <h2 class="headline headline--small">About the Vendor:</h2>
            <ul class="link-list min-list">
                <?php foreach($linkedVendors as $vendor) { ?>
                    <li>
                        <a href="<?php echo get_the_permalink($vendor); ?>">
                            <i class="fa fa-cutlery" aria-hidden="true"></i> Visit <?php echo get_the_title($vendor); ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>

<?php }

get_footer(); ?>
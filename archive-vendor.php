<?php get_header(); ?>

<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/food-hero.jpg'); ?>);"></div>
    <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title">Local Vendors</h1>  
        <div class="page-banner__intro">
            <p>From Curepe to Port of Spain—find your next favorite meal.</p>
        </div>
    </div>     
</div>

<div class="container container--narrow page-section">
    <div class="vendor-grid">
        <?php
        while(have_posts()){
            the_post(); ?>
            
            <div class="event-summary">
                <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()) {
                        the_post_thumbnail('thumbnail');
                    } else { ?>
                        <i class="fa fa-cutlery" aria-hidden="true" style="font-size: 2rem; padding-top: 15px;"></i>
                    <?php } ?>
                </a>
                <div class="event-summary__content">
                    <h5 class="event-summary__title headline headline--tiny">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h5>
                    <p><?php echo wp_trim_words(get_the_content(), 18); ?> 
                        <a href="<?php the_permalink(); ?>" class="nu gray">View Profile</a>
                    </p>
                </div>
            </div>

        <?php }
        echo paginate_links();
        ?>
    </div>
</div>

<?php get_footer(); ?>
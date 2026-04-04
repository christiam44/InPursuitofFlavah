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
    ?>
        <div class="event-summary">
            <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
                <span class="event-summary__month">Rating</span>
                <span class="event-summary__day"><?php echo $rating ? $rating : '—'; ?></span>
            </a>
            <div class="event-summary__content">
                <h5 class="event-summary__title headline headline--tiny">
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
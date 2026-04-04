<?php get_header(); ?>

<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('images/food-hero.jpg'); ?>);"></div>
    <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title">Local Vendors</h1>  
        <div class="page-banner__intro">
            <p>Come explore and discover the best local food vendors in your area!</p>
        </div>
    </div>     
</div>

<style>
    .vendor-archive-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
        margin-top: 60px; /* Added spacing from banner */
        margin-bottom: 40px;
    }
    
    .vendor-archive-card {
        background: #fff;
        border: 1px solid #eaeaea;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
    }
    
    .vendor-archive-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .vendor-card__logo-area {
        height: 200px;
        background: #f9f9f9;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .vendor-card__logo-area img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    
    .vendor-card__text-area {
        padding: 25px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    
    .vendor-card__title {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        margin: 0 0 10px 0;
    }
    
    .vendor-card__title a {
        color: #333;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .vendor-card__title a:hover {
        color: #ff5722;
    }
    
    .vendor-card__desc {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: 20px;
    }
    
    .vendor-card__btn {
        margin-top: auto;
        display: inline-block;
        text-align: center;
        background: #ff5722;
        color: #fff !important;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: bold;
        text-decoration: none;
        transition: background 0.2s ease;
    }
    
    .vendor-card__btn:hover {
        background: #e04c1d;
    }
    
    /* Pagination styling */
    .custom-pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 30px;
    }
    
    .custom-pagination a, .custom-pagination span {
        padding: 10px 15px;
        border: 1px solid #ff5722;
        border-radius: 4px;
        color: #ff5722;
        text-decoration: none;
    }
    
    .custom-pagination .current {
        background: #ff5722;
        color: #fff;
    }
</style>

<div class="container page-section">
    <div class="vendor-archive-grid">
        <?php
        while(have_posts()){
            the_post(); ?>
            
            <div class="vendor-archive-card">
                <div class="vendor-card__logo-area">
                    <a href="<?php the_permalink(); ?>">
                        <?php if (has_post_thumbnail()) {
                            the_post_thumbnail('medium');
                        } else { ?>
                            <i class="fa fa-cutlery" aria-hidden="true" style="font-size: 3rem; color: #ccc;"></i>
                        <?php } ?>
                    </a>
                </div>
                
                <div class="vendor-card__text-area">
                    <h5 class="vendor-card__title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h5>
                    
                    <div class="vendor-card__desc">
                        <?php echo wp_trim_words(get_the_content(), 22); ?>
                    </div>
                     
                    <a href="<?php the_permalink(); ?>" class="vendor-card__btn">View Profile</a>
                </div>
            </div>

        <?php } ?>
    </div>
    
    <div class="custom-pagination">
        <?php echo paginate_links(); ?>
    </div>
</div>

<?php get_footer(); ?>
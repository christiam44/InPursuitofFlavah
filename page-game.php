<?php
/*
Template Name: This or That Game
*/

get_header(); ?>

<div class="game-container" style="max-width: 1200px; margin: 50px auto; padding: 20px; text-align: center;">
    <?php
    while ( have_posts() ) :
        the_post();
        // This line is the "bridge." It pulls the [flavah_game] shortcode 
        // from your dashboard and runs the plugin code.
        the_content(); 
    endwhile;
    ?>
</div>

<?php get_footer(); ?>

<?php
/*
Template Name: Food Map
*/

// Standard WordPress header
get_header(); ?>

<header style="text-align: center; margin-top: 50px;">
    <h1 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; margin-bottom: 10px;">Our Food Map</h1>
    <p style="color: #666; font-size: 1.1rem;">A visual guide to the best flavors and restaurant venues!</p>
</header>

<?php 
/**
 * Run the Food Map Shortcode. 
 * This calls the logic inside the Flavah Food Map plugin.
 */
echo do_shortcode('[flavah_food_map]'); 
?>

<?php 
// Standard WordPress footer
get_footer(); ?>

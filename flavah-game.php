<?php
/*
Plugin Name: Flavah Discovery Engine - This or That Game
Description: A tournament-style food recommendation game built for the INFO3602 Group Project.
Version: 1.0
Author: Christiam Alexander
*/
// Secure the file from direct access
if ( ! defined( 'ABSPATH' ) ) exit;

// Register the shortcode [flavah_game]
add_shortcode('flavah_game', 'run_flavah_game_plugin');

function run_flavah_game_plugin() {
    // Safety check: ensure ACF is active before running queries
    if ( ! function_exists('get_field') ) {
        return "<p style='color:red;'>Error: Advanced Custom Fields must be active to play this game.</p>";
    }

    // Start output buffering to inject the game into the page content
    ob_start(); 
    ?>

    <div class="game-container" style="max-width: 1200px; margin: 50px auto; padding: 20px; text-align: center;">
        
        <div id="game-intro">
            <h1 style="font-family: 'Playfair Display', serif; margin-bottom: 10px;">This or That: Culinary Edition</h1>
            <p style="color: #666; margin-bottom: 40px;">Click on the dish you prefer to find your ultimate flavor!</p>
        </div>

        <div id="game-arena" style="display: flex; justify-content: space-around; gap: 30px; align-items: stretch;">
            </div>

        <div id="winner-screen" style="display: none; padding: 40px; border: 3px solid #e67e22; border-radius: 12px; background: #fff;">
            <h1 style="color: #e67e22;">🎉 We Have a Winner! 🎉</h1>
            <p style="font-size: 1.2rem;">Based on your choices, you should definitely check out:</p>
            <h2 id="winning-food" style="margin: 20px 0 5px 0; font-size: 2.5rem;"></h2>
            <p id="winning-vendor" style="font-size: 1.3rem; color: #555; font-weight: bold;"></p>
            <div style="margin-top: 30px;">
                <a id="vendor-link" href="#" style="background: #e67e22; color: #fff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;">Visit Restaurant Page</a>
                <button onclick="location.reload();" style="background: #eee; color: #333; padding: 12px 25px; border: none; border-radius: 5px; font-weight: bold; margin-left: 10px; cursor: pointer;">Play Again</button>
            </div>
        </div>
    </div>

    <?php
    // PHP pulls 10 random foods to act as our tournament bracket
    $game_query = new WP_Query(array(
        'post_type'      => 'food',
        'posts_per_page' => 10,
        'orderby'        => 'rand',
    ));

    $food_array = array();
     // Loop through the foods and gather necessary data (title, image, linked vendor info) to pass to JavaScript for game logic and display
    if ($game_query->have_posts()) : 
        while ($game_query->have_posts()) : $game_query->the_post(); 
            
            $vendor = get_field('linked_vendor');
            if (is_array($vendor)) { $vendor = $vendor[0]; }
            
            $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large');
            if (!$thumbnail) {
                // Use a fallback image if no thumbnail is set
                $thumbnail = 'https://via.placeholder.com/600x400?text=Flavah+Food';
            }

            // Pack all this data into a clean PHP array to hand over to JavaScript
            $food_array[] = array(
                'title' => get_the_title(),
                'img' => $thumbnail,
                'vendor_name' => $vendor ? $vendor->post_title : 'Local Vendor',
                'vendor_url' => $vendor ? get_permalink($vendor->ID) : '#'
            );

        endwhile; wp_reset_postdata();
    endif; 
    ?>

    <script>
    // Pass our PHP data into JavaScript
    let foodPool = <?php echo json_encode($food_array); ?>;

    const arena = document.getElementById('game-arena');
    const winnerScreen = document.getElementById('winner-screen');
    const gameIntro = document.getElementById('game-intro');

    // Fallback if there aren't enough foods in the DB
    if (foodPool.length < 2) {
        arena.innerHTML = "<p>Please add at least 2 Food Items in your WordPress dashboard to play!</p>";
    } else {
        loadMatchup();
    }

    function loadMatchup() {
        // If only 1 food remains, it is crowned the winner!
        if (foodPool.length === 1) {
            showWinner(foodPool[0]);
            return;
        }

        // Grab the first two foods from the list
        let itemA = foodPool[0];
        let itemB = foodPool[1];

        arena.innerHTML = `
            <div class="food-card" style="flex: 1; border: 2px solid #eaeaea; border-radius: 12px; overflow: hidden; cursor: pointer;" onclick="keepItem(0)">
                <div style="height: 300px; background: url('${itemA.img}') center/cover no-repeat;"></div>
                <div style="padding: 20px; background: #fff;">
                    <h2 style="margin: 0;">${itemA.title}</h2>
                    <span style="color: #e67e22; font-weight: 600;">by ${itemA.vendor_name}</span>
                </div>
            </div>

            <div class="food-card" style="flex: 1; border: 2px solid #eaeaea; border-radius: 12px; overflow: hidden; cursor: pointer;" onclick="keepItem(1)">
                <div style="height: 300px; background: url('${itemB.img}') center/cover no-repeat;"></div>
                <div style="padding: 20px; background: #fff;">
                    <h2 style="margin: 0;">${itemB.title}</h2>
                    <span style="color: #e67e22; font-weight: 600;">by ${itemB.vendor_name}</span>
                </div>
            </div>
        `;
    }

    
    // When a user clicks on a food item we keep that one and remove the other from the pool, then load the next matchup
    function keepItem(winningIndex) {
        // Determine the index of the loser
        let losingIndex = (winningIndex === 0) ? 1 : 0;
        
        // Remove the loser from the array entirely
        foodPool.splice(losingIndex, 1);
        
        // Move the winner to the end of the line so they face new challengers
        let winner = foodPool.shift();
        foodPool.push(winner);
        
        // Load the next round
        loadMatchup();
    }

    function showWinner(finalDish) {
        gameIntro.style.display = 'none';
        arena.style.display = 'none';
        
        document.getElementById('winning-food').innerText = finalDish.title;
        document.getElementById('winning-vendor').innerText = "by " + finalDish.vendor_name;
        document.getElementById('vendor-link').href = finalDish.vendor_url;
        
        winnerScreen.style.display = 'block';
    }
    </script>

    <?php
    return ob_get_clean(); 
}
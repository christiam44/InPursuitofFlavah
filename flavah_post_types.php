<?php 
function flavah_post_types() { 

    // --- 1. TAXONOMY: FOOD TYPES (Categorize by Burgers, BBQ, etc.) ---
    register_taxonomy('food_type', 'vendor', array(
        'labels' => array(
            'name' => 'Food Types',
            'add_new_item' => 'Add New Food Type',
            'singular_name' => 'Food Type'
        ),
        'hierarchical' => true, // behaving like standard categories
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'food-type'),
    ));

    // --- 2. TAXONOMY: LOCATIONS (Categorize by Curepe, POS, etc.) ---
    register_taxonomy('location', 'vendor', array(
        'labels' => array(
            'name' => 'Locations',
            'add_new_item' => 'Add New Location',
            'singular_name' => 'Location'
        ),
        'hierarchical' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'location'),
    ));

    // --- 3. VENDORS ---
    register_post_type('vendor', array( 
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail'), 
        'rewrite' => array('slug' => 'vendors'), 
        'has_archive' => true, 
        'public' => true,  
        'labels' => array( 
            'name' => "Vendors", 
            'add_new_item' => 'Add New Vendor', 
            'edit_item' => 'Edit Vendor', 
            'all_items' => 'All Vendors', 
            'singular_name' => "Vendor" 
        ), 
        'menu_icon' => 'dashicons-store' 
    )); 

    // --- 4. REVIEWS ---
    register_post_type('review', array( 
        'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'comments'), 
        'rewrite' => array('slug' => 'reviews'), 
        'has_archive' => true, 
        'public' => true,  
        'labels' => array( 
            'name' => "Reviews", 
            'add_new_item' => 'Add New Review', 
            'edit_item' => 'Edit Review', 
            'all_items' => 'All Reviews', 
            'singular_name' => "Review" 
        ), 
        'menu_icon' => 'dashicons-star-filled' 
    ));

    // --- 5. FOOD ITEMS ---
    register_post_type('food', array( 
        'supports' => array('title', 'editor', 'thumbnail'), 
        'public' => true,  
        'labels' => array( 
            'name' => "Food Items", 
            'add_new_item' => 'Add New Food Item', 
            'edit_item' => 'Edit Food Item', 
            'all_items' => 'All Food Items',
            'singular_name' => "Food Item" 
        ), 
        'menu_icon' => 'dashicons-carrot' 
    )); 
}

add_action('init', 'flavah_post_types');
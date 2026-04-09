# InPursuitofFlavah
For INFO 3602 Project 2026

A  WordPress site developed for the INFO3602 Web Programming course at The University of the West Indies (UWI) using the Nook theme available on Wordpress. This project is a culinary recommendation and exploration engine designed to help users discover top-tier food vendors and interact with a unique decision-making game.

Current Project Status & Features
The project is currently in active development. The following core mechanics and custom templates have been successfully implemented and tested:

1. Custom Interactive Game (page-game.php) for the template and flavah-game for the plugin functionality.
A custom "This or That" style voting arena for food items.

Features interactive cards and dynamic logic to help users solve decision paralysis.

2. Visual Food Map Grid (page-food-map.php) for the template and flavah-map for the plugin functionality
A dedicated visual grid acting as a "map" of flavors.

Queries and displays all registered Vendor custom post types with high-quality venue images.

Includes custom CSS UI hover-zoom and card-lift interaction effects.

3. Custom Site-Wide Search System or flavah-search

Search overlay triggered by clicking on the magnifying glass icon.

Clean Query Filtering: Programmed via functions.php to bypass default WordPress sample pages.

Optimized Targeting: Configured to return Vendor custom post types directly when querying specific foods, streamlining the user journey.

Technical Stack & Architecture
Core: WordPress CMS (PHP)

Custom Post Types: Vendors, Food Items, Reviews

Iconography: Font Awesome 4.7.0 (Enqueued via functions.php)

Version Control: Git & GitHub (Used for maintaining a clean, timestamped development history)

Installation & Setup
Clone this repository into your local WordPress development environment's theme folder (/wp-content/themes/).

Activate the theme via the WordPress Dashboard under Appearance > Themes.

Ensure that the Advanced Custom Fields (ACF) or custom code for the 'Vendors', 'Food Items', and 'Reviews' post types are active to properly render the queries.

Move flavah-search, flavah-map, flavah-game plugin files to a folder named plugins in wp-content to access functionality.

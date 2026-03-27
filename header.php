<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="<?php bloginfo('charset'); ?>">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <header class="site-header">
        <div class="container">
            <h1 class="school-logo-text float-left">
                <a href="<?php echo esc_url(site_url()); ?>">
                    <strong>In Pursuit</strong> of Flavah
                </a>
            </h1>

            <span class="js-search-trigger site-header__search-trigger">
                <i class="fa fa-search" aria-hidden="true"></i>
            </span>

            <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>

            <div class="site-header__menu group">
                <nav class="main-navigation">
                    <ul>
                        <li><a href="<?php echo get_post_type_archive_link('vendor'); ?>">Vendors</a></li>
                        <li><a href="<?php echo get_post_type_archive_link('review'); ?>">Reviews</a></li>
                        <li><a href="<?php echo site_url('/food-map'); ?>">Food Map</a></li>
                        <li><a href="<?php echo site_url('/this-or-that'); ?>">The Game</a></li>
                    </ul>
                </nav>

                <div class="site-header__util">
                    <?php if (is_user_logged_in()) { ?>
                        <a href="<?php echo wp_logout_url(); ?>" class="btn btn--small btn--dark-orange float-left btn--with-photo">
                          <span class="site-header__avatar"><?php echo get_avatar(get_current_user_id(), 60); ?></span> 
                          <span class="btn__text">Log Out</span> 
                        </a>
                    <?php } else { ?>
                        <a href="<?php echo wp_login_url(); ?>" class="btn btn--small btn--orange float-left push-right">Login</a> 
                        <a href="<?php echo wp_registration_url(); ?>" class="btn btn--small btn--dark-orange float-left">Sign Up</a> 
                        <span class="search-trigger js-search-trigger">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </span>
                    <?php } ?>
                </div>
            </div>
        </div>
    </header>
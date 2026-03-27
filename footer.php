<footer class="site-footer">
    <div class="site-footer__inner container container--narrow">
      <div class="group">

        <div class="site-footer__col-one">
          <h1 class="school-logo-text school-logo-text--alt-color">
            <a href="<?php echo site_url() ?>"><strong>In Pursuit</strong> of Flavah</a>
          </h1>
          <p><a class="site-footer__link" href="#">868-FLAVAH-1</a></p>
        </div>

        <div class="site-footer__col-two-three-group">
          <div class="site-footer__col-two">
            <h3 class="headline headline--small">Explore</h3>
            <nav class="nav-list">
              <ul>
                <li><a href="<?php echo site_url('/about-us') ?>">About Us</a></li>
                <li><a href="<?php echo get_post_type_archive_link('vendor'); ?>">Vendors</a></li>
                <li><a href="<?php echo get_post_type_archive_link('review'); ?>">Reviews</a></li>
              </ul>
            </nav>
          </div>

          <div class="site-footer__col-three">
            <h3 class="headline headline--small">Learn</h3>
            <nav class="nav-list">
              <ul>
                <li><a href="<?php echo site_url('/privacy-policy') ?>">Privacy Policy</a></li>
                <li><a href="<?php echo site_url('/this-or-that') ?>">The Game</a></li>
                <li><a href="<?php echo site_url('/contact-us') ?>">Contact</a></li>
              </ul>
            </nav>
          </div>
        </div>

        <div class="site-footer__col-four">
          <h3 class="headline headline--small">Connect With Us</h3>
          <nav>
            <ul class="min-list social-icons-list group">
              <li><a href="#" class="social-color-facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
              <li><a href="#" class="social-color-twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
              <li><a href="#" class="social-color-instagram"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </footer>

<?php wp_footer(); ?>
</body>
</html>
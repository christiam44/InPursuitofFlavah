<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label style="width: 100%;">
        <span class="screen-reader-text">Search for:</span>
        <input type="search" class="search-field" placeholder="What are you craving?..." value="<?php echo get_search_query(); ?>" name="s" style="width: 100%; padding: 15px; font-size: 2rem; border: none; border-bottom: 3px solid #e67e22; outline: none; text-align: center; background: transparent; color: #fff;" />
    </label>
</form>
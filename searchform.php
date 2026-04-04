<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>" style="position: relative;">
    <label style="width: 100%;">
        <span class="screen-reader-text">Search for:</span>
        
        <input id="live-search-input" type="search" class="search-field" placeholder="What are you craving?..." value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" style="width: 100%; padding: 15px; font-size: 2rem; border: none; border-bottom: 3px solid #ff5722; outline: none; text-align: center; background: transparent; color: #fff;" />
    </label>

    <div id="live-search-results" style="position: absolute; top: 100%; left: 0; right: 0; background: #fff; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); max-height: 300px; overflow-y: auto; z-index: 999; display: none;"></div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('live-search-input');
    const resultsDiv = document.getElementById('live-search-results');

    if (!searchInput || !resultsDiv) return;

    searchInput.addEventListener('input', function() {
        const query = searchInput.value.trim();

        if (query.length < 2) {
            resultsDiv.style.display = 'none';
            resultsDiv.innerHTML = '';
            return;
        }

        // Setting up standard form data to securely hand over to PHP
        let formData = new FormData();
        formData.append('action', 'flavah_live_search');
        formData.append('keyword', query);

        // We fetch from the secure admin-ajax pathway rather than querying the open API!
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                let html = '';
                response.data.forEach(item => {
                    html += `
                        <a href="${item.link}" style="display: block; padding: 12px 15px; text-decoration: none; border-bottom: 1px solid #f5ede2; display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <strong style="color: #222; font-size: 1rem;">${item.title}</strong>
                                <div style="font-size: 0.8rem; color: #777; margin-top: 2px;">View details →</div>
                            </div>
                            <span style="background: #fff3f0; color: #ff5722; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase;">Food Item</span>
                        </a>
                    `;
                });
                resultsDiv.innerHTML = html;
                resultsDiv.style.display = 'block';
            } else {
                resultsDiv.innerHTML = '<div style="padding: 15px; color: #777; text-align: center;">No items found. Try another craving!</div>';
                resultsDiv.style.display = 'block';
            }
        })
        .catch(error => console.error('Search error:', error));
    });

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
            resultsDiv.style.display = 'none';
        }
    });
});
</script>
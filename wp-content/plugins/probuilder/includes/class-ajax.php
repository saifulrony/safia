<?php
/**
 * AJAX Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Ajax {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Save page data
        add_action('wp_ajax_probuilder_save_page', [$this, 'save_page']);
        
        // Page settings
        add_action('wp_ajax_probuilder_get_page_settings', [$this, 'get_page_settings']);
        add_action('wp_ajax_probuilder_save_page_settings', [$this, 'save_page_settings']);
        
        // Get element preview
        add_action('wp_ajax_probuilder_get_element_preview', [$this, 'get_element_preview']);
        
        // Quick View (no login required for public access)
        add_action('wp_ajax_pb_quick_view', [$this, 'quick_view']);
        add_action('wp_ajax_nopriv_pb_quick_view', [$this, 'quick_view']);
        
        // Create new page
        add_action('wp_ajax_probuilder_create_new_page', [$this, 'create_new_page']);
        
        // Upload image
        add_action('wp_ajax_probuilder_upload_image', [$this, 'upload_image']);
        
        // Get WooCommerce products for preview
        add_action('wp_ajax_probuilder_get_woo_products', [$this, 'get_woo_products']);
        
        // Get WooCommerce categories for preview
        add_action('wp_ajax_probuilder_get_woo_categories', [$this, 'get_woo_categories']);
        
        // Get blog posts for preview
        add_action('wp_ajax_probuilder_get_blog_posts', [$this, 'get_blog_posts']);
    }
    
    /**
     * Get page settings
     */
    public function get_page_settings() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => __('Permission denied', 'probuilder')]);
        }
        
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        
        if (!$post_id) {
            wp_send_json_error(['message' => __('Invalid post ID', 'probuilder')]);
        }
        
        $post = get_post($post_id);
        
        if (!$post) {
            wp_send_json_error(['message' => __('Post not found', 'probuilder')]);
        }
        
        wp_send_json_success([
            'title' => $post->post_title,
            'slug' => $post->post_name,
            'permalink' => get_permalink($post_id),
            'site_url' => home_url()
        ]);
    }
    
    /**
     * Save page settings (title and slug)
     */
    public function save_page_settings() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => __('Permission denied', 'probuilder')]);
        }
        
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $title = isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '';
        $slug = isset($_POST['slug']) ? sanitize_title($_POST['slug']) : '';
        
        if (!$post_id) {
            wp_send_json_error(['message' => __('Invalid post ID', 'probuilder')]);
        }
        
        if (empty($title)) {
            wp_send_json_error(['message' => __('Title cannot be empty', 'probuilder')]);
        }
        
        if (empty($slug)) {
            wp_send_json_error(['message' => __('URL cannot be empty', 'probuilder')]);
        }
        
        // Store original slug to check if it was changed
        $original_slug = $slug;
        
        // Check if slug already exists (for different post)
        $existing_post = get_page_by_path($slug, OBJECT, get_post_type($post_id));
        if ($existing_post && $existing_post->ID != $post_id) {
            // Slug exists, make it unique
            $slug = wp_unique_post_slug($slug, $post_id, get_post_status($post_id), get_post_type($post_id), 0);
            error_log("ProBuilder: Duplicate slug detected. Changed '$original_slug' to '$slug' for Post #$post_id");
        }
        
        // Update post
        $result = wp_update_post([
            'ID' => $post_id,
            'post_title' => $title,
            'post_name' => $slug
        ], true);
        
        if (is_wp_error($result)) {
            wp_send_json_error(['message' => $result->get_error_message()]);
        }
        
        // Flush rewrite rules to ensure URL changes take effect
        flush_rewrite_rules(false);
        
        // Clear post cache
        clean_post_cache($post_id);
        
        // Prepare success message
        $message = __('Page settings updated successfully!', 'probuilder');
        if ($original_slug !== $slug) {
            $message .= ' ' . __('Note: URL was changed to avoid duplicate.', 'probuilder');
        }
        
        wp_send_json_success([
            'message' => $message,
            'title' => $title,
            'slug' => $slug,
            'original_slug' => $original_slug,
            'slug_changed' => ($original_slug !== $slug),
            'permalink' => get_permalink($post_id)
        ]);
    }
    
    /**
     * Save page
     */
    public function save_page() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_send_json_error(['message' => __('Permission denied', 'probuilder')]);
        }
        
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $elements = isset($_POST['elements']) ? $_POST['elements'] : '[]';
        
        // Debug logging (only when WP_DEBUG is enabled)
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('ProBuilder Save - POST keys: ' . implode(', ', array_keys($_POST)));
            error_log('ProBuilder Save - Elements raw length: ' . (is_string($elements) ? strlen($elements) : 'not a string'));
        }
        
        if (!$post_id) {
            wp_send_json_error(['message' => __('Invalid post ID', 'probuilder')]);
        }
        
        // Ensure post exists and is editable
        $post = get_post($post_id);
        if (!$post) {
            wp_send_json_error(['message' => __('Post not found', 'probuilder')]);
        }
        
        // Parse and validate elements
        if (is_string($elements)) {
            // CRITICAL FIX: WordPress adds slashes to POST data, we need to remove them!
            // This prevents JSON decode errors when WordPress magic quotes are enabled
            $elements = stripslashes($elements);
            
            $decoded = json_decode($elements, true);
            
            if (json_last_error() === JSON_ERROR_NONE) {
                $elements = $decoded;
            } else {
                // If JSON decode fails, log error (only in debug mode)
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log('ProBuilder Save - JSON decode failed: ' . json_last_error_msg());
                }
                $elements = [];
            }
        }
        
        // Ensure elements is an array
        if (!is_array($elements)) {
            $elements = [];
        }
        
        // Debug logging (only when WP_DEBUG is enabled)
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('ProBuilder Save - Post ID: ' . $post_id);
            error_log('ProBuilder Save - Elements count: ' . count($elements));
            error_log('ProBuilder Save - Post type: ' . $post->post_type);
        }
        
        // Preserve custom post types (headers, footers, sliders, sidebars)
        // Only convert to page if it's a generic 'post' type
        $custom_part_types = ['pb_header', 'pb_footer', 'pb_slider', 'pb_sidebar', 'probuilder_part', 'pb_theme_template'];
        
        if ($post->post_type === 'post') {
            // If this content was created as a blog post by mistake, convert it to a Page
            wp_update_post([
                'ID' => $post_id,
                'post_type' => 'page',
            ]);
            // Refresh $post after type change
            $post = get_post($post_id);
        }
        // If it's already page or a custom part type, keep it as is
        // This preserves pb_header, pb_footer, pb_slider, pb_sidebar types

        // Save ProBuilder data (save as array, WordPress will serialize it)
        $updated = update_post_meta($post_id, '_probuilder_data', $elements);
        update_post_meta($post_id, '_probuilder_edit_mode', 'probuilder');
        
        // Ensure post has a unique slug before publishing
        $current_slug = $post->post_name;
        if (empty($current_slug) || $current_slug === 'auto-draft') {
            // Generate slug from title
            $current_slug = sanitize_title($post->post_title);
            if (empty($current_slug)) {
                $current_slug = 'page-' . $post_id;
            }
        }
        
        // Check for duplicate slugs and make unique if needed
        $existing_post = get_page_by_path($current_slug, OBJECT, $post->post_type);
        if ($existing_post && $existing_post->ID != $post_id) {
            $unique_slug = wp_unique_post_slug($current_slug, $post_id, 'publish', $post->post_type, 0);
            error_log("ProBuilder Save - Duplicate slug detected. Changed '$current_slug' to '$unique_slug' for Post #$post_id");
            $current_slug = $unique_slug;
        }
        
        // Ensure post status is published (not draft)
        if ($post->post_status === 'auto-draft' || $post->post_status === 'draft') {
            wp_update_post([
                'ID' => $post_id,
                'post_status' => 'publish',
                'post_name' => $current_slug,
                'post_modified' => current_time('mysql')
            ]);
            error_log('ProBuilder Save - Post status changed to publish');
        } else {
            // Just update modified date and ensure slug is set
            wp_update_post([
                'ID' => $post_id, 
                'post_name' => $current_slug,
                'post_modified' => current_time('mysql')
            ]);
        }
        
        // Do not flush rewrite rules on every save; it's expensive and unnecessary
        // Slug/permalink changes are already handled in save_page_settings()
        
        // Clear any cache
        clean_post_cache($post_id);
        
        // Get permalink for response
        $permalink = get_permalink($post_id);
        
        error_log('ProBuilder Save - Meta updated: ' . ($updated ? 'YES' : 'NO (same data)'));
        error_log('ProBuilder Save - Permalink: ' . $permalink);
        
        wp_send_json_success([
            'message' => __('Page saved successfully!', 'probuilder'),
            'permalink' => $permalink,
            'element_count' => count($elements)
        ]);
    }
    
    /**
     * Get element preview
     */
    public function get_element_preview() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        $widget_name = isset($_POST['widget_name']) ? sanitize_text_field($_POST['widget_name']) : '';
        $settings = isset($_POST['settings']) ? $_POST['settings'] : [];
        
        $widget = ProBuilder_Widgets_Manager::instance()->get_widget($widget_name);
        
        if (!$widget) {
            wp_send_json_error(['message' => __('Widget not found', 'probuilder')]);
        }
        
        ob_start();
        $widget->render_widget($settings);
        $html = ob_get_clean();
        
        wp_send_json_success(['html' => $html]);
    }
    
    /**
     * Upload image
     */
    public function upload_image() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('upload_files')) {
            wp_send_json_error(['message' => __('Permission denied', 'probuilder')]);
        }
        
        if (!isset($_FILES['file'])) {
            wp_send_json_error(['message' => __('No file uploaded', 'probuilder')]);
        }
        
        $file = $_FILES['file'];
        
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        
        $attachment_id = media_handle_upload('file', 0);
        
        if (is_wp_error($attachment_id)) {
            wp_send_json_error(['message' => $attachment_id->get_error_message()]);
        }
        
        $image_url = wp_get_attachment_url($attachment_id);
        
        wp_send_json_success([
            'id' => $attachment_id,
            'url' => $image_url
        ]);
    }
    
    /**
     * Get WooCommerce products for editor preview (with caching)
     */
    public function get_woo_products() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!class_exists('WooCommerce')) {
            wp_send_json_error(['message' => __('WooCommerce not active', 'probuilder')]);
        }
        
        $query_type = isset($_POST['query_type']) ? sanitize_text_field($_POST['query_type']) : 'recent';
        $per_page = min(isset($_POST['per_page']) ? intval($_POST['per_page']) : 8, 20); // Max 20 for performance
        $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'date';
        $order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) : 'DESC';
        
        // Check cache first (5 minute cache)
        $cache_key = 'probuilder_woo_' . md5($query_type . $per_page . $orderby . $order);
        $cached = get_transient($cache_key);
        
        if ($cached !== false) {
            wp_send_json_success($cached);
            return;
        }
        
        $args = [
            'post_type' => 'product',
            'posts_per_page' => $per_page,
            'post_status' => 'publish',
            'fields' => 'ids', // Only get IDs first for speed
        ];
        
        // Query modifications based on type
        switch ($query_type) {
            case 'featured':
                $args['tax_query'] = [
                    [
                        'taxonomy' => 'product_visibility',
                        'field' => 'name',
                        'terms' => 'featured',
                    ]
                ];
                $args['orderby'] = $orderby;
                $args['order'] = $order;
                break;
                
            case 'sale':
                $sale_ids = wc_get_product_ids_on_sale();
                if (!empty($sale_ids)) {
                    $args['post__in'] = array_slice($sale_ids, 0, $per_page);
                } else {
                    // No sale products, return empty
                    $result = ['products' => [], 'count' => 0];
                    set_transient($cache_key, $result, 300);
                    wp_send_json_success($result);
                    return;
                }
                unset($args['fields']); // Need full posts for sale check
                $args['orderby'] = $orderby;
                $args['order'] = $order;
                break;
                
            case 'best_selling':
                $args['meta_key'] = 'total_sales';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = $order; // Use user's order preference
                unset($args['fields']);
                break;
                
            case 'top_rated':
                $args['meta_key'] = '_wc_average_rating';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = $order; // Use user's order preference
                unset($args['fields']);
                break;
                
            default: // recent
                $args['orderby'] = $orderby;
                $args['order'] = $order;
                break;
        }
        
        $products_query = new WP_Query($args);
        $products = [];
        
        if ($products_query->have_posts()) {
            $ids = isset($args['fields']) ? $products_query->posts : wp_list_pluck($products_query->posts, 'ID');
            
            foreach ($ids as $id) {
                $product = wc_get_product($id);
                
                if ($product) {
                    $image_id = $product->get_image_id();
                    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : wc_placeholder_img_src('medium');
                    
                    // Clean price display - just show formatted price without extra text
                    $price_html = $product->get_price_html();
                    // Remove verbose text like "Original price was" and "Current price is"
                    $price_html = preg_replace('/Original price was:.*?\./i', '', $price_html);
                    $price_html = preg_replace('/Current price is:.*?\./i', '', $price_html);
                    $price_html = trim($price_html);
                    // If on sale, show sale price and regular price cleanly
                    if ($product->is_on_sale()) {
                        $regular_price = $product->get_regular_price();
                        $sale_price = $product->get_sale_price();
                        $price_html = '<span style="text-decoration: line-through; opacity: 0.6; margin-right: 8px;">' . wc_price($regular_price) . '</span><span>' . wc_price($sale_price) . '</span>';
                    } else {
                        $price_html = wc_price($product->get_price());
                    }
                    
                    $products[] = [
                        'id' => $product->get_id(),
                        'title' => $product->get_name(),
                        'price' => $price_html,
                        'image' => $image_url,
                        'permalink' => $product->get_permalink(),
                        'sale' => $product->is_on_sale(),
                        'rating' => round($product->get_average_rating()),
                    ];
                }
            }
        }
        
        wp_reset_postdata();
        
        $result = [
            'products' => $products,
            'count' => count($products)
        ];
        
        // Cache for 5 minutes
        set_transient($cache_key, $result, 300);
        
        wp_send_json_success($result);
    }
    
    /**
     * Get WooCommerce categories for editor preview (with caching)
     */
    public function get_woo_categories() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!class_exists('WooCommerce')) {
            wp_send_json_error(['message' => __('WooCommerce not active', 'probuilder')]);
        }
        
        $hide_empty = isset($_POST['hide_empty']) && $_POST['hide_empty'] === 'true';
        
        // Check cache first (5 minute cache)
        $cache_key = 'probuilder_woo_cat_' . ($hide_empty ? 'nonempty' : 'all');
        $cached = get_transient($cache_key);
        
        if ($cached !== false) {
            wp_send_json_success($cached);
            return;
        }
        
        $args = [
            'taxonomy' => 'product_cat',
            'hide_empty' => $hide_empty,
            'orderby' => 'name',
            'order' => 'ASC',
        ];
        
        $terms = get_terms($args);
        $categories = [];
        
        if (!is_wp_error($terms) && !empty($terms)) {
            foreach ($terms as $term) {
                $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
                $image = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : '';
                
                $categories[] = [
                    'id' => $term->term_id,
                    'name' => $term->name,
                    'count' => $term->count,
                    'image' => $image,
                    'link' => get_term_link($term),
                ];
            }
        }
        
        $result = [
            'categories' => $categories,
            'count' => count($categories)
        ];
        
        // Cache for 5 minutes
        set_transient($cache_key, $result, 300);
        
        wp_send_json_success($result);
    }
    
    /**
     * Get blog posts for editor preview (with caching)
     */
    public function get_blog_posts() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 6;
        $category = isset($_POST['category']) ? intval($_POST['category']) : 0;
        
        // Check cache first (5 minute cache)
        $cache_key = 'probuilder_blog_' . $per_page . '_' . $category;
        $cached = get_transient($cache_key);
        
        if ($cached !== false) {
            wp_send_json_success($cached);
            return;
        }
        
        $args = [
            'post_type' => 'post',
            'posts_per_page' => $per_page,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
        ];
        
        if ($category > 0) {
            $args['cat'] = $category;
        }
        
        $posts_query = get_posts($args);
        $posts = [];
        
        foreach ($posts_query as $post) {
            $thumbnail = get_the_post_thumbnail_url($post->ID, 'medium');
            if (!$thumbnail) {
                // Create simple colored placeholder
                $colors = ['#92003b', '#667eea', '#4facfe', '#764ba2', '#f093fb', '#00f2fe'];
                $color = $colors[abs(crc32($post->post_title)) % count($colors)];
                $thumbnail = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'800\' height=\'400\'%3E%3Crect fill=\'' . $color . '\' width=\'800\' height=\'400\'/%3E%3C/svg%3E';
            }
            
            $excerpt = $post->post_excerpt;
            if (empty($excerpt)) {
                $excerpt = wp_trim_words(strip_tags($post->post_content), 30);
            }
            
            $posts[] = [
                'id' => $post->ID,
                'title' => $post->post_title,
                'excerpt' => $excerpt,
                'image' => $thumbnail,
                'permalink' => get_permalink($post->ID),
                'date' => get_the_date('F j, Y', $post->ID),
                'author' => get_the_author_meta('display_name', $post->post_author),
            ];
        }
        
        $result = [
            'posts' => $posts,
            'count' => count($posts)
        ];
        
        // Cache for 5 minutes
        set_transient($cache_key, $result, 300);
        
        wp_send_json_success($result);
    }
    
    /**
     * Quick View AJAX Handler
     */
    public function quick_view() {
        $product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
        
        if (!$product_id || !class_exists('WooCommerce')) {
            echo '<div style="padding: 40px; text-align: center;"><p style="color: #ef4444;">Invalid product or WooCommerce not active.</p></div>';
            wp_die();
        }
        
        $product = wc_get_product($product_id);
        
        if (!$product) {
            echo '<div style="padding: 40px; text-align: center;"><p style="color: #ef4444;">Product not found.</p></div>';
            wp_die();
        }
        
        // Render product in modal
        ?>
        <div class="pb-quick-view-product" style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; padding: 20px;">
            
            <!-- Product Image -->
            <div class="pb-qv-image" style="position: relative;">
                <?php echo $product->get_image('large', ['style' => 'width: 100%; height: auto; border-radius: 8px;']); ?>
                
                <?php if ($product->is_on_sale()): ?>
                    <span style="position: absolute; top: 15px; left: 15px; background: #e74c3c; color: white; padding: 8px 16px; border-radius: 6px; font-weight: 600; font-size: 14px;">
                        Sale!
                    </span>
                <?php endif; ?>
            </div>
            
            <!-- Product Details -->
            <div class="pb-qv-details">
                <h2 style="margin: 0 0 15px; font-size: 28px; font-weight: 700; line-height: 1.3; color: #1f2937;">
                    <?php echo esc_html($product->get_name()); ?>
                </h2>
                
                <!-- Rating -->
                <?php if ($product->get_average_rating() > 0): ?>
                <div style="margin-bottom: 15px; color: #fbbf24; font-size: 16px;">
                    <?php
                    $rating = round($product->get_average_rating());
                    for ($i = 0; $i < 5; $i++) {
                        echo $i < $rating ? '★' : '☆';
                    }
                    ?>
                    <span style="color: #6b7280; font-size: 14px; margin-left: 8px;">
                        (<?php echo $product->get_review_count(); ?> reviews)
                    </span>
                </div>
                <?php endif; ?>
                
                <!-- Price -->
                <div style="margin-bottom: 25px; font-size: 32px; font-weight: 700; color: #92003b;">
                    <?php echo $product->get_price_html(); ?>
                </div>
                
                <!-- Short Description -->
                <div style="margin-bottom: 25px; color: #6b7280; line-height: 1.8; font-size: 15px;">
                    <?php echo wpautop($product->get_short_description()); ?>
                </div>
                
                <!-- Stock Status -->
                <div style="margin-bottom: 20px;">
                    <?php if ($product->is_in_stock()): ?>
                        <span style="color: #10b981; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                            <span style="width: 8px; height: 8px; background: #10b981; border-radius: 50%; display: inline-block;"></span>
                            In Stock
                        </span>
                    <?php else: ?>
                        <span style="color: #ef4444; font-weight: 600;">Out of Stock</span>
                    <?php endif; ?>
                </div>
                
                <!-- Add to Cart / Select Options -->
                <div style="margin-bottom: 20px;">
                    <?php if ($product->is_type('variable')): ?>
                        <a href="<?php echo esc_url($product->get_permalink()); ?>" class="button" style="display: inline-block; background: #92003b; color: white; padding: 16px 32px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; transition: all 0.3s;">
                            Select Options
                        </a>
                    <?php else: ?>
                        <form class="cart" method="post" enctype="multipart/form-data">
                            <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="single_add_to_cart_button button alt" style="background: #92003b; color: white; padding: 16px 32px; border: none; border-radius: 8px; font-weight: 600; font-size: 16px; cursor: pointer; width: 100%; transition: all 0.3s;">
                                Add to Cart
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
                
                <!-- View Full Details Link -->
                <a href="<?php echo esc_url($product->get_permalink()); ?>" style="color: #92003b; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; font-size: 14px;">
                    View Full Details
                    <span class="dashicons dashicons-arrow-right-alt" style="font-size: 18px;"></span>
                </a>
                
                <!-- Product Meta -->
                <?php if ($product->get_sku()): ?>
                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 13px; color: #6b7280;">
                    <strong>SKU:</strong> <?php echo esc_html($product->get_sku()); ?>
                </div>
                <?php endif; ?>
                
                <?php
                $categories = get_the_terms($product_id, 'product_cat');
                if ($categories && !is_wp_error($categories)):
                ?>
                <div style="margin-top: 10px; font-size: 13px; color: #6b7280;">
                    <strong>Category:</strong> 
                    <?php
                    $cat_names = array_map(function($cat) {
                        return $cat->name;
                    }, $categories);
                    echo implode(', ', $cat_names);
                    ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <style>
            .pb-quick-view-product .button:hover {
                background: #7a0031;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(146,0,59,0.3);
            }
            @media (max-width: 768px) {
                .pb-quick-view-product {
                    grid-template-columns: 1fr !important;
                }
            }
        </style>
        <?php
        
        wp_die();
    }
    
    /**
     * Create New Page
     */
    public function create_new_page() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('edit_pages')) {
            wp_send_json_error(['message' => __('Permission denied', 'probuilder')]);
        }
        
        $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'page';
        
        // Validate post type
        $allowed_types = ['page', 'post', 'pb_header', 'pb_footer', 'pb_slider', 'probuilder_part'];
        if (!in_array($post_type, $allowed_types)) {
            $post_type = 'page';
        }
        
        // Create appropriate title based on post type
        $unique_id = substr(uniqid(), -6);
        $titles = [
            'page' => sprintf(__('New Page %s', 'probuilder'), $unique_id),
            'post' => sprintf(__('New Post %s', 'probuilder'), $unique_id),
            'pb_header' => sprintf(__('New Header %s', 'probuilder'), $unique_id),
            'pb_footer' => sprintf(__('New Footer %s', 'probuilder'), $unique_id),
            'pb_slider' => sprintf(__('New Slider %s', 'probuilder'), $unique_id),
            'probuilder_part' => sprintf(__('New Part %s', 'probuilder'), $unique_id),
        ];
        
        $post_title = isset($titles[$post_type]) ? $titles[$post_type] : sprintf(__('New %s %s', 'probuilder'), ucfirst($post_type), $unique_id);
        
        // Create new post
        $post_id = wp_insert_post([
            'post_type'   => $post_type,
            'post_status' => 'draft',
            'post_title'  => $post_title,
            'post_author' => get_current_user_id(),
        ]);
        
        if (is_wp_error($post_id) || !$post_id) {
            wp_send_json_error(['message' => __('Failed to create new page', 'probuilder')]);
        }
        
        // Build editor URL
        $editor_url = add_query_arg([
            'p' => $post_id,
            'probuilder' => 'true',
            'post_type' => $post_type,
        ], home_url('/'));
        
        wp_send_json_success([
            'message' => __('New page created successfully!', 'probuilder'),
            'post_id' => $post_id,
            'editor_url' => $editor_url,
            'post_type' => $post_type,
        ]);
    }
}


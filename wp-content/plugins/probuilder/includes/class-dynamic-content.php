<?php
/**
 * Dynamic Content System
 * ACF, Custom Fields, and WordPress dynamic data
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Dynamic_Content {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('wp_ajax_probuilder_get_dynamic_tags', [$this, 'ajax_get_dynamic_tags']);
        add_filter('probuilder_render_content', [$this, 'parse_dynamic_tags'], 10, 2);
    }
    
    /**
     * Get available dynamic tags
     */
    public function get_dynamic_tags() {
        $tags = [
            'post' => [
                'title' => __('Post', 'probuilder'),
                'tags' => [
                    'post_title' => __('Post Title', 'probuilder'),
                    'post_content' => __('Post Content', 'probuilder'),
                    'post_excerpt' => __('Post Excerpt', 'probuilder'),
                    'post_date' => __('Post Date', 'probuilder'),
                    'post_author' => __('Post Author', 'probuilder'),
                    'post_author_name' => __('Author Name', 'probuilder'),
                    'post_author_bio' => __('Author Bio', 'probuilder'),
                    'post_featured_image' => __('Featured Image', 'probuilder'),
                    'post_url' => __('Post URL', 'probuilder'),
                    'post_id' => __('Post ID', 'probuilder'),
                ]
            ],
            'site' => [
                'title' => __('Site', 'probuilder'),
                'tags' => [
                    'site_title' => __('Site Title', 'probuilder'),
                    'site_tagline' => __('Site Tagline', 'probuilder'),
                    'site_url' => __('Site URL', 'probuilder'),
                    'site_logo' => __('Site Logo', 'probuilder'),
                    'current_date' => __('Current Date', 'probuilder'),
                    'current_time' => __('Current Time', 'probuilder'),
                    'current_year' => __('Current Year', 'probuilder'),
                ]
            ],
            'user' => [
                'title' => __('User', 'probuilder'),
                'tags' => [
                    'user_name' => __('User Name', 'probuilder'),
                    'user_email' => __('User Email', 'probuilder'),
                    'user_id' => __('User ID', 'probuilder'),
                    'user_login' => __('User Login', 'probuilder'),
                    'user_display_name' => __('User Display Name', 'probuilder'),
                ]
            ],
            'custom_field' => [
                'title' => __('Custom Field', 'probuilder'),
                'tags' => [
                    'custom_field' => __('Custom Field', 'probuilder'),
                    'acf_field' => __('ACF Field', 'probuilder'),
                ]
            ],
        ];
        
        // Add WooCommerce tags if available
        if (class_exists('WooCommerce')) {
            $tags['woocommerce'] = [
                'title' => __('WooCommerce', 'probuilder'),
                'tags' => [
                    'product_price' => __('Product Price', 'probuilder'),
                    'product_sku' => __('Product SKU', 'probuilder'),
                    'product_rating' => __('Product Rating', 'probuilder'),
                    'product_stock' => __('Product Stock', 'probuilder'),
                    'product_gallery' => __('Product Gallery', 'probuilder'),
                ]
            ];
        }
        
        return apply_filters('probuilder_dynamic_tags', $tags);
    }
    
    /**
     * AJAX: Get dynamic tags
     */
    public function ajax_get_dynamic_tags() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        wp_send_json_success($this->get_dynamic_tags());
    }
    
    /**
     * Parse dynamic tags in content
     */
    public function parse_dynamic_tags($content, $post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        
        // Match pattern: {{tag_name:parameter}}
        preg_match_all('/\{\{([^:}]+)(?::([^}]+))?\}\}/', $content, $matches);
        
        if (!empty($matches[0])) {
            foreach ($matches[0] as $index => $full_tag) {
                $tag = $matches[1][$index];
                $param = isset($matches[2][$index]) ? $matches[2][$index] : '';
                
                $replacement = $this->get_tag_value($tag, $param, $post_id);
                $content = str_replace($full_tag, $replacement, $content);
            }
        }
        
        return $content;
    }
    
    /**
     * Get tag value
     */
    private function get_tag_value($tag, $param = '', $post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        
        switch ($tag) {
            // Post tags
            case 'post_title':
                return get_the_title($post_id);
            case 'post_content':
                return get_post_field('post_content', $post_id);
            case 'post_excerpt':
                return get_the_excerpt($post_id);
            case 'post_date':
                return get_the_date('', $post_id);
            case 'post_author':
                return get_the_author_meta('display_name', get_post_field('post_author', $post_id));
            case 'post_author_name':
                return get_the_author_meta('display_name', get_post_field('post_author', $post_id));
            case 'post_author_bio':
                return get_the_author_meta('description', get_post_field('post_author', $post_id));
            case 'post_featured_image':
                return get_the_post_thumbnail_url($post_id, 'full');
            case 'post_url':
                return get_permalink($post_id);
            case 'post_id':
                return $post_id;
                
            // Site tags
            case 'site_title':
                return get_bloginfo('name');
            case 'site_tagline':
                return get_bloginfo('description');
            case 'site_url':
                return home_url();
            case 'site_logo':
                return get_custom_logo();
            case 'current_date':
                return date_i18n(get_option('date_format'));
            case 'current_time':
                return date_i18n(get_option('time_format'));
            case 'current_year':
                return date('Y');
                
            // User tags
            case 'user_name':
                $user = wp_get_current_user();
                return $user->display_name;
            case 'user_email':
                $user = wp_get_current_user();
                return $user->user_email;
            case 'user_id':
                return get_current_user_id();
            case 'user_login':
                $user = wp_get_current_user();
                return $user->user_login;
            case 'user_display_name':
                $user = wp_get_current_user();
                return $user->display_name;
                
            // Custom field
            case 'custom_field':
                return get_post_meta($post_id, $param, true);
            case 'acf_field':
                if (function_exists('get_field')) {
                    return get_field($param, $post_id);
                }
                return '';
                
            // WooCommerce tags
            case 'product_price':
                if (class_exists('WooCommerce')) {
                    $product = wc_get_product($post_id);
                    return $product ? $product->get_price_html() : '';
                }
                return '';
            case 'product_sku':
                if (class_exists('WooCommerce')) {
                    $product = wc_get_product($post_id);
                    return $product ? $product->get_sku() : '';
                }
                return '';
            case 'product_rating':
                if (class_exists('WooCommerce')) {
                    $product = wc_get_product($post_id);
                    return $product ? $product->get_average_rating() : '';
                }
                return '';
                
            default:
                return apply_filters('probuilder_dynamic_tag_value', '', $tag, $param, $post_id);
        }
    }
}


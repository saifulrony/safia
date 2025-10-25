<?php
/**
 * Assets Optimizer Class
 * Handles CSS/JS minification, combination, and conditional loading
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Assets_Optimizer {
    
    private static $instance = null;
    
    /**
     * Required widgets for current page
     */
    private $required_widgets = [];
    
    /**
     * Inline CSS buffer
     */
    private $inline_css = [];
    
    /**
     * Loaded assets
     */
    private $loaded_assets = [];
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        // Optimize scripts and styles
        add_action('wp_enqueue_scripts', [$this, 'optimize_assets'], 999);
        
        // Add inline critical CSS
        add_action('wp_head', [$this, 'add_critical_css'], 5);
        
        // Defer non-critical JS
        add_filter('script_loader_tag', [$this, 'defer_scripts'], 10, 3);
        
        // Preload critical assets
        add_action('wp_head', [$this, 'preload_assets'], 1);
        
        // Minify inline CSS
        add_filter('probuilder_inline_css', [$this, 'minify_css']);
        
        // Combine widget-specific assets
        add_action('wp_footer', [$this, 'combine_widget_assets'], 1);
    }
    
    /**
     * Optimize assets loading
     */
    public function optimize_assets() {
        global $post;
        
        if (!is_singular() || !$post) {
            return;
        }
        
        $probuilder_data = get_post_meta($post->ID, '_probuilder_data', true);
        
        if (empty($probuilder_data)) {
            return;
        }
        
        // Get required widgets from page data
        $this->required_widgets = $this->extract_required_widgets($probuilder_data);
        
        // Conditionally load widget-specific assets
        $this->load_widget_assets();
        
        // Combine and minify CSS
        $this->combine_css();
    }
    
    /**
     * Extract required widgets from page data
     */
    private function extract_required_widgets($elements) {
        $widgets = [];
        
        if (!is_array($elements)) {
            $elements = json_decode($elements, true);
        }
        
        foreach ($elements as $element) {
            if (isset($element['widgetType'])) {
                $widgets[$element['widgetType']] = true;
            }
            
            // Check children recursively
            if (isset($element['children']) && !empty($element['children'])) {
                $child_widgets = $this->extract_required_widgets($element['children']);
                $widgets = array_merge($widgets, $child_widgets);
            }
        }
        
        return array_keys($widgets);
    }
    
    /**
     * Load widget-specific assets conditionally
     */
    private function load_widget_assets() {
        $widget_assets = [
            'carousel' => [
                'css' => ['swiper'],
                'js' => ['swiper'],
            ],
            'slider' => [
                'css' => ['swiper'],
                'js' => ['swiper'],
            ],
            'gallery' => [
                'js' => ['lightbox'],
            ],
            'countdown' => [
                'js' => ['countdown'],
            ],
            'map' => [
                'js' => ['google-maps'],
            ],
            'animated-headline' => [
                'js' => ['animated-text'],
            ],
        ];
        
        foreach ($this->required_widgets as $widget) {
            if (isset($widget_assets[$widget])) {
                $assets = $widget_assets[$widget];
                
                // Load CSS
                if (isset($assets['css'])) {
                    foreach ($assets['css'] as $css) {
                        $this->enqueue_optimized_css($css);
                    }
                }
                
                // Load JS
                if (isset($assets['js'])) {
                    foreach ($assets['js'] as $js) {
                        $this->enqueue_optimized_js($js);
                    }
                }
            }
        }
    }
    
    /**
     * Enqueue optimized CSS
     */
    private function enqueue_optimized_css($handle) {
        if (isset($this->loaded_assets['css'][$handle])) {
            return;
        }
        
        $css_file = PROBUILDER_PATH . "assets/css/{$handle}.css";
        
        if (file_exists($css_file)) {
            // Check for minified version
            $min_file = PROBUILDER_PATH . "assets/css/{$handle}.min.css";
            $url = file_exists($min_file) 
                ? PROBUILDER_URL . "assets/css/{$handle}.min.css"
                : PROBUILDER_URL . "assets/css/{$handle}.css";
            
            wp_enqueue_style("probuilder-{$handle}", $url, [], PROBUILDER_VERSION);
            $this->loaded_assets['css'][$handle] = true;
        }
    }
    
    /**
     * Enqueue optimized JS
     */
    private function enqueue_optimized_js($handle) {
        if (isset($this->loaded_assets['js'][$handle])) {
            return;
        }
        
        $js_file = PROBUILDER_PATH . "assets/js/{$handle}.js";
        
        if (file_exists($js_file)) {
            // Check for minified version
            $min_file = PROBUILDER_PATH . "assets/js/{$handle}.min.js";
            $url = file_exists($min_file)
                ? PROBUILDER_URL . "assets/js/{$handle}.min.js"
                : PROBUILDER_URL . "assets/js/{$handle}.js";
            
            wp_enqueue_script("probuilder-{$handle}", $url, ['jquery'], PROBUILDER_VERSION, true);
            $this->loaded_assets['js'][$handle] = true;
        }
    }
    
    /**
     * Combine and minify CSS
     */
    private function combine_css() {
        global $post;
        
        if (!$post) {
            return;
        }
        
        // Check if combined CSS exists in cache
        $cache = ProBuilder_Cache::instance();
        $cache_key = 'combined_css_' . $post->ID;
        $combined_css = $cache->get($cache_key, ProBuilder_Cache::ASSET_CACHE_GROUP);
        
        if ($combined_css !== false) {
            wp_add_inline_style('probuilder-frontend', $combined_css);
            return;
        }
        
        // Collect all ProBuilder CSS
        $css_content = '';
        
        foreach ($this->inline_css as $css) {
            $css_content .= $css . "\n";
        }
        
        // Minify combined CSS
        $css_content = $this->minify_css($css_content);
        
        // Cache combined CSS
        $cache->set($cache_key, $css_content, ProBuilder_Cache::ASSET_CACHE_GROUP, ProBuilder_Cache::ASSET_CACHE_EXPIRATION);
        
        // Add inline
        wp_add_inline_style('probuilder-frontend', $css_content);
    }
    
    /**
     * Minify CSS
     */
    public function minify_css($css) {
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Remove whitespace
        $css = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $css);
        
        // Remove spaces around colons, semicolons, braces
        $css = str_replace([' {', '{ ', ' }', '; ', ': '], ['{', '{', '}', ';', ':'], $css);
        
        // Remove last semicolon in a block
        $css = str_replace(';}', '}', $css);
        
        return trim($css);
    }
    
    /**
     * Minify JS
     */
    public function minify_js($js) {
        // Remove single-line comments
        $js = preg_replace('~//[^\n]*~', '', $js);
        
        // Remove multi-line comments
        $js = preg_replace('~/\*.*?\*/~s', '', $js);
        
        // Remove whitespace (preserve strings)
        $js = preg_replace('~\s+~', ' ', $js);
        
        return trim($js);
    }
    
    /**
     * Add critical CSS to head
     */
    public function add_critical_css() {
        global $post;
        
        if (!is_singular() || !$post) {
            return;
        }
        
        $probuilder_data = get_post_meta($post->ID, '_probuilder_data', true);
        
        if (empty($probuilder_data)) {
            return;
        }
        
        // Critical CSS for above-the-fold content
        $critical_css = '
            .probuilder-content { display: block; }
            .probuilder-element { position: relative; }
            .probuilder-widget-container { width: 100%; }
            .probuilder-loading { opacity: 0; }
        ';
        
        echo '<style id="probuilder-critical-css">' . $this->minify_css($critical_css) . '</style>';
    }
    
    /**
     * Defer non-critical scripts
     */
    public function defer_scripts($tag, $handle, $src) {
        // Don't defer jQuery or critical scripts
        $critical_handles = ['jquery', 'probuilder-editor'];
        
        if (in_array($handle, $critical_handles)) {
            return $tag;
        }
        
        // Defer ProBuilder scripts
        if (strpos($handle, 'probuilder-') === 0) {
            return str_replace(' src', ' defer src', $tag);
        }
        
        return $tag;
    }
    
    /**
     * Preload critical assets
     */
    public function preload_assets() {
        global $post;
        
        if (!is_singular() || !$post) {
            return;
        }
        
        $probuilder_data = get_post_meta($post->ID, '_probuilder_data', true);
        
        if (empty($probuilder_data)) {
            return;
        }
        
        // Preload main CSS
        echo '<link rel="preload" href="' . esc_url(PROBUILDER_URL . 'assets/css/frontend.css') . '" as="style">';
        
        // Preload main JS
        echo '<link rel="preload" href="' . esc_url(PROBUILDER_URL . 'assets/js/frontend.js') . '" as="script">';
        
        // Preload fonts if used
        $this->preload_fonts();
    }
    
    /**
     * Preload custom fonts
     */
    private function preload_fonts() {
        $fonts = apply_filters('probuilder_preload_fonts', []);
        
        foreach ($fonts as $font) {
            echo '<link rel="preload" href="' . esc_url($font) . '" as="font" type="font/woff2" crossorigin>';
        }
    }
    
    /**
     * Combine widget-specific assets
     */
    public function combine_widget_assets() {
        if (empty($this->required_widgets)) {
            return;
        }
        
        // Generate combined asset key
        $widgets_hash = md5(implode(',', $this->required_widgets));
        $cache_key = 'widget_assets_' . $widgets_hash;
        
        // Check cache
        $cache = ProBuilder_Cache::instance();
        $cached_assets = $cache->get($cache_key, ProBuilder_Cache::ASSET_CACHE_GROUP);
        
        if ($cached_assets !== false) {
            echo $cached_assets;
            return;
        }
        
        // Build combined assets output
        $output = '<!-- ProBuilder Optimized Assets -->';
        
        // Cache the output
        $cache->set($cache_key, $output, ProBuilder_Cache::ASSET_CACHE_GROUP, ProBuilder_Cache::ASSET_CACHE_EXPIRATION);
        
        echo $output;
    }
    
    /**
     * Add inline CSS
     */
    public function add_inline_css($css) {
        $this->inline_css[] = $css;
    }
    
    /**
     * Get asset dependencies for widgets
     */
    public function get_widget_dependencies($widget_type) {
        $dependencies = [
            'carousel' => ['swiper'],
            'slider' => ['swiper'],
            'gallery' => ['lightbox'],
            'countdown' => ['countdown-timer'],
            'map' => ['google-maps-api'],
            'video' => ['plyr'],
            'animated-headline' => ['animated-text'],
        ];
        
        return isset($dependencies[$widget_type]) ? $dependencies[$widget_type] : [];
    }
    
    /**
     * Generate minified file if not exists
     */
    public function generate_minified_files() {
        $css_dir = PROBUILDER_PATH . 'assets/css/';
        $js_dir = PROBUILDER_PATH . 'assets/js/';
        
        // Minify CSS files
        $css_files = glob($css_dir . '*.css');
        foreach ($css_files as $file) {
            if (strpos($file, '.min.css') !== false) {
                continue;
            }
            
            $min_file = str_replace('.css', '.min.css', $file);
            
            if (!file_exists($min_file)) {
                $css_content = file_get_contents($file);
                $minified = $this->minify_css($css_content);
                file_put_contents($min_file, $minified);
            }
        }
        
        // Minify JS files
        $js_files = glob($js_dir . '*.js');
        foreach ($js_files as $file) {
            if (strpos($file, '.min.js') !== false) {
                continue;
            }
            
            $min_file = str_replace('.js', '.min.js', $file);
            
            if (!file_exists($min_file)) {
                $js_content = file_get_contents($file);
                $minified = $this->minify_js($js_content);
                file_put_contents($min_file, $minified);
            }
        }
    }
    
    /**
     * Check if optimization is enabled
     */
    public function is_optimization_enabled() {
        return apply_filters('probuilder_optimize_assets', true);
    }
}


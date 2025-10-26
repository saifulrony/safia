<?php
/**
 * Popup Builder System
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Popup_Builder {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', [$this, 'register_popup_post_type']);
        add_action('wp_ajax_probuilder_save_popup', [$this, 'ajax_save_popup']);
        add_action('wp_ajax_probuilder_get_popups', [$this, 'ajax_get_popups']);
        add_action('wp_footer', [$this, 'render_popups']);
        add_action('admin_menu', [$this, 'add_popup_menu']);
    }
    
    /**
     * Register popup post type
     */
    public function register_popup_post_type() {
        register_post_type('probuilder_popup', [
            'labels' => [
                'name' => __('Popups', 'probuilder'),
                'singular_name' => __('Popup', 'probuilder'),
                'add_new' => __('Add New Popup', 'probuilder'),
                'add_new_item' => __('Add New Popup', 'probuilder'),
                'edit_item' => __('Edit Popup', 'probuilder'),
            ],
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => 'probuilder',
            'supports' => ['title'],
            'capability_type' => 'page',
        ]);
    }
    
    /**
     * Add popup menu
     */
    public function add_popup_menu() {
        add_submenu_page(
            'probuilder',
            __('Popups', 'probuilder'),
            __('Popups', 'probuilder'),
            'edit_pages',
            'edit.php?post_type=probuilder_popup'
        );
    }
    
    /**
     * Save popup
     */
    public function ajax_save_popup() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        $popup_id = intval($_POST['popup_id']);
        $data = json_decode(stripslashes($_POST['data']), true);
        $settings = json_decode(stripslashes($_POST['settings']), true);
        
        update_post_meta($popup_id, '_probuilder_data', $data);
        update_post_meta($popup_id, '_probuilder_popup_settings', $settings);
        
        wp_send_json_success(['message' => 'Popup saved']);
    }
    
    /**
     * Get popups
     */
    public function ajax_get_popups() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        $popups = get_posts([
            'post_type' => 'probuilder_popup',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ]);
        
        $popup_list = [];
        foreach ($popups as $popup) {
            $popup_list[] = [
                'id' => $popup->ID,
                'title' => $popup->post_title,
                'settings' => get_post_meta($popup->ID, '_probuilder_popup_settings', true)
            ];
        }
        
        wp_send_json_success($popup_list);
    }
    
    /**
     * Render active popups
     */
    public function render_popups() {
        $popups = get_posts([
            'post_type' => 'probuilder_popup',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ]);
        
        foreach ($popups as $popup) {
            $settings = get_post_meta($popup->ID, '_probuilder_popup_settings', true);
            $data = get_post_meta($popup->ID, '_probuilder_data', true);
            
            if (!$this->should_show_popup($settings)) {
                continue;
            }
            
            $this->render_popup($popup->ID, $data, $settings);
        }
    }
    
    /**
     * Check if popup should be shown
     */
    private function should_show_popup($settings) {
        if (!$settings) {
            return false;
        }
        
        // Check display rules
        $display_on = $settings['display_on'] ?? 'all';
        
        if ($display_on === 'homepage' && !is_front_page()) {
            return false;
        }
        
        if ($display_on === 'posts' && !is_single()) {
            return false;
        }
        
        if ($display_on === 'pages' && !is_page()) {
            return false;
        }
        
        // Check if user has dismissed
        if (isset($settings['dismissible']) && $settings['dismissible']) {
            if (isset($_COOKIE['pb_popup_dismissed_' . $settings['id']])) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Render popup HTML
     */
    private function render_popup($popup_id, $data, $settings) {
        $trigger = $settings['trigger'] ?? 'page_load';
        $delay = $settings['delay'] ?? 3;
        $width = $settings['width'] ?? '600px';
        $animation = $settings['animation'] ?? 'fadeIn';
        
        ?>
        <div class="probuilder-popup" 
             id="pb-popup-<?php echo esc_attr($popup_id); ?>"
             data-trigger="<?php echo esc_attr($trigger); ?>"
             data-delay="<?php echo esc_attr($delay); ?>"
             style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 999999; align-items: center; justify-content: center;">
            
            <div class="popup-content" style="background: #fff; max-width: <?php echo esc_attr($width); ?>; width: 90%; max-height: 90vh; overflow-y: auto; border-radius: 8px; position: relative; padding: 40px;">
                <button class="popup-close" style="position: absolute; top: 15px; right: 15px; background: transparent; border: none; font-size: 24px; cursor: pointer; color: #333;">&times;</button>
                
                <?php
                // Render popup content
                if ($data && is_array($data)) {
                    foreach ($data as $element) {
                        ProBuilder_Frontend::instance()->render_element($element);
                    }
                }
                ?>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            const popup = $('#pb-popup-<?php echo esc_js($popup_id); ?>');
            const trigger = popup.data('trigger');
            const delay = popup.data('delay') * 1000;
            
            function showPopup() {
                popup.css('display', 'flex').hide().fadeIn(400);
            }
            
            // Trigger logic
            if (trigger === 'page_load') {
                setTimeout(showPopup, delay);
            } else if (trigger === 'exit_intent') {
                $(document).on('mouseleave', function(e) {
                    if (e.clientY < 50) {
                        showPopup();
                    }
                });
            } else if (trigger === 'scroll') {
                $(window).on('scroll', function() {
                    if ($(window).scrollTop() > $(document).height() * 0.5) {
                        showPopup();
                        $(window).off('scroll');
                    }
                });
            }
            
            // Close popup
            popup.find('.popup-close, .popup-overlay').on('click', function() {
                popup.fadeOut(300);
                
                <?php if ($settings['dismissible'] ?? false): ?>
                document.cookie = 'pb_popup_dismissed_<?php echo esc_js($popup_id); ?>=1; max-age=<?php echo esc_js($settings['dismiss_duration'] ?? 86400); ?>; path=/';
                <?php endif; ?>
            });
        });
        </script>
        <?php
    }
}


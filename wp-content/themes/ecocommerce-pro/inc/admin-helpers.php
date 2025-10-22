<?php
/**
 * EcoCommerce Pro - Admin Helper Functions
 * Visual UI components and helpers for theme options
 * 
 * @package EcoCommerce_Pro
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Visual Radio Buttons with Images
 */
function ecocommerce_pro_visual_radio($args) {
    $name = $args['name'];
    $value = $args['value'] ?? '';
    $options = $args['options'] ?? array();
    $class = $args['class'] ?? '';
    
    echo '<div class="visual-radio-group ' . esc_attr($class) . '">';
    
    foreach ($options as $option_value => $option_data) {
        $checked = checked($value, $option_value, false);
        $option_label = $option_data['label'] ?? $option_value;
        $option_image = $option_data['image'] ?? '';
        $option_desc = $option_data['description'] ?? '';
        
        echo '<label class="visual-radio-option ' . ($checked ? 'selected' : '') . '">';
        echo '<input type="radio" name="' . esc_attr($name) . '" value="' . esc_attr($option_value) . '" ' . $checked . ' />';
        
        if ($option_image) {
            echo '<div class="visual-radio-image">';
            echo '<img src="' . esc_url($option_image) . '" alt="' . esc_attr($option_label) . '" />';
            echo '</div>';
        }
        
        echo '<div class="visual-radio-label">';
        echo '<strong>' . esc_html($option_label) . '</strong>';
        if ($option_desc) {
            echo '<span class="visual-radio-desc">' . esc_html($option_desc) . '</span>';
        }
        echo '</div>';
        
        echo '<span class="visual-radio-check">✓</span>';
        echo '</label>';
    }
    
    echo '</div>';
}

/**
 * Icon Option Selector
 */
function ecocommerce_pro_icon_selector($args) {
    $name = $args['name'];
    $value = $args['value'] ?? '';
    $options = $args['options'] ?? array();
    
    echo '<div class="icon-selector-group">';
    
    foreach ($options as $option_value => $option_data) {
        $checked = checked($value, $option_value, false);
        $icon = $option_data['icon'] ?? '📌';
        $label = $option_data['label'] ?? $option_value;
        
        echo '<label class="icon-option ' . ($checked ? 'selected' : '') . '" title="' . esc_attr($label) . '">';
        echo '<input type="radio" name="' . esc_attr($name) . '" value="' . esc_attr($option_value) . '" ' . $checked . ' />';
        echo '<span class="icon-option-icon">' . $icon . '</span>';
        echo '<span class="icon-option-label">' . esc_html($label) . '</span>';
        echo '</label>';
    }
    
    echo '</div>';
}

/**
 * Toggle Switch Component
 */
function ecocommerce_pro_toggle_switch($args) {
    $name = $args['name'];
    $checked = $args['checked'] ?? false;
    $label = $args['label'] ?? '';
    $description = $args['description'] ?? '';
    
    $unique_id = 'toggle_' . md5($name);
    
    echo '<div class="toggle-switch-wrapper">';
    echo '<label class="toggle-switch" for="' . esc_attr($unique_id) . '">';
    echo '<input type="checkbox" id="' . esc_attr($unique_id) . '" name="' . esc_attr($name) . '" value="1" ' . checked($checked, true, false) . ' />';
    echo '<span class="toggle-slider"></span>';
    echo '</label>';
    
    if ($label) {
        echo '<label for="' . esc_attr($unique_id) . '" class="toggle-label">';
        echo '<strong>' . esc_html($label) . '</strong>';
        if ($description) {
            echo '<span class="toggle-description">' . esc_html($description) . '</span>';
        }
        echo '</label>';
    }
    
    echo '</div>';
}

/**
 * Color Scheme Preview
 */
function ecocommerce_pro_color_preview($colors) {
    echo '<div class="color-scheme-preview">';
    echo '<div class="color-preview-mockup">';
    echo '<div class="preview-header" style="background: ' . esc_attr($colors['primary'] ?? '#2563eb') . ';">';
    echo '<div class="preview-logo">LOGO</div>';
    echo '<div class="preview-menu">';
    echo '<span></span><span></span><span></span>';
    echo '</div>';
    echo '</div>';
    echo '<div class="preview-content">';
    echo '<div class="preview-button" style="background: ' . esc_attr($colors['primary'] ?? '#2563eb') . ';">Button</div>';
    echo '<div class="preview-text" style="color: ' . esc_attr($colors['text'] ?? '#333') . ';">Sample text content</div>';
    echo '<div class="preview-link" style="color: ' . esc_attr($colors['primary'] ?? '#2563eb') . ';">Link Example</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

/**
 * Layout Preview Component
 */
function ecocommerce_pro_layout_preview($layout_type) {
    $layouts = array(
        'full-width' => array(
            'label' => 'Full Width',
            'svg' => '<svg viewBox="0 0 200 150"><rect x="10" y="10" width="180" height="30" fill="#2563eb"/><rect x="10" y="50" width="180" height="80" fill="#e5e7eb"/></svg>'
        ),
        'boxed' => array(
            'label' => 'Boxed',
            'svg' => '<svg viewBox="0 0 200 150"><rect x="30" y="10" width="140" height="30" fill="#2563eb"/><rect x="30" y="50" width="140" height="80" fill="#e5e7eb"/></svg>'
        ),
        'sidebar-left' => array(
            'label' => 'Sidebar Left',
            'svg' => '<svg viewBox="0 0 200 150"><rect x="10" y="10" width="180" height="30" fill="#2563eb"/><rect x="10" y="50" width="50" height="80" fill="#94a3b8"/><rect x="70" y="50" width="120" height="80" fill="#e5e7eb"/></svg>'
        ),
        'sidebar-right' => array(
            'label' => 'Sidebar Right',
            'svg' => '<svg viewBox="0 0 200 150"><rect x="10" y="10" width="180" height="30" fill="#2563eb"/><rect x="10" y="50" width="120" height="80" fill="#e5e7eb"/><rect x="140" y="50" width="50" height="80" fill="#94a3b8"/></svg>'
        ),
    );
    
    if (isset($layouts[$layout_type])) {
        echo '<div class="layout-preview-item">';
        echo '<div class="layout-svg">' . $layouts[$layout_type]['svg'] . '</div>';
        echo '<div class="layout-label">' . esc_html($layouts[$layout_type]['label']) . '</div>';
        echo '</div>';
    }
}

/**
 * Info Box Component
 */
function ecocommerce_pro_info_box($args) {
    $type = $args['type'] ?? 'info'; // info, success, warning, error
    $title = $args['title'] ?? '';
    $content = $args['content'] ?? '';
    $icon = $args['icon'] ?? '💡';
    
    $colors = array(
        'info' => array('bg' => '#dbeafe', 'border' => '#2563eb', 'text' => '#1e40af'),
        'success' => array('bg' => '#d1fae5', 'border' => '#10b981', 'text' => '#065f46'),
        'warning' => array('bg' => '#fef3c7', 'border' => '#f59e0b', 'text' => '#92400e'),
        'error' => array('bg' => '#fee2e2', 'border' => '#ef4444', 'text' => '#991b1b'),
    );
    
    $color = $colors[$type] ?? $colors['info'];
    
    echo '<div class="info-box info-box-' . esc_attr($type) . '" style="background: ' . $color['bg'] . '; border-left: 4px solid ' . $color['border'] . '; color: ' . $color['text'] . ';">';
    echo '<div class="info-box-icon">' . $icon . '</div>';
    echo '<div class="info-box-content">';
    if ($title) {
        echo '<h4>' . esc_html($title) . '</h4>';
    }
    echo '<p>' . wp_kses_post($content) . '</p>';
    echo '</div>';
    echo '</div>';
}

/**
 * Feature Grid Component
 */
function ecocommerce_pro_feature_grid($features) {
    echo '<div class="feature-grid">';
    
    foreach ($features as $feature) {
        echo '<div class="feature-item">';
        echo '<div class="feature-icon">' . ($feature['icon'] ?? '✓') . '</div>';
        echo '<div class="feature-content">';
        echo '<h4>' . esc_html($feature['title'] ?? '') . '</h4>';
        echo '<p>' . esc_html($feature['description'] ?? '') . '</p>';
        echo '</div>';
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Before/After Comparison Slider
 */
function ecocommerce_pro_comparison_slider($before_image, $after_image) {
    echo '<div class="comparison-slider">';
    echo '<div class="comparison-before">';
    echo '<img src="' . esc_url($before_image) . '" alt="Before" />';
    echo '<span class="comparison-label">Before</span>';
    echo '</div>';
    echo '<div class="comparison-after">';
    echo '<img src="' . esc_url($after_image) . '" alt="After" />';
    echo '<span class="comparison-label">After</span>';
    echo '</div>';
    echo '</div>';
}

/**
 * Stats Counter Component
 */
function ecocommerce_pro_stats_display($stats) {
    echo '<div class="stats-grid">';
    
    foreach ($stats as $stat) {
        echo '<div class="stat-item">';
        echo '<div class="stat-icon">' . ($stat['icon'] ?? '📊') . '</div>';
        echo '<div class="stat-number">' . esc_html($stat['number'] ?? '0') . '</div>';
        echo '<div class="stat-label">' . esc_html($stat['label'] ?? '') . '</div>';
        echo '</div>';
    }
    
    echo '</div>';
}


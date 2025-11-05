<?php
/**
 * Saved Part Widget - Insert Headers, Footers, Sliders
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Saved_Part extends ProBuilder_Base_Widget {
    
    public function get_name() {
        return 'saved-part';
    }
    
    public function get_title() {
        return __('Saved Part', 'probuilder');
    }
    
    public function get_icon() {
        return 'fa-puzzle-piece';
    }
    
    public function get_category() {
        return 'layout';
    }
    
    public function get_keywords() {
        return ['header', 'footer', 'slider', 'saved', 'template', 'reusable'];
    }
    
    protected function register_controls() {
        // Content Tab
        $this->start_controls_section('section_content', [
            'label' => __('Saved Part', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('part_type', [
            'label' => __('Part Type', 'probuilder'),
            'type' => 'select',
            'default' => 'header',
            'options' => [
                'header' => __('Header', 'probuilder'),
                'footer' => __('Footer', 'probuilder'),
                'slider' => __('Slider', 'probuilder'),
            ],
        ]);
        
        // Get headers
        $headers = get_posts([
            'post_type' => 'pb_header',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC'
        ]);
        $header_options = ['' => __('Select Header', 'probuilder')];
        foreach ($headers as $header) {
            $header_options[$header->ID] = $header->post_title;
        }
        
        $this->add_control('header_id', [
            'label' => __('Select Header', 'probuilder'),
            'type' => 'select',
            'default' => '',
            'options' => $header_options,
            'condition' => ['part_type' => 'header'],
        ]);
        
        // Get footers
        $footers = get_posts([
            'post_type' => 'pb_footer',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC'
        ]);
        $footer_options = ['' => __('Select Footer', 'probuilder')];
        foreach ($footers as $footer) {
            $footer_options[$footer->ID] = $footer->post_title;
        }
        
        $this->add_control('footer_id', [
            'label' => __('Select Footer', 'probuilder'),
            'type' => 'select',
            'default' => '',
            'options' => $footer_options,
            'condition' => ['part_type' => 'footer'],
        ]);
        
        // Get sliders
        $sliders = get_posts([
            'post_type' => 'pb_slider',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC'
        ]);
        $slider_options = ['' => __('Select Slider', 'probuilder')];
        foreach ($sliders as $slider) {
            $slider_options[$slider->ID] = $slider->post_title;
        }
        
        $this->add_control('slider_id', [
            'label' => __('Select Slider', 'probuilder'),
            'type' => 'select',
            'default' => '',
            'options' => $slider_options,
            'condition' => ['part_type' => 'slider'],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $part_type = $this->get_settings('part_type', 'header');
        $header_id = $this->get_settings('header_id', '');
        $footer_id = $this->get_settings('footer_id', '');
        $slider_id = $this->get_settings('slider_id', '');
        
        // Determine which ID to use
        $part_id = 0;
        $post_type = '';
        
        switch ($part_type) {
            case 'header':
                $part_id = $header_id;
                $post_type = 'pb_header';
                break;
            case 'footer':
                $part_id = $footer_id;
                $post_type = 'pb_footer';
                break;
            case 'slider':
                $part_id = $slider_id;
                $post_type = 'pb_slider';
                break;
        }
        
        $part_id = intval($part_id);
        
        echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-saved-part-wrapper" ' . $wrapper_attributes . ' style="' . esc_attr($inline_styles) . '">';
        
        if (!$part_id) {
            // Show placeholder in editor
            echo '<div style="padding: 40px; text-align: center; background: #f8f9fa; border: 2px dashed #cbd5e1; border-radius: 8px;">';
            echo '<span class="dashicons dashicons-admin-page" style="font-size: 48px; opacity: 0.3; color: #92003b;"></span>';
            echo '<p style="margin: 10px 0 5px; font-weight: 600; color: #374151;">' . __('No Part Selected', 'probuilder') . '</p>';
            echo '<p style="margin: 0; font-size: 13px; color: #6b7280;">' . sprintf(__('Select a %s from the settings panel', 'probuilder'), $part_type) . '</p>';
            echo '</div>';
        } else {
            // Check if part exists
            $post = get_post($part_id);
            
            if (!$post || $post->post_type !== $post_type) {
                echo '<div style="padding: 20px; background: #fee2e2; border: 2px solid #ef4444; border-radius: 8px; color: #991b1b;">';
                echo '<p style="margin: 0; font-weight: 600;">' . __('Part not found!', 'probuilder') . '</p>';
                echo '<p style="margin: 5px 0 0; font-size: 13px;">' . __('The selected part may have been deleted.', 'probuilder') . '</p>';
                echo '</div>';
            } else {
                // Get ProBuilder data
                $probuilder_data = get_post_meta($part_id, '_probuilder_data', true);
                
                if (empty($probuilder_data)) {
                    echo '<div style="padding: 20px; background: #fff3cd; border: 2px solid #ffc107; border-radius: 8px; color: #92400e;">';
                    echo '<p style="margin: 0; font-weight: 600;">' . __('No content yet!', 'probuilder') . '</p>';
                    echo '<p style="margin: 5px 0 0; font-size: 13px;">' . sprintf(__('"%s" has no content yet.', 'probuilder'), esc_html($post->post_title)) . '</p>';
                    
                    $edit_url = add_query_arg([
                        'p' => $part_id,
                        'probuilder' => 'true',
                        'post_type' => $post_type,
                    ], home_url('/'));
                    
                    echo '<a href="' . esc_url($edit_url) . '" target="_blank" style="display: inline-block; margin-top: 10px; padding: 8px 16px; background: #92003b; color: white; text-decoration: none; border-radius: 4px; font-size: 13px; font-weight: 600;">';
                    echo __('Edit with ProBuilder', 'probuilder');
                    echo '</a>';
                    echo '</div>';
                } else {
                    // Render ProBuilder content
                    echo '<div class="probuilder-saved-part-content" data-part-id="' . esc_attr($part_id) . '" data-part-type="' . esc_attr($part_type) . '">';
                    
                    // Render elements
                    if (class_exists('ProBuilder_Frontend')) {
                        $frontend = ProBuilder_Frontend::instance();
                        foreach ($probuilder_data as $element) {
                            $frontend->render_element($element);
                        }
                    }
                    
                    echo '</div>';
                }
            }
        }
        
        echo '</div>';
    }
}


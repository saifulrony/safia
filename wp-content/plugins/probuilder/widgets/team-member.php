<?php
/**
 * Team Member Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Team_Member extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'team-member';
        $this->title = __('Team Member', 'probuilder');
        $this->icon = 'fa fa-user';
        $this->category = 'content';
        $this->keywords = ['team', 'member', 'staff', 'person'];
    }
    
    protected function register_controls() {
        // Content Section
        $this->start_controls_section('section_member', [
            'label' => __('Team Member', 'probuilder'),
            'tab' => 'content',
        ]);
        
        $this->add_control('image', [
            'label' => __('Photo', 'probuilder'),
            'type' => 'media',
            'default' => ['url' => 'https://via.placeholder.com/300x300'],
        ]);
        
        $this->add_control('name', [
            'label' => __('Name', 'probuilder'),
            'type' => 'text',
            'default' => __('John Doe', 'probuilder'),
        ]);
        
        $this->add_control('position', [
            'label' => __('Position', 'probuilder'),
            'type' => 'text',
            'default' => __('CEO & Founder', 'probuilder'),
        ]);
        
        $this->add_control('bio', [
            'label' => __('Bio', 'probuilder'),
            'type' => 'textarea',
            'default' => __('Passionate about creating amazing products.', 'probuilder'),
        ]);
        
        $this->add_control('email', [
            'label' => __('Email', 'probuilder'),
            'type' => 'text',
            'default' => 'john@example.com',
        ]);
        
        $this->add_control('phone', [
            'label' => __('Phone', 'probuilder'),
            'type' => 'text',
            'default' => '+1 234 567 890',
        ]);
        
        $this->end_controls_section();
        
        // Social Links Section
        $this->start_controls_section('section_social', [
            'label' => __('Social Links', 'probuilder'),
            'tab' => 'content',
        ]);
        
        $this->add_control('facebook', [
            'label' => __('Facebook', 'probuilder'),
            'type' => 'url',
            'default' => '',
        ]);
        
        $this->add_control('twitter', [
            'label' => __('Twitter', 'probuilder'),
            'type' => 'url',
            'default' => '',
        ]);
        
        $this->add_control('linkedin', [
            'label' => __('LinkedIn', 'probuilder'),
            'type' => 'url',
            'default' => '',
        ]);
        
        $this->add_control('instagram', [
            'label' => __('Instagram', 'probuilder'),
            'type' => 'url',
            'default' => '',
        ]);
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style',
        ]);
        
        $this->add_control('layout', [
            'label' => __('Layout', 'probuilder'),
            'type' => 'select',
            'default' => 'left',
            'options' => [
                'left' => __('Image Left', 'probuilder'),
                'center' => __('Image Top (Centered)', 'probuilder'),
                'right' => __('Image Right', 'probuilder'),
            ],
        ]);
        
        $this->add_control('text_align', [
            'label' => __('Content Alignment', 'probuilder'),
            'type' => 'select',
            'default' => 'left',
            'options' => [
                'left' => __('Left', 'probuilder'),
                'center' => __('Center', 'probuilder'),
                'right' => __('Right', 'probuilder'),
            ],
        ]);
        
        $this->add_control('image_size', [
            'label' => __('Image Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 150,
            'range' => [
                'px' => [
                    'min' => 80,
                    'max' => 300,
                    'step' => 10
                ]
            ],
        ]);
        
        $this->add_control('border_color', [
            'label' => __('Image Border Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->add_control('name_color', [
            'label' => __('Name Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->add_control('position_color', [
            'label' => __('Position Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $image = $this->get_settings('image', ['url' => 'https://via.placeholder.com/300x300']);
        $name = $this->get_settings('name', 'John Doe');
        $position = $this->get_settings('position', 'CEO & Founder');
        $bio = $this->get_settings('bio', '');
        $email = $this->get_settings('email', '');
        $phone = $this->get_settings('phone', '');
        $facebook = $this->get_settings('facebook', '');
        $twitter = $this->get_settings('twitter', '');
        $linkedin = $this->get_settings('linkedin', '');
        $instagram = $this->get_settings('instagram', '');
        
        // Style settings
        $layout = $this->get_settings('layout', 'left');
        $text_align = $this->get_settings('text_align', 'left');
        $image_size = $this->get_settings('image_size', 150);
        $border_color = $this->get_settings('border_color', '#92003b');
        $name_color = $this->get_settings('name_color', '#333333');
        $position_color = $this->get_settings('position_color', '#92003b');
        
        // Container style
        $container_style = 'padding: 20px; border: 1px solid #e5e5e5; border-radius: 8px; background: #fff;';
        
        if ($layout === 'center') {
            // Centered layout (image on top)
            $container_style .= ' text-align: ' . esc_attr($text_align) . '; display: flex; flex-direction: column; align-items: center;';
            
            echo '<div class="probuilder-team-member probuilder-team-center" style="' . $container_style . '">';
            
            // Photo - centered
            echo '<div class="team-photo" style="margin-bottom: 20px; display: inline-block;">';
            echo '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($name) . '" style="width: ' . esc_attr($image_size) . 'px; height: ' . esc_attr($image_size) . 'px; border-radius: 50%; object-fit: cover; border: 3px solid ' . esc_attr($border_color) . '; display: block;">';
            echo '</div>';
            
        } else {
            // Left or Right layout (image on side)
            $flex_direction = $layout === 'left' ? 'row' : 'row-reverse';
            $container_style .= ' display: flex; flex-direction: ' . $flex_direction . '; gap: 25px; align-items: flex-start;';
            
            echo '<div class="probuilder-team-member probuilder-team-' . esc_attr($layout) . '" style="' . $container_style . '">';
            
            // Photo
            echo '<div class="team-photo" style="flex-shrink: 0;">';
            echo '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($name) . '" style="width: ' . esc_attr($image_size) . 'px; height: ' . esc_attr($image_size) . 'px; border-radius: 50%; object-fit: cover; border: 3px solid ' . esc_attr($border_color) . ';">';
            echo '</div>';
            
            // Content wrapper
            echo '<div class="team-content" style="flex: 1; text-align: ' . esc_attr($text_align) . ';">';
        }
        
        // Name
        echo '<h3 style="margin: 0 0 5px 0; font-size: 22px; font-weight: 600; color: ' . esc_attr($name_color) . ';">' . esc_html($name) . '</h3>';
        
        // Position
        echo '<div class="team-position" style="color: ' . esc_attr($position_color) . '; font-size: 14px; font-weight: 600; margin-bottom: 15px;">' . esc_html($position) . '</div>';
        
        // Bio
        if ($bio) {
            echo '<p style="color: #666; font-size: 14px; line-height: 1.6; margin: 0 0 15px 0;">' . esc_html($bio) . '</p>';
        }
        
        // Contact
        if ($email || $phone) {
            $contact_justify = $text_align === 'center' ? 'center' : ($text_align === 'right' ? 'flex-end' : 'flex-start');
            echo '<div class="team-contact" style="font-size: 13px; color: #666; margin-bottom: 15px; display: flex; flex-direction: column; align-items: ' . ($text_align === 'center' ? 'center' : ($text_align === 'right' ? 'flex-end' : 'flex-start')) . '; gap: 5px;">';
            if ($email) echo '<div style="display: flex; align-items: center; gap: 8px;"><i class="fa fa-envelope" style="color: ' . esc_attr($position_color) . ';"></i> <span>' . esc_html($email) . '</span></div>';
            if ($phone) echo '<div style="display: flex; align-items: center; gap: 8px;"><i class="fa fa-phone" style="color: ' . esc_attr($position_color) . ';"></i> <span>' . esc_html($phone) . '</span></div>';
            echo '</div>';
        }
        
        // Social links
        if ($facebook || $twitter || $linkedin || $instagram) {
            $social_justify = $text_align === 'center' ? 'center' : ($text_align === 'right' ? 'flex-end' : 'flex-start');
            echo '<div class="team-social" style="display: flex; justify-content: ' . $social_justify . '; gap: 10px; margin-top: 15px;">';
            
            if ($facebook) {
                echo '<a href="' . esc_url($facebook) . '" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; justify-content: center; width: 35px; height: 35px; background: #3b5998; color: #fff; border-radius: 50%; text-decoration: none; transition: all 0.3s;"><i class="fab fa-facebook-f"></i></a>';
            }
            if ($twitter) {
                echo '<a href="' . esc_url($twitter) . '" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; justify-content: center; width: 35px; height: 35px; background: #1da1f2; color: #fff; border-radius: 50%; text-decoration: none; transition: all 0.3s;"><i class="fab fa-twitter"></i></a>';
            }
            if ($linkedin) {
                echo '<a href="' . esc_url($linkedin) . '" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; justify-content: center; width: 35px; height: 35px; background: #0077b5; color: #fff; border-radius: 50%; text-decoration: none; transition: all 0.3s;"><i class="fab fa-linkedin-in"></i></a>';
            }
            if ($instagram) {
                echo '<a href="' . esc_url($instagram) . '" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; justify-content: center; width: 35px; height: 35px; background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); color: #fff; border-radius: 50%; text-decoration: none; transition: all 0.3s;"><i class="fab fa-instagram"></i></a>';
            }
            
            echo '</div>';
        }
        
        // Close content wrapper for left/right layouts
        if ($layout !== 'center') {
            echo '</div>';
        }
        
        echo '</div>';
    }
}


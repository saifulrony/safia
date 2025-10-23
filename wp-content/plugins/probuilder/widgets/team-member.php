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
        $this->start_controls_section('section_member', [
            'label' => __('Team Member', 'probuilder'),
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
        
        $this->start_controls_section('section_social', [
            'label' => __('Social Links', 'probuilder'),
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
        
        echo '<div class="probuilder-team-member" style="text-align: center; padding: 20px; border: 1px solid #e5e5e5; border-radius: 8px; background: #fff;">';
        
        // Photo
        echo '<div class="team-photo" style="margin-bottom: 20px;">';
        echo '<img src="' . esc_url($image['url']) . '" alt="' . esc_attr($name) . '" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 3px solid #92003b;">';
        echo '</div>';
        
        // Name
        echo '<h3 style="margin: 0 0 5px 0; font-size: 22px; color: #333;">' . esc_html($name) . '</h3>';
        
        // Position
        echo '<div style="color: #92003b; font-size: 14px; font-weight: 600; margin-bottom: 15px;">' . esc_html($position) . '</div>';
        
        // Bio
        if ($bio) {
            echo '<p style="color: #666; font-size: 14px; line-height: 1.6; margin-bottom: 15px;">' . esc_html($bio) . '</p>';
        }
        
        // Contact
        if ($email || $phone) {
            echo '<div style="font-size: 13px; color: #666; margin-bottom: 15px;">';
            if ($email) echo '<div style="margin: 5px 0;"><i class="fa fa-envelope"></i> ' . esc_html($email) . '</div>';
            if ($phone) echo '<div style="margin: 5px 0;"><i class="fa fa-phone"></i> ' . esc_html($phone) . '</div>';
            echo '</div>';
        }
        
        // Social links
        if ($facebook || $twitter || $linkedin) {
            echo '<div class="team-social" style="display: flex; justify-content: center; gap: 10px;">';
            
            if ($facebook) {
                echo '<a href="' . esc_url($facebook) . '" target="_blank" style="display: inline-flex; align-items: center; justify-content: center; width: 35px; height: 35px; background: #3b5998; color: #fff; border-radius: 50%; text-decoration: none;"><i class="fab fa-facebook-f"></i></a>';
            }
            if ($twitter) {
                echo '<a href="' . esc_url($twitter) . '" target="_blank" style="display: inline-flex; align-items: center; justify-content: center; width: 35px; height: 35px; background: #1da1f2; color: #fff; border-radius: 50%; text-decoration: none;"><i class="fab fa-twitter"></i></a>';
            }
            if ($linkedin) {
                echo '<a href="' . esc_url($linkedin) . '" target="_blank" style="display: inline-flex; align-items: center; justify-content: center; width: 35px; height: 35px; background: #0077b5; color: #fff; border-radius: 50%; text-decoration: none;"><i class="fab fa-linkedin-in"></i></a>';
            }
            
            echo '</div>';
        }
        
        echo '</div>';
    }
}


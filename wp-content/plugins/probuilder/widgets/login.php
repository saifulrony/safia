<?php
/**
 * Login Form Widget - Fixed
 */

if (!defined('ABSPATH')) exit;

class ProBuilder_Login_Widget extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'login';
        $this->title = __('Login Form', 'probuilder');
        $this->icon = 'fa fa-sign-in-alt';
        $this->category = 'wordpress';
        $this->keywords = ['login', 'user', 'auth'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
        ]);
        
        $this->add_control('show_labels', [
            'label' => __('Show Labels', 'probuilder'),
            'type' => 'switcher',
            'default' => true,
        ]);
        
        $this->add_control('show_remember', [
            'label' => __('Show Remember Me', 'probuilder'),
            'type' => 'switcher',
            'default' => true,
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $style = 'background:#f9f9f9;padding:20px;border-radius:8px;text-align:center;';
            if ($inline_styles) $style .= ' ' . $inline_styles;
            echo '<div class="' . esc_attr($wrapper_classes) . ' pb-login" ' . $wrapper_attributes . ' style="' . esc_attr($style) . '">';
            echo '<p style="margin:0 0 15px">Welcome, <strong>' . $user->display_name . '</strong>!</p>';
            echo '<a href="' . wp_logout_url() . '" style="color:#0073aa;text-decoration:none">Logout</a>';
            echo '</div>';
            return;
        }
        
        $show_labels = $this->get_settings('show_labels', true);
        $show_remember = $this->get_settings('show_remember', true);
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        
        $style = 'max-width:400px;margin:0 auto;';
        if ($inline_styles) $style .= ' ' . $inline_styles;
        echo '<div class="' . esc_attr($wrapper_classes) . ' pb-login-form" ' . $wrapper_attributes . ' style="' . esc_attr($style) . '">';
        wp_login_form([
            'echo' => true,
            'label_username' => $show_labels ? __('Username', 'probuilder') : '',
            'label_password' => $show_labels ? __('Password', 'probuilder') : '',
            'remember' => $show_remember,
        ]);
        echo '<style>.login-username input,.login-password input{width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;margin-top:5px}.login-submit input{background:#0073aa;color:#fff;border:none;padding:12px 24px;border-radius:4px;cursor:pointer;width:100%}</style>';
        echo '</div>';
    }
}


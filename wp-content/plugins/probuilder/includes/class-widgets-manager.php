<?php
/**
 * Widgets Manager
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widgets_Manager {
    
    private static $instance = null;
    private $widgets = [];
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', [$this, 'register_widgets']);
    }
    
    /**
     * Register all widgets
     */
    public function register_widgets() {
        $widget_classes = [
            // Layout
            'ProBuilder_Widget_Container',
            'ProBuilder_Widget_Flexbox',
            
            // Basic
            'ProBuilder_Widget_Heading',
            'ProBuilder_Widget_Text',
            'ProBuilder_Widget_Button',
            'ProBuilder_Widget_Image',
            'ProBuilder_Widget_Divider',
            'ProBuilder_Widget_Spacer',
            'ProBuilder_Widget_Alert',
            'ProBuilder_Widget_Blockquote',
            
            // Advanced
            'ProBuilder_Widget_Tabs',
            'ProBuilder_Widget_Accordion',
            'ProBuilder_Widget_Carousel',
            'ProBuilder_Widget_Gallery',
            'ProBuilder_Widget_Toggle',
            'ProBuilder_Widget_Flip_Box',
            'ProBuilder_Widget_Before_After',
            'ProBuilder_Widget_Animated_Headline',
            
            // Content
            'ProBuilder_Widget_Image_Box',
            'ProBuilder_Widget_Icon_Box',
            'ProBuilder_Widget_Info_Box',
            'ProBuilder_Widget_Icon_List',
            'ProBuilder_Widget_Feature_List',
            'ProBuilder_Widget_Progress_Bar',
            'ProBuilder_Widget_Testimonial',
            'ProBuilder_Widget_Counter',
            'ProBuilder_Widget_Star_Rating',
            'ProBuilder_Widget_Pricing_Table',
            'ProBuilder_Widget_Team_Member',
            'ProBuilder_Widget_Call_To_Action',
            'ProBuilder_Widget_Social_Icons',
            'ProBuilder_Widget_Countdown',
            'ProBuilder_Widget_Newsletter',
            'ProBuilder_Widget_Contact_Form',
            'ProBuilder_Widget_Logo_Grid',
            'ProBuilder_Widget_Video',
            'ProBuilder_Widget_Map',
            'ProBuilder_Widget_HTML_Code',
            'ProBuilder_Widget_Shortcode',
            'ProBuilder_Widget_WP_Header',
            'ProBuilder_Widget_WP_Sidebar',
            'ProBuilder_Widget_WP_Footer',
        ];
        
        foreach ($widget_classes as $class) {
            if (class_exists($class)) {
                $this->register_widget(new $class());
            }
        }
    }
    
    /**
     * Register widget
     */
    public function register_widget($widget) {
        $this->widgets[$widget->get_name()] = $widget;
    }
    
    /**
     * Get all widgets
     */
    public function get_widgets() {
        return $this->widgets;
    }
    
    /**
     * Get widget by name
     */
    public function get_widget($name) {
        return isset($this->widgets[$name]) ? $this->widgets[$name] : null;
    }
    
    /**
     * Get widgets for editor
     */
    public function get_widgets_config() {
        $config = [];
        
        foreach ($this->widgets as $widget) {
            $controls = $widget->get_controls();
            
            // Debug: Log control tabs for heading widget
            if ($widget->get_name() === 'heading') {
                error_log("========== HEADING WIDGET DEBUG ==========");
                error_log("Total controls: " . count($controls));
                foreach ($controls as $key => $control) {
                    $tab = isset($control['tab']) ? $control['tab'] : 'NO TAB';
                    $type = isset($control['type']) ? $control['type'] : 'NO TYPE';
                    error_log("  Control: $key | Tab: $tab | Type: $type");
                }
                error_log("==========================================");
            }
            
            $config[] = [
                'name' => $widget->get_name(),
                'title' => $widget->get_title(),
                'icon' => $widget->get_icon(),
                'category' => $widget->get_category(),
                'keywords' => $widget->get_keywords(),
                'controls' => $controls,
            ];
        }
        
        return $config;
    }
}


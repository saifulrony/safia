<?php
/**
 * Base Widget Class
 */

if (!defined('ABSPATH')) {
    exit;
}

abstract class ProBuilder_Base_Widget {
    
    protected $name;
    protected $title;
    protected $icon;
    protected $category;
    protected $keywords = [];
    
    /**
     * Get widget name
     */
    public function get_name() {
        return $this->name;
    }
    
    /**
     * Get widget title
     */
    public function get_title() {
        return $this->title;
    }
    
    /**
     * Get widget icon
     */
    public function get_icon() {
        return $this->icon;
    }
    
    /**
     * Get widget category
     */
    public function get_category() {
        return $this->category;
    }
    
    /**
     * Get widget keywords
     */
    public function get_keywords() {
        return $this->keywords;
    }
    
    /**
     * Get widget controls/settings
     */
    abstract protected function register_controls();
    
    /**
     * Render widget output
     */
    abstract protected function render();
    
    /**
     * Get controls
     */
    public function get_controls() {
        $this->register_controls();
        return $this->controls;
    }
    
    protected $controls = [];
    
    /**
     * Add control
     */
    protected function add_control($id, $args) {
        $this->controls[$id] = $args;
    }
    
    /**
     * Start controls section
     */
    protected function start_controls_section($id, $args) {
        $this->controls[$id] = array_merge($args, ['type' => 'section_start']);
    }
    
    /**
     * End controls section
     */
    protected function end_controls_section() {
        // Just for API compatibility
    }
    
    /**
     * Render widget
     */
    public function render_widget($settings = []) {
        $this->settings = $settings;
        $this->render();
    }
    
    protected $settings = [];
    
    /**
     * Get setting
     */
    protected function get_settings($key = null, $default = null) {
        if ($key === null) {
            return $this->settings;
        }
        return isset($this->settings[$key]) ? $this->settings[$key] : $default;
    }
}


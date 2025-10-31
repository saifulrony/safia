<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Category_List_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'category-list'; }
    public function get_title() { return __('Category List', 'probuilder'); }
    public function get_icon() { return 'fa fa-folder-open'; }
    public function get_categories() { return ['wordpress']; }
    protected function register_controls() {
        $this->add_control('taxonomy', ['label' => 'Taxonomy', 'type' => 'select', 'options' => ['category' => 'Categories', 'post_tag' => 'Tags'], 'default' => 'category']);
        $this->add_control('show_count', ['label' => 'Show Count', 'type' => 'switcher', 'default' => true]);
        $this->add_control('limit', ['label' => 'Limit', 'type' => 'number', 'default' => 10]);
    }
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $s = $this->get_settings();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $terms = get_terms(['taxonomy' => $s['taxonomy'], 'number' => $s['limit']]);
        echo '<ul class="' . esc_attr($wrapper_classes) . ' pb-category-list" ' . $wrapper_attributes . ' style="' . esc_attr($inline_styles) . '">';
        foreach ($terms as $term) {
            echo '<li><a href="' . get_term_link($term) . '">' . $term->name . '</a>';
            if ($s['show_count']) echo ' (' . $term->count . ')';
            echo '</li>';
        }
        echo '</ul><style>.pb-category-list{list-style:none;padding:0}.pb-category-list li{padding:8px 0;border-bottom:1px solid #eee}.pb-category-list a{color:#0073aa;text-decoration:none}</style>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Category_List_Widget());


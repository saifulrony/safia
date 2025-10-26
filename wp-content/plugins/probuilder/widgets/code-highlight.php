<?php
if (!defined('ABSPATH')) exit;

class ProBuilder_Code_Highlight_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'code-highlight'; }
    public function get_title() { return __('Code Highlight', 'probuilder'); }
    public function get_icon() { return 'fa fa-code'; }
    public function get_categories() { return ['content']; }
    
    protected function register_controls() {
        $this->add_control('code', ['label' => __('Code', 'probuilder'), 'type' => 'textarea', 'default' => 'function hello() {\n  console.log("Hello");\n}']);
        $this->add_control('language', ['label' => __('Language', 'probuilder'), 'type' => 'select', 'options' => ['html' => 'HTML', 'css' => 'CSS', 'javascript' => 'JavaScript', 'php' => 'PHP', 'python' => 'Python'], 'default' => 'javascript']);
        $this->add_control('show_line_numbers', ['label' => __('Line Numbers', 'probuilder'), 'type' => 'switcher', 'default' => true]);
        $this->add_control('theme', ['label' => __('Theme', 'probuilder'), 'type' => 'select', 'options' => ['light' => 'Light', 'dark' => 'Dark'], 'default' => 'dark']);
    }
    
    protected function render() {
        $s = $this->get_settings();
        $lines = explode("\n", $s['code']);
        echo '<div class="pb-code-highlight theme-' . $s['theme'] . '"><pre><code class="language-' . $s['language'] . '">';
        if ($s['show_line_numbers']) {
            foreach ($lines as $i => $line) {
                echo '<span class="line-number">' . ($i+1) . '</span>' . esc_html($line) . "\n";
            }
        } else {
            echo esc_html($s['code']);
        }
        echo '</code></pre></div>';
        echo '<style>.pb-code-highlight pre{background:#2d2d2d;color:#f8f8f2;padding:20px;border-radius:8px;overflow-x:auto}.theme-light pre{background:#f5f5f5;color:#333}.line-number{color:#6c6c6c;margin-right:15px;user-select:none}</style>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Code_Highlight_Widget());


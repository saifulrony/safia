<?php
if (!defined('ABSPATH')) exit;

class ProBuilder_Progress_Tracker_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'progress-tracker'; }
    public function get_title() { return __('Progress Tracker', 'probuilder'); }
    public function get_icon() { return 'fa fa-tasks'; }
    public function get_categories() { return ['content']; }
    
    protected function register_controls() {
        $this->add_control('steps', ['label' => __('Steps', 'probuilder'), 'type' => 'repeater', 'default' => [['title' => 'Step 1', 'complete' => true], ['title' => 'Step 2', 'complete' => false]], 'fields' => [
            ['name' => 'title', 'label' => 'Title', 'type' => 'text', 'default' => 'Step'],
            ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'default' => ''],
            ['name' => 'complete', 'label' => 'Complete', 'type' => 'switcher', 'default' => false],
        ]]);
        $this->add_control('orientation', ['label' => __('Orientation', 'probuilder'), 'type' => 'select', 'options' => ['horizontal' => 'Horizontal', 'vertical' => 'Vertical'], 'default' => 'horizontal']);
        
        $this->start_style_tab();
        $this->add_control('active_color', ['label' => __('Active Color', 'probuilder'), 'type' => 'color', 'default' => '#4caf50']);
        $this->add_control('inactive_color', ['label' => __('Inactive Color', 'probuilder'), 'type' => 'color', 'default' => '#cccccc']);
        $this->end_style_tab();
    }
    
    protected function render() {
        $s = $this->get_settings();
        echo '<div class="pb-progress-tracker pb-orient-' . $s['orientation'] . '">';
        foreach ($s['steps'] as $i => $step) {
            $class = $step['complete'] ? 'complete' : 'incomplete';
            echo '<div class="pb-step ' . $class . '">';
            echo '<div class="pb-step-circle" style="background: ' . ($step['complete'] ? $s['active_color'] : $s['inactive_color']) . '">' . ($i+1) . '</div>';
            echo '<div class="pb-step-content"><h4>' . esc_html($step['title']) . '</h4>';
            if (!empty($step['description'])) echo '<p>' . esc_html($step['description']) . '</p>';
            echo '</div></div>';
            if ($i < count($s['steps']) - 1) echo '<div class="pb-step-line" style="background: ' . ($step['complete'] ? $s['active_color'] : $s['inactive_color']) . '"></div>';
        }
        echo '</div>';
        echo '<style>.pb-progress-tracker{display:flex;gap:10px;align-items:center}.pb-orient-vertical{flex-direction:column}.pb-step{display:flex;align-items:center;gap:10px}.pb-step-circle{width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700}.pb-step-line{height:2px;flex:1;min-width:50px}.pb-orient-vertical .pb-step-line{height:30px;width:2px}</style>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Progress_Tracker_Widget());


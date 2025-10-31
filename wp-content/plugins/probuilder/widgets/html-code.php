<?php
/**
 * HTML/CSS/JS Custom Code Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_HTML_Code extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'html-code';
        $this->title = __('HTML/CSS/JS', 'probuilder');
        $this->icon = 'fa fa-code';
        $this->category = 'advanced';
        $this->keywords = ['html', 'css', 'js', 'javascript', 'custom', 'code'];
    }
    
    protected function register_controls() {
        // HTML
        $this->start_controls_section('section_html', [
            'label' => __('HTML', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('html_code', [
            'label' => __('HTML Code', 'probuilder'),
            'type' => 'textarea',
            'default' => '<div class="custom-element" <?php echo \$wrapper_attributes; ?> >\n  <h3>Custom HTML Element</h3>\n  <p>Add your custom HTML code here.</p>\n</div>',
            'description' => __('Enter your custom HTML code', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        // CSS
        $this->start_controls_section('section_css', [
            'label' => __('CSS', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('css_code', [
            'label' => __('CSS Code', 'probuilder'),
            'type' => 'textarea',
            'default' => '.custom-element {\n  padding: 20px;\n  background: #f8f9fa;\n  border-radius: 8px;\n}\n\n.custom-element h3 {\n  color: #92003b;\n  margin: 0 0 10px 0;\n}',
            'description' => __('Enter your custom CSS code (without <style> tags)', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        // JAVASCRIPT
        $this->start_controls_section('section_js', [
            'label' => __('JavaScript', 'probuilder'),
            'tab' => 'content'
        ]);
        
        $this->add_control('js_code', [
            'label' => __('JavaScript Code', 'probuilder'),
            'type' => 'textarea',
            'default' => '// Add your JavaScript code here\nconsole.log("Custom code widget loaded");',
            'description' => __('Enter your custom JavaScript code (without <script> tags)', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        // ADVANCED
        $this->start_controls_section('section_advanced', [
            'label' => __('Advanced', 'probuilder'),
            'tab' => 'advanced'
        ]);
        
        $this->add_control('margin', [
            'label' => __('Margin', 'probuilder'),
            'type' => 'dimensions',
            'default' => ['top' => 20, 'right' => 0, 'bottom' => 20, 'left' => 0]
        ]);
        
        $this->add_control('entrance_animation', [
            'label' => __('Entrance Animation', 'probuilder'),
            'type' => 'select',
            'default' => 'none',
            'options' => [
                'none' => __('None', 'probuilder'),
                'fadeIn' => __('Fade In', 'probuilder'),
                'fadeInUp' => __('Fade In Up', 'probuilder'),
                'zoomIn' => __('Zoom In', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $settings = $this->get_settings();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $id = 'html-code-' . uniqid();
        
        $html_code = $settings['html_code'] ?? '';
        $css_code = $settings['css_code'] ?? '';
        $js_code = $settings['js_code'] ?? '';
        $margin = $settings['margin'] ?? ['top' => 20, 'right' => 0, 'bottom' => 20, 'left' => 0];
        $animation = $settings['entrance_animation'] ?? 'none';
        
        $container_style = 'margin: ' . $margin['top'] . 'px ' . $margin['right'] . 'px ' . $margin['bottom'] . 'px ' . $margin['left'] . 'px;';
        
        if ($animation !== 'none') {
            $container_style .= ' animation: ' . $animation . ' 0.6s ease forwards;';
        }
        
        ?>
        <div class="<?php echo esc_attr($wrapper_classes); ?> probuilder-html-code-widget" <?php echo $wrapper_attributes; ?> id="<?php echo esc_attr($id); ?>" style="<?php echo esc_attr($container_style . ($inline_styles ? ' ' . $inline_styles : '')); ?>">
            <?php 
            // Output HTML (with proper escaping for safety in editor, but allow in frontend)
            if (is_admin() || (isset($_GET['probuilder']) && $_GET['probuilder'])) {
                // In editor - show safely
                echo wp_kses_post($html_code);
            } else {
                // On frontend - allow full HTML
                echo $html_code;
            }
            ?>
        </div>
        
        <?php if (!empty($css_code)): ?>
        <style id="<?php echo esc_attr($id); ?>-style">
            <?php echo wp_strip_all_tags($css_code); ?>
        </style>
        <?php endif; ?>
        
        <?php if (!empty($js_code)): ?>
        <script id="<?php echo esc_attr($id); ?>-script">
            (function() {
                <?php echo wp_strip_all_tags($js_code); ?>
            })();
        </script>
        <?php endif; ?>
        <?php
    }
}


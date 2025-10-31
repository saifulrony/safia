<?php
/**
 * Text Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Text extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'text';
        $this->title = __('Text Editor', 'probuilder');
        $this->icon = 'fa fa-align-left';
        $this->category = 'basic';
        $this->keywords = ['text', 'editor', 'paragraph'];
    }
    
    protected function register_controls() {
        // Content Section
        $this->start_controls_section('section_content', [
            'label' => __('Content', 'probuilder'),
            'tab' => 'content',
        ]);
        
        $this->add_control('text', [
            'label' => __('Text', 'probuilder'),
            'type' => 'editor',
            'default' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'probuilder'),
        ]);
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section('section_style', [
            'label' => __('Style', 'probuilder'),
            'tab' => 'style',
        ]);
        
        $this->add_control('text_color', [
            'label' => __('Text Color', 'probuilder'),
            'type' => 'color',
            'default' => '#666666',
        ]);
        
        $this->add_control('font_size', [
            'label' => __('Font Size (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 16,
            'range' => [
                'px' => ['min' => 10, 'max' => 50],
            ],
        ]);
        
        $this->add_control('line_height', [
            'label' => __('Line Height', 'probuilder'),
            'type' => 'slider',
            'default' => 1.6,
            'range' => [
                'px' => ['min' => 1, 'max' => 3, 'step' => 0.1],
            ],
        ]);
        
        $this->add_control('text_align', [
            'label' => __('Text Alignment', 'probuilder'),
            'type' => 'select',
            'default' => 'left',
            'options' => [
                'left' => __('Left', 'probuilder'),
                'center' => __('Center', 'probuilder'),
                'right' => __('Right', 'probuilder'),
                'justify' => __('Justify', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
        
        // Text Path Section
        $this->start_controls_section('section_text_path', [
            'label' => __('Text Path & Effects', 'probuilder'),
            'tab' => 'style'
        ]);
        
        $this->add_control('path_type', [
            'label' => __('Path Type', 'probuilder'),
            'type' => 'select',
            'default' => 'none',
            'options' => [
                'none' => __('No Path (Normal Text)', 'probuilder'),
                'curve' => __('Curve (Arc)', 'probuilder'),
                'wave' => __('Wave', 'probuilder'),
                'circle' => __('Circle', 'probuilder'),
                'zigzag' => __('Zigzag', 'probuilder'),
                'spiral' => __('Spiral', 'probuilder'),
                'sine' => __('Sine Wave', 'probuilder'),
                'bounce' => __('Bounce', 'probuilder'),
                'infinity' => __('Infinity', 'probuilder'),
            ],
            'description' => __('Choose a path for your text (works best with single line text)', 'probuilder'),
        ]);
        
        $this->add_control('curve_amount', [
            'label' => __('Path Intensity', 'probuilder'),
            'type' => 'slider',
            'default' => 50,
            'range' => [
                'px' => ['min' => -100, 'max' => 100, 'step' => 5],
            ],
            'description' => __('Adjust the intensity of the path effect', 'probuilder'),
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $text = $this->get_settings('text', '');
        $color = $this->get_settings('text_color', '#666666');
        $font_size = $this->get_settings('font_size', 16);
        $line_height = $this->get_settings('line_height', 1.6);
        $text_align = $this->get_settings('text_align', 'left');
        $path_type = $this->get_settings('path_type', 'none');
        $curve_amount = $this->get_settings('curve_amount', 50);
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        if ($path_type !== 'none') {
            // Generate unique ID
            $id = 'text-path-' . uniqid();
            
            // Use only first line for text path
            $text_lines = explode("\n", $text);
            $curved_text = trim($text_lines[0]);
            
            // Calculate SVG height based on curve
            $svg_height = $font_size * 3;
            $text_length = strlen($curved_text) * $font_size * 0.6;
            
            // Generate SVG path based on type
            $mid_y = $svg_height / 2;
            $intensity = $curve_amount;
            
            switch ($path_type) {
                case 'curve':
                    $control_y = $mid_y - $intensity;
                    $path_d = "M 0,{$svg_height} Q " . ($text_length / 2) . ",{$control_y} {$text_length},{$svg_height}";
                    break;
                    
                case 'wave':
                    $wave_height = abs($intensity);
                    $path_d = "M 0,{$mid_y} Q " . ($text_length * 0.25) . "," . ($mid_y - $wave_height) . " " . ($text_length * 0.5) . ",{$mid_y} T {$text_length},{$mid_y}";
                    break;
                    
                case 'circle':
                    $radius = $text_length / 2;
                    $path_d = "M 0,{$svg_height} A {$radius},{$radius} 0 0,1 {$text_length},{$svg_height}";
                    break;
                    
                case 'zigzag':
                    $points = 8;
                    $path_d = "M 0,{$mid_y}";
                    for ($i = 1; $i <= $points; $i++) {
                        $x = ($text_length / $points) * $i;
                        $y = $mid_y + (($i % 2 === 0) ? $intensity : -$intensity);
                        $path_d .= " L {$x},{$y}";
                    }
                    break;
                    
                case 'spiral':
                    $turns = 3;
                    $path_d = "M 0,{$mid_y}";
                    for ($i = 1; $i <= 20; $i++) {
                        $x = ($text_length / 20) * $i;
                        $angle = ($i / 20) * $turns * 2 * M_PI;
                        $amplitude = $intensity * ($i / 20);
                        $y = $mid_y + sin($angle) * $amplitude;
                        $path_d .= " L {$x},{$y}";
                    }
                    break;
                    
                case 'sine':
                    $frequency = 2;
                    $path_d = "M 0,{$mid_y}";
                    for ($i = 1; $i <= 30; $i++) {
                        $x = ($text_length / 30) * $i;
                        $angle = ($i / 30) * $frequency * 2 * M_PI;
                        $y = $mid_y + sin($angle) * $intensity;
                        $path_d .= " L {$x},{$y}";
                    }
                    break;
                    
                case 'bounce':
                    $bounces = 4;
                    $path_d = "M 0,{$mid_y}";
                    for ($i = 1; $i <= $bounces; $i++) {
                        $x1 = ($text_length / $bounces) * ($i - 0.5);
                        $y1 = $mid_y - abs($intensity);
                        $x2 = ($text_length / $bounces) * $i;
                        $y2 = $mid_y;
                        $path_d .= " Q {$x1},{$y1} {$x2},{$y2}";
                    }
                    break;
                    
                case 'infinity':
                    $loop_width = $text_length / 2;
                    $path_d = "M 0,{$mid_y} ";
                    $path_d .= "Q " . ($loop_width * 0.25) . "," . ($mid_y - $intensity) . " " . ($loop_width * 0.5) . ",{$mid_y} ";
                    $path_d .= "Q " . ($loop_width * 0.75) . "," . ($mid_y + $intensity) . " {$loop_width},{$mid_y} ";
                    $path_d .= "Q " . ($loop_width * 1.25) . "," . ($mid_y - $intensity) . " " . ($loop_width * 1.5) . ",{$mid_y} ";
                    $path_d .= "Q " . ($loop_width * 1.75) . "," . ($mid_y + $intensity) . " " . ($text_length) . ",{$mid_y}";
                    break;
                    
                default:
                    $path_d = "M 0,{$mid_y} L {$text_length},{$mid_y}";
            }
            
            $wrapper_style = 'text-align: ' . esc_attr($text_align) . ';';
            if ($inline_styles) {
                $wrapper_style .= ' ' . $inline_styles;
            }
            echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-text-path-wrapper" ' . $wrapper_attributes . ' style="' . $wrapper_style . '">';
            echo '<svg width="100%" height="' . $svg_height . '" viewBox="0 0 ' . $text_length . ' ' . $svg_height . '" xmlns="http://www.w3.org/2000/svg" style="overflow: visible;">';
            echo '<defs>';
            echo '<path id="' . esc_attr($id) . '" d="' . esc_attr($path_d) . '" fill="transparent"/>';
            echo '</defs>';
            echo '<text style="fill: ' . esc_attr($color) . '; font-size: ' . esc_attr($font_size) . 'px; font-family: inherit;">';
            echo '<textPath href="#' . esc_attr($id) . '" startOffset="50%" text-anchor="middle">';
            echo esc_html($curved_text);
            echo '</textPath>';
            echo '</text>';
            echo '</svg>';
            
            // Show remaining lines as regular text
            if (count($text_lines) > 1) {
                array_shift($text_lines);
                $remaining_text = implode("\n", $text_lines);
                $style = 'color: ' . esc_attr($color) . '; ';
                $style .= 'font-size: ' . esc_attr($font_size) . 'px; ';
                $style .= 'line-height: ' . esc_attr($line_height) . '; ';
                $style .= 'text-align: ' . esc_attr($text_align) . '; ';
                $style .= 'margin-top: 10px;';
                echo '<div style="' . $style . '">' . wp_kses_post(wpautop($remaining_text)) . '</div>';
            }
            echo '</div>';
        } else {
            // Regular text (may contain HTML from editor)
            $style = 'color: ' . esc_attr($color) . '; ';
            $style .= 'font-size: ' . esc_attr($font_size) . 'px; ';
            $style .= 'line-height: ' . esc_attr($line_height) . '; ';
            $style .= 'text-align: ' . esc_attr($text_align) . ';';
            if ($inline_styles) {
                $style .= ' ' . $inline_styles;
            }
            
            echo '<style>
                .probuilder-text blockquote {
                    border-left: 4px solid #92003b;
                    margin: 16px 0;
                    padding: 12px 20px;
                    background: #f9f9f9;
                    font-style: italic;
                    color: #555;
                }
                .probuilder-text pre {
                    background: #2d2d2d;
                    color: #f8f8f2;
                    padding: 16px;
                    border-radius: 4px;
                    overflow-x: auto;
                    font-family: "Courier New", monospace;
                    font-size: 13px;
                    line-height: 1.5;
                }
                .probuilder-text code {
                    background: #f4f4f4;
                    padding: 2px 6px;
                    border-radius: 3px;
                    font-family: "Courier New", monospace;
                    font-size: 13px;
                    color: #c7254e;
                }
                .probuilder-text pre code {
                    background: transparent;
                    padding: 0;
                    color: #f8f8f2;
                }
                .probuilder-text h1, 
                .probuilder-text h2, 
                .probuilder-text h3, 
                .probuilder-text h4, 
                .probuilder-text h5, 
                .probuilder-text h6 {
                    margin-top: 16px;
                    margin-bottom: 8px;
                    font-weight: 600;
                    line-height: 1.3;
                }
                .probuilder-text h1 { font-size: 32px; }
                .probuilder-text h2 { font-size: 28px; }
                .probuilder-text h3 { font-size: 24px; }
                .probuilder-text h4 { font-size: 20px; }
                .probuilder-text h5 { font-size: 18px; }
                .probuilder-text h6 { font-size: 16px; }
                .probuilder-text ul, 
                .probuilder-text ol {
                    margin: 12px 0;
                    padding-left: 30px;
                }
                .probuilder-text li {
                    margin: 6px 0;
                }
                .probuilder-text a {
                    color: #92003b;
                    text-decoration: underline;
                }
                .probuilder-text img {
                    max-width: 100%;
                    height: auto;
                    border-radius: 4px;
                    margin: 8px 0;
                }
                .probuilder-text hr {
                    border: none;
                    border-top: 2px solid #ddd;
                    margin: 20px 0;
                }
            </style>';
            
            echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-text" ' . $wrapper_attributes . ' style="' . $style . '">';
            // Text may already be HTML from the WYSIWYG editor
            if (strip_tags($text) !== $text) {
                // Contains HTML, output as-is (sanitized)
                echo wp_kses_post($text);
            } else {
                // Plain text, apply wpautop
                echo wp_kses_post(wpautop($text));
            }
            echo '</div>';
        }
    }
}


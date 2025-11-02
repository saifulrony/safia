<?php
/**
 * Pie Chart Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Pie_Chart extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'pie-chart';
        $this->title = __('Pie Chart', 'probuilder');
        $this->icon = 'fa fa-chart-pie';
        $this->category = 'content';
        $this->keywords = ['chart', 'pie', 'graph', 'data', 'statistics'];
    }
    
    protected function register_controls() {
        // Content Section
        $this->start_controls_section('section_chart', [
            'label' => __('Chart Data', 'probuilder'),
            'tab' => 'content',
        ]);
        
        $this->add_control('chart_title', [
            'label' => __('Chart Title', 'probuilder'),
            'type' => 'text',
            'default' => __('Sales Distribution', 'probuilder'),
        ]);
        
        $this->add_control('show_title', [
            'label' => __('Show Title', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('chart_data', [
            'label' => __('Chart Data', 'probuilder'),
            'type' => 'textarea',
            'default' => "Product A, 30\nProduct B, 25\nProduct C, 20\nProduct D, 15\nProduct E, 10",
            'description' => __('Enter data in format: Label, Value (one per line)', 'probuilder'),
        ]);
        
        $this->add_control('show_legend', [
            'label' => __('Show Legend', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('legend_position', [
            'label' => __('Legend Position', 'probuilder'),
            'type' => 'select',
            'default' => 'bottom',
            'options' => [
                'top' => __('Top', 'probuilder'),
                'bottom' => __('Bottom', 'probuilder'),
                'left' => __('Left', 'probuilder'),
                'right' => __('Right', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section('section_style', [
            'label' => __('Chart Style', 'probuilder'),
            'tab' => 'style',
        ]);
        
        $this->add_control('chart_height', [
            'label' => __('Chart Height (px)', 'probuilder'),
            'type' => 'slider',
            'default' => 400,
            'range' => [
                'px' => ['min' => 200, 'max' => 800, 'step' => 50],
            ],
        ]);
        
        $this->add_control('colors_scheme', [
            'label' => __('Color Scheme', 'probuilder'),
            'type' => 'select',
            'default' => 'vibrant',
            'options' => [
                'vibrant' => __('Vibrant', 'probuilder'),
                'pastel' => __('Pastel', 'probuilder'),
                'monochrome' => __('Monochrome', 'probuilder'),
                'custom' => __('Custom', 'probuilder'),
            ],
        ]);
        
        $this->add_control('custom_colors', [
            'label' => __('Custom Colors', 'probuilder'),
            'type' => 'textarea',
            'default' => "#FF6384, #36A2EB, #FFCE56, #4BC0C0, #9966FF",
            'description' => __('Enter hex colors separated by comma', 'probuilder'),
        ]);
        
        $this->add_control('show_values', [
            'label' => __('Show Values', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('animation_duration', [
            'label' => __('Animation Duration (ms)', 'probuilder'),
            'type' => 'number',
            'default' => 1000,
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $chart_title = $this->get_settings('chart_title', 'Sales Distribution');
        $show_title = $this->get_settings('show_title', 'yes');
        $chart_data = $this->get_settings('chart_data', "Product A, 30\nProduct B, 25\nProduct C, 20");
        $show_legend = $this->get_settings('show_legend', 'yes');
        $legend_position = $this->get_settings('legend_position', 'bottom');
        $chart_height = $this->get_settings('chart_height', 400);
        $colors_scheme = $this->get_settings('colors_scheme', 'vibrant');
        $custom_colors = $this->get_settings('custom_colors', '#FF6384, #36A2EB, #FFCE56');
        $show_values = $this->get_settings('show_values', 'yes');
        $animation_duration = $this->get_settings('animation_duration', 1000);
        
        // Parse chart data
        $lines = explode("\n", trim($chart_data));
        $labels = [];
        $values = [];
        
        foreach ($lines as $line) {
            $parts = array_map('trim', explode(',', $line));
            if (count($parts) >= 2) {
                $labels[] = $parts[0];
                $values[] = floatval($parts[1]);
            }
        }
        
        // Define color schemes
        $color_schemes = [
            'vibrant' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'],
            'pastel' => ['#FFB3BA', '#BAFFC9', '#BAE1FF', '#FFFFBA', '#FFD4BA', '#E0BBE4', '#FFDFD3', '#C7CEEA'],
            'monochrome' => ['#1a1a1a', '#333333', '#4d4d4d', '#666666', '#808080', '#999999', '#b3b3b3', '#cccccc'],
        ];
        
        if ($colors_scheme === 'custom') {
            $colors = array_map('trim', explode(',', $custom_colors));
        } else {
            $colors = isset($color_schemes[$colors_scheme]) ? $color_schemes[$colors_scheme] : $color_schemes['vibrant'];
        }
        
        $id = 'pie-chart-' . uniqid();
        
        $wrapper_style = 'padding: 20px;';
        if ($inline_styles) $wrapper_style .= ' ' . $inline_styles;
        
        echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-pie-chart" ' . $wrapper_attributes . ' style="' . esc_attr($wrapper_style) . '">';
        
        if ($show_title === 'yes' && !empty($chart_title)) {
            echo '<h3 style="text-align: center; margin-bottom: 20px; font-size: 24px; font-weight: 600;">' . esc_html($chart_title) . '</h3>';
        }
        
        echo '<div style="position: relative; height: ' . esc_attr($chart_height) . 'px;">';
        echo '<canvas id="' . esc_attr($id) . '"></canvas>';
        echo '</div>';
        echo '</div>';
        
        // Enqueue Chart.js
        wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js', [], '4.4.0', true);
        
        // Initialize chart
        ?>
        <script>
        (function() {
            function initPieChart() {
                if (typeof Chart === 'undefined') {
                    setTimeout(initPieChart, 100);
                    return;
                }
                
                var ctx = document.getElementById('<?php echo esc_js($id); ?>');
                if (!ctx) return;
                
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: <?php echo json_encode($labels); ?>,
                        datasets: [{
                            data: <?php echo json_encode($values); ?>,
                            backgroundColor: <?php echo json_encode($colors); ?>,
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: <?php echo $show_legend === 'yes' ? 'true' : 'false'; ?>,
                                position: '<?php echo esc_js($legend_position); ?>',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        var label = context.label || '';
                                        var value = context.parsed || 0;
                                        var total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        var percentage = ((value / total) * 100).toFixed(1);
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            },
                            datalabels: false
                        },
                        animation: {
                            duration: <?php echo intval($animation_duration); ?>
                        }
                    }
                });
            }
            
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initPieChart);
            } else {
                initPieChart();
            }
        })();
        </script>
        <?php
    }
}


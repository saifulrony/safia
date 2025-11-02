<?php
/**
 * Line Chart Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Line_Chart extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'line-chart';
        $this->title = __('Line Chart', 'probuilder');
        $this->icon = 'fa fa-chart-line';
        $this->category = 'content';
        $this->keywords = ['chart', 'line', 'graph', 'data', 'statistics', 'trend'];
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
            'default' => __('Monthly Revenue', 'probuilder'),
        ]);
        
        $this->add_control('show_title', [
            'label' => __('Show Title', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('x_axis_label', [
            'label' => __('X-Axis Label', 'probuilder'),
            'type' => 'text',
            'default' => 'Months',
        ]);
        
        $this->add_control('y_axis_label', [
            'label' => __('Y-Axis Label', 'probuilder'),
            'type' => 'text',
            'default' => 'Revenue ($)',
        ]);
        
        $this->add_control('chart_data', [
            'label' => __('Chart Data', 'probuilder'),
            'type' => 'textarea',
            'default' => "Jan, 4500\nFeb, 5200\nMar, 6100\nApr, 5800\nMay, 7200\nJun, 8500",
            'description' => __('Enter data in format: Label, Value (one per line)', 'probuilder'),
        ]);
        
        $this->add_control('show_grid', [
            'label' => __('Show Grid Lines', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('show_points', [
            'label' => __('Show Data Points', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
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
        
        $this->add_control('line_color', [
            'label' => __('Line Color', 'probuilder'),
            'type' => 'color',
            'default' => '#36A2EB',
        ]);
        
        $this->add_control('line_width', [
            'label' => __('Line Width', 'probuilder'),
            'type' => 'slider',
            'default' => 3,
            'range' => [
                'px' => ['min' => 1, 'max' => 10, 'step' => 1],
            ],
        ]);
        
        $this->add_control('fill_area', [
            'label' => __('Fill Area Under Line', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
        ]);
        
        $this->add_control('fill_color', [
            'label' => __('Fill Color', 'probuilder'),
            'type' => 'color',
            'default' => 'rgba(54, 162, 235, 0.2)',
        ]);
        
        $this->add_control('curve_tension', [
            'label' => __('Curve Smoothness', 'probuilder'),
            'type' => 'slider',
            'default' => 0.4,
            'range' => [
                'px' => ['min' => 0, 'max' => 1, 'step' => 0.1],
            ],
            'description' => __('0 = straight lines, 1 = very curved', 'probuilder'),
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
        
        $chart_title = $this->get_settings('chart_title', 'Monthly Revenue');
        $show_title = $this->get_settings('show_title', 'yes');
        $x_axis_label = $this->get_settings('x_axis_label', 'Months');
        $y_axis_label = $this->get_settings('y_axis_label', 'Revenue ($)');
        $chart_data = $this->get_settings('chart_data', "Jan, 4500\nFeb, 5200\nMar, 6100");
        $show_grid = $this->get_settings('show_grid', 'yes');
        $show_points = $this->get_settings('show_points', 'yes');
        $chart_height = $this->get_settings('chart_height', 400);
        $line_color = $this->get_settings('line_color', '#36A2EB');
        $line_width = $this->get_settings('line_width', 3);
        $fill_area = $this->get_settings('fill_area', 'no');
        $fill_color = $this->get_settings('fill_color', 'rgba(54, 162, 235, 0.2)');
        $curve_tension = $this->get_settings('curve_tension', 0.4);
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
        
        $id = 'line-chart-' . uniqid();
        
        $wrapper_style = 'padding: 20px;';
        if ($inline_styles) $wrapper_style .= ' ' . $inline_styles;
        
        echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-line-chart" ' . $wrapper_attributes . ' style="' . esc_attr($wrapper_style) . '">';
        
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
            function initLineChart() {
                if (typeof Chart === 'undefined') {
                    setTimeout(initLineChart, 100);
                    return;
                }
                
                var ctx = document.getElementById('<?php echo esc_js($id); ?>');
                if (!ctx) return;
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: <?php echo json_encode($labels); ?>,
                        datasets: [{
                            label: '<?php echo esc_js($y_axis_label); ?>',
                            data: <?php echo json_encode($values); ?>,
                            borderColor: '<?php echo esc_js($line_color); ?>',
                            backgroundColor: '<?php echo esc_js($fill_color); ?>',
                            borderWidth: <?php echo intval($line_width); ?>,
                            fill: <?php echo $fill_area === 'yes' ? 'true' : 'false'; ?>,
                            tension: <?php echo floatval($curve_tension); ?>,
                            pointRadius: <?php echo $show_points === 'yes' ? 5 : 0; ?>,
                            pointHoverRadius: <?php echo $show_points === 'yes' ? 7 : 0; ?>,
                            pointBackgroundColor: '<?php echo esc_js($line_color); ?>',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                            }
                        },
                        scales: {
                            x: {
                                display: true,
                                title: {
                                    display: <?php echo !empty($x_axis_label) ? 'true' : 'false'; ?>,
                                    text: '<?php echo esc_js($x_axis_label); ?>'
                                },
                                grid: {
                                    display: <?php echo $show_grid === 'yes' ? 'true' : 'false'; ?>
                                }
                            },
                            y: {
                                display: true,
                                title: {
                                    display: <?php echo !empty($y_axis_label) ? 'true' : 'false'; ?>,
                                    text: '<?php echo esc_js($y_axis_label); ?>'
                                },
                                grid: {
                                    display: <?php echo $show_grid === 'yes' ? 'true' : 'false'; ?>
                                }
                            }
                        },
                        animation: {
                            duration: <?php echo intval($animation_duration); ?>
                        }
                    }
                });
            }
            
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initLineChart);
            } else {
                initLineChart();
            }
        })();
        </script>
        <?php
    }
}


<?php
/**
 * Area Chart Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Area_Chart extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'area-chart';
        $this->title = __('Area Chart', 'probuilder');
        $this->icon = 'fa fa-chart-area';
        $this->category = 'content';
        $this->keywords = ['chart', 'area', 'graph', 'data', 'statistics', 'trend'];
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
            'default' => __('Website Traffic', 'probuilder'),
        ]);
        
        $this->add_control('show_title', [
            'label' => __('Show Title', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('x_axis_label', [
            'label' => __('X-Axis Label', 'probuilder'),
            'type' => 'text',
            'default' => 'Time Period',
        ]);
        
        $this->add_control('y_axis_label', [
            'label' => __('Y-Axis Label', 'probuilder'),
            'type' => 'text',
            'default' => 'Visitors',
        ]);
        
        $this->add_control('chart_data', [
            'label' => __('Chart Data', 'probuilder'),
            'type' => 'textarea',
            'default' => "Week 1, 2400\nWeek 2, 3200\nWeek 3, 2800\nWeek 4, 4100\nWeek 5, 3900\nWeek 6, 5200",
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
            'default' => '#4BC0C0',
        ]);
        
        $this->add_control('fill_type', [
            'label' => __('Fill Type', 'probuilder'),
            'type' => 'select',
            'default' => 'solid',
            'options' => [
                'solid' => __('Solid', 'probuilder'),
                'gradient' => __('Gradient', 'probuilder'),
            ],
        ]);
        
        $this->add_control('fill_color', [
            'label' => __('Fill Color', 'probuilder'),
            'type' => 'color',
            'default' => 'rgba(75, 192, 192, 0.4)',
        ]);
        
        $this->add_control('fill_opacity', [
            'label' => __('Fill Opacity', 'probuilder'),
            'type' => 'slider',
            'default' => 40,
            'range' => [
                'px' => ['min' => 0, 'max' => 100, 'step' => 5],
            ],
        ]);
        
        $this->add_control('line_width', [
            'label' => __('Line Width', 'probuilder'),
            'type' => 'slider',
            'default' => 3,
            'range' => [
                'px' => ['min' => 1, 'max' => 10, 'step' => 1],
            ],
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
        
        $chart_title = $this->get_settings('chart_title', 'Website Traffic');
        $show_title = $this->get_settings('show_title', 'yes');
        $x_axis_label = $this->get_settings('x_axis_label', 'Time Period');
        $y_axis_label = $this->get_settings('y_axis_label', 'Visitors');
        $chart_data = $this->get_settings('chart_data', "Week 1, 2400\nWeek 2, 3200");
        $show_grid = $this->get_settings('show_grid', 'yes');
        $show_points = $this->get_settings('show_points', 'yes');
        $chart_height = $this->get_settings('chart_height', 400);
        $line_color = $this->get_settings('line_color', '#4BC0C0');
        $fill_type = $this->get_settings('fill_type', 'solid');
        $fill_color = $this->get_settings('fill_color', 'rgba(75, 192, 192, 0.4)');
        $fill_opacity = $this->get_settings('fill_opacity', 40);
        $line_width = $this->get_settings('line_width', 3);
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
        
        $id = 'area-chart-' . uniqid();
        
        $wrapper_style = 'padding: 20px;';
        if ($inline_styles) $wrapper_style .= ' ' . $inline_styles;
        
        echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-area-chart" ' . $wrapper_attributes . ' style="' . esc_attr($wrapper_style) . '">';
        
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
            function initAreaChart() {
                if (typeof Chart === 'undefined') {
                    setTimeout(initAreaChart, 100);
                    return;
                }
                
                var ctx = document.getElementById('<?php echo esc_js($id); ?>');
                if (!ctx) return;
                
                // Create fill color
                var fillColor = '<?php echo esc_js($fill_color); ?>';
                <?php if ($fill_type === 'gradient') : ?>
                var gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, <?php echo intval($chart_height); ?>);
                var lineColor = '<?php echo esc_js($line_color); ?>';
                var opacity = <?php echo intval($fill_opacity) / 100; ?>;
                gradient.addColorStop(0, lineColor.replace(')', ', ' + opacity + ')').replace('rgb', 'rgba'));
                gradient.addColorStop(1, lineColor.replace(')', ', 0.05)').replace('rgb', 'rgba'));
                fillColor = gradient;
                <?php endif; ?>
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: <?php echo json_encode($labels); ?>,
                        datasets: [{
                            label: '<?php echo esc_js($y_axis_label); ?>',
                            data: <?php echo json_encode($values); ?>,
                            borderColor: '<?php echo esc_js($line_color); ?>',
                            backgroundColor: fillColor,
                            borderWidth: <?php echo intval($line_width); ?>,
                            fill: true,
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
                                beginAtZero: true,
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
                document.addEventListener('DOMContentLoaded', initAreaChart);
            } else {
                initAreaChart();
            }
        })();
        </script>
        <?php
    }
}


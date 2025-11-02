<?php
/**
 * Bar Chart Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Bar_Chart extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'bar-chart';
        $this->title = __('Bar Chart', 'probuilder');
        $this->icon = 'fa fa-chart-bar';
        $this->category = 'content';
        $this->keywords = ['chart', 'bar', 'column', 'graph', 'data', 'statistics'];
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
            'default' => __('Sales by Category', 'probuilder'),
        ]);
        
        $this->add_control('show_title', [
            'label' => __('Show Title', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('x_axis_label', [
            'label' => __('X-Axis Label', 'probuilder'),
            'type' => 'text',
            'default' => 'Categories',
        ]);
        
        $this->add_control('y_axis_label', [
            'label' => __('Y-Axis Label', 'probuilder'),
            'type' => 'text',
            'default' => 'Sales',
        ]);
        
        $this->add_control('chart_data', [
            'label' => __('Chart Data', 'probuilder'),
            'type' => 'textarea',
            'default' => "Electronics, 12500\nClothing, 9800\nHome & Garden, 7600\nSports, 6400\nBooks, 5200",
            'description' => __('Enter data in format: Label, Value (one per line)', 'probuilder'),
        ]);
        
        $this->add_control('orientation', [
            'label' => __('Orientation', 'probuilder'),
            'type' => 'select',
            'default' => 'vertical',
            'options' => [
                'vertical' => __('Vertical', 'probuilder'),
                'horizontal' => __('Horizontal', 'probuilder'),
            ],
        ]);
        
        $this->add_control('show_grid', [
            'label' => __('Show Grid Lines', 'probuilder'),
            'type' => 'switcher',
            'default' => 'yes',
        ]);
        
        $this->add_control('show_values', [
            'label' => __('Show Values on Bars', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
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
        
        $this->add_control('color_mode', [
            'label' => __('Color Mode', 'probuilder'),
            'type' => 'select',
            'default' => 'single',
            'options' => [
                'single' => __('Single Color', 'probuilder'),
                'gradient' => __('Gradient', 'probuilder'),
                'multi' => __('Multiple Colors', 'probuilder'),
            ],
        ]);
        
        $this->add_control('bar_color', [
            'label' => __('Bar Color', 'probuilder'),
            'type' => 'color',
            'default' => '#36A2EB',
        ]);
        
        $this->add_control('gradient_color', [
            'label' => __('Gradient End Color', 'probuilder'),
            'type' => 'color',
            'default' => '#9966FF',
        ]);
        
        $this->add_control('multi_colors', [
            'label' => __('Multiple Colors', 'probuilder'),
            'type' => 'textarea',
            'default' => '#FF6384, #36A2EB, #FFCE56, #4BC0C0, #9966FF, #FF9F40',
            'description' => __('Enter hex colors separated by comma (one per bar)', 'probuilder'),
        ]);
        
        $this->add_control('bar_thickness', [
            'label' => __('Bar Thickness', 'probuilder'),
            'type' => 'slider',
            'default' => 40,
            'range' => [
                'px' => ['min' => 10, 'max' => 100, 'step' => 5],
            ],
        ]);
        
        $this->add_control('bar_spacing', [
            'label' => __('Bar Spacing', 'probuilder'),
            'type' => 'slider',
            'default' => 0.8,
            'range' => [
                'px' => ['min' => 0.1, 'max' => 1, 'step' => 0.1],
            ],
            'description' => __('1 = no space, 0.1 = maximum space', 'probuilder'),
        ]);
        
        $this->add_control('border_radius', [
            'label' => __('Border Radius', 'probuilder'),
            'type' => 'slider',
            'default' => 4,
            'range' => [
                'px' => ['min' => 0, 'max' => 20, 'step' => 1],
            ],
        ]);
        
        $this->add_control('bar_border_width', [
            'label' => __('Bar Border Width', 'probuilder'),
            'type' => 'slider',
            'default' => 0,
            'range' => [
                'px' => ['min' => 0, 'max' => 5, 'step' => 1],
            ],
        ]);
        
        $this->add_control('bar_border_color', [
            'label' => __('Bar Border Color', 'probuilder'),
            'type' => 'color',
            'default' => '#ffffff',
        ]);
        
        $this->add_control('animation_duration', [
            'label' => __('Animation Duration (ms)', 'probuilder'),
            'type' => 'number',
            'default' => 1000,
        ]);
        
        $this->add_control('animation_easing', [
            'label' => __('Animation Easing', 'probuilder'),
            'type' => 'select',
            'default' => 'easeOutQuart',
            'options' => [
                'linear' => __('Linear', 'probuilder'),
                'easeInQuad' => __('Ease In Quad', 'probuilder'),
                'easeOutQuart' => __('Ease Out Quart', 'probuilder'),
                'easeInOutQuart' => __('Ease In Out Quart', 'probuilder'),
            ],
        ]);
        
        $this->end_controls_section();
        
        // Value Display Section
        $this->start_controls_section('section_values', [
            'label' => __('Value Display', 'probuilder'),
            'tab' => 'style',
        ]);
        
        $this->add_control('show_values_on_bars', [
            'label' => __('Show Values on Bars', 'probuilder'),
            'type' => 'switcher',
            'default' => 'no',
        ]);
        
        $this->add_control('value_position', [
            'label' => __('Value Position', 'probuilder'),
            'type' => 'select',
            'default' => 'end',
            'options' => [
                'end' => __('End (Outside)', 'probuilder'),
                'center' => __('Center (Inside)', 'probuilder'),
                'start' => __('Start (Base)', 'probuilder'),
            ],
        ]);
        
        $this->add_control('value_font_size', [
            'label' => __('Value Font Size', 'probuilder'),
            'type' => 'slider',
            'default' => 12,
            'range' => [
                'px' => ['min' => 8, 'max' => 24, 'step' => 1],
            ],
        ]);
        
        $this->add_control('value_color', [
            'label' => __('Value Color', 'probuilder'),
            'type' => 'color',
            'default' => '#666666',
        ]);
        
        $this->add_control('value_format', [
            'label' => __('Value Format', 'probuilder'),
            'type' => 'select',
            'default' => 'number',
            'options' => [
                'number' => __('Number', 'probuilder'),
                'currency' => __('Currency ($)', 'probuilder'),
                'percentage' => __('Percentage (%)', 'probuilder'),
            ],
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
        
        $chart_title = $this->get_settings('chart_title', 'Sales by Category');
        $show_title = $this->get_settings('show_title', 'yes');
        $x_axis_label = $this->get_settings('x_axis_label', 'Categories');
        $y_axis_label = $this->get_settings('y_axis_label', 'Sales');
        $chart_data = $this->get_settings('chart_data', "Electronics, 12500\nClothing, 9800");
        $orientation = $this->get_settings('orientation', 'vertical');
        $show_grid = $this->get_settings('show_grid', 'yes');
        $show_values = $this->get_settings('show_values', 'no');
        $chart_height = $this->get_settings('chart_height', 400);
        $color_mode = $this->get_settings('color_mode', 'single');
        $bar_color = $this->get_settings('bar_color', '#36A2EB');
        $gradient_color = $this->get_settings('gradient_color', '#9966FF');
        $multi_colors = $this->get_settings('multi_colors', '#FF6384, #36A2EB, #FFCE56, #4BC0C0, #9966FF, #FF9F40');
        $bar_thickness = $this->get_settings('bar_thickness', 40);
        $bar_spacing = $this->get_settings('bar_spacing', 0.8);
        $border_radius = $this->get_settings('border_radius', 4);
        $bar_border_width = $this->get_settings('bar_border_width', 0);
        $bar_border_color = $this->get_settings('bar_border_color', '#ffffff');
        $animation_duration = $this->get_settings('animation_duration', 1000);
        $animation_easing = $this->get_settings('animation_easing', 'easeOutQuart');
        $show_values_on_bars = $this->get_settings('show_values_on_bars', 'no');
        $value_position = $this->get_settings('value_position', 'end');
        $value_font_size = $this->get_settings('value_font_size', 12);
        $value_color = $this->get_settings('value_color', '#666666');
        $value_format = $this->get_settings('value_format', 'number');
        
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
        
        $id = 'bar-chart-' . uniqid();
        
        $wrapper_style = 'padding: 20px;';
        if ($inline_styles) $wrapper_style .= ' ' . $inline_styles;
        
        echo '<div class="' . esc_attr($wrapper_classes) . ' probuilder-bar-chart" ' . $wrapper_attributes . ' style="' . esc_attr($wrapper_style) . '">';
        
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
            function initBarChart() {
                if (typeof Chart === 'undefined') {
                    setTimeout(initBarChart, 100);
                    return;
                }
                
                var ctx = document.getElementById('<?php echo esc_js($id); ?>');
                if (!ctx) return;
                
                var canvasCtx = ctx.getContext('2d');
                
                // Prepare colors based on color mode
                var barColors;
                var colorMode = '<?php echo esc_js($color_mode); ?>';
                
                if (colorMode === 'multi') {
                    // Multiple colors - one per bar
                    var multiColors = '<?php echo esc_js($multi_colors); ?>'.split(',').map(function(c) { return c.trim(); });
                    barColors = <?php echo json_encode($labels); ?>.map(function(label, i) {
                        return multiColors[i % multiColors.length];
                    });
                } else if (colorMode === 'gradient') {
                    // Single gradient for all bars
                    var gradient = canvasCtx.createLinearGradient(0, 0, 0, <?php echo intval($chart_height); ?>);
                    gradient.addColorStop(0, '<?php echo esc_js($bar_color); ?>');
                    gradient.addColorStop(1, '<?php echo esc_js($gradient_color); ?>');
                    barColors = gradient;
                } else {
                    // Single color for all bars
                    barColors = '<?php echo esc_js($bar_color); ?>';
                }
                
                // Value formatter
                function formatValue(value) {
                    var format = '<?php echo esc_js($value_format); ?>';
                    if (format === 'currency') {
                        return '$' + value.toLocaleString();
                    } else if (format === 'percentage') {
                        return value.toLocaleString() + '%';
                    }
                    return value.toLocaleString();
                }
                
                // Chart.js data labels plugin
                var datalabelsPlugin = {
                    id: 'datalabels',
                    afterDatasetsDraw: function(chart) {
                        if ('<?php echo esc_js($show_values_on_bars); ?>' !== 'yes') return;
                        
                        var ctx = chart.ctx;
                        var position = '<?php echo esc_js($value_position); ?>';
                        
                        chart.data.datasets.forEach(function(dataset, i) {
                            var meta = chart.getDatasetMeta(i);
                            if (!meta.hidden) {
                                meta.data.forEach(function(element, index) {
                                    ctx.fillStyle = '<?php echo esc_js($value_color); ?>';
                                    ctx.font = '<?php echo intval($value_font_size); ?>px Arial';
                                    ctx.textAlign = 'center';
                                    ctx.textBaseline = 'middle';
                                    
                                    var dataString = formatValue(dataset.data[index]);
                                    var x, y;
                                    
                                    if ('<?php echo esc_js($orientation); ?>' === 'horizontal') {
                                        if (position === 'end') {
                                            x = element.x + 5;
                                            y = element.y;
                                            ctx.textAlign = 'left';
                                        } else if (position === 'center') {
                                            x = element.x / 2;
                                            y = element.y;
                                        } else {
                                            x = element.base + 5;
                                            y = element.y;
                                            ctx.textAlign = 'left';
                                        }
                                    } else {
                                        if (position === 'end') {
                                            x = element.x;
                                            y = element.y - 5;
                                        } else if (position === 'center') {
                                            x = element.x;
                                            y = (element.y + element.base) / 2;
                                        } else {
                                            x = element.x;
                                            y = element.base - 5;
                                        }
                                    }
                                    
                                    ctx.fillText(dataString, x, y);
                                });
                            }
                        });
                    }
                };
                
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode($labels); ?>,
                        datasets: [{
                            label: '<?php echo esc_js($y_axis_label); ?>',
                            data: <?php echo json_encode($values); ?>,
                            backgroundColor: barColors,
                            borderColor: '<?php echo esc_js($bar_border_color); ?>',
                            borderWidth: <?php echo intval($bar_border_width); ?>,
                            borderRadius: <?php echo intval($border_radius); ?>,
                            barThickness: <?php echo intval($bar_thickness); ?>,
                            barPercentage: <?php echo floatval($bar_spacing); ?>
                        }]
                    },
                    options: {
                        indexAxis: '<?php echo $orientation === 'horizontal' ? 'y' : 'x'; ?>',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function(context) {
                                        var label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        label += formatValue(context.parsed.y || context.parsed.x);
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                display: true,
                                title: {
                                    display: <?php echo !empty($x_axis_label) && $orientation === 'vertical' ? 'true' : 'false'; ?>,
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
                                    display: <?php echo !empty($y_axis_label) && $orientation === 'vertical' ? 'true' : 'false'; ?>,
                                    text: '<?php echo esc_js($y_axis_label); ?>'
                                },
                                grid: {
                                    display: <?php echo $show_grid === 'yes' ? 'true' : 'false'; ?>
                                }
                            }
                        },
                        animation: {
                            duration: <?php echo intval($animation_duration); ?>,
                            easing: '<?php echo esc_js($animation_easing); ?>'
                        }
                    },
                    plugins: [datalabelsPlugin]
                });
            }
            
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initBarChart);
            } else {
                initBarChart();
            }
        })();
        </script>
        <?php
    }
}


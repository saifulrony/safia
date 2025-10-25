<?php
/**
 * Form Builder Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Form_Builder extends ProBuilder_Base_Widget {
    
    public function get_name() {
        return 'form-builder';
    }
    
    public function get_title() {
        return 'Form Builder';
    }
    
    public function get_icon() {
        return 'fa fa-wpforms';
    }
    
    public function get_category() {
        return 'forms';
    }
    
    public function get_keywords() {
        return ['form', 'contact', 'survey', 'registration', 'builder'];
    }
    
    protected function register_controls() {
        // Content Tab
        $this->start_controls_section('content_section', [
            'label' => 'Form Content',
            'tab' => 'content'
        ]);
        
        $this->add_control('form_title', [
            'label' => 'Form Title',
            'type' => 'text',
            'default' => 'Contact Us',
            'placeholder' => 'Enter form title'
        ]);
        
        $this->add_control('form_description', [
            'label' => 'Form Description',
            'type' => 'textarea',
            'default' => 'Send us a message and we\'ll get back to you as soon as possible.',
            'placeholder' => 'Enter form description'
        ]);
        
        $this->add_control('form_fields', [
            'label' => 'Form Fields',
            'type' => 'repeater',
            'fields' => [
                [
                    'name' => 'field_type',
                    'label' => 'Field Type',
                    'type' => 'select',
                    'options' => [
                        'text' => 'Text',
                        'email' => 'Email',
                        'tel' => 'Phone',
                        'textarea' => 'Textarea',
                        'select' => 'Select',
                        'checkbox' => 'Checkbox',
                        'radio' => 'Radio',
                        'file' => 'File Upload',
                        'date' => 'Date',
                        'number' => 'Number'
                    ],
                    'default' => 'text'
                ],
                [
                    'name' => 'field_label',
                    'label' => 'Field Label',
                    'type' => 'text',
                    'default' => 'Field Label'
                ],
                [
                    'name' => 'field_placeholder',
                    'label' => 'Placeholder',
                    'type' => 'text',
                    'default' => 'Enter text...'
                ],
                [
                    'name' => 'field_required',
                    'label' => 'Required',
                    'type' => 'switcher',
                    'default' => 'no'
                ],
                [
                    'name' => 'field_options',
                    'label' => 'Options (for select/radio/checkbox)',
                    'type' => 'textarea',
                    'description' => 'One option per line'
                ]
            ],
            'default' => [
                [
                    'field_type' => 'text',
                    'field_label' => 'Name',
                    'field_placeholder' => 'Your Name',
                    'field_required' => 'yes'
                ],
                [
                    'field_type' => 'email',
                    'field_label' => 'Email',
                    'field_placeholder' => 'your@email.com',
                    'field_required' => 'yes'
                ],
                [
                    'field_type' => 'textarea',
                    'field_label' => 'Message',
                    'field_placeholder' => 'Your message...',
                    'field_required' => 'yes'
                ]
            ]
        ]);
        
        $this->add_control('submit_button_text', [
            'label' => 'Submit Button Text',
            'type' => 'text',
            'default' => 'Send Message'
        ]);
        
        $this->add_control('success_message', [
            'label' => 'Success Message',
            'type' => 'text',
            'default' => 'Thank you! Your message has been sent.'
        ]);
        
        $this->end_controls_section();
        
        // Style Tab
        $this->start_controls_section('style_section', [
            'label' => 'Form Style',
            'tab' => 'style'
        ]);
        
        $this->add_control('form_bg_color', [
            'label' => 'Form Background',
            'type' => 'color',
            'default' => '#ffffff'
        ]);
        
        $this->add_control('form_padding', [
            'label' => 'Form Padding',
            'type' => 'dimensions',
            'default' => ['top' => 30, 'right' => 30, 'bottom' => 30, 'left' => 30]
        ]);
        
        $this->add_control('form_border_radius', [
            'label' => 'Border Radius',
            'type' => 'slider',
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'default' => ['size' => 8]
        ]);
        
        $this->add_control('form_box_shadow', [
            'label' => 'Box Shadow',
            'type' => 'switcher',
            'default' => 'yes'
        ]);
        
        $this->add_control('field_bg_color', [
            'label' => 'Field Background',
            'type' => 'color',
            'default' => '#ffffff'
        ]);
        
        $this->add_control('field_border_color', [
            'label' => 'Field Border Color',
            'type' => 'color',
            'default' => '#e1e5e9'
        ]);
        
        $this->add_control('field_focus_color', [
            'label' => 'Field Focus Color',
            'type' => 'color',
            'default' => '#92003b'
        ]);
        
        $this->add_control('button_bg_color', [
            'label' => 'Button Background',
            'type' => 'color',
            'default' => '#92003b'
        ]);
        
        $this->add_control('button_text_color', [
            'label' => 'Button Text Color',
            'type' => 'color',
            'default' => '#ffffff'
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $form_id = 'probuilder-form-' . $this->get_id();
        
        $form_style = '';
        $form_style .= 'background-color: ' . esc_attr($settings['form_bg_color']) . ';';
        $form_style .= 'padding: ' . esc_attr($settings['form_padding']['top']) . 'px ' . esc_attr($settings['form_padding']['right']) . 'px ' . esc_attr($settings['form_padding']['bottom']) . 'px ' . esc_attr($settings['form_padding']['left']) . 'px;';
        $form_style .= 'border-radius: ' . esc_attr($settings['form_border_radius']['size']) . 'px;';
        
        if ($settings['form_box_shadow'] === 'yes') {
            $form_style .= 'box-shadow: 0 4px 20px rgba(0,0,0,0.1);';
        }
        
        echo '<div class="probuilder-form-wrapper" style="' . $form_style . '">';
        
        if (!empty($settings['form_title'])) {
            echo '<h3 class="probuilder-form-title" style="margin-top: 0; margin-bottom: 15px; color: #1e293b;">' . esc_html($settings['form_title']) . '</h3>';
        }
        
        if (!empty($settings['form_description'])) {
            echo '<p class="probuilder-form-description" style="margin-bottom: 25px; color: #64748b;">' . esc_html($settings['form_description']) . '</p>';
        }
        
        echo '<form id="' . esc_attr($form_id) . '" class="probuilder-form" method="post">';
        echo '<input type="hidden" name="probuilder_form_id" value="' . esc_attr($this->get_id()) . '">';
        
        foreach ($settings['form_fields'] as $field) {
            $field_id = 'field-' . sanitize_title($field['field_label']);
            $required = $field['field_required'] === 'yes' ? 'required' : '';
            $required_attr = $field['field_required'] === 'yes' ? ' *' : '';
            
            echo '<div class="probuilder-form-field" style="margin-bottom: 20px;">';
            echo '<label for="' . esc_attr($field_id) . '" style="display: block; margin-bottom: 5px; font-weight: 600; color: #1e293b;">' . esc_html($field['field_label']) . $required_attr . '</label>';
            
            switch ($field['field_type']) {
                case 'textarea':
                    echo '<textarea id="' . esc_attr($field_id) . '" name="' . esc_attr($field_id) . '" placeholder="' . esc_attr($field['field_placeholder']) . '" ' . $required . ' style="width: 100%; padding: 12px; border: 1px solid ' . esc_attr($settings['field_border_color']) . '; border-radius: 4px; background-color: ' . esc_attr($settings['field_bg_color']) . '; font-family: inherit; resize: vertical; min-height: 100px;"></textarea>';
                    break;
                    
                case 'select':
                    echo '<select id="' . esc_attr($field_id) . '" name="' . esc_attr($field_id) . '" ' . $required . ' style="width: 100%; padding: 12px; border: 1px solid ' . esc_attr($settings['field_border_color']) . '; border-radius: 4px; background-color: ' . esc_attr($settings['field_bg_color']) . '; font-family: inherit;">';
                    echo '<option value="">' . esc_html($field['field_placeholder']) . '</option>';
                    if (!empty($field['field_options'])) {
                        $options = explode("\n", $field['field_options']);
                        foreach ($options as $option) {
                            $option = trim($option);
                            if (!empty($option)) {
                                echo '<option value="' . esc_attr($option) . '">' . esc_html($option) . '</option>';
                            }
                        }
                    }
                    echo '</select>';
                    break;
                    
                case 'checkbox':
                    if (!empty($field['field_options'])) {
                        $options = explode("\n", $field['field_options']);
                        foreach ($options as $option) {
                            $option = trim($option);
                            if (!empty($option)) {
                                $option_id = $field_id . '-' . sanitize_title($option);
                                echo '<label style="display: block; margin-bottom: 8px; font-weight: normal;">';
                                echo '<input type="checkbox" id="' . esc_attr($option_id) . '" name="' . esc_attr($field_id) . '[]" value="' . esc_attr($option) . '" style="margin-right: 8px;">';
                                echo esc_html($option);
                                echo '</label>';
                            }
                        }
                    }
                    break;
                    
                case 'radio':
                    if (!empty($field['field_options'])) {
                        $options = explode("\n", $field['field_options']);
                        foreach ($options as $option) {
                            $option = trim($option);
                            if (!empty($option)) {
                                $option_id = $field_id . '-' . sanitize_title($option);
                                echo '<label style="display: block; margin-bottom: 8px; font-weight: normal;">';
                                echo '<input type="radio" id="' . esc_attr($option_id) . '" name="' . esc_attr($field_id) . '" value="' . esc_attr($option) . '" ' . $required . ' style="margin-right: 8px;">';
                                echo esc_html($option);
                                echo '</label>';
                            }
                        }
                    }
                    break;
                    
                default:
                    $input_type = in_array($field['field_type'], ['email', 'tel', 'date', 'number']) ? $field['field_type'] : 'text';
                    echo '<input type="' . esc_attr($input_type) . '" id="' . esc_attr($field_id) . '" name="' . esc_attr($field_id) . '" placeholder="' . esc_attr($field['field_placeholder']) . '" ' . $required . ' style="width: 100%; padding: 12px; border: 1px solid ' . esc_attr($settings['field_border_color']) . '; border-radius: 4px; background-color: ' . esc_attr($settings['field_bg_color']) . '; font-family: inherit;">';
                    break;
            }
            
            echo '</div>';
        }
        
        echo '<div class="probuilder-form-submit" style="margin-top: 25px;">';
        echo '<button type="submit" style="background-color: ' . esc_attr($settings['button_bg_color']) . '; color: ' . esc_attr($settings['button_text_color']) . '; padding: 12px 30px; border: none; border-radius: 4px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">' . esc_html($settings['submit_button_text']) . '</button>';
        echo '</div>';
        
        echo '</form>';
        echo '</div>';
        
        // Add JavaScript for form handling
        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.getElementById("' . esc_js($form_id) . '");
            if (form) {
                form.addEventListener("submit", function(e) {
                    e.preventDefault();
                    
                    // Simple form validation and submission
                    const formData = new FormData(form);
                    const submitBtn = form.querySelector("button[type=submit]");
                    const originalText = submitBtn.textContent;
                    
                    submitBtn.textContent = "Sending...";
                    submitBtn.disabled = true;
                    
                    // Simulate form submission
                    setTimeout(function() {
                        alert("' . esc_js($settings['success_message']) . '");
                        form.reset();
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    }, 1000);
                });
                
                // Add focus styles
                const inputs = form.querySelectorAll("input, textarea, select");
                inputs.forEach(function(input) {
                    input.addEventListener("focus", function() {
                        this.style.borderColor = "' . esc_js($settings['field_focus_color']) . '";
                        this.style.boxShadow = "0 0 0 2px rgba(' . esc_js(str_replace('#', '', $settings['field_focus_color'])) . ', 0.2)";
                    });
                    
                    input.addEventListener("blur", function() {
                        this.style.borderColor = "' . esc_js($settings['field_border_color']) . '";
                        this.style.boxShadow = "none";
                    });
                });
            }
        });
        </script>';
    }
}

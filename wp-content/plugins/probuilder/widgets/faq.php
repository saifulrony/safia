<?php
/**
 * FAQ Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_FAQ extends ProBuilder_Base_Widget {
    
    public function get_name() {
        return 'faq';
    }
    
    public function get_title() {
        return 'FAQ';
    }
    
    public function get_icon() {
        return 'fa fa-question-circle';
    }
    
    public function get_category() {
        return 'content';
    }
    
    public function get_keywords() {
        return ['faq', 'questions', 'answers', 'accordion', 'help'];
    }
    
    protected function register_controls() {
        // Content Tab
        $this->start_controls_section('content_section', [
            'label' => 'FAQ Content',
            'tab' => 'content'
        ]);
        
        $this->add_control('faq_title', [
            'label' => 'FAQ Title',
            'type' => 'text',
            'default' => 'Frequently Asked Questions',
            'placeholder' => 'Enter FAQ title'
        ]);
        
        $this->add_control('faq_description', [
            'label' => 'FAQ Description',
            'type' => 'textarea',
            'default' => 'Find answers to the most common questions about our products and services.',
            'placeholder' => 'Enter FAQ description'
        ]);
        
        $this->add_control('faq_items', [
            'label' => 'FAQ Items',
            'type' => 'repeater',
            'fields' => [
                [
                    'name' => 'question',
                    'label' => 'Question',
                    'type' => 'text',
                    'default' => 'What is your return policy?'
                ],
                [
                    'name' => 'answer',
                    'label' => 'Answer',
                    'type' => 'textarea',
                    'default' => 'We offer a 30-day return policy for all products in original condition.'
                ],
                [
                    'name' => 'icon',
                    'label' => 'Icon (optional)',
                    'type' => 'text',
                    'default' => 'fa fa-question',
                    'placeholder' => 'FontAwesome icon class'
                ]
            ],
            'default' => [
                [
                    'question' => 'What is your return policy?',
                    'answer' => 'We offer a 30-day return policy for all products in original condition. Simply contact our customer service team to initiate a return.',
                    'icon' => 'fa fa-undo'
                ],
                [
                    'question' => 'How long does shipping take?',
                    'answer' => 'Standard shipping takes 3-5 business days. Express shipping is available for next-day delivery in most areas.',
                    'icon' => 'fa fa-shipping-fast'
                ],
                [
                    'question' => 'Do you offer customer support?',
                    'answer' => 'Yes! Our customer support team is available 24/7 via live chat, email, and phone to help with any questions or issues.',
                    'icon' => 'fa fa-headset'
                ],
                [
                    'question' => 'Can I track my order?',
                    'answer' => 'Absolutely! Once your order ships, you\'ll receive a tracking number via email to monitor your package\'s progress.',
                    'icon' => 'fa fa-map-marker-alt'
                ],
                [
                    'question' => 'What payment methods do you accept?',
                    'answer' => 'We accept all major credit cards, PayPal, Apple Pay, Google Pay, and bank transfers for your convenience.',
                    'icon' => 'fa fa-credit-card'
                ]
            ]
        ]);
        
        $this->add_control('layout', [
            'label' => 'Layout',
            'type' => 'select',
            'options' => [
                'accordion' => 'Accordion',
                'list' => 'List'
            ],
            'default' => 'accordion'
        ]);
        
        $this->add_control('allow_multiple', [
            'label' => 'Allow Multiple Open',
            'type' => 'switcher',
            'default' => 'no',
            'condition' => ['layout' => 'accordion']
        ]);
        
        $this->end_controls_section();
        
        // Style Tab
        $this->start_controls_section('style_section', [
            'label' => 'FAQ Style',
            'tab' => 'style'
        ]);
        
        $this->add_control('title_color', [
            'label' => 'Title Color',
            'type' => 'color',
            'default' => '#1e293b'
        ]);
        
        $this->add_control('description_color', [
            'label' => 'Description Color',
            'type' => 'color',
            'default' => '#64748b'
        ]);
        
        $this->add_control('item_bg_color', [
            'label' => 'Item Background',
            'type' => 'color',
            'default' => '#ffffff'
        ]);
        
        $this->add_control('item_border_color', [
            'label' => 'Item Border Color',
            'type' => 'color',
            'default' => '#e1e5e9'
        ]);
        
        $this->add_control('question_color', [
            'label' => 'Question Color',
            'type' => 'color',
            'default' => '#1e293b'
        ]);
        
        $this->add_control('answer_color', [
            'label' => 'Answer Color',
            'type' => 'color',
            'default' => '#64748b'
        ]);
        
        $this->add_control('icon_color', [
            'label' => 'Icon Color',
            'type' => 'color',
            'default' => '#92003b'
        ]);
        
        $this->add_control('active_color', [
            'label' => 'Active Item Color',
            'type' => 'color',
            'default' => '#92003b'
        ]);
        
        $this->add_control('border_radius', [
            'label' => 'Border Radius',
            'type' => 'slider',
            'range' => ['px' => ['min' => 0, 'max' => 20]],
            'default' => ['size' => 8]
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
        $settings = $this->get_settings_for_display();
        $faq_id = 'probuilder-faq-' . $this->get_id();
        
        $faq_style = '';
        if ($inline_styles) $faq_style = ' style="' . esc_attr($inline_styles) . '"';
        echo '<div id="' . esc_attr($faq_id) . '" class="' . esc_attr($wrapper_classes) . ' probuilder-faq" ' . $wrapper_attributes . $faq_style . '>';
        
        // Title and Description
        if (!empty($settings['faq_title'])) {
            echo '<h2 class="probuilder-faq-title" style="color: ' . esc_attr($settings['title_color']) . '; font-size: 32px; font-weight: 700; margin: 0 0 15px 0; text-align: center;">' . esc_html($settings['faq_title']) . '</h2>';
        }
        
        if (!empty($settings['faq_description'])) {
            echo '<p class="probuilder-faq-description" style="color: ' . esc_attr($settings['description_color']) . '; font-size: 16px; text-align: center; margin: 0 0 40px 0; max-width: 600px; margin-left: auto; margin-right: auto;">' . esc_html($settings['faq_description']) . '</p>';
        }
        
        // FAQ Items
        echo '<div class="probuilder-faq-items" style="max-width: 800px; margin: 0 auto;">';
        
        foreach ($settings['faq_items'] as $index => $item) {
            $item_id = 'faq-item-' . $index;
            $item_style = '';
            $item_style .= 'background-color: ' . esc_attr($settings['item_bg_color']) . ';';
            $item_style .= 'border: 1px solid ' . esc_attr($settings['item_border_color']) . ';';
            $item_style .= 'border-radius: ' . esc_attr($settings['border_radius']['size']) . 'px;';
            $item_style .= 'margin-bottom: 15px;';
            $item_style .= 'overflow: hidden;';
            
            echo '<div class="probuilder-faq-item" style="' . $item_style . '">';
            
            // Question Header
            $header_style = 'padding: 20px; cursor: pointer; display: flex; align-items: center; justify-content: space-between; transition: all 0.3s ease;';
            echo '<div class="probuilder-faq-question" data-target="' . esc_attr($item_id) . '" style="' . $header_style . '">';
            
            echo '<div style="display: flex; align-items: center; gap: 15px;">';
            
            // Icon
            if (!empty($item['icon'])) {
                echo '<i class="' . esc_attr($item['icon']) . '" style="color: ' . esc_attr($settings['icon_color']) . '; font-size: 18px; width: 20px; text-align: center;"></i>';
            }
            
            // Question Text
            echo '<h3 style="margin: 0; color: ' . esc_attr($settings['question_color']) . '; font-size: 18px; font-weight: 600;">' . esc_html($item['question']) . '</h3>';
            
            echo '</div>';
            
            // Toggle Icon
            echo '<i class="fa fa-chevron-down probuilder-faq-toggle" style="color: ' . esc_attr($settings['icon_color']) . '; font-size: 14px; transition: transform 0.3s ease;"></i>';
            
            echo '</div>';
            
            // Answer Content
            $answer_style = 'padding: 0 20px 20px 20px; color: ' . esc_attr($settings['answer_color']) . '; line-height: 1.6; display: none;';
            echo '<div id="' . esc_attr($item_id) . '" class="probuilder-faq-answer" style="' . $answer_style . '">';
            echo '<p style="margin: 0;">' . wp_kses_post(nl2br($item['answer'])) . '</p>';
            echo '</div>';
            
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
        
        // Add JavaScript for FAQ functionality
        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            const faq = document.getElementById("' . esc_js($faq_id) . '");
            if (!faq) return;
            
            const questions = faq.querySelectorAll(".probuilder-faq-question");
            const allowMultiple = ' . ($settings['allow_multiple'] === 'yes' ? 'true' : 'false') . ';
            
            questions.forEach(function(question) {
                question.addEventListener("click", function() {
                    const targetId = this.getAttribute("data-target");
                    const answer = document.getElementById(targetId);
                    const toggle = this.querySelector(".probuilder-faq-toggle");
                    const isOpen = answer.style.display === "block";
                    
                    if (!allowMultiple) {
                        // Close all other answers
                        questions.forEach(function(otherQuestion) {
                            const otherTargetId = otherQuestion.getAttribute("data-target");
                            const otherAnswer = document.getElementById(otherTargetId);
                            const otherToggle = otherQuestion.querySelector(".probuilder-faq-toggle");
                            
                            if (otherTargetId !== targetId) {
                                otherAnswer.style.display = "none";
                                otherToggle.style.transform = "rotate(0deg)";
                                otherQuestion.style.backgroundColor = "' . esc_js($settings['item_bg_color']) . '";
                            }
                        });
                    }
                    
                    // Toggle current answer
                    if (isOpen) {
                        answer.style.display = "none";
                        toggle.style.transform = "rotate(0deg)";
                        this.style.backgroundColor = "' . esc_js($settings['item_bg_color']) . '";
                    } else {
                        answer.style.display = "block";
                        toggle.style.transform = "rotate(180deg)";
                        this.style.backgroundColor = "' . esc_js($settings['active_color']) . '";
                    }
                });
                
                // Hover effects
                question.addEventListener("mouseenter", function() {
                    if (this.style.backgroundColor !== "' . esc_js($settings['active_color']) . '") {
                        this.style.backgroundColor = "rgba(' . esc_js(str_replace('#', '', $settings['active_color'])) . ', 0.1)";
                    }
                });
                
                question.addEventListener("mouseleave", function() {
                    if (this.style.backgroundColor !== "' . esc_js($settings['active_color']) . '") {
                        this.style.backgroundColor = "' . esc_js($settings['item_bg_color']) . '";
                    }
                });
            });
        });
        </script>';
    }
}

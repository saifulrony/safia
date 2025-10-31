<?php
/**
 * Slider Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Slider extends ProBuilder_Base_Widget {
    
    public function get_name() {
        return 'slider';
    }
    
    public function get_title() {
        return 'Image Slider';
    }
    
    public function get_icon() {
        return 'fa fa-images';
    }
    
    public function get_category() {
        return 'media';
    }
    
    public function get_keywords() {
        return ['slider', 'carousel', 'images', 'gallery', 'slideshow'];
    }
    
    protected function register_controls() {
        // Content Tab
        $this->start_controls_section('content_section', [
            'label' => 'Slider Content',
            'tab' => 'content'
        ]);
        
        $this->add_control('slides', [
            'label' => 'Slides',
            'type' => 'repeater',
            'fields' => [
                [
                    'name' => 'image',
                    'label' => 'Image',
                    'type' => 'media',
                    'default' => ['url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200']
                ],
                [
                    'name' => 'title',
                    'label' => 'Title',
                    'type' => 'text',
                    'default' => 'Slide Title'
                ],
                [
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'textarea',
                    'default' => 'Slide description goes here...'
                ],
                [
                    'name' => 'button_text',
                    'label' => 'Button Text',
                    'type' => 'text',
                    'default' => 'Learn More'
                ],
                [
                    'name' => 'button_link',
                    'label' => 'Button Link',
                    'type' => 'url',
                    'default' => '#'
                ],
                [
                    'name' => 'content_position',
                    'label' => 'Content Position',
                    'type' => 'select',
                    'options' => [
                        'left' => 'Left',
                        'center' => 'Center',
                        'right' => 'Right'
                    ],
                    'default' => 'center'
                ]
            ],
            'default' => [
                [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200'],
                    'title' => 'Welcome to Our Website',
                    'description' => 'Discover amazing products and services that will transform your business.',
                    'button_text' => 'Get Started',
                    'button_link' => '#',
                    'content_position' => 'center'
                ],
                [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1551434678-e076c223a692?w=1200'],
                    'title' => 'Quality Products',
                    'description' => 'We offer the highest quality products with exceptional customer service.',
                    'button_text' => 'Shop Now',
                    'button_link' => '#',
                    'content_position' => 'center'
                ],
                [
                    'image' => ['url' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=1200'],
                    'title' => 'Expert Support',
                    'description' => 'Our team of experts is here to help you every step of the way.',
                    'button_text' => 'Contact Us',
                    'button_link' => '#',
                    'content_position' => 'center'
                ]
            ]
        ]);
        
        $this->add_control('slider_height', [
            'label' => 'Slider Height',
            'type' => 'slider',
            'range' => ['px' => ['min' => 200, 'max' => 800]],
            'default' => ['size' => 500]
        ]);
        
        $this->add_control('autoplay', [
            'label' => 'Autoplay',
            'type' => 'switcher',
            'default' => 'yes'
        ]);
        
        $this->add_control('autoplay_speed', [
            'label' => 'Autoplay Speed (seconds)',
            'type' => 'slider',
            'range' => ['px' => ['min' => 1, 'max' => 10]],
            'default' => ['size' => 5],
            'condition' => ['autoplay' => 'yes']
        ]);
        
        $this->add_control('show_arrows', [
            'label' => 'Show Navigation Arrows',
            'type' => 'switcher',
            'default' => 'yes'
        ]);
        
        $this->add_control('show_dots', [
            'label' => 'Show Dots Navigation',
            'type' => 'switcher',
            'default' => 'yes'
        ]);
        
        $this->add_control('fade_effect', [
            'label' => 'Fade Effect',
            'type' => 'switcher',
            'default' => 'no'
        ]);
        
        $this->end_controls_section();
        
        // Style Tab
        $this->start_controls_section('style_section', [
            'label' => 'Slider Style',
            'tab' => 'style'
        ]);
        
        $this->add_control('overlay_color', [
            'label' => 'Overlay Color',
            'type' => 'color',
            'default' => 'rgba(0,0,0,0.4)'
        ]);
        
        $this->add_control('title_color', [
            'label' => 'Title Color',
            'type' => 'color',
            'default' => '#ffffff'
        ]);
        
        $this->add_control('description_color', [
            'label' => 'Description Color',
            'type' => 'color',
            'default' => '#ffffff'
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
        
        $this->add_control('arrow_color', [
            'label' => 'Arrow Color',
            'type' => 'color',
            'default' => '#ffffff'
        ]);
        
        $this->add_control('dot_color', [
            'label' => 'Dot Color',
            'type' => 'color',
            'default' => '#ffffff'
        ]);
        
        $this->add_control('active_dot_color', [
            'label' => 'Active Dot Color',
            'type' => 'color',
            'default' => '#92003b'
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
        $slider_id = 'probuilder-slider-' . $this->get_id();
        
        $slider_style = 'position: relative; height: ' . esc_attr($settings['slider_height']['size']) . 'px; overflow: hidden; border-radius: 8px;';
        if ($inline_styles) $slider_style .= ' ' . $inline_styles;
        echo '<div id="' . esc_attr($slider_id) . '" class="' . esc_attr($wrapper_classes) . ' probuilder-slider" ' . $wrapper_attributes . ' style="' . esc_attr($slider_style) . '">';
        
        foreach ($settings['slides'] as $index => $slide) {
            $active_class = $index === 0 ? 'active' : '';
            $content_align = $slide['content_position'] === 'left' ? 'flex-start' : ($slide['content_position'] === 'right' ? 'flex-end' : 'center');
            
            echo '<div class="probuilder-slide ' . esc_attr($active_class) . '" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url(' . esc_url($slide['image']['url']) . '); background-size: cover; background-position: center; display: flex; align-items: center; justify-content: ' . esc_attr($content_align) . '; opacity: ' . ($index === 0 ? '1' : '0') . '; transition: opacity 0.5s ease;">';
            
            echo '<div class="probuilder-slide-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: ' . esc_attr($settings['overlay_color']) . ';"></div>';
            
            echo '<div class="probuilder-slide-content" style="position: relative; z-index: 2; text-align: ' . esc_attr($slide['content_position']) . '; max-width: 600px; padding: 40px;">';
            
            if (!empty($slide['title'])) {
                echo '<h2 class="probuilder-slide-title" style="color: ' . esc_attr($settings['title_color']) . '; font-size: 48px; font-weight: 700; margin: 0 0 20px 0; line-height: 1.2;">' . esc_html($slide['title']) . '</h2>';
            }
            
            if (!empty($slide['description'])) {
                echo '<p class="probuilder-slide-description" style="color: ' . esc_attr($settings['description_color']) . '; font-size: 18px; margin: 0 0 30px 0; line-height: 1.6;">' . esc_html($slide['description']) . '</p>';
            }
            
            if (!empty($slide['button_text'])) {
                echo '<a href="' . esc_url($slide['button_link']) . '" class="probuilder-slide-button" style="display: inline-block; background-color: ' . esc_attr($settings['button_bg_color']) . '; color: ' . esc_attr($settings['button_text_color']) . '; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: 600; font-size: 16px; transition: all 0.3s ease;">' . esc_html($slide['button_text']) . '</a>';
            }
            
            echo '</div>';
            echo '</div>';
        }
        
        // Navigation Arrows
        if ($settings['show_arrows'] === 'yes') {
            echo '<button class="probuilder-slider-prev" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); border: none; color: ' . esc_attr($settings['arrow_color']) . '; font-size: 24px; padding: 15px; border-radius: 50%; cursor: pointer; z-index: 3; transition: all 0.3s ease;">‹</button>';
            echo '<button class="probuilder-slider-next" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.5); border: none; color: ' . esc_attr($settings['arrow_color']) . '; font-size: 24px; padding: 15px; border-radius: 50%; cursor: pointer; z-index: 3; transition: all 0.3s ease;">›</button>';
        }
        
        // Dots Navigation
        if ($settings['show_dots'] === 'yes') {
            echo '<div class="probuilder-slider-dots" style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); display: flex; gap: 10px; z-index: 3;">';
            foreach ($settings['slides'] as $index => $slide) {
                $active_class = $index === 0 ? 'active' : '';
                $dot_color = $index === 0 ? $settings['active_dot_color'] : $settings['dot_color'];
                echo '<button class="probuilder-slider-dot ' . esc_attr($active_class) . '" data-slide="' . esc_attr($index) . '" style="width: 12px; height: 12px; border-radius: 50%; border: none; background-color: ' . esc_attr($dot_color) . '; cursor: pointer; transition: all 0.3s ease;"></button>';
            }
            echo '</div>';
        }
        
        echo '</div>';
        
        // Add JavaScript for slider functionality
        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            const slider = document.getElementById("' . esc_js($slider_id) . '");
            if (!slider) return;
            
            const slides = slider.querySelectorAll(".probuilder-slide");
            const dots = slider.querySelectorAll(".probuilder-slider-dot");
            const prevBtn = slider.querySelector(".probuilder-slider-prev");
            const nextBtn = slider.querySelector(".probuilder-slider-next");
            
            let currentSlide = 0;
            const totalSlides = slides.length;
            const autoplay = ' . ($settings['autoplay'] === 'yes' ? 'true' : 'false') . ';
            const autoplaySpeed = ' . esc_js($settings['autoplay_speed']['size']) . ' * 1000;
            let autoplayInterval;
            
            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.style.opacity = i === index ? "1" : "0";
                });
                
                dots.forEach((dot, i) => {
                    dot.classList.toggle("active", i === index);
                    dot.style.backgroundColor = i === index ? "' . esc_js($settings['active_dot_color']) . '" : "' . esc_js($settings['dot_color']) . '";
                });
                
                currentSlide = index;
            }
            
            function nextSlide() {
                const next = (currentSlide + 1) % totalSlides;
                showSlide(next);
            }
            
            function prevSlide() {
                const prev = (currentSlide - 1 + totalSlides) % totalSlides;
                showSlide(prev);
            }
            
            function startAutoplay() {
                if (autoplay) {
                    autoplayInterval = setInterval(nextSlide, autoplaySpeed);
                }
            }
            
            function stopAutoplay() {
                if (autoplayInterval) {
                    clearInterval(autoplayInterval);
                }
            }
            
            // Event listeners
            if (nextBtn) {
                nextBtn.addEventListener("click", function() {
                    stopAutoplay();
                    nextSlide();
                    startAutoplay();
                });
            }
            
            if (prevBtn) {
                prevBtn.addEventListener("click", function() {
                    stopAutoplay();
                    prevSlide();
                    startAutoplay();
                });
            }
            
            dots.forEach((dot, index) => {
                dot.addEventListener("click", function() {
                    stopAutoplay();
                    showSlide(index);
                    startAutoplay();
                });
            });
            
            // Pause autoplay on hover
            slider.addEventListener("mouseenter", stopAutoplay);
            slider.addEventListener("mouseleave", startAutoplay);
            
            // Start autoplay
            startAutoplay();
        });
        </script>';
    }
}

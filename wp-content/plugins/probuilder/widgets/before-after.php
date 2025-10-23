<?php
/**
 * Before After Image Comparison Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Before_After extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'before-after';
        $this->title = __('Before/After', 'probuilder');
        $this->icon = 'fa fa-code-compare';
        $this->category = 'advanced';
        $this->keywords = ['before', 'after', 'comparison', 'slider'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_images', [
            'label' => __('Images', 'probuilder'),
        ]);
        
        $this->add_control('before_image', [
            'label' => __('Before Image', 'probuilder'),
            'type' => 'media',
            'default' => ['url' => 'https://via.placeholder.com/800x600/999/fff?text=Before'],
        ]);
        
        $this->add_control('after_image', [
            'label' => __('After Image', 'probuilder'),
            'type' => 'media',
            'default' => ['url' => 'https://via.placeholder.com/800x600/92003b/fff?text=After'],
        ]);
        
        $this->add_control('before_label', [
            'label' => __('Before Label', 'probuilder'),
            'type' => 'text',
            'default' => __('Before', 'probuilder'),
        ]);
        
        $this->add_control('after_label', [
            'label' => __('After Label', 'probuilder'),
            'type' => 'text',
            'default' => __('After', 'probuilder'),
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $before = $this->get_settings('before_image', ['url' => 'https://via.placeholder.com/800x600/999/fff?text=Before']);
        $after = $this->get_settings('after_image', ['url' => 'https://via.placeholder.com/800x600/92003b/fff?text=After']);
        $before_label = $this->get_settings('before_label', 'Before');
        $after_label = $this->get_settings('after_label', 'After');
        
        $id = 'before-after-' . uniqid();
        
        ?>
        <div class="probuilder-before-after" id="<?php echo esc_attr($id); ?>" style="position: relative; overflow: hidden; border-radius: 8px; max-width: 100%;">
            
            <!-- After Image (bottom layer) -->
            <img src="<?php echo esc_url($after['url']); ?>" alt="After" style="width: 100%; display: block;">
            
            <!-- Before Image (top layer with clip) -->
            <div class="before-layer" style="position: absolute; top: 0; left: 0; width: 50%; height: 100%; overflow: hidden;">
                <img src="<?php echo esc_url($before['url']); ?>" alt="Before" style="width: 200%; max-width: none;">
            </div>
            
            <!-- Slider -->
            <div class="slider-handle" style="position: absolute; top: 0; left: 50%; width: 4px; height: 100%; background: #92003b; cursor: ew-resize; transform: translateX(-50%);">
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 40px; height: 40px; background: #92003b; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                    <i class="fa fa-arrows-left-right"></i>
                </div>
            </div>
            
            <!-- Labels -->
            <div style="position: absolute; top: 20px; left: 20px; background: rgba(0,0,0,0.7); color: #fff; padding: 8px 15px; border-radius: 4px; font-size: 14px; font-weight: 600;">
                <?php echo esc_html($before_label); ?>
            </div>
            <div style="position: absolute; top: 20px; right: 20px; background: rgba(146,0,59,0.9); color: #fff; padding: 8px 15px; border-radius: 4px; font-size: 14px; font-weight: 600;">
                <?php echo esc_html($after_label); ?>
            </div>
            
        </div>
        
        <script>
        (function() {
            const container = document.getElementById('<?php echo esc_js($id); ?>');
            const slider = container.querySelector('.slider-handle');
            const beforeLayer = container.querySelector('.before-layer');
            const beforeImage = beforeLayer.querySelector('img');
            
            let isDragging = false;
            
            slider.addEventListener('mousedown', function() {
                isDragging = true;
            });
            
            document.addEventListener('mouseup', function() {
                isDragging = false;
            });
            
            container.addEventListener('mousemove', function(e) {
                if (!isDragging) return;
                
                const rect = container.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const percentage = (x / rect.width) * 100;
                
                if (percentage >= 0 && percentage <= 100) {
                    beforeLayer.style.width = percentage + '%';
                    slider.style.left = percentage + '%';
                }
            });
        })();
        </script>
        <?php
    }
}


<?php
/**
 * Flip Box Widget
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Widget_Flip_Box extends ProBuilder_Base_Widget {
    
    public function __construct() {
        $this->name = 'flip-box';
        $this->title = __('Flip Box', 'probuilder');
        $this->icon = 'fa fa-clone';
        $this->category = 'advanced';
        $this->keywords = ['flip', 'box', 'card', 'hover'];
    }
    
    protected function register_controls() {
        $this->start_controls_section('section_front', [
            'label' => __('Front Side', 'probuilder'),
        ]);
        
        $this->add_control('front_title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('Front Title', 'probuilder'),
        ]);
        
        $this->add_control('front_icon', [
            'label' => __('Icon', 'probuilder'),
            'type' => 'icon',
            'default' => 'fa fa-star',
        ]);
        
        $this->add_control('front_bg', [
            'label' => __('Background Color', 'probuilder'),
            'type' => 'color',
            'default' => '#92003b',
        ]);
        
        $this->end_controls_section();
        
        $this->start_controls_section('section_back', [
            'label' => __('Back Side', 'probuilder'),
        ]);
        
        $this->add_control('back_title', [
            'label' => __('Title', 'probuilder'),
            'type' => 'text',
            'default' => __('Back Title', 'probuilder'),
        ]);
        
        $this->add_control('back_description', [
            'label' => __('Description', 'probuilder'),
            'type' => 'textarea',
            'default' => __('This is the back side description.', 'probuilder'),
        ]);
        
        $this->add_control('back_button_text', [
            'label' => __('Button Text', 'probuilder'),
            'type' => 'text',
            'default' => __('Learn More', 'probuilder'),
        ]);
        
        $this->add_control('back_button_link', [
            'label' => __('Button Link', 'probuilder'),
            'type' => 'url',
            'default' => '#',
        ]);
        
        $this->add_control('back_bg', [
            'label' => __('Background Color', 'probuilder'),
            'type' => 'color',
            'default' => '#333333',
        ]);
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $front_title = $this->get_settings('front_title', 'Front Title');
        $front_icon = $this->get_settings('front_icon', 'fa fa-star');
        $front_bg = $this->get_settings('front_bg', '#92003b');
        $back_title = $this->get_settings('back_title', 'Back Title');
        $back_desc = $this->get_settings('back_description', '');
        $back_btn_text = $this->get_settings('back_button_text', 'Learn More');
        $back_btn_link = $this->get_settings('back_button_link', '#');
        $back_bg = $this->get_settings('back_bg', '#333333');
        
        $id = 'flip-box-' . uniqid();
        
        ?>
        <div class="probuilder-flip-box" id="<?php echo esc_attr($id); ?>" style="perspective: 1000px; height: 300px;">
            <div class="flip-box-inner" style="position: relative; width: 100%; height: 100%; transition: transform 0.6s; transform-style: preserve-3d;">
                
                <!-- Front -->
                <div class="flip-box-front" style="position: absolute; width: 100%; height: 100%; backface-visibility: hidden; background: <?php echo esc_attr($front_bg); ?>; color: #fff; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 8px; padding: 30px;">
                    <i class="<?php echo esc_attr($front_icon); ?>" style="font-size: 60px; margin-bottom: 20px;"></i>
                    <h3 style="margin: 0; font-size: 24px;"><?php echo esc_html($front_title); ?></h3>
                </div>
                
                <!-- Back -->
                <div class="flip-box-back" style="position: absolute; width: 100%; height: 100%; backface-visibility: hidden; background: <?php echo esc_attr($back_bg); ?>; color: #fff; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 8px; padding: 30px; transform: rotateY(180deg);">
                    <h3 style="margin: 0 0 15px 0; font-size: 22px;"><?php echo esc_html($back_title); ?></h3>
                    <p style="margin: 0 0 20px 0; font-size: 14px; line-height: 1.6;"><?php echo esc_html($back_desc); ?></p>
                    <a href="<?php echo esc_url($back_btn_link); ?>" style="background: #fff; color: <?php echo esc_attr($back_bg); ?>; padding: 10px 25px; text-decoration: none; border-radius: 4px; font-weight: 600;"><?php echo esc_html($back_btn_text); ?></a>
                </div>
                
            </div>
        </div>
        
        <style>
            #<?php echo esc_attr($id); ?>:hover .flip-box-inner {
                transform: rotateY(180deg);
            }
        </style>
        <?php
    }
}


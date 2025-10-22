<?php
/**
 * Preloader Output
 * Displays preloader based on theme options
 *
 * @package EcoCommerce_Pro
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Output preloader HTML and CSS
 */
function ecocommerce_pro_preloader_output() {
    $options = get_option('ecocommerce_pro_general_options', array());
    
    // Check if preloader is enabled
    if (empty($options['preloader_enable'])) {
        return;
    }
    
    $type = $options['preloader_type'] ?? 'spinner';
    $color = $options['preloader_color'] ?? '#667eea';
    $bg = $options['preloader_bg'] ?? '#ffffff';
    $size = $options['preloader_size'] ?? '60';
    $speed = $options['preloader_speed'] ?? 'normal';
    $custom_image = $options['preloader_custom_image'] ?? '';
    
    // Convert speed to duration
    $duration = array(
        'slow' => '2s',
        'normal' => '1s',
        'fast' => '0.5s'
    );
    $animation_duration = $duration[$speed] ?? '1s';
    
    ?>
    <div id="ecocommerce-preloader" style="
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: <?php echo esc_attr($bg); ?>;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 999999;
        transition: opacity 0.5s ease, visibility 0.5s ease;
    ">
        <div class="preloader-content">
            <?php if ($type === 'custom' && !empty($custom_image)) : ?>
                <!-- Custom Image Preloader -->
                <img src="<?php echo esc_url($custom_image); ?>" alt="Loading..." style="max-width: <?php echo esc_attr($size); ?>px; max-height: <?php echo esc_attr($size); ?>px;" />
                
            <?php elseif ($type === 'spinner') : ?>
                <!-- Spinner -->
                <div class="preloader-spinner" style="
                    width: <?php echo esc_attr($size); ?>px;
                    height: <?php echo esc_attr($size); ?>px;
                    border: 4px solid rgba(<?php echo ecocommerce_hex_to_rgb_values($color); ?>, 0.1);
                    border-top-color: <?php echo esc_attr($color); ?>;
                    border-radius: 50%;
                    animation: spin <?php echo esc_attr($animation_duration); ?> linear infinite;
                "></div>
                
            <?php elseif ($type === 'dots') : ?>
                <!-- Dots -->
                <div class="preloader-dots" style="display: flex; gap: 10px;">
                    <?php for ($i = 1; $i <= 3; $i++) : ?>
                        <div style="
                            width: <?php echo esc_attr($size / 4); ?>px;
                            height: <?php echo esc_attr($size / 4); ?>px;
                            background: <?php echo esc_attr($color); ?>;
                            border-radius: 50%;
                            animation: bounce <?php echo esc_attr($animation_duration); ?> infinite ease-in-out;
                            animation-delay: <?php echo ($i - 1) * 0.1; ?>s;
                        "></div>
                    <?php endfor; ?>
                </div>
                
            <?php elseif ($type === 'bars') : ?>
                <!-- Bars -->
                <div class="preloader-bars" style="display: flex; gap: 6px; align-items: flex-end; height: <?php echo esc_attr($size); ?>px;">
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <div style="
                            width: <?php echo esc_attr($size / 8); ?>px;
                            background: <?php echo esc_attr($color); ?>;
                            border-radius: 3px;
                            animation: bar-grow <?php echo esc_attr($animation_duration); ?> infinite ease-in-out;
                            animation-delay: <?php echo ($i - 1) * 0.1; ?>s;
                        "></div>
                    <?php endfor; ?>
                </div>
                
            <?php elseif ($type === 'circle') : ?>
                <!-- Circle -->
                <div style="
                    width: <?php echo esc_attr($size); ?>px;
                    height: <?php echo esc_attr($size); ?>px;
                    border: 4px solid <?php echo esc_attr($color); ?>;
                    border-radius: 50%;
                    animation: circle-pulse <?php echo esc_attr($animation_duration); ?> infinite ease-in-out;
                "></div>
                
            <?php elseif ($type === 'pulse') : ?>
                <!-- Pulse -->
                <div style="
                    width: <?php echo esc_attr($size); ?>px;
                    height: <?php echo esc_attr($size); ?>px;
                    background: <?php echo esc_attr($color); ?>;
                    border-radius: 50%;
                    animation: pulse <?php echo esc_attr($animation_duration); ?> infinite ease-in-out;
                "></div>
                
            <?php elseif ($type === 'wave') : ?>
                <!-- Wave -->
                <div class="preloader-wave" style="display: flex; gap: 4px; align-items: center;">
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <div style="
                            width: <?php echo esc_attr($size / 8); ?>px;
                            height: <?php echo esc_attr($size); ?>px;
                            background: <?php echo esc_attr($color); ?>;
                            border-radius: 3px;
                            animation: wave <?php echo esc_attr($animation_duration); ?> infinite ease-in-out;
                            animation-delay: <?php echo ($i - 1) * 0.15; ?>s;
                        "></div>
                    <?php endfor; ?>
                </div>
                
            <?php elseif ($type === 'bounce') : ?>
                <!-- Bounce -->
                <div style="
                    width: <?php echo esc_attr($size); ?>px;
                    height: <?php echo esc_attr($size); ?>px;
                    background: <?php echo esc_attr($color); ?>;
                    border-radius: 50%;
                    animation: bounce-up <?php echo esc_attr($animation_duration); ?> infinite ease-in-out;
                "></div>
                
            <?php elseif ($type === 'flip') : ?>
                <!-- Flip -->
                <div style="
                    width: <?php echo esc_attr($size); ?>px;
                    height: <?php echo esc_attr($size); ?>px;
                    background: <?php echo esc_attr($color); ?>;
                    border-radius: 10px;
                    animation: flip <?php echo esc_attr($animation_duration); ?> infinite ease-in-out;
                "></div>
            <?php endif; ?>
        </div>
    </div>
    
    <style>
        /* Preloader Animations */
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes bar-grow {
            0%, 100% { height: 20%; }
            50% { height: 100%; }
        }
        
        @keyframes circle-pulse {
            0%, 100% { transform: scale(0.8); opacity: 0.5; }
            50% { transform: scale(1.2); opacity: 1; }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(0.8); opacity: 0.5; }
            50% { transform: scale(1); opacity: 1; }
        }
        
        @keyframes wave {
            0%, 100% { transform: scaleY(0.5); }
            50% { transform: scaleY(1); }
        }
        
        @keyframes bounce-up {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-30px); }
        }
        
        @keyframes flip {
            0% { transform: perspective(400px) rotateY(0); }
            100% { transform: perspective(400px) rotateY(360deg); }
        }
        
        /* Fade out class */
        .preloader-hidden {
            opacity: 0 !important;
            visibility: hidden !important;
        }
    </style>
    
    <script>
        // Hide preloader when page loads
        window.addEventListener('load', function() {
            const preloader = document.getElementById('ecocommerce-preloader');
            if (preloader) {
                preloader.classList.add('preloader-hidden');
                setTimeout(function() {
                    preloader.style.display = 'none';
                }, 500);
            }
        });
        
        // Fallback: Hide after 10 seconds max
        setTimeout(function() {
            const preloader = document.getElementById('ecocommerce-preloader');
            if (preloader) {
                preloader.style.display = 'none';
            }
        }, 10000);
    </script>
    <?php
}
add_action('wp_body_open', 'ecocommerce_pro_preloader_output');

/**
 * Helper function to convert hex to RGB values
 */
function ecocommerce_hex_to_rgb_values($hex) {
    $hex = ltrim($hex, '#');
    
    if (strlen($hex) === 3) {
        $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
        $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
        $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
    } else {
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
    }
    
    return "$r, $g, $b";
}


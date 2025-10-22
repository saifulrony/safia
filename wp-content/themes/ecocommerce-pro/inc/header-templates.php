<?php
/**
 * EcoCommerce Pro - Header Templates
 * Multiple header designs to choose from
 * 
 * @package EcoCommerce_Pro
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get available header templates
 */
function ecocommerce_pro_get_header_templates() {
    return array(
        'default' => array(
            'name' => 'Default Header',
            'description' => 'Logo left, menu center, icons right',
            'preview' => '
                <div class="realistic-header-preview" style="background: #ffffff; padding: 15px 20px; border-bottom: 1px solid #e5e7eb;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="background: linear-gradient(135deg, #667eea, #764ba2); padding: 8px 16px; border-radius: 6px; color: white; font-weight: 700; font-size: 11px;">LOGO</div>
                        <div style="display: flex; gap: 12px;">
                            <div style="background: #f3f4f6; padding: 6px 12px; border-radius: 4px; font-size: 10px; color: #6b7280;">Home</div>
                            <div style="background: #f3f4f6; padding: 6px 12px; border-radius: 4px; font-size: 10px; color: #6b7280;">Shop</div>
                            <div style="background: #f3f4f6; padding: 6px 12px; border-radius: 4px; font-size: 10px; color: #6b7280;">About</div>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <div style="width: 24px; height: 24px; background: #f3f4f6; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 10px;">🔍</div>
                            <div style="width: 24px; height: 24px; background: #f3f4f6; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 10px;">🛒</div>
                        </div>
                    </div>
                </div>
            ',
        ),
        'centered' => array(
            'name' => 'Centered Header',
            'description' => 'Logo and menu centered, icons on sides',
            'preview' => '
                <div class="realistic-header-preview" style="background: #ffffff; padding: 20px; border-bottom: 1px solid #e5e7eb;">
                    <div style="text-align: center; margin-bottom: 12px;">
                        <div style="background: linear-gradient(135deg, #667eea, #764ba2); padding: 8px 20px; border-radius: 6px; color: white; font-weight: 700; font-size: 12px; display: inline-block;">LOGO</div>
                    </div>
                    <div style="display: flex; gap: 10px; justify-content: center; align-items: center;">
                        <div style="background: #f3f4f6; padding: 5px 10px; border-radius: 4px; font-size: 9px; color: #6b7280;">Home</div>
                        <div style="background: #f3f4f6; padding: 5px 10px; border-radius: 4px; font-size: 9px; color: #6b7280;">Shop</div>
                        <div style="background: #f3f4f6; padding: 5px 10px; border-radius: 4px; font-size: 9px; color: #6b7280;">Pages</div>
                        <div style="background: #f3f4f6; padding: 5px 10px; border-radius: 4px; font-size: 9px; color: #6b7280;">Contact</div>
                    </div>
                </div>
            ',
        ),
        'minimal' => array(
            'name' => 'Minimal Header',
            'description' => 'Clean minimal design, logo + menu only',
            'preview' => '
                <div class="realistic-header-preview" style="background: #ffffff; padding: 12px 20px; border-bottom: 1px solid #f0f0f0;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="color: #1f2937; font-weight: 700; font-size: 14px; letter-spacing: -0.5px;">YOUR STORE</div>
                        <div style="display: flex; gap: 16px;">
                            <span style="font-size: 10px; color: #6b7280; font-weight: 500;">Home</span>
                            <span style="font-size: 10px; color: #6b7280; font-weight: 500;">Shop</span>
                            <span style="font-size: 10px; color: #6b7280; font-weight: 500;">About</span>
                            <span style="font-size: 10px; color: #2563eb; font-weight: 600;">Contact</span>
                        </div>
                    </div>
                </div>
            ',
        ),
        'split' => array(
            'name' => 'Split Header',
            'description' => 'Menu on left, logo center, icons right',
            'preview' => '
                <div class="realistic-header-preview" style="background: #ffffff; padding: 15px 20px; border-bottom: 1px solid #e5e7eb;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; gap: 10px;">
                            <div style="background: #f9fafb; padding: 5px 10px; border-radius: 4px; font-size: 9px; color: #6b7280;">Shop</div>
                            <div style="background: #f9fafb; padding: 5px 10px; border-radius: 4px; font-size: 9px; color: #6b7280;">Sale</div>
                        </div>
                        <div style="background: linear-gradient(135deg, #667eea, #764ba2); padding: 8px 18px; border-radius: 6px; color: white; font-weight: 700; font-size: 11px;">LOGO</div>
                        <div style="display: flex; gap: 6px;">
                            <div style="width: 22px; height: 22px; background: #f3f4f6; border-radius: 5px; font-size: 9px; display: flex; align-items: center; justify-content: center;">🔍</div>
                            <div style="width: 22px; height: 22px; background: #f3f4f6; border-radius: 5px; font-size: 9px; display: flex; align-items: center; justify-content: center;">🛒</div>
                        </div>
                    </div>
                </div>
            ',
        ),
        'stacked' => array(
            'name' => 'Stacked Header',
            'description' => 'Logo on top row, menu below',
            'preview' => '
                <div class="realistic-header-preview" style="background: #ffffff; padding: 12px 20px; border-bottom: 1px solid #e5e7eb;">
                    <div style="text-align: center; padding-bottom: 10px; border-bottom: 1px solid #f0f0f0;">
                        <div style="background: linear-gradient(135deg, #667eea, #764ba2); padding: 6px 16px; border-radius: 6px; color: white; font-weight: 700; font-size: 11px; display: inline-block;">LOGO</div>
                    </div>
                    <div style="display: flex; gap: 12px; justify-content: center; margin-top: 10px;">
                        <span style="font-size: 9px; color: #2563eb; font-weight: 600;">Home</span>
                        <span style="font-size: 9px; color: #6b7280; font-weight: 500;">Shop</span>
                        <span style="font-size: 9px; color: #6b7280; font-weight: 500;">Pages</span>
                        <span style="font-size: 9px; color: #6b7280; font-weight: 500;">Blog</span>
                        <span style="font-size: 9px; color: #6b7280; font-weight: 500;">Contact</span>
                    </div>
                </div>
            ',
        ),
        'modern' => array(
            'name' => 'Modern Header',
            'description' => 'Large logo, inline menu, gradient background',
            'preview' => '
                <div class="realistic-header-preview" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 18px 20px;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="background: rgba(255,255,255,0.95); padding: 10px 20px; border-radius: 8px; color: #667eea; font-weight: 700; font-size: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">YOUR STORE</div>
                        <div style="display: flex; gap: 14px;">
                            <div style="background: rgba(255,255,255,0.2); padding: 6px 12px; border-radius: 6px; font-size: 10px; color: white; font-weight: 600; border: 1px solid rgba(255,255,255,0.3);">Home</div>
                            <div style="background: rgba(255,255,255,0.2); padding: 6px 12px; border-radius: 6px; font-size: 10px; color: white; font-weight: 600; border: 1px solid rgba(255,255,255,0.3);">Shop</div>
                            <div style="background: rgba(255,255,255,0.2); padding: 6px 12px; border-radius: 6px; font-size: 10px; color: white; font-weight: 600; border: 1px solid rgba(255,255,255,0.3);">About</div>
                        </div>
                        <div style="display: flex; gap: 6px;">
                            <div style="width: 26px; height: 26px; background: rgba(255,255,255,0.2); border-radius: 6px; font-size: 10px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.3);">🔍</div>
                            <div style="width: 26px; height: 26px; background: rgba(255,255,255,0.2); border-radius: 6px; font-size: 10px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.3);">🛒</div>
                        </div>
                    </div>
                </div>
            ',
        ),
        'transparent' => array(
            'name' => 'Transparent Header',
            'description' => 'Overlay on hero, white text',
            'preview' => '
                <div class="realistic-header-preview" style="background: linear-gradient(180deg, rgba(0,0,0,0.4) 0%, rgba(0,0,0,0) 100%); padding: 15px 20px;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="color: white; font-weight: 700; font-size: 13px; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">LOGO</div>
                        <div style="display: flex; gap: 14px;">
                            <span style="font-size: 10px; color: white; font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">Home</span>
                            <span style="font-size: 10px; color: white; font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">Shop</span>
                            <span style="font-size: 10px; color: white; font-weight: 600; text-shadow: 0 1px 2px rgba(0,0,0,0.3);">About</span>
                        </div>
                        <div style="display: flex; gap: 6px;">
                            <div style="width: 24px; height: 24px; background: rgba(255,255,255,0.2); border-radius: 5px; font-size: 10px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.3); color: white;">🔍</div>
                            <div style="width: 24px; height: 24px; background: rgba(255,255,255,0.2); border-radius: 5px; font-size: 10px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.3); color: white;">🛒</div>
                        </div>
                    </div>
                </div>
            ',
        ),
        'boxed' => array(
            'name' => 'Boxed Header',
            'description' => 'Contained in boxed layout, shadow bottom',
            'preview' => '
                <div class="realistic-header-preview" style="background: #f9fafb; padding: 15px 20px;">
                    <div style="background: white; padding: 15px 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); max-width: 90%; margin: 0 auto;">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="background: linear-gradient(135deg, #667eea, #764ba2); padding: 8px 16px; border-radius: 6px; color: white; font-weight: 700; font-size: 11px;">LOGO</div>
                            <div style="display: flex; gap: 12px;">
                                <div style="font-size: 10px; color: #6b7280; font-weight: 500;">Home</div>
                                <div style="font-size: 10px; color: #6b7280; font-weight: 500;">Shop</div>
                                <div style="font-size: 10px; color: #2563eb; font-weight: 600;">Sale</div>
                            </div>
                            <div style="display: flex; gap: 6px;">
                                <div style="width: 22px; height: 22px; background: #f3f4f6; border-radius: 5px; font-size: 9px; display: flex; align-items: center; justify-content: center;">🛒</div>
                            </div>
                        </div>
                    </div>
                </div>
            ',
        ),
        'mega-menu' => array(
            'name' => 'Mega Menu Header',
            'description' => 'Full-width menu with dropdowns',
            'preview' => '
                <div class="realistic-header-preview" style="background: #ffffff; border-bottom: 2px solid #e5e7eb;">
                    <div style="padding: 15px 20px;">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div style="background: linear-gradient(135deg, #667eea, #764ba2); padding: 8px 16px; border-radius: 6px; color: white; font-weight: 700; font-size: 11px;">LOGO</div>
                            <div style="display: flex; gap: 6px;">
                                <div style="width: 22px; height: 22px; background: #f3f4f6; border-radius: 5px; font-size: 9px; display: flex; align-items: center; justify-content: center;">🔍</div>
                                <div style="width: 22px; height: 22px; background: #f3f4f6; border-radius: 5px; font-size: 9px; display: flex; align-items: center; justify-content: center;">🛒</div>
                            </div>
                        </div>
                    </div>
                    <div style="background: #f9fafb; padding: 10px 20px; border-top: 1px solid #e5e7eb;">
                        <div style="display: flex; gap: 16px;">
                            <div style="font-size: 10px; color: #2563eb; font-weight: 600;">Electronics ▾</div>
                            <div style="font-size: 10px; color: #6b7280; font-weight: 500;">Clothing ▾</div>
                            <div style="font-size: 10px; color: #6b7280; font-weight: 500;">Home ▾</div>
                            <div style="font-size: 10px; color: #dc2626; font-weight: 600;">Sale 🔥</div>
                        </div>
                    </div>
                </div>
            ',
        ),
    );
}

/**
 * Render header template selector
 */
function ecocommerce_pro_header_template_selector($current_template = 'default') {
    $templates = ecocommerce_pro_get_header_templates();
    ?>
    <div class="header-template-selector">
        <div class="header-templates-grid">
            <?php foreach ($templates as $key => $template) : ?>
                <label class="header-template-card <?php echo $current_template === $key ? 'selected' : ''; ?>" data-template="<?php echo esc_attr($key); ?>">
                    <input type="radio" name="ecocommerce_pro_header_options[template]" value="<?php echo esc_attr($key); ?>" <?php checked($current_template, $key); ?> />
                    
                    <div class="template-preview">
                        <?php echo $template['preview']; ?>
                        <div class="template-overlay">
                            <span class="check-icon">✓</span>
                        </div>
                    </div>
                    
                    <div class="template-info">
                        <h4 class="template-name"><?php echo esc_html($template['name']); ?></h4>
                        <p class="template-desc"><?php echo esc_html($template['description']); ?></p>
                    </div>
                    
                    <div class="template-badge">
                        <?php if ($key === 'default') echo '<span class="badge-recommended">Recommended</span>'; ?>
                        <?php if ($key === 'modern') echo '<span class="badge-popular">Popular</span>'; ?>
                        <?php if ($key === 'minimal') echo '<span class="badge-new">Trending</span>'; ?>
                    </div>
                </label>
            <?php endforeach; ?>
        </div>
        
        <div class="header-template-preview-panel">
            <h4>👁️ Live Preview</h4>
            <p class="preview-hint">Click a header style above to see it here</p>
            <div class="live-header-preview" id="live-header-preview">
                <!-- Dynamic preview will load here -->
            </div>
            
            <div class="preview-actions">
                <button type="button" class="button customize-header-btn">🎨 Customize Selected</button>
            </div>
        </div>
    </div>
    
    <style>
    .header-template-selector {
        margin: 20px 0;
    }
    
    .header-templates-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .header-template-card {
        position: relative;
        background: white;
        border: 3px solid #e5e7eb;
        border-radius: 12px;
        padding: 0;
        cursor: pointer;
        transition: all 0.3s ease;
        overflow: hidden;
        display: block;
    }
    
    .header-template-card:hover {
        border-color: #2563eb;
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(37, 99, 235, 0.15);
    }
    
    .header-template-card.selected {
        border-color: #2563eb;
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        box-shadow: 0 12px 30px rgba(37, 99, 235, 0.25);
    }
    
    .header-template-card input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    
    .template-preview {
        position: relative;
        background: #f9fafb;
        padding: 15px;
        border-bottom: 2px solid #e5e7eb;
        min-height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .header-preview-svg {
        width: 100%;
        height: auto;
        max-height: 100px;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
    }
    
    .template-overlay {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 32px;
        height: 32px;
        background: #10b981;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 18px;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }
    
    .header-template-card.selected .template-overlay {
        display: flex;
        animation: scaleIn 0.3s ease;
    }
    
    @keyframes scaleIn {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
    
    .template-info {
        padding: 20px;
    }
    
    .template-name {
        margin: 0 0 8px 0;
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
    }
    
    .template-desc {
        margin: 0;
        font-size: 13px;
        color: #6b7280;
        line-height: 1.5;
    }
    
    .template-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 2;
    }
    
    .badge-recommended,
    .badge-popular,
    .badge-new {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge-recommended {
        background: #10b981;
        color: white;
    }
    
    .badge-popular {
        background: #f59e0b;
        color: white;
    }
    
    .badge-new {
        background: #2563eb;
        color: white;
    }
    
    .header-template-preview-panel {
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        margin-top: 20px;
    }
    
    .header-template-preview-panel h4 {
        margin: 0 0 12px 0;
        font-size: 16px;
        color: #1f2937;
    }
    
    .preview-hint {
        margin: 0 0 20px 0;
        color: #6b7280;
        font-size: 13px;
    }
    
    .live-header-preview {
        background: #f9fafb;
        border: 2px dashed #d1d5db;
        border-radius: 10px;
        min-height: 150px;
        padding: 20px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .customize-header-btn {
        width: 100%;
        padding: 12px;
        font-weight: 600;
    }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        // Header template selection
        $('.header-template-card').on('click', function() {
            var $card = $(this);
            var template = $card.data('template');
            
            // Remove selected from all
            $('.header-template-card').removeClass('selected');
            
            // Add selected to clicked
            $card.addClass('selected');
            
            // Check radio
            $card.find('input[type="radio"]').prop('checked', true);
            
            // Update live preview
            var previewSvg = $card.find('.header-preview-svg').clone();
            $('#live-header-preview').html(previewSvg);
        });
        
        // Customize button
        $('.customize-header-btn').on('click', function() {
            // Scroll to header customization options
            $('html, body').animate({
                scrollTop: $('.accordion-section').first().offset().top - 100
            }, 500);
        });
    });
    </script>
    <?php
}


<?php
/**
 * Demo Import Page UI
 * Modern interface for importing demo content
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render the demo import page
 */
function ecocommerce_pro_render_demo_import_page_new() {
    ?>
    <div class="wrap ecocommerce-demo-import-wrap">
        <h1>📦 Import Demo Content</h1>
        
        <div class="demo-import-container">
            <!-- Demo Preview -->
            <div class="demo-preview-section">
                <h2>Demo Store Preview</h2>
                <div class="demo-preview-images">
                    <img src="<?php echo get_template_directory_uri(); ?>/screenshot.png" alt="Demo Preview" class="demo-screenshot" />
                </div>
            </div>
            
            <!-- Import Options -->
            <div class="demo-import-options">
                <div class="import-card">
                    <div class="import-card-header">
                        <h3>🎨 What Will Be Imported?</h3>
                    </div>
                    <div class="import-card-body">
                        <div class="import-item">
                            <span class="import-icon">🏷️</span>
                            <div class="import-details">
                                <strong>5 Product Categories</strong>
                                <p>Electronics, Clothing, Home & Garden, Sports, Beauty</p>
                                <small>✅ With category images</small>
                            </div>
                        </div>
                        
                        <div class="import-item">
                            <span class="import-icon">📦</span>
                            <div class="import-details">
                                <strong>12 Demo Products</strong>
                                <p>Complete with descriptions, prices, and variations</p>
                                <small>✅ With high-quality product images</small>
                            </div>
                        </div>
                        
                        <div class="import-item">
                            <span class="import-icon">📄</span>
                            <div class="import-details">
                                <strong>Elementor-Ready Pages</strong>
                                <p>Homepage, About Us, Contact pages</p>
                                <small>✅ Edit with Elementor, WPBakery, or any builder</small>
                            </div>
                        </div>
                        
                        <div class="import-item">
                            <span class="import-icon">⚙️</span>
                            <div class="import-details">
                                <strong>WooCommerce Settings</strong>
                                <p>Optimized shop configuration</p>
                                <small>✅ Gallery features, currency, display settings</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Import Button -->
                <div class="import-action-card">
                    <button type="button" id="start-complete-import" class="button button-primary button-hero">
                        <span class="btn-icon">🚀</span>
                        <span class="btn-text">Start Import</span>
                    </button>
                    
                    <p class="import-note">
                        <strong>Note:</strong> Import takes 1-2 minutes. Don't close this page during import.
                    </p>
                </div>
                
                <!-- Progress Section -->
                <div id="import-progress-section" class="import-progress-section" style="display: none;">
                    <div class="progress-header">
                        <h3>Importing Demo Content...</h3>
                        <p class="progress-status">Starting import...</p>
                    </div>
                    
                    <div class="progress-steps">
                        <div class="progress-step" data-step="categories">
                            <div class="step-icon">🏷️</div>
                            <div class="step-info">
                                <strong>Categories</strong>
                                <span class="step-status">Waiting...</span>
                            </div>
                        </div>
                        
                        <div class="progress-step" data-step="products">
                            <div class="step-icon">📦</div>
                            <div class="step-info">
                                <strong>Products</strong>
                                <span class="step-status">Waiting...</span>
                            </div>
                        </div>
                        
                        <div class="progress-step" data-step="pages">
                            <div class="step-icon">📄</div>
                            <div class="step-info">
                                <strong>Pages</strong>
                                <span class="step-status">Waiting...</span>
                            </div>
                        </div>
                        
                        <div class="progress-step" data-step="settings">
                            <div class="step-icon">⚙️</div>
                            <div class="step-info">
                                <strong>Settings</strong>
                                <span class="step-status">Waiting...</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="progress-bar-wrapper">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 0%;"></div>
                        </div>
                        <div class="progress-percentage">0%</div>
                    </div>
                </div>
                
                <!-- Success Section -->
                <div id="import-success-section" class="import-success-section" style="display: none;">
                    <div class="success-icon">🎉</div>
                    <h2>Import Complete!</h2>
                    <p>Your demo content has been imported successfully.</p>
                    
                    <div class="success-actions">
                        <a href="<?php echo home_url(); ?>" class="button button-primary" target="_blank">
                            👁️ View Site
                        </a>
                        <a href="<?php echo admin_url('edit.php?post_type=product'); ?>" class="button button-secondary">
                            📦 View Products
                        </a>
                        <a href="<?php echo admin_url('edit.php?post_type=page'); ?>" class="button button-secondary">
                            📄 Edit Pages with Elementor
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .ecocommerce-demo-import-wrap {
            margin: 20px 20px 20px 0;
        }
        
        .demo-import-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-top: 30px;
        }
        
        .demo-preview-section h2 {
            font-size: 20px;
            margin-bottom: 20px;
        }
        
        .demo-screenshot {
            width: 100%;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .import-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .import-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            color: white;
        }
        
        .import-card-header h3 {
            margin: 0;
            color: white;
            font-size: 18px;
        }
        
        .import-card-body {
            padding: 20px;
        }
        
        .import-item {
            display: flex;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .import-item:last-child {
            border-bottom: none;
        }
        
        .import-icon {
            font-size: 32px;
            line-height: 1;
        }
        
        .import-details strong {
            display: block;
            font-size: 15px;
            margin-bottom: 4px;
            color: #1f2937;
        }
        
        .import-details p {
            margin: 0 0 4px 0;
            color: #6b7280;
            font-size: 13px;
        }
        
        .import-details small {
            color: #10b981;
            font-size: 12px;
        }
        
        .import-action-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            text-align: center;
            margin-top: 20px;
        }
        
        #start-complete-import {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 40px;
            font-size: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            transition: all 0.3s;
        }
        
        #start-complete-import:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-icon {
            font-size: 20px;
        }
        
        .import-note {
            margin-top: 15px;
            color: #6b7280;
            font-size: 13px;
        }
        
        .import-progress-section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-top: 20px;
        }
        
        .progress-header h3 {
            margin: 0 0 10px 0;
            font-size: 20px;
        }
        
        .progress-status {
            color: #667eea;
            font-weight: 600;
            margin: 0;
        }
        
        .progress-steps {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin: 30px 0;
        }
        
        .progress-step {
            text-align: center;
            padding: 20px;
            background: #f9fafb;
            border-radius: 10px;
            border: 2px solid #e5e7eb;
        }
        
        .progress-step.active {
            background: #eff6ff;
            border-color: #3b82f6;
        }
        
        .progress-step.complete {
            background: #f0fdf4;
            border-color: #10b981;
        }
        
        .step-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .step-info strong {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .step-status {
            font-size: 12px;
            color: #6b7280;
        }
        
        .progress-step.active .step-status {
            color: #3b82f6;
            font-weight: 600;
        }
        
        .progress-step.complete .step-status {
            color: #10b981;
            font-weight: 600;
        }
        
        .progress-bar-wrapper {
            margin-top: 20px;
        }
        
        .progress-bar {
            height: 30px;
            background: #f0f0f0;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            transition: width 0.5s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
        }
        
        .progress-percentage {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
        }
        
        .import-success-section {
            background: white;
            padding: 50px 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            text-align: center;
            margin-top: 20px;
        }
        
        .success-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }
        
        .import-success-section h2 {
            font-size: 28px;
            color: #1f2937;
            margin-bottom: 10px;
        }
        
        .import-success-section p {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 30px;
        }
        
        .success-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        @media (max-width: 1024px) {
            .demo-import-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        let currentStep = 0;
        const steps = ['categories', 'products', 'pages', 'settings'];
        
        $('#start-complete-import').on('click', function() {
            if (!confirm('This will import demo products, categories, and pages. Continue?')) {
                return;
            }
            
            $(this).prop('disabled', true);
            $('.import-action-card').fadeOut(300);
            
            setTimeout(function() {
                $('#import-progress-section').fadeIn(300);
                runImport();
            }, 400);
        });
        
        function runImport() {
            importNextStep();
        }
        
        function importNextStep() {
            if (currentStep >= steps.length) {
                // All done!
                showSuccess();
                return;
            }
            
            const step = steps[currentStep];
            const $stepEl = $(`.progress-step[data-step="${step}"]`);
            
            // Update UI
            $stepEl.addClass('active');
            $stepEl.find('.step-status').text('Importing...');
            
            const progress = ((currentStep + 1) / steps.length) * 100;
            $('.progress-fill').css('width', progress + '%');
            $('.progress-percentage').text(Math.round(progress) + '%');
            
            // Make AJAX call
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'ecocommerce_complete_demo_import',
                    nonce: '<?php echo wp_create_nonce('ecocommerce_demo_nonce'); ?>',
                    step: step
                },
                success: function(response) {
                    if (response.success) {
                        $stepEl.removeClass('active').addClass('complete');
                        $stepEl.find('.step-status').html('✓ Complete');
                        $('.progress-status').text(response.data.message || 'Processing...');
                        
                        currentStep++;
                        setTimeout(importNextStep, 500);
                    } else {
                        showError(response.data || 'Import failed');
                    }
                },
                error: function() {
                    showError('Network error. Please try again.');
                }
            });
        }
        
        function showSuccess() {
            $('#import-progress-section').fadeOut(300, function() {
                $('#import-success-section').fadeIn(300);
            });
        }
        
        function showError(message) {
            alert('Error: ' + message);
            location.reload();
        }
    });
    </script>
    <?php
}


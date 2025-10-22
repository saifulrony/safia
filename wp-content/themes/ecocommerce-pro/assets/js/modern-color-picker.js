/**
 * Modern Color Picker Component
 * User-friendly color selection with presets, gradients, and visual picker
 */

(function($) {
    'use strict';
    
    // Color Presets - Popular colors organized by category
    const colorPresets = {
        primary: [
            '#2563eb', '#3b82f6', '#60a5fa', '#93c5fd', // Blues
            '#7c3aed', '#8b5cf6', '#a78bfa', '#c4b5fd', // Purples  
            '#dc2626', '#ef4444', '#f87171', '#fca5a5', // Reds
            '#ea580c', '#f97316', '#fb923c', '#fdba74', // Oranges
        ],
        secondary: [
            '#059669', '#10b981', '#34d399', '#6ee7b7', // Greens
            '#0891b2', '#06b6d4', '#22d3ee', '#67e8f9', // Cyans
            '#4f46e5', '#6366f1', '#818cf8', '#a5b4fc', // Indigos
            '#9333ea', '#a855f7', '#c084fc', '#d8b4fe', // Violets
        ],
        neutral: [
            '#111827', '#1f2937', '#374151', '#4b5563', // Grays Dark
            '#6b7280', '#9ca3af', '#d1d5db', '#e5e7eb', // Grays Light
            '#f9fafb', '#f3f4f6', '#ffffff', '#000000', // Black/White
            '#f59e0b', '#fbbf24', '#fcd34d', '#fde68a', // Yellows
        ]
    };
    
    // Gradient Presets
    const gradientPresets = [
        {
            name: 'Ocean Blue',
            value: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'
        },
        {
            name: 'Sunset',
            value: 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)'
        },
        {
            name: 'Forest',
            value: 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)'
        },
        {
            name: 'Purple Dream',
            value: 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)'
        },
        {
            name: 'Cosmic',
            value: 'linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%)'
        },
        {
            name: 'Fire',
            value: 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)'
        },
        {
            name: 'Sky',
            value: 'linear-gradient(135deg, #30cfd0 0%, #330867 100%)'
        },
        {
            name: 'Rose',
            value: 'linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)'
        },
        {
            name: 'Mint',
            value: 'linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%)'
        }
    ];
    
    // Recent colors storage
    let recentColors = JSON.parse(localStorage.getItem('ecocommerce_recent_colors') || '[]');
    
    // Create backdrop element once
    let $backdrop = null;
    
    /**
     * Initialize modern color picker for all color inputs
     */
    function initModernColorPickers() {
        // Create backdrop if doesn't exist
        if (!$backdrop || $backdrop.length === 0) {
            $backdrop = $('<div class="color-picker-backdrop"></div>');
            $('body').append($backdrop);
            
            // Close all pickers when backdrop clicked
            $backdrop.on('click', function() {
                $('.color-picker-dropdown').removeClass('active');
                $backdrop.removeClass('active');
            });
        }
        
        // Find all color input fields
        $('.color-picker-field, input[type="text"][name*="color"], input[type="text"][name*="_bg"], input[type="text"][name*="_border"]').each(function() {
            const $input = $(this);
            
            // Skip if already initialized
            if ($input.parent().hasClass('modern-color-picker-wrapper')) {
                return;
            }
            
            // Wrap input
            $input.wrap('<div class="modern-color-picker-wrapper"></div>');
            const $wrapper = $input.parent();
            
            // Hide original input
            $input.hide();
            
            // Create color picker UI
            createColorPickerUI($wrapper, $input);
        });
    }
    
    /**
     * Create the color picker UI
     */
    function createColorPickerUI($wrapper, $input) {
        const currentValue = $input.val() || '#2563eb';
        
        // Create display button
        const $display = $(`
            <div class="color-picker-display">
                <div class="color-preview-box">
                    <div class="color-preview-color" style="background: ${currentValue};"></div>
                </div>
                <div class="color-info">
                    <div class="color-value-display">${currentValue}</div>
                    <div class="color-label">Click to change</div>
                </div>
                <div class="color-picker-icon">🎨</div>
            </div>
        `);
        
        // Create dropdown
        const $dropdown = createDropdown($input);
        
        // Append to wrapper
        $wrapper.append($display);
        $wrapper.append($dropdown);
        
        // Event listeners
        $display.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Close other dropdowns first
            $('.color-picker-dropdown').not($dropdown).removeClass('active');
            
            const isCurrentlyActive = $dropdown.hasClass('active');
            
            if (isCurrentlyActive) {
                // Close this dropdown
                $dropdown.removeClass('active');
                $backdrop.removeClass('active');
                return;
            }
            
            // Position dropdown below the clicked element
            const rect = this.getBoundingClientRect();
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;
            
            // Calculate position
            let top = rect.bottom + scrollTop + 8;
            let left = rect.left + scrollLeft;
            
            // Adjust if dropdown would go off screen
            const dropdownWidth = 520;
            const dropdownHeight = 600;
            
            if (left + dropdownWidth > window.innerWidth) {
                left = window.innerWidth - dropdownWidth - 20;
            }
            
            if (left < 10) {
                left = 10;
            }
            
            if (top + dropdownHeight > window.innerHeight + scrollTop) {
                // Show above if not enough space below
                top = rect.top + scrollTop - dropdownHeight - 8;
            }
            
            if (top < 10) {
                top = 10;
            }
            
            // Apply position
            $dropdown.css({
                'top': top + 'px',
                'left': left + 'px'
            });
            
            // Show dropdown and backdrop
            $dropdown.addClass('active');
            $backdrop.addClass('active');
        });
        
        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.modern-color-picker-wrapper').length && 
                !$(e.target).closest('.color-picker-dropdown').length) {
                $dropdown.removeClass('active');
                if ($backdrop) {
                    $backdrop.removeClass('active');
                }
            }
        });
    }
    
    /**
     * Create dropdown with all color options
     */
    function createDropdown($input) {
        const $dropdown = $('<div class="color-picker-dropdown"></div>');
        
        // Mode tabs
        const $modeTabs = $(`
            <div class="color-mode-tabs">
                <button type="button" class="color-mode-tab active" data-mode="solid">🎨 Solid Colors</button>
                <button type="button" class="color-mode-tab" data-mode="gradient">🌈 Gradients</button>
            </div>
        `);
        
        // Solid colors section
        const $solidSection = $('<div class="color-mode-section" data-section="solid"></div>');
        
        // Add color palettes
        Object.keys(colorPresets).forEach(category => {
            const $paletteSection = $(`
                <div class="color-palettes-section">
                    <div class="palette-section-title">${category.toUpperCase()}</div>
                    <div class="color-palette-grid"></div>
                </div>
            `);
            
            const $grid = $paletteSection.find('.color-palette-grid');
            colorPresets[category].forEach(color => {
                const $colorBtn = $(`
                    <div class="palette-color" data-color="${color}" style="background: ${color};" title="${color}"></div>
                `);
                $grid.append($colorBtn);
            });
            
            $solidSection.append($paletteSection);
        });
        
        // Recent colors
        if (recentColors.length > 0) {
            const $recentSection = $(`
                <div class="recent-colors-section">
                    <div class="palette-section-title">RECENTLY USED</div>
                    <div class="recent-colors-grid"></div>
                </div>
            `);
            
            const $recentGrid = $recentSection.find('.recent-colors-grid');
            recentColors.slice(0, 8).forEach(color => {
                const $colorBtn = $(`
                    <div class="palette-color" data-color="${color}" style="background: ${color};" title="${color}"></div>
                `);
                $recentGrid.append($colorBtn);
            });
            
            $solidSection.append($recentSection);
        }
        
        // Gradients section
        const $gradientSection = $(`
            <div class="color-mode-section" data-section="gradient" style="display: none;">
                <div class="gradient-presets-section">
                    <div class="palette-section-title">GRADIENT PRESETS</div>
                    <div class="gradient-preset-grid"></div>
                </div>
            </div>
        `);
        
        const $gradientGrid = $gradientSection.find('.gradient-preset-grid');
        gradientPresets.forEach(gradient => {
            const $gradientBtn = $(`
                <div class="gradient-preset" data-gradient="${gradient.value}" style="background: ${gradient.value};">
                    <div class="gradient-name">${gradient.name}</div>
                </div>
            `);
            $gradientGrid.append($gradientBtn);
        });
        
        // Custom input section
        const $customSection = $(`
            <div class="custom-color-input-section">
                <div class="palette-section-title">CUSTOM COLOR</div>
                <div class="color-input-group">
                    <input type="color" class="color-input-native" value="${$input.val() || '#2563eb'}" />
                    <input type="text" class="color-input-text" placeholder="#000000 or gradient" value="${$input.val() || ''}" />
                    <button type="button" class="clear-color-btn">Clear</button>
                </div>
            </div>
        `);
        
        // Assemble dropdown
        $dropdown.append($modeTabs);
        $dropdown.append($solidSection);
        $dropdown.append($gradientSection);
        $dropdown.append($customSection);
        
        // Event handlers
        setupDropdownEvents($dropdown, $input);
        
        return $dropdown;
    }
    
    /**
     * Setup all event handlers for the dropdown
     */
    function setupDropdownEvents($dropdown, $input) {
        const $wrapper = $input.closest('.modern-color-picker-wrapper');
        
        // Mode tab switching
        $dropdown.on('click', '.color-mode-tab', function() {
            const mode = $(this).data('mode');
            $dropdown.find('.color-mode-tab').removeClass('active');
            $(this).addClass('active');
            
            $dropdown.find('.color-mode-section').hide();
            $dropdown.find(`[data-section="${mode}"]`).show();
        });
        
        // Solid color selection
        $dropdown.on('click', '.palette-color', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const color = $(this).data('color');
            if (color) {
                updateColor($wrapper, $input, color);
                addToRecentColors(color);
                $dropdown.removeClass('active');
                if ($backdrop) $backdrop.removeClass('active');
            }
        });
        
        // Gradient selection
        $dropdown.on('click', '.gradient-preset', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const gradient = $(this).data('gradient');
            if (gradient) {
                updateColor($wrapper, $input, gradient);
                $dropdown.removeClass('active');
                if ($backdrop) $backdrop.removeClass('active');
            }
        });
        
        // Native color picker
        $dropdown.on('change', '.color-input-native', function() {
            const color = $(this).val();
            $dropdown.find('.color-input-text').val(color);
            updateColor($wrapper, $input, color);
            addToRecentColors(color);
        });
        
        // Text input
        $dropdown.on('change blur', '.color-input-text', function() {
            const value = $(this).val().trim();
            if (value) {
                updateColor($wrapper, $input, value);
                if (value.startsWith('#')) {
                    addToRecentColors(value);
                }
            }
        });
        
        // Clear button
        $dropdown.on('click', '.clear-color-btn', function() {
            updateColor($wrapper, $input, '');
            $dropdown.find('.color-input-text').val('');
            $dropdown.removeClass('active');
            if ($backdrop) $backdrop.removeClass('active');
        });
    }
    
    /**
     * Update color value
     */
    function updateColor($wrapper, $input, value) {
        $input.val(value).trigger('change');
        
        const $display = $wrapper.find('.color-picker-display');
        $display.find('.color-preview-color').css('background', value || '#f3f4f6');
        $display.find('.color-value-display').text(value || 'Not set');
        
        // Update dropdown input
        $wrapper.find('.color-input-text').val(value);
    }
    
    /**
     * Add color to recent colors
     */
    function addToRecentColors(color) {
        // Remove if already exists
        recentColors = recentColors.filter(c => c !== color);
        // Add to beginning
        recentColors.unshift(color);
        // Keep only last 8
        recentColors = recentColors.slice(0, 8);
        // Save to localStorage
        localStorage.setItem('ecocommerce_recent_colors', JSON.stringify(recentColors));
    }
    
    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        initModernColorPickers();
        
        // Reinitialize on dynamic content load
        $(document).on('DOMNodeInserted', function(e) {
            if ($(e.target).find('.color-picker-field, input[type="text"][name*="color"]').length) {
                setTimeout(initModernColorPickers, 100);
            }
        });
    });
    
})(jQuery);


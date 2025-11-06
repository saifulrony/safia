<?php
/**
 * Create Clothing Store Homepage with ProBuilder Template
 * 
 * Run this script to automatically create a homepage using the
 * "Clothing Store - Full Homepage" template
 * 
 * Usage: Access via browser: http://localhost:7000/create-clothing-store-homepage.php
 */

// Load WordPress
require_once('wp-load.php');

// Check if user is logged in and has permissions
if (!current_user_can('edit_pages')) {
    die('❌ Error: You must be logged in as an administrator to run this script.');
}

echo '<style>
    body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding: 40px; background: #f9fafb; }
    .container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    h1 { color: #1f2937; margin-bottom: 10px; }
    .step { padding: 20px; margin: 20px 0; background: #f3f4f6; border-radius: 8px; border-left: 4px solid #667eea; }
    .success { color: #10b981; font-weight: bold; }
    .error { color: #ef4444; font-weight: bold; }
    .info { color: #3b82f6; }
    .button { display: inline-block; background: #667eea; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; margin: 10px 10px 0 0; }
    .button:hover { background: #5568d3; }
    .button.secondary { background: #6b7280; }
    .button.secondary:hover { background: #4b5563; }
    code { background: #e5e7eb; padding: 2px 6px; border-radius: 3px; font-size: 14px; }
</style>';

echo '<div class="container">';
echo '<h1>👔 Clothing Store Homepage Creator</h1>';
echo '<p>This script will create a new homepage using the ProBuilder clothing store template.</p>';

// Check if ProBuilder is active
if (!class_exists('ProBuilder_Templates_Library')) {
    echo '<div class="step error">❌ ProBuilder plugin is not active. Please activate it first.</div>';
    echo '</div>';
    exit;
}

// Check if WooCommerce is active
if (!class_exists('WooCommerce')) {
    echo '<div class="step error">⚠️ Warning: WooCommerce is not active. The template uses WooCommerce products and categories.</div>';
}

echo '<div class="step">';
echo '<h3>📋 Creating Homepage...</h3>';

// Check if a page with this slug already exists
$existing_page = get_page_by_path('clothing-store-home', OBJECT, 'page');

if ($existing_page) {
    echo '<p class="info">ℹ️ A page with slug "clothing-store-home" already exists (ID: ' . $existing_page->ID . ')</p>';
    echo '<p>Do you want to:</p>';
    echo '<a href="?action=recreate" class="button">🔄 Recreate Page</a>';
    echo '<a href="?action=edit&page_id=' . $existing_page->ID . '" class="button secondary">✏️ Edit Existing</a>';
    echo '<a href="' . get_permalink($existing_page->ID) . '" class="button secondary" target="_blank">👁️ View Page</a>';
    
    if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['page_id'])) {
        $page_id = intval($_GET['page_id']);
        $edit_url = admin_url('post.php?post=' . $page_id . '&action=edit');
        echo '<script>window.location.href = "' . $edit_url . '";</script>';
        echo '<p>Redirecting to editor...</p>';
    }
    
    if (!isset($_GET['action']) || $_GET['action'] !== 'recreate') {
        echo '</div></div>';
        exit;
    }
    
    // Delete existing page
    wp_delete_post($existing_page->ID, true);
    echo '<p class="success">✅ Deleted existing page</p>';
}

// Create new page
$page_data = array(
    'post_title'    => 'Clothing Store Homepage',
    'post_name'     => 'clothing-store-home',
    'post_content'  => '<!-- ProBuilder page content -->',
    'post_status'   => 'publish',
    'post_type'     => 'page',
    'post_author'   => get_current_user_id(),
    'meta_input'    => array(
        '_wp_page_template' => 'elementor_header_footer',
    ),
);

$page_id = wp_insert_post($page_data);

if (is_wp_error($page_id)) {
    echo '<p class="error">❌ Error creating page: ' . $page_id->get_error_message() . '</p>';
    echo '</div></div>';
    exit;
}

echo '<p class="success">✅ Page created (ID: ' . $page_id . ')</p>';

// Get the template data
$templates_library = ProBuilder_Templates_Library::instance();
$template_data = $templates_library->get_template_data('clothing-store-home');

if (!$template_data) {
    echo '<p class="error">❌ Error: Could not load template data</p>';
    echo '</div></div>';
    exit;
}

echo '<p class="success">✅ Template loaded (' . count($template_data) . ' widgets)</p>';

// Save template data to page
update_post_meta($page_id, '_probuilder_data', $template_data);
update_post_meta($page_id, '_probuilder_edit_mode', 'builder');

echo '<p class="success">✅ Template applied to page</p>';

echo '</div>';

// Success message
echo '<div class="step" style="border-left-color: #10b981;">';
echo '<h3 class="success">🎉 Success! Your clothing store homepage is ready!</h3>';
echo '<p>The page has been created with all sections including:</p>';
echo '<ul>';
echo '<li>✨ Hero slider with 3 slides</li>';
echo '<li>👔 Category banners (Women, Men, Kids)</li>';
echo '<li>🌟 Featured products section</li>';
echo '<li>🎁 Promotional banner</li>';
echo '<li>🆕 New arrivals section</li>';
echo '<li>📂 Shop by category</li>';
echo '<li>💎 Features section</li>';
echo '<li>💬 Customer testimonials</li>';
echo '<li>📧 Newsletter signup</li>';
echo '</ul>';
echo '</div>';

// Next steps
echo '<div class="step">';
echo '<h3>📍 Next Steps:</h3>';
echo '<ol>';
echo '<li><strong>View the page:</strong> <a href="' . get_permalink($page_id) . '" target="_blank" class="button">View Homepage</a></li>';
echo '<li><strong>Edit with ProBuilder:</strong> <a href="' . admin_url('post.php?post=' . $page_id . '&action=edit') . '" class="button">Edit Page</a></li>';
echo '<li><strong>Set as homepage:</strong> Go to Settings → Reading → Set static page</li>';
echo '<li><strong>Customize:</strong> Edit colors, images, and text to match your brand</li>';
echo '<li><strong>Add products:</strong> Make sure you have WooCommerce products for best results</li>';
echo '</ol>';
echo '</div>';

// Set as homepage option
echo '<div class="step">';
echo '<h3>🏠 Set as Homepage?</h3>';
echo '<p>Would you like to set this page as your site\'s homepage?</p>';
echo '<a href="?set_homepage=' . $page_id . '" class="button">Yes, Set as Homepage</a>';
echo '<a href="' . get_permalink($page_id) . '" class="button secondary" target="_blank">No, Just View It</a>';
echo '</div>';

// Handle set homepage action
if (isset($_GET['set_homepage'])) {
    $homepage_id = intval($_GET['set_homepage']);
    update_option('show_on_front', 'page');
    update_option('page_on_front', $homepage_id);
    echo '<div class="step" style="border-left-color: #10b981;">';
    echo '<p class="success">✅ Homepage set successfully!</p>';
    echo '<p><a href="' . home_url() . '" class="button" target="_blank">View Your New Homepage</a></p>';
    echo '</div>';
}

// Additional resources
echo '<div class="step">';
echo '<h3>📚 Resources:</h3>';
echo '<ul>';
echo '<li>📖 <a href="/CLOTHING_STORE_TEMPLATE_GUIDE.md" target="_blank">Template Guide</a> - Detailed documentation</li>';
echo '<li>🎨 Template uses modern colors: Purple (#667eea), Dark Gray (#1f2937)</li>';
echo '<li>📱 Fully responsive and mobile-friendly</li>';
echo '<li>🛒 Requires WooCommerce for product display</li>';
echo '</ul>';
echo '</div>';

echo '<div class="step" style="background: #fef3c7; border-left-color: #f59e0b;">';
echo '<h3>💡 Pro Tips:</h3>';
echo '<ul>';
echo '<li>Mark some products as "Featured" in WooCommerce for the Featured Products section</li>';
echo '<li>Add product categories with images for the "Shop by Category" section</li>';
echo '<li>Replace placeholder images with your own high-quality photos</li>';
echo '<li>Customize colors to match your brand identity</li>';
echo '<li>Test the page on mobile devices for best user experience</li>';
echo '</ul>';
echo '</div>';

echo '</div>';


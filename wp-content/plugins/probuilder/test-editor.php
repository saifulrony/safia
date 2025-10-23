<?php
/**
 * Test ProBuilder Editor - Direct Access
 * Access: http://localhost:7000/wp-content/plugins/probuilder/test-editor.php
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if user is logged in
if (!is_user_logged_in()) {
    die('Please log in to WordPress first.');
}

// Set post ID for testing
$test_post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 2;

// Load ProBuilder
if (!class_exists('ProBuilder')) {
    die('ProBuilder plugin not loaded. Please activate it first.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ProBuilder Editor Test</title>
    
    <!-- WordPress Head -->
    <?php wp_head(); ?>
    
    <!-- ProBuilder CSS -->
    <link rel="stylesheet" href="<?php echo plugins_url('assets/css/editor.css', __FILE__); ?>?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
</head>
<body class="probuilder-editor-body">
    
    <h1 style="padding: 20px; background: #f0f0f0; margin: 0;">ProBuilder Editor Test - Post ID: <?php echo $test_post_id; ?></h1>
    
    <!-- Test: Can you see this sidebar? -->
    <div style="display: flex; min-height: 500px;">
        <div style="width: 320px; background: white; border-right: 1px solid #ddd; padding: 20px;">
            <h3>Sidebar Test</h3>
            <p>If you can see this, HTML is rendering!</p>
            
            <div id="test-widget-container">
                <!-- Widgets will load here via JS -->
            </div>
        </div>
        
        <div style="flex: 1; padding: 20px; background: #f5f5f5;">
            <h3>Canvas Test</h3>
            <p>If you can see this, the layout is working!</p>
            
            <div id="test-preview-area" style="background: white; padding: 20px; min-height: 300px;">
                <!-- Elements will appear here -->
            </div>
        </div>
    </div>
    
    <!-- ProBuilder JavaScript -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script>
        console.log('Test page loaded');
        console.log('jQuery:', typeof jQuery);
        console.log('jQuery UI:', typeof jQuery.ui);
    </script>
    
    <script>
        // Test ProBuilder data
        var ProBuilderEditor = {
            ajaxurl: '<?php echo admin_url('admin-ajax.php'); ?>',
            nonce: '<?php echo wp_create_nonce('probuilder_editor'); ?>',
            post_id: <?php echo $test_post_id; ?>,
            widgets: <?php echo json_encode(ProBuilder_Widgets_Manager::instance()->get_widgets_config()); ?>,
            templates: [],
            i18n: {},
            debug: true
        };
        
        console.log('ProBuilderEditor:', ProBuilderEditor);
        console.log('Widgets count:', ProBuilderEditor.widgets.length);
        
        // Render test widgets
        jQuery(document).ready(function($) {
            $('#test-widget-container').html('<h4>Widgets (' + ProBuilderEditor.widgets.length + '):</h4>');
            
            ProBuilderEditor.widgets.forEach(function(widget) {
                $('#test-widget-container').append(
                    '<div style="padding: 10px; border: 1px solid #ddd; margin: 5px;">' +
                    '<i class="' + widget.icon + '"></i> ' +
                    widget.title +
                    '</div>'
                );
            });
        });
    </script>
    
    <script src="<?php echo plugins_url('assets/js/editor.js', __FILE__); ?>?v=<?php echo time(); ?>"></script>
    
    <?php wp_footer(); ?>
</body>
</html>


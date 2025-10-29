<?php
/**
 * Quick Page Finder - Shows all ProBuilder pages with direct links
 * Access: /wp-content/plugins/probuilder/list-all-pages.php
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    die('Access denied. Admin only.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>ProBuilder - All Pages</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }
        .header h1 {
            font-size: 32px;
            color: #344047;
            margin-bottom: 10px;
        }
        .header p {
            color: #71717a;
            font-size: 16px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-number {
            font-size: 48px;
            font-weight: 700;
            color: #344047;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 14px;
            color: #71717a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .page-list {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }
        .page-item {
            padding: 25px 30px;
            border-bottom: 1px solid #e6e9ec;
            transition: all 0.2s;
        }
        .page-item:last-child {
            border-bottom: none;
        }
        .page-item:hover {
            background: #f8f9fa;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        .page-title {
            font-size: 20px;
            font-weight: 600;
            color: #344047;
            margin-bottom: 8px;
        }
        .page-url {
            font-size: 14px;
            color: #667eea;
            font-family: monospace;
            word-break: break-all;
        }
        .page-meta {
            display: flex;
            gap: 20px;
            font-size: 13px;
            color: #71717a;
            margin-bottom: 15px;
        }
        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-publish {
            background: #d4edda;
            color: #155724;
        }
        .badge-draft {
            background: #fff3cd;
            color: #856404;
        }
        .badge-elements {
            background: #e6f3ff;
            color: #0066cc;
        }
        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background: #344047;
            color: white;
        }
        .btn-primary:hover {
            background: #2c3540;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 64, 71, 0.3);
        }
        .btn-secondary {
            background: #e6e9ec;
            color: #344047;
        }
        .btn-secondary:hover {
            background: #d4d7db;
            transform: translateY(-2px);
        }
        .btn-success {
            background: #22c55e;
            color: white;
        }
        .btn-success:hover {
            background: #16a34a;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }
        .empty-state {
            padding: 60px 30px;
            text-align: center;
            color: #71717a;
        }
        .empty-state i {
            font-size: 80px;
            opacity: 0.2;
            margin-bottom: 20px;
        }
        .icon {
            width: 18px;
            height: 18px;
            display: inline-block;
        }
    </style>
    <link rel="stylesheet" href="<?php echo includes_url('css/dashicons.min.css'); ?>">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎨 ProBuilder Pages</h1>
            <p>All pages built with ProBuilder</p>
        </div>

        <?php
        // Get all pages with ProBuilder data
        $args = [
            'post_type' => ['page', 'post'],
            'post_status' => ['publish', 'draft', 'private'],
            'posts_per_page' => -1,
            'orderby' => 'modified',
            'order' => 'DESC',
            'meta_query' => [
                [
                    'key' => '_probuilder_data',
                    'compare' => 'EXISTS'
                ]
            ]
        ];

        $query = new WP_Query($args);
        
        $published_count = 0;
        $draft_count = 0;
        $total_elements = 0;

        if ($query->have_posts()) {
            // Calculate stats
            $temp_query = new WP_Query($args);
            while ($temp_query->have_posts()) {
                $temp_query->the_post();
                if (get_post_status() === 'publish') {
                    $published_count++;
                } else {
                    $draft_count++;
                }
                
                $data = get_post_meta(get_the_ID(), '_probuilder_data', true);
                if (is_string($data)) {
                    $data = json_decode($data, true);
                }
                if (is_array($data)) {
                    $total_elements += count($data);
                }
            }
            wp_reset_postdata();
            
            // Display stats
            ?>
            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $query->found_posts; ?></div>
                    <div class="stat-label">Total Pages</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $published_count; ?></div>
                    <div class="stat-label">Published</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $draft_count; ?></div>
                    <div class="stat-label">Drafts</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_elements; ?></div>
                    <div class="stat-label">Total Elements</div>
                </div>
            </div>
            <?php
            
            echo '<div class="page-list">';
            
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $probuilder_data = get_post_meta($post_id, '_probuilder_data', true);
                
                // Parse data
                if (is_string($probuilder_data)) {
                    $decoded = json_decode($probuilder_data, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $probuilder_data = $decoded;
                    }
                }
                
                $element_count = is_array($probuilder_data) ? count($probuilder_data) : 0;
                $status = get_post_status();
                $permalink = get_permalink();
                $slug = $post->post_name;
                
                ?>
                <div class="page-item">
                    <div class="page-header">
                        <div style="flex: 1;">
                            <div class="page-title"><?php the_title(); ?></div>
                            <div class="page-url">
                                <i class="dashicons dashicons-admin-links"></i>
                                <?php echo esc_html($permalink); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="page-meta">
                        <div class="meta-item">
                            <span class="badge <?php echo $status === 'publish' ? 'badge-publish' : 'badge-draft'; ?>">
                                <?php echo $status; ?>
                            </span>
                        </div>
                        <div class="meta-item">
                            <span class="badge badge-elements">
                                <?php echo $element_count; ?> element<?php echo $element_count != 1 ? 's' : ''; ?>
                            </span>
                        </div>
                        <div class="meta-item">
                            <i class="dashicons dashicons-calendar-alt"></i>
                            <?php echo get_the_modified_date('M j, Y'); ?>
                        </div>
                        <div class="meta-item">
                            <i class="dashicons dashicons-admin-post"></i>
                            <?php echo get_post_type(); ?>
                        </div>
                        <div class="meta-item">
                            <i class="dashicons dashicons-tag"></i>
                            ID: <?php echo $post_id; ?>
                        </div>
                    </div>
                    
                    <div class="actions">
                        <a href="<?php echo $permalink; ?>" 
                           class="btn btn-success" 
                           target="_blank">
                            <i class="dashicons dashicons-visibility"></i>
                            View Page
                        </a>
                        <a href="<?php echo add_query_arg(['p' => $post_id, 'probuilder' => 'true'], home_url('/')); ?>" 
                           class="btn btn-primary">
                            <i class="dashicons dashicons-edit"></i>
                            Edit in ProBuilder
                        </a>
                        <a href="<?php echo get_edit_post_link($post_id); ?>" 
                           class="btn btn-secondary">
                            <i class="dashicons dashicons-admin-page"></i>
                            Edit in WordPress
                        </a>
                    </div>
                </div>
                <?php
            }
            
            echo '</div>';
            wp_reset_postdata();
        } else {
            ?>
            <div class="page-list">
                <div class="empty-state">
                    <i class="dashicons dashicons-admin-page"></i>
                    <h3 style="font-size: 24px; margin-bottom: 10px; color: #344047;">No ProBuilder Pages Found</h3>
                    <p style="font-size: 16px;">Create your first page in ProBuilder to see it here!</p>
                </div>
            </div>
            <?php
        }
        ?>

        <div style="margin-top: 30px; text-align: center;">
            <a href="<?php echo admin_url('edit.php?post_type=page'); ?>" 
               class="btn btn-primary" 
               style="display: inline-flex;">
                <i class="dashicons dashicons-plus-alt2"></i>
                Create New Page
            </a>
            <a href="<?php echo admin_url(); ?>" 
               class="btn btn-secondary"
               style="display: inline-flex;">
                <i class="dashicons dashicons-dashboard"></i>
                Back to Dashboard
            </a>
        </div>

        <div style="margin-top: 40px; padding: 20px; background: rgba(255,255,255,0.9); border-radius: 8px; text-align: center; color: #71717a; font-size: 13px;">
            <p><strong>Bookmark this page!</strong> It's your quick access to all ProBuilder pages.</p>
            <p style="margin-top: 10px; font-family: monospace; background: #f4f4f5; padding: 10px; border-radius: 4px;">
                <?php echo home_url('/wp-content/plugins/probuilder/list-all-pages.php'); ?>
            </p>
        </div>
    </div>
</body>
</html>


<?php
/**
 * Admin Feedback Page Template
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1 style="margin-bottom: 20px;">
        <span class="dashicons dashicons-feedback" style="font-size: 32px; width: 32px; height: 32px;"></span>
        ProBuilder Deactivation Feedback
    </h1>

    <style>
        .feedback-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .feedback-stat-card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid #667eea;
        }
        .feedback-stat-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .feedback-stat-card .number {
            font-size: 48px;
            font-weight: 700;
            color: #667eea;
            line-height: 1;
        }
        
        .feedback-reasons {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .feedback-reasons h2 {
            margin: 0 0 20px 0;
            font-size: 18px;
            color: #344047;
        }
        .reason-bar {
            margin-bottom: 15px;
        }
        .reason-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: 600;
            color: #344047;
        }
        .reason-progress {
            height: 30px;
            background: #e5e7eb;
            border-radius: 15px;
            overflow: hidden;
        }
        .reason-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transition: width 0.5s;
            display: flex;
            align-items: center;
            padding-left: 15px;
            color: white;
            font-size: 13px;
            font-weight: 600;
        }
        
        .feedback-table {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .feedback-table h2 {
            margin: 0;
            padding: 20px 25px;
            background: #f8f9fa;
            border-bottom: 1px solid #e5e7eb;
            font-size: 18px;
            color: #344047;
        }
        .feedback-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .feedback-table th,
        .feedback-table td {
            padding: 15px 25px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        .feedback-table th {
            background: #f8f9fa;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
        }
        .feedback-table tr:hover {
            background: #f8f9fa;
        }
        .feedback-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .feedback-badge-bugs { background: #fee2e2; color: #991b1b; }
        .feedback-badge-features { background: #fef3c7; color: #92400e; }
        .feedback-badge-complex { background: #e0e7ff; color: #3730a3; }
        .feedback-badge-better { background: #dbeafe; color: #1e40af; }
        .feedback-badge-temporary { background: #d1fae5; color: #065f46; }
        .feedback-badge-other { background: #f3f4f6; color: #374151; }
        
        .feedback-details {
            max-width: 400px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #6b7280;
            font-size: 14px;
        }
        .feedback-details-full {
            white-space: normal;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }
        .empty-state .dashicons {
            font-size: 80px;
            width: 80px;
            height: 80px;
            opacity: 0.3;
            margin-bottom: 20px;
        }
    </style>

    <?php if ($total_feedback > 0): ?>
        
        <!-- Statistics Cards -->
        <div class="feedback-stats">
            <div class="feedback-stat-card">
                <h3>Total Feedback</h3>
                <div class="number"><?php echo $total_feedback; ?></div>
            </div>
            
            <div class="feedback-stat-card">
                <h3>Most Common Reason</h3>
                <div class="number" style="font-size: 20px; margin-top: 10px;">
                    <?php 
                    if (!empty($reason_counts)) {
                        echo ucwords(str_replace('-', ' ', $reason_counts[0]->reason));
                    }
                    ?>
                </div>
            </div>
            
            <div class="feedback-stat-card">
                <h3>Avg Pages Created</h3>
                <div class="number">
                    <?php 
                    $avg_pages = $wpdb->get_var("SELECT AVG(pages_created) FROM $table_name");
                    echo round($avg_pages, 1);
                    ?>
                </div>
            </div>
        </div>

        <!-- Reasons Chart -->
        <div class="feedback-reasons">
            <h2>Deactivation Reasons Breakdown</h2>
            <?php foreach ($reason_counts as $reason_data): ?>
                <?php 
                $percentage = ($reason_data->count / $total_feedback) * 100;
                $reason_label = ucwords(str_replace('-', ' ', $reason_data->reason));
                ?>
                <div class="reason-bar">
                    <div class="reason-label">
                        <span><?php echo $reason_label; ?></span>
                        <span><?php echo $reason_data->count; ?> (<?php echo round($percentage, 1); ?>%)</span>
                    </div>
                    <div class="reason-progress">
                        <div class="reason-progress-fill" style="width: <?php echo $percentage; ?>%">
                            <?php if ($percentage > 15): ?>
                                <?php echo round($percentage, 0); ?>%
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Feedback Table -->
        <div class="feedback-table">
            <h2>Recent Feedback</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reason</th>
                        <th>Details</th>
                        <th>Site</th>
                        <th>Pages</th>
                        <th>Versions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($feedback_items as $item): ?>
                        <?php
                        // Badge class based on reason
                        $badge_classes = [
                            'not-working' => 'feedback-badge-bugs',
                            'missing-features' => 'feedback-badge-features',
                            'too-complex' => 'feedback-badge-complex',
                            'found-better' => 'feedback-badge-better',
                            'temporary' => 'feedback-badge-temporary',
                            'no-longer-needed' => 'feedback-badge-temporary',
                            'other' => 'feedback-badge-other'
                        ];
                        $badge_class = $badge_classes[$item->reason] ?? 'feedback-badge-other';
                        ?>
                        <tr>
                            <td>
                                <strong><?php echo date('M j, Y', strtotime($item->created_at)); ?></strong><br>
                                <small style="color: #9ca3af;"><?php echo date('g:i A', strtotime($item->created_at)); ?></small>
                            </td>
                            <td>
                                <span class="feedback-badge <?php echo $badge_class; ?>">
                                    <?php echo ucwords(str_replace('-', ' ', $item->reason)); ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($item->details)): ?>
                                    <div class="feedback-details" title="<?php echo esc_attr($item->details); ?>">
                                        "<?php echo esc_html($item->details); ?>"
                                    </div>
                                <?php else: ?>
                                    <span style="color: #9ca3af;">No details provided</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo esc_url($item->site_url); ?>" target="_blank" style="color: #667eea;">
                                    <?php echo parse_url($item->site_url, PHP_URL_HOST); ?>
                                </a>
                            </td>
                            <td>
                                <strong><?php echo $item->pages_created; ?></strong>
                            </td>
                            <td style="font-size: 12px; color: #6b7280;">
                                WP: <?php echo $item->wp_version; ?><br>
                                PHP: <?php echo $item->php_version; ?><br>
                                PB: <?php echo $item->plugin_version; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php else: ?>
        
        <!-- Empty State -->
        <div class="feedback-table">
            <div class="empty-state">
                <div class="dashicons dashicons-feedback"></div>
                <h2 style="color: #6b7280; margin: 0 0 10px 0;">No Feedback Yet</h2>
                <p style="color: #9ca3af; margin: 0;">Feedback will appear here when users deactivate the plugin.</p>
            </div>
        </div>

    <?php endif; ?>

    <div style="margin-top: 30px; padding: 20px; background: #f0f4ff; border-left: 4px solid #667eea; border-radius: 8px;">
        <h3 style="margin: 0 0 10px 0; color: #344047;">💡 About This Feature</h3>
        <p style="margin: 0; color: #6b7280; line-height: 1.6;">
            This page collects feedback from users who deactivate ProBuilder. 
            Use this data to understand why users are leaving and improve the plugin.
            Feedback is saved in the database and optionally emailed to the admin.
        </p>
    </div>
</div>


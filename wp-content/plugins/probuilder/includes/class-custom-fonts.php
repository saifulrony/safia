<?php
/**
 * Custom Fonts Manager
 * Upload and manage custom font files
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProBuilder_Custom_Fonts {
    
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', [$this, 'add_fonts_menu']);
        add_action('wp_ajax_probuilder_upload_font', [$this, 'ajax_upload_font']);
        add_action('wp_ajax_probuilder_delete_font', [$this, 'ajax_delete_font']);
        add_action('wp_ajax_probuilder_get_fonts', [$this, 'ajax_get_fonts']);
        add_action('wp_head', [$this, 'output_font_faces']);
        add_action('admin_head', [$this, 'output_font_faces']);
        add_filter('upload_mimes', [$this, 'allow_font_uploads']);
    }
    
    /**
     * Allow font file uploads
     */
    public function allow_font_uploads($mimes) {
        $mimes['woff'] = 'font/woff';
        $mimes['woff2'] = 'font/woff2';
        $mimes['ttf'] = 'font/ttf';
        $mimes['otf'] = 'font/otf';
        $mimes['eot'] = 'application/vnd.ms-fontobject';
        
        return $mimes;
    }
    
    /**
     * Add fonts menu
     */
    public function add_fonts_menu() {
        add_submenu_page(
            'probuilder',
            __('Custom Fonts', 'probuilder'),
            __('Custom Fonts', 'probuilder'),
            'manage_options',
            'probuilder-custom-fonts',
            [$this, 'fonts_page']
        );
    }
    
    /**
     * Custom fonts page
     */
    public function fonts_page() {
        $fonts = get_option('probuilder_custom_fonts', []);
        
        ?>
        <div class="wrap">
            <h1><?php _e('Custom Fonts', 'probuilder'); ?></h1>
            <p><?php _e('Upload custom font files (WOFF, WOFF2, TTF, OTF)', 'probuilder'); ?></p>
            
            <div class="probuilder-font-upload" style="background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin: 20px 0;">
                <h2><?php _e('Upload New Font', 'probuilder'); ?></h2>
                
                <form id="font-upload-form">
                    <table class="form-table">
                        <tr>
                            <th><label><?php _e('Font Name', 'probuilder'); ?></label></th>
                            <td><input type="text" name="font_name" id="font-name" required style="width: 300px;"></td>
                        </tr>
                        <tr>
                            <th><label><?php _e('Font Weight', 'probuilder'); ?></label></th>
                            <td>
                                <select name="font_weight" id="font-weight">
                                    <option value="100">100 - Thin</option>
                                    <option value="200">200 - Extra Light</option>
                                    <option value="300">300 - Light</option>
                                    <option value="400" selected>400 - Normal</option>
                                    <option value="500">500 - Medium</option>
                                    <option value="600">600 - Semi Bold</option>
                                    <option value="700">700 - Bold</option>
                                    <option value="800">800 - Extra Bold</option>
                                    <option value="900">900 - Black</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label><?php _e('Font Style', 'probuilder'); ?></label></th>
                            <td>
                                <select name="font_style" id="font-style">
                                    <option value="normal">Normal</option>
                                    <option value="italic">Italic</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th><label><?php _e('WOFF2 File', 'probuilder'); ?></label></th>
                            <td><input type="file" name="font_woff2" id="font-woff2" accept=".woff2"></td>
                        </tr>
                        <tr>
                            <th><label><?php _e('WOFF File', 'probuilder'); ?></label></th>
                            <td><input type="file" name="font_woff" id="font-woff" accept=".woff"></td>
                        </tr>
                        <tr>
                            <th><label><?php _e('TTF File', 'probuilder'); ?></label></th>
                            <td><input type="file" name="font_ttf" id="font-ttf" accept=".ttf"></td>
                        </tr>
                    </table>
                    
                    <p class="submit">
                        <button type="submit" class="button button-primary"><?php _e('Upload Font', 'probuilder'); ?></button>
                    </p>
                </form>
            </div>
            
            <h2><?php _e('Installed Fonts', 'probuilder'); ?></h2>
            
            <?php if (empty($fonts)): ?>
                <p><?php _e('No custom fonts installed yet.', 'probuilder'); ?></p>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php _e('Font Name', 'probuilder'); ?></th>
                            <th><?php _e('Weight', 'probuilder'); ?></th>
                            <th><?php _e('Style', 'probuilder'); ?></th>
                            <th><?php _e('Preview', 'probuilder'); ?></th>
                            <th><?php _e('Actions', 'probuilder'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fonts as $font_id => $font): ?>
                        <tr>
                            <td><strong><?php echo esc_html($font['name']); ?></strong></td>
                            <td><?php echo esc_html($font['weight']); ?></td>
                            <td><?php echo esc_html($font['style']); ?></td>
                            <td>
                                <span style="font-family: '<?php echo esc_attr($font['name']); ?>'; font-weight: <?php echo esc_attr($font['weight']); ?>; font-style: <?php echo esc_attr($font['style']); ?>; font-size: 18px;">
                                    The quick brown fox
                                </span>
                            </td>
                            <td>
                                <button class="button button-small delete-font" data-font-id="<?php echo esc_attr($font_id); ?>">
                                    <?php _e('Delete', 'probuilder'); ?>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('#font-upload-form').on('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                formData.append('action', 'probuilder_upload_font');
                formData.append('nonce', '<?php echo wp_create_nonce('probuilder-editor'); ?>');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            alert('Font uploaded successfully!');
                            location.reload();
                        } else {
                            alert('Error: ' + response.data.message);
                        }
                    }
                });
            });
            
            $('.delete-font').on('click', function() {
                if (!confirm('Are you sure you want to delete this font?')) {
                    return;
                }
                
                const fontId = $(this).data('font-id');
                
                $.post(ajaxurl, {
                    action: 'probuilder_delete_font',
                    nonce: '<?php echo wp_create_nonce('probuilder-editor'); ?>',
                    font_id: fontId
                }, function(response) {
                    if (response.success) {
                        alert('Font deleted!');
                        location.reload();
                    }
                });
            });
        });
        </script>
        <?php
    }
    
    /**
     * Upload font
     */
    public function ajax_upload_font() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized']);
            return;
        }
        
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        
        $font_name = sanitize_text_field($_POST['font_name']);
        $font_weight = sanitize_text_field($_POST['font_weight']);
        $font_style = sanitize_text_field($_POST['font_style']);
        
        $files = [];
        
        // Upload each font file
        foreach (['woff2', 'woff', 'ttf'] as $format) {
            if (isset($_FILES['font_' . $format]) && $_FILES['font_' . $format]['size'] > 0) {
                $upload = wp_handle_upload($_FILES['font_' . $format], ['test_form' => false]);
                
                if (isset($upload['error'])) {
                    wp_send_json_error(['message' => $upload['error']]);
                    return;
                }
                
                $files[$format] = $upload['url'];
            }
        }
        
        if (empty($files)) {
            wp_send_json_error(['message' => 'Please upload at least one font file']);
            return;
        }
        
        // Save font data
        $fonts = get_option('probuilder_custom_fonts', []);
        $font_id = uniqid('font_');
        
        $fonts[$font_id] = [
            'name' => $font_name,
            'weight' => $font_weight,
            'style' => $font_style,
            'files' => $files
        ];
        
        update_option('probuilder_custom_fonts', $fonts);
        
        wp_send_json_success(['message' => 'Font uploaded successfully']);
    }
    
    /**
     * Delete font
     */
    public function ajax_delete_font() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => 'Unauthorized']);
            return;
        }
        
        $font_id = sanitize_text_field($_POST['font_id']);
        
        $fonts = get_option('probuilder_custom_fonts', []);
        
        if (isset($fonts[$font_id])) {
            unset($fonts[$font_id]);
            update_option('probuilder_custom_fonts', $fonts);
        }
        
        wp_send_json_success(['message' => 'Font deleted']);
    }
    
    /**
     * Get fonts for editor
     */
    public function ajax_get_fonts() {
        check_ajax_referer('probuilder-editor', 'nonce');
        
        $fonts = get_option('probuilder_custom_fonts', []);
        
        wp_send_json_success($fonts);
    }
    
    /**
     * Output @font-face rules
     */
    public function output_font_faces() {
        $fonts = get_option('probuilder_custom_fonts', []);
        
        if (empty($fonts)) {
            return;
        }
        
        echo '<style id="probuilder-custom-fonts">';
        
        foreach ($fonts as $font) {
            $src = [];
            
            if (isset($font['files']['woff2'])) {
                $src[] = "url('" . esc_url($font['files']['woff2']) . "') format('woff2')";
            }
            if (isset($font['files']['woff'])) {
                $src[] = "url('" . esc_url($font['files']['woff']) . "') format('woff')";
            }
            if (isset($font['files']['ttf'])) {
                $src[] = "url('" . esc_url($font['files']['ttf']) . "') format('truetype')";
            }
            
            if (!empty($src)) {
                echo '@font-face {';
                echo 'font-family: "' . esc_attr($font['name']) . '";';
                echo 'src: ' . implode(', ', $src) . ';';
                echo 'font-weight: ' . esc_attr($font['weight']) . ';';
                echo 'font-style: ' . esc_attr($font['style']) . ';';
                echo 'font-display: swap;';
                echo '}';
            }
        }
        
        echo '</style>';
    }
    
    /**
     * Get font list for select control
     */
    public static function get_font_list() {
        $fonts = get_option('probuilder_custom_fonts', []);
        $list = [];
        
        foreach ($fonts as $font) {
            $list[$font['name']] = $font['name'];
        }
        
        return $list;
    }
}


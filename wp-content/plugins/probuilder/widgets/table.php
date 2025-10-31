<?php
if (!defined('ABSPATH')) exit;

class ProBuilder_Table_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'table'; }
    public function get_title() { return __('Table', 'probuilder'); }
    public function get_icon() { return 'fa fa-table'; }
    public function get_categories() { return ['content']; }
    
    protected function register_controls() {
        $this->add_control('rows', ['label' => __('Table Rows', 'probuilder'), 'type' => 'repeater', 'default' => [['cells' => 'Cell 1|Cell 2|Cell 3']], 'fields' => [
            ['name' => 'cells', 'label' => 'Cells (| separated)', 'type' => 'text', 'default' => 'Cell 1|Cell 2'],
            ['name' => 'is_header', 'label' => 'Header Row', 'type' => 'switcher', 'default' => false],
        ]]);
        
        $this->start_style_tab();
        $this->add_control('stripe', ['label' => __('Striped Rows', 'probuilder'), 'type' => 'switcher', 'default' => true]);
        $this->add_control('border', ['label' => __('Show Borders', 'probuilder'), 'type' => 'switcher', 'default' => true]);
        $this->add_control('hover', ['label' => __('Hover Effect', 'probuilder'), 'type' => 'switcher', 'default' => true]);
        $this->end_style_tab();
    }
    
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $s = $this->get_settings();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $wrapper_style = '';
        if ($inline_styles) $wrapper_style = ' style="' . esc_attr($inline_styles) . '"';
        echo '<table class="' . esc_attr($wrapper_classes) . ' pb-table' . ($s['stripe'] ? ' striped' : '') . ($s['border'] ? ' bordered' : '') . ($s['hover'] ? ' hover' : '') . '" ' . $wrapper_attributes . $wrapper_style . '>';
        foreach ($s['rows'] as $row) {
            $cells = explode('|', $row['cells']);
            $tag = $row['is_header'] ? 'th' : 'td';
            echo '<tr>';
            foreach ($cells as $cell) echo '<' . $tag . '>' . esc_html(trim($cell)) . '</' . $tag . '>';
            echo '</tr>';
        }
        echo '</table>';
        echo '<style>.pb-table{width:100%;border-collapse:collapse}.pb-table.bordered td,.pb-table.bordered th{border:1px solid #ddd}.pb-table td,.pb-table th{padding:12px;text-align:left}.pb-table th{background:#f5f5f5;font-weight:600}.pb-table.striped tr:nth-child(even){background:#f9f9f9}.pb-table.hover tr:hover{background:#f0f0f0}</style>';
    }
}


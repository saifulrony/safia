<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_PayPal_Button_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'paypal-button'; }
    public function get_title() { return __('PayPal Button', 'probuilder'); }
    public function get_icon() { return 'fa fa-paypal'; }
    public function get_categories() { return ['content']; }
    protected function register_controls() {
        $this->add_control('email', ['label' => 'PayPal Email', 'type' => 'text', 'default' => '']);
        $this->add_control('amount', ['label' => 'Amount', 'type' => 'number', 'default' => 10]);
        $this->add_control('currency', ['label' => 'Currency', 'type' => 'select', 'options' => ['USD' => 'USD', 'EUR' => 'EUR', 'GBP' => 'GBP'], 'default' => 'USD']);
        $this->add_control('button_text', ['label' => 'Button Text', 'type' => 'text', 'default' => 'Buy Now']);
    }
    protected function render() {
        // Render custom CSS if any
        $this->render_custom_css();
        
        $s = $this->get_settings();
        
        // Get wrapper classes and attributes from base class
        $wrapper_classes = $this->get_wrapper_classes();
        $wrapper_attributes = $this->get_wrapper_attributes();
        $inline_styles = $this->get_inline_styles();
        
        $style = 'background:#0070ba;color:#fff;padding:12px 30px;border:none;border-radius:4px;cursor:pointer;font-size:16px;font-weight:600;';
        if ($inline_styles) $style .= ' ' . $inline_styles;
        echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" class="' . esc_attr($wrapper_classes) . ' pb-paypal-form" ' . $wrapper_attributes . '>
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="business" value="' . esc_attr($s['email']) . '">
        <input type="hidden" name="amount" value="' . esc_attr($s['amount']) . '">
        <input type="hidden" name="currency_code" value="' . esc_attr($s['currency']) . '">
        <button type="submit" class="pb-paypal-btn" style="' . esc_attr($style) . '">' . esc_html($s['button_text']) . '</button>
        </form>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_PayPal_Button_Widget());


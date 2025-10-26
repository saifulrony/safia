<?php
if (!defined('ABSPATH')) exit;
class ProBuilder_Stripe_Button_Widget extends ProBuilder_Base_Widget {
    public function get_name() { return 'stripe-button'; }
    public function get_title() { return __('Stripe Payment Button', 'probuilder'); }
    public function get_icon() { return 'fa fa-stripe-s'; }
    public function get_categories() { return ['content']; }
    protected function register_controls() {
        $this->add_control('publishable_key', ['label' => 'Publishable Key', 'type' => 'text', 'default' => '']);
        $this->add_control('amount', ['label' => 'Amount (cents)', 'type' => 'number', 'default' => 1000]);
        $this->add_control('currency', ['label' => 'Currency', 'type' => 'select', 'options' => ['usd' => 'USD', 'eur' => 'EUR', 'gbp' => 'GBP'], 'default' => 'usd']);
        $this->add_control('button_text', ['label' => 'Button Text', 'type' => 'text', 'default' => 'Pay with Stripe']);
    }
    protected function render() {
        $s = $this->get_settings();
        echo '<button id="stripe-btn-' . uniqid() . '" class="pb-stripe-btn" style="background:#635bff;color:#fff;padding:12px 30px;border:none;border-radius:4px;cursor:pointer;font-size:16px;font-weight:600">' . esc_html($s['button_text']) . '</button>';
        echo '<script src="https://js.stripe.com/v3/"></script>';
        echo '<script>var stripe=Stripe("' . esc_js($s['publishable_key']) . '");document.getElementById("stripe-btn-' . uniqid() . '").addEventListener("click",function(){alert("Stripe checkout - Amount: $' . ($s['amount']/100) . ' ' . strtoupper($s['currency']) . '")});</script>';
    }
}
ProBuilder_Widgets_Manager::instance()->register_widget(new ProBuilder_Stripe_Button_Widget());


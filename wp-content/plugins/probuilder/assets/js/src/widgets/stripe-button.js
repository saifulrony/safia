export default // Widget renderer for "stripe-button" (auto-generated)
function renderStripeButton(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const stripeAmount = settings.amount || 1000;
  const stripeCurrency = settings.currency || 'usd';
  const stripeButtonText = settings.button_text || 'Pay with Stripe';
  const stripeDisplayAmount = (stripeAmount / 100).toFixed(2);
  return `<div style="text-align:center;padding:30px;background:#f9f9f9;border-radius:8px">
                        <div style="margin-bottom:20px">
                            <i class="fa fa-stripe-s" style="font-size:48px;color:#635bff"></i>
                        </div>
                        <button style="background:#635bff;color:#fff;border:none;padding:12px 30px;border-radius:4px;cursor:pointer;font-size:16px;font-weight:600;box-shadow:0 2px 5px rgba(99,91,255,0.3)">
                            <i class="fa fa-lock" style="margin-right:8px"></i>
                            ${stripeButtonText}
                        </button>
                        <p style="margin:15px 0 0;color:#666;font-size:14px">Amount: $${stripeDisplayAmount} ${stripeCurrency.toUpperCase()}</p>
                        <small style="color:#999;font-size:12px">Secure payment processing</small>
                    </div>`;
}
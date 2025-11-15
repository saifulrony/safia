export default // Widget renderer for "paypal-button" (auto-generated)
function renderPaypalButton(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const paypalAmount = settings.amount || 10;
  const paypalCurrency = settings.currency || 'USD';
  const paypalButtonText = settings.button_text || 'Buy Now';
  return `<div style="text-align:center;padding:30px;background:#f9f9f9;border-radius:8px">
                        <div style="margin-bottom:20px">
                            <i class="fa fa-paypal" style="font-size:48px;color:#0070ba"></i>
                        </div>
                        <button style="background:#0070ba;color:#fff;border:none;padding:12px 30px;border-radius:4px;cursor:pointer;font-size:16px;font-weight:600;box-shadow:0 2px 5px rgba(0,112,186,0.3)">
                            <i class="fa fa-paypal" style="margin-right:8px"></i>
                            ${paypalButtonText}
                        </button>
                        <p style="margin:15px 0 0;color:#666;font-size:14px">Amount: $${paypalAmount} ${paypalCurrency}</p>
                    </div>`;
}
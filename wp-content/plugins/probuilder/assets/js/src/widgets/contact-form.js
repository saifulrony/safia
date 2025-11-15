export default // Widget renderer for "contact-form" (auto-generated)
function renderContactForm(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const formTitle = settings.form_title || 'Get in Touch';
  const formButtonText = settings.button_text || 'Send Message';
  const formButtonColor = settings.button_color || '#92003b';
  let formHTML = `<div style="padding: 30px; background: #fff; border: 1px solid #e5e5e5; border-radius: 8px;">`;
  if (formTitle) {
    formHTML += `<h3 style="margin: 0 0 25px 0; font-size: 24px; color: #333; font-weight: 600;">${formTitle}</h3>`;
  }
  formHTML += `<div style="display: flex; flex-direction: column; gap: 15px;">`;
  formHTML += `<input type="text" placeholder="Your Name" style="padding: 12px 15px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" disabled>`;
  formHTML += `<input type="email" placeholder="Your Email" style="padding: 12px 15px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" disabled>`;
  formHTML += `<input type="text" placeholder="Subject" style="padding: 12px 15px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" disabled>`;
  formHTML += `<textarea placeholder="Your Message" rows="4" style="padding: 12px 15px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; resize: vertical;" disabled></textarea>`;
  formHTML += `<button type="button" style="background: ${formButtonColor}; color: #fff; padding: 14px 30px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 15px;">${formButtonText}</button>`;
  formHTML += `</div>`;
  formHTML += `</div>`;
  return formHTML;
}
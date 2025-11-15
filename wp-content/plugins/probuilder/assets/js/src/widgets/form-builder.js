export default // Widget renderer for "form-builder" (auto-generated)
function renderFormBuilder(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const fbFormTitle = settings.form_title || 'Contact Us';
  const fbFormDescription = settings.form_description || 'Send us a message and we\'ll get back to you as soon as possible.';
  const fbFormButtonText = settings.submit_button_text || 'Send Message';
  const fbFormBgColor = settings.form_bg_color || '#ffffff';
  const fbFormPadding = settings.form_padding || {
    top: 30,
    right: 30,
    bottom: 30,
    left: 30
  };
  const fbFormBorderRadius = settings.form_border_radius || {
    size: 8
  };
  const fbFormBoxShadow = settings.form_box_shadow !== 'no';
  const fbFieldBgColor = settings.field_bg_color || '#ffffff';
  const fbFieldBorderColor = settings.field_border_color || '#e1e5e9';
  const fbFieldFocusColor = settings.field_focus_color || '#92003b';
  const fbButtonBgColor = settings.button_bg_color || '#92003b';
  const fbButtonTextColor = settings.button_text_color || '#ffffff';
  const fbFormStyle = `
                        background-color: ${fbFormBgColor};
                        padding: ${fbFormPadding.top}px ${fbFormPadding.right}px ${fbFormPadding.bottom}px ${fbFormPadding.left}px;
                        border-radius: ${fbFormBorderRadius.size}px;
                        ${fbFormBoxShadow ? 'box-shadow: 0 4px 20px rgba(0,0,0,0.1);' : ''}
                    `;
  let fbFormHTML = `<div style="${fbFormStyle}">`;
  if (fbFormTitle) {
    fbFormHTML += `<h3 style="margin-top: 0; margin-bottom: 15px; color: #1e293b; font-size: 24px;">${fbFormTitle}</h3>`;
  }
  if (fbFormDescription) {
    fbFormHTML += `<p style="margin-bottom: 25px; color: #64748b; font-size: 14px;">${fbFormDescription}</p>`;
  }
  fbFormHTML += `<form style="display: flex; flex-direction: column; gap: 20px;">`;

  // Sample form fields
  // Sample form fields
  fbFormHTML += `<div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #1e293b; font-size: 14px;">Name *</label>
                        <input type="text" placeholder="Your Name" style="width: 100%; padding: 12px; border: 1px solid ${fbFieldBorderColor}; border-radius: 4px; font-family: inherit; box-sizing: border-box;" disabled>
                    </div>`;
  fbFormHTML += `<div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #1e293b; font-size: 14px;">Email *</label>
                        <input type="email" placeholder="your@email.com" style="width: 100%; padding: 12px; border: 1px solid ${fbFieldBorderColor}; border-radius: 4px; font-family: inherit; box-sizing: border-box;" disabled>
                    </div>`;
  fbFormHTML += `<div>
                        <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #1e293b; font-size: 14px;">Message *</label>
                        <textarea placeholder="Your message..." style="width: 100%; padding: 12px; border: 1px solid ${fbFieldBorderColor}; border-radius: 4px; font-family: inherit; min-height: 100px; resize: vertical; box-sizing: border-box;" disabled></textarea>
                    </div>`;
  fbFormHTML += `<button type="submit" style="background-color: ${fbButtonBgColor}; color: ${fbButtonTextColor}; padding: 12px 30px; border: none; border-radius: 4px; font-size: 16px; font-weight: 600; cursor: pointer; align-self: flex-start;">${fbFormButtonText}</button>`;
  fbFormHTML += `</form></div>`;
  return fbFormHTML;
}
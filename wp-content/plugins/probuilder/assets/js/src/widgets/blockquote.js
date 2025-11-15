export default // Widget renderer for "blockquote" (auto-generated)
function renderBlockquote(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const quoteText = settings.quote_text || 'The only way to do great work is to love what you do.';
  const author = settings.author || 'Steve Jobs';
  const authorTitle = settings.author_title || 'Apple Co-founder';
  const quoteStyle = settings.quote_style || 'border';
  const accentColorQuote = settings.accent_color || '#92003b';
  const quoteTextColor = settings.quote_color || '#333333';
  const quoteFontSize = settings.quote_size || 20;
  const quoteBgColor = settings.background_color || 'transparent';
  const showQuoteIcon = settings.show_icon !== 'no';
  const quotePadding = settings.padding || {
    top: 20,
    right: 30,
    bottom: 20,
    left: 30
  };
  const quoteMargin = settings.margin || {
    top: 20,
    right: 0,
    bottom: 20,
    left: 0
  };
  let blockquoteStyle = '';
  if (quoteStyle === 'border') {
    blockquoteStyle = `border-left: 4px solid ${accentColorQuote}; padding-left: 30px; font-style: italic;`;
  } else if (quoteStyle === 'box') {
    blockquoteStyle = `border: 2px solid ${accentColorQuote}; padding: 30px; background: ${quoteBgColor !== 'transparent' ? quoteBgColor : '#f9f9f9'}; border-radius: 8px;`;
  } else {
    blockquoteStyle = `font-style: italic; padding: 20px 0;`;
  }
  let blockquoteHTML = `
                        <blockquote class="probuilder-blockquote" style="
                            ${blockquoteStyle}
                            margin: ${quoteMargin.top}px ${quoteMargin.right}px ${quoteMargin.bottom}px ${quoteMargin.left}px;
                            padding: ${quotePadding.top}px ${quotePadding.right}px ${quotePadding.bottom}px ${quotePadding.left}px;
                            background: ${quoteBgColor !== 'transparent' ? quoteBgColor : quoteStyle === 'box' ? '#f9f9f9' : 'transparent'};
                        ">
                    `;

  // Quote icon
  // Quote icon
  if (showQuoteIcon) {
    blockquoteHTML += `
                            <div style="font-size: 48px; color: ${accentColorQuote}; opacity: 0.3; margin-bottom: 15px;">
                                <i class="fa fa-quote-left"></i>
                            </div>
                        `;
  }

  // Quote text
  // Quote text
  blockquoteHTML += `
                        <p style="
                            font-size: ${quoteFontSize}px;
                            line-height: 1.6;
                            margin: 0 0 20px 0;
                            color: ${quoteTextColor};
                            font-style: italic;
                        ">${quoteText}</p>
                    `;

  // Author
  // Author
  if (author) {
    blockquoteHTML += `
                            <footer style="font-style: normal;">
                                <cite style="font-weight: 600; color: ${accentColorQuote}; font-style: normal;">
                                    ${author}
                                </cite>
                        `;
    if (authorTitle) {
      blockquoteHTML += `<span style="color: #999; font-size: 14px;"> — ${authorTitle}</span>`;
    }
    blockquoteHTML += '</footer>';
  }
  blockquoteHTML += '</blockquote>';
  return blockquoteHTML;
}
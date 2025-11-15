export default // Widget renderer for "archives" (auto-generated)
function renderArchives(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const archivesTitle = settings.title || 'Archives';
  const archivesShowTitle = settings.show_title !== 'no';
  const archivesFormat = settings.format || 'html';
  const archivesShowCount = settings.show_post_count !== 'no';
  const archivesTitleSize = settings.title_size || 24;
  const archivesTitleColor = settings.title_color || '#333333';
  const archivesLinkColor = settings.link_color || '#0073aa';
  const archivesTextSize = settings.text_size || 15;
  const archivesItemSpacing = settings.item_spacing || 10;
  let archivesHTML = `<div style="padding: 20px;">`;
  if (archivesShowTitle && archivesTitle) {
    archivesHTML += `<h3 style="margin: 0 0 20px 0; font-size: ${archivesTitleSize}px; color: ${archivesTitleColor}; font-weight: 600;">${archivesTitle}</h3>`;
  }
  if (archivesFormat === 'option') {
    archivesHTML += `<select style="width: 100%; padding: 10px; font-size: ${archivesTextSize}px; border: 1px solid #ddd; border-radius: 4px;">`;
    archivesHTML += `<option>Select Month</option>`;
    archivesHTML += `<option>November 2025 ${archivesShowCount ? '(12)' : ''}</option>`;
    archivesHTML += `<option>October 2025 ${archivesShowCount ? '(15)' : ''}</option>`;
    archivesHTML += `<option>September 2025 ${archivesShowCount ? '(8)' : ''}</option>`;
    archivesHTML += `<option>August 2025 ${archivesShowCount ? '(21)' : ''}</option>`;
    archivesHTML += `</select>`;
  } else {
    archivesHTML += `<ul style="margin: 0; padding: 0; list-style-type: disc; padding-left: 20px;">`;
    archivesHTML += `<li style="margin-bottom: ${archivesItemSpacing}px; font-size: ${archivesTextSize}px;"><a href="#" style="color: ${archivesLinkColor}; text-decoration: none;">November 2025</a>${archivesShowCount ? '<span style="color: #999; margin-left: 5px;">(12)</span>' : ''}</li>`;
    archivesHTML += `<li style="margin-bottom: ${archivesItemSpacing}px; font-size: ${archivesTextSize}px;"><a href="#" style="color: ${archivesLinkColor}; text-decoration: none;">October 2025</a>${archivesShowCount ? '<span style="color: #999; margin-left: 5px;">(15)</span>' : ''}</li>`;
    archivesHTML += `<li style="margin-bottom: ${archivesItemSpacing}px; font-size: ${archivesTextSize}px;"><a href="#" style="color: ${archivesLinkColor}; text-decoration: none;">September 2025</a>${archivesShowCount ? '<span style="color: #999; margin-left: 5px;">(8)</span>' : ''}</li>`;
    archivesHTML += `<li style="margin-bottom: ${archivesItemSpacing}px; font-size: ${archivesTextSize}px;"><a href="#" style="color: ${archivesLinkColor}; text-decoration: none;">August 2025</a>${archivesShowCount ? '<span style="color: #999; margin-left: 5px;">(21)</span>' : ''}</li>`;
    archivesHTML += `<li style="margin-bottom: ${archivesItemSpacing}px; font-size: ${archivesTextSize}px;"><a href="#" style="color: ${archivesLinkColor}; text-decoration: none;">July 2025</a>${archivesShowCount ? '<span style="color: #999; margin-left: 5px;">(9)</span>' : ''}</li>`;
    archivesHTML += `</ul>`;
  }
  archivesHTML += `</div>`;
  return archivesHTML;
}
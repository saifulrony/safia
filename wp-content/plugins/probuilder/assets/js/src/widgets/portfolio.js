export default // Widget renderer for "portfolio" (auto-generated)
function renderPortfolio(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const portfolioItems = settings.items || [{
    title: 'Project 1',
    image: 'https://via.placeholder.com/400x300/93003c/ffffff?text=Project+1',
    category: 'Design'
  }, {
    title: 'Project 2',
    image: 'https://via.placeholder.com/400x300/0073aa/ffffff?text=Project+2',
    category: 'Development'
  }, {
    title: 'Project 3',
    image: 'https://via.placeholder.com/400x300/4caf50/ffffff?text=Project+3',
    category: 'Branding'
  }];
  const portfolioColumns = settings.columns || '3';
  let portfolioHTML = `<div style="display:grid;grid-template-columns:repeat(${portfolioColumns},1fr);gap:20px">`;
  portfolioItems.forEach(item => {
    portfolioHTML += `
                            <div style="position:relative;overflow:hidden;border-radius:8px;cursor:pointer">
                                <img src="${item.image || 'https://via.placeholder.com/400x300'}" style="width:100%;height:200px;object-fit:cover;display:block">
                                <div style="position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.7);display:flex;align-items:center;justify-content:center;flex-direction:column;color:#fff;opacity:0;transition:opacity 0.3s" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                                    <h3 style="margin:0 0 8px;font-size:20px;color:#fff">${item.title || 'Project'}</h3>
                                    <p style="margin:0;color:#ccc;font-size:14px">${item.category || 'Category'}</p>
                                </div>
                            </div>
                        `;
  });
  portfolioHTML += '</div>';
  return portfolioHTML;
}
export default // Widget renderer for "team-member" (auto-generated)
function renderTeamMember(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const teamImage = settings.image?.url || 'https://i.pravatar.cc/300?img=12';
  const teamName = settings.name || 'John Doe';
  const teamPosition = settings.position || 'CEO & Founder';
  const teamBio = settings.bio || 'Passionate about creating amazing products.';
  const teamEmail = settings.email || '';
  const teamPhone = settings.phone || '';
  const teamFacebook = settings.facebook || '';
  const teamTwitter = settings.twitter || '';
  const teamLinkedin = settings.linkedin || '';
  const teamInstagram = settings.instagram || '';

  // Style settings
  // Style settings
  const teamLayout = settings.layout || 'left';
  const teamTextAlign = settings.text_align || 'left';
  const teamImageSize = settings.image_size || 150;
  const teamBorderColor = settings.border_color || '#92003b';
  const teamNameColor = settings.name_color || '#333333';
  const teamPositionColor = settings.position_color || '#92003b';
  let teamHTML = '';
  let teamContainerStyle = 'padding: 20px; border: 1px solid #e5e5e5; border-radius: 8px; background: #fff;';
  if (teamLayout === 'center') {
    // Centered layout (image on top)
    teamContainerStyle += ` text-align: ${teamTextAlign}; display: flex; flex-direction: column; align-items: center;`;
    teamHTML = `<div style="${teamContainerStyle}">`;

    // Photo - centered
    teamHTML += `<div style="margin-bottom: 20px; display: inline-block;">
                            <img src="${teamImage}" alt="${teamName}" style="width: ${teamImageSize}px; height: ${teamImageSize}px; border-radius: 50%; object-fit: cover; border: 3px solid ${teamBorderColor}; display: block;">
                        </div>`;
  } else {
    // Left or Right layout
    const flexDirection = teamLayout === 'left' ? 'row' : 'row-reverse';
    teamContainerStyle += ` display: flex; flex-direction: ${flexDirection}; gap: 25px; align-items: flex-start;`;
    teamHTML = `<div style="${teamContainerStyle}">`;

    // Photo
    teamHTML += `<div style="flex-shrink: 0;">
                            <img src="${teamImage}" alt="${teamName}" style="width: ${teamImageSize}px; height: ${teamImageSize}px; border-radius: 50%; object-fit: cover; border: 3px solid ${teamBorderColor};">
                        </div>`;

    // Content wrapper
    teamHTML += `<div style="flex: 1; text-align: ${teamTextAlign};">`;
  }

  // Name
  // Name
  teamHTML += `<h3 style="margin: 0 0 5px 0; font-size: 22px; font-weight: 600; color: ${teamNameColor};">${teamName}</h3>`;

  // Position
  // Position
  teamHTML += `<div style="color: ${teamPositionColor}; font-size: 14px; font-weight: 600; margin-bottom: 15px;">${teamPosition}</div>`;

  // Bio
  // Bio
  if (teamBio) {
    teamHTML += `<p style="color: #666; font-size: 14px; line-height: 1.6; margin: 0 0 15px 0;">${teamBio}</p>`;
  }

  // Contact
  // Contact
  if (teamEmail || teamPhone) {
    const contactAlign = teamTextAlign === 'center' ? 'center' : teamTextAlign === 'right' ? 'flex-end' : 'flex-start';
    teamHTML += `<div style="font-size: 13px; color: #666; margin-bottom: 15px; display: flex; flex-direction: column; align-items: ${contactAlign}; gap: 5px;">`;
    if (teamEmail) teamHTML += `<div style="display: flex; align-items: center; gap: 8px;"><i class="fa fa-envelope" style="color: ${teamPositionColor};"></i> <span>${teamEmail}</span></div>`;
    if (teamPhone) teamHTML += `<div style="display: flex; align-items: center; gap: 8px;"><i class="fa fa-phone" style="color: ${teamPositionColor};"></i> <span>${teamPhone}</span></div>`;
    teamHTML += `</div>`;
  }

  // Social links
  // Social links
  const hasSocial = teamFacebook || teamTwitter || teamLinkedin || teamInstagram;
  if (hasSocial) {
    const socialJustify = teamTextAlign === 'center' ? 'center' : teamTextAlign === 'right' ? 'flex-end' : 'flex-start';
    teamHTML += `<div style="display: flex; justify-content: ${socialJustify}; gap: 10px; margin-top: 15px;">`;
    if (teamFacebook || !hasSocial) {
      teamHTML += `<a href="#" style="display: inline-flex; align-items: center; justify-content: center; width: 35px; height: 35px; background: #3b5998; color: #fff; border-radius: 50%; text-decoration: none;"><i class="fab fa-facebook-f"></i></a>`;
    }
    if (teamTwitter || !hasSocial) {
      teamHTML += `<a href="#" style="display: inline-flex; align-items: center; justify-content: center; width: 35px; height: 35px; background: #1da1f2; color: #fff; border-radius: 50%; text-decoration: none;"><i class="fab fa-twitter"></i></a>`;
    }
    if (teamLinkedin || !hasSocial) {
      teamHTML += `<a href="#" style="display: inline-flex; align-items: center; justify-content: center; width: 35px; height: 35px; background: #0077b5; color: #fff; border-radius: 50%; text-decoration: none;"><i class="fab fa-linkedin-in"></i></a>`;
    }
    if (teamInstagram) {
      teamHTML += `<a href="#" style="display: inline-flex; align-items: center; justify-content: center; width: 35px; height: 35px; background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); color: #fff; border-radius: 50%; text-decoration: none;"><i class="fab fa-instagram"></i></a>`;
    }
    teamHTML += `</div>`;
  }

  // Close content wrapper for left/right layouts
  // Close content wrapper for left/right layouts
  if (teamLayout !== 'center') {
    teamHTML += `</div>`;
  }
  teamHTML += `</div>`;
  return teamHTML;
}
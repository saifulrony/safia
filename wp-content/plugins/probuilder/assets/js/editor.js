/* This file is auto-generated via esbuild. Edit files under assets/js/src instead. */
(() => {
  // wp-content/plugins/probuilder/assets/js/src/widgets/accordion.js
  function renderAccordion(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const accordionItems = settings2.items || [{
      title: "What is ProBuilder?",
      content: "ProBuilder is a powerful page builder that allows you to create stunning websites with drag-and-drop functionality."
    }, {
      title: "How do I use it?",
      content: "Simply drag widgets from the left panel onto your canvas and customize them using the settings panel on the right."
    }, {
      title: "Is it responsive?",
      content: "Yes! ProBuilder creates fully responsive designs that work perfectly on all devices."
    }];
    const allowMultiple = settings2.allow_multiple || "no";
    const defaultOpen = parseInt(settings2.default_open) || 1;
    const accordionTitleBg = settings2.title_bg_color || "#f8f9fa";
    const accordionTitleText = settings2.title_text_color || "#333333";
    const accordionActiveBg = settings2.active_bg_color || "#92003b";
    const accordionActiveText = settings2.active_text_color || "#ffffff";
    const accordionContentBg = settings2.content_bg_color || "#ffffff";
    const accordionContentText = settings2.content_text_color || "#666666";
    const accordionBorderColor = settings2.border_color || "#e6e9ec";
    const accordionBorderRadius = settings2.border_radius || 4;
    const accordionPadding = settings2.padding || {
      top: 20,
      right: 0,
      bottom: 20,
      left: 0
    };
    const accordionMargin = settings2.margin || {
      top: 20,
      right: 0,
      bottom: 20,
      left: 0
    };
    const accordionId = "accordion-" + element2.id;
    const accordionContainerStyle = `margin: ${accordionMargin.top}px ${accordionMargin.right}px ${accordionMargin.bottom}px ${accordionMargin.left}px;`;
    let accordionHTML = `<div class="probuilder-accordion-preview" data-accordion-id="${accordionId}" data-allow-multiple="${allowMultiple}" style="width: 100%; ${accordionContainerStyle}">`;
    accordionItems.forEach((item, index) => {
      const isOpen = defaultOpen > 0 && index + 1 === defaultOpen;
      accordionHTML += `
                            <div class="probuilder-accordion-item" data-item-index="${index}" style="margin-bottom: 10px; border: 1px solid ${accordionBorderColor}; border-radius: ${accordionBorderRadius}px; overflow: hidden;">
                                <div class="probuilder-accordion-header" style="
                                    padding: 15px 20px;
                                    background: ${isOpen ? accordionActiveBg : accordionTitleBg};
                                    color: ${isOpen ? accordionActiveText : accordionTitleText};
                                    font-weight: 600;
                                    cursor: pointer;
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: center;
                                    transition: all 0.3s ease;
                                    border-radius: ${accordionBorderRadius}px;
                                " data-active-bg="${accordionActiveBg}" data-active-color="${accordionActiveText}" data-inactive-bg="${accordionTitleBg}" data-inactive-color="${accordionTitleText}">
                                    <span>${item.title || `Item ${index + 1}`}</span>
                                    <span class="probuilder-accordion-icon" style="font-size: 18px; transition: all 0.3s;">${isOpen ? "\u2212" : "+"}</span>
                                </div>
                                <div class="probuilder-accordion-content" style="
                                    padding: ${isOpen ? "15px 20px" : "0 20px"};
                                    max-height: ${isOpen ? "500px" : "0"};
                                    background: ${accordionContentBg};
                                    color: ${accordionContentText};
                                    overflow: hidden;
                                    transition: all 0.3s ease;
                                    opacity: ${isOpen ? "1" : "0"};
                                    border-top: none;
                                ">
                                    ${item.content || "Content for accordion item"}
                                </div>
                            </div>
                        `;
    });
    accordionHTML += "</div>";
    setTimeout(function() {
      const $global = window.jQuery || window.$;
      if (!$global) {
        console.warn("Accordion renderer: jQuery not available");
        return;
      }
      const $accordionContainer = $global(`[data-accordion-id="${accordionId}"]`);
      if ($accordionContainer.length === 0) {
        return;
      }
      const allowMultipleOpen = $accordionContainer.data("allow-multiple") === "yes";
      $accordionContainer.find(".probuilder-accordion-item").each(function() {
        const $item = $global(this);
        const $header = $item.find(".probuilder-accordion-header");
        const $content = $item.find(".probuilder-accordion-content");
        const isOpen = $header.find(".probuilder-accordion-icon").text() === "\u2212";
        if (isOpen) {
          const naturalHeight = $content.prop("scrollHeight");
          $content.css({
            "max-height": naturalHeight + "px",
            "padding": "15px 20px",
            "opacity": "1"
          });
        }
      });
      $accordionContainer.find(".probuilder-accordion-header").off("click.probuilderAccordion").on("click.probuilderAccordion", function(e) {
        e.stopPropagation();
        const $header = $global(this);
        const $item = $header.closest(".probuilder-accordion-item");
        const $content = $item.find(".probuilder-accordion-content");
        const $icon = $header.find(".probuilder-accordion-icon");
        const activeBg = $header.data("active-bg");
        const activeColor = $header.data("active-color");
        const inactiveBg = $header.data("inactive-bg");
        const inactiveColor = $header.data("inactive-color");
        const isCurrentlyOpen = $content.hasClass("probuilder-accordion-open");
        if (!allowMultipleOpen && !isCurrentlyOpen) {
          $accordionContainer.find(".probuilder-accordion-content").each(function() {
            const $otherContent = $global(this);
            $otherContent.removeClass("probuilder-accordion-open").css({
              "max-height": "0",
              "padding": "0 20px",
              "opacity": "0"
            });
          });
          $accordionContainer.find(".probuilder-accordion-icon").text("+");
          $accordionContainer.find(".probuilder-accordion-header").css({
            "background": inactiveBg,
            "color": inactiveColor
          });
        }
        if (isCurrentlyOpen) {
          $content.removeClass("probuilder-accordion-open").css({
            "max-height": "0",
            "padding": "0 20px",
            "opacity": "0"
          });
          $icon.text("+");
          $header.css({
            "background": inactiveBg,
            "color": inactiveColor
          });
        } else {
          const targetHeight = $content.prop("scrollHeight");
          $content.addClass("probuilder-accordion-open").css({
            "max-height": targetHeight + "px",
            "padding": "15px 20px",
            "opacity": "1"
          });
          $icon.text("\u2212");
          $header.css({
            "background": activeBg,
            "color": activeColor
          });
        }
      });
    }, 100);
    return accordionHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/alert.js
  function renderAlert(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const alertType = settings2.alert_type || "info";
    const alertTitle = settings2.title || "Information";
    const alertMessage = settings2.message || "This is an alert message.";
    const isDismissible = settings2.dismissible !== "no";
    const showIcon = settings2.show_icon !== "no";
    const accentColor = settings2.accent_color || "#92003b";
    const quoteColor = settings2.quote_color || "#333333";
    const quoteSize = settings2.quote_size || 20;
    const bgColor = settings2.background_color || "transparent";
    const alertPadding = settings2.padding || {
      top: 20,
      right: 30,
      bottom: 20,
      left: 30
    };
    const alertMargin = settings2.margin || {
      top: 20,
      right: 0,
      bottom: 20,
      left: 0
    };
    const alertColors = {
      "info": {
        bg: "#e3f2fd",
        border: "#2196f3",
        text: "#0d47a1",
        icon: "fa-circle-info"
      },
      "success": {
        bg: "#e8f5e9",
        border: "#4caf50",
        text: "#1b5e20",
        icon: "fa-circle-check"
      },
      "warning": {
        bg: "#fff3e0",
        border: "#ff9800",
        text: "#e65100",
        icon: "fa-triangle-exclamation"
      },
      "error": {
        bg: "#ffebee",
        border: "#f44336",
        text: "#b71c1c",
        icon: "fa-circle-xmark"
      }
    };
    const colorScheme = alertColors[alertType] || alertColors["info"];
    const finalBg = bgColor !== "transparent" ? bgColor : colorScheme.bg;
    const finalBorder = accentColor || colorScheme.border;
    const finalText = quoteColor || colorScheme.text;
    let alertHTML = `
                        <div class="probuilder-alert probuilder-alert-${alertType}" style="
                            background: ${finalBg};
                            border-left: 4px solid ${finalBorder};
                            color: ${finalText};
                            padding: ${alertPadding.top}px ${alertPadding.right}px ${alertPadding.bottom}px ${alertPadding.left}px;
                            margin: ${alertMargin.top}px ${alertMargin.right}px ${alertMargin.bottom}px ${alertMargin.left}px;
                            border-radius: 4px;
                            position: relative;
                        ">
                            <div style="display: flex; align-items: flex-start; gap: 15px;">
                    `;
    if (showIcon) {
      alertHTML += `<div style="font-size: 24px; color: ${finalBorder};">
                            <i class="fa ${colorScheme.icon}"></i>
                        </div>`;
    }
    alertHTML += `
                        <div style="flex: 1;">
                            <h4 style="margin: 0 0 8px 0; font-size: ${quoteSize}px; font-weight: 600; color: ${finalText};">
                                ${alertTitle}
                            </h4>
                    `;
    if (alertMessage) {
      alertHTML += `<p style="margin: 0; font-size: 14px; line-height: 1.6; color: ${finalText}; opacity: 0.9;">
                            ${alertMessage}
                        </p>`;
    }
    alertHTML += "</div>";
    if (isDismissible) {
      alertHTML += `
                            <button class="probuilder-alert-close" style="
                                background: transparent;
                                border: none;
                                color: ${finalText};
                                cursor: pointer;
                                font-size: 20px;
                                padding: 0;
                                opacity: 0.6;
                                transition: opacity 0.2s;
                            " onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.6'">
                                <i class="fa fa-times"></i>
                            </button>
                        `;
    }
    alertHTML += "</div></div>";
    return alertHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/anchor.js
  function renderAnchor(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const anchorId = settings2.anchor_id || "section-1";
    return `<div style="padding: 20px; background: #f0f8ff; border: 2px dashed #0073aa; border-radius: 8px; text-align: center;">
                        <i class="fa fa-anchor" style="font-size: 32px; color: #0073aa; margin-bottom: 10px; display: block;"></i>
                        <h4 style="margin: 0 0 5px; color: #0073aa;">Anchor Point</h4>
                        <p style="margin: 0; color: #666; font-size: 13px;">ID: <strong>${anchorId}</strong></p>
                        <p style="margin: 5px 0 0; color: #999; font-size: 11px;">Use this ID in links to scroll to this section</p>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/animated-headline.js
  function renderAnimatedHeadline(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const ahTitle = settings2.before_text || "We Are";
    const ahWords = settings2.animated_text || "Creative\nAwesome\nProfessional";
    const ahStyle = settings2.animation_type || "typing";
    const firstWord = ahWords.split("\n")[0];
    return `<div style="padding: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; text-align: center;">
                        <h2 style="margin: 0; color: #fff; font-size: 36px; font-weight: 700;">
                            ${ahTitle} <span style="color: #ffd700;">${firstWord}</span>
                        </h2>
                        <p style="margin: 10px 0 0; color: rgba(255,255,255,0.8); font-size: 13px;">
                            <i class="fa fa-magic"></i> Animation: ${ahStyle}
                        </p>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/animated-text.js
  function renderAnimatedText(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const animText = settings2.text || "Animated Text";
    const animType = settings2.animation || "typing";
    const animTextColor = settings2.color || "#0073aa";
    const animTextSize = settings2.size || 36;
    let animPreview = "";
    if (animType === "typing") {
      animPreview = `<h2 style="color:${animTextColor};font-size:${animTextSize}px;margin:0;font-weight:700;border-right:3px solid ${animTextColor}">${animText}</h2>`;
    } else if (animType === "wave") {
      animPreview = `<h2 style="color:${animTextColor};font-size:${animTextSize}px;margin:0;font-weight:700">${animText}</h2>`;
    } else if (animType === "glitch") {
      animPreview = `<h2 style="color:${animTextColor};font-size:${animTextSize}px;margin:0;font-weight:700;text-shadow:2px 2px ${animTextColor}">${animText}</h2>`;
    } else if (animType === "neon") {
      animPreview = `<h2 style="color:${animTextColor};font-size:${animTextSize}px;margin:0;font-weight:700;text-shadow:0 0 10px ${animTextColor},0 0 20px ${animTextColor}">${animText}</h2>`;
    }
    return `<div style="background:#2d2d2d;padding:40px;border-radius:8px;text-align:center">
                        ${animPreview}
                        <p style="margin:15px 0 0;color:#999;font-size:12px">Animation: ${animType}</p>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/archive-title.js
  function renderArchiveTitle(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const archiveTag = settings2.tag || "h1";
    const archiveColor = settings2.color || "#333";
    return `<${archiveTag} style="color:${archiveColor};margin:0;font-size:36px;font-weight:700">Archive: Category Name</${archiveTag}>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/archives.js
  function renderArchives(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const archivesTitle = settings2.title || "Archives";
    const archivesShowTitle = settings2.show_title !== "no";
    const archivesFormat = settings2.format || "html";
    const archivesShowCount = settings2.show_post_count !== "no";
    const archivesTitleSize = settings2.title_size || 24;
    const archivesTitleColor = settings2.title_color || "#333333";
    const archivesLinkColor = settings2.link_color || "#0073aa";
    const archivesTextSize = settings2.text_size || 15;
    const archivesItemSpacing = settings2.item_spacing || 10;
    let archivesHTML = `<div style="padding: 20px;">`;
    if (archivesShowTitle && archivesTitle) {
      archivesHTML += `<h3 style="margin: 0 0 20px 0; font-size: ${archivesTitleSize}px; color: ${archivesTitleColor}; font-weight: 600;">${archivesTitle}</h3>`;
    }
    if (archivesFormat === "option") {
      archivesHTML += `<select style="width: 100%; padding: 10px; font-size: ${archivesTextSize}px; border: 1px solid #ddd; border-radius: 4px;">`;
      archivesHTML += `<option>Select Month</option>`;
      archivesHTML += `<option>November 2025 ${archivesShowCount ? "(12)" : ""}</option>`;
      archivesHTML += `<option>October 2025 ${archivesShowCount ? "(15)" : ""}</option>`;
      archivesHTML += `<option>September 2025 ${archivesShowCount ? "(8)" : ""}</option>`;
      archivesHTML += `<option>August 2025 ${archivesShowCount ? "(21)" : ""}</option>`;
      archivesHTML += `</select>`;
    } else {
      archivesHTML += `<ul style="margin: 0; padding: 0; list-style-type: disc; padding-left: 20px;">`;
      archivesHTML += `<li style="margin-bottom: ${archivesItemSpacing}px; font-size: ${archivesTextSize}px;"><a href="#" style="color: ${archivesLinkColor}; text-decoration: none;">November 2025</a>${archivesShowCount ? '<span style="color: #999; margin-left: 5px;">(12)</span>' : ""}</li>`;
      archivesHTML += `<li style="margin-bottom: ${archivesItemSpacing}px; font-size: ${archivesTextSize}px;"><a href="#" style="color: ${archivesLinkColor}; text-decoration: none;">October 2025</a>${archivesShowCount ? '<span style="color: #999; margin-left: 5px;">(15)</span>' : ""}</li>`;
      archivesHTML += `<li style="margin-bottom: ${archivesItemSpacing}px; font-size: ${archivesTextSize}px;"><a href="#" style="color: ${archivesLinkColor}; text-decoration: none;">September 2025</a>${archivesShowCount ? '<span style="color: #999; margin-left: 5px;">(8)</span>' : ""}</li>`;
      archivesHTML += `<li style="margin-bottom: ${archivesItemSpacing}px; font-size: ${archivesTextSize}px;"><a href="#" style="color: ${archivesLinkColor}; text-decoration: none;">August 2025</a>${archivesShowCount ? '<span style="color: #999; margin-left: 5px;">(21)</span>' : ""}</li>`;
      archivesHTML += `<li style="margin-bottom: ${archivesItemSpacing}px; font-size: ${archivesTextSize}px;"><a href="#" style="color: ${archivesLinkColor}; text-decoration: none;">July 2025</a>${archivesShowCount ? '<span style="color: #999; margin-left: 5px;">(9)</span>' : ""}</li>`;
      archivesHTML += `</ul>`;
    }
    archivesHTML += `</div>`;
    return archivesHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/area-chart.js
  function renderAreaChart(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const areaTitle = settings2.chart_title || "Website Traffic";
    const areaShowTitle = settings2.show_title !== "no";
    const areaHeight = settings2.chart_height || 400;
    const areaLineColor = settings2.line_color || "#4BC0C0";
    const areaData = settings2.chart_data || "Week 1, 2400\nWeek 2, 3200\nWeek 3, 2800\nWeek 4, 4100\nWeek 5, 3900\nWeek 6, 5200";
    const areaLineWidth = settings2.line_width || 3;
    const areaShowPoints = settings2.show_points !== "no";
    const areaFillOpacity = (settings2.fill_opacity || 40) / 100;
    const areaLines = areaData.split("\n").filter((line) => line.trim());
    const areaLabels = [];
    const areaValues = [];
    areaLines.forEach((line) => {
      const parts = line.split(",").map((s) => s.trim());
      if (parts.length >= 2) {
        areaLabels.push(parts[0]);
        areaValues.push(parseFloat(parts[1]) || 0);
      }
    });
    const areaMaxValue = Math.max(...areaValues);
    const areaMinValue = Math.min(...areaValues);
    const areaRange = areaMaxValue - areaMinValue || 1;
    const areaSvgWidth = 400;
    const areaSvgHeight = 200;
    const areaPadding = 30;
    const areaPlotWidth = areaSvgWidth - 2 * areaPadding;
    const areaPlotHeight = areaSvgHeight - 2 * areaPadding;
    let areaPoints = areaValues.map((value, i) => {
      const x = areaPadding + i / (areaValues.length - 1 || 1) * areaPlotWidth;
      const y = areaPadding + areaPlotHeight - (value - areaMinValue) / areaRange * areaPlotHeight;
      return {
        x,
        y
      };
    });
    const areaPolylinePoints = areaPoints.map((p) => `${p.x},${p.y}`).join(" ");
    const areaPolygonPoints = `${areaPadding},${areaPadding + areaPlotHeight} ${areaPolylinePoints} ${areaPoints[areaPoints.length - 1].x},${areaPadding + areaPlotHeight}`;
    const gradId = "areaGrad" + Date.now();
    let areaHTML = `<div style="padding: 20px; text-align: center;">`;
    if (areaShowTitle && areaTitle) {
      areaHTML += `<h3 style="margin-bottom: 20px; font-size: 24px; font-weight: 600;">${areaTitle}</h3>`;
    }
    areaHTML += `<div style="display: flex; justify-content: center; align-items: center; height: ${areaHeight}px; background: #f9f9f9; border-radius: 8px; padding: 20px;">`;
    areaHTML += `<svg width="100%" height="100%" viewBox="0 0 ${areaSvgWidth} ${areaSvgHeight + 40}">`;
    areaHTML += `<defs><linearGradient id="${gradId}" x1="0%" y1="0%" x2="0%" y2="100%">`;
    areaHTML += `<stop offset="0%" style="stop-color:${areaLineColor};stop-opacity:${areaFillOpacity}" />`;
    areaHTML += `<stop offset="100%" style="stop-color:${areaLineColor};stop-opacity:0.05" />`;
    areaHTML += `</linearGradient></defs>`;
    areaHTML += `<polygon points="${areaPolygonPoints}" fill="url(#${gradId})"/>`;
    areaHTML += `<polyline points="${areaPolylinePoints}" fill="none" stroke="${areaLineColor}" stroke-width="${areaLineWidth}"/>`;
    if (areaShowPoints) {
      areaPoints.forEach((p) => {
        areaHTML += `<circle cx="${p.x}" cy="${p.y}" r="5" fill="${areaLineColor}"/>`;
      });
    }
    areaLabels.forEach((label, i) => {
      const x = areaPadding + i / (areaLabels.length - 1 || 1) * areaPlotWidth;
      areaHTML += `<text x="${x}" y="${areaSvgHeight}" text-anchor="middle" font-size="11" fill="#666">${label}</text>`;
    });
    areaHTML += `</svg>`;
    areaHTML += `</div></div>`;
    return areaHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/audio.js
  function renderAudio(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const audioUrl = settings2.audio_url || "";
    const audioTitle = settings2.title || "Audio Player";
    return `<div style="padding: 20px; background: #f5f5f5; border-radius: 8px;">
                        <h4 style="margin: 0 0 15px; color: #333;">${audioTitle}</h4>
                        <div style="background: #92003b; color: #fff; padding: 15px; border-radius: 4px; display: flex; align-items: center; gap: 15px;">
                            <i class="fa fa-play-circle" style="font-size: 32px;"></i>
                            <div style="flex: 1;">
                                <div style="height: 4px; background: rgba(255,255,255,0.3); border-radius: 2px; margin-bottom: 5px;">
                                    <div style="height: 100%; width: 30%; background: #fff; border-radius: 2px;"></div>
                                </div>
                                <div style="font-size: 11px; opacity: 0.9;">0:45 / 3:24</div>
                            </div>
                            <i class="fa fa-volume-up" style="font-size: 20px;"></i>
                        </div>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/author-box.js
  function renderAuthorBox(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    return `<div style="background:#f9f9f9;border:1px solid #eee;padding:30px;border-radius:8px;display:flex;gap:20px;align-items:center">
                        <div style="width:80px;height:80px;border-radius:50%;background:#ddd;flex-shrink:0"></div>
                        <div style="flex:1">
                            <h3 style="margin:0 0 10px;font-size:24px">Author Name</h3>
                            <p style="margin:0 0 15px;color:#666">Author biography will appear here...</p>
                            <a href="#" style="color:#0073aa;text-decoration:none;font-weight:600">View All Posts \u2192</a>
                        </div>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/back-to-top.js
  function renderBackToTop(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const backPosition = settings2.position || "bottom-right";
    const backSize = settings2.size || 50;
    const backButtonColor = settings2.button_color || "#0073aa";
    const backIconColor = settings2.icon_color || "#ffffff";
    const posStyle = backPosition === "bottom-right" ? "bottom:20px;right:20px" : backPosition === "bottom-left" ? "bottom:20px;left:20px" : backPosition === "top-right" ? "top:20px;right:20px" : "top:20px;left:20px";
    return `<div style="background:#f5f5f5;padding:40px;border-radius:8px;position:relative;min-height:200px">
                        <p style="margin:0 0 100px;color:#666;text-align:center">Scroll down to see button...</p>
                        <div style="position:absolute;${posStyle};width:${backSize}px;height:${backSize}px;background:${backButtonColor};color:${backIconColor};border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 10px rgba(0,0,0,0.2);cursor:pointer;font-size:24px;font-weight:bold">\u2191</div>
                        <p style="margin:0;color:#999;font-size:12px;text-align:center">Back to Top Button (${backPosition})</p>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/bar-chart.js
  function renderBarChart(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const barTitle = settings2.chart_title || "Sales by Category";
    const barShowTitle = settings2.show_title !== "no";
    const barHeight = settings2.chart_height || 400;
    const barColorMode = settings2.color_mode || "single";
    const barColor = settings2.bar_color || "#36A2EB";
    const barGradientColor = settings2.gradient_color || "#9966FF";
    const barMultiColors = settings2.multi_colors || "#FF6384, #36A2EB, #FFCE56, #4BC0C0, #9966FF, #FF9F40";
    const barData = settings2.chart_data || "Electronics, 12500\nClothing, 9800\nHome & Garden, 7600\nSports, 6400\nBooks, 5200";
    const barRadius = settings2.border_radius || 4;
    const barOrientation = settings2.orientation || "vertical";
    const barShowValues = settings2.show_values_on_bars === "yes";
    const barValueFormat = settings2.value_format || "number";
    const barLines = barData.split("\n").filter((line) => line.trim());
    const barLabels = [];
    const barValues = [];
    barLines.forEach((line) => {
      const parts = line.split(",").map((s) => s.trim());
      if (parts.length >= 2) {
        barLabels.push(parts[0]);
        barValues.push(parseFloat(parts[1]) || 0);
      }
    });
    if (barValues.length === 0) {
      return '<div style="padding: 20px; text-align: center; color: #999;">Enter chart data to see preview</div>';
    }
    const barMaxValue = Math.max(...barValues);
    let barColors = [];
    if (barColorMode === "multi") {
      const multiColorArray = barMultiColors.split(",").map((c) => c.trim());
      barColors = barLabels.map((_, i) => multiColorArray[i % multiColorArray.length]);
    } else if (barColorMode === "gradient") {
      barColors = barLabels.map(() => `linear-gradient(180deg, ${barColor}, ${barGradientColor})`);
    } else {
      barColors = barLabels.map(() => barColor);
    }
    let barHTML = `<div style="padding: 20px; text-align: center;">`;
    if (barShowTitle && barTitle) {
      barHTML += `<h3 style="margin-bottom: 20px; font-size: 24px; font-weight: 600;">${barTitle}</h3>`;
    }
    if (barOrientation === "vertical") {
      barHTML += `<div style="display: flex; justify-content: center; align-items: flex-end; gap: 10px; height: ${barHeight}px; background: #f9f9f9; border-radius: 8px; padding: 40px 20px 40px;">`;
      barValues.forEach((value, i) => {
        const heightPercent = value / barMaxValue * 80;
        let formattedValue = value.toLocaleString();
        if (barValueFormat === "currency") formattedValue = "$" + formattedValue;
        if (barValueFormat === "percentage") formattedValue = formattedValue + "%";
        barHTML += `<div style="flex: 1; max-width: 80px; display: flex; flex-direction: column; align-items: center; gap: 8px;">`;
        if (barShowValues) {
          barHTML += `<span style="font-size: 11px; color: #666; font-weight: 600;">${formattedValue}</span>`;
        }
        barHTML += `<div style="width: 100%; height: ${heightPercent}%; background: ${barColors[i]}; border-radius: ${barRadius}px ${barRadius}px 0 0;"></div>`;
        barHTML += `<span style="font-size: 11px; color: #666; text-align: center; word-break: break-word;">${barLabels[i]}</span>`;
        barHTML += `</div>`;
      });
      barHTML += `</div>`;
    } else {
      barHTML += `<div style="display: flex; flex-direction: column; justify-content: center; gap: 15px; height: ${barHeight}px; background: #f9f9f9; border-radius: 8px; padding: 20px;">`;
      barValues.forEach((value, i) => {
        const widthPercent = value / barMaxValue * 85;
        let formattedValue = value.toLocaleString();
        if (barValueFormat === "currency") formattedValue = "$" + formattedValue;
        if (barValueFormat === "percentage") formattedValue = formattedValue + "%";
        barHTML += `<div style="display: flex; align-items: center; gap: 10px;">`;
        barHTML += `<span style="font-size: 12px; color: #666; min-width: 100px; text-align: right;">${barLabels[i]}</span>`;
        barHTML += `<div style="display: flex; align-items: center; width: ${widthPercent}%; height: 30px; background: ${barColors[i]}; border-radius: 0 ${barRadius}px ${barRadius}px 0; position: relative;">`;
        if (barShowValues) {
          barHTML += `<span style="position: absolute; right: -50px; font-size: 11px; color: #666; font-weight: 600; white-space: nowrap;">${formattedValue}</span>`;
        }
        barHTML += `</div>`;
        barHTML += `</div>`;
      });
      barHTML += `</div>`;
    }
    barHTML += `</div>`;
    return barHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/before-after.js
  function renderBeforeAfter(context2) {
    var _a, _b;
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const baBeforeImageUrl = ((_a = settings2.before_image) == null ? void 0 : _a.url) || "https://via.placeholder.com/800x600/999/fff?text=Before";
    const baAfterImageUrl = ((_b = settings2.after_image) == null ? void 0 : _b.url) || "https://via.placeholder.com/800x600/92003b/fff?text=After";
    const baBeforeLabel = settings2.before_label || "Before";
    const baAfterLabel = settings2.after_label || "After";
    const baPosition = 50;
    return `<div style="position: relative; overflow: hidden; border-radius: 8px; max-width: 100%; background: #f5f5f5;">
                        <div style="position: relative; height: 400px; background: #f0f0f0;">
                            <!-- After Image (bottom layer) -->
                            <img src="${baAfterImageUrl}" alt="After" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                            
                            <!-- Before Image (top layer with clip) -->
                            <div style="position: absolute; top: 0; left: 0; width: ${baPosition}%; height: 100%; overflow: hidden;">
                                <img src="${baBeforeImageUrl}" alt="Before" style="width: 200%; height: 100%; max-width: none; object-fit: cover;">
                            </div>
                            
                            <!-- Slider -->
                            <div style="position: absolute; top: 0; left: ${baPosition}%; width: 4px; height: 100%; background: #92003b; transform: translateX(-50%); z-index: 2;">
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 40px; height: 40px; background: #92003b; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                                    <i class="fa fa-arrows-left-right"></i>
                                </div>
                            </div>
                            
                            <!-- Labels -->
                            <div style="position: absolute; top: 20px; left: 20px; background: rgba(0,0,0,0.7); color: #fff; padding: 8px 15px; border-radius: 4px; font-size: 14px; font-weight: 600; z-index: 3;">
                                ${baBeforeLabel}
                            </div>
                            <div style="position: absolute; top: 20px; right: 20px; background: rgba(146,0,59,0.9); color: #fff; padding: 8px 15px; border-radius: 4px; font-size: 14px; font-weight: 600; z-index: 3;">
                                ${baAfterLabel}
                            </div>
                        </div>
                        <p style="text-align: center; margin: 15px 0 0; color: #666; font-size: 12px;">
                            <i class="fa fa-arrows-left-right"></i> Drag slider to compare before & after
                        </p>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/blockquote.js
  function renderBlockquote(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const quoteText = settings2.quote_text || "The only way to do great work is to love what you do.";
    const author = settings2.author || "Steve Jobs";
    const authorTitle = settings2.author_title || "Apple Co-founder";
    const quoteStyle = settings2.quote_style || "border";
    const accentColorQuote = settings2.accent_color || "#92003b";
    const quoteTextColor = settings2.quote_color || "#333333";
    const quoteFontSize = settings2.quote_size || 20;
    const quoteBgColor = settings2.background_color || "transparent";
    const showQuoteIcon = settings2.show_icon !== "no";
    const quotePadding = settings2.padding || {
      top: 20,
      right: 30,
      bottom: 20,
      left: 30
    };
    const quoteMargin = settings2.margin || {
      top: 20,
      right: 0,
      bottom: 20,
      left: 0
    };
    let blockquoteStyle = "";
    if (quoteStyle === "border") {
      blockquoteStyle = `border-left: 4px solid ${accentColorQuote}; padding-left: 30px; font-style: italic;`;
    } else if (quoteStyle === "box") {
      blockquoteStyle = `border: 2px solid ${accentColorQuote}; padding: 30px; background: ${quoteBgColor !== "transparent" ? quoteBgColor : "#f9f9f9"}; border-radius: 8px;`;
    } else {
      blockquoteStyle = `font-style: italic; padding: 20px 0;`;
    }
    let blockquoteHTML = `
                        <blockquote class="probuilder-blockquote" style="
                            ${blockquoteStyle}
                            margin: ${quoteMargin.top}px ${quoteMargin.right}px ${quoteMargin.bottom}px ${quoteMargin.left}px;
                            padding: ${quotePadding.top}px ${quotePadding.right}px ${quotePadding.bottom}px ${quotePadding.left}px;
                            background: ${quoteBgColor !== "transparent" ? quoteBgColor : quoteStyle === "box" ? "#f9f9f9" : "transparent"};
                        ">
                    `;
    if (showQuoteIcon) {
      blockquoteHTML += `
                            <div style="font-size: 48px; color: ${accentColorQuote}; opacity: 0.3; margin-bottom: 15px;">
                                <i class="fa fa-quote-left"></i>
                            </div>
                        `;
    }
    blockquoteHTML += `
                        <p style="
                            font-size: ${quoteFontSize}px;
                            line-height: 1.6;
                            margin: 0 0 20px 0;
                            color: ${quoteTextColor};
                            font-style: italic;
                        ">${quoteText}</p>
                    `;
    if (author) {
      blockquoteHTML += `
                            <footer style="font-style: normal;">
                                <cite style="font-weight: 600; color: ${accentColorQuote}; font-style: normal;">
                                    ${author}
                                </cite>
                        `;
      if (authorTitle) {
        blockquoteHTML += `<span style="color: #999; font-size: 14px;"> \u2014 ${authorTitle}</span>`;
      }
      blockquoteHTML += "</footer>";
    }
    blockquoteHTML += "</blockquote>";
    return blockquoteHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/blog-posts.js
  function renderBlogPosts(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const blogLayout = settings2.post_layout || "grid";
    const blogColumns = parseInt(settings2.columns || 3);
    const blogCardBg = settings2.card_bg_color || "#ffffff";
    const blogBorderRadius = parseInt(settings2.card_border_radius || 8);
    const blogBoxShadow = settings2.card_box_shadow !== "no";
    const blogTitleColor = settings2.title_color || "#1e293b";
    const blogExcerptColor = settings2.excerpt_color || "#64748b";
    const blogMetaColor = settings2.meta_color || "#94a3b8";
    const blogReadMoreBg = settings2.read_more_bg_color || "#92003b";
    const blogReadMoreText = settings2.read_more_text_color || "#ffffff";
    const blogShowImage = settings2.show_image !== "no";
    const blogShowTitle = settings2.show_title !== "no";
    const blogShowExcerpt = settings2.show_excerpt !== "no";
    const blogShowMeta = settings2.show_meta !== "no";
    const blogShowReadMore = settings2.show_read_more !== "no";
    const blogReadMoreLabel = settings2.read_more_text || "Read More";
    const blogPerPage = parseInt(settings2.posts_per_page || 6);
    const blogCategory = settings2.category_filter || 0;
    const blogContainerId = "blog-posts-" + element2.id;
    let blogHTML = `<div id="${blogContainerId}" style="min-height: 100px;">
                        <div style="text-align: center; padding: 30px; color: #92003b;">
                            <div style="display: inline-block; width: 30px; height: 30px; border: 3px solid #f3f4f6; border-top-color: #92003b; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                            <p style="margin-top: 10px; font-size: 13px; font-weight: 600;">Loading posts...</p>
                        </div>
                    </div>`;
    setTimeout(() => {
      $.ajax({
        url: ProBuilderEditor.ajaxurl,
        type: "POST",
        data: {
          action: "probuilder_get_blog_posts",
          nonce: ProBuilderEditor.nonce,
          per_page: blogPerPage,
          category: blogCategory
        },
        success: function(response) {
          if (response.success && response.data.posts && response.data.posts.length > 0) {
            const posts = response.data.posts;
            const boxShadow = blogBoxShadow ? "box-shadow: 0 4px 20px rgba(0,0,0,0.1);" : "";
            let postsHTML = `<div style="display: grid; grid-template-columns: repeat(${blogColumns}, 1fr); gap: 30px;">`;
            posts.forEach((post) => {
              postsHTML += `<article class="probuilder-blog-post" style="background-color: ${blogCardBg}; border-radius: ${blogBorderRadius}px; overflow: hidden; ${boxShadow}">`;
              if (blogShowImage && post.image) {
                postsHTML += `<div style="position: relative; height: 200px; background-image: url(${post.image}); background-size: cover; background-position: center;">
                                                <a href="${post.permalink}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: block;"></a>
                                            </div>`;
              }
              postsHTML += `<div style="padding: 25px;">`;
              if (blogShowMeta) {
                postsHTML += `<div style="margin-bottom: 15px; font-size: 14px; color: ${blogMetaColor};">
                                                <span>${post.date}</span> \u2022 <span>${post.author}</span>
                                            </div>`;
              }
              if (blogShowTitle) {
                postsHTML += `<h3 style="margin: 0 0 15px 0; font-size: 20px; line-height: 1.4;">
                                                <a href="${post.permalink}" style="color: ${blogTitleColor}; text-decoration: none;">${post.title}</a>
                                            </h3>`;
              }
              if (blogShowExcerpt) {
                postsHTML += `<div style="color: ${blogExcerptColor}; line-height: 1.6; margin-bottom: 20px;">${post.excerpt}</div>`;
              }
              if (blogShowReadMore) {
                postsHTML += `<a href="${post.permalink}" style="display: inline-block; background-color: ${blogReadMoreBg}; color: ${blogReadMoreText}; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: 600; font-size: 14px;">${blogReadMoreLabel}</a>`;
              }
              postsHTML += `</div></article>`;
            });
            postsHTML += `</div>`;
            $("#" + blogContainerId).html(postsHTML);
            console.log("\u2705 Loaded", posts.length, "real blog posts:", posts.map((p) => p.title).join(", "));
          } else {
            $("#" + blogContainerId).html(`<div style="padding: 40px; text-align: center; background: #fffbeb; border: 2px dashed #fbbf24; border-radius: 8px; color: #78350f;">
                                        <i class="dashicons dashicons-admin-post" style="font-size: 48px; opacity: 0.3;"></i>
                                        <p style="margin: 10px 0 0; font-weight: 600;">No blog posts found</p>
                                        <p style="margin: 5px 0 0; font-size: 13px;">Create some blog posts in WordPress</p>
                                    </div>`);
          }
        },
        error: function() {
          console.error("Error loading blog posts");
          $("#" + blogContainerId).html(`<div style="padding: 40px; text-align: center; background: #fee2e2; border: 2px solid #ef4444; border-radius: 8px; color: #991b1b;">
                                    <i class="dashicons dashicons-warning" style="font-size: 48px;"></i>
                                    <p style="margin: 10px 0 0; font-weight: 600;">Error loading posts</p>
                                </div>`);
        }
      });
    }, 50);
    return blogHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/breadcrumbs.js
  function renderBreadcrumbs(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    return `<nav style="font-size:14px;color:#666">
                        <a href="#" style="color:#0073aa;text-decoration:none">Home</a>
                        <span style="color:#999;margin:0 8px">${settings2.separator || "/"}</span>
                        <a href="#" style="color:#0073aa;text-decoration:none">Category</a>
                        <span style="color:#999;margin:0 8px">${settings2.separator || "/"}</span>
                        <span style="color:#666">Current Page</span>
                    </nav>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/button.js
  function renderButton(context2) {
    const { settings: settings2, app: app2 } = context2;
    const btnType = settings2.button_type || "solid";
    const btnSizePreset = settings2.size_preset || "medium";
    const btnWidthType = settings2.width_type || "auto";
    const btnCustomWidth = settings2.custom_width || 200;
    const btnAlign = settings2.align || "left";
    const btnBgColor = settings2.bg_color || app2.getGlobalColor("primary") || "#0073aa";
    const btnTextColor = settings2.text_color || "#ffffff";
    const btnGradientColor = settings2.gradient_color || "#005a87";
    const btnGradientAngle = settings2.gradient_angle || 135;
    let btnFontSize = settings2.font_size || 16;
    const btnFontWeight = settings2.font_weight || "500";
    const btnTextTransform = settings2.text_transform || "none";
    const btnLetterSpacing = settings2.letter_spacing || 0;
    const btnBorderWidth = settings2.border_width || 0;
    const btnBorderColor = settings2.border_color || "#0073aa";
    const btnBorderRadius = settings2.border_radius || 3;
    const btnShadow = settings2.box_shadow || { x: 0, y: 2, blur: 8, color: "rgba(0,0,0,0.1)" };
    const btnIcon = settings2.icon || "";
    const btnIconPosition = settings2.icon_position || "left";
    const btnIconSpacing = settings2.icon_spacing || 8;
    let btnPadding = settings2.padding || { top: 12, right: 24, bottom: 12, left: 24 };
    const btnMargin = settings2.margin || { top: 0, right: 0, bottom: 0, left: 0 };
    if (btnSizePreset !== "custom") {
      const presets = {
        small: { font: 14, padding: { top: 8, right: 16, bottom: 8, left: 16 } },
        medium: { font: 16, padding: { top: 12, right: 24, bottom: 12, left: 24 } },
        large: { font: 18, padding: { top: 16, right: 32, bottom: 16, left: 32 } },
        xl: { font: 22, padding: { top: 20, right: 40, bottom: 20, left: 40 } }
      };
      if (presets[btnSizePreset]) {
        btnFontSize = presets[btnSizePreset].font;
        btnPadding = presets[btnSizePreset].padding;
      }
    }
    let btnBackground = "";
    if (btnType === "gradient") {
      btnBackground = `background: linear-gradient(${btnGradientAngle}deg, ${btnBgColor}, ${btnGradientColor});`;
    } else if (btnType === "outline" || btnType === "ghost") {
      btnBackground = "background: transparent;";
    } else {
      btnBackground = `background: ${btnBgColor};`;
    }
    const btnBorder = btnBorderWidth > 0 || btnType === "outline" ? `border: ${btnType === "outline" ? Math.max(2, btnBorderWidth) : btnBorderWidth}px solid ${btnBorderColor};` : "border: none;";
    const btnBoxShadow = btnShadow.blur > 0 ? `box-shadow: ${btnShadow.x}px ${btnShadow.y}px ${btnShadow.blur}px ${btnShadow.color};` : "";
    let btnWidth = "";
    if (btnWidthType === "full" || btnAlign === "justify") {
      btnWidth = "width: 100%; display: block; text-align: center;";
    } else if (btnWidthType === "custom") {
      btnWidth = `width: ${btnCustomWidth}px; display: inline-block; text-align: center;`;
    } else {
      btnWidth = "display: inline-block;";
    }
    const btnStyle = `
        ${btnBackground}
        color: ${btnTextColor};
        font-size: ${btnFontSize}px;
        font-weight: ${btnFontWeight};
        text-transform: ${btnTextTransform};
        letter-spacing: ${btnLetterSpacing}px;
        padding: ${btnPadding.top}px ${btnPadding.right}px ${btnPadding.bottom}px ${btnPadding.left}px;
        margin: ${btnMargin.top}px ${btnMargin.right}px ${btnMargin.bottom}px ${btnMargin.left}px;
        border-radius: ${btnBorderRadius}px;
        ${btnBoxShadow}
        ${btnWidth}
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        ${btnBorder}
    `;
    const btnIconHtml = btnIcon ? btnIconPosition === "left" ? `<i class="${btnIcon}" style="margin-right: ${btnIconSpacing}px;"></i> ` : ` <i class="${btnIcon}" style="margin-left: ${btnIconSpacing}px;"></i>` : "";
    const btnContent = btnIconPosition === "left" ? btnIconHtml + (settings2.text || "Click Here") : (settings2.text || "Click Here") + btnIconHtml;
    return `<div style="text-align: ${btnAlign === "justify" ? "center" : btnAlign};"><a href="#" class="probuilder-button" style="${btnStyle}">${btnContent}</a></div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/calendly.js
  function renderCalendly(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const calendlyUrl = settings2.url || "your-calendly-link";
    const calendlyType = settings2.type || "inline";
    const calendlyButtonText = settings2.button_text || "Schedule a Meeting";
    if (calendlyType === "popup") {
      return `<div style="text-align:center;padding:40px;background:#f5f8fa;border-radius:8px">
                            <div style="margin-bottom:20px">
                                <i class="fa fa-calendar-alt" style="font-size:48px;color:#006bff"></i>
                            </div>
                            <h3 style="margin:0 0 10px;font-size:22px;color:#333">Book a Time</h3>
                            <p style="margin:0 0 20px;color:#666;font-size:14px">Click button to open scheduling popup</p>
                            <button style="background:#006bff;color:#fff;border:none;padding:15px 40px;border-radius:25px;font-size:16px;font-weight:600;cursor:pointer;box-shadow:0 4px 12px rgba(0,107,255,0.3)">
                                <i class="fa fa-calendar-check" style="margin-right:8px"></i>
                                ${calendlyButtonText}
                            </button>
                            <p style="margin:15px 0 0;font-size:11px;color:#999">
                                <i class="fa fa-info-circle"></i> Calendly popup mode
                            </p>
                        </div>`;
    } else {
      const timeSlots = ["9:00 AM", "10:00 AM", "11:00 AM", "1:00 PM", "2:00 PM", "3:00 PM"];
      return `<div style="background:#fff;border:1px solid #e1e4e8;border-radius:8px;overflow:hidden;max-width:800px;margin:0 auto">
                            <div style="background:#006bff;color:#fff;padding:20px;text-align:center">
                                <i class="fa fa-calendar-alt" style="font-size:32px;margin-bottom:10px"></i>
                                <h3 style="margin:0 0 5px;font-size:22px">Schedule a Meeting</h3>
                                <p style="margin:0;font-size:14px;opacity:0.9">Select a convenient time</p>
                            </div>
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:0">
                                <div style="padding:20px;border-right:1px solid #e1e4e8">
                                    <h4 style="margin:0 0 15px;font-size:16px;color:#333">
                                        <i class="fa fa-calendar" style="margin-right:8px;color:#006bff"></i>
                                        Select Date
                                    </h4>
                                    <div style="background:#f5f8fa;padding:15px;border-radius:6px;text-align:center">
                                        <div style="font-size:12px;color:#666;font-weight:600;margin-bottom:10px">OCTOBER 2025</div>
                                        <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:5px;font-size:12px">
                                            ${[25, 26, 27, 28, 29, 30, 31].map((day) => `<div style="padding:8px;background:${day === 25 ? "#006bff" : "#fff"};color:${day === 25 ? "#fff" : "#333"};border-radius:4px;cursor:pointer;font-weight:${day === 25 ? "700" : "400"}">${day}</div>`).join("")}
                                        </div>
                                    </div>
                                </div>
                                <div style="padding:20px">
                                    <h4 style="margin:0 0 15px;font-size:16px;color:#333">
                                        <i class="fa fa-clock" style="margin-right:8px;color:#006bff"></i>
                                        Available Times
                                    </h4>
                                    <div style="display:flex;flex-direction:column;gap:8px">
                                        ${timeSlots.map((time, idx) => `
                                            <div style="padding:12px 15px;background:${idx === 1 ? "#e8f3ff" : "#f5f8fa"};border:1px solid ${idx === 1 ? "#006bff" : "transparent"};border-radius:6px;cursor:pointer;text-align:center;font-size:14px;font-weight:${idx === 1 ? "600" : "400"};color:#333">
                                                ${time}
                                            </div>
                                        `).join("")}
                                    </div>
                                </div>
                            </div>
                            <div style="padding:15px 20px;background:#f5f8fa;border-top:1px solid #e1e4e8;text-align:center;font-size:12px;color:#666">
                                <i class="fa fa-globe" style="margin-right:5px"></i>
                                Powered by Calendly \xB7 ${calendlyUrl}
                            </div>
                        </div>`;
    }
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/call-to-action.js
  function renderCallToAction(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const ctaTitle = settings2.title || "Ready to Get Started?";
    const ctaDescription = settings2.description || "Join thousands of satisfied customers today!";
    const ctaButtonText = settings2.button_text || "Get Started Now";
    const ctaBgColor = settings2.bg_color || "#92003b";
    const ctaTextColor = settings2.text_color || "#ffffff";
    const ctaTitleColor = settings2.title_color || ctaTextColor;
    const ctaTitleSize = settings2.title_size || "36px";
    const ctaDescColor = settings2.description_color || ctaTextColor;
    const ctaDescSize = settings2.description_size || "18px";
    const ctaBtnBg = settings2.button_bg_color || "#ffffff";
    const ctaBtnText = settings2.button_text_color || ctaBgColor;
    const ctaAlign = settings2.alignment || "center";
    const ctaMinHeight = settings2._min_height || "auto";
    const ctaPadding = settings2._padding || "60px 40px";
    const ctaBgType = settings2._background_type || "color";
    const ctaBgImage = settings2._background_image || "";
    const ctaBgOverlay = settings2._background_overlay || "rgba(0,0,0,0.3)";
    let ctaBgStyle = "";
    if (ctaBgType === "image" && ctaBgImage) {
      ctaBgStyle = `background-image: url('${ctaBgImage}'); background-size: cover; background-position: center;`;
    } else {
      ctaBgStyle = `background: ${ctaBgColor};`;
    }
    let ctaHTML = `<div style="${ctaBgStyle} color: ${ctaTextColor}; padding: ${ctaPadding}; text-align: ${ctaAlign}; border-radius: 8px; position: relative; overflow: hidden; min-height: ${ctaMinHeight}; display: flex; align-items: center; justify-content: ${ctaAlign};">`;
    if (ctaBgType === "image" && ctaBgImage) {
      ctaHTML += `<div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: ${ctaBgOverlay}; z-index: 0;"></div>`;
    }
    ctaHTML += `<div style="position: relative; z-index: 1; max-width: 600px;">`;
    ctaHTML += `<h2 style="margin: 0 0 15px 0; font-size: ${ctaTitleSize}; color: ${ctaTitleColor}; font-weight: 700; line-height: 1.2;">${ctaTitle}</h2>`;
    if (ctaDescription) {
      ctaHTML += `<p style="margin: 0 0 30px 0; font-size: ${ctaDescSize}; color: ${ctaDescColor}; opacity: 0.95; line-height: 1.6;">${ctaDescription}</p>`;
    }
    if (ctaButtonText) {
      ctaHTML += `<a href="#" style="background: ${ctaBtnBg}; color: ${ctaBtnText}; padding: 15px 40px; text-decoration: none; display: inline-block; border-radius: 6px; font-weight: 600; font-size: 16px; transition: all 0.3s; box-shadow: 0 4px 12px rgba(0,0,0,0.15);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(0,0,0,0.25)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)';">${ctaButtonText}</a>`;
    }
    ctaHTML += `</div>`;
    ctaHTML += `</div>`;
    return ctaHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/carousel.js
  function renderCarousel(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const carouselImages = Array.isArray(settings2.images) ? settings2.images : [{
      image_url: "https://via.placeholder.com/1200x600/92003b/ffffff?text=Slide+1",
      caption: "First Slide"
    }, {
      image_url: "https://via.placeholder.com/1200x600/667eea/ffffff?text=Slide+2",
      caption: "Second Slide"
    }, {
      image_url: "https://via.placeholder.com/1200x600/4facfe/ffffff?text=Slide+3",
      caption: "Third Slide"
    }];
    const carouselHeight = settings2.height || 400;
    const showArrows = settings2.show_arrows !== "no";
    const showDots = settings2.show_dots !== "no";
    const arrowsColor = settings2.arrows_color || "#ffffff";
    const dotsColor = settings2.dots_color || "#92003b";
    const autoplay = settings2.autoplay !== "no";
    const autoplaySpeed = settings2.autoplay_speed || 3e3;
    const carouselId = "carousel-" + element2.id;
    let carouselHTML = `<div class="probuilder-carousel-preview" data-carousel-id="${carouselId}" data-autoplay="${autoplay}" data-speed="${autoplaySpeed}" style="position: relative; overflow: hidden; height: ${carouselHeight}px; background: #f8f9fa; border-radius: 4px;">`;
    carouselHTML += '<div class="probuilder-carousel-slides" style="display: flex; height: 100%; transition: transform 0.5s ease; position: relative;">';
    carouselImages.forEach((img, index) => {
      carouselHTML += `
                            <div class="probuilder-carousel-slide" data-slide="${index}" style="
                                flex: 0 0 100%;
                                width: 100%;
                                height: 100%;
                                position: relative;
                                display: ${index === 0 ? "flex" : "none"};
                                align-items: center;
                                justify-content: center;
                                background: #000;
                            ">
                                <img src="${img.image_url || "https://via.placeholder.com/1200x600"}" style="
                                    max-width: 100%;
                                    max-height: 100%;
                                    object-fit: contain;
                                    display: block;
                                " alt="${img.caption || "Slide " + (index + 1)}">
                                ${img.caption ? `
                                    <div style="
                                        position: absolute;
                                        bottom: 20px;
                                        left: 50%;
                                        transform: translateX(-50%);
                                        background: rgba(0,0,0,0.7);
                                        color: #ffffff;
                                        padding: 10px 20px;
                                        border-radius: 4px;
                                        font-size: 16px;
                                    ">${img.caption}</div>
                                ` : ""}
                            </div>
                        `;
    });
    carouselHTML += "</div>";
    if (showArrows) {
      carouselHTML += `
                            <button class="probuilder-carousel-prev" style="
                                position: absolute;
                                top: 50%;
                                left: 15px;
                                transform: translateY(-50%);
                                background: rgba(0,0,0,0.6);
                                color: ${arrowsColor};
                                border: none;
                                width: 45px;
                                height: 45px;
                                border-radius: 50%;
                                cursor: pointer;
                                font-size: 24px;
                                z-index: 10;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: all 0.3s;
                            " onmouseover="this.style.background='rgba(0,0,0,0.8)'" onmouseout="this.style.background='rgba(0,0,0,0.6)'">
                                \u2039
                            </button>
                            <button class="probuilder-carousel-next" style="
                                position: absolute;
                                top: 50%;
                                right: 15px;
                                transform: translateY(-50%);
                                background: rgba(0,0,0,0.6);
                                color: ${arrowsColor};
                                border: none;
                                width: 45px;
                                height: 45px;
                                border-radius: 50%;
                                cursor: pointer;
                                font-size: 24px;
                                z-index: 10;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: all 0.3s;
                            " onmouseover="this.style.background='rgba(0,0,0,0.8)'" onmouseout="this.style.background='rgba(0,0,0,0.6)'">
                                \u203A
                            </button>
                        `;
    }
    if (showDots) {
      carouselHTML += '<div class="probuilder-carousel-dots" style="position: absolute; bottom: 15px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px; z-index: 10;">';
      carouselImages.forEach((img, index) => {
        carouselHTML += `
                                <button class="probuilder-carousel-dot ${index === 0 ? "active" : ""}" data-slide="${index}" style="
                                    width: ${index === 0 ? "24px" : "12px"};
                                    height: 12px;
                                    border-radius: 6px;
                                    border: 2px solid ${dotsColor};
                                    background: ${index === 0 ? dotsColor : "transparent"};
                                    cursor: pointer;
                                    transition: all 0.3s;
                                    padding: 0;
                                "></button>
                            `;
      });
      carouselHTML += "</div>";
    }
    carouselHTML += "</div>";
    const self2 = app2;
    setTimeout(function() {
      self2.initializeCarousel(element2, null);
    }, 100);
    return carouselHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/category-list.js
  function renderCategoryList(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const showCount = settings2.show_count !== false;
    return `<div style="background:#f9f9f9;padding:20px;border-radius:8px">
                        <h4 style="margin:0 0 15px;font-size:18px;color:#333">Categories</h4>
                        <ul style="list-style:none;padding:0;margin:0">
                            <li style="padding:8px 0;border-bottom:1px solid #eee"><a href="#" style="color:#0073aa;text-decoration:none">Design</a>${showCount ? ' <span style="color:#999">(12)</span>' : ""}</li>
                            <li style="padding:8px 0;border-bottom:1px solid #eee"><a href="#" style="color:#0073aa;text-decoration:none">Development</a>${showCount ? ' <span style="color:#999">(8)</span>' : ""}</li>
                            <li style="padding:8px 0;border-bottom:1px solid #eee"><a href="#" style="color:#0073aa;text-decoration:none">Marketing</a>${showCount ? ' <span style="color:#999">(5)</span>' : ""}</li>
                            <li style="padding:8px 0;border-bottom:1px solid #eee"><a href="#" style="color:#0073aa;text-decoration:none">Business</a>${showCount ? ' <span style="color:#999">(15)</span>' : ""}</li>
                        </ul>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/code-highlight.js
  function renderCodeHighlight(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const codeLanguage = settings2.language || "javascript";
    const codeTheme = settings2.theme || "dark";
    const codeShowNumbers = settings2.show_line_numbers !== false;
    const codeSample = settings2.code || 'function hello() {\n  console.log("Hello World");\n}';
    const codeLines = codeSample.split("\\n");
    const codeBg = codeTheme === "dark" ? "#2d2d2d" : "#f5f5f5";
    const codeColor = codeTheme === "dark" ? "#f8f8f2" : "#333";
    return `<div style="background:${codeBg};color:${codeColor};padding:20px;border-radius:8px;font-family:monospace;font-size:14px;overflow-x:auto">
                        <div style="margin-bottom:8px;color:#999;font-size:12px;text-transform:uppercase">${codeLanguage}</div>
                        ${codeShowNumbers ? codeLines.map((line, i) => `<div><span style="color:#6c6c6c;margin-right:15px;user-select:none">${i + 1}</span>${line}</div>`).join("") : codeSample}
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/contact-form.js
  function renderContactForm(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const formTitle = settings2.form_title || "Get in Touch";
    const formButtonText = settings2.button_text || "Send Message";
    const formButtonColor = settings2.button_color || "#92003b";
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

  // wp-content/plugins/probuilder/assets/js/src/widgets/container.js
  function renderContainer(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const c2Columns = parseInt(settings2.columns) || 2;
    const c2Gap = settings2.gap || 20;
    const c2MinHeight = settings2.min_height || 30;
    const c2BgColor = settings2.background_color || "#f8f9fa";
    const c2BorderColor = settings2.border_color || "#ddd";
    const c2BorderWidth = settings2.border_width || 1;
    const c2BorderRadius = settings2.border_radius || 8;
    const c2EnableResize = settings2.enable_resize !== false;
    if (!element2.children) {
      element2.children = [];
    }
    console.log("\u{1F50D} Container rendering:", {
      widgetType: element2.widgetType,
      id: element2.id,
      childrenCount: element2.children.length,
      children: element2.children,
      columns: c2Columns
    });
    const childrenCount = element2.children.length;
    const minCells = Math.max(c2Columns, childrenCount || c2Columns);
    const c2TemplateData = {
      columns: `repeat(${c2Columns}, 1fr)`,
      rows: "auto",
      areas: []
    };
    const numRows = Math.ceil(minCells / c2Columns);
    let cellIndex = 0;
    for (let row = 1; row <= numRows; row++) {
      for (let col = 1; col <= c2Columns && cellIndex < minCells; col++) {
        c2TemplateData.areas.push(`${row} / ${col} / ${row + 1} / ${col + 1}`);
        cellIndex++;
      }
    }
    if (numRows > 1) {
      c2TemplateData.rows = `repeat(${numRows}, auto)`;
    }
    console.log("Container layout:", {
      columns: c2Columns,
      children: childrenCount,
      minCells,
      rows: numRows,
      areasCreated: c2TemplateData.areas.length
    });
    let c2ColumnsTemplate = c2TemplateData.columns;
    let c2RowsTemplate = c2TemplateData.rows;
    if (element2.settings.custom_template) {
      c2ColumnsTemplate = element2.settings.custom_template.columns || c2ColumnsTemplate;
      c2RowsTemplate = element2.settings.custom_template.rows || c2RowsTemplate;
      console.log("Container using custom template:", {
        columns: c2ColumnsTemplate,
        rows: c2RowsTemplate
      });
    }
    const c2Margin = settings2.margin || {
      top: "0",
      right: "0",
      bottom: "0",
      left: "0"
    };
    const c2Padding = settings2.padding || {
      top: "0",
      right: "0",
      bottom: "0",
      left: "0"
    };
    const c2WrapperStyle = `
                        margin: ${c2Margin.top}px ${c2Margin.right}px ${c2Margin.bottom}px ${c2Margin.left}px;
                        padding: ${c2Padding.top}px ${c2Padding.right}px ${c2Padding.bottom}px ${c2Padding.left}px;
                    `;
    const c2Id = "container-" + element2.id;
    let c2HTML = `
                        <style>
                            #${c2Id} {
                                display: grid;
                                grid-template-columns: ${c2ColumnsTemplate};
                                grid-template-rows: ${c2RowsTemplate};
                                gap: ${c2Gap}px;
                                width: 100%;
                                position: relative;
                            }
                            #${c2Id} .container-cell {
                                min-height: 0 !important;
                                background: ${c2BgColor};
                                border: ${c2BorderWidth}px solid ${c2BorderColor};
                                border-radius: ${c2BorderRadius}px;
                                padding: 10px;
                                position: relative;
                                overflow: visible !important;
                            }
                            #${c2Id} .container-cell.has-content {
                                padding: 0;
                                overflow: visible !important;
                            }
                            #${c2Id} .container-cell.empty-cell {
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: all 0.3s;
                            }
                            #${c2Id} .container-cell.empty-cell:hover {
                                background: rgba(0,124,186,0.05);
                                border-color: #007cba;
                                transform: translateY(-2px);
                                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                            }
                            /* Resize handles */
                            #${c2Id} .container-resize-handle {
                                position: absolute;
                                background: #007cba;
                                opacity: 0;
                                transition: opacity 0.2s;
                                z-index: 50;
                            }
                            #${c2Id} .container-cell:hover .container-resize-handle {
                                opacity: 0.6;
                            }
                            #${c2Id} .container-resize-handle:hover {
                                opacity: 1 !important;
                                background: #005a87;
                            }
                            #${c2Id} .container-resize-handle-top {
                                top: -${Math.floor(c2Gap / 2)}px;
                                left: 0;
                                width: 100%;
                                height: 4px;
                                cursor: row-resize;
                            }
                            #${c2Id} .container-resize-handle-left {
                                top: 0;
                                left: -${Math.floor(c2Gap / 2)}px;
                                width: 4px;
                                height: 100%;
                                cursor: col-resize;
                            }
                            #${c2Id} .container-resize-handle-right {
                                top: 0;
                                right: -${Math.floor(c2Gap / 2)}px;
                                width: 4px;
                                height: 100%;
                                cursor: col-resize;
                            }
                            #${c2Id} .container-resize-handle-bottom {
                                bottom: -${Math.floor(c2Gap / 2)}px;
                                left: 0;
                                width: 100%;
                                height: 4px;
                                cursor: row-resize;
                            }
                            #${c2Id} .container-resize-handle-corner {
                                bottom: -${Math.floor(c2Gap / 2)}px;
                                right: -${Math.floor(c2Gap / 2)}px;
                                width: 12px;
                                height: 12px;
                                cursor: nwse-resize;
                                border-radius: 2px;
                            }
                        </style>
                        <div id="${c2Id}" class="probuilder-container-widget" data-element-id="${element2.id}" style="${c2WrapperStyle}">
                    `;
    c2TemplateData.areas.forEach((area, index) => {
      const child = element2.children && element2.children[index];
      const hasContent = !!child;
      console.log(`Container cell ${index}:`, {
        hasContent,
        child: child ? child.widgetType : "none"
      });
      c2HTML += `
                            <div class="container-cell ${hasContent ? "has-content" : "empty-cell"} probuilder-drop-zone" 
                                 style="grid-area: ${area};" 
                                 data-cell-index="${index}"
                                 data-container-id="${element2.id}"
                                 data-original-area="${area}">
                        `;
      if (c2EnableResize) {
        c2HTML += `
                                <div class="container-resize-handle container-resize-handle-top" data-cell-index="${index}" data-direction="top"></div>
                                <div class="container-resize-handle container-resize-handle-left" data-cell-index="${index}" data-direction="left"></div>
                                <div class="container-resize-handle container-resize-handle-right" data-cell-index="${index}" data-direction="right"></div>
                                <div class="container-resize-handle container-resize-handle-bottom" data-cell-index="${index}" data-direction="bottom"></div>
                                <div class="container-resize-handle container-resize-handle-corner" data-cell-index="${index}" data-direction="both"></div>
                            `;
      }
      if (hasContent) {
        const childPreview = app2.generatePreview(child, depth2 + 1);
        c2HTML += `
                                <div class="probuilder-nested-element" 
                                     data-id="${child.id}" 
                                     data-widget="${child.widgetType}"
                                     data-cell-index="${index}"
                                     style="position: relative;">
                                    <div class="probuilder-nested-toolbar" style="
                                        position: absolute;
                                        top: 5px;
                                        right: 5px;
                                        z-index: 1000;
                                        display: none;
                                        gap: 4px;
                                        background: rgba(255,255,255,0.95);
                                        padding: 4px;
                                        border-radius: 3px;
                                        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                                    ">
                                        <button class="probuilder-nested-edit" title="Edit" style="
                                            background: #92003b;
                                            border: none;
                                            color: #ffffff;
                                            width: 24px;
                                            height: 24px;
                                            border-radius: 2px;
                                            cursor: pointer;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                        ">
                                            <i class="dashicons dashicons-edit" style="font-size: 12px;"></i>
                                        </button>
                                        <button class="probuilder-nested-delete" title="Delete" style="
                                            background: #dc2626;
                                            border: none;
                                            color: #ffffff;
                                            width: 24px;
                                            height: 24px;
                                            border-radius: 2px;
                                            cursor: pointer;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                        ">
                                            <i class="dashicons dashicons-trash" style="font-size: 12px;"></i>
                                        </button>
                                    </div>
                                    <div class="probuilder-nested-preview">
                                        ${childPreview}
                                    </div>
                                </div>
                            `;
      } else {
        c2HTML += `
                                <div class="container-cell-empty-content" style="pointer-events: auto; padding: 30px;">
                                    <i class="dashicons dashicons-welcome-add-page" style="font-size: 32px; opacity: 0.3; color: #999;"></i>
                                    <div style="font-size: 12px; margin-top: 8px; color: #999;">Column ${index + 1}</div>
                                    <div style="font-size: 11px; margin-top: 4px; color: #bbb;">Drop widgets here</div>
                                </div>
                            `;
      }
      c2HTML += `</div>`;
    });
    c2HTML += `</div>`;
    return c2HTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/countdown.js
  function renderCountdown(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const countdownTarget = settings2.target_date || "";
    const showDays = settings2.show_days !== "no";
    const showHours = settings2.show_hours !== "no";
    const showMinutes = settings2.show_minutes !== "no";
    const showSeconds = settings2.show_seconds !== "no";
    const showLabels = settings2.show_labels !== "no";
    const countdownLayout = settings2.layout || "boxes";
    const countdownAlign = settings2.align || "center";
    const digitSize = settings2.digit_size || 48;
    const labelSize = settings2.label_size || 14;
    const digitColor = settings2.digit_color || "#ffffff";
    const labelColor = settings2.label_color || "#ffffff";
    const boxBgColor = settings2.box_bg_color || "#92003b";
    const borderRadius = settings2.box_border_radius || 8;
    const showSeparator = settings2.separator_show === "yes";
    const separatorText = settings2.separator_text || ":";
    const justifyMap = {
      "left": "flex-start",
      "center": "center",
      "right": "flex-end"
    };
    let countdownHTML = `<div style="display: flex; justify-content: ${justifyMap[countdownAlign]}; align-items: center; gap: 15px; flex-wrap: wrap;">`;
    let boxStyle = "";
    if (countdownLayout === "boxes") {
      boxStyle = `background: ${boxBgColor}; padding: 20px 15px; text-align: center; min-width: 90px; border-radius: ${borderRadius}px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);`;
    } else if (countdownLayout === "circles") {
      const circleSize = Math.max(digitSize + 40, 100);
      boxStyle = `background: ${boxBgColor}; width: ${circleSize}px; height: ${circleSize}px; display: flex; flex-direction: column; align-items: center; justify-content: center; border-radius: 50%; box-shadow: 0 4px 15px rgba(0,0,0,0.1);`;
    } else {
      boxStyle = `text-align: center;`;
    }
    const digitStyle = `font-size: ${digitSize}px; font-weight: bold; color: ${digitColor}; line-height: 1;`;
    const labelStyle = `font-size: ${labelSize}px; color: ${labelColor}; margin-top: 8px; text-transform: uppercase; letter-spacing: 1px;`;
    const separatorStyle = `font-size: ${digitSize}px; font-weight: bold; color: ${digitColor};`;
    let firstItem = true;
    if (showDays) {
      if (!firstItem && showSeparator && countdownLayout === "inline") {
        countdownHTML += `<div style="${separatorStyle}">${separatorText}</div>`;
      }
      countdownHTML += `<div style="${boxStyle}">`;
      countdownHTML += `<div style="${digitStyle}">05</div>`;
      if (showLabels) countdownHTML += `<div style="${labelStyle}">DAYS</div>`;
      countdownHTML += `</div>`;
      firstItem = false;
    }
    if (showHours) {
      if (!firstItem && showSeparator && countdownLayout === "inline") {
        countdownHTML += `<div style="${separatorStyle}">${separatorText}</div>`;
      }
      countdownHTML += `<div style="${boxStyle}">`;
      countdownHTML += `<div style="${digitStyle}">12</div>`;
      if (showLabels) countdownHTML += `<div style="${labelStyle}">HOURS</div>`;
      countdownHTML += `</div>`;
      firstItem = false;
    }
    if (showMinutes) {
      if (!firstItem && showSeparator && countdownLayout === "inline") {
        countdownHTML += `<div style="${separatorStyle}">${separatorText}</div>`;
      }
      countdownHTML += `<div style="${boxStyle}">`;
      countdownHTML += `<div style="${digitStyle}">34</div>`;
      if (showLabels) countdownHTML += `<div style="${labelStyle}">MINUTES</div>`;
      countdownHTML += `</div>`;
      firstItem = false;
    }
    if (showSeconds) {
      if (!firstItem && showSeparator && countdownLayout === "inline") {
        countdownHTML += `<div style="${separatorStyle}">${separatorText}</div>`;
      }
      countdownHTML += `<div style="${boxStyle}">`;
      countdownHTML += `<div style="${digitStyle}">56</div>`;
      if (showLabels) countdownHTML += `<div style="${labelStyle}">SECONDS</div>`;
      countdownHTML += `</div>`;
    }
    countdownHTML += `</div>`;
    return countdownHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/counter.js
  function renderCounter(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const counterEnd = settings2.ending_number || 1e3;
    const counterPrefix = settings2.prefix || "";
    const counterSuffix = settings2.suffix || "+";
    const counterTitle = settings2.title || "Happy Clients";
    const counterNumberColor = settings2.number_color || "#0073aa";
    const counterTitleColor = settings2.title_color || "#333333";
    const counterAlign = settings2.text_align || "center";
    let counterHTML = `<div style="text-align: ${counterAlign}; padding: 20px;">`;
    counterHTML += `<div style="font-size: 48px; font-weight: bold; color: ${counterNumberColor}; margin-bottom: 10px;">`;
    counterHTML += `${counterPrefix}${counterEnd}${counterSuffix}`;
    counterHTML += `</div>`;
    counterHTML += `<div style="font-size: 18px; color: ${counterTitleColor};">${counterTitle}</div>`;
    counterHTML += `</div>`;
    return counterHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/custom-css.js
  function renderCustomCss(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const customCssCode = settings2.css_code || ".my-class { color: red; }";
    return `<div style="background:#2d2d2d;color:#f8f8f2;padding:20px;border-radius:8px;font-family:monospace;font-size:14px">
                        <div style="margin-bottom:10px;color:#6c92c7;font-weight:600">
                            <i class="fa fa-css3-alt" style="margin-right:8px;color:#2965f1"></i>
                            Custom CSS
                        </div>
                        <pre style="margin:0;color:#98c379;line-height:1.6">${customCssCode.substring(0, 200)}</pre>
                        <div style="margin-top:10px;padding:10px;background:rgba(255,255,255,0.1);border-radius:4px;font-size:12px;color:#abb2bf">
                            <i class="fa fa-info-circle" style="margin-right:5px"></i>
                            CSS will be applied to the page
                        </div>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/divider.js
  function renderDivider(context2) {
    const { settings: settings2 } = context2;
    const divHeight = settings2.height || 1;
    const divStyle = settings2.style || "solid";
    const divColor = settings2.color || "#ddd";
    const divWidth = settings2.width || 100;
    const divAlign = settings2.align || "center";
    const divGap = settings2.gap || 15;
    let divMargin = `${divGap}px auto`;
    if (divAlign === "left") divMargin = `${divGap}px auto ${divGap}px 0`;
    if (divAlign === "right") divMargin = `${divGap}px 0 ${divGap}px auto`;
    return `<div style="width: 100%; display: block; line-height: 0; margin: ${divMargin};"><hr style="border: none; border-top: ${divHeight}px ${divStyle} ${divColor}; width: ${divWidth}%; margin: 0; display: inline-block;"></div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/donut-chart.js
  function renderDonutChart(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const donutTitle = settings2.chart_title || "Market Share";
    const donutShowTitle = settings2.show_title !== "no";
    const donutHeight = settings2.chart_height || 400;
    const donutCenterText = settings2.center_text || "";
    const donutData = settings2.chart_data || "Product A, 35\nProduct B, 30\nProduct C, 20\nProduct D, 15";
    const donutColorScheme = settings2.colors_scheme || "vibrant";
    const donutCustomColors = settings2.custom_colors || "#FF6384, #36A2EB, #FFCE56, #4BC0C0, #9966FF";
    const donutShowLegend = settings2.show_legend !== "no";
    const donutLegendPos = settings2.legend_position || "bottom";
    const donutCutout = settings2.cutout_percentage || 50;
    const donutLines = donutData.split("\n").filter((line) => line.trim());
    const donutLabels = [];
    const donutValues = [];
    donutLines.forEach((line) => {
      const parts = line.split(",").map((s) => s.trim());
      if (parts.length >= 2) {
        donutLabels.push(parts[0]);
        donutValues.push(parseFloat(parts[1]) || 0);
      }
    });
    const donutColorSchemes = {
      "vibrant": ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0", "#9966FF", "#FF9F40"],
      "pastel": ["#FFB3BA", "#BAFFC9", "#BAE1FF", "#FFFFBA", "#FFD4BA", "#E0BBE4"],
      "monochrome": ["#1a1a1a", "#333333", "#4d4d4d", "#666666", "#808080", "#999999"]
    };
    const donutColors = donutColorScheme === "custom" ? donutCustomColors.split(",").map((c) => c.trim()) : donutColorSchemes[donutColorScheme] || donutColorSchemes["vibrant"];
    const donutTotal = donutValues.reduce((sum, val) => sum + val, 0);
    const donutStrokeWidth = 80 * (1 - donutCutout / 100);
    let donutCurrentAngle = -90;
    let donutSVGPaths = "";
    donutValues.forEach((value, i) => {
      const angle = value / donutTotal * 360;
      const endAngle = donutCurrentAngle + angle;
      const radius = 80 - donutStrokeWidth / 2;
      const startX = 100 + radius * Math.cos(donutCurrentAngle * Math.PI / 180);
      const startY = 100 + radius * Math.sin(donutCurrentAngle * Math.PI / 180);
      const endX = 100 + radius * Math.cos(endAngle * Math.PI / 180);
      const endY = 100 + radius * Math.sin(endAngle * Math.PI / 180);
      const largeArc = angle > 180 ? 1 : 0;
      const color = donutColors[i % donutColors.length];
      donutSVGPaths += `<path d="M ${startX} ${startY} A ${radius} ${radius} 0 ${largeArc} 1 ${endX} ${endY}" fill="none" stroke="${color}" stroke-width="${donutStrokeWidth}"/>`;
      donutCurrentAngle = endAngle;
    });
    let donutHTML = `<div style="padding: 20px; text-align: center;">`;
    if (donutShowTitle && donutTitle) {
      donutHTML += `<h3 style="margin-bottom: 20px; font-size: 24px; font-weight: 600;">${donutTitle}</h3>`;
    }
    const donutLegendHTML = donutShowLegend ? `
                        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 15px; margin: 15px 0;">
                            ${donutLabels.map((label, i) => `
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 12px; height: 12px; background: ${donutColors[i % donutColors.length]}; border-radius: 2px;"></div>
                                    <span style="font-size: 13px; color: #666;">${label}</span>
                                </div>
                            `).join("")}
                        </div>
                    ` : "";
    if (donutLegendPos === "top") donutHTML += donutLegendHTML;
    donutHTML += `<div style="position: relative; display: flex; justify-content: center; align-items: center; height: ${donutHeight}px; background: #f9f9f9; border-radius: 8px;">`;
    donutHTML += `<svg width="250" height="250" viewBox="0 0 200 200">${donutSVGPaths}</svg>`;
    if (donutCenterText) {
      donutHTML += `<div style="position: absolute; font-size: 24px; font-weight: 600;">${donutCenterText}</div>`;
    }
    donutHTML += `</div>`;
    if (donutLegendPos === "bottom") donutHTML += donutLegendHTML;
    donutHTML += `</div>`;
    return donutHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/facebook-embed.js
  function renderFacebookEmbed(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const facebookType = settings2.type || "post";
    const facebookUrl = settings2.url || "https://www.facebook.com/...";
    return `<div style="background:#f5f5f5;padding:30px;border-radius:8px;text-align:center">
                        <div style="background:#fff;border:1px solid #ddd;border-radius:8px;padding:20px;max-width:500px;margin:0 auto">
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:15px">
                                <i class="fa fa-facebook" style="font-size:32px;color:#1877f2"></i>
                                <div style="text-align:left">
                                    <strong style="color:#333;display:block">Facebook ${facebookType === "post" ? "Post" : facebookType === "page" ? "Page" : "Video"}</strong>
                                    <small style="color:#999">Embedded content</small>
                                </div>
                            </div>
                            <div style="background:#f0f2f5;height:200px;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#999">
                                <div>
                                    <i class="fa fa-facebook-f" style="font-size:48px;margin-bottom:10px;display:block"></i>
                                    Facebook ${facebookType.charAt(0).toUpperCase() + facebookType.slice(1)} Preview
                                </div>
                            </div>
                            <div style="margin-top:15px;padding:10px;background:#f0f2f5;border-radius:4px;font-size:12px;color:#666">
                                <i class="fa fa-link" style="margin-right:5px"></i>
                                Embed URL configured
                            </div>
                        </div>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/faq.js
  function renderFaq(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const fqFaqTitle = settings2.faq_title || "Frequently Asked Questions";
    const fqFaqDescription = settings2.faq_description || "Find answers to the most common questions about our products and services.";
    const fqFaqLayout = settings2.layout || "accordion";
    const fqAllowMultiple = settings2.allow_multiple !== "yes";
    const fqItemBgColor = settings2.item_bg_color || "#ffffff";
    const fqItemBorderColor = settings2.item_border_color || "#e1e5e9";
    const fqQuestionColor = settings2.question_color || "#1e293b";
    const fqAnswerColor = settings2.answer_color || "#64748b";
    const fqIconColor = settings2.icon_color || "#92003b";
    const fqActiveColor = settings2.active_color || "#92003b";
    const fqBorderRadius = settings2.border_radius || {
      size: 8
    };
    let fqFaqHTML = `<div style="max-width: 800px; margin: 0 auto;">`;
    if (fqFaqTitle) {
      fqFaqHTML += `<h2 style="color: #1e293b; font-size: 32px; font-weight: 700; margin: 0 0 15px 0; text-align: center;">${fqFaqTitle}</h2>`;
    }
    if (fqFaqDescription) {
      fqFaqHTML += `<p style="color: #64748b; font-size: 16px; text-align: center; margin: 0 0 40px 0;">${fqFaqDescription}</p>`;
    }
    fqFaqHTML += `<div style="display: flex; flex-direction: column; gap: 15px;">`;
    const sampleFAQs = [{
      question: "What is your return policy?",
      answer: "We offer a 30-day return policy for all products in original condition. Simply contact our customer service team to initiate a return.",
      icon: "fa fa-undo"
    }, {
      question: "How long does shipping take?",
      answer: "Standard shipping takes 3-5 business days. Express shipping is available for next-day delivery in most areas.",
      icon: "fa fa-shipping-fast"
    }, {
      question: "Do you offer customer support?",
      answer: "Yes! Our customer support team is available 24/7 via live chat, email, and phone to help with any questions or issues.",
      icon: "fa fa-headset"
    }];
    sampleFAQs.forEach((faq, index) => {
      const fqItemStyle = `
                            background-color: ${fqItemBgColor};
                            border: 1px solid ${fqItemBorderColor};
                            border-radius: ${fqBorderRadius.size}px;
                            overflow: hidden;
                        `;
      fqFaqHTML += `<div style="${fqItemStyle}">`;
      const fqHeaderStyle = `padding: 20px; cursor: pointer; display: flex; align-items: center; justify-content: space-between; ${index === 0 ? `background: ${fqActiveColor};` : ""}`;
      fqFaqHTML += `<div style="${fqHeaderStyle}">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <i class="${faq.icon}" style="color: ${fqIconColor}; font-size: 18px; width: 20px; text-align: center;"></i>
                                <h3 style="margin: 0; color: ${fqQuestionColor}; font-size: 18px; font-weight: 600;">${faq.question}</h3>
                            </div>
                            <i class="fa fa-chevron-down" style="color: ${fqIconColor}; font-size: 14px; transform: ${index === 0 ? "rotate(180deg)" : "rotate(0deg)"};"></i>
                        </div>`;
      if (index === 0) {
        fqFaqHTML += `<div style="padding: 0 20px 20px 20px; color: ${fqAnswerColor}; line-height: 1.6;">
                                <p style="margin: 0;">${faq.answer}</p>
                            </div>`;
      }
      fqFaqHTML += `</div>`;
    });
    fqFaqHTML += `</div></div>`;
    return fqFaqHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/feature-list.js
  function renderFeatureList(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const featureListItems = settings2.items || [{
      title: "24/7 Support",
      description: "Get help whenever you need it",
      icon: "fa fa-headset"
    }, {
      title: "Free Updates",
      description: "Always get the latest features",
      icon: "fa fa-rocket"
    }, {
      title: "Money Back",
      description: "30-day refund policy",
      icon: "fa fa-shield-halved"
    }];
    const featureListLayout = settings2.layout || "grid";
    const featureListColumns = settings2.columns || "3";
    const featureListIconColor = settings2.icon_color || "#92003b";
    const featureListIconSize = settings2.icon_size || 40;
    const featureListIconBg = settings2.icon_bg_color || "#f8f9fa";
    const featureListTitleColor = settings2.title_color || "#333333";
    const featureListDescColor = settings2.description_color || "#666666";
    const featureListShowCard = settings2.show_card !== "no";
    const featureListCardBg = settings2.card_bg_color || "#ffffff";
    const featureListMargin = settings2.margin || {
      top: 20,
      right: 0,
      bottom: 20,
      left: 0
    };
    const featureListPadding = settings2.padding || {
      top: 25,
      right: 25,
      bottom: 25,
      left: 25
    };
    let featureListContainerStyle = `margin: ${featureListMargin.top}px ${featureListMargin.right}px ${featureListMargin.bottom}px ${featureListMargin.left}px; `;
    if (featureListLayout === "grid") {
      featureListContainerStyle += `display: grid; grid-template-columns: repeat(${featureListColumns}, 1fr); gap: 20px;`;
    } else {
      featureListContainerStyle += "display: flex; flex-direction: column; gap: 20px;";
    }
    let featureListHTML = `<div class="probuilder-feature-list-preview" style="${featureListContainerStyle}">`;
    featureListItems.forEach((item) => {
      featureListHTML += `
                            <div class="feature-item" style="
                                ${featureListShowCard ? `background: ${featureListCardBg}; border-radius: 8px; padding: ${featureListPadding.top}px ${featureListPadding.right}px ${featureListPadding.bottom}px ${featureListPadding.left}px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);` : ""}
                                display: flex; flex-direction: column; align-items: flex-start;
                            ">
                                <div style="
                                    display: flex; align-items: center; justify-content: center;
                                    width: ${featureListIconSize + 30}px; height: ${featureListIconSize + 30}px;
                                    background: ${featureListIconBg}; border-radius: 50%;
                                    color: ${featureListIconColor}; font-size: ${featureListIconSize}px;
                                    margin-bottom: 15px;
                                ">
                                    <i class="${item.icon}"></i>
                                </div>
                                <h4 style="margin: 0 0 8px 0; font-size: 18px; color: ${featureListTitleColor}; font-weight: 600;">
                                    ${item.title}
                                </h4>
                                ${item.description ? `<p style="margin: 0; font-size: 14px; color: ${featureListDescColor}; line-height: 1.6;">
                                    ${item.description}
                                </p>` : ""}
                            </div>
                        `;
    });
    featureListHTML += "</div>";
    return featureListHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/flexbox.js
  function renderFlexbox(context2) {
    var _a;
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const flexDirection = settings2.direction || "row";
    const flexJustify = settings2.justify_content || "flex-start";
    const flexAlign = settings2.align_items || "stretch";
    const flexWrap = settings2.wrap || "wrap";
    const flexGap = settings2.gap || 20;
    const flexMinHeight = settings2.min_height || 100;
    const flexPadding = settings2.padding || {
      top: 20,
      right: 20,
      bottom: 20,
      left: 20
    };
    const flexMargin = settings2.margin || {
      top: 0,
      right: 0,
      bottom: 20,
      left: 0
    };
    const flexBgType = settings2.background_type || "color";
    const flexBgColor = settings2.background_color || "#f8f9fa";
    const flexBgGradient = settings2.background_gradient || "linear-gradient(135deg, #667eea 0%, #764ba2 100%)";
    const flexBgImage = ((_a = settings2.background_image) == null ? void 0 : _a.url) || "";
    const flexBorder = settings2.border || {
      width: 0,
      style: "solid",
      color: "#000000"
    };
    const flexBorderRadius = settings2.border_radius || 0;
    const flexBoxShadow = settings2.box_shadow === "yes";
    let flexBg = "";
    if (flexBgType === "color") {
      flexBg = `background-color: ${flexBgColor};`;
    } else if (flexBgType === "gradient") {
      flexBg = `background: ${flexBgGradient};`;
    } else if (flexBgType === "image" && flexBgImage) {
      flexBg = `background-image: url(${flexBgImage}); background-size: cover; background-position: center;`;
    }
    const flexboxStyle = `
                        display: flex;
                        flex-direction: ${flexDirection};
                        justify-content: ${flexJustify};
                        align-items: ${flexAlign};
                        flex-wrap: ${flexWrap};
                        gap: ${flexGap}px;
                        min-height: ${flexMinHeight}px;
                        padding: ${flexPadding.top}px ${flexPadding.right}px ${flexPadding.bottom}px ${flexPadding.left}px;
                        margin: ${flexMargin.top}px ${flexMargin.right}px ${flexMargin.bottom}px ${flexMargin.left}px;
                        ${flexBg}
                        ${flexBorder.width > 0 ? `border: ${flexBorder.width}px ${flexBorder.style} ${flexBorder.color};` : ""}
                        ${flexBorderRadius > 0 ? `border-radius: ${flexBorderRadius}px;` : ""}
                        ${flexBoxShadow ? "box-shadow: 0 4px 20px rgba(0,0,0,0.1);" : ""}
                    `;
    let flexboxHTML = `<div style="${flexboxStyle}">`;
    flexboxHTML += `
                        <div style="padding: 30px; background: rgba(255,255,255,0.9); border: 2px dashed #cbd5e1; border-radius: 8px; text-align: center; color: #64748b; flex: 1;">
                            <i class="dashicons dashicons-plus" style="font-size: 48px; opacity: 0.4; margin-bottom: 10px;"></i>
                            <div style="font-size: 16px; font-weight: 600;">Flexbox Container</div>
                            <div style="font-size: 13px; margin-top: 5px; opacity: 0.7;">Add widgets inside this flexible layout</div>
                            <div style="font-size: 12px; margin-top: 10px; opacity: 0.6;">
                                Direction: ${flexDirection} | Justify: ${flexJustify.replace("flex-", "").replace("-", " ")}
                            </div>
                        </div>
                    `;
    flexboxHTML += `</div>`;
    return flexboxHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/flip-box.js
  function renderFlipBox(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const flipFrontIconType = settings2.front_icon_type || "icon";
    const flipFrontIcon = settings2.front_icon || "fa fa-star";
    const flipFrontImage = settings2.front_image || "";
    const flipFrontTitle = settings2.front_title || "Amazing Feature";
    const flipFrontDesc = settings2.front_description || "Hover to see more";
    const flipBackIconType = settings2.back_icon_type || "none";
    const flipBackIcon = settings2.back_icon || "fa fa-check-circle";
    const flipBackImage = settings2.back_image || "";
    const flipBackTitle = settings2.back_title || "Discover More";
    const flipBackDesc = settings2.back_description || "This is an amazing feature";
    const flipShowButton = settings2.show_button !== "no";
    const flipButtonText = settings2.button_text || "Learn More";
    const flipEffect = settings2.flip_effect || "flip-horizontal";
    const flipHeight = settings2.box_height || 300;
    const flipFrontBgType = settings2.front_bg_type || "color";
    const flipFrontBgColor = settings2.front_bg_color || "#92003b";
    const flipFrontBgGrad = settings2.front_bg_gradient || "";
    const flipFrontTextColor = settings2.front_text_color || "#ffffff";
    const flipFrontIconColor = settings2.front_icon_color || "#ffffff";
    const flipFrontIconSize = settings2.front_icon_size || 60;
    const flipBackBgType = settings2.back_bg_type || "color";
    const flipBackBgColor = settings2.back_bg_color || "#333333";
    const flipBackBgGrad = settings2.back_bg_gradient || "";
    const flipBackTextColor = settings2.back_text_color || "#ffffff";
    const flipButtonBg = settings2.back_button_bg || "#ffffff";
    const flipButtonColor = settings2.back_button_color || "#333333";
    const flipBorderRadius = settings2.border_radius || 8;
    const flipMargin = settings2.margin || {
      top: 20,
      right: 0,
      bottom: 20,
      left: 0
    };
    const flipPadding = settings2.padding || {
      top: 30,
      right: 30,
      bottom: 30,
      left: 30
    };
    let flipFrontBg = "";
    if (flipFrontBgType === "gradient" && flipFrontBgGrad) {
      flipFrontBg = `background: ${flipFrontBgGrad};`;
    } else {
      flipFrontBg = `background: ${flipFrontBgColor};`;
    }
    let flipBackBg = "";
    if (flipBackBgType === "gradient" && flipBackBgGrad) {
      flipBackBg = `background: ${flipBackBgGrad};`;
    } else {
      flipBackBg = `background: ${flipBackBgColor};`;
    }
    const flipBoxId = "flipbox-" + element2.id;
    let flipBoxHTML = `
                        <div class="probuilder-flip-box-preview" id="${flipBoxId}" data-effect="${flipEffect}" style="
                            perspective: 1000px;
                            height: ${flipHeight}px;
                            margin: ${flipMargin.top}px ${flipMargin.right}px ${flipMargin.bottom}px ${flipMargin.left}px;
                            cursor: pointer;
                        ">
                            <div class="flip-box-inner" style="
                                position: relative;
                                width: 100%;
                                height: 100%;
                                transition: transform 0.6s;
                                transform-style: preserve-3d;
                            ">
                                <div class="flip-box-front" style="
                                    position: absolute;
                                    width: 100%;
                                    height: 100%;
                                    backface-visibility: hidden;
                                    ${flipFrontBg}
                                    color: ${flipFrontTextColor};
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    justify-content: center;
                                    border-radius: ${flipBorderRadius}px;
                                    padding: ${flipPadding.top}px ${flipPadding.right}px ${flipPadding.bottom}px ${flipPadding.left}px;
                                    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                                ">
                                    ${flipFrontIconType === "icon" && flipFrontIcon ? `
                                        <i class="${flipFrontIcon}" style="font-size: ${flipFrontIconSize}px; color: ${flipFrontIconColor}; margin-bottom: 20px;"></i>
                                    ` : ""}
                                    ${flipFrontIconType === "image" && flipFrontImage ? `
                                        <img src="${flipFrontImage}" alt="${flipFrontTitle}" style="width: ${flipFrontIconSize}px; height: ${flipFrontIconSize}px; margin-bottom: 20px; border-radius: 50%;">
                                    ` : ""}
                                    <h3 style="margin: 0 0 10px 0; font-size: 24px; font-weight: 600; color: ${flipFrontTextColor};">
                                        ${flipFrontTitle}
                                    </h3>
                                    ${flipFrontDesc ? `<p style="margin: 0; font-size: 14px; opacity: 0.9; color: ${flipFrontTextColor};">
                                        ${flipFrontDesc}
                                    </p>` : ""}
                                </div>
                                
                                <div class="flip-box-back" style="
                                    position: absolute;
                                    width: 100%;
                                    height: 100%;
                                    backface-visibility: hidden;
                                    ${flipBackBg}
                                    color: ${flipBackTextColor};
                                    display: flex;
                                    flex-direction: column;
                                    align-items: center;
                                    justify-content: center;
                                    border-radius: ${flipBorderRadius}px;
                                    padding: ${flipPadding.top}px ${flipPadding.right}px ${flipPadding.bottom}px ${flipPadding.left}px;
                                    transform: rotateY(180deg);
                                    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                                ">
                                    ${flipBackIconType === "icon" && flipBackIcon ? `
                                        <i class="${flipBackIcon}" style="font-size: 40px; margin-bottom: 15px; color: ${flipBackTextColor};"></i>
                                    ` : ""}
                                    ${flipBackIconType === "image" && flipBackImage ? `
                                        <img src="${flipBackImage}" alt="${flipBackTitle}" style="width: 60px; height: 60px; margin-bottom: 15px; border-radius: 50%;">
                                    ` : ""}
                                    <h3 style="margin: 0 0 15px 0; font-size: 22px; font-weight: 600; color: ${flipBackTextColor};">
                                        ${flipBackTitle}
                                    </h3>
                                    ${flipBackDesc ? `<p style="margin: 0 0 20px 0; font-size: 14px; line-height: 1.6; text-align: center; color: ${flipBackTextColor};">
                                        ${flipBackDesc}
                                    </p>` : ""}
                                    ${flipShowButton ? `<a href="#" style="background: ${flipButtonBg}; color: ${flipButtonColor}; padding: 10px 25px; text-decoration: none; border-radius: 4px; font-weight: 600;">
                                        ${flipButtonText}
                                    </a>` : ""}
                                </div>
                            </div>
                        </div>
                    `;
    setTimeout(function() {
      const $flipBox = jQuery("#" + flipBoxId);
      $flipBox.on("mouseenter", function() {
        const effect = jQuery(app2).data("effect");
        const $inner = jQuery(app2).find(".flip-box-inner");
        switch (effect) {
          case "flip-horizontal":
            $inner.css("transform", "rotateY(180deg)");
            break;
          case "flip-vertical":
            $inner.css("transform", "rotateX(180deg)");
            break;
          case "zoom-in":
            $inner.find(".flip-box-front").css({
              "opacity": "0",
              "transform": "scale(1.2)"
            });
            $inner.find(".flip-box-back").css({
              "opacity": "1",
              "transform": "scale(1) rotateY(0deg)"
            });
            break;
          case "zoom-out":
            $inner.find(".flip-box-front").css({
              "opacity": "0",
              "transform": "scale(0.8)"
            });
            $inner.find(".flip-box-back").css({
              "opacity": "1",
              "transform": "scale(1) rotateY(0deg)"
            });
            break;
          case "fade":
            $inner.find(".flip-box-front").css("opacity", "0");
            $inner.find(".flip-box-back").css({
              "opacity": "1",
              "transform": "rotateY(0deg)"
            });
            break;
        }
      }).on("mouseleave", function() {
        const $inner = jQuery(app2).find(".flip-box-inner");
        $inner.css("transform", "");
        $inner.find(".flip-box-front").css({
          "opacity": "1",
          "transform": ""
        });
        $inner.find(".flip-box-back").css({
          "opacity": "0",
          "transform": "rotateY(180deg)"
        });
      });
    }, 100);
    return flipBoxHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/form-builder.js
  function renderFormBuilder(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const fbFormTitle = settings2.form_title || "Contact Us";
    const fbFormDescription = settings2.form_description || "Send us a message and we'll get back to you as soon as possible.";
    const fbFormButtonText = settings2.submit_button_text || "Send Message";
    const fbFormBgColor = settings2.form_bg_color || "#ffffff";
    const fbFormPadding = settings2.form_padding || {
      top: 30,
      right: 30,
      bottom: 30,
      left: 30
    };
    const fbFormBorderRadius = settings2.form_border_radius || {
      size: 8
    };
    const fbFormBoxShadow = settings2.form_box_shadow !== "no";
    const fbFieldBgColor = settings2.field_bg_color || "#ffffff";
    const fbFieldBorderColor = settings2.field_border_color || "#e1e5e9";
    const fbFieldFocusColor = settings2.field_focus_color || "#92003b";
    const fbButtonBgColor = settings2.button_bg_color || "#92003b";
    const fbButtonTextColor = settings2.button_text_color || "#ffffff";
    const fbFormStyle = `
                        background-color: ${fbFormBgColor};
                        padding: ${fbFormPadding.top}px ${fbFormPadding.right}px ${fbFormPadding.bottom}px ${fbFormPadding.left}px;
                        border-radius: ${fbFormBorderRadius.size}px;
                        ${fbFormBoxShadow ? "box-shadow: 0 4px 20px rgba(0,0,0,0.1);" : ""}
                    `;
    let fbFormHTML = `<div style="${fbFormStyle}">`;
    if (fbFormTitle) {
      fbFormHTML += `<h3 style="margin-top: 0; margin-bottom: 15px; color: #1e293b; font-size: 24px;">${fbFormTitle}</h3>`;
    }
    if (fbFormDescription) {
      fbFormHTML += `<p style="margin-bottom: 25px; color: #64748b; font-size: 14px;">${fbFormDescription}</p>`;
    }
    fbFormHTML += `<form style="display: flex; flex-direction: column; gap: 20px;">`;
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

  // wp-content/plugins/probuilder/assets/js/src/widgets/gallery.js
  function renderGallery(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const galleryImages = settings2.images || [{
      image_url: "https://via.placeholder.com/600x400/FF6B6B/ffffff?text=1",
      caption: "Beautiful Image 1"
    }, {
      image_url: "https://via.placeholder.com/600x400/4ECDC4/ffffff?text=2",
      caption: "Beautiful Image 2"
    }, {
      image_url: "https://via.placeholder.com/600x400/45B7D1/ffffff?text=3",
      caption: "Beautiful Image 3"
    }, {
      image_url: "https://via.placeholder.com/600x400/96CEB4/ffffff?text=4",
      caption: "Beautiful Image 4"
    }, {
      image_url: "https://via.placeholder.com/600x400/FFEAA7/ffffff?text=5",
      caption: "Beautiful Image 5"
    }, {
      image_url: "https://via.placeholder.com/600x400/6C5CE7/ffffff?text=6",
      caption: "Beautiful Image 6"
    }];
    const galleryColumns = settings2.columns || "3";
    const galleryGap = settings2.gap || 15;
    const galleryRadius = settings2.border_radius || 8;
    const galleryMargin = settings2.margin || {
      top: 20,
      right: 0,
      bottom: 20,
      left: 0
    };
    const galleryShowCaption = settings2.show_caption !== "no";
    let galleryHTML = `<div class="probuilder-gallery-preview" style="
                        display: grid;
                        grid-template-columns: repeat(${galleryColumns}, 1fr);
                        gap: ${galleryGap}px;
                        margin: ${galleryMargin.top}px ${galleryMargin.right}px ${galleryMargin.bottom}px ${galleryMargin.left}px;
                    ">`;
    galleryImages.forEach((img, idx) => {
      galleryHTML += `
                            <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: ${galleryRadius}px; line-height: 0; cursor: pointer;">
                                <img src="${img.image_url}" alt="${img.caption || ""}" style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.3s ease;">
                                ${galleryShowCaption && img.caption ? `
                                    <div class="gallery-caption" style="
                                        position: absolute;
                                        bottom: 0;
                                        left: 0;
                                        right: 0;
                                        background: rgba(0,0,0,0.7);
                                        color: #ffffff;
                                        padding: 10px 15px;
                                        font-size: 14px;
                                        transform: translateY(100%);
                                        transition: transform 0.3s ease;
                                    ">${img.caption}</div>
                                ` : ""}
                            </div>
                        `;
    });
    galleryHTML += "</div>";
    setTimeout(function() {
      jQuery(".probuilder-gallery-preview .gallery-item").on("mouseenter", function() {
        jQuery(app2).find("img").css("transform", "scale(1.1)");
        jQuery(app2).find(".gallery-caption").css("transform", "translateY(0)");
      }).on("mouseleave", function() {
        jQuery(app2).find("img").css("transform", "scale(1)");
        jQuery(app2).find(".gallery-caption").css("transform", "translateY(100%)");
      });
    }, 100);
    return galleryHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/heading.js
  function renderHeading(context2) {
    const { element: element2, settings: settings2, spacingStyles: spacingStyles2, app: app2 } = context2;
    const headingTag = settings2.html_tag || "h2";
    const headingColor = settings2.color || app2.getGlobalColor("primary") || "#344047";
    const enableTextPath = settings2.enable_text_path === "yes";
    const pathTypeHeading = settings2.path_type || "curve";
    const curveAmountHeading = settings2.curve_amount || 50;
    const opacityStyle = typeof settings2.opacity !== "undefined" && settings2.opacity !== "" && settings2.opacity !== 100 ? `opacity: ${parseFloat(settings2.opacity) / 100};` : "";
    const transforms = [];
    if (typeof settings2.rotate !== "undefined" && settings2.rotate !== 0) transforms.push(`rotate(${settings2.rotate}deg)`);
    if (typeof settings2.scale !== "undefined" && settings2.scale !== 100) {
      const scaleVal = parseFloat(settings2.scale) / 100;
      transforms.push(`scale(${scaleVal})`);
    }
    if (typeof settings2.skew_x !== "undefined" && settings2.skew_x !== 0 || typeof settings2.skew_y !== "undefined" && settings2.skew_y !== 0) {
      const sx = settings2.skew_x || 0;
      const sy = settings2.skew_y || 0;
      transforms.push(`skew(${sx}deg, ${sy}deg)`);
    }
    const transformStyle = transforms.length ? `transform: ${transforms.join(" ")};` : "";
    const combinedSpacing = spacingStyles2 ? `${spacingStyles2};` : "";
    if (enableTextPath) {
      const titleText = (settings2.title || "This is a heading").toString();
      const fontSize = settings2.font_size || 32;
      const fontWeight = settings2.font_weight || 600;
      const textAlign = settings2.align || "left";
      const svgId = "heading-path-preview-" + element2.id;
      const svgHeight = fontSize * 3;
      const textLength = Math.max(10, titleText.length) * fontSize * 0.6;
      let pathD = "";
      if (pathTypeHeading === "curve") {
        const controlY = svgHeight / 2 - curveAmountHeading;
        pathD = `M 0,${svgHeight} Q ${textLength / 2},${controlY} ${textLength},${svgHeight}`;
      } else if (pathTypeHeading === "wave") {
        const waveHeight = Math.abs(curveAmountHeading);
        pathD = `M 0,${svgHeight / 2} Q ${textLength * 0.25},${svgHeight / 2 - waveHeight} ${textLength * 0.5},${svgHeight / 2} T ${textLength},${svgHeight / 2}`;
      } else {
        const radius = textLength / 2;
        pathD = `M 0,${svgHeight} A ${radius},${radius} 0 0,1 ${textLength},${svgHeight}`;
      }
      return `
            <div style="text-align: ${textAlign}; ${combinedSpacing} ${opacityStyle} ${transformStyle}">
                <svg width="100%" height="${svgHeight}px" viewBox="0 0 ${textLength} ${svgHeight}" xmlns="http://www.w3.org/2000/svg" style="overflow: visible;">
                    <defs>
                        <path id="${svgId}" d="${pathD}" fill="transparent"/>
                    </defs>
                    <text style="fill: ${headingColor}; font-size: ${fontSize}px; font-weight: ${fontWeight};">
                        <textPath href="#${svgId}" startOffset="50%" text-anchor="middle">${titleText}</textPath>
                    </text>
                </svg>
            </div>
        `;
    }
    const headingStyle = `
        color: ${headingColor};
        font-size: ${settings2.font_size || 32}px;
        font-weight: ${settings2.font_weight || 600};
        text-align: ${settings2.align || "left"};
        margin: 0;
        line-height: 1.3;
        ${combinedSpacing}
        ${opacityStyle}
        ${transformStyle}
    `;
    return `<${headingTag} style="${headingStyle}">${settings2.title || "This is a heading"}</${headingTag}>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/google-maps.js
  function renderGoogleMaps(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const gmAddress = settings2.address || "Times Square, New York, NY";
    const gmZoom = settings2.zoom || 15;
    const gmHeight = settings2.height || 400;
    return `<div style="position: relative; height: ${gmHeight}px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 8px; overflow: hidden;">
                        <div style="position: absolute; inset: 0; background: url('data:image/svg+xml,%3Csvg xmlns=\\'http://www.w3.org/2000/svg\\' viewBox=\\'0 0 800 600\\'%3E%3Crect fill=\\'%23e0e0e0\\' width=\\'800\\' height=\\'600\\'/%3E%3Cpath fill=\\'%23ccc\\' d=\\'M0 300 Q200 250 400 300 T800 300 V600 H0 Z\\'/%3E%3Cpath fill=\\'%23bbb\\' d=\\'M0 400 Q200 350 400 400 T800 400 V600 H0 Z\\'/%3E%3C/svg%3E') center/cover;"></div>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; z-index: 1;">
                            <div style="width: 40px; height: 60px; margin: 0 auto 15px; background: #92003b; clip-path: polygon(50% 100%, 0% 40%, 0% 0%, 100% 0%, 100% 40%); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 20px; padding-bottom: 15px;">
                                <i class="fa fa-map-marker-alt"></i>
                            </div>
                            <div style="background: rgba(255,255,255,0.95); padding: 15px 20px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                                <div style="font-weight: 600; color: #333; margin-bottom: 5px;">${gmAddress}</div>
                                <div style="font-size: 11px; color: #666;">Zoom: ${gmZoom}x \xB7 Height: ${gmHeight}px</div>
                            </div>
                        </div>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/grid-layout.js
  function renderGridLayout(context2) {
    var _a, _b, _c;
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const gridPattern = settings2.grid_pattern || "pattern-1";
    const gridGap = settings2.gap || 20;
    const gridMinHeight = settings2.min_height || 30;
    const gridBgColor = settings2.background_color || "#f8f9fa";
    const gridBorderColor = settings2.border_color || "#ddd";
    const gridBorderWidth = settings2.border_width || 1;
    const gridBorderRadius = settings2.border_radius || 8;
    const enableResize = settings2.enable_resize !== false;
    const gridPadding = settings2.padding || {
      top: 20,
      right: 20,
      bottom: 20,
      left: 20
    };
    const gridMargin = settings2.margin || {
      top: 0,
      right: 0,
      bottom: 0,
      left: 0
    };
    if (!element2.children) {
      element2.children = [];
    }
    console.log("\u{1F50D} Grid-layout rendering:", {
      widgetType: element2.widgetType,
      id: element2.id,
      childrenCount: element2.children.length,
      children: element2.children,
      settings: settings2
    });
    const columnsCount = parseInt(settings2.columns) || null;
    let gridTemplateData;
    let columnsTemplate;
    let rowsTemplate;
    if (columnsCount && columnsCount > 0) {
      const childrenCount = element2.children.length || columnsCount;
      const numRows = Math.ceil(Math.max(childrenCount, columnsCount) / columnsCount);
      columnsTemplate = `repeat(${columnsCount}, 1fr)`;
      rowsTemplate = `repeat(${numRows}, auto)`;
      const areas = [];
      for (let row = 1; row <= numRows; row++) {
        for (let col = 1; col <= columnsCount; col++) {
          const cellIndex = (row - 1) * columnsCount + (col - 1);
          areas.push(`${row} / ${col} / ${row + 1} / ${col + 1}`);
        }
      }
      gridTemplateData = {
        columns: columnsTemplate,
        rows: rowsTemplate,
        areas: areas.slice()
      };
      console.log("\u2705 Using dynamic grid template:", {
        columns: columnsCount,
        rows: numRows,
        areas: areas.length,
        children: childrenCount
      });
    } else {
      const pattern = app2.getGridPatterns().find((p) => p.id === gridPattern) || app2.getGridPatterns()[0];
      const baseTemplate = app2.getGridTemplateData(gridPattern);
      gridTemplateData = {
        columns: baseTemplate.columns,
        rows: baseTemplate.rows,
        areas: Array.isArray(baseTemplate.areas) ? baseTemplate.areas.slice() : []
      };
      columnsTemplate = gridTemplateData.columns;
      rowsTemplate = gridTemplateData.rows;
      if (element2.settings.custom_template) {
        if (element2.settings.custom_template.columns) {
          columnsTemplate = element2.settings.custom_template.columns;
        }
        if (element2.settings.custom_template.rows) {
          rowsTemplate = element2.settings.custom_template.rows;
        }
        if (Array.isArray(element2.settings.custom_template.areas) && element2.settings.custom_template.areas.length > 0) {
          gridTemplateData.areas = element2.settings.custom_template.areas.slice();
        }
        console.log("Using custom template:", {
          columns: columnsTemplate,
          rows: rowsTemplate,
          areas: gridTemplateData.areas.length
        });
      }
    }
    if (element2.settings.custom_template) {
      const customTemplate = element2.settings.custom_template;
      if (customTemplate.columns) {
        columnsTemplate = customTemplate.columns;
      }
      if (customTemplate.rows) {
        rowsTemplate = customTemplate.rows;
      }
      if (Array.isArray(customTemplate.areas) && customTemplate.areas.length > 0) {
        gridTemplateData.areas = customTemplate.areas.slice();
      }
    }
    const gridId = "grid-" + element2.id;
    let gridHTML = `
                        <style>
                            #${gridId} {
                                display: grid;
                                grid-template-columns: ${columnsTemplate};
                                grid-template-rows: ${rowsTemplate};
                                gap: ${gridGap}px;
                                width: 100%;
                                position: relative;
                                padding: ${gridPadding.top || 20}px ${gridPadding.right || 20}px ${gridPadding.bottom || 20}px ${gridPadding.left || 20}px;
                                margin: ${gridMargin.top || 0}px ${gridMargin.right || 0}px ${gridMargin.bottom || 0}px ${gridMargin.left || 0}px;
                            }
                            #${gridId} .grid-cell {
                                min-height: 0 !important;
                                background: ${gridBgColor};
                                border: ${gridBorderWidth}px solid ${gridBorderColor};
                                border-radius: ${gridBorderRadius}px;
                                padding: 40px 16px 16px;
                                position: relative;
                                overflow: visible !important;
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                                justify-content: flex-start;
                                gap: 12px;
                                transition: all 0.3s;
                            }
                            #${gridId} .grid-cell.has-content {
                                padding-top: 40px;
                            }
                            #${gridId} .grid-cell.empty-cell {
                                display: flex;
                                align-items: center;
                                justify-content: flex-start;
                                transition: all 0.3s;
                            }
                            #${gridId} .grid-cell.empty-cell:hover {
                                background: rgba(0,124,186,0.05);
                                border-color: #007cba;
                                transform: translateY(-2px);
                                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                            }
                            
                            /* Cell Drag Handle - Shows on hover for reordering */
                            #${gridId} .grid-cell-drag-handle {
                                position: absolute;
                                top: 5px;
                                left: 5px;
                                width: 32px;
                                height: 32px;
                                background: rgba(0, 124, 186, 0.9);
                                border: none;
                                border-radius: 4px;
                                color: white;
                                cursor: grab;
                                display: none;
                                align-items: center;
                                justify-content: center;
                                z-index: 999;
                                transition: all 0.2s ease;
                                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                            }
                            
                            #${gridId} .grid-cell:hover .grid-cell-drag-handle {
                                display: flex;
                            }
                            
                            #${gridId} .grid-cell-drag-handle:hover {
                                background: rgba(0, 92, 135, 1);
                                transform: scale(1.1);
                                box-shadow: 0 4px 8px rgba(0,0,0,0.3);
                            }
                            
                            #${gridId} .grid-cell-drag-handle:active {
                                cursor: grabbing;
                            }
                            
                            /* Cell Delete Button - Now part of toolbar, styled above */
                            
                            /* Dragging state for grid cells */
                            #${gridId} .grid-cell.ui-sortable-helper {
                                opacity: 0.7;
                                box-shadow: 0 10px 30px rgba(0,0,0,0.3) !important;
                                transform: rotate(2deg);
                                z-index: 10000 !important;
                                cursor: grabbing !important;
                            }
                            
                            #${gridId} .grid-cell.ui-sortable-placeholder {
                                border: 2px dashed #007cba !important;
                                background: rgba(0, 124, 186, 0.1) !important;
                                opacity: 1 !important;
                            }
                            #${gridId} .grid-cell-empty-content {
                                display: inline-flex;
                                align-items: center;
                                justify-content: center;
                                flex-direction: column;
                                padding: 16px 20px;
                                border: 1px dashed rgba(0, 124, 186, 0.4);
                                border-radius: 10px;
                                max-width: 220px;
                                pointer-events: auto;
                                cursor: pointer;
                                background: rgba(0, 124, 186, 0.05);
                                transition: border-color 0.2s ease, background 0.2s ease;
                                margin: 0 auto;
                                gap: 6px;
                            }
                            
                            #${gridId} .grid-cell-empty-content:hover {
                                border-color: rgba(0, 124, 186, 0.8);
                                background: rgba(0, 124, 186, 0.1);
                            }
                            
                            #${gridId} .grid-cell-empty-content .dashicons {
                                opacity: 0.4 !important;
                            }
                            
                            #${gridId} .grid-cell-add-btn {
                                display: inline-flex;
                                align-items: center;
                                gap: 6px;
                                padding: 10px 16px;
                                border-radius: 20px;
                                border: none;
                                background: #007cba;
                                color: #fff;
                                font-size: 12px;
                                cursor: pointer;
                                transition: background 0.2s ease, transform 0.2s ease;
                                pointer-events: auto;
                            }
                            
                            #${gridId} .grid-cell-add-btn:hover {
                                background: #005a87;
                                transform: translateY(-1px);
                            }
                            
                            /* Ensure toolbar buttons are always on top and clickable */
                            #${gridId} .grid-cell-toolbar,
                            #${gridId} .grid-cell-toolbar * {
                                pointer-events: auto !important;
                                position: relative;
                                z-index: 1000 !important;
                            }
                            #${gridId} .grid-cell-toolbar {
                                position: absolute;
                                top: 8px;
                                right: 8px;
                                opacity: 0;
                                transition: opacity 0.2s;
                                z-index: 1000;
                                pointer-events: auto;
                                display: flex;
                                gap: 4px;
                            }
                            #${gridId} .grid-cell:hover .grid-cell-toolbar {
                                opacity: 1;
                            }
                            #${gridId} .grid-cell-toolbar button {
                                background: #007cba;
                                color: white;
                                border: none;
                                border-radius: 3px;
                                padding: 4px 8px;
                                cursor: pointer;
                                font-size: 11px;
                                margin-left: 4px;
                                pointer-events: auto;
                                position: relative;
                                z-index: 1001;
                            }
                            #${gridId} .grid-cell-toolbar .grid-cell-delete-btn {
                                background: rgba(220, 38, 38, 0.9);
                            }
                            #${gridId} .grid-cell-toolbar .grid-cell-delete-btn:hover {
                                background: rgba(220, 38, 38, 1);
                            }
                            /* Resize handles - Show on hover only */
                            #${gridId} .grid-resize-handle {
                                position: absolute;
                                background: #007cba;
                                opacity: 0;
                                transition: all 0.2s;
                                z-index: 999;
                                pointer-events: auto;
                            }
                            #${gridId} .grid-cell:hover .grid-resize-handle {
                                opacity: 0.7;
                            }
                            #${gridId} .grid-resize-handle:hover {
                                opacity: 1 !important;
                                background: #005a87;
                                transform: scale(1.2);
                            }
                            #${gridId} .grid-resize-handle-top {
                                top: -${Math.floor(gridGap / 2)}px;
                                left: 0;
                                width: 100%;
                                height: 6px;
                                cursor: row-resize;
                            }
                            #${gridId} .grid-resize-handle-left {
                                top: 0;
                                left: -${Math.floor(gridGap / 2)}px;
                                width: 6px;
                                height: 100%;
                                cursor: col-resize;
                            }
                            #${gridId} .grid-resize-handle-right {
                                top: 0;
                                right: -${Math.floor(gridGap / 2)}px;
                                width: 6px;
                                height: 100%;
                                cursor: col-resize;
                            }
                            #${gridId} .grid-resize-handle-bottom {
                                bottom: -${Math.floor(gridGap / 2)}px;
                                left: 0;
                                width: 100%;
                                height: 6px;
                                cursor: row-resize;
                            }
                            #${gridId} .grid-resize-handle-corner {
                                bottom: -${Math.floor(gridGap / 2)}px;
                                right: -${Math.floor(gridGap / 2)}px;
                                width: 16px;
                                height: 16px;
                                cursor: nwse-resize;
                                border-radius: 3px;
                                background: #ffffff !important;
                                border: 2px solid #007cba;
                            }
                            
                            /* Drag and Drop Styles for Grid Cells */
                            #${gridId} .probuilder-drop-target {
                                outline: 2px dashed #007cba !important;
                                background: rgba(0, 124, 186, 0.1) !important;
                                transition: all 0.2s ease;
                            }
                            
                            #${gridId} .probuilder-drop-hover {
                                outline: 3px solid #007cba !important;
                                background: rgba(0, 124, 186, 0.2) !important;
                                transform: scale(1.02);
                                box-shadow: 0 4px 12px rgba(0, 124, 186, 0.3);
                            }
                            
                            /* Nested Element Drag Styles */
                            #${gridId} .probuilder-nested-element {
                                cursor: grab;
                                transition: all 0.2s ease;
                            }
                            
                            #${gridId} .probuilder-nested-element:hover {
                                transform: translateY(-2px);
                                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                            }
                            
                            #${gridId} .probuilder-nested-element:active {
                                cursor: grabbing;
                            }
                            
                            /* Toolbar Styles */
                            #${gridId} .probuilder-nested-toolbar {
                                background: rgba(0, 0, 0, 0.8);
                                border-radius: 4px;
                                padding: 4px;
                                backdrop-filter: blur(10px);
                            }
                            
                            #${gridId} .probuilder-nested-toolbar button {
                                background: transparent;
                                border: none;
                                color: white;
                                padding: 6px;
                                margin: 0 2px;
                                border-radius: 3px;
                                cursor: pointer;
                                transition: background 0.2s ease;
                            }
                            
                            #${gridId} .probuilder-resizable-widget:hover .probuilder-nested-toolbar,
                            #${gridId} .probuilder-nested-element:hover .probuilder-nested-toolbar {
                                display: flex !important;
                            }
                            
                            #${gridId} .probuilder-nested-toolbar button:hover {
                                background: rgba(255, 255, 255, 0.2);
                            }
                            
                            #${gridId} .probuilder-nested-toolbar .probuilder-nested-delete:hover {
                                background: rgba(244, 67, 54, 0.8);
                            }
                            
                            /* Widget Resize Dots - SIMPLE ALWAYS VISIBLE CORNERS */
                            #${gridId} .widget-resize-dot {
                                position: absolute;
                                width: 16px;
                                height: 16px;
                                z-index: 9999;
                                opacity: 1 !important;
                                cursor: pointer;
                                box-shadow: 0 2px 8px rgba(0,0,0,0.3);
                                border: 2px solid white;
                            }
                            
                            #${gridId} .widget-resize-dot:hover {
                                transform: scale(1.3);
                                box-shadow: 0 4px 12px rgba(0,0,0,0.5);
                            }
                            
                            /* 4 Corner Dots - OUTSIDE the widget for visibility */
                            #${gridId} .widget-resize-dot-tl {
                                top: -8px;
                                left: -8px;
                                cursor: nwse-resize;
                            }
                            
                            #${gridId} .widget-resize-dot-tr {
                                top: -8px;
                                right: -8px;
                                cursor: nesw-resize;
                            }
                            
                            #${gridId} .widget-resize-dot-br {
                                bottom: -8px;
                                right: -8px;
                                cursor: nwse-resize;
                            }
                            
                            #${gridId} .widget-resize-dot-bl {
                                bottom: -8px;
                                left: -8px;
                                cursor: nesw-resize;
                            }
                            
                            #${gridId} .probuilder-resizable-widget {
                                min-width: 50px;
                                min-height: 50px;
                                border: 1px dashed transparent;
                                transition: border-color 0.2s ease;
                                padding: 8px; /* Add padding so handles don't get cut off */
                                margin: 8px; /* Add margin for space */
                                overflow: visible !important; /* Ensure handles are visible */
                            }
                            
                            #${gridId} .probuilder-resizable-widget:hover {
                                border-color: #007cba;
                            }
                        </style>
                    `;
    const layoutMode = ((_a = element2.settings.custom_template) == null ? void 0 : _a.layout_mode) || "grid";
    const containerHeight = (_b = element2.settings.custom_template) == null ? void 0 : _b.container_height;
    const containerWidth = (_c = element2.settings.custom_template) == null ? void 0 : _c.container_width;
    const containerStyleParts = ["position: relative"];
    if (layoutMode === "absolute") {
      containerStyleParts.push("display: block");
      if (containerWidth) {
        containerStyleParts.push(`min-width: ${containerWidth}px`);
      }
      if (containerHeight) {
        containerStyleParts.push(`min-height: ${containerHeight}px`);
        containerStyleParts.push(`height: ${containerHeight}px`);
      }
    }
    const containerStyleAttr = containerStyleParts.length ? ` style="${containerStyleParts.join("; ")}"` : "";
    gridHTML += `
                        <div id="${gridId}" class="probuilder-grid-layout" data-element-id="${element2.id}" data-grid-element-id="${element2.id}" data-grid-pattern="${gridPattern}"${containerStyleAttr}>
                    `;
    gridTemplateData.areas.forEach((area, index) => {
      var _a2, _b2, _c2, _d, _e;
      const child = element2.children && element2.children[index];
      const hasContent = !!child;
      console.log(`Grid cell ${index}:`, {
        hasContent,
        child: child ? child.widgetType : "none"
      });
      const cellOverrides = ((_a2 = element2.settings.custom_template) == null ? void 0 : _a2.cell_overrides) || [];
      const override = cellOverrides[index];
      const useAbsolute = override && typeof override === "object" && override.position === "absolute";
      const overrideStyles = [];
      if (useAbsolute) {
        const resolveValue = (percent, px) => {
          if (typeof percent === "number" && !Number.isNaN(percent)) {
            return `${percent}%`;
          }
          if (typeof px === "number" && !Number.isNaN(px)) {
            return `${px}px`;
          }
          return null;
        };
        overrideStyles.push("position: absolute");
        overrideStyles.push("grid-area: unset");
        const leftValue = resolveValue(override.leftPercent, override.left);
        const topValue = resolveValue(override.topPercent, override.top);
        const widthValue = resolveValue(override.widthPercent, override.width);
        const heightValue = resolveValue(override.heightPercent, override.height);
        if (leftValue !== null) {
          overrideStyles.push(`left: ${leftValue}`);
        }
        if (topValue !== null) {
          overrideStyles.push(`top: ${topValue}`);
        }
        if (widthValue !== null) {
          overrideStyles.push(`width: ${widthValue}`);
        }
        if (heightValue !== null) {
          overrideStyles.push(`height: ${heightValue}`);
        }
        if (typeof override.zIndex !== "undefined" && override.zIndex !== null && override.zIndex !== "") {
          overrideStyles.push(`z-index: ${override.zIndex}`);
        }
      } else {
        overrideStyles.push(`grid-area: ${area}`);
        overrideStyles.push("position: relative");
      }
      const overrideStyle = overrideStyles.length ? `style="${overrideStyles.join("; ")}"` : "";
      gridHTML += `
                            <div class="grid-cell ${hasContent ? "has-content" : "empty-cell"} ${hasContent ? "" : "probuilder-drop-zone"}" 
                                 ${overrideStyle}
                                 data-cell-index="${index}"
                                 data-grid-id="${element2.id}"
                                 data-original-area="${area}">
                                <div class="grid-cell-drag-handle" data-cell-index="${index}" title="Drag to reorder">
                                    <i class="dashicons dashicons-move" style="font-size: 16px;"></i>
                                </div>
                                <div class="grid-cell-toolbar" data-grid-id="${element2.id}">
                                    <button type="button" class="add-content-btn" title="Add Content" data-grid-id="${element2.id}" data-cell-index="${index}">
                                        <i class="dashicons dashicons-plus" style="font-size: 12px; width: 12px; height: 12px;"></i>
                                    </button>
                                    <button type="button" class="settings-btn" title="Cell Settings" data-grid-id="${element2.id}" data-cell-index="${index}">
                                        <i class="dashicons dashicons-admin-generic" style="font-size: 12px; width: 12px; height: 12px;"></i>
                                    </button>
                                    <button type="button" class="grid-cell-delete-btn" title="Delete Cell" data-grid-id="${element2.id}" data-cell-index="${index}">
                                        <i class="dashicons dashicons-trash" style="font-size: 12px; width: 12px; height: 12px;"></i>
                                    </button>
                                </div>
                        `;
      if (hasContent) {
        const childPreview = app2.generatePreview(child, depth2 + 1);
        const widgetWidth = ((_b2 = child.settings) == null ? void 0 : _b2.widget_width) || "100%";
        const widgetHeight = ((_c2 = child.settings) == null ? void 0 : _c2.widget_height) || "auto";
        const widgetMarginLeft = ((_d = child.settings) == null ? void 0 : _d.widget_margin_left) || "0px";
        const widgetMarginTop = ((_e = child.settings) == null ? void 0 : _e.widget_margin_top) || "0px";
        gridHTML += `
                                <div class="probuilder-nested-element probuilder-resizable-widget" 
                                     data-id="${child.id}" 
                                     data-widget="${child.widgetType}"
                                     data-cell-index="${index}"
                                     style="width: ${widgetWidth}; height: ${widgetHeight}; margin-left: ${widgetMarginLeft}; margin-top: ${widgetMarginTop}; position: relative;">
                                    <div class="probuilder-nested-toolbar" style="
                                        position: absolute;
                                        top: 5px;
                                        right: 5px;
                                        z-index: 1000;
                                        display: none;
                                        gap: 4px;
                                        background: rgba(255,255,255,0.95);
                                        padding: 4px;
                                        border-radius: 3px;
                                        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                                        pointer-events: auto;
                                    ">
                                        <button class="probuilder-nested-edit" title="Edit" style="
                                            background: #007cba;
                                            border: none;
                                            color: #ffffff;
                                            width: 24px;
                                            height: 24px;
                                            border-radius: 2px;
                                            cursor: pointer;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            pointer-events: auto;
                                        ">
                                            <i class="dashicons dashicons-edit" style="font-size: 12px; pointer-events: none;"></i>
                                        </button>
                                        <button class="probuilder-nested-delete" title="Delete" style="
                                            background: #dc2626;
                                            border: none;
                                            color: #ffffff;
                                            width: 24px;
                                            height: 24px;
                                            border-radius: 2px;
                                            cursor: pointer;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            pointer-events: auto;
                                        ">
                                            <i class="dashicons dashicons-trash" style="font-size: 12px; pointer-events: none;"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Widget Resize Handles - 4 VISIBLE corner dots -->
                                    <div class="widget-resize-dot widget-resize-dot-tl" data-direction="top-left" title="Resize from top-left">
                                        <span style="background: #ff0000; width: 100%; height: 100%; display: block; border-radius: 50%;"></span>
                                    </div>
                                    <div class="widget-resize-dot widget-resize-dot-tr" data-direction="top-right" title="Resize from top-right">
                                        <span style="background: #00ff00; width: 100%; height: 100%; display: block; border-radius: 50%;"></span>
                                    </div>
                                    <div class="widget-resize-dot widget-resize-dot-br" data-direction="bottom-right" title="Resize from bottom-right">
                                        <span style="background: #0000ff; width: 100%; height: 100%; display: block; border-radius: 50%;"></span>
                                    </div>
                                    <div class="widget-resize-dot widget-resize-dot-bl" data-direction="bottom-left" title="Resize from bottom-left">
                                        <span style="background: #ffff00; width: 100%; height: 100%; display: block; border-radius: 50%;"></span>
                                    </div>
                                    
                                    <div class="probuilder-nested-preview">
                                        ${childPreview}
                                    </div>
                                </div>
                            `;
      } else {
        gridHTML += `
                                <div class="grid-cell-empty-content">
                                    <button type="button" class="grid-cell-add-btn" data-grid-id="${element2.id}" data-cell-index="${index}">
                                        <i class="dashicons dashicons-plus-alt2" style="font-size: 16px;"></i>
                                        <span>Add Widget</span>
                                    </button>
                                </div>
                            `;
      }
      if (enableResize) {
        gridHTML += `
                                <div class="grid-resize-handle grid-resize-handle-top" data-cell-index="${index}" data-direction="top"></div>
                                <div class="grid-resize-handle grid-resize-handle-left" data-cell-index="${index}" data-direction="left"></div>
                                <div class="grid-resize-handle grid-resize-handle-right" data-cell-index="${index}" data-direction="right"></div>
                                <div class="grid-resize-handle grid-resize-handle-bottom" data-cell-index="${index}" data-direction="bottom"></div>
                                <div class="grid-resize-handle grid-resize-handle-corner" data-cell-index="${index}" data-direction="both"></div>
                            `;
      }
      gridHTML += `</div>`;
    });
    gridHTML += `</div>`;
    return gridHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/hotspot.js
  function renderHotspot(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const hotspotImage = settings2.image || "https://via.placeholder.com/800x600/93003c/ffffff?text=Hotspot+Image";
    const hotspots = settings2.hotspots || [{
      x_position: 30,
      y_position: 30,
      title: "Hotspot 1",
      content: "Info"
    }];
    let hotspotHTML = `<div style="position:relative;display:inline-block;max-width:100%">
                        <img src="${hotspotImage}" style="width:100%;height:auto;display:block">`;
    hotspots.forEach((spot) => {
      hotspotHTML += `<div style="position:absolute;left:${spot.x_position || 50}%;top:${spot.y_position || 50}%;transform:translate(-50%,-50%)">
                            <span style="display:block;width:20px;height:20px;background:#0073aa;border-radius:50%;animation:pulse 2s infinite"></span>
                        </div>`;
    });
    hotspotHTML += "</div><style>@keyframes pulse{0%,100%{opacity:1}50%{opacity:0.5}}</style>";
    return hotspotHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/html-code.js
  function renderHtmlCode(context) {
    const {
      element,
      settings,
      spacingStyles,
      app,
      depth = 0,
      widget
    } = context;
    const htmlCode = settings.html_code || '<div class="custom-element"><h3>Custom HTML</h3><p>Add your code here</p></div>';
    const cssCode = settings.css_code || ".custom-element { padding: 20px; background: #f8f9fa; }";
    const jsCode = settings.js_code || "";
    const htmlMargin = settings.margin || {
      top: 20,
      right: 0,
      bottom: 20,
      left: 0
    };
    const htmlCodeId = "html-code-" + element.id;
    let htmlCodeHTML = `
                        <div class="probuilder-html-code-preview" id="${htmlCodeId}" style="
                            margin: ${htmlMargin.top}px ${htmlMargin.right}px ${htmlMargin.bottom}px ${htmlMargin.left}px;
                            position: relative;
                        ">
                            <div class="code-label" style="
                                position: absolute;
                                top: 8px;
                                right: 8px;
                                background: #1e293b;
                                color: #fff;
                                padding: 4px 10px;
                                border-radius: 3px;
                                font-size: 10px;
                                font-family: monospace;
                                z-index: 10;
                            ">HTML/CSS/JS</div>
                            <div class="html-output">
                                ${htmlCode}
                            </div>
                            ${cssCode ? `<style>${cssCode}</style>` : ""}
                        </div>
                    `;
    if (jsCode) {
      setTimeout(function() {
        try {
          eval(jsCode);
        } catch (e) {
          console.log("JS code error (expected in preview):", e);
        }
      }, 100);
    }
    return htmlCodeHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/icon.js
  function renderIcon(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const iconWidgetSize = settings2.size || 50;
    const iconWidgetColor = settings2.color || "#0073aa";
    const iconWidgetAlign = settings2.align || "center";
    const iconWidgetClass = settings2.icon || "fa fa-star";
    return `<div style="text-align:${iconWidgetAlign}"><i class="${iconWidgetClass}" style="font-size:${iconWidgetSize}px;color:${iconWidgetColor}"></i></div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/icon-box.js
  function renderIconBox(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const iconAlign = settings2.alignment || settings2.text_align || "center";
    const iconSize = settings2.icon_size || "48px";
    const iconColor = settings2.icon_color || "#92003b";
    const iconTitle = settings2.title || "Icon Box Title";
    const iconTitleSize = settings2.title_size || "18px";
    const iconDesc = settings2.description || "Description goes here";
    const iconDescSize = settings2.description_size || "14px";
    const iconBg = settings2.background_color || "transparent";
    const iconPadding = settings2.padding || "30px";
    const iconBorderRadius = settings2.border_radius || "8px";
    const iconBoxShadow = settings2.box_shadow === "yes" ? "0 4px 12px rgba(0,0,0,0.1)" : "none";
    return `
                        <div style="text-align: ${iconAlign}; padding: ${iconPadding}; background: ${iconBg}; border-radius: ${iconBorderRadius}; box-shadow: ${iconBoxShadow}; transition: all 0.3s;">
                            <div style="width: ${iconSize}; height: ${iconSize}; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center; background: ${iconColor}15; border-radius: 50%;">
                                <i class="${settings2.icon || "fa fa-star"}" style="font-size: ${iconSize}; color: ${iconColor};"></i>
                            </div>
                            <h3 style="margin: 0 0 10px 0; font-size: ${iconTitleSize}; font-weight: 600; color: #1f2937;">${iconTitle}</h3>
                            <p style="margin: 0; color: #6b7280; font-size: ${iconDescSize}; line-height: 1.6;">${iconDesc}</p>
                        </div>
                    `;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/icon-list.js
  function renderIconList(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const iconListItems = settings2.items || [{
      text: "Professional Design",
      icon: "fa fa-check-circle"
    }, {
      text: "Fast Performance",
      icon: "fa fa-check-circle"
    }, {
      text: "Responsive Layout",
      icon: "fa fa-check-circle"
    }];
    const iconListLayout = settings2.layout || "vertical";
    const iconListColumns = settings2.columns || "2";
    const iconListIconColor = settings2.icon_color || "#92003b";
    const iconListIconSize = settings2.icon_size || 20;
    const iconListTextColor = settings2.text_color || "#333333";
    const iconListTextSize = settings2.text_size || 16;
    const iconListMargin = settings2.margin || {
      top: 20,
      right: 0,
      bottom: 20,
      left: 0
    };
    let iconListContainerStyle = `list-style: none; margin: ${iconListMargin.top}px ${iconListMargin.right}px ${iconListMargin.bottom}px ${iconListMargin.left}px; padding: 0; `;
    if (iconListLayout === "grid") {
      iconListContainerStyle += `display: grid; grid-template-columns: repeat(${iconListColumns}, 1fr); gap: 15px;`;
    } else if (iconListLayout === "horizontal") {
      iconListContainerStyle += "display: flex; flex-wrap: wrap; gap: 15px 30px;";
    } else {
      iconListContainerStyle += "display: flex; flex-direction: column; gap: 15px;";
    }
    let iconListHTML = `<ul class="probuilder-icon-list-preview" style="${iconListContainerStyle}">`;
    iconListItems.forEach((item) => {
      iconListHTML += `
                            <li style="display: flex; align-items: center;">
                                <span style="color: ${iconListIconColor}; margin-right: 12px; font-size: ${iconListIconSize}px;">
                                    <i class="${item.icon}"></i>
                                </span>
                                <span style="color: ${iconListTextColor}; font-size: ${iconListTextSize}px;">
                                    ${item.text}
                                </span>
                            </li>
                        `;
    });
    iconListHTML += "</ul>";
    return iconListHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/image.js
  function renderImage(context2) {
    var _a;
    const { settings: settings2 } = context2;
    const defaultPlaceholder = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600"%3E%3Crect fill="%23d1d5db" width="800" height="600"/%3E%3Cg fill="white" opacity="0.5"%3E%3Crect x="250" y="180" width="300" height="240" rx="8" fill="none" stroke="white" stroke-width="3"/%3E%3Ccircle cx="320" cy="250" r="25"/%3E%3Cpath d="M250 380 L350 300 L450 350 L550 280 L550 420 L250 420 Z"/%3E%3C/g%3E%3C/svg%3E';
    const imgUrl = ((_a = settings2.image) == null ? void 0 : _a.url) || defaultPlaceholder;
    const imgAlign = settings2.align || "center";
    const imgWidth = settings2.width || 100;
    const imgMaxWidth = settings2.max_width || "";
    const imgHeight = settings2.height || "";
    const imgObjectFit = settings2.object_fit || "cover";
    const imgBorderRadius = settings2.border_radius || 0;
    const imgBorderWidth = settings2.border_width || 0;
    const imgBorderColor = settings2.border_color || "#000000";
    let imgStyle = `width: ${imgWidth}%; max-width: 100%;`;
    if (imgHeight) {
      imgStyle += ` height: ${imgHeight}px; object-fit: ${imgObjectFit};`;
    } else {
      imgStyle += ` height: 100%; max-height: 100%; object-fit: ${imgObjectFit};`;
    }
    if (imgMaxWidth) imgStyle += ` max-width: ${imgMaxWidth}px;`;
    if (imgBorderRadius > 0) imgStyle += ` border-radius: ${imgBorderRadius}px;`;
    if (imgBorderWidth > 0) imgStyle += ` border: ${imgBorderWidth}px solid ${imgBorderColor};`;
    imgStyle += " display: block;";
    return `<div style="text-align: ${imgAlign}; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; overflow: hidden;"><img src="${imgUrl}" alt="" style="${imgStyle}"></div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/image-box.js
  function renderImageBox(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const imageBoxUrl = settings2.image_url || "https://via.placeholder.com/600x400";
    const imageBoxPosition = settings2.image_position || "top";
    const imageBoxTitle = settings2.title || "Beautiful Image Box";
    const imageBoxDesc = settings2.description || "Add a stunning image with text";
    const imageBoxShowBtn = settings2.show_button !== "no";
    const imageBoxBtnText = settings2.button_text || "Learn More";
    const imageBoxAlign = settings2.text_align || "left";
    const imageBoxTitleColor = settings2.title_color || "#333333";
    const imageBoxTitleSize = settings2.title_size || 24;
    const imageBoxDescColor = settings2.description_color || "#666666";
    const imageBoxBgColor = settings2.bg_color || "#ffffff";
    const imageBoxRadius = settings2.border_radius || 8;
    const imageBoxMargin = settings2.margin || {
      top: 20,
      right: 0,
      bottom: 20,
      left: 0
    };
    const imageBoxPadding = settings2.padding || {
      top: 25,
      right: 25,
      bottom: 25,
      left: 25
    };
    let imageBoxHTML = `
                        <div class="probuilder-image-box-preview" style="
                            background: ${imageBoxBgColor};
                            border-radius: ${imageBoxRadius}px;
                            overflow: hidden;
                            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                            margin: ${imageBoxMargin.top}px ${imageBoxMargin.right}px ${imageBoxMargin.bottom}px ${imageBoxMargin.left}px;
                            ${imageBoxPosition !== "top" ? "display: flex; align-items: center;" : ""}
                            ${imageBoxPosition === "right" ? "flex-direction: row-reverse;" : ""}
                        ">
                            <div class="image-box-image" style="margin: 0; line-height: 0; ${imageBoxPosition !== "top" ? "flex-shrink: 0; width: 50%;" : ""}">
                                <img src="${imageBoxUrl}" alt="${imageBoxTitle}" style="width: 100%; height: auto; display: block;">
                            </div>
                            <div class="image-box-content" style="
                                padding: ${imageBoxPadding.top}px ${imageBoxPadding.right}px ${imageBoxPadding.bottom}px ${imageBoxPadding.left}px;
                                text-align: ${imageBoxAlign};
                                ${imageBoxPosition !== "top" ? "flex: 1;" : ""}
                            ">
                                <h3 style="margin: 0 0 12px 0; font-size: ${imageBoxTitleSize}px; color: ${imageBoxTitleColor}; font-weight: 600;">
                                    ${imageBoxTitle}
                                </h3>
                                ${imageBoxDesc ? `<p style="margin: 0 0 20px 0; font-size: 16px; color: ${imageBoxDescColor}; line-height: 1.6;">
                                    ${imageBoxDesc}
                                </p>` : ""}
                                ${imageBoxShowBtn ? `<a href="#" style="display: inline-block; background: #92003b; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 4px; font-weight: 600;">
                                    ${imageBoxBtnText}
                                </a>` : ""}
                            </div>
                        </div>
                    `;
    return imageBoxHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/image-comparison.js
  function renderImageComparison(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const beforeImage = settings2.before_image || "https://via.placeholder.com/800x600/666/fff?text=Before";
    const afterImage = settings2.after_image || "https://via.placeholder.com/800x600/0073aa/fff?text=After";
    const comparisonPosition = settings2.position || 50;
    return `<div style="position:relative;overflow:hidden;border-radius:8px;background:#f5f5f5">
                        <div style="position:relative;height:400px;background:url('${beforeImage}') center/cover">
                            <div style="position:absolute;top:0;left:0;height:100%;width:${comparisonPosition}%;overflow:hidden;background:url('${afterImage}') center/cover"></div>
                            <div style="position:absolute;top:0;left:${comparisonPosition}%;width:4px;height:100%;background:#fff;box-shadow:0 0 10px rgba(0,0,0,0.5)">
                                <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:40px;height:40px;background:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 10px rgba(0,0,0,0.3)">
                                    <i class="fa fa-arrows-alt-h" style="color:#333"></i>
                                </div>
                            </div>
                            <div style="position:absolute;top:20px;left:20px;background:rgba(0,0,0,0.7);color:#fff;padding:8px 15px;border-radius:4px;font-size:12px;font-weight:600">BEFORE</div>
                            <div style="position:absolute;top:20px;right:20px;background:rgba(0,115,170,0.9);color:#fff;padding:8px 15px;border-radius:4px;font-size:12px;font-weight:600">AFTER</div>
                        </div>
                        <p style="text-align:center;margin:15px 0 0;color:#666;font-size:12px">
                            <i class="fa fa-arrows-alt-h"></i> Drag slider to compare
                        </p>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/info-box.js
  function renderInfoBox(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const infoIconType = settings2.icon_type || "number";
    const infoNumber = settings2.number || "01";
    const infoIcon = settings2.icon || "fa fa-check-circle";
    const infoTitle = settings2.title || "Step One";
    const infoDescription = settings2.description || "This is a description of the first step.";
    const infoButtonText = settings2.button_text || "";
    const infoLayout = settings2.layout || "horizontal";
    const infoIconStyle = settings2.icon_style || "circle";
    const infoIconSize = settings2.icon_size || 70;
    const infoAccentColor = settings2.accent_color || "#92003b";
    const infoBgColor = settings2.bg_color || "#ffffff";
    const infoTitleColor = settings2.title_color || "#333333";
    const infoDescColor = settings2.description_color || "#666666";
    const infoBorderColor = settings2.border_color || "#e5e5e5";
    const infoBorderRadius = settings2.border_radius || 8;
    const infoBoxShadow = settings2.box_shadow === "yes";
    let infoIconBorderRadius = "50%";
    if (infoIconStyle === "square") infoIconBorderRadius = "0";
    else if (infoIconStyle === "rounded") infoIconBorderRadius = "12px";
    const infoContainerStyle = `
                        padding: 25px;
                        background: ${infoBgColor};
                        border: 1px solid ${infoBorderColor};
                        border-radius: ${infoBorderRadius}px;
                        ${infoLayout === "horizontal" ? "display: flex; gap: 20px; align-items: flex-start;" : "display: flex; flex-direction: column; align-items: center; text-align: center; gap: 20px;"}
                        ${infoBoxShadow ? "box-shadow: 0 4px 15px rgba(0,0,0,0.1);" : ""}
                    `;
    const infoIconContainerStyle = `
                        flex-shrink: 0;
                        width: ${infoIconSize}px;
                        height: ${infoIconSize}px;
                        background: ${infoAccentColor};
                        color: #fff;
                        border-radius: ${infoIconBorderRadius};
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: ${infoIconSize * 0.4}px;
                        font-weight: bold;
                    `;
    let infoBoxHTML = `<div style="${infoContainerStyle}">`;
    infoBoxHTML += `<div style="${infoIconContainerStyle}">`;
    if (infoIconType === "icon") {
      infoBoxHTML += `<i class="${infoIcon}"></i>`;
    } else {
      infoBoxHTML += infoNumber;
    }
    infoBoxHTML += `</div>`;
    const infoContentStyle = `flex: 1; ${infoLayout === "vertical" ? "text-align: center;" : ""}`;
    infoBoxHTML += `<div style="${infoContentStyle}">`;
    infoBoxHTML += `<h3 style="margin: 0 0 10px 0; font-size: 20px; font-weight: 600; color: ${infoTitleColor};">${infoTitle}</h3>`;
    if (infoDescription) {
      infoBoxHTML += `<p style="margin: 0 0 15px 0; color: ${infoDescColor}; line-height: 1.6; font-size: 14px;">${infoDescription}</p>`;
    }
    if (infoButtonText) {
      infoBoxHTML += `<a href="#" style="background: ${infoAccentColor}; color: #fff; padding: 10px 24px; border: none; border-radius: 4px; text-decoration: none; display: inline-block; font-weight: 600; font-size: 14px;">${infoButtonText}</a>`;
    }
    infoBoxHTML += `</div>`;
    infoBoxHTML += `</div>`;
    return infoBoxHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/instagram-feed.js
  function renderInstagramFeed(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const instaColumns = settings2.columns || 3;
    const instaLimit = settings2.limit || 6;
    return `<div style="padding:20px;background:#f9f9f9;border-radius:8px">
                        <div style="text-align:center;margin-bottom:20px">
                            <i class="fa fa-instagram" style="font-size:32px;color:#e4405f;margin-bottom:10px"></i>
                            <h4 style="margin:0;font-size:18px;color:#333">Instagram Feed</h4>
                            <small style="color:#999">@${settings2.username || "username"}</small>
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(${instaColumns},1fr);gap:10px">
                            ${Array(Math.min(instaLimit, 6)).fill(0).map((_, i) => `
                                <div style="aspect-ratio:1;background:linear-gradient(135deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:32px">
                                    <i class="fa fa-camera"></i>
                                </div>
                            `).join("")}
                        </div>
                        <p style="text-align:center;margin-top:15px;font-size:12px;color:#999">
                            <i class="fa fa-info-circle"></i> Connect Instagram API to display photos
                        </p>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/line-chart.js
  function renderLineChart(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const lineTitle = settings2.chart_title || "Monthly Revenue";
    const lineShowTitle = settings2.show_title !== "no";
    const lineHeight = settings2.chart_height || 400;
    const lineColor = settings2.line_color || "#36A2EB";
    const lineData = settings2.chart_data || "Jan, 4500\nFeb, 5200\nMar, 6100\nApr, 5800\nMay, 7200\nJun, 8500";
    const lineWidth = settings2.line_width || 3;
    const showPoints = settings2.show_points !== "no";
    const fillArea = settings2.fill_area === "yes";
    const fillColor = settings2.fill_color || "rgba(54, 162, 235, 0.2)";
    const lineLines = lineData.split("\n").filter((line) => line.trim());
    const lineLabels = [];
    const lineValues = [];
    lineLines.forEach((line) => {
      const parts = line.split(",").map((s) => s.trim());
      if (parts.length >= 2) {
        lineLabels.push(parts[0]);
        lineValues.push(parseFloat(parts[1]) || 0);
      }
    });
    const lineMaxValue = Math.max(...lineValues);
    const lineMinValue = Math.min(...lineValues);
    const lineRange = lineMaxValue - lineMinValue || 1;
    const svgWidth = 400;
    const svgHeight = 200;
    const padding = 30;
    const plotWidth = svgWidth - 2 * padding;
    const plotHeight = svgHeight - 2 * padding;
    let linePoints = lineValues.map((value, i) => {
      const x = padding + i / (lineValues.length - 1 || 1) * plotWidth;
      const y = padding + plotHeight - (value - lineMinValue) / lineRange * plotHeight;
      return {
        x,
        y
      };
    });
    const linePolylinePoints = linePoints.map((p) => `${p.x},${p.y}`).join(" ");
    let lineHTML = `<div style="padding: 20px; text-align: center;">`;
    if (lineShowTitle && lineTitle) {
      lineHTML += `<h3 style="margin-bottom: 20px; font-size: 24px; font-weight: 600;">${lineTitle}</h3>`;
    }
    lineHTML += `<div style="display: flex; justify-content: center; align-items: center; height: ${lineHeight}px; background: #f9f9f9; border-radius: 8px; padding: 20px;">`;
    lineHTML += `<svg width="100%" height="100%" viewBox="0 0 ${svgWidth} ${svgHeight + 40}">`;
    if (fillArea && linePoints.length > 0) {
      const areaPoints = `${padding},${padding + plotHeight} ${linePolylinePoints} ${linePoints[linePoints.length - 1].x},${padding + plotHeight}`;
      lineHTML += `<polygon points="${areaPoints}" fill="${fillColor}" stroke="none"/>`;
    }
    lineHTML += `<polyline points="${linePolylinePoints}" fill="none" stroke="${lineColor}" stroke-width="${lineWidth}"/>`;
    if (showPoints) {
      linePoints.forEach((p) => {
        lineHTML += `<circle cx="${p.x}" cy="${p.y}" r="5" fill="${lineColor}"/>`;
      });
    }
    lineLabels.forEach((label, i) => {
      const x = padding + i / (lineLabels.length - 1 || 1) * plotWidth;
      lineHTML += `<text x="${x}" y="${svgHeight}" text-anchor="middle" font-size="11" fill="#666">${label}</text>`;
    });
    lineHTML += `</svg>`;
    lineHTML += `</div></div>`;
    return lineHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/login.js
  function renderLogin(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    return `<div style="max-width:400px;padding:30px;background:#f9f9f9;border-radius:8px">
                        <h3 style="margin:0 0 20px">Login</h3>
                        <input type="text" placeholder="Username" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;margin-bottom:15px">
                        <input type="password" placeholder="Password" style="width:100%;padding:12px;border:1px solid #ddd;border-radius:4px;margin-bottom:15px">
                        <button style="width:100%;background:#0073aa;color:#fff;border:none;padding:12px;border-radius:4px;cursor:pointer;font-weight:600">Login</button>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/logo-grid.js
  function renderLogoGrid(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const logoGridLogos = settings2.logos || [{
      logo_url: "https://logo.clearbit.com/google.com",
      name: "Google",
      link: "https://google.com"
    }, {
      logo_url: "https://logo.clearbit.com/microsoft.com",
      name: "Microsoft",
      link: "https://microsoft.com"
    }, {
      logo_url: "https://logo.clearbit.com/apple.com",
      name: "Apple",
      link: "https://apple.com"
    }, {
      logo_url: "https://logo.clearbit.com/amazon.com",
      name: "Amazon",
      link: "https://amazon.com"
    }, {
      logo_url: "https://logo.clearbit.com/facebook.com",
      name: "Meta",
      link: "https://facebook.com"
    }, {
      logo_url: "https://logo.clearbit.com/netflix.com",
      name: "Netflix",
      link: "https://netflix.com"
    }];
    const logoColumns = settings2.columns || "4";
    const logoGap = settings2.gap || 30;
    const logoGrayscale = settings2.grayscale !== "no";
    const logoOpacity = settings2.opacity || 0.7;
    const logoBg = settings2.bg_color || "transparent";
    const logoPadding = settings2.padding || 20;
    const logoShowBorder = settings2.border === "yes";
    const logoBorderColor = settings2.border_color || "#e5e5e5";
    const logoMargin = settings2.margin || {
      top: 20,
      right: 0,
      bottom: 20,
      left: 0
    };
    let logoGridHTML = `<div class="probuilder-logo-grid-preview" style="
                        display: grid;
                        grid-template-columns: repeat(${logoColumns}, 1fr);
                        gap: ${logoGap}px;
                        margin: ${logoMargin.top}px ${logoMargin.right}px ${logoMargin.bottom}px ${logoMargin.left}px;
                    ">`;
    logoGridLogos.forEach((logo) => {
      logoGridHTML += `
                            <div class="logo-item" style="
                                text-align: center;
                                padding: ${logoPadding}px;
                                background: ${logoBg};
                                ${logoShowBorder ? `border: 1px solid ${logoBorderColor}; border-radius: 8px;` : ""}
                                transition: all 0.3s ease;
                            ">
                                <img src="${logo.logo_url}" alt="${logo.name}" title="${logo.name}" style="
                                    max-width: 100%;
                                    height: auto;
                                    display: block;
                                    margin: 0 auto;
                                    ${logoGrayscale ? "filter: grayscale(100%);" : ""}
                                    opacity: ${logoOpacity};
                                    transition: all 0.3s ease;
                                ">
                            </div>
                        `;
    });
    logoGridHTML += "</div>";
    setTimeout(function() {
      jQuery(".probuilder-logo-grid-preview .logo-item").on("mouseenter", function() {
        jQuery(app2).find("img").css({
          "filter": "grayscale(0%)",
          "opacity": "1",
          "transform": "scale(1.05)"
        });
      }).on("mouseleave", function() {
        jQuery(app2).find("img").css({
          "filter": logoGrayscale ? "grayscale(100%)" : "grayscale(0%)",
          "opacity": logoOpacity,
          "transform": "scale(1)"
        });
      });
    }, 100);
    return logoGridHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/loop-builder.js
  function renderLoopBuilder(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    return `<div style="display:grid;grid-template-columns:repeat(${settings2.columns || 3},1fr);gap:20px">
                        ${[1, 2, 3].map((i) => `
                            <div style="border:1px solid #eee;border-radius:8px;overflow:hidden">
                                <div style="background:#f0f0f0;height:150px;display:flex;align-items:center;justify-content:center;color:#999">Post ${i}</div>
                                <div style="padding:15px">
                                    <h3 style="margin:0 0 10px;font-size:18px">Dynamic Post ${i}</h3>
                                    <p style="margin:0;color:#666;font-size:14px">Post excerpt will appear here...</p>
                                </div>
                            </div>
                        `).join("")}
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/lottie.js
  function renderLottie(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const lottieWidth = settings2.width || 300;
    const lottieLoop = settings2.loop !== false;
    const lottieAutoplay = settings2.autoplay !== false;
    const lottieUrl = settings2.animation_url || "https://assets3.lottiefiles.com/packages/lf20_UJNc2t.json";
    return `<div style="text-align:center;padding:40px;background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);border-radius:12px">
                        <div style="width:${lottieWidth}px;max-width:100%;margin:0 auto;background:#ffffff;border-radius:8px;padding:30px;box-shadow:0 10px 30px rgba(0,0,0,0.2)">
                            <div style="position:relative;width:100%;aspect-ratio:1;display:flex;align-items:center;justify-content:center;overflow:hidden">
                                <svg width="100%" height="100%" viewBox="0 0 200 200" style="animation:lottieRotate 3s linear infinite">
                                    <circle cx="100" cy="100" r="80" fill="none" stroke="#667eea" stroke-width="8" stroke-dasharray="50 30" style="animation:lottieDash 2s linear infinite"/>
                                    <circle cx="100" cy="100" r="60" fill="none" stroke="#fa709a" stroke-width="6" stroke-dasharray="40 20" style="animation:lottieDash 2.5s linear infinite reverse"/>
                                    <circle cx="100" cy="100" r="40" fill="none" stroke="#4facfe" stroke-width="4" stroke-dasharray="30 10" style="animation:lottieDash 3s linear infinite"/>
                                    <path d="M 100 60 L 120 100 L 100 140 L 80 100 Z" fill="#fee140" style="animation:lottieScale 2s ease-in-out infinite"/>
                                </svg>
                                <style>
                                    @keyframes lottieRotate {
                                        from { transform: rotate(0deg); }
                                        to { transform: rotate(360deg); }
                                    }
                                    @keyframes lottieDash {
                                        from { stroke-dashoffset: 0; }
                                        to { stroke-dashoffset: 100; }
                                    }
                                    @keyframes lottieScale {
                                        0%, 100% { transform: scale(1); opacity: 1; }
                                        50% { transform: scale(1.2); opacity: 0.8; }
                                    }
                                </style>
                            </div>
                            <div style="margin-top:20px;padding-top:20px;border-top:2px solid #f0f0f0">
                                <div style="display:flex;justify-content:center;gap:15px;margin-bottom:10px">
                                    <span style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:${lottieLoop ? "#10b981" : "#e5e7eb"};color:${lottieLoop ? "#fff" : "#666"};border-radius:20px;font-size:12px;font-weight:600">
                                        <i class="fa fa-repeat"></i> ${lottieLoop ? "Loop ON" : "Loop OFF"}
                                    </span>
                                    <span style="display:inline-flex;align-items:center;gap:5px;padding:6px 12px;background:${lottieAutoplay ? "#3b82f6" : "#e5e7eb"};color:${lottieAutoplay ? "#fff" : "#666"};border-radius:20px;font-size:12px;font-weight:600">
                                        <i class="fa fa-play"></i> ${lottieAutoplay ? "Autoplay ON" : "Autoplay OFF"}
                                    </span>
                                </div>
                                <p style="margin:10px 0 0;color:#666;font-size:13px;font-weight:500">
                                    <i class="fa fa-film" style="color:#667eea;margin-right:5px"></i>
                                    Lottie Animation
                                </p>
                                <p style="margin:5px 0 0;color:#999;font-size:11px">
                                    ${lottieWidth}px \xD7 ${lottieLoop ? "Infinite" : "Once"} \xD7 ${lottieAutoplay ? "Auto" : "Manual"}
                                </p>
                            </div>
                        </div>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/map.js
  function renderMap(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const mapAddress = settings2.address || "Times Square, New York, NY, USA";
    const mapLat = settings2.latitude || "";
    const mapLon = settings2.longitude || "";
    const mapZoom = settings2.zoom || 12;
    const mapHeight = settings2.height || 400;
    const mapRadius = settings2.border_radius || 8;
    const mapMargin = settings2.margin || {
      top: 20,
      right: 0,
      bottom: 20,
      left: 0
    };
    let googleMapsUrl;
    const hasCoords = mapLat && mapLon;
    if (hasCoords) {
      googleMapsUrl = `https://maps.google.com/maps?q=${encodeURIComponent(mapLat + "," + mapLon)}&t=m&z=${mapZoom}&output=embed&iwloc=near`;
    } else {
      googleMapsUrl = `https://maps.google.com/maps?q=${encodeURIComponent(mapAddress)}&t=m&z=${mapZoom}&output=embed&iwloc=near`;
    }
    let mapHTML = `
                        <div class="probuilder-map-preview" style="
                            width: 100%;
                            height: ${mapHeight}px;
                            border-radius: ${mapRadius}px;
                            overflow: hidden;
                            margin: ${mapMargin.top}px ${mapMargin.right}px ${mapMargin.bottom}px ${mapMargin.left}px;
                            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                            position: relative;
                            background: #e5e7eb;
                        ">
                            <iframe 
                                width="100%" 
                                height="100%" 
                                frameborder="0" 
                                style="border:0; display: block; pointer-events: none;" 
                                src="${googleMapsUrl}"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                            ></iframe>
                        </div>
                        <div style="
                            font-size: 12px; 
                            color: #64748b; 
                            margin-top: 8px; 
                            display: flex; 
                            align-items: center; 
                            gap: 5px;
                            margin-left: ${mapMargin.left}px;
                        ">
                            <i class="fa fa-map-marker" style="color: #92003b;"></i>
                            <span>${mapAddress}</span>
                        </div>
                    `;
    return mapHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/mega-menu.js
  function renderMegaMenu(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/menu.js
  function renderMenu(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const menuLayout = settings2.layout || "horizontal";
    const menuItems = ["Home", "About", "Services", "Contact"];
    const menuStyle = menuLayout === "horizontal" ? "flex-direction:row" : "flex-direction:column";
    return `<nav style="display:flex;${menuStyle};gap:20px;list-style:none;padding:0;margin:0">
                        ${menuItems.map((item) => `<div style="padding:10px 15px;color:#333;cursor:pointer">${item}</div>`).join("")}
                    </nav>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/newsletter.js
  function renderNewsletter(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const newsTitle = settings2.title || "Subscribe to Our Newsletter";
    const newsDescription = settings2.description || "Get the latest updates and offers.";
    const newsPlaceholder = settings2.placeholder || "Enter your email";
    const newsButtonText = settings2.button_text || "Subscribe";
    const newsLayout = settings2.layout || "inline";
    const newsButtonColor = settings2.button_color || "#92003b";
    let newsHTML = `<div style="padding: 40px; background: #f9f9f9; border-radius: 8px; text-align: center;">`;
    newsHTML += `<div style="font-size: 48px; color: ${newsButtonColor}; margin-bottom: 20px; opacity: 0.8;"><i class="fa fa-envelope-open-text"></i></div>`;
    newsHTML += `<h3 style="margin: 0 0 10px 0; font-size: 24px; color: #333; font-weight: 600;">${newsTitle}</h3>`;
    if (newsDescription) {
      newsHTML += `<p style="margin: 0 0 25px 0; color: #666; font-size: 15px;">${newsDescription}</p>`;
    }
    const formStyle = newsLayout === "inline" ? "display: flex; gap: 10px;" : "display: flex; flex-direction: column; gap: 10px;";
    newsHTML += `<div style="${formStyle} max-width: 500px; margin: 0 auto;">`;
    newsHTML += `<input type="email" placeholder="${newsPlaceholder}" style="flex: 1; padding: 14px 20px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;" disabled>`;
    newsHTML += `<button type="button" style="background: ${newsButtonColor}; color: #fff; padding: 14px 35px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 14px; white-space: nowrap;">${newsButtonText}</button>`;
    newsHTML += `</div>`;
    newsHTML += `</div>`;
    return newsHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/notification.js
  function renderNotification(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const notifMessage = settings2.message || "Important announcement!";
    const notifType = settings2.type || "info";
    const notifDismissible = settings2.dismissible !== false;
    const notifColors = {
      info: {
        bg: "#2196f3",
        icon: "fa fa-info-circle"
      },
      success: {
        bg: "#4caf50",
        icon: "fa fa-check-circle"
      },
      warning: {
        bg: "#ff9800",
        icon: "fa fa-exclamation-triangle"
      },
      error: {
        bg: "#f44336",
        icon: "fa fa-times-circle"
      }
    };
    const notifColor = notifColors[notifType];
    return `<div style="background:${notifColor.bg};color:#fff;padding:15px 20px;border-radius:8px;position:relative;text-align:center">
                        <i class="${notifColor.icon}" style="margin-right:10px;font-size:18px"></i>
                        <strong>${notifMessage}</strong>
                        ${notifDismissible ? '<button style="position:absolute;right:15px;top:50%;transform:translateY(-50%);background:none;border:none;color:#fff;font-size:24px;cursor:pointer;line-height:1">\xD7</button>' : ""}
                        <div style="margin-top:10px;font-size:11px;opacity:0.8">
                            <i class="fa fa-info-circle"></i> Fixed ${settings2.position || "top"} notification bar
                        </div>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/offcanvas.js
  function renderOffcanvas(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const offcanvasPosition = settings2.position || "right";
    const offcanvasTrigger = settings2.trigger_text || "\u2630 Menu";
    const offcanvasWidth = settings2.panel_width || 300;
    const offcanvasBg = settings2.panel_bg || "#ffffff";
    return `<div style="padding:30px;background:#f5f5f5;border-radius:8px">
                        <button style="background:#0073aa;color:#fff;border:none;padding:12px 24px;border-radius:4px;cursor:pointer;font-weight:600;font-size:16px">
                            ${offcanvasTrigger}
                        </button>
                        <div style="margin-top:20px;padding:20px;background:${offcanvasBg};border:2px solid #ddd;border-radius:8px;position:relative">
                            <button style="position:absolute;top:10px;right:10px;background:none;border:none;font-size:24px;cursor:pointer;color:#999">\xD7</button>
                            <h4 style="margin:0 0 15px;font-size:18px">Offcanvas Panel Preview</h4>
                            <p style="margin:0;color:#666;font-size:14px;line-height:1.6">Panel slides in from ${offcanvasPosition}</p>
                            <p style="margin:10px 0 0;color:#999;font-size:12px">Width: ${offcanvasWidth}px</p>
                        </div>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/parallax-image.js
  function renderParallaxImage(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const parallaxImage = settings2.image || "https://via.placeholder.com/1200x800/93003c/ffffff?text=Parallax+Background";
    const parallaxHeight = settings2.height || 400;
    const parallaxSpeed = settings2.speed || 0.5;
    return `<div style="position:relative;overflow:hidden;height:${parallaxHeight}px;border-radius:8px">
                        <div style="background:url('${parallaxImage}') center/cover;height:150%;width:100%;position:absolute;top:-25%"></div>
                        <div style="position:relative;z-index:1;display:flex;align-items:center;justify-content:center;height:100%;color:#fff;text-shadow:0 2px 10px rgba(0,0,0,0.5)">
                            <div style="text-align:center">
                                <i class="fa fa-mountain" style="font-size:48px;margin-bottom:15px;display:block"></i>
                                <h3 style="margin:0 0 10px;font-size:28px;font-weight:700">Parallax Image</h3>
                                <p style="margin:0;font-size:14px">Speed: ${parallaxSpeed}x \xB7 Height: ${parallaxHeight}px</p>
                            </div>
                        </div>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/paypal-button.js
  function renderPaypalButton(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const paypalAmount = settings2.amount || 10;
    const paypalCurrency = settings2.currency || "USD";
    const paypalButtonText = settings2.button_text || "Buy Now";
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

  // wp-content/plugins/probuilder/assets/js/src/widgets/pie-chart.js
  function renderPieChart(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const pieTitle = settings2.chart_title || "Sales Distribution";
    const pieShowTitle = settings2.show_title !== "no";
    const pieHeight = settings2.chart_height || 400;
    const pieData = settings2.chart_data || "Product A, 30\nProduct B, 25\nProduct C, 20\nProduct D, 15\nProduct E, 10";
    const pieColorScheme = settings2.colors_scheme || "vibrant";
    const pieCustomColors = settings2.custom_colors || "#FF6384, #36A2EB, #FFCE56, #4BC0C0, #9966FF";
    const pieShowLegend = settings2.show_legend !== "no";
    const pieLegendPos = settings2.legend_position || "bottom";
    const pieLines = pieData.split("\n").filter((line) => line.trim());
    const pieLabels = [];
    const pieValues = [];
    pieLines.forEach((line) => {
      const parts = line.split(",").map((s) => s.trim());
      if (parts.length >= 2) {
        pieLabels.push(parts[0]);
        pieValues.push(parseFloat(parts[1]) || 0);
      }
    });
    const pieColorSchemes = {
      "vibrant": ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0", "#9966FF", "#FF9F40"],
      "pastel": ["#FFB3BA", "#BAFFC9", "#BAE1FF", "#FFFFBA", "#FFD4BA", "#E0BBE4"],
      "monochrome": ["#1a1a1a", "#333333", "#4d4d4d", "#666666", "#808080", "#999999"]
    };
    const pieColors = pieColorScheme === "custom" ? pieCustomColors.split(",").map((c) => c.trim()) : pieColorSchemes[pieColorScheme] || pieColorSchemes["vibrant"];
    const pieTotal = pieValues.reduce((sum, val) => sum + val, 0);
    let pieCurrentAngle = -90;
    let pieSVGPaths = "";
    pieValues.forEach((value, i) => {
      const percentage = value / pieTotal * 100;
      const angle = value / pieTotal * 360;
      const endAngle = pieCurrentAngle + angle;
      const startX = 100 + 80 * Math.cos(pieCurrentAngle * Math.PI / 180);
      const startY = 100 + 80 * Math.sin(pieCurrentAngle * Math.PI / 180);
      const endX = 100 + 80 * Math.cos(endAngle * Math.PI / 180);
      const endY = 100 + 80 * Math.sin(endAngle * Math.PI / 180);
      const largeArc = angle > 180 ? 1 : 0;
      const color = pieColors[i % pieColors.length];
      pieSVGPaths += `<path d="M 100 100 L ${startX} ${startY} A 80 80 0 ${largeArc} 1 ${endX} ${endY} Z" fill="${color}" stroke="#fff" stroke-width="2"/>`;
      pieCurrentAngle = endAngle;
    });
    let pieHTML = `<div style="padding: 20px; text-align: center;">`;
    if (pieShowTitle && pieTitle) {
      pieHTML += `<h3 style="margin-bottom: 20px; font-size: 24px; font-weight: 600;">${pieTitle}</h3>`;
    }
    const legendHTML = pieShowLegend ? `
                        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 15px; margin: 15px 0;">
                            ${pieLabels.map((label, i) => `
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 12px; height: 12px; background: ${pieColors[i % pieColors.length]}; border-radius: 2px;"></div>
                                    <span style="font-size: 13px; color: #666;">${label}</span>
                                </div>
                            `).join("")}
                        </div>
                    ` : "";
    if (pieLegendPos === "top") pieHTML += legendHTML;
    pieHTML += `<div style="display: flex; justify-content: center; align-items: center; height: ${pieHeight}px; background: #f9f9f9; border-radius: 8px;">`;
    pieHTML += `<svg width="250" height="250" viewBox="0 0 200 200">${pieSVGPaths}</svg>`;
    pieHTML += `</div>`;
    if (pieLegendPos === "bottom") pieHTML += legendHTML;
    pieHTML += `</div>`;
    return pieHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/portfolio.js
  function renderPortfolio(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const portfolioItems = settings2.items || [{
      title: "Project 1",
      image: "https://via.placeholder.com/400x300/93003c/ffffff?text=Project+1",
      category: "Design"
    }, {
      title: "Project 2",
      image: "https://via.placeholder.com/400x300/0073aa/ffffff?text=Project+2",
      category: "Development"
    }, {
      title: "Project 3",
      image: "https://via.placeholder.com/400x300/4caf50/ffffff?text=Project+3",
      category: "Branding"
    }];
    const portfolioColumns = settings2.columns || "3";
    let portfolioHTML = `<div style="display:grid;grid-template-columns:repeat(${portfolioColumns},1fr);gap:20px">`;
    portfolioItems.forEach((item) => {
      portfolioHTML += `
                            <div style="position:relative;overflow:hidden;border-radius:8px;cursor:pointer">
                                <img src="${item.image || "https://via.placeholder.com/400x300"}" style="width:100%;height:200px;object-fit:cover;display:block">
                                <div style="position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.7);display:flex;align-items:center;justify-content:center;flex-direction:column;color:#fff;opacity:0;transition:opacity 0.3s" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                                    <h3 style="margin:0 0 8px;font-size:20px;color:#fff">${item.title || "Project"}</h3>
                                    <p style="margin:0;color:#ccc;font-size:14px">${item.category || "Category"}</p>
                                </div>
                            </div>
                        `;
    });
    portfolioHTML += "</div>";
    return portfolioHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/post-author.js
  function renderPostAuthor(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const authorShowAvatar = settings2.show_avatar !== false;
    const authorAvatarSize = settings2.avatar_size || 32;
    const authorLink = settings2.link !== false;
    return `<div style="display:flex;align-items:center;gap:10px">
                        ${authorShowAvatar ? `<div style="width:${authorAvatarSize}px;height:${authorAvatarSize}px;background:#ddd;border-radius:50%"></div>` : ""}
                        ${authorLink ? '<a href="#" style="color:#333;text-decoration:none;font-weight:500">Author Name</a>' : '<span style="color:#333;font-weight:500">Author Name</span>'}
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/post-comments.js
  function renderPostComments(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const commentsShowCount = settings2.show_count !== false;
    return `<div style="background:#f9f9f9;padding:20px;border-radius:8px">
                        ${commentsShowCount ? '<h3 style="margin:0 0 20px;font-size:24px;color:#333">5 Comments</h3>' : ""}
                        <div style="border-left:3px solid #0073aa;padding-left:15px;margin-bottom:20px">
                            <div style="display:flex;gap:10px;margin-bottom:10px">
                                <div style="width:40px;height:40px;background:#ddd;border-radius:50%;flex-shrink:0"></div>
                                <div>
                                    <strong style="color:#333">John Doe</strong>
                                    <p style="margin:5px 0 0;color:#666;font-size:14px">This is a sample comment on the post.</p>
                                </div>
                            </div>
                        </div>
                        <div style="border:1px solid #ddd;border-radius:4px;padding:15px">
                            <textarea placeholder="Leave a comment..." style="width:100%;border:1px solid #ddd;border-radius:4px;padding:10px;font-size:14px;min-height:80px"></textarea>
                            <button style="background:#0073aa;color:#fff;border:none;padding:10px 20px;border-radius:4px;cursor:pointer;margin-top:10px">Post Comment</button>
                        </div>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/post-date.js
  function renderPostDate(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const dateFormat = settings2.format || "F j, Y";
    const dateShowIcon = settings2.show_icon !== false;
    const dateColor = settings2.color || "#666";
    return `<div style="color:${dateColor};font-size:14px;display:flex;align-items:center;gap:8px">
                        ${dateShowIcon ? '<i class="fa fa-calendar-alt"></i>' : ""}
                        <span>October 25, 2025</span>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/post-excerpt.js
  function renderPostExcerpt(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const excerptLength = settings2.length || 55;
    const excerptShowMore = settings2.show_more !== false;
    const excerptMoreText = settings2.more_text || "Read More";
    return `<div style="color:#666;line-height:1.6;font-size:16px">
                        <p style="margin:0">This is a sample post excerpt that will display the first ${excerptLength} words of the post content. It provides a preview of the article...</p>
                        ${excerptShowMore ? `<a href="#" style="color:#0073aa;text-decoration:none;font-weight:600;margin-top:10px;display:inline-block">${excerptMoreText} \u2192</a>` : ""}
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/post-featured-image.js
  function renderPostFeaturedImage(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const featuredImageSize = settings2.size || "full";
    const featuredBorderRadius = settings2.border_radius || 0;
    const featuredLink = settings2.link !== false;
    const featuredImageHTML = `<img src="https://via.placeholder.com/800x600/93003c/ffffff?text=Featured+Image" alt="Featured Image" style="width:100%;height:auto;border-radius:${featuredBorderRadius}px;display:block">`;
    return featuredLink ? `<a href="#">${featuredImageHTML}</a>` : featuredImageHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/post-navigation.js
  function renderPostNavigation(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    return `<div style="display:flex;gap:20px">
                        <div style="flex:1;background:#f9f9f9;padding:20px;border-radius:8px">
                            <div style="color:#0073aa;font-size:12px;margin-bottom:5px">\u2190 Previous</div>
                            <h4 style="margin:0;font-size:16px">Previous Post Title</h4>
                        </div>
                        <div style="flex:1;background:#f9f9f9;padding:20px;border-radius:8px;text-align:right">
                            <div style="color:#0073aa;font-size:12px;margin-bottom:5px">Next \u2192</div>
                            <h4 style="margin:0;font-size:16px">Next Post Title</h4>
                        </div>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/post-title.js
  function renderPostTitle(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const postTitleTag = settings2.tag || "h1";
    const postTitleColor = settings2.color || "#333";
    const postTitleSize = settings2.size || 36;
    return `<${postTitleTag} style="color:${postTitleColor};font-size:${postTitleSize}px;margin:0;font-weight:700">Post Title Will Appear Here</${postTitleTag}>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/price-list.js
  function renderPriceList(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const priceItems = settings2.items || [{
      title: "Service 1",
      price: "$50",
      description: "Description"
    }];
    return priceItems.map((item) => `
                        <div style="display:flex;justify-content:space-between;padding:20px 0;border-bottom:1px solid #eee">
                            <div><h4 style="margin:0 0 8px;font-size:18px">${item.title || "Service"}</h4>
                            <p style="margin:0;color:#666;font-size:14px">${item.description || ""}</p></div>
                            <div style="font-size:20px;font-weight:700;color:#0073aa">${item.price || "$50"}</div>
                        </div>
                    `).join("");
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/pricing-table.js
  function renderPricingTable(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const priceTitle = settings2.title || "Basic Plan";
    const priceCurrency = settings2.currency || "$";
    const priceAmount = settings2.price || "29";
    const pricePeriod = settings2.period || "per month";
    const priceFeatures = Array.isArray(settings2.features) ? settings2.features : [{
      text: "Feature 1"
    }, {
      text: "Feature 2"
    }, {
      text: "Feature 3"
    }];
    const priceButtonText = settings2.button_text || "Get Started";
    const priceFeatured = settings2.featured === "yes";
    const pricingBoxStyle = `
                        border: 2px solid ${priceFeatured ? "#0073aa" : "#e5e5e5"};
                        padding: 40px 30px;
                        text-align: center;
                        background: #ffffff;
                        position: relative;
                        border-radius: 8px;
                        transition: all 0.3s;
                        min-height: 400px;
                    `;
    let pricingHTML = `<div style="${pricingBoxStyle}">`;
    if (priceFeatured) {
      pricingHTML += `
                            <div style="
                                position: absolute;
                                top: 20px;
                                right: 20px;
                                background: #0073aa;
                                color: white;
                                padding: 5px 15px;
                                border-radius: 20px;
                                font-size: 12px;
                                font-weight: bold;
                            ">POPULAR</div>
                        `;
    }
    pricingHTML += `<h3 style="font-size: 24px; margin: 0 0 20px 0; font-weight: 600;">${priceTitle}</h3>`;
    pricingHTML += `
                        <div style="margin-bottom: 30px;">
                            <span style="font-size: 24px; vertical-align: top; font-weight: 600;">${priceCurrency}</span>
                            <span style="font-size: 60px; font-weight: bold; line-height: 1; color: #333;">${priceAmount}</span>
                            <div style="color: #666; font-size: 14px; margin-top: 5px;">${pricePeriod}</div>
                        </div>
                    `;
    pricingHTML += '<ul style="list-style: none; margin: 0 0 30px 0; padding: 0; text-align: left;">';
    priceFeatures.forEach((feature) => {
      pricingHTML += `
                            <li style="
                                padding: 10px 0;
                                border-bottom: 1px solid #f0f0f0;
                                color: #555;
                                position: relative;
                                padding-left: 25px;
                            ">
                                <i class="dashicons dashicons-yes" style="
                                    position: absolute;
                                    left: 0;
                                    top: 10px;
                                    color: #0073aa;
                                    font-size: 18px;
                                "></i>
                                ${feature.text || "Feature"}
                            </li>
                        `;
    });
    pricingHTML += "</ul>";
    pricingHTML += `
                        <a href="#" style="
                            background: ${priceFeatured ? "#0073aa" : "#333333"};
                            color: white;
                            padding: 15px 40px;
                            text-decoration: none;
                            display: inline-block;
                            border-radius: 5px;
                            font-weight: bold;
                            transition: all 0.3s;
                        " onmouseover="this.style.opacity='0.9'; this.style.transform='translateY(-2px)'" onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)'">
                            ${priceButtonText}
                        </a>
                    `;
    pricingHTML += "</div>";
    return pricingHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/progress-bar.js
  function renderProgressBar(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const progTitle = settings2.title || "My Skill";
    const progPercentage = settings2.percentage || 75;
    const progShowPercentage = settings2.show_percentage !== "no";
    const progInnerText = settings2.inner_text || "";
    const progBarStyle = settings2.bar_style || "solid";
    const progBarColor = settings2.bar_color || "#92003b";
    const progBarGradient = settings2.bar_gradient || "linear-gradient(90deg, #92003b 0%, #c44569 100%)";
    const progBgColor = settings2.bg_color || "#e5e7eb";
    const progHeight = settings2.height || 30;
    const progBorderRadius = settings2.border_radius || 15;
    const progTitleColor = settings2.title_color || "#333333";
    const progPercentageColor = settings2.percentage_color || "#333333";
    const progInnerTextColor = settings2.inner_text_color || "#ffffff";
    let progressHTML = `<div style="margin: 15px 0;">`;
    progressHTML += `<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">`;
    progressHTML += `<span style="font-weight: 600; font-size: 15px; color: ${progTitleColor};">${progTitle}</span>`;
    if (progShowPercentage) {
      progressHTML += `<span style="font-weight: 700; font-size: 15px; color: ${progPercentageColor};">${progPercentage}%</span>`;
    }
    progressHTML += `</div>`;
    progressHTML += `<div style="position: relative; background: ${progBgColor}; height: ${progHeight}px; border-radius: ${progBorderRadius}px; overflow: hidden; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">`;
    let barFillStyle = `height: 100%; width: ${progPercentage}%; display: flex; align-items: center; padding: 0 15px; box-sizing: border-box;`;
    if (progBarStyle === "solid") {
      barFillStyle += ` background: ${progBarColor};`;
    } else if (progBarStyle === "gradient") {
      barFillStyle += ` background: ${progBarGradient};`;
    } else if (progBarStyle === "striped" || progBarStyle === "animated") {
      barFillStyle += ` background: ${progBarColor};`;
      barFillStyle += ` background-image: linear-gradient(45deg, rgba(255,255,255,.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,.15) 50%, rgba(255,255,255,.15) 75%, transparent 75%, transparent);`;
      barFillStyle += ` background-size: 20px 20px;`;
    }
    progressHTML += `<div style="${barFillStyle}">`;
    if (progInnerText) {
      progressHTML += `<span style="font-size: 13px; font-weight: 600; color: ${progInnerTextColor}; white-space: nowrap;">${progInnerText}</span>`;
    }
    progressHTML += `</div>`;
    progressHTML += `</div>`;
    progressHTML += `</div>`;
    return progressHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/progress-tracker.js
  function renderProgressTracker(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const trackerSteps = settings2.steps || [{
      title: "Step 1",
      complete: true
    }, {
      title: "Step 2",
      complete: true
    }, {
      title: "Step 3",
      complete: false
    }];
    const trackerOrientation = settings2.orientation || "horizontal";
    const trackerActiveColor = settings2.active_color || "#4caf50";
    const trackerInactiveColor = settings2.inactive_color || "#cccccc";
    if (trackerOrientation === "horizontal") {
      return `<div style="display:flex;align-items:center;gap:10px">
                            ${trackerSteps.map((step, i) => `
                                <div style="display:flex;align-items:center;gap:10px">
                                    <div style="display:flex;flex-direction:column;align-items:center">
                                        <div style="width:40px;height:40px;border-radius:50%;background:${step.complete ? trackerActiveColor : trackerInactiveColor};color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700">${i + 1}</div>
                                        <span style="margin-top:8px;font-size:14px;color:#333">${step.title || "Step " + (i + 1)}</span>
                                    </div>
                                    ${i < trackerSteps.length - 1 ? `<div style="flex:1;min-width:50px;height:2px;background:${step.complete ? trackerActiveColor : trackerInactiveColor}"></div>` : ""}
                                </div>
                            `).join("")}
                        </div>`;
    } else {
      return `<div style="display:flex;flex-direction:column;gap:10px">
                            ${trackerSteps.map((step, i) => `
                                <div style="display:flex;flex-direction:column;gap:10px">
                                    <div style="display:flex;align-items:center;gap:10px">
                                        <div style="width:40px;height:40px;border-radius:50%;background:${step.complete ? trackerActiveColor : trackerInactiveColor};color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700">${i + 1}</div>
                                        <span style="font-size:14px;color:#333">${step.title || "Step " + (i + 1)}</span>
                                    </div>
                                    ${i < trackerSteps.length - 1 ? `<div style="width:2px;height:30px;background:${step.complete ? trackerActiveColor : trackerInactiveColor};margin-left:19px"></div>` : ""}
                                </div>
                            `).join("")}
                        </div>`;
    }
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/reading-progress.js
  function renderReadingProgress(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const progressPosition = settings2.position || "top";
    const progressHeight = settings2.height || 4;
    const progressColor = settings2.color || "#0073aa";
    const progressBg = settings2.background || "#eeeeee";
    return `<div style="background:#f5f5f5;padding:30px;border-radius:8px;text-align:center">
                        <div style="background:${progressBg};height:${progressHeight}px;border-radius:${progressHeight}px;overflow:hidden;margin-bottom:15px">
                            <div style="width:60%;height:100%;background:${progressColor};transition:width 0.3s"></div>
                        </div>
                        <p style="margin:0;color:#666;font-size:14px">
                            <i class="fa fa-scroll" style="color:${progressColor};margin-right:8px"></i>
                            Reading Progress Bar (Fixed ${progressPosition === "top" ? "Top" : "Top"})
                        </p>
                        <small style="color:#999">Scrolls with page progress</small>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/recent-posts.js
  function renderRecentPosts(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const recentLimit = settings2.limit || 5;
    const recentShowImage = settings2.show_image !== false;
    const recentShowDate = settings2.show_date !== false;
    return `<div style="background:#f9f9f9;padding:20px;border-radius:8px">
                        <h4 style="margin:0 0 15px;font-size:18px;color:#333">Recent Posts</h4>
                        ${[1, 2, 3].map((i) => `
                            <div style="display:flex;gap:10px;margin-bottom:15px;align-items:center">
                                ${recentShowImage ? '<div style="width:60px;height:60px;background:#ddd;border-radius:4px;flex-shrink:0"></div>' : ""}
                                <div>
                                    <a href="#" style="color:#333;text-decoration:none;font-weight:600;display:block;margin-bottom:4px">Recent Post ${i}</a>
                                    ${recentShowDate ? '<div style="font-size:12px;color:#999">October 25, 2025</div>' : ""}
                                </div>
                            </div>
                        `).join("")}
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/reviews.js
  function renderReviews(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const reviewItems = settings2.reviews || [{
      name: "John Doe",
      rating: 5,
      review: "Excellent service!"
    }, {
      name: "Jane Smith",
      rating: 5,
      review: "Highly recommended!"
    }];
    const reviewColumns = settings2.columns || "2";
    let reviewsHTML = `<div style="display:grid;grid-template-columns:repeat(${reviewColumns},1fr);gap:20px">`;
    reviewItems.forEach((review) => {
      const stars = "\u2605".repeat(review.rating || 5) + "\u2606".repeat(5 - (review.rating || 5));
      reviewsHTML += `
                            <div style="background:#f9f9f9;padding:20px;border-radius:8px">
                                <div style="color:#ffc107;font-size:18px;margin-bottom:10px">${stars}</div>
                                <h4 style="margin:0 0 10px;font-size:18px">${review.name || "Customer"}</h4>
                                <p style="margin:0;color:#666;line-height:1.6">${review.review || "Great experience!"}</p>
                            </div>
                        `;
    });
    reviewsHTML += "</div>";
    return reviewsHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/scroll-snap.js
  function renderScrollSnap(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const snapSections = settings2.sections || [{
      title: "Section 1",
      content: "Content 1",
      bg: "#f5f5f5"
    }, {
      title: "Section 2",
      content: "Content 2",
      bg: "#e5e5e5"
    }];
    return `<div style="border:2px dashed #ddd;border-radius:8px;padding:20px;background:#fafafa">
                        <div style="text-align:center;margin-bottom:20px">
                            <i class="fa fa-layer-group" style="font-size:32px;color:#93003c;margin-bottom:10px"></i>
                            <h4 style="margin:0 0 5px;font-size:18px">Scroll Snap Container</h4>
                            <small style="color:#999">${snapSections.length} full-height sections</small>
                        </div>
                        ${snapSections.map((section, i) => `
                            <div style="background:${section.bg || "#f5f5f5"};padding:20px;border-radius:6px;margin-bottom:10px;border-left:4px solid #0073aa">
                                <h5 style="margin:0 0 8px;font-size:16px">${section.title || "Section " + (i + 1)}</h5>
                                <p style="margin:0;color:#666;font-size:14px">${section.content || "Section content"}</p>
                            </div>
                        `).join("")}
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/search-form.js
  function renderSearchForm(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    return `<div style="display:flex;gap:0;max-width:600px">
                        <input type="text" placeholder="${settings2.placeholder || "Search..."}" style="flex:1;padding:12px 15px;border:1px solid #ddd;border-radius:4px 0 0 4px;font-size:16px">
                        <button style="background:${settings2.button_color || "#0073aa"};color:#fff;border:none;padding:12px 24px;border-radius:0 4px 4px 0;cursor:pointer;font-weight:600">${settings2.button_text || "Search"}</button>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/share-buttons.js
  function renderShareButtons(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const networks = {
      facebook: {
        color: "#1877f2",
        icon: "fa fa-facebook-f"
      },
      twitter: {
        color: "#1da1f2",
        icon: "fa fa-twitter"
      },
      linkedin: {
        color: "#0077b5",
        icon: "fa fa-linkedin-in"
      },
      pinterest: {
        color: "#bd081c",
        icon: "fa fa-pinterest-p"
      }
    };
    let shareHTML = '<div style="display:flex;gap:10px">';
    Object.keys(networks).forEach((network) => {
      shareHTML += `<div style="width:40px;height:40px;background:${networks[network].color};color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center"><i class="${networks[network].icon}"></i></div>`;
    });
    shareHTML += "</div>";
    return shareHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/shortcode.js
  function renderShortcode(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const shortcodeText = settings2.shortcode || "";
    const shortcodeBgColor = settings2.bg_color || "";
    const shortcodePadding = settings2.padding || {
      top: 20,
      right: 20,
      bottom: 20,
      left: 20
    };
    const shortcodeTextAlign = settings2.text_align || "left";
    let shortcodeHTML = `<div style="
                        ${shortcodeBgColor ? `background: ${shortcodeBgColor};` : ""}
                        padding: ${shortcodePadding.top}px ${shortcodePadding.right}px ${shortcodePadding.bottom}px ${shortcodePadding.left}px;
                        text-align: ${shortcodeTextAlign};
                    ">`;
    if (!shortcodeText || shortcodeText.trim() === "" || shortcodeText === '[contact-form-7 id="123"]') {
      shortcodeHTML += `
                            <div style="padding: 30px; background: #fff3cd; border: 2px dashed #ffc107; border-radius: 8px; text-align: center; color: #856404;">
                                <i class="fa fa-code" style="font-size: 48px; margin-bottom: 15px; opacity: 0.6;"></i>
                                <div style="font-size: 16px; font-weight: 600; margin-bottom: 10px;">Shortcode Widget</div>
                                <div style="font-size: 14px;">Enter a shortcode in the settings to display content</div>
                                <div style="margin-top: 15px; padding: 10px; background: rgba(255,255,255,0.5); border-radius: 4px; font-size: 13px;">
                                    <strong>Examples:</strong><br>
                                    [gallery]<br>
                                    [contact-form-7 id="123"]<br>
                                    [woocommerce_cart]
                                </div>
                            </div>
                        `;
    } else {
      shortcodeHTML += `
                            <div style="padding: 25px; background: #f0f7ff; border: 2px solid #2196f3; border-radius: 8px; text-align: center;">
                                <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 15px;">
                                    <i class="fa fa-code" style="font-size: 32px; color: #2196f3;"></i>
                                    <div style="font-size: 18px; font-weight: 600; color: #1976d2;">Shortcode Will Execute Here</div>
                                </div>
                                <div style="background: white; padding: 12px 20px; border-radius: 6px; border: 1px solid #bbdefb; margin-top: 15px;">
                                    <code style="color: #d32f2f; font-size: 14px; font-family: 'Courier New', monospace;">${shortcodeText}</code>
                                </div>
                                <div style="font-size: 12px; color: #64748b; margin-top: 12px; opacity: 0.8;">
                                    Preview not available in editor - actual output will show on frontend
                                </div>
                            </div>
                        `;
    }
    shortcodeHTML += `</div>`;
    return shortcodeHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/sidebar.js
  function renderSidebar(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const sidebarName = settings2.sidebar || "sidebar-1";
    return `<div style="padding: 20px; background: #f5f5f5; border: 1px solid #ddd; border-radius: 8px;">
                        <div style="margin-bottom: 20px; padding: 15px; background: #fff; border-left: 4px solid #92003b;">
                            <h4 style="margin: 0 0 5px; font-size: 16px;">Widget Area</h4>
                            <p style="margin: 0; font-size: 12px; color: #666;">Search Widget</p>
                        </div>
                        <div style="margin-bottom: 20px; padding: 15px; background: #fff; border-left: 4px solid #0073aa;">
                            <h4 style="margin: 0 0 5px; font-size: 16px;">Recent Posts</h4>
                            <p style="margin: 0; font-size: 12px; color: #666;">Latest blog entries</p>
                        </div>
                        <div style="padding: 15px; background: #fff; border-left: 4px solid #46b450;">
                            <h4 style="margin: 0 0 5px; font-size: 16px;">Categories</h4>
                            <p style="margin: 0; font-size: 12px; color: #666;">Post categories</p>
                        </div>
                        <p style="margin-top: 15px; text-align: center; color: #666; font-size: 11px;">
                            <i class="fa fa-columns"></i> WordPress Sidebar: ${sidebarName}
                        </p>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/site-logo.js
  function renderSiteLogo(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const siteLogoWidth = settings2.width || 150;
    const siteLogoImage = settings2.logo || "https://via.placeholder.com/300x100/93003c/ffffff?text=Site+Logo";
    const siteLogoLink = settings2.link !== false;
    const siteLogoHTML = `<img src="${siteLogoImage}" alt="Site Logo" style="width:${siteLogoWidth}px;height:auto;display:block">`;
    return siteLogoLink ? `<a href="#">${siteLogoHTML}</a>` : siteLogoHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/sitemap.js
  function renderSitemap(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    return `<div style="display:grid;grid-template-columns:repeat(${settings2.columns || 3},1fr);gap:30px">
                        <div><h3 style="margin:0 0 15px">Pages</h3><ul style="list-style:none;padding:0;line-height:2"><li><a href="#" style="color:#0073aa">Home</a></li><li><a href="#" style="color:#0073aa">About</a></li><li><a href="#" style="color:#0073aa">Contact</a></li></ul></div>
                        <div><h3 style="margin:0 0 15px">Posts</h3><ul style="list-style:none;padding:0;line-height:2"><li><a href="#" style="color:#0073aa">Recent Post 1</a></li><li><a href="#" style="color:#0073aa">Recent Post 2</a></li><li><a href="#" style="color:#0073aa">Recent Post 3</a></li></ul></div>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/slider.js
  function renderSlider(context2) {
    var _a;
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const slSlides = settings2.slides || [{
      image: {
        url: "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200"
      },
      title: "Welcome to Our Website",
      description: "Discover amazing products and services",
      button_text: "Get Started",
      content_position: "center"
    }];
    const slSliderStyle = settings2.slider_style || "classic";
    const slSliderHeight = settings2.slider_height || {
      size: 500
    };
    const slShowArrows = settings2.show_arrows !== "no";
    const slShowDots = settings2.show_dots !== "no";
    const slShowProgressBar = settings2.show_progress_bar === "yes";
    const slShowFraction = settings2.show_fraction === "yes";
    const slAutoplay = settings2.autoplay !== "no";
    const slAutoplaySpeed = ((_a = settings2.autoplay_speed) == null ? void 0 : _a.size) || 5;
    const slTransitionSpeed = settings2.transition_speed || 500;
    const slOverlayType = settings2.overlay_type || "color";
    const slOverlayColor = settings2.overlay_color || "rgba(0,0,0,0.4)";
    const slOverlayGradStart = settings2.overlay_gradient_start || "rgba(0,0,0,0.6)";
    const slOverlayGradEnd = settings2.overlay_gradient_end || "rgba(0,0,0,0.2)";
    const slTitleColor = settings2.title_color || "#ffffff";
    const slTitleSize = settings2.title_size || 48;
    const slTitleWeight = settings2.title_weight || "700";
    const slDescColor = settings2.description_color || "#ffffff";
    const slDescSize = settings2.description_size || 18;
    const slContentMaxWidth = settings2.content_max_width || 600;
    const slContentBgEnable = settings2.content_bg_enable === "yes";
    const slContentBgColor = settings2.content_bg_color || "rgba(255,255,255,0.1)";
    const slContentBgBlur = settings2.content_bg_blur === "yes";
    const slButtonBgColor = settings2.button_bg_color || "#92003b";
    const slButtonTextColor = settings2.button_text_color || "#ffffff";
    const slButtonSize = settings2.button_size || "medium";
    const slButtonRadius = settings2.button_border_radius || 5;
    const slArrowStyle = settings2.arrow_style || "circle";
    const slArrowSize = settings2.arrow_size || 50;
    const slArrowColor = settings2.arrow_color || "#ffffff";
    const slArrowBgColor = settings2.arrow_bg_color || "rgba(0,0,0,0.5)";
    const slDotStyle = settings2.dot_style || "circle";
    const slDotSize = settings2.dot_size || 12;
    const slDotColor = settings2.dot_color || "rgba(255,255,255,0.5)";
    const slActiveDotColor = settings2.active_dot_color || "#ffffff";
    const slDotPosition = settings2.dot_position || "bottom-center";
    const slContentAnimation = settings2.content_animation || "fade-up";
    const slAnimationDelay = settings2.animation_delay || 200;
    const heightValue = slSliderHeight.size || 500;
    const buttonPadding = slButtonSize === "small" ? "10px 20px" : slButtonSize === "large" ? "18px 40px" : "15px 30px";
    const buttonFontSize = slButtonSize === "small" ? "14px" : slButtonSize === "large" ? "18px" : "16px";
    const arrowBorderRadius = slArrowStyle === "circle" ? "50%" : slArrowStyle === "rounded" ? "8px" : slArrowStyle === "square" ? "0" : "50%";
    const arrowBackground = slArrowStyle === "chevron" || slArrowStyle === "minimal" ? "transparent" : slArrowBgColor;
    const arrowButtonSize = slArrowSize + "px";
    const arrowFontSize = slArrowSize / 2 + "px";
    const dotBorderRadius = slDotStyle === "circle" ? "50%" : slDotStyle === "square" ? "0" : "2px";
    const dotWidth = slDotStyle === "line" ? slDotSize * 3 + "px" : slDotStyle === "dash" ? slDotSize * 2 + "px" : slDotSize + "px";
    const dotHeight = slDotStyle === "line" ? slDotSize / 2 + "px" : slDotStyle === "dash" ? slDotSize / 2 + "px" : slDotSize + "px";
    const sliderId = "pb-slider-" + Date.now() + "-" + Math.random().toString(36).substr(2, 9);
    let slSliderHTML = `
                    <style>
                        @keyframes pb-animate-fade-up {
                            from { opacity: 0; transform: translateY(30px); }
                            to { opacity: 1; transform: translateY(0); }
                        }
                        @keyframes pb-animate-fade-down {
                            from { opacity: 0; transform: translateY(-30px); }
                            to { opacity: 1; transform: translateY(0); }
                        }
                        @keyframes pb-animate-fade-left {
                            from { opacity: 0; transform: translateX(-30px); }
                            to { opacity: 1; transform: translateX(0); }
                        }
                        @keyframes pb-animate-fade-right {
                            from { opacity: 0; transform: translateX(30px); }
                            to { opacity: 1; transform: translateX(0); }
                        }
                        @keyframes pb-animate-zoom-in {
                            from { opacity: 0; transform: scale(0.8); }
                            to { opacity: 1; transform: scale(1); }
                        }
                        @keyframes pb-animate-zoom-out {
                            from { opacity: 0; transform: scale(1.2); }
                            to { opacity: 1; transform: scale(1); }
                        }
                        @keyframes pb-animate-flip-up {
                            from { opacity: 0; transform: perspective(400px) rotateX(90deg); }
                            to { opacity: 1; transform: perspective(400px) rotateX(0deg); }
                        }
                        @keyframes pb-animate-none {
                            from { opacity: 1; }
                            to { opacity: 1; }
                        }
                    </style>
                    <div id="${sliderId}" class="pb-slider-preview" style="position: relative; height: ${heightValue}px; border-radius: 8px; overflow: hidden;">`;
    slSliderHTML += `<div class="pb-slides" style="position: relative; width: 100%; height: 100%;">`;
    slSlides.forEach((slide, index) => {
      var _a2;
      const slideImage = ((_a2 = slide.image) == null ? void 0 : _a2.url) || "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200";
      const slideTitle = slide.title || "Slide Title";
      const slideDesc = slide.description || "Slide description goes here...";
      const slideButton = slide.button_text || "Learn More";
      const slidePosition = slide.content_position || "center";
      const contentAlign = slidePosition === "left" ? "flex-start" : slidePosition === "right" ? "flex-end" : "center";
      let overlayStyle = "";
      if (slOverlayType === "gradient") {
        overlayStyle = `background: linear-gradient(135deg, ${slOverlayGradStart}, ${slOverlayGradEnd});`;
      } else if (slOverlayType === "color") {
        overlayStyle = `background-color: ${slOverlayColor};`;
      }
      let contentContainerStyle = `max-width: ${slContentMaxWidth}px; padding: 40px; text-align: ${slidePosition};`;
      if (slContentBgEnable) {
        contentContainerStyle += ` background-color: ${slContentBgColor}; border-radius: 12px;`;
        if (slContentBgBlur) {
          contentContainerStyle += ` backdrop-filter: blur(10px);`;
        }
      }
      const isActive = index === 0;
      const displayStyle = isActive ? "flex" : "none";
      slSliderHTML += `<div class="pb-slide" data-slide-index="${index}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('${slideImage}'); background-size: cover; background-position: center; display: ${displayStyle}; align-items: center; justify-content: ${contentAlign}; transition: opacity ${slTransitionSpeed}ms ease-in-out;">`;
      if (slOverlayType !== "none") {
        slSliderHTML += `<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; ${overlayStyle}"></div>`;
      }
      const animationClass = `pb-animate-${slContentAnimation}`;
      const animationStyle = isActive ? `animation: ${animationClass} 0.8s ease-out ${slAnimationDelay}ms both;` : "";
      slSliderHTML += `<div class="pb-slide-content ${animationClass}" data-animation="${slContentAnimation}" style="position: relative; z-index: 2; ${contentContainerStyle} ${animationStyle}">`;
      if (slideTitle) {
        const titleAnimDelay = isActive ? slAnimationDelay + 100 : 0;
        slSliderHTML += `<h2 style="color: ${slTitleColor}; font-size: ${slTitleSize}px; font-weight: ${slTitleWeight}; margin: 0 0 20px 0; line-height: 1.2; text-shadow: 0 2px 10px rgba(0,0,0,0.3); animation: ${animationClass} 0.8s ease-out ${titleAnimDelay}ms both;">${slideTitle}</h2>`;
      }
      if (slideDesc) {
        const descAnimDelay = isActive ? slAnimationDelay + 200 : 0;
        slSliderHTML += `<p style="color: ${slDescColor}; font-size: ${slDescSize}px; margin: 0 0 30px 0; line-height: 1.6; animation: ${animationClass} 0.8s ease-out ${descAnimDelay}ms both;">${slideDesc}</p>`;
      }
      if (slideButton) {
        const btnAnimDelay = isActive ? slAnimationDelay + 300 : 0;
        slSliderHTML += `<a href="#" onclick="return false;" style="display: inline-block; background-color: ${slButtonBgColor}; color: ${slButtonTextColor}; padding: ${buttonPadding}; text-decoration: none; border-radius: ${slButtonRadius}px; font-weight: 600; font-size: ${buttonFontSize}; box-shadow: 0 4px 10px rgba(0,0,0,0.2); animation: ${animationClass} 0.8s ease-out ${btnAnimDelay}ms both;">${slideButton}</a>`;
      }
      slSliderHTML += `</div>`;
      slSliderHTML += `</div>`;
    });
    slSliderHTML += `</div>`;
    if (slShowArrows) {
      slSliderHTML += `<button class="pb-slider-prev" onclick="pbSliderPrev('${sliderId}')" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); background: ${arrowBackground}; border: none; color: ${slArrowColor}; font-size: ${arrowFontSize}; width: ${arrowButtonSize}; height: ${arrowButtonSize}; border-radius: ${arrowBorderRadius}; cursor: pointer; z-index: 3; display: flex; align-items: center; justify-content: center; padding: 0; line-height: 1;">\u2039</button>`;
      slSliderHTML += `<button class="pb-slider-next" onclick="pbSliderNext('${sliderId}')" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); background: ${arrowBackground}; border: none; color: ${slArrowColor}; font-size: ${arrowFontSize}; width: ${arrowButtonSize}; height: ${arrowButtonSize}; border-radius: ${arrowBorderRadius}; cursor: pointer; z-index: 3; display: flex; align-items: center; justify-content: center; padding: 0; line-height: 1;">\u203A</button>`;
    }
    if (slShowDots) {
      const dotBottom = slDotPosition.includes("bottom") ? "20px" : "auto";
      const dotTop = slDotPosition.includes("top") ? "20px" : "auto";
      let dotLeft = "50%";
      let dotTransform = "translateX(-50%)";
      let dotRight = "auto";
      if (slDotPosition === "bottom-left" || slDotPosition === "top-left") {
        dotLeft = "20px";
        dotTransform = "none";
      } else if (slDotPosition === "bottom-right" || slDotPosition === "top-right") {
        dotLeft = "auto";
        dotRight = "20px";
        dotTransform = "none";
      }
      slSliderHTML += `<div class="pb-slider-dots" style="position: absolute; bottom: ${dotBottom}; top: ${dotTop}; left: ${dotLeft}; right: ${dotRight}; transform: ${dotTransform}; display: flex; gap: 10px; z-index: 3;">`;
      slSlides.forEach((slide, index) => {
        const isActive = index === 0;
        const dotColor = isActive ? slActiveDotColor : slDotColor;
        slSliderHTML += `<div class="pb-slider-dot" data-slide="${index}" onclick="pbSliderGoTo('${sliderId}', ${index})" style="width: ${dotWidth}; height: ${dotHeight}; border-radius: ${dotBorderRadius}; background-color: ${dotColor}; cursor: pointer; transition: all 0.3s;"></div>`;
      });
      slSliderHTML += `</div>`;
    }
    if (slShowProgressBar) {
      const progressBarColor = settings2.progress_bar_color || "#92003b";
      slSliderHTML += `<div class="pb-slider-progress" style="position: absolute; bottom: 0; left: 0; width: 0%; height: 4px; background-color: ${progressBarColor}; z-index: 4; transition: width linear ${slAutoplaySpeed * 1e3}ms;"></div>`;
    }
    if (slShowFraction) {
      const fractionColor = settings2.fraction_color || "#ffffff";
      slSliderHTML += `<div class="pb-slider-fraction" style="position: absolute; top: 20px; right: 20px; color: ${fractionColor}; font-size: 14px; font-weight: 600; z-index: 3; background: rgba(0,0,0,0.3); padding: 8px 16px; border-radius: 20px;">1 / ${slSlides.length}</div>`;
    }
    if (slSlides.length > 1) {
      slSliderHTML += `<div style="position: absolute; top: 10px; left: 10px; background: rgba(0,0,0,0.5); color: white; padding: 4px 10px; border-radius: 12px; font-size: 11px; z-index: 3;">${slSlides.length} Slides</div>`;
    }
    slSliderHTML += `</div>`;
    slSliderHTML += `<script>
                        (function() {
                            const sliderId = '${sliderId}';
                            const autoplay = ${slAutoplay};
                            const autoplaySpeed = ${slAutoplaySpeed * 1e3};
                            const transitionSpeed = ${slTransitionSpeed};
                            const totalSlides = ${slSlides.length};
                            
                            // Store slider state
                            window.pbSliders = window.pbSliders || {};
                            window.pbSliders[sliderId] = {
                                currentSlide: 0,
                                totalSlides: totalSlides,
                                autoplayInterval: null,
                                transitionSpeed: transitionSpeed
                            };
                            
                            const slider = window.pbSliders[sliderId];
                            
                            // Function to show slide
                            function showSlide(index) {
                                const sliderEl = document.getElementById(sliderId);
                                if (!sliderEl) return;
                                
                                const slides = sliderEl.querySelectorAll('.pb-slide');
                                const dots = sliderEl.querySelectorAll('.pb-slider-dot');
                                const fraction = sliderEl.querySelector('.pb-slider-fraction');
                                const progress = sliderEl.querySelector('.pb-slider-progress');
                                
                                // Hide all slides
                                slides.forEach(s => s.style.display = 'none');
                                
                                // Show current slide
                                if (slides[index]) {
                                    slides[index].style.display = 'flex';
                                    
                                    // Trigger content animations
                                    const content = slides[index].querySelector('.pb-slide-content');
                                    if (content) {
                                        const animation = content.getAttribute('data-animation');
                                        const animClass = 'pb-animate-' + animation;
                                        
                                        // Remove and re-add animation to trigger it
                                        content.style.animation = 'none';
                                        content.querySelectorAll('h2, p, a').forEach(el => {
                                            el.style.animation = 'none';
                                        });
                                        
                                        setTimeout(() => {
                                            content.style.animation = animClass + ' 0.8s ease-out ${slAnimationDelay}ms both';
                                            const elements = content.querySelectorAll('h2, p, a');
                                            elements.forEach((el, i) => {
                                                const delay = ${slAnimationDelay} + ((i + 1) * 100);
                                                el.style.animation = animClass + ' 0.8s ease-out ' + delay + 'ms both';
                                            });
                                        }, 10);
                                    }
                                }
                                
                                // Update dots
                                dots.forEach((dot, i) => {
                                    dot.style.backgroundColor = i === index ? '${slActiveDotColor}' : '${slDotColor}';
                                });
                                
                                // Update fraction
                                if (fraction) {
                                    fraction.textContent = (index + 1) + ' / ' + totalSlides;
                                }
                                
                                // Reset and restart progress bar
                                if (progress) {
                                    progress.style.transition = 'none';
                                    progress.style.width = '0%';
                                    setTimeout(() => {
                                        progress.style.transition = 'width linear ' + autoplaySpeed + 'ms';
                                        progress.style.width = '100%';
                                    }, 50);
                                }
                                
                                slider.currentSlide = index;
                            }
                            
                            // Navigation functions
                            window.pbSliderNext = function(id) {
                                if (id !== sliderId) return;
                                const nextSlide = (slider.currentSlide + 1) % totalSlides;
                                showSlide(nextSlide);
                                if (slider.autoplayInterval) {
                                    clearInterval(slider.autoplayInterval);
                                    startAutoplay();
                                }
                            };
                            
                            window.pbSliderPrev = function(id) {
                                if (id !== sliderId) return;
                                const prevSlide = (slider.currentSlide - 1 + totalSlides) % totalSlides;
                                showSlide(prevSlide);
                                if (slider.autoplayInterval) {
                                    clearInterval(slider.autoplayInterval);
                                    startAutoplay();
                                }
                            };
                            
                            window.pbSliderGoTo = function(id, index) {
                                if (id !== sliderId) return;
                                showSlide(index);
                                if (slider.autoplayInterval) {
                                    clearInterval(slider.autoplayInterval);
                                    startAutoplay();
                                }
                            };
                            
                            // Autoplay
                            function startAutoplay() {
                                if (!autoplay || totalSlides <= 1) return;
                                slider.autoplayInterval = setInterval(() => {
                                    window.pbSliderNext(sliderId);
                                }, autoplaySpeed);
                            }
                            
                            // Initialize
                            showSlide(0);
                            startAutoplay();
                        })();
                    <\/script>`;
    return slSliderHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/social-icons.js
  function renderSocialIcons(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const socialItems = settings2.social_items || [{
      platform: "facebook",
      url: "#",
      icon: "fab fa-facebook-f",
      color: "#3b5998"
    }, {
      platform: "twitter",
      url: "#",
      icon: "fab fa-twitter",
      color: "#1da1f2"
    }, {
      platform: "instagram",
      url: "#",
      icon: "fab fa-instagram",
      color: "#E4405F"
    }, {
      platform: "linkedin",
      url: "#",
      icon: "fab fa-linkedin-in",
      color: "#0077b5"
    }];
    const socialAlign = settings2.align || "center";
    const socialIconSize = settings2.icon_size || 20;
    const socialBoxSize = settings2.icon_box_size || 45;
    const socialSpacing = settings2.icon_spacing || 10;
    const socialBgColor = settings2.icon_bg_color || "#333333";
    const socialIconColor = settings2.icon_color || "#ffffff";
    let socialIconsHTML = "";
    socialItems.forEach((item) => {
      socialIconsHTML += `<a href="${item.url || "#"}" target="_blank" style="display: inline-flex; align-items: center; justify-content: center; width: ${socialBoxSize}px; height: ${socialBoxSize}px; background: ${socialBgColor}; color: ${socialIconColor}; border-radius: 50%; margin: 0 ${socialSpacing / 2}px; text-decoration: none; transition: all 0.3s; font-size: ${socialIconSize}px;">
                            <i class="${item.icon}"></i>
                        </a>`;
    });
    return `<div style="text-align: ${socialAlign}; padding: 20px; background: #f5f5f5; border-radius: 8px;">
                        <div style="display: inline-block;">${socialIconsHTML}</div>
                        <p style="margin-top: 15px; color: #666; font-size: 12px; text-align: center;">
                            <i class="fa fa-share-nodes"></i> Social Media Links
                        </p>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/spacer.js
  function renderSpacer(context2) {
    const { settings: settings2 } = context2;
    return `<div style="height: ${settings2.height || 50}px; width: 100%;"></div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/star-rating.js
  function renderStarRating(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const starRating = parseFloat(settings2.rating) || 5;
    const starMaxStars = parseInt(settings2.max_stars) || 5;
    const starShowTitle = settings2.show_title !== "no";
    const starTitle = settings2.title || "Excellent Service!";
    const starShowNumber = settings2.show_number !== "no";
    const starFilledColor = settings2.filled_color || "#ffa500";
    const starEmptyColor = settings2.empty_color || "#d4d4d4";
    const starSize = settings2.star_size || 24;
    const starTitleColor = settings2.title_color || "#333333";
    const starAlign = settings2.align || "left";
    const starMargin = settings2.margin || {
      top: 20,
      right: 0,
      bottom: 20,
      left: 0
    };
    let starHTML = `
                        <div class="probuilder-star-rating-preview" style="
                            text-align: ${starAlign};
                            margin: ${starMargin.top}px ${starMargin.right}px ${starMargin.bottom}px ${starMargin.left}px;
                        ">
                            ${starShowTitle ? `<div style="font-size: 18px; font-weight: 600; margin-bottom: 10px; color: ${starTitleColor};">
                                ${starTitle}
                            </div>` : ""}
                            <div style="font-size: ${starSize}px; margin-bottom: 8px;">
                    `;
    for (let i = 1; i <= starMaxStars; i++) {
      let starClass = "fa fa-star";
      let starColor = starEmptyColor;
      if (i <= Math.floor(starRating)) {
        starColor = starFilledColor;
      } else if (i - 0.5 <= starRating) {
        starClass = "fa fa-star-half-stroke";
        starColor = starFilledColor;
      }
      starHTML += `<i class="${starClass}" style="color: ${starColor}; margin-right: 3px;"></i>`;
    }
    starHTML += `</div>`;
    if (starShowNumber) {
      starHTML += `<div style="font-size: 14px; color: #666;">
                            ${starRating.toFixed(1)} / ${starMaxStars}
                        </div>`;
    }
    starHTML += "</div>";
    return starHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/sticky-video.js
  function renderStickyVideo(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const videoUrl = settings2.video_url || "https://www.youtube.com/watch?v=dQw4w9WgXcQ";
    const stickyVideoPosition = settings2.sticky_position || "bottom-right";
    return `<div style="background:#f5f5f5;padding:40px;border-radius:8px;position:relative;min-height:250px">
                        <p style="margin:0 0 150px;color:#666;text-align:center">
                            <i class="fa fa-video" style="font-size:48px;color:#93003c;display:block;margin-bottom:15px"></i>
                            Sticky Video Player
                        </p>
                        <div style="position:absolute;bottom:20px;right:20px;width:300px;background:#000;border-radius:8px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.3)">
                            <div style="position:relative;padding-bottom:56.25%;background:#333">
                                <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);color:#fff;font-size:48px">\u25B6</div>
                            </div>
                            <div style="padding:8px;background:#222;color:#fff;font-size:11px;text-align:center">Video will stick on scroll (${stickyVideoPosition})</div>
                        </div>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/stripe-button.js
  function renderStripeButton(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const stripeAmount = settings2.amount || 1e3;
    const stripeCurrency = settings2.currency || "usd";
    const stripeButtonText = settings2.button_text || "Pay with Stripe";
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

  // wp-content/plugins/probuilder/assets/js/src/widgets/table-of-contents.js
  function renderTableOfContents(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    return `<div style="background:#f9f9f9;padding:20px;border-radius:8px">
                        <h4 style="margin:0 0 15px;font-size:18px">${settings2.title || "Table of Contents"}</h4>
                        <ol style="margin:0;padding-left:25px;line-height:2">
                            <li><a href="#" style="color:#0073aa;text-decoration:none">Section 1</a></li>
                            <li><a href="#" style="color:#0073aa;text-decoration:none">Section 2</a></li>
                            <li><a href="#" style="color:#0073aa;text-decoration:none">Section 3</a></li>
                        </ol>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/tabs.js
  function renderTabs(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const tabItems = Array.isArray(settings2.tabs) ? settings2.tabs : [{
      tab_title: "Tab 1",
      children: []
    }, {
      tab_title: "Tab 2",
      children: []
    }, {
      tab_title: "Tab 3",
      children: []
    }];
    const tabsStyle = settings2.style || "horizontal";
    const activeTabBg = settings2.active_bg_color || "#92003b";
    const activeTabColor = settings2.active_text_color || "#ffffff";
    const inactiveTabBg = settings2.inactive_bg_color || "#f3f4f6";
    const inactiveTabColor = settings2.inactive_text_color || "#333333";
    const contentBg = settings2.content_bg_color || "#ffffff";
    const contentColor = settings2.content_text_color || "#666666";
    if (!element2.tabChildren) {
      element2.tabChildren = tabItems.map(() => []);
    }
    const uniqueId = "tabs-" + element2.id;
    let tabsHTML = `<div class="probuilder-tabs-preview" data-tabs-id="${uniqueId}" data-element-id="${element2.id}" style="width: 100%;">`;
    tabsHTML += '<div class="probuilder-tabs-header" style="display: flex; gap: 5px; border-bottom: 2px solid #e6e9ec; margin-bottom: 0;">';
    tabItems.forEach((tab, index) => {
      tabsHTML += `
                            <div class="probuilder-tab-header" data-tab-index="${index}" style="
                                padding: 12px 24px;
                                background: ${index === 0 ? activeTabBg : inactiveTabBg};
                                color: ${index === 0 ? activeTabColor : inactiveTabColor};
                                cursor: pointer;
                                border-radius: 3px 3px 0 0;
                                font-weight: ${index === 0 ? "600" : "400"};
                                transition: all 0.3s;
                                margin-bottom: -2px;
                                border-bottom: ${index === 0 ? `2px solid ${activeTabBg}` : "none"};
                            " data-active-bg="${activeTabBg}" data-active-color="${activeTabColor}" data-inactive-bg="${inactiveTabBg}" data-inactive-color="${inactiveTabColor}">
                                ${tab.tab_title || tab.title || `Tab ${index + 1}`}
                            </div>
                        `;
    });
    tabsHTML += "</div>";
    tabsHTML += '<div class="probuilder-tabs-contents">';
    tabItems.forEach((tab, index) => {
      const tabChildren = element2.tabChildren[index] || [];
      const hasChildren = tabChildren.length > 0;
      tabsHTML += `
                            <div class="probuilder-tabs-content probuilder-tab-drop-zone" data-tab-content="${index}" data-tab-index="${index}" data-element-id="${element2.id}" style="
                                padding: 20px;
                                background: ${contentBg};
                                color: ${contentColor};
                                border: 1px solid #e6e9ec;
                                border-top: none;
                                border-radius: 0 3px 3px 3px;
                                min-height: 150px;
                                display: ${index === 0 ? "block" : "none"};
                            ">`;
      if (hasChildren) {
        tabChildren.forEach((child) => {
          const childWidget = app2.widgets.find((w) => w.name === child.widgetType);
          const childPreview = app2.generatePreview(child, depth2 + 1);
          tabsHTML += `
                                    <div class="probuilder-nested-element" data-id="${child.id}" data-widget="${child.widgetType}" style="position: relative; z-index: 1; margin-bottom: 10px;">
                                        <div class="probuilder-nested-controls" style="
                                            position: absolute;
                                            top: 0;
                                            right: 0;
                                            display: none;
                                            gap: 4px;
                                            z-index: 100;
                                            background: rgba(255, 255, 255, 0.95);
                                            padding: 4px;
                                            border-radius: 3px;
                                            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                                        ">
                                            <button class="probuilder-nested-drag" title="Move" style="background: #71717a; border: none; color: #ffffff; width: 24px; height: 24px; border-radius: 2px; cursor: move; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                                <i class="dashicons dashicons-move" style="font-size: 12px;"></i>
                                            </button>
                                            <button class="probuilder-nested-edit" title="Edit" style="background: #92003b; border: none; color: #ffffff; width: 24px; height: 24px; border-radius: 2px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                                <i class="dashicons dashicons-edit" style="font-size: 12px;"></i>
                                            </button>
                                            <button class="probuilder-nested-delete" title="Delete" style="background: #dc2626; border: none; color: #ffffff; width: 24px; height: 24px; border-radius: 2px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                                <i class="dashicons dashicons-trash" style="font-size: 12px;"></i>
                                            </button>
                                        </div>
                                        <div class="probuilder-nested-preview">
                                            ${childPreview}
                                        </div>
                                    </div>
                                `;
        });
      } else {
        tabsHTML += `
                                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 130px; color: #a4afb7; cursor: pointer;" class="probuilder-tab-empty-zone">
                                    <i class="dashicons dashicons-plus" style="font-size: 32px; opacity: 0.4; margin-bottom: 8px;"></i>
                                    <span style="font-size: 14px; opacity: 0.7;">Click to add widget or drag & drop here</span>
                                </div>
                            `;
      }
      tabsHTML += `</div>`;
    });
    tabsHTML += "</div>";
    tabsHTML += "</div>";
    setTimeout(function() {
      const $tabsContainer = $(`[data-tabs-id="${uniqueId}"]`);
      if ($tabsContainer.length === 0) {
        console.warn("Tabs container not found:", uniqueId);
        return;
      }
      console.log("\u2705 Attaching tab switching handlers for:", uniqueId);
      $tabsContainer.off("click.tabSwitch").on("click.tabSwitch", ".probuilder-tab-header", function(e) {
        e.stopPropagation();
        e.preventDefault();
        const $header = $(app2);
        const tabIndex = $header.data("tab-index");
        const activeBg = $header.data("active-bg");
        const activeColor = $header.data("active-color");
        const inactiveBg = $header.data("inactive-bg");
        const inactiveColor = $header.data("inactive-color");
        console.log("\u{1F504} Switching to tab:", tabIndex);
        $tabsContainer.find(".probuilder-tab-header").each(function() {
          const $tab = $(app2);
          const isActive = $tab.data("tab-index") === tabIndex;
          $tab.css({
            "background": isActive ? activeBg : inactiveBg,
            "color": isActive ? activeColor : inactiveColor,
            "font-weight": isActive ? "600" : "400",
            "border-bottom": isActive ? `2px solid ${activeBg}` : "none"
          });
        });
        $tabsContainer.find(".probuilder-tabs-content").each(function() {
          const $content = $(app2);
          const contentIndex = $content.data("tab-content");
          const shouldShow = contentIndex === tabIndex;
          $content.css("display", shouldShow ? "block" : "none");
          console.log(`  Tab ${contentIndex}: ${shouldShow ? "SHOW" : "hide"}`);
        });
      });
      ProBuilder.makeTabsDroppable(element2);
      ProBuilder.attachTabNestedHandlers(element2, $tabsContainer);
      $tabsContainer.find(".probuilder-tab-empty-zone").off("click").on("click", function(e) {
        e.stopPropagation();
        const tabIndex = $(app2).closest(".probuilder-tabs-content").data("tab-index");
        console.log("Tab empty zone clicked:", tabIndex);
        ProBuilder.showWidgetPickerForTab(element2.id, tabIndex);
      });
    }, 100);
    return tabsHTML;
    const tabsItems = settings2.tabs || [{
      tab_title: "Tab #1",
      tab_icon: "fa fa-home",
      tab_content: "Tab content goes here."
    }, {
      tab_title: "Tab #2",
      tab_icon: "fa fa-star",
      tab_content: "Tab content goes here."
    }, {
      tab_title: "Tab #3",
      tab_icon: "fa fa-heart",
      tab_content: "Tab content goes here."
    }];
    const tabOrientation = settings2.tab_orientation || "horizontal";
    const tabAlignment = settings2.tab_alignment || "left";
    const verticalTabWidth = settings2.vertical_tab_width || 25;
    const tabBg = settings2.tab_bg_color || "#f5f5f5";
    const tabActiveBg = settings2.tab_active_bg_color || "#ffffff";
    const tabText = settings2.tab_text_color || "#333333";
    const tabActiveText = settings2.tab_active_text_color || "#007cba";
    const tabBorderColor = settings2.tab_border_color || "#ddd";
    const tabBorderWidth = settings2.tab_border_width || 1;
    const tabBorderRadius = settings2.tab_border_radius || 4;
    const tabPadding = settings2.tab_padding || 15;
    const contentPadding = settings2.content_padding || 20;
    let tabsPreviewHTML = `
                        <div style="
                            display: ${tabOrientation === "vertical" ? "flex" : "block"};
                            border: ${tabBorderWidth}px solid ${tabBorderColor};
                            border-radius: ${tabBorderRadius}px;
                            overflow: hidden;
                        ">
                    `;
    tabsPreviewHTML += `
                        <div style="
                            ${tabOrientation === "vertical" ? `
                                width: ${verticalTabWidth}%;
                                flex-shrink: 0;
                                border-right: ${tabBorderWidth}px solid ${tabBorderColor};
                            ` : `
                                display: flex;
                                ${tabAlignment === "center" ? "justify-content: center;" : ""}
                                ${tabAlignment === "right" ? "justify-content: flex-end;" : ""}
                                ${tabAlignment === "justified" ? "justify-content: space-between;" : ""}
                                border-bottom: ${tabBorderWidth}px solid ${tabBorderColor};
                            `}
                        ">
                    `;
    tabsItems.forEach((tab, index) => {
      const isActive = index === 0;
      const isLast = index === tabsItems.length - 1;
      tabsPreviewHTML += `
                            <div style="
                                padding: ${tabPadding}px ${tabPadding * 1.5}px;
                                background: ${isActive ? tabActiveBg : tabBg};
                                color: ${isActive ? tabActiveText : tabText};
                                font-weight: ${isActive ? "600" : "400"};
                                cursor: pointer;
                                ${tabOrientation === "horizontal" ? `
                                    display: inline-block;
                                    border-top-left-radius: ${tabBorderRadius}px;
                                    border-top-right-radius: ${tabBorderRadius}px;
                                    ${tabAlignment === "justified" ? "flex: 1; text-align: center;" : ""}
                                ` : ""}
                                ${tabOrientation === "vertical" ? `
                                    display: block;
                                    width: 100%;
                                    ${index === 0 ? `border-top-left-radius: ${tabBorderRadius}px;` : ""}
                                    ${isLast ? `border-bottom-left-radius: ${tabBorderRadius}px;` : ""}
                                    ${!isLast ? `border-bottom: ${tabBorderWidth}px solid ${tabBorderColor};` : ""}
                                    text-align: left;
                                ` : ""}
                            ">
                                ${tab.tab_icon ? `<i class="${tab.tab_icon}" style="margin-right: 8px;"></i>` : ""}
                                ${tab.tab_title}
                            </div>
                        `;
    });
    tabsPreviewHTML += "</div>";
    tabsPreviewHTML += `
                        <div style="
                            ${tabOrientation === "vertical" ? "flex: 1;" : ""}
                            padding: ${contentPadding}px;
                            background: ${tabActiveBg};
                            min-height: 150px;
                            ${tabOrientation === "vertical" ? `
                                border-radius: 0 ${tabBorderRadius}px ${tabBorderRadius}px 0;
                            ` : `
                                border-radius: 0 0 ${tabBorderRadius}px ${tabBorderRadius}px;
                            `}
                        ">
                            <div style="color: #666; line-height: 1.6;">
                                ${tabsItems[0].tab_content}
                            </div>
                        </div>
                    `;
    tabsPreviewHTML += "</div>";
    return tabsPreviewHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/tag-cloud.js
  function renderTagCloud(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const tagColor = settings2.color || "#0073aa";
    const tags = [{
      name: "WordPress",
      size: 20
    }, {
      name: "Design",
      size: 16
    }, {
      name: "Development",
      size: 18
    }, {
      name: "Tutorial",
      size: 14
    }, {
      name: "Guide",
      size: 16
    }, {
      name: "Tips",
      size: 15
    }, {
      name: "SEO",
      size: 17
    }, {
      name: "Marketing",
      size: 14
    }, {
      name: "Business",
      size: 19
    }];
    return `<div style="background:#f9f9f9;padding:20px;border-radius:8px">
                        <h4 style="margin:0 0 15px;font-size:18px;color:#333">Popular Tags</h4>
                        <div style="display:flex;flex-wrap:wrap;gap:10px">
                            ${tags.map((tag) => `<a href="#" style="color:${tagColor};font-size:${tag.size}px;text-decoration:none;transition:opacity 0.3s" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">${tag.name}</a>`).join("")}
                        </div>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/team-member.js
  function renderTeamMember(context2) {
    var _a;
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const teamImage = ((_a = settings2.image) == null ? void 0 : _a.url) || "https://i.pravatar.cc/300?img=12";
    const teamName = settings2.name || "John Doe";
    const teamPosition = settings2.position || "CEO & Founder";
    const teamBio = settings2.bio || "Passionate about creating amazing products.";
    const teamEmail = settings2.email || "";
    const teamPhone = settings2.phone || "";
    const teamFacebook = settings2.facebook || "";
    const teamTwitter = settings2.twitter || "";
    const teamLinkedin = settings2.linkedin || "";
    const teamInstagram = settings2.instagram || "";
    const teamLayout = settings2.layout || "left";
    const teamTextAlign = settings2.text_align || "left";
    const teamImageSize = settings2.image_size || 150;
    const teamBorderColor = settings2.border_color || "#92003b";
    const teamNameColor = settings2.name_color || "#333333";
    const teamPositionColor = settings2.position_color || "#92003b";
    let teamHTML = "";
    let teamContainerStyle = "padding: 20px; border: 1px solid #e5e5e5; border-radius: 8px; background: #fff;";
    if (teamLayout === "center") {
      teamContainerStyle += ` text-align: ${teamTextAlign}; display: flex; flex-direction: column; align-items: center;`;
      teamHTML = `<div style="${teamContainerStyle}">`;
      teamHTML += `<div style="margin-bottom: 20px; display: inline-block;">
                            <img src="${teamImage}" alt="${teamName}" style="width: ${teamImageSize}px; height: ${teamImageSize}px; border-radius: 50%; object-fit: cover; border: 3px solid ${teamBorderColor}; display: block;">
                        </div>`;
    } else {
      const flexDirection = teamLayout === "left" ? "row" : "row-reverse";
      teamContainerStyle += ` display: flex; flex-direction: ${flexDirection}; gap: 25px; align-items: flex-start;`;
      teamHTML = `<div style="${teamContainerStyle}">`;
      teamHTML += `<div style="flex-shrink: 0;">
                            <img src="${teamImage}" alt="${teamName}" style="width: ${teamImageSize}px; height: ${teamImageSize}px; border-radius: 50%; object-fit: cover; border: 3px solid ${teamBorderColor};">
                        </div>`;
      teamHTML += `<div style="flex: 1; text-align: ${teamTextAlign};">`;
    }
    teamHTML += `<h3 style="margin: 0 0 5px 0; font-size: 22px; font-weight: 600; color: ${teamNameColor};">${teamName}</h3>`;
    teamHTML += `<div style="color: ${teamPositionColor}; font-size: 14px; font-weight: 600; margin-bottom: 15px;">${teamPosition}</div>`;
    if (teamBio) {
      teamHTML += `<p style="color: #666; font-size: 14px; line-height: 1.6; margin: 0 0 15px 0;">${teamBio}</p>`;
    }
    if (teamEmail || teamPhone) {
      const contactAlign = teamTextAlign === "center" ? "center" : teamTextAlign === "right" ? "flex-end" : "flex-start";
      teamHTML += `<div style="font-size: 13px; color: #666; margin-bottom: 15px; display: flex; flex-direction: column; align-items: ${contactAlign}; gap: 5px;">`;
      if (teamEmail) teamHTML += `<div style="display: flex; align-items: center; gap: 8px;"><i class="fa fa-envelope" style="color: ${teamPositionColor};"></i> <span>${teamEmail}</span></div>`;
      if (teamPhone) teamHTML += `<div style="display: flex; align-items: center; gap: 8px;"><i class="fa fa-phone" style="color: ${teamPositionColor};"></i> <span>${teamPhone}</span></div>`;
      teamHTML += `</div>`;
    }
    const hasSocial = teamFacebook || teamTwitter || teamLinkedin || teamInstagram;
    if (hasSocial) {
      const socialJustify = teamTextAlign === "center" ? "center" : teamTextAlign === "right" ? "flex-end" : "flex-start";
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
    if (teamLayout !== "center") {
      teamHTML += `</div>`;
    }
    teamHTML += `</div>`;
    return teamHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/text.js
  function renderText(context2) {
    const { element: element2, settings: settings2, app: app2 } = context2;
    const textColor = settings2.text_color || app2.getGlobalColor("text") || "#495157";
    const textAlign = settings2.text_align || "left";
    const pathType = settings2.path_type || "none";
    const textCurveAmount = settings2.curve_amount || 50;
    const textStyle = `
        color: ${textColor};
        font-size: ${settings2.font_size || 16}px;
        line-height: ${settings2.line_height || 1.6};
        text-align: ${textAlign};
        margin: 0;
    `;
    let textContent = settings2.text || "Lorem ipsum dolor sit amet, consectetur adipiscing elit.";
    const textContentPlain = textContent.replace(/<[^>]*>/g, "").substring(0, 200);
    if (pathType !== "none") {
      const svgId = "text-path-preview-" + element2.id;
      const svgHeight = (settings2.font_size || 16) * 3;
      const textLength = textContentPlain.length * (settings2.font_size || 16) * 0.6;
      const midY = svgHeight / 2;
      const intensity = textCurveAmount;
      let pathD = "";
      switch (pathType) {
        case "curve": {
          const controlY = midY - intensity;
          pathD = `M 0,${svgHeight} Q ${textLength / 2},${controlY} ${textLength},${svgHeight}`;
          break;
        }
        case "wave": {
          const waveHeight = Math.abs(intensity);
          pathD = `M 0,${midY} Q ${textLength * 0.25},${midY - waveHeight} ${textLength * 0.5},${midY} T ${textLength},${midY}`;
          break;
        }
        case "circle": {
          const radius = textLength / 2;
          pathD = `M 0,${svgHeight} A ${radius},${radius} 0 0,1 ${textLength},${svgHeight}`;
          break;
        }
        case "zigzag": {
          const points = 8;
          pathD = `M 0,${midY}`;
          for (let i = 1; i <= points; i++) {
            const x = textLength / points * i;
            const y = midY + (i % 2 === 0 ? intensity : -intensity);
            pathD += ` L ${x},${y}`;
          }
          break;
        }
        case "spiral": {
          const turns = 3;
          pathD = `M 0,${midY}`;
          for (let i = 1; i <= 20; i++) {
            const x = textLength / 20 * i;
            const angle = i / 20 * turns * 2 * Math.PI;
            const amplitude = intensity * (i / 20);
            const y = midY + Math.sin(angle) * amplitude;
            pathD += ` L ${x},${y}`;
          }
          break;
        }
        case "sine": {
          const frequency = 2;
          pathD = `M 0,${midY}`;
          for (let i = 1; i <= 30; i++) {
            const x = textLength / 30 * i;
            const angle = i / 30 * frequency * 2 * Math.PI;
            const y = midY + Math.sin(angle) * intensity;
            pathD += ` L ${x},${y}`;
          }
          break;
        }
        case "bounce": {
          const bounces = 4;
          pathD = `M 0,${midY}`;
          for (let i = 1; i <= bounces; i++) {
            const x1 = textLength / bounces * (i - 0.5);
            const y1 = midY - Math.abs(intensity);
            const x2 = textLength / bounces * i;
            const y2 = midY;
            pathD += ` Q ${x1},${y1} ${x2},${y2}`;
          }
          break;
        }
        case "infinity": {
          const loopWidth = textLength / 2;
          pathD = `M 0,${midY} `;
          pathD += `Q ${loopWidth * 0.25},${midY - intensity} ${loopWidth * 0.5},${midY} `;
          pathD += `Q ${loopWidth * 0.75},${midY + intensity} ${loopWidth},${midY} `;
          pathD += `Q ${loopWidth * 1.25},${midY - intensity} ${loopWidth * 1.5},${midY} `;
          pathD += `Q ${loopWidth * 1.75},${midY + intensity} ${textLength},${midY}`;
          break;
        }
        default:
          pathD = `M 0,${midY} L ${textLength},${midY}`;
      }
      return `
            <div style="text-align: ${textAlign};">
                <svg width="100%" height="${svgHeight}px" viewBox="0 0 ${textLength} ${svgHeight}" xmlns="http://www.w3.org/2000/svg" style="overflow: visible;">
                    <defs>
                        <path id="${svgId}" d="${pathD}" fill="transparent"/>
                    </defs>
                    <text style="fill: ${textColor}; font-size: ${settings2.font_size || 16}px;">
                        <textPath href="#${svgId}" startOffset="50%" text-anchor="middle">
                            ${textContentPlain}
                        </textPath>
                    </text>
                </svg>
            </div>
        `;
    }
    return `<div class="probuilder-text" style="${textStyle}">${textContent}</div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/testimonial.js
  function renderTestimonial(context2) {
    var _a;
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const testContent = settings2.testimonial || settings2.content || "This is an amazing product! I highly recommend it to everyone.";
    const testImage = settings2.author_image || ((_a = settings2.image) == null ? void 0 : _a.url) || "https://i.pravatar.cc/100?img=8";
    const testName = settings2.author_name || settings2.name || "Jane Smith";
    const testTitle = settings2.author_title || settings2.title || "Marketing Director";
    const testRating = parseInt(settings2.rating) || 5;
    const testAlign = settings2.alignment || settings2.align || "center";
    let testHTML = `<div style="text-align: ${testAlign}; padding: 40px; background: #ffffff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); position: relative; transition: all 0.3s;">`;
    testHTML += `<div style="font-size: 60px; color: #92003b; opacity: 0.15; margin-bottom: 20px; line-height: 1;"><i class="fa fa-quote-left"></i></div>`;
    testHTML += `<div style="font-size: 16px; line-height: 1.8; margin-bottom: 25px; font-style: italic; color: #4b5563;"><p style="margin: 0;">${testContent}</p></div>`;
    if (testRating > 0) {
      testHTML += `<div style="margin-bottom: 20px; color: #fbbf24; font-size: 18px;">`;
      for (let i = 1; i <= 5; i++) {
        testHTML += i <= testRating ? "\u2605" : "\u2606";
      }
      testHTML += `</div>`;
    }
    testHTML += `<div style="display: flex; align-items: center; justify-content: ${testAlign}; gap: 15px; margin-top: 20px;">
                        <img src="${testImage}" alt="${testName}" style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 3px solid #f3f4f6;">
                        <div style="text-align: left;">
                            <div style="font-weight: 700; margin-bottom: 3px; font-size: 16px; color: #1f2937;">${testName}</div>
                            <div style="color: #92003b; font-size: 14px; font-weight: 500;">${testTitle}</div>
                        </div>
                    </div>`;
    testHTML += `</div>`;
    return testHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/text-path.js
  function renderTextPath(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const curvedText = settings2.text || "Beautiful Curved Text";
    const curvePathType = settings2.path_type || "arc";
    const curveAmount = settings2.curve_amount || 30;
    const curveWidth = settings2.path_width || 500;
    const curveHeight = settings2.path_height || 200;
    const textPathColor = settings2.text_color || "#1a1a1a";
    const textPathSize = settings2.font_size || 32;
    const textPathWeight = settings2.font_weight || "600";
    const textPathSpacing = settings2.letter_spacing || 0;
    const startOff = settings2.start_offset || 0;
    const strokeW = settings2.text_stroke || 0;
    const strokeCol = settings2.stroke_color || "#000000";
    let curvePath = "";
    const cX = curveWidth / 2;
    const cY = curveHeight / 2;
    const sX = 50;
    const eX = curveWidth - 50;
    switch (curvePathType) {
      case "arc":
        curvePath = `M ${sX},${cY} Q ${cX},${cY - curveAmount} ${eX},${cY}`;
        break;
      case "arc-down":
        curvePath = `M ${sX},${cY} Q ${cX},${cY + curveAmount} ${eX},${cY}`;
        break;
      case "wave":
        const wH = Math.abs(curveAmount);
        const step = curveWidth / 4;
        curvePath = `M ${sX},${cY} Q ${sX + step},${cY - wH} ${sX + step * 2},${cY} Q ${sX + step * 3},${cY + wH} ${eX},${cY}`;
        break;
      case "circle":
        const rad = Math.min(curveWidth, curveHeight) / 2 - 50;
        curvePath = `M ${cX - rad},${cY} A ${rad},${rad} 0 1,1 ${cX + rad},${cY}`;
        break;
      case "s-curve":
        const c1Y = cY - curveAmount;
        const c2Y = cY + curveAmount;
        curvePath = `M ${sX},${cY} C ${curveWidth * 0.3},${c1Y} ${curveWidth * 0.7},${c2Y} ${eX},${cY}`;
        break;
      default:
        curvePath = `M ${sX},${cY} Q ${cX},${cY - curveAmount} ${eX},${cY}`;
    }
    const pathId = "curve-" + Math.random().toString(36).substr(2, 9);
    const strokeAttr = strokeW > 0 ? `stroke="${strokeCol}" stroke-width="${strokeW}"` : "";
    return `<div style="text-align:center;padding:30px;background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);border-radius:12px">
                        <svg viewBox="0 0 ${curveWidth} ${curveHeight}" style="width:100%;max-width:${curveWidth}px;height:auto">
                            <path id="${pathId}" d="${curvePath}" fill="transparent" stroke="rgba(0,0,0,0.1)" stroke-width="1" stroke-dashautor="5,5"/>
                            <text fill="${textPathColor}" font-size="${textPathSize}" font-weight="${textPathWeight}" letter-spacing="${textPathSpacing}" ${strokeAttr} text-anchor="middle">
                                <textPath href="#${pathId}" startOffset="${startOff}%">
                                    ${curvedText}
                                </textPath>
                            </text>
                        </svg>
                        <div style="margin-top:20px;padding:15px;background:rgba(255,255,255,0.9);border-radius:8px">
                            <p style="margin:0;color:#667eea;font-size:13px;font-weight:600">
                                \u{1F4D0} ${curvePathType.toUpperCase()} Path | 
                                Curve: ${curveAmount > 0 ? "+" : ""}${curveAmount} | 
                                Size: ${textPathSize}px
                            </p>
                        </div>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/timeline.js
  function renderTimeline(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const tlTimelineTitle = settings2.timeline_title || "Our Journey";
    const tlTimelineDescription = settings2.timeline_description || "Follow our journey from the beginning to where we are today.";
    const tlTimelineLayout = settings2.layout || "vertical";
    const tlShowConnector = settings2.show_connector !== "no";
    const tlItemBgColor = settings2.item_bg_color || "#ffffff";
    const tlItemBorderColor = settings2.item_border_color || "#e1e5e9";
    const tlDateColor = settings2.date_color || "#92003b";
    const tlIconBgColor = settings2.icon_bg_color || "#92003b";
    const tlIconColor = settings2.icon_color || "#ffffff";
    const tlConnectorColor = settings2.connector_color || "#e1e5e9";
    const tlBorderRadius = settings2.border_radius || {
      size: 8
    };
    let tlTimelineHTML = `<div>`;
    if (tlTimelineTitle) {
      tlTimelineHTML += `<h2 style="color: #1e293b; font-size: 32px; font-weight: 700; margin: 0 0 15px 0; text-align: center;">${tlTimelineTitle}</h2>`;
    }
    if (tlTimelineDescription) {
      tlTimelineHTML += `<p style="color: #64748b; font-size: 16px; text-align: center; margin: 0 0 50px 0;">${tlTimelineDescription}</p>`;
    }
    if (tlTimelineLayout === "vertical") {
      tlTimelineHTML += `<div style="position: relative; max-width: 800px; margin: 0 auto;">`;
      if (tlShowConnector) {
        tlTimelineHTML += `<div style="position: absolute; left: 30px; top: 0; bottom: 0; width: 2px; background-color: ${tlConnectorColor};"></div>`;
      }
      const sampleItems = [{
        date: "2020",
        title: "Company Founded",
        description: "We started our journey with a vision to revolutionize the industry and provide innovative solutions.",
        icon: "fa fa-rocket"
      }, {
        date: "2021",
        title: "First Product Launch",
        description: "Launched our flagship product that quickly gained recognition in the market.",
        icon: "fa fa-star"
      }, {
        date: "2022",
        title: "International Expansion",
        description: "Expanded our operations to serve customers across multiple countries.",
        icon: "fa fa-globe"
      }];
      sampleItems.forEach((item, index) => {
        const tlItemStyle = `
                                background-color: ${tlItemBgColor};
                                border: 1px solid ${tlItemBorderColor};
                                border-radius: ${tlBorderRadius.size}px;
                                padding: 25px;
                                margin-left: 80px;
                                margin-bottom: 30px;
                                position: relative;
                                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                            `;
        tlTimelineHTML += `<div style="${tlItemStyle}">
                                <div style="position: absolute; left: -60px; top: 25px; width: 40px; height: 40px; background-color: ${tlIconBgColor}; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 2;">
                                    <i class="${item.icon}" style="color: ${tlIconColor}; font-size: 16px;"></i>
                                </div>
                                <div style="color: ${tlDateColor}; font-size: 14px; font-weight: 600; margin-bottom: 10px;">${item.date}</div>
                                <h3 style="color: #1e293b; font-size: 20px; font-weight: 600; margin: 0 0 15px 0;">${item.title}</h3>
                                <p style="color: #64748b; margin: 0; line-height: 1.6;">${item.description}</p>
                            </div>`;
      });
      tlTimelineHTML += `</div>`;
    } else {
      tlTimelineHTML += `<div style="display: flex; overflow-x: auto; gap: 30px; padding: 20px 0;">`;
      const sampleItems = [{
        date: "2020",
        title: "Company Founded",
        description: "We started our journey with a vision to revolutionize the industry.",
        icon: "fa fa-rocket"
      }, {
        date: "2021",
        title: "First Product Launch",
        description: "Launched our flagship product that quickly gained recognition.",
        icon: "fa fa-star"
      }, {
        date: "2022",
        title: "International Expansion",
        description: "Expanded our operations to serve customers worldwide.",
        icon: "fa fa-globe"
      }];
      sampleItems.forEach((item, index) => {
        const tlItemStyle = `
                                background-color: ${tlItemBgColor};
                                border: 1px solid ${tlItemBorderColor};
                                border-radius: ${tlBorderRadius.size}px;
                                padding: 25px;
                                min-width: 300px;
                                flex-shrink: 0;
                                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                            `;
        tlTimelineHTML += `<div style="${tlItemStyle}">
                                <div style="width: 50px; height: 50px; background-color: ${tlIconBgColor}; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto;">
                                    <i class="${item.icon}" style="color: ${tlIconColor}; font-size: 20px;"></i>
                                </div>
                                <div style="color: ${tlDateColor}; font-size: 14px; font-weight: 600; margin-bottom: 10px; text-align: center;">${item.date}</div>
                                <h3 style="color: #1e293b; font-size: 18px; font-weight: 600; margin: 0 0 15px 0; text-align: center;">${item.title}</h3>
                                <p style="color: #64748b; margin: 0; line-height: 1.6; text-align: center;">${item.description}</p>
                            </div>`;
      });
      tlTimelineHTML += `</div>`;
    }
    tlTimelineHTML += `</div>`;
    return tlTimelineHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/toggle.js
  function renderToggle(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const toggleItems = settings2.items || [{
      title: "What are the system requirements?",
      content: "Our system works on all modern browsers",
      default_open: "no"
    }, {
      title: "How do I get started?",
      content: "Simply sign up and follow our guide",
      default_open: "no"
    }];
    const toggleStyle = settings2.toggle_style || "switch";
    const toggleTitleBg = settings2.title_bg_color || "#f8f9fa";
    const toggleTitleColor = settings2.title_text_color || "#333333";
    const toggleIconColor = settings2.toggle_icon_color || "#92003b";
    const toggleTitleSize = settings2.title_font_size || 16;
    const toggleContentBg = settings2.content_bg_color || "#f9f9f9";
    const toggleContentColor = settings2.content_text_color || "#666666";
    const toggleRadius = settings2.border_radius || 4;
    const toggleSpacing = settings2.item_spacing || 10;
    const toggleMargin = settings2.margin || {
      top: 20,
      right: 0,
      bottom: 20,
      left: 0
    };
    const toggleId = "toggle-" + element2.id;
    let toggleHTML = `<div class="probuilder-toggle-preview" data-toggle-id="${toggleId}" style="
                        margin: ${toggleMargin.top}px ${toggleMargin.right}px ${toggleMargin.bottom}px ${toggleMargin.left}px;
                    ">`;
    toggleItems.forEach((item, index) => {
      const isOpen = item.default_open === "yes";
      let titleExtraStyle = "";
      if (toggleStyle === "bordered") {
        titleExtraStyle = `border: 2px solid ${toggleIconColor};`;
      } else if (toggleStyle === "simple") {
        titleExtraStyle = `border-bottom: 2px solid #e5e5e5; background: transparent; border-radius: 0;`;
      }
      toggleHTML += `
                            <div class="toggle-item" data-index="${index}" style="margin-bottom: ${toggleSpacing}px;">
                                <div class="toggle-title" style="
                                    background: ${toggleTitleBg};
                                    color: ${toggleTitleColor};
                                    font-size: ${toggleTitleSize}px;
                                    padding: 15px 20px;
                                    cursor: pointer;
                                    border-radius: ${toggleRadius}px;
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: center;
                                    font-weight: 600;
                                    transition: all 0.3s ease;
                                    ${titleExtraStyle}
                                ">
                                    <span style="flex: 1;">${item.title}</span>
                                    
                                    ${toggleStyle === "switch" ? `
                                        <span class="switch-toggle" style="
                                            position: relative;
                                            width: 44px;
                                            height: 24px;
                                            background: ${isOpen ? toggleIconColor : "#cbd5e1"};
                                            border-radius: 12px;
                                            transition: background 0.3s;
                                            display: inline-block;
                                            margin-left: 15px;
                                        ">
                                            <span class="switch-thumb" style="
                                                position: absolute;
                                                top: 2px;
                                                left: ${isOpen ? "22px" : "2px"};
                                                width: 20px;
                                                height: 20px;
                                                background: #ffffff;
                                                border-radius: 10px;
                                                transition: left 0.3s;
                                                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                                            "></span>
                                        </span>
                                    ` : `
                                        <span class="toggle-icon" style="transition: transform 0.3s; font-size: 18px; color: ${toggleIconColor}; transform: rotate(${isOpen ? "180deg" : "0deg"});">\u25BC</span>
                                    `}
                                </div>
                                <div class="toggle-content" style="
                                    display: ${isOpen ? "block" : "none"};
                                    background: ${toggleContentBg};
                                    color: ${toggleContentColor};
                                    padding: ${isOpen ? "15px 20px" : "0 20px"};
                                    margin-top: 5px;
                                    border-radius: ${toggleRadius}px;
                                    overflow: hidden;
                                    transition: all 0.3s ease;
                                    max-height: ${isOpen ? "1000px" : "0"};
                                ">
                                    <p style="margin: 0; line-height: 1.6;">${item.content}</p>
                                </div>
                            </div>
                        `;
    });
    toggleHTML += "</div>";
    setTimeout(function() {
      const $toggleContainer = jQuery(`[data-toggle-id="${toggleId}"]`);
      $toggleContainer.find(".toggle-title").off("click").on("click", function(e) {
        e.stopPropagation();
        const $title = jQuery(app2);
        const $content = $title.next(".toggle-content");
        const $icon = $title.find(".toggle-icon");
        const $switchToggle = $title.find(".switch-toggle");
        const $switchThumb = $switchToggle.find(".switch-thumb");
        const isOpen = $content.css("display") !== "none";
        if (isOpen) {
          $content.css({
            "max-height": "0",
            "padding": "0 20px"
          });
          setTimeout(function() {
            $content.css("display", "none");
          }, 300);
          $icon.css("transform", "rotate(0deg)");
          $switchToggle.css("background", "#cbd5e1");
          $switchThumb.css("left", "2px");
        } else {
          $content.css({
            "display": "block",
            "max-height": "1000px",
            "padding": "15px 20px"
          });
          $icon.css("transform", "rotate(180deg)");
          $switchToggle.css("background", toggleIconColor);
          $switchThumb.css("left", "22px");
        }
      });
    }, 100);
    return toggleHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/twitter-embed.js
  function renderTwitterEmbed(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const twitterTheme = settings2.theme || "light";
    const twitterBg = twitterTheme === "dark" ? "#15202b" : "#ffffff";
    const twitterTextColor = twitterTheme === "dark" ? "#ffffff" : "#14171a";
    return `<div style="background:#f5f5f5;padding:30px;border-radius:8px;text-align:center">
                        <div style="background:${twitterBg};border:1px solid ${twitterTheme === "dark" ? "#38444d" : "#e1e8ed"};border-radius:12px;padding:20px;max-width:550px;margin:0 auto;text-align:left">
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:15px">
                                <div style="width:48px;height:48px;background:#1da1f2;border-radius:50%;display:flex;align-items:center;justify-content:center">
                                    <i class="fa fa-twitter" style="font-size:24px;color:#fff"></i>
                                </div>
                                <div>
                                    <strong style="color:${twitterTextColor};display:block">Twitter User</strong>
                                    <small style="color:#8899a6">@username</small>
                                </div>
                            </div>
                            <p style="margin:0 0 15px;color:${twitterTextColor};line-height:1.5">This is a sample tweet that will be embedded. Twitter/X content will display here with the ${twitterTheme} theme. \u{1F426}</p>
                            <div style="color:#8899a6;font-size:13px">
                                <i class="fa fa-clock" style="margin-right:5px"></i>
                                Oct 25, 2025
                            </div>
                        </div>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/video.js
  function renderVideo(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const videoType = settings2.video_type || "youtube";
    const youtubeUrl = settings2.youtube_url || "https://www.youtube.com/watch?v=ScMzIvxBSi4";
    const vimeoUrl = settings2.vimeo_url || "";
    const selfUrl = settings2.self_url || "";
    const aspectRatio = settings2.aspect_ratio || "16:9";
    const videoBorderRadius = settings2.border_radius || 8;
    const videoBoxShadow = settings2.box_shadow !== "no";
    const paddingMap = {
      "16:9": "56.25%",
      "4:3": "75%",
      "21:9": "42.85%",
      "1:1": "100%",
      "9:16": "177.78%"
    };
    const videoPadding = paddingMap[aspectRatio] || "56.25%";
    let videoHTML = `<div style="position: relative; padding-bottom: ${videoPadding}; height: 0; overflow: hidden; border-radius: ${videoBorderRadius}px; ${videoBoxShadow ? "box-shadow: 0 4px 20px rgba(0,0,0,0.15);" : ""}">`;
    if (videoType === "youtube" && youtubeUrl) {
      let videoId = "";
      const youtubeMatch1 = youtubeUrl.match(/[?&]v=([^&]+)/);
      const youtubeMatch2 = youtubeUrl.match(/youtu\.be\/([^?]+)/);
      const youtubeMatch3 = youtubeUrl.match(/embed\/([^?]+)/);
      if (youtubeMatch1) videoId = youtubeMatch1[1];
      else if (youtubeMatch2) videoId = youtubeMatch2[1];
      else if (youtubeMatch3) videoId = youtubeMatch3[1];
      if (videoId) {
        videoHTML += `<iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;" src="https://www.youtube.com/embed/${videoId}" frameborder="0" allowfullscreen></iframe>`;
      } else {
        videoHTML += `<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f0f0f0; color: #666;"><i class="fa fa-video" style="font-size: 48px; opacity: 0.3;"></i></div>`;
      }
    } else if (videoType === "vimeo" && vimeoUrl) {
      const vimeoMatch = vimeoUrl.match(/vimeo\.com\/(\d+)/);
      const videoId = vimeoMatch ? vimeoMatch[1] : "";
      if (videoId) {
        videoHTML += `<iframe style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;" src="https://player.vimeo.com/video/${videoId}" frameborder="0" allowfullscreen></iframe>`;
      } else {
        videoHTML += `<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f0f0f0; color: #666;"><i class="fa fa-video" style="font-size: 48px; opacity: 0.3;"></i></div>`;
      }
    } else if (videoType === "self" && selfUrl) {
      videoHTML += `<video style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" controls><source src="${selfUrl}" type="video/mp4"></video>`;
    } else {
      videoHTML += `<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <i class="fa fa-play-circle" style="font-size: 72px; margin-bottom: 15px; opacity: 0.9;"></i>
                            <div style="font-size: 18px; font-weight: 600;">Video Placeholder</div>
                            <div style="font-size: 13px; margin-top: 8px; opacity: 0.8;">Enter a video URL to display</div>
                        </div>`;
    }
    videoHTML += `</div>`;
    return videoHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/woo-add-to-cart.js
  function renderWooAddToCart(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const wcBtnText = settings2.button_text || "Add to Cart";
    const wcBtnColor = settings2.button_color || "#92003b";
    const wcShowQty = settings2.show_quantity !== "no";
    return `<div style="padding: 20px; background: #f5f5f5; border-radius: 8px;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            ${wcShowQty ? '<input type="number" value="1" min="1" style="width: 60px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; text-align: center;">' : ""}
                            <button style="background: ${wcBtnColor}; color: #fff; border: none; padding: 12px 30px; border-radius: 4px; font-weight: 600; cursor: pointer; flex: 1; font-size: 15px;">
                                <i class="fa fa-cart-plus"></i> ${wcBtnText}
                            </button>
                        </div>
                        <p style="margin-top: 10px; color: #666; font-size: 12px; text-align: center;">
                            <i class="fa fa-shopping-cart"></i> WooCommerce Add to Cart Button
                        </p>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/woo-breadcrumbs.js
  function renderWooBreadcrumbs(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const bcSeparator = settings2.separator || "/";
    return `<div style="padding: 15px 20px; background: #f5f5f5; border-radius: 4px;">
                        <div style="font-size: 14px; color: #666;">
                            <a href="#" style="color: #0073aa; text-decoration: none;">Home</a>
                            <span style="margin: 0 8px; color: #999;">${bcSeparator}</span>
                            <a href="#" style="color: #0073aa; text-decoration: none;">Shop</a>
                            <span style="margin: 0 8px; color: #999;">${bcSeparator}</span>
                            <span style="color: #333;">Product Name</span>
                        </div>
                        <p style="margin: 10px 0 0; color: #999; font-size: 11px; text-align: center;">
                            <i class="fa fa-route"></i> WooCommerce Breadcrumbs
                        </p>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/woo-cart.js
  function renderWooCart(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const cartShowIcon = settings2.show_icon !== "no";
    const cartIcon = settings2.icon || "fa fa-shopping-cart";
    const cartCount = settings2.show_count !== "no";
    const cartAmount = settings2.show_amount !== "no";
    return `<div style="padding: 15px 25px; background: #92003b; color: #fff; border-radius: 4px; display: inline-flex; align-items: center; gap: 10px; cursor: pointer;">
                        ${cartShowIcon ? `<i class="${cartIcon}" style="font-size: 20px;"></i>` : ""}
                        <span style="font-weight: 600;">Cart</span>
                        ${cartCount ? '<span style="background: #fff; color: #92003b; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700;">3</span>' : ""}
                        ${cartAmount ? '<span style="margin-left: 5px; font-size: 14px;">$129.00</span>' : ""}
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/woo-categories.js
  function renderWooCategories(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const catColumns = parseInt(settings2.columns || 4);
    const catColumnGap = parseInt(settings2.column_gap || 20);
    const catRowGap = parseInt(settings2.row_gap || 20);
    const catBorderRadius = parseInt(settings2.border_radius || 8);
    const catCardBg = settings2.card_bg_color || "#ffffff";
    const catTitleColor = settings2.title_color || "#344047";
    const catCountColor = settings2.count_color || "#6b7280";
    const catImageHeight = parseInt(settings2.image_height || 200);
    const catShowImage = settings2.show_image !== "no";
    const catShowCount = settings2.show_count !== "no";
    const catHideEmpty = settings2.hide_empty !== "no";
    const catContainerId = "woo-categories-" + element2.id;
    let catHTML = `<div id="${catContainerId}" style="min-height: 100px;">
                        <div style="text-align: center; padding: 30px; color: #92003b;">
                            <div style="display: inline-block; width: 30px; height: 30px; border: 3px solid #f3f4f6; border-top-color: #92003b; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                            <p style="margin-top: 10px; font-size: 13px; font-weight: 600;">Loading categories...</p>
                        </div>
                    </div>`;
    setTimeout(() => {
      $.ajax({
        url: ProBuilderEditor.ajaxurl,
        type: "POST",
        data: {
          action: "probuilder_get_woo_categories",
          nonce: ProBuilderEditor.nonce,
          hide_empty: catHideEmpty
        },
        success: function(response) {
          if (response.success && response.data.categories && response.data.categories.length > 0) {
            const categories = response.data.categories;
            const colors = ["#92003b", "#667eea", "#4facfe", "#764ba2", "#f093fb", "#00f2fe", "#c44569", "#22c55e"];
            let categoriesHTML = `<div style="display: grid; grid-template-columns: repeat(${catColumns}, 1fr); gap: ${catRowGap}px ${catColumnGap}px;">`;
            categories.forEach((category, idx) => {
              categoriesHTML += `<div class="category-item" style="text-align: center; padding: 20px; background: ${catCardBg}; border-radius: ${catBorderRadius}px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.3s;">`;
              if (catShowImage) {
                categoriesHTML += `<a href="${category.link}" style="text-decoration: none;">`;
                if (category.image) {
                  categoriesHTML += `<img src="${category.image}" alt="${category.name}" style="width: 100%; height: ${catImageHeight}px; object-fit: cover; border-radius: 4px; margin-bottom: 15px; display: block;">`;
                } else {
                  const initial = category.name.charAt(0).toUpperCase();
                  const color = colors[idx % colors.length];
                  categoriesHTML += `<div style="width: 100%; height: ${catImageHeight}px; background: ${color}; border-radius: 4px; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 48px; font-weight: 700;">${initial}</div>`;
                }
                categoriesHTML += `</a>`;
              }
              categoriesHTML += `<h3 style="margin: 0 0 8px; font-size: 18px; font-weight: 600; line-height: 1.4;"><a href="${category.link}" style="color: ${catTitleColor}; text-decoration: none;">${category.name}</a></h3>`;
              if (catShowCount) {
                categoriesHTML += `<p style="margin: 0; font-size: 14px; color: ${catCountColor};">${category.count} product${category.count !== 1 ? "s" : ""}</p>`;
              }
              categoriesHTML += `</div>`;
            });
            categoriesHTML += `</div>`;
            $("#" + catContainerId).html(categoriesHTML);
            console.log("\u2705 Loaded", categories.length, "real categories:", categories.map((c) => c.name).join(", "));
          } else {
            $("#" + catContainerId).html(`<div style="padding: 40px; text-align: center; background: #fffbeb; border: 2px dashed #fbbf24; border-radius: 8px; color: #78350f;">
                                        <i class="dashicons dashicons-category" style="font-size: 48px; opacity: 0.3;"></i>
                                        <p style="margin: 10px 0 0; font-weight: 600;">No categories found</p>
                                        <p style="margin: 5px 0 0; font-size: 13px;">Add product categories to your WooCommerce store</p>
                                    </div>`);
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error loading categories:", status, error);
          $("#" + catContainerId).html(`<div style="padding: 40px; text-align: center; background: #fee2e2; border: 2px solid #ef4444; border-radius: 8px; color: #991b1b;">
                                    <i class="dashicons dashicons-warning" style="font-size: 48px;"></i>
                                    <p style="margin: 10px 0 0; font-weight: 600;">Error loading categories</p>
                                </div>`);
        }
      });
    }, 50);
    return catHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/woo-meta.js
  function renderWooMeta(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const metaShowSku = settings2.show_sku !== "no";
    const metaShowCategory = settings2.show_category !== "no";
    const metaShowTags = settings2.show_tags !== "no";
    return `<div style="padding: 20px; background: #f5f5f5; border-radius: 8px;">
                        ${metaShowSku ? '<div style="margin-bottom: 10px; font-size: 14px;"><strong>SKU:</strong> <span style="color: #666;">WP-001</span></div>' : ""}
                        ${metaShowCategory ? '<div style="margin-bottom: 10px; font-size: 14px;"><strong>Category:</strong> <a href="#" style="color: #0073aa;">Electronics</a></div>' : ""}
                        ${metaShowTags ? '<div style="font-size: 14px;"><strong>Tags:</strong> <a href="#" style="color: #0073aa;">Featured</a>, <a href="#" style="color: #0073aa;">New</a></div>' : ""}
                        <p style="margin-top: 15px; color: #999; font-size: 11px; text-align: center;">
                            <i class="fa fa-info-circle"></i> Product Meta Information
                        </p>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/woo-products.js
  function renderWooProducts(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const wooColumns = parseInt(settings2.columns || 4);
    const wooGap = parseInt(settings2.column_gap || 20);
    const wooRowGap = parseInt(settings2.row_gap || 30);
    const wooBorderRadius = parseInt(settings2.product_border_radius || 8);
    const wooCardBg = settings2.card_bg_color || "#ffffff";
    const wooTitleColor = settings2.title_color || "#344047";
    const wooPriceColor = settings2.price_color || "#92003b";
    const wooBtnBg = settings2.button_bg_color || "#92003b";
    const wooBtnText = settings2.button_text_color || "#ffffff";
    const wooPerPage = parseInt(settings2.products_per_page || 8);
    const wooShowImage = settings2.show_image !== "no";
    const wooShowTitle = settings2.show_title !== "no";
    const wooShowPrice = settings2.show_price !== "no";
    const wooShowRating = settings2.show_rating !== "no";
    const wooShowCart = settings2.show_cart_button !== "no";
    const wooShowBadge = settings2.show_badge !== "no";
    const wooQueryType = settings2.query_type || "recent";
    const wooOrderBy = settings2.orderby || "date";
    const wooOrder = settings2.order || "DESC";
    const wooImageRatio = settings2.image_ratio || "1:1";
    const wooImageHeight = settings2.image_height || 300;
    const wooImageFit = settings2.image_fit || "cover";
    const ratioMap = {
      "1:1": "100%",
      "4:3": "75%",
      "3:4": "133.33%",
      "16:9": "56.25%",
      "custom": wooImageHeight + "px"
    };
    const wooPaddingTop = ratioMap[wooImageRatio] || "100%";
    const wooContainerId = "woo-products-" + element2.id;
    let wooHTML = `<div id="${wooContainerId}" style="box-sizing: border-box;">
                        <div style="text-align: center; padding: 30px; color: #92003b;">
                            <div style="display: inline-block; width: 30px; height: 30px; border: 3px solid #f3f4f6; border-top-color: #92003b; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                            <p style="margin-top: 10px; font-size: 13px; font-weight: 600;">Loading products...</p>
                        </div>
                    </div>
                    <style>@keyframes spin { to { transform: rotate(360deg); } }</style>`;
    setTimeout(() => {
      $.ajax({
        url: ProBuilderEditor.ajaxurl,
        type: "POST",
        data: {
          action: "probuilder_get_woo_products",
          nonce: ProBuilderEditor.nonce,
          query_type: wooQueryType,
          per_page: wooPerPage,
          orderby: wooOrderBy,
          order: wooOrder
        },
        success: function(response) {
          if (response.success && response.data.products && response.data.products.length > 0) {
            const products = response.data.products;
            let productsHTML = `<div style="display: grid; grid-template-columns: repeat(${wooColumns}, 1fr); gap: ${wooRowGap}px ${wooGap}px; width: 100%; box-sizing: border-box;">`;
            products.forEach((product) => {
              productsHTML += `<div class="probuilder-product-card" style="border-radius: ${wooBorderRadius}px; overflow: hidden; background: ${wooCardBg}; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.3s;">`;
              if (wooShowImage) {
                const imageContainerStyle = wooImageRatio !== "custom" ? `position: relative; background: #f8f9fa; overflow: hidden; padding-top: ${wooPaddingTop};` : `position: relative; background: #f8f9fa; overflow: hidden; height: ${wooImageHeight}px;`;
                const imageStyle = wooImageRatio !== "custom" ? `position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: ${wooImageFit}; display: block;` : `width: 100%; height: 100%; object-fit: ${wooImageFit}; display: block;`;
                productsHTML += `<div class="product-image" style="${imageContainerStyle}">`;
                productsHTML += `<img src="${product.image}" style="${imageStyle}" alt="${product.title}">`;
                if (wooShowBadge && product.sale) {
                  productsHTML += `<span class="sale-badge" style="position: absolute; top: 10px; right: 10px; background: #e74c3c; color: #fff; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; z-index: 10;">Sale</span>`;
                }
                productsHTML += `</div>`;
              }
              productsHTML += `<div class="product-content" style="padding: 20px;">`;
              if (wooShowTitle) {
                productsHTML += `<h3 class="product-title" style="margin: 0 0 10px; font-size: 16px; font-weight: 600; line-height: 1.4; color: ${wooTitleColor};"><a href="${product.permalink}" style="color: inherit; text-decoration: none;">${product.title}</a></h3>`;
              }
              if (wooShowRating && product.rating > 0) {
                productsHTML += `<div class="product-rating" style="margin-bottom: 10px; color: #fbbf24; font-size: 14px;">`;
                for (let i = 0; i < 5; i++) {
                  productsHTML += i < product.rating ? "\u2605" : "\u2606";
                }
                productsHTML += `</div>`;
              }
              if (wooShowPrice) {
                productsHTML += `<div class="product-price" style="margin-bottom: 15px; font-size: 18px; font-weight: 700; color: ${wooPriceColor}; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;">${product.price}</div>`;
              }
              if (wooShowCart) {
                productsHTML += `<a href="${product.permalink}" class="button product_type_simple add_to_cart_button ajax_add_to_cart" style="background: ${wooBtnBg}; color: ${wooBtnText}; padding: 12px 24px; text-decoration: none; display: inline-block; border-radius: 6px; font-weight: 600; font-size: 14px; border: none; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;">Add to Cart</a>`;
              }
              productsHTML += `</div></div>`;
            });
            productsHTML += `</div>`;
            $("#" + wooContainerId).html(productsHTML);
            console.log("\u2705 Loaded", products.length, "real products:", products.map((p) => p.title).join(", "));
          } else {
            $("#" + wooContainerId).html(`<div style="padding: 40px; text-align: center; background: #fffbeb; border: 2px dashed #fbbf24; border-radius: 8px; color: #78350f;">
                                        <i class="dashicons dashicons-cart" style="font-size: 48px; opacity: 0.3;"></i>
                                        <p style="margin: 10px 0 0; font-weight: 600;">No products found</p>
                                        <p style="margin: 5px 0 0; font-size: 13px;">Query: ${wooQueryType} | Add products to your WooCommerce store</p>
                                    </div>`);
            console.warn("No products returned from AJAX");
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error loading products:", status, error);
          $("#" + wooContainerId).html(`<div style="padding: 40px; text-align: center; background: #fee2e2; border: 2px solid #ef4444; border-radius: 8px; color: #991b1b;">
                                    <i class="dashicons dashicons-warning" style="font-size: 48px;"></i>
                                    <p style="margin: 10px 0 0; font-weight: 600;">Error loading products</p>
                                    <p style="margin: 5px 0 0; font-size: 13px;">Check browser console for details</p>
                                </div>`);
        }
      });
    }, 50);
    return wooHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/woo-rating.js
  function renderWooRating(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const ratingValue = settings2.rating || 4.5;
    const ratingCount = settings2.show_count !== "no";
    return `<div style="padding: 15px 20px; background: #f5f5f5; border-radius: 8px; text-align: center;">
                        <div style="color: #f90; font-size: 20px; margin-bottom: 5px;">
                            ${"\u2605".repeat(Math.floor(ratingValue))}${"\u2606".repeat(5 - Math.floor(ratingValue))}
                        </div>
                        ${ratingCount ? '<div style="font-size: 13px; color: #666;">(24 customer reviews)</div>' : ""}
                        <p style="margin-top: 10px; color: #999; font-size: 11px;">
                            <i class="fa fa-star"></i> Product Rating Widget
                        </p>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/woo-related.js
  function renderWooRelated(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const relatedCount = settings2.posts_per_page || 4;
    const relatedColumns = settings2.columns || 4;
    return `<div style="padding: 20px; background: #f5f5f5; border-radius: 8px;">
                        <h3 style="margin: 0 0 20px; font-size: 22px; color: #333;">Related Products</h3>
                        <div style="display: grid; grid-template-columns: repeat(${Math.min(relatedColumns, 4)}, 1fr); gap: 15px;">
                            ${Array(Math.min(relatedCount, 4)).fill("").map((_, i) => `
                                <div style="background: #fff; padding: 15px; border-radius: 4px; text-align: center;">
                                    <div style="width: 100%; height: 120px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 4px; margin-bottom: 10px;"></div>
                                    <div style="font-weight: 600; font-size: 14px; margin-bottom: 5px;">Product ${i + 1}</div>
                                    <div style="color: #92003b; font-weight: 600;">$${(i + 1) * 10}.00</div>
                                </div>
                            `).join("")}
                        </div>
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/woo-reviews.js
  function renderWooReviews(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const reviewsCount = settings2.reviews_count || 3;
    return `<div style="padding: 20px; background: #f5f5f5; border-radius: 8px;">
                        <h3 style="margin: 0 0 20px; font-size: 22px; color: #333;">Customer Reviews</h3>
                        ${Array(Math.min(reviewsCount, 3)).fill("").map((_, i) => `
                            <div style="background: #fff; padding: 15px; border-radius: 4px; margin-bottom: 15px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <strong style="color: #333;">Customer ${i + 1}</strong>
                                    <div style="color: #f90; font-size: 14px;">${"\u2605".repeat(5 - i)}${"\u2606".repeat(i)}</div>
                                </div>
                                <p style="margin: 0; color: #666; font-size: 14px; line-height: 1.6;">Great product! Highly recommended for anyone looking for quality.</p>
                            </div>
                        `).join("")}
                    </div>`;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/wp-footer.js
  function renderWpFooter(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const footerId = settings2.footer_id || "";
    const footerLayout = settings2.footer_layout || "columns";
    const footerColumns = settings2.columns || "3";
    const showCopyright = settings2.show_copyright !== "no";
    const copyrightText = settings2.copyright_text || "\xA9 2025 Your Site. All rights reserved.";
    const footerBgColor = settings2.bg_color || "#1f2937";
    const footerTextColor = settings2.text_color || "#e5e7eb";
    const footerLinkColor = settings2.link_color || "#93c5fd";
    const footerPadding = settings2.padding || {
      top: 60,
      right: 30,
      bottom: 30,
      left: 30
    };
    const copyrightBg = settings2.copyright_bg || "#111827";
    const footerStyle = `
                        background: ${footerBgColor};
                        color: ${footerTextColor};
                        padding: ${footerPadding.top}px ${footerPadding.right}px ${footerPadding.bottom}px ${footerPadding.left}px;
                    `;
    let footerHTML = `<div style="${footerStyle}">`;
    if (!footerId) {
      footerHTML += `
                            <div style="padding: 30px; background: rgba(255,255,255,0.1); border: 2px dashed rgba(255,255,255,0.3); border-radius: 8px; text-align: center;">
                                <i class="fa fa-window-minimize" style="font-size: 36px; opacity: 0.6; margin-bottom: 10px;"></i>
                                <div style="font-size: 15px; font-weight: 600; margin-bottom: 5px;">WordPress Footer</div>
                                <div style="font-size: 13px; opacity: 0.9;">Select a footer widget area from the settings</div>
                            </div>
                        `;
    } else {
      const contentStyle = footerLayout === "columns" ? `display: grid; grid-template-columns: repeat(${footerColumns}, 1fr); gap: 30px;` : `display: flex; flex-direction: column; gap: 20px;`;
      footerHTML += `<div style="${contentStyle}">`;
      for (let i = 0; i < parseInt(footerColumns); i++) {
        footerHTML += `
                                <div style="padding: 20px; background: rgba(255,255,255,0.05); border-radius: 6px;">
                                    <h3 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600;">Footer Widget ${i + 1}</h3>
                                    <ul style="list-style: none; padding: 0; margin: 0; font-size: 14px; line-height: 2;">
                                        <li><a href="#" style="color: ${footerLinkColor}; text-decoration: none;">Link ${i * 3 + 1}</a></li>
                                        <li><a href="#" style="color: ${footerLinkColor}; text-decoration: none;">Link ${i * 3 + 2}</a></li>
                                        <li><a href="#" style="color: ${footerLinkColor}; text-decoration: none;">Link ${i * 3 + 3}</a></li>
                                    </ul>
                                </div>
                            `;
      }
      footerHTML += `</div>`;
    }
    if (showCopyright) {
      footerHTML += `
                            <div style="background: ${copyrightBg}; text-align: center; padding: 20px; margin-top: 30px; font-size: 14px;">
                                ${copyrightText}
                            </div>
                        `;
    }
    footerHTML += `</div>`;
    return footerHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/wp-header.js
  function renderWpHeader(context2) {
    var _a;
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const headerMenuId = settings2.menu_id || "";
    const headerType = settings2.header_type || "horizontal";
    const showLogo = settings2.show_logo !== "no";
    const customLogo = ((_a = settings2.custom_logo) == null ? void 0 : _a.url) || "";
    const logoWidth = settings2.logo_width || 150;
    const headerBgColor = settings2.bg_color || "#ffffff";
    const headerMenuColor = settings2.menu_color || "#333333";
    const headerPadding = settings2.padding || {
      top: 20,
      right: 30,
      bottom: 20,
      left: 30
    };
    const headerShadow = settings2.box_shadow !== "no";
    const headerStyle = `
                        background: ${headerBgColor};
                        padding: ${headerPadding.top}px ${headerPadding.right}px ${headerPadding.bottom}px ${headerPadding.left}px;
                        display: flex;
                        align-items: center;
                        ${headerType === "horizontal" ? "justify-content: space-between; flex-direction: row;" : "flex-direction: column; gap: 20px;"}
                        ${headerShadow ? "box-shadow: 0 2px 10px rgba(0,0,0,0.1);" : ""}
                    `;
    let headerHTML = `<div style="${headerStyle}">`;
    if (showLogo) {
      const logoSrc = customLogo || "https://via.placeholder.com/150x50/92003b/ffffff?text=LOGO";
      headerHTML += `<div style="flex-shrink: 0;">
                            <img src="${logoSrc}" alt="Logo" style="max-width: ${logoWidth}px; height: auto; display: block;">
                        </div>`;
    }
    headerHTML += `<nav style="flex-grow: 1;">
                        <ul style="list-style: none; margin: 0; padding: 0; display: flex; ${headerType === "horizontal" ? "flex-direction: row; gap: 30px; justify-content: flex-end;" : "flex-direction: column; gap: 15px; align-items: center;"}">
                            <li><a href="#" style="color: ${headerMenuColor}; text-decoration: none; font-weight: 500;">Home</a></li>
                            <li><a href="#" style="color: ${headerMenuColor}; text-decoration: none; font-weight: 500;">About</a></li>
                            <li><a href="#" style="color: ${headerMenuColor}; text-decoration: none; font-weight: 500;">Services</a></li>
                            <li><a href="#" style="color: ${headerMenuColor}; text-decoration: none; font-weight: 500;">Contact</a></li>
                        </ul>
                    </nav>`;
    headerHTML += `</div>`;
    return headerHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/wp-sidebar.js
  function renderWpSidebar(context2) {
    const {
      element: element2,
      settings: settings2,
      spacingStyles: spacingStyles2,
      app: app2,
      depth: depth2 = 0,
      widget: widget2
    } = context2;
    const sidebarId = settings2.sidebar_id || "";
    const sidebarBgColor = settings2.bg_color || "";
    const sidebarPadding = settings2.padding || {
      top: 20,
      right: 20,
      bottom: 20,
      left: 20
    };
    const sidebarMargin = settings2.margin || {
      top: 0,
      right: 0,
      bottom: 20,
      left: 0
    };
    const sidebarRadius = settings2.border_radius || 0;
    const sidebarShadow = settings2.box_shadow === "yes";
    const sidebarWrapperStyle = `
                        ${sidebarBgColor ? `background: ${sidebarBgColor};` : ""}
                        padding: ${sidebarPadding.top}px ${sidebarPadding.right}px ${sidebarPadding.bottom}px ${sidebarPadding.left}px;
                        margin: ${sidebarMargin.top}px ${sidebarMargin.right}px ${sidebarMargin.bottom}px ${sidebarMargin.left}px;
                        ${sidebarRadius > 0 ? `border-radius: ${sidebarRadius}px;` : ""}
                        ${sidebarShadow ? "box-shadow: 0 4px 15px rgba(0,0,0,0.1);" : ""}
                    `;
    let sidebarHTML = `<div style="${sidebarWrapperStyle}">`;
    if (!sidebarId) {
      sidebarHTML += `
                            <div style="padding: 30px; background: #e3f2fd; border: 2px dashed #2196f3; border-radius: 8px; text-align: center; color: #1976d2;">
                                <i class="fa fa-sidebar" style="font-size: 36px; opacity: 0.6; margin-bottom: 10px;"></i>
                                <div style="font-size: 15px; font-weight: 600; margin-bottom: 5px;">WordPress Sidebar</div>
                                <div style="font-size: 13px;">Select a sidebar from the settings</div>
                            </div>
                        `;
    } else {
      sidebarHTML += `
                            <div style="padding: 20px; background: #f8f9fa; border-radius: 6px;">
                                <h3 style="margin: 0 0 15px 0; font-size: 16px; font-weight: 600; color: #333;">Sidebar Widget Area</h3>
                                <div style="font-size: 13px; color: #666; line-height: 1.6;">
                                    <p style="margin: 0 0 10px 0;">\u2022 Sidebar widgets will appear here</p>
                                    <p style="margin: 0 0 10px 0;">\u2022 Configured in Appearance \u2192 Widgets</p>
                                    <p style="margin: 0;">\u2022 Preview shows on frontend</p>
                                </div>
                            </div>
                        `;
    }
    sidebarHTML += `</div>`;
    return sidebarHTML;
  }

  // wp-content/plugins/probuilder/assets/js/src/widgets/index.js
  var widgetRenderers = {
    "accordion": renderAccordion,
    "alert": renderAlert,
    "anchor": renderAnchor,
    "animated-headline": renderAnimatedHeadline,
    "animated-text": renderAnimatedText,
    "archive-title": renderArchiveTitle,
    "archives": renderArchives,
    "area-chart": renderAreaChart,
    "audio": renderAudio,
    "author-box": renderAuthorBox,
    "back-to-top": renderBackToTop,
    "bar-chart": renderBarChart,
    "before-after": renderBeforeAfter,
    "blockquote": renderBlockquote,
    "blog-posts": renderBlogPosts,
    "breadcrumbs": renderBreadcrumbs,
    "button": renderButton,
    "calendly": renderCalendly,
    "call-to-action": renderCallToAction,
    "carousel": renderCarousel,
    "category-list": renderCategoryList,
    "code-highlight": renderCodeHighlight,
    "contact-form": renderContactForm,
    "container": renderContainer,
    "countdown": renderCountdown,
    "counter": renderCounter,
    "custom-css": renderCustomCss,
    "divider": renderDivider,
    "donut-chart": renderDonutChart,
    "facebook-embed": renderFacebookEmbed,
    "faq": renderFaq,
    "feature-list": renderFeatureList,
    "flexbox": renderFlexbox,
    "flip-box": renderFlipBox,
    "form-builder": renderFormBuilder,
    "gallery": renderGallery,
    "heading": renderHeading,
    "google-maps": renderGoogleMaps,
    "grid-layout": renderGridLayout,
    "hotspot": renderHotspot,
    "html-code": renderHtmlCode,
    "icon": renderIcon,
    "icon-box": renderIconBox,
    "icon-list": renderIconList,
    "image": renderImage,
    "image-box": renderImageBox,
    "image-comparison": renderImageComparison,
    "info-box": renderInfoBox,
    "instagram-feed": renderInstagramFeed,
    "line-chart": renderLineChart,
    "login": renderLogin,
    "logo-grid": renderLogoGrid,
    "loop-builder": renderLoopBuilder,
    "lottie": renderLottie,
    "map": renderMap,
    "mega-menu": renderMegaMenu,
    "menu": renderMenu,
    "newsletter": renderNewsletter,
    "notification": renderNotification,
    "offcanvas": renderOffcanvas,
    "parallax-image": renderParallaxImage,
    "paypal-button": renderPaypalButton,
    "pie-chart": renderPieChart,
    "portfolio": renderPortfolio,
    "post-author": renderPostAuthor,
    "post-comments": renderPostComments,
    "post-date": renderPostDate,
    "post-excerpt": renderPostExcerpt,
    "post-featured-image": renderPostFeaturedImage,
    "post-navigation": renderPostNavigation,
    "post-title": renderPostTitle,
    "price-list": renderPriceList,
    "pricing-table": renderPricingTable,
    "progress-bar": renderProgressBar,
    "progress-tracker": renderProgressTracker,
    "reading-progress": renderReadingProgress,
    "recent-posts": renderRecentPosts,
    "reviews": renderReviews,
    "scroll-snap": renderScrollSnap,
    "search-form": renderSearchForm,
    "share-buttons": renderShareButtons,
    "shortcode": renderShortcode,
    "sidebar": renderSidebar,
    "site-logo": renderSiteLogo,
    "sitemap": renderSitemap,
    "slider": renderSlider,
    "spacer": renderSpacer,
    "social-icons": renderSocialIcons,
    "star-rating": renderStarRating,
    "sticky-video": renderStickyVideo,
    "stripe-button": renderStripeButton,
    "table-of-contents": renderTableOfContents,
    "tabs": renderTabs,
    "tag-cloud": renderTagCloud,
    "team-member": renderTeamMember,
    "testimonial": renderTestimonial,
    "text": renderText,
    "text-path": renderTextPath,
    "timeline": renderTimeline,
    "toggle": renderToggle,
    "twitter-embed": renderTwitterEmbed,
    "video": renderVideo,
    "woo-add-to-cart": renderWooAddToCart,
    "woo-breadcrumbs": renderWooBreadcrumbs,
    "woo-cart": renderWooCart,
    "woo-categories": renderWooCategories,
    "woo-meta": renderWooMeta,
    "woo-products": renderWooProducts,
    "woo-rating": renderWooRating,
    "woo-related": renderWooRelated,
    "woo-reviews": renderWooReviews,
    "wp-footer": renderWpFooter,
    "wp-header": renderWpHeader,
    "wp-sidebar": renderWpSidebar
  };

  // wp-content/plugins/probuilder/assets/js/src/probuilder-app.js
  (function($2) {
    "use strict";
    const ProBuilder2 = {
      widgets: [],
      elements: [],
      selectedElement: null,
      history: [],
      historyIndex: -1,
      maxHistorySize: 50,
      isPerformingHistoryAction: false,
      isGridCellResizing: false,
      isNestedDropInProgress: false,
      globalStyles: {
        colors: [
          { id: "primary", name: "Primary", color: "#344047" },
          { id: "secondary", name: "Secondary", color: "#2c3e50" },
          { id: "accent", name: "Accent", color: "#4a5a6b" },
          { id: "text", name: "Text", color: "#495157" }
        ],
        typography: {
          h1: { fontSize: 48, fontWeight: 700, lineHeight: 1.2 },
          h2: { fontSize: 36, fontWeight: 600, lineHeight: 1.3 },
          h3: { fontSize: 28, fontWeight: 600, lineHeight: 1.4 },
          body: { fontSize: 16, fontWeight: 400, lineHeight: 1.6 }
        },
        buttons: {
          primary: { bgColor: "#344047", textColor: "#ffffff", borderRadius: 4 },
          secondary: { bgColor: "#2c3e50", textColor: "#ffffff", borderRadius: 4 },
          outline: { bgColor: "transparent", textColor: "#344047", borderRadius: 4, border: "2px solid #344047" }
        }
      },
      /**
       * Ensure color inputs receive valid hex values
       */
      sanitizeColorValue: function(value, fallback = "#000000") {
        if (typeof value !== "string") {
          return fallback;
        }
        const trimmed = value.trim();
        if (trimmed.toLowerCase() === "transparent") {
          return fallback;
        }
        const shortHexMatch = trimmed.match(/^#([0-9a-fA-F]{3})$/);
        if (shortHexMatch) {
          const [r, g, b] = shortHexMatch[1].split("");
          return `#${r}${r}${g}${g}${b}${b}`.toLowerCase();
        }
        if (/^#([0-9a-fA-F]{6})$/.test(trimmed)) {
          return trimmed.toLowerCase();
        }
        return fallback;
      },
      /**
       * Initialize
       */
      init: function() {
        console.log("ProBuilder initializing...");
        console.log("ProBuilderEditor object:", typeof ProBuilderEditor !== "undefined" ? "EXISTS" : "MISSING");
        if (typeof ProBuilderEditor === "undefined") {
          console.error("ProBuilderEditor is not defined! Check if scripts are loading.");
          alert("ProBuilder Error: Editor scripts not loaded. Please refresh the page.");
          return;
        }
        this.widgets = ProBuilderEditor.widgets || [];
        console.log("Widgets loaded:", this.widgets.length);
        if (this.widgets.length === 0) {
          console.error("No widgets found! Check widget registration.");
        }
        if (this.widgets.length > 0) {
          const headingWidget = this.widgets.find((w) => w.name === "heading");
          if (headingWidget) {
            console.log("==================== HEADING WIDGET DEBUG ====================");
            console.log("Total controls:", Object.keys(headingWidget.controls).length);
            console.log("Controls object:", headingWidget.controls);
            let contentCount = 0, styleCount = 0, advancedCount = 0;
            Object.keys(headingWidget.controls).forEach((key) => {
              const control = headingWidget.controls[key];
              const tab = control.tab || "NO_TAB";
              const type = control.type || "NO_TYPE";
              console.log(`  ${key}: tab="${tab}" type="${type}"`);
              if (tab === "content") contentCount++;
              if (tab === "style") styleCount++;
              if (tab === "advanced") advancedCount++;
            });
            console.log("Tab distribution:");
            console.log(`  - Content: ${contentCount} controls`);
            console.log(`  - Style: ${styleCount} controls`);
            console.log(`  - Advanced: ${advancedCount} controls`);
            console.log("==============================================================");
          }
        }
        this.loadSavedData();
        if (!Array.isArray(this.elements)) {
          console.error("\u274C CRITICAL: this.elements is not an array after loadSavedData!");
          console.error("Type:", typeof this.elements);
          console.error("Value:", this.elements);
          this.elements = [];
          console.log("\u2705 Reset to empty array");
        }
        this.renderWidgets();
        setTimeout(() => {
          this.initDragDrop();
          console.log("Drag and drop initialized");
        }, 100);
        this.bindEvents();
        this.initResizablePanels();
        this.initSidebarToggles();
        this.updateEmptyState();
        this.updatePanelResponsiveness();
        this.loadTemplates();
        this.showSettingsPlaceholder();
        console.log("ProBuilder initialized successfully!");
        console.log("- Widgets:", this.widgets.length);
        console.log("- Elements:", this.elements.length);
        window.ProBuilderApp = this;
        this.initKeyboardShortcuts();
        this.initGlobalStyles();
        this.saveHistory();
      },
      /**
       * Initialize keyboard shortcuts
       */
      initKeyboardShortcuts: function() {
        const self2 = this;
        $2(document).on("keydown", function(e) {
          if ((e.ctrlKey || e.metaKey) && e.key === "z" && !e.shiftKey) {
            e.preventDefault();
            self2.undo();
            return false;
          }
          if ((e.ctrlKey || e.metaKey) && (e.shiftKey && e.key === "z" || e.key === "y")) {
            e.preventDefault();
            self2.redo();
            return false;
          }
          if ((e.ctrlKey || e.metaKey) && e.key === "s") {
            e.preventDefault();
            self2.saveData();
            return false;
          }
          if ((e.ctrlKey || e.metaKey) && e.key === "c" && self2.selectedElement) {
            e.preventDefault();
            self2.copyElement();
            return false;
          }
          if ((e.ctrlKey || e.metaKey) && e.key === "v" && self2.copiedElement) {
            e.preventDefault();
            self2.pasteElement();
            return false;
          }
          if ((e.ctrlKey || e.metaKey) && e.key === "d" && self2.selectedElement) {
            e.preventDefault();
            self2.duplicateElement();
            return false;
          }
          if (e.key === "Delete" && self2.selectedElement) {
            e.preventDefault();
            self2.deleteSelectedElement();
            return false;
          }
        });
        console.log("\u2705 Keyboard shortcuts initialized");
      },
      /**
       * Initialize global styles
       */
      initGlobalStyles: function() {
        const self2 = this;
        const savedStyles = localStorage.getItem("probuilder_global_styles");
        if (savedStyles) {
          try {
            this.globalStyles = JSON.parse(savedStyles);
          } catch (e) {
            console.error("Error loading global styles:", e);
          }
        }
        this.renderGlobalColors();
        this.renderGlobalTypography();
        this.renderGlobalButtons();
        $2("#add-global-color").on("click", function() {
          self2.addGlobalColor();
        });
        $2("#probuilder-layout-width").on("change", function() {
          const value = $2(this).val();
          if (value === "boxed") {
            $2("#probuilder-boxed-width-control").show();
          } else {
            $2("#probuilder-boxed-width-control").hide();
          }
        });
        $2("#apply-layout-settings").on("click", function() {
          self2.applyLayoutSettings();
        });
        self2.loadLayoutSettings();
        console.log("\u2705 Global styles initialized");
      },
      /**
       * Render global colors
       */
      renderGlobalColors: function() {
        const $container = $2("#probuilder-color-palette");
        $container.empty();
        this.globalStyles.colors.forEach((color, index) => {
          const $colorItem = $2(`
                    <div class="probuilder-global-color-item" data-index="${index}">
                        <div class="probuilder-color-preview" style="background: ${color.color};">
                            <input type="color" class="probuilder-color-picker-input" value="${color.color}" data-index="${index}">
                        </div>
                        <div class="probuilder-color-info">
                            <input type="text" class="probuilder-color-name" value="${color.name}" data-index="${index}" placeholder="Color name">
                            <div class="probuilder-color-value">${color.color}</div>
                        </div>
                        <button class="probuilder-color-delete" data-index="${index}" title="Delete">
                            <i class="dashicons dashicons-trash"></i>
                        </button>
                    </div>
                `);
          $container.append($colorItem);
        });
        const self2 = this;
        $container.find(".probuilder-color-picker-input").on("change", function() {
          const index = $2(this).data("index");
          const newColor = $2(this).val();
          self2.updateGlobalColor(index, "color", newColor);
        });
        $container.find(".probuilder-color-name").on("change", function() {
          const index = $2(this).data("index");
          const newName = $2(this).val();
          self2.updateGlobalColor(index, "name", newName);
        });
        $container.find(".probuilder-color-delete").on("click", function() {
          const index = $2(this).data("index");
          self2.deleteGlobalColor(index);
        });
      },
      /**
       * Render global typography
       */
      renderGlobalTypography: function() {
        const $container = $2("#probuilder-typography-presets");
        $container.empty();
        const types = ["h1", "h2", "h3", "body"];
        const labels = { h1: "Heading 1", h2: "Heading 2", h3: "Heading 3", body: "Body Text" };
        types.forEach((type) => {
          const typo = this.globalStyles.typography[type];
          const $typoItem = $2(`
                    <div class="probuilder-typo-preset" data-type="${type}">
                        <div class="probuilder-typo-preview">
                            <span style="font-size: ${typo.fontSize}px; font-weight: ${typo.fontWeight}; line-height: ${typo.lineHeight};">
                                ${labels[type]}
                            </span>
                        </div>
                        <div class="probuilder-typo-controls">
                            <label>
                                <span>Size:</span>
                                <input type="number" class="probuilder-typo-fontsize" value="${typo.fontSize}" data-type="${type}" min="10" max="100">
                            </label>
                            <label>
                                <span>Weight:</span>
                                <select class="probuilder-typo-weight" data-type="${type}">
                                    <option value="300" ${typo.fontWeight == 300 ? "selected" : ""}>Light</option>
                                    <option value="400" ${typo.fontWeight == 400 ? "selected" : ""}>Normal</option>
                                    <option value="600" ${typo.fontWeight == 600 ? "selected" : ""}>Semi Bold</option>
                                    <option value="700" ${typo.fontWeight == 700 ? "selected" : ""}>Bold</option>
                                </select>
                            </label>
                            <label>
                                <span>Line Height:</span>
                                <input type="number" class="probuilder-typo-lineheight" value="${typo.lineHeight}" data-type="${type}" min="0.5" max="3" step="0.1">
                            </label>
                        </div>
                    </div>
                `);
          $container.append($typoItem);
        });
        const self2 = this;
        $container.find(".probuilder-typo-fontsize, .probuilder-typo-weight, .probuilder-typo-lineheight").on("change", function() {
          const type = $2(this).data("type");
          self2.updateTypography(type);
        });
      },
      /**
       * Render global buttons
       */
      renderGlobalButtons: function() {
        const $container = $2("#probuilder-button-presets");
        $container.empty();
        const buttonTypes = ["primary", "secondary", "outline"];
        const labels = { primary: "Primary", secondary: "Secondary", outline: "Outline" };
        Object.keys(this.globalStyles.buttons).forEach((type) => {
          const btn = this.globalStyles.buttons[type];
          const bgColorInputValue = this.sanitizeColorValue(btn.bgColor, "#000000");
          const textColorInputValue = this.sanitizeColorValue(btn.textColor, "#000000");
          const $btnItem = $2(`
                    <div class="probuilder-button-preset" data-type="${type}">
                        <div class="probuilder-button-preview">
                            <button style="
                                background: ${btn.bgColor};
                                color: ${btn.textColor};
                                border-radius: ${btn.borderRadius}px;
                                border: ${btn.border || "none"};
                                padding: 12px 24px;
                                cursor: default;
                            ">
                                ${labels[type]} Button
                            </button>
                        </div>
                        <div class="probuilder-button-controls">
                            <label>
                                <span>Background:</span>
                                <input type="color" class="probuilder-btn-bgcolor" value="${bgColorInputValue}" data-type="${type}">
                            </label>
                            <label>
                                <span>Text:</span>
                                <input type="color" class="probuilder-btn-textcolor" value="${textColorInputValue}" data-type="${type}">
                            </label>
                            <label>
                                <span>Border Radius:</span>
                                <input type="number" class="probuilder-btn-radius" value="${btn.borderRadius}" data-type="${type}" min="0" max="50">
                            </label>
                        </div>
                    </div>
                `);
          $container.append($btnItem);
        });
        const self2 = this;
        $container.find(".probuilder-btn-bgcolor, .probuilder-btn-textcolor, .probuilder-btn-radius").on("change", function() {
          const type = $2(this).data("type");
          self2.updateButtonStyle(type);
        });
      },
      /**
       * Add global color
       */
      addGlobalColor: function() {
        const newColor = {
          id: "color-" + Date.now(),
          name: "New Color",
          color: "#" + Math.floor(Math.random() * 16777215).toString(16)
        };
        this.globalStyles.colors.push(newColor);
        this.renderGlobalColors();
        this.saveGlobalStyles();
        this.showToast("Color added!");
      },
      /**
       * Update global color
       */
      updateGlobalColor: function(index, property, value) {
        if (this.globalStyles.colors[index]) {
          this.globalStyles.colors[index][property] = value;
          if (property === "color") {
            $2(`.probuilder-color-preview`).eq(index).css("background", value);
            $2(`.probuilder-color-value`).eq(index).text(value);
          }
          this.saveGlobalStyles();
          this.applyGlobalStyles();
        }
      },
      /**
       * Delete global color
       */
      deleteGlobalColor: function(index) {
        if (confirm("Delete this color?")) {
          this.globalStyles.colors.splice(index, 1);
          this.renderGlobalColors();
          this.saveGlobalStyles();
          this.showToast("Color deleted!");
        }
      },
      /**
       * Update typography
       */
      updateTypography: function(type) {
        const $container = $2(`.probuilder-typo-preset[data-type="${type}"]`);
        const fontSize = $container.find(".probuilder-typo-fontsize").val();
        const fontWeight = $container.find(".probuilder-typo-weight").val();
        const lineHeight = $container.find(".probuilder-typo-lineheight").val();
        this.globalStyles.typography[type] = {
          fontSize: parseInt(fontSize),
          fontWeight: parseInt(fontWeight),
          lineHeight: parseFloat(lineHeight)
        };
        $container.find(".probuilder-typo-preview span").css({
          "font-size": fontSize + "px",
          "font-weight": fontWeight,
          "line-height": lineHeight
        });
        this.saveGlobalStyles();
        this.applyGlobalStyles();
      },
      /**
       * Update button style
       */
      updateButtonStyle: function(type) {
        const $container = $2(`.probuilder-button-preset[data-type="${type}"]`);
        const bgColor = $container.find(".probuilder-btn-bgcolor").val();
        const textColor = $container.find(".probuilder-btn-textcolor").val();
        const borderRadius = $container.find(".probuilder-btn-radius").val();
        this.globalStyles.buttons[type].bgColor = bgColor;
        this.globalStyles.buttons[type].textColor = textColor;
        this.globalStyles.buttons[type].borderRadius = parseInt(borderRadius);
        $container.find(".probuilder-button-preview button").css({
          "background": bgColor,
          "color": textColor,
          "border-radius": borderRadius + "px"
        });
        this.saveGlobalStyles();
        this.applyGlobalStyles();
      },
      /**
       * Save global styles
       */
      saveGlobalStyles: function() {
        localStorage.setItem("probuilder_global_styles", JSON.stringify(this.globalStyles));
        console.log("\u2705 Global styles saved");
      },
      /**
       * Load layout settings from server
       */
      loadLayoutSettings: function() {
        const self2 = this;
        $2.ajax({
          url: ProBuilderEditor.ajax_url,
          type: "POST",
          data: {
            action: "probuilder_get_global_styles",
            nonce: ProBuilderEditor.nonce
          },
          success: function(response) {
            const layout = response.success && response.data && response.data.layout ? response.data.layout : { content_width: "boxed", boxed_width: "1400px", boxed_padding: "15px" };
            const contentWidth = layout.content_width || "boxed";
            const boxedWidth = layout.boxed_width || "1400px";
            const boxedPadding = layout.boxed_padding || "15px";
            $2("#probuilder-layout-width").val(contentWidth);
            $2("#probuilder-boxed-width").val(boxedWidth);
            $2("#probuilder-layout-padding").val(boxedPadding);
            if (contentWidth === "full") {
              $2("#probuilder-boxed-width-control").hide();
            } else {
              $2("#probuilder-boxed-width-control").show();
            }
            const $previewArea = $2("#probuilder-preview-area");
            if (contentWidth === "boxed") {
              $previewArea.css({
                "max-width": boxedWidth,
                "margin": "0 auto",
                "padding": "0 " + boxedPadding,
                "box-sizing": "border-box"
              });
            } else {
              $previewArea.css({
                "max-width": "100%",
                "margin": "0",
                "padding": "0"
              });
            }
            console.log("\u2705 Layout settings loaded and applied:", layout);
          },
          error: function() {
            console.error("Failed to load layout settings");
          }
        });
      },
      /**
       * Apply layout settings
       */
      applyLayoutSettings: function() {
        const self2 = this;
        const contentWidth = $2("#probuilder-layout-width").val();
        const boxedWidth = $2("#probuilder-boxed-width").val();
        const boxedPadding = $2("#probuilder-layout-padding").val();
        $2.ajax({
          url: ProBuilderEditor.ajax_url,
          type: "POST",
          data: {
            action: "probuilder_save_global_styles",
            nonce: ProBuilderEditor.nonce,
            styles: JSON.stringify({
              layout: {
                content_width: contentWidth,
                boxed_width: boxedWidth,
                boxed_padding: boxedPadding
              }
            })
          },
          success: function(response) {
            console.log("Layout save response:", response);
            if (response.success) {
              const $previewArea = $2("#probuilder-preview-area");
              if (contentWidth === "boxed") {
                $previewArea.css({
                  "max-width": boxedWidth,
                  "margin": "0 auto",
                  "padding": "0 " + boxedPadding,
                  "box-sizing": "border-box"
                });
              } else {
                $previewArea.css({
                  "max-width": "100%",
                  "margin": "0",
                  "padding": "0"
                });
              }
              self2.showNotification("Layout settings applied!", "success");
              console.log("\u2705 Layout settings saved and applied to preview");
            } else {
              const errorMsg = response.data && response.data.message ? response.data.message : "Unknown error";
              console.error("\u274C Layout save failed:", errorMsg, response);
              self2.showNotification("Failed to save: " + errorMsg, "error");
            }
          },
          error: function(xhr, status, error) {
            console.error("\u274C AJAX error:", { xhr, status, error });
            console.error("Response text:", xhr.responseText);
            self2.showNotification("AJAX Error: " + (error || status), "error");
          }
        });
      },
      /**
       * Get global color by ID
       */
      getGlobalColor: function(colorId) {
        const color = this.globalStyles.colors.find((c) => c.id === colorId);
        return color ? color.color : null;
      },
      /**
       * Apply global styles to all elements
       */
      applyGlobalStyles: function() {
        console.log("\u{1F3A8} Applying global styles to all elements...");
        this.elements.forEach((element2) => {
          this.updateElementPreview(element2);
        });
        console.log("\u2705 Global styles applied to", this.elements.length, "elements");
      },
      /**
       * Save current state to history
       */
      saveHistory: function() {
        if (this.isPerformingHistoryAction) {
          return;
        }
        const state = JSON.parse(JSON.stringify(this.elements));
        if (this.historyIndex < this.history.length - 1) {
          this.history = this.history.slice(0, this.historyIndex + 1);
        }
        this.history.push(state);
        if (this.history.length > this.maxHistorySize) {
          this.history.shift();
        } else {
          this.historyIndex++;
        }
        this.updateHistoryButtons();
        console.log("\u{1F4DD} History saved. Index:", this.historyIndex, "Total:", this.history.length);
      },
      /**
       * Undo
       */
      undo: function() {
        if (this.historyIndex <= 0) {
          console.log("\u26A0\uFE0F Nothing to undo");
          return;
        }
        this.historyIndex--;
        this.restoreHistory();
        console.log("\u21A9\uFE0F Undo. Index:", this.historyIndex);
      },
      /**
       * Redo
       */
      redo: function() {
        if (this.historyIndex >= this.history.length - 1) {
          console.log("\u26A0\uFE0F Nothing to redo");
          return;
        }
        this.historyIndex++;
        this.restoreHistory();
        console.log("\u21AA\uFE0F Redo. Index:", this.historyIndex);
      },
      /**
       * Restore state from history
       */
      restoreHistory: function() {
        this.isPerformingHistoryAction = true;
        const state = JSON.parse(JSON.stringify(this.history[this.historyIndex]));
        this.elements = state;
        console.log("\u{1F504} Restoring history state with", this.elements.length, "elements");
        $2("#probuilder-preview-area").empty();
        this.elements.forEach((element2) => {
          this.renderElement(element2);
        });
        this.updateEmptyState();
        setTimeout(() => {
          this.makeContainersDroppable();
          this.reinitializeSidebarWidgets();
          this.reinitializePreviewArea();
          console.log("\u2705 History restored and drag & drop reinitialized");
        }, 100);
        this.updateHistoryButtons();
        this.isPerformingHistoryAction = false;
      },
      /**
       * Update history button states
       */
      updateHistoryButtons: function() {
        const canUndo = this.historyIndex > 0;
        const canRedo = this.historyIndex < this.history.length - 1;
        $2("#probuilder-undo").prop("disabled", !canUndo).toggleClass("disabled", !canUndo);
        $2("#probuilder-redo").prop("disabled", !canRedo).toggleClass("disabled", !canRedo);
      },
      /**
       * Copy element
       */
      copyElement: function() {
        if (!this.selectedElement) return;
        const element2 = this.elements.find((el) => el.id === this.selectedElement);
        if (element2) {
          this.copiedElement = JSON.parse(JSON.stringify(element2));
          console.log("\u{1F4CB} Element copied:", element2.widgetType);
          this.showToast("Element copied! Press Ctrl+V to paste");
        }
      },
      /**
       * Paste element
       */
      pasteElement: function() {
        if (!this.copiedElement) return;
        if (!Array.isArray(this.elements)) {
          console.warn("\u26A0\uFE0F this.elements was not an array! Initializing as empty array.");
          this.elements = [];
        }
        const newElement = this.cloneElementData(this.copiedElement);
        this.elements.push(newElement);
        this.renderElement(newElement);
        this.updateEmptyState();
        this.saveHistory();
        console.log("\u{1F4CC} Element pasted:", newElement.widgetType);
        this.showToast("Element pasted!");
      },
      /**
       * Duplicate element
       */
      duplicateElement: function() {
        if (!this.selectedElement) return;
        if (!Array.isArray(this.elements)) {
          console.warn("\u26A0\uFE0F this.elements was not an array! Initializing as empty array.");
          this.elements = [];
          return;
        }
        const element2 = this.elements.find((el) => el.id === this.selectedElement);
        if (element2) {
          const newElement = this.cloneElementData(element2);
          const index = this.elements.findIndex((el) => el.id === this.selectedElement);
          this.elements.splice(index + 1, 0, newElement);
          this.renderElement(newElement);
          this.updateEmptyState();
          this.saveHistory();
          console.log("\u{1F504} Element duplicated:", newElement.widgetType);
          this.showToast("Element duplicated!");
        }
      },
      /**
       * Delete selected element
       */
      deleteSelectedElement: function() {
        if (!this.selectedElement) return;
        this.deleteElement(this.selectedElement);
        this.showToast("Element deleted!");
      },
      /**
       * Show toast notification
       */
      showToast: function(message) {
        const toast = $2(`
                <div class="probuilder-toast" style="
                    position: fixed;
                    bottom: 30px;
                    left: 50%;
                    transform: translateX(-50%);
                    background: #333;
                    color: white;
                    padding: 12px 24px;
                    border-radius: 6px;
                    z-index: 99999;
                    font-size: 14px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                    animation: slideUp 0.3s ease;
                ">
                    ${message}
                </div>
            `);
        $2("body").append(toast);
        setTimeout(() => {
          toast.fadeOut(300, function() {
            $2(this).remove();
          });
        }, 2e3);
      },
      /**
       * Show notification with type (success/error/info)
       */
      showNotification: function(message, type = "info") {
        const colors = {
          "success": "#10b981",
          "error": "#ef4444",
          "info": "#3b82f6",
          "warning": "#f59e0b"
        };
        const icons = {
          "success": "\u2713",
          "error": "\u2715",
          "info": "\u2139",
          "warning": "\u26A0"
        };
        const bgColor = colors[type] || colors.info;
        const icon = icons[type] || icons.info;
        const notification = $2(`
                <div class="probuilder-notification" style="
                    position: fixed;
                    bottom: 30px;
                    left: 50%;
                    transform: translateX(-50%);
                    background: ${bgColor};
                    color: white;
                    padding: 14px 24px;
                    border-radius: 8px;
                    z-index: 99999;
                    font-size: 14px;
                    font-weight: 500;
                    box-shadow: 0 4px 16px rgba(0,0,0,0.3);
                    animation: slideUp 0.3s ease;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                ">
                    <span style="font-size: 18px; line-height: 1;">${icon}</span>
                    <span>${message}</span>
                </div>
            `);
        $2("body").append(notification);
        setTimeout(() => {
          notification.fadeOut(300, function() {
            $2(this).remove();
          });
        }, 3e3);
      },
      /**
       * Import template data
       */
      importTemplate: function(templateData) {
        console.log("Importing template:", templateData);
        if (!templateData || !Array.isArray(templateData)) {
          console.error("Invalid template data");
          return;
        }
        if (!Array.isArray(this.elements)) {
          console.warn("\u26A0\uFE0F this.elements was not an array! Initializing as empty array.");
          this.elements = [];
        }
        templateData.forEach((elementData) => {
          const newElement = this.cloneElementData(elementData);
          this.elements.push(newElement);
          this.renderElement(newElement);
        });
        this.updateEmptyState();
        this.makeContainersDroppable();
        console.log("\u2705 Template imported successfully! Elements added:", templateData.length);
      },
      /**
       * Clone element data with new IDs
       */
      cloneElementData: function(elementData) {
        const newElement = {
          id: "element-" + Date.now() + "-" + Math.random().toString(36).substr(2, 9),
          widgetType: elementData.widgetType,
          settings: JSON.parse(JSON.stringify(elementData.settings || {})),
          children: []
        };
        if (elementData.children && elementData.children.length > 0) {
          newElement.children = elementData.children.map((child) => this.cloneElementData(child));
        }
        return newElement;
      },
      /**
       * Normalize nested structure to array (handles objects with numeric keys)
       */
      normalizeStructureToArray: function(value) {
        if (Array.isArray(value)) {
          return value.slice();
        }
        if (value && typeof value === "object") {
          const arr = [];
          Object.keys(value).forEach((key) => {
            const index = parseInt(key, 10);
            if (!Number.isNaN(index)) {
              arr[index] = value[key];
            }
          });
          return arr;
        }
        return [];
      },
      /**
       * Ensure grid layout elements have proper children/custom template populated
       */
      ensureGridElementStructure: function(element2) {
        if (!element2 || element2.widgetType !== "grid-layout") {
          return;
        }
        if (!element2.settings || typeof element2.settings !== "object") {
          element2.settings = {};
        }
        const pattern = element2.settings.grid_pattern || "pattern-1";
        const baseTemplate = this.getGridTemplateData(pattern) || {};
        if (!element2.settings.custom_template || typeof element2.settings.custom_template !== "object") {
          element2.settings.custom_template = {};
        }
        const customTemplate = element2.settings.custom_template;
        if (!customTemplate.columns && baseTemplate.columns) {
          customTemplate.columns = baseTemplate.columns;
        }
        if (!customTemplate.rows && baseTemplate.rows) {
          customTemplate.rows = baseTemplate.rows;
        }
        if (!Array.isArray(customTemplate.cell_overrides)) {
          customTemplate.cell_overrides = [];
        }
        const baseAreas = Array.isArray(baseTemplate.areas) ? baseTemplate.areas.slice() : [];
        let templateAreas = Array.isArray(customTemplate.areas) && customTemplate.areas.length > 0 ? customTemplate.areas.filter((area) => area) : baseAreas;
        if (!Array.isArray(templateAreas)) {
          templateAreas = [];
        }
        customTemplate.areas = templateAreas.slice();
        const directChildren = this.normalizeStructureToArray(element2.children);
        const storedChildren = this.normalizeStructureToArray(element2.settings._children);
        const totalCells = templateAreas.length;
        const finalChildren = new Array(totalCells);
        for (let index = 0; index < totalCells; index++) {
          let child = directChildren[index];
          if (!child && storedChildren[index]) {
            try {
              child = JSON.parse(JSON.stringify(storedChildren[index]));
            } catch (error) {
              console.warn("\u26A0\uFE0F Failed to clone stored child structure", storedChildren[index], error);
              child = storedChildren[index];
            }
          }
          if (child && typeof child === "object") {
            if (!child.id) {
              child.id = "element-" + Date.now() + "-" + Math.random().toString(36).substr(2, 9);
            }
            if (!child.settings || typeof child.settings !== "object") {
              child.settings = {};
            }
          } else {
            child = null;
          }
          finalChildren[index] = child;
          if (typeof customTemplate.cell_overrides[index] === "undefined") {
            customTemplate.cell_overrides[index] = null;
          }
        }
        element2.children = finalChildren;
        try {
          element2.settings._children = JSON.parse(JSON.stringify(finalChildren));
        } catch (error) {
          console.warn("\u26A0\uFE0F Failed to serialize grid children for settings._children", error);
          element2.settings._children = finalChildren;
        }
      },
      /**
       * Prepare elements for saving (deep clone + normalize)
       */
      prepareElementsForSave: function(elements) {
        const normalize = (element2) => {
          if (!element2 || typeof element2 !== "object") {
            return null;
          }
          const cloned = JSON.parse(JSON.stringify(element2));
          if (!cloned.settings || typeof cloned.settings !== "object") {
            cloned.settings = {};
          }
          if (cloned.widgetType === "grid-layout") {
            const pattern = cloned.settings.grid_pattern || "pattern-1";
            const baseTemplate = this.getGridTemplateData(pattern) || {};
            if (!cloned.settings.custom_template || typeof cloned.settings.custom_template !== "object") {
              cloned.settings.custom_template = {};
            }
            if (!cloned.settings.custom_template.columns && baseTemplate.columns) {
              cloned.settings.custom_template.columns = baseTemplate.columns;
            }
            if (!cloned.settings.custom_template.rows && baseTemplate.rows) {
              cloned.settings.custom_template.rows = baseTemplate.rows;
            }
            if (!Array.isArray(cloned.settings.custom_template.areas) || cloned.settings.custom_template.areas.length === 0) {
              cloned.settings.custom_template.areas = Array.isArray(baseTemplate.areas) ? baseTemplate.areas.slice() : [];
            } else {
              cloned.settings.custom_template.areas = cloned.settings.custom_template.areas.filter((area) => area);
            }
            if (!cloned.settings.custom_template.columns && baseTemplate.columns) {
              cloned.settings.custom_template.columns = baseTemplate.columns;
            }
            if (!cloned.settings.custom_template.rows && baseTemplate.rows) {
              cloned.settings.custom_template.rows = baseTemplate.rows;
            }
            if (!Array.isArray(cloned.settings.custom_template.cell_overrides)) {
              cloned.settings.custom_template.cell_overrides = [];
            } else {
              cloned.settings.custom_template.cell_overrides = cloned.settings.custom_template.cell_overrides.map((override) => {
                if (!override) {
                  return null;
                }
                return Object.assign({}, override);
              });
            }
            if (!Array.isArray(cloned.children)) {
              cloned.children = [];
            }
            cloned.children = cloned.children.map((child) => normalize(child));
            try {
              cloned.settings._children = JSON.parse(JSON.stringify(cloned.children));
            } catch (error) {
              console.warn("\u26A0\uFE0F Failed to clone children for _children during save prep", error);
              cloned.settings._children = cloned.children;
            }
          } else if (Array.isArray(cloned.children) && cloned.children.length > 0) {
            cloned.children = cloned.children.map((child) => normalize(child));
          }
          return cloned;
        };
        return (elements || []).map((el) => normalize(el)).filter((el) => el);
      },
      /**
       * Load saved data
       */
      loadSavedData: function() {
        if (typeof ProBuilderEditor !== "undefined" && ProBuilderEditor.savedElements) {
          console.log("\u{1F4E5} Loading saved elements from PHP...", ProBuilderEditor.savedElements);
          if (Array.isArray(ProBuilderEditor.savedElements)) {
            this.elements = ProBuilderEditor.savedElements;
            console.log("\u2705 Loaded", this.elements.length, "elements from ProBuilderEditor.savedElements");
            this.renderElements();
            return;
          }
        }
        const data = $2("#probuilder-data").val();
        if (data && data !== "[]" && data !== "") {
          try {
            const parsed = JSON.parse(data);
            if (Array.isArray(parsed)) {
              this.elements = parsed;
            } else if (typeof parsed === "object" && parsed !== null) {
              this.elements = Object.values(parsed);
              console.warn("Converted object to array:", this.elements);
            } else {
              console.error("Invalid data format, expected array but got:", typeof parsed);
              this.elements = [];
            }
            console.log("\u2705 Loaded", this.elements.length, "elements from hidden input (fallback)");
            this.renderElements();
          } catch (e) {
            console.error("Failed to parse saved data:", e);
            this.elements = [];
          }
        } else {
          console.log("\u2139\uFE0F No saved data found, starting with empty canvas");
          this.elements = [];
        }
      },
      /**
       * Load Templates Library
       */
      loadTemplates: function() {
        const self2 = this;
        const $templatesContainer = $2('.probuilder-tab-content[data-tab="templates"]');
        console.log("Loading templates from library...");
        $2.ajax({
          url: ProBuilderEditor.ajaxurl || ajaxurl,
          type: "POST",
          data: {
            action: "probuilder_get_templates"
          },
          success: function(response) {
            console.log("Templates response:", response);
            if (response.success && response.data) {
              self2.renderTemplates(response.data.prebuilt || []);
            } else {
              $templatesContainer.html(`
                            <div style="text-align: center; padding: 60px 20px; color: #dc2626;">
                                <i class="dashicons dashicons-warning" style="font-size: 48px; margin-bottom: 20px;"></i>
                                <h3 style="font-size: 16px; margin: 0 0 10px 0;">Failed to Load Templates</h3>
                                <p style="font-size: 13px; margin: 0;">Please refresh the page and try again</p>
                            </div>
                        `);
            }
          },
          error: function(xhr, status, error) {
            console.error("Templates load error:", error);
            $templatesContainer.html(`
                        <div style="text-align: center; padding: 60px 20px; color: #dc2626;">
                            <i class="dashicons dashicons-warning" style="font-size: 48px; margin-bottom: 20px;"></i>
                            <h3 style="font-size: 16px; margin: 0 0 10px 0;">Error Loading Templates</h3>
                            <p style="font-size: 13px; margin: 0;">${error}</p>
                        </div>
                    `);
          }
        });
      },
      /**
       * Clear Canvas - Remove all elements
       */
      clearCanvas: function() {
        console.log("\u{1F5D1}\uFE0F Clearing canvas elements...");
        this.elements = [];
        this.selectedElement = null;
        this.renderElements();
        $2(".probuilder-properties-content").html(`
                <div style="text-align: center; padding: 60px 20px; color: #a1a1aa;">
                    <i class="dashicons dashicons-admin-page" style="font-size: 48px; margin-bottom: 20px; opacity: 0.3;"></i>
                    <h3 style="font-size: 16px; color: #71717a; margin: 0 0 10px 0;">No Element Selected</h3>
                    <p style="font-size: 13px; margin: 0;">Select an element to edit its properties</p>
                </div>
            `);
        this.saveHistory();
        console.log("\u2705 Canvas cleared, ready for template");
      },
      /**
       * Render Templates in UI
       */
      renderTemplates: function(templates) {
        const $templatesContainer = $2('.probuilder-tab-content[data-tab="templates"]');
        const self2 = this;
        console.log("=== TEMPLATES DEBUG ===");
        console.log("Total templates received:", templates ? templates.length : 0);
        console.log("Templates array:", templates);
        if (!templates || templates.length === 0) {
          $templatesContainer.html(`
                    <div style="text-align: center; padding: 60px 20px; color: #a1a1aa;">
                        <i class="dashicons dashicons-admin-page" style="font-size: 48px; margin-bottom: 20px; opacity: 0.3;"></i>
                        <h3 style="font-size: 16px; color: #71717a; margin: 0 0 10px 0;">No Templates Found</h3>
                        <p style="font-size: 13px; margin: 0;">Templates will appear here once created</p>
                    </div>
                `);
          return;
        }
        console.log("Rendering", templates.length, "templates");
        templates.forEach((template, index) => {
          console.log(`Template ${index + 1}:`, template.name, "(ID:", template.id + ")");
        });
        let templatesHTML = `
                <div style="padding: 15px 20px; background: #f0f9ff; border-left: 4px solid #0284c7; margin-bottom: 20px;">
                    <strong style="color: #0c4a6e;">\u{1F4CB} ${templates.length} Templates Available</strong>
                    <p style="margin: 5px 0 0; font-size: 12px; color: #475569;">Showing all full page and section templates</p>
                </div>
                <div class="probuilder-templates-grid" style="padding: 0 20px 20px;">
            `;
        templates.forEach(function(template) {
          const thumbnail = template.thumbnail || "data:image/svg+xml;base64," + btoa('<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="200" fill="#f3f4f6"/><text x="150" y="100" text-anchor="middle" fill="#9ca3af" font-size="16">Template</text></svg>');
          templatesHTML += `
                    <div class="probuilder-template-item" data-template-id="${template.id}" style="
                        background: #fff;
                        border: 1px solid #e5e7eb;
                        border-radius: 8px;
                        overflow: hidden;
                        cursor: pointer;
                        transition: all 0.2s;
                        margin-bottom: 15px;
                    " onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)';this.style.transform='translateY(-2px)'" onmouseout="this.style.boxShadow='none';this.style.transform='translateY(0)'">
                        <div class="probuilder-template-thumbnail" style="
                            width: 100%;
                            height: 150px;
                            background: url('${thumbnail}') center/cover;
                            border-bottom: 1px solid #e5e7eb;
                        "></div>
                        <div class="probuilder-template-info" style="padding: 12px 15px;">
                            <h4 style="margin: 0 0 5px 0; font-size: 14px; font-weight: 600; color: #1f2937;">${template.name}</h4>
                            <p style="margin: 0; font-size: 12px; color: #6b7280;">${template.category || "Full Page"}</p>
                        </div>
                        <div class="probuilder-template-actions" style="padding: 0 15px 12px; display: flex; gap: 8px;">
                            <button class="probuilder-template-insert" data-template-id="${template.id}" style="
                                flex: 1;
                                background: #0073aa;
                                color: #fff;
                                border: none;
                                padding: 8px 12px;
                                border-radius: 4px;
                                cursor: pointer;
                                font-size: 12px;
                                font-weight: 600;
                                transition: background 0.2s;
                            " onmouseover="this.style.background='#005a87'" onmouseout="this.style.background='#0073aa'">
                                <i class="dashicons dashicons-plus-alt2" style="font-size: 14px; vertical-align: middle;"></i>
                                Insert
                            </button>
                            <button class="probuilder-template-preview" data-template-id="${template.id}" style="
                                background: #f3f4f6;
                                color: #374151;
                                border: 1px solid #d1d5db;
                                padding: 8px 12px;
                                border-radius: 4px;
                                cursor: pointer;
                                font-size: 12px;
                                font-weight: 600;
                            ">
                                <i class="dashicons dashicons-visibility" style="font-size: 14px; vertical-align: middle;"></i>
                            </button>
                        </div>
                    </div>
                `;
        });
        templatesHTML += "</div></div>";
        console.log("Templates HTML generated, inserting into DOM...");
        $templatesContainer.html(templatesHTML);
        console.log("Templates inserted successfully!");
        $2(".probuilder-template-insert").on("click", function(e) {
          e.stopPropagation();
          const templateId = $2(this).data("template-id");
          const template = templates.find((t) => t.id === templateId);
          if (template && template.data) {
            console.log("Inserting template:", template.name);
            console.log("Template type:", template.type);
            console.log("Template data:", template.data);
            if (template.type === "page") {
              console.log("\u{1F5D1}\uFE0F Full page template - clearing canvas first");
              self2.clearCanvas();
            } else {
              console.log("\u2795 Section template - adding to existing content");
            }
            if (Array.isArray(template.data)) {
              template.data.forEach(function(elementData) {
                console.log("\u{1F4E6} Template element:", elementData.widgetType);
                console.log("   Children count:", elementData.children ? elementData.children.length : 0);
                if (elementData.children && elementData.children.length > 0) {
                  console.log("   First child:", elementData.children[0]);
                }
                if (elementData.widgetType) {
                  const newElement = self2.cloneElementData(elementData);
                  console.log("\u2705 Cloned element:", newElement.widgetType, "with", newElement.children.length, "children");
                  self2.elements.push(newElement);
                  self2.renderElement(newElement);
                }
              });
            } else {
              if (template.data.widgetType) {
                const newElement = self2.cloneElementData(template.data);
                self2.elements.push(newElement);
                self2.renderElement(newElement);
              }
            }
            self2.updateEmptyState();
            self2.saveHistory();
            const action = template.type === "page" ? "inserted" : "added";
            self2.showToast("\u2713 Template " + action + " successfully!");
            $2('.probuilder-tab-btn[data-tab="widgets"]').click();
          } else {
            console.error("Template not found or no data:", templateId);
            self2.showToast("\u274C Error: Template not found");
          }
        });
        $2(".probuilder-template-preview").on("click", function(e) {
          e.stopPropagation();
          const templateId = $2(this).data("template-id");
          const template = templates.find((t) => t.id === templateId);
          if (template) {
            console.log("Previewing template:", template.name);
            self2.showToast("\u{1F441} Preview: " + template.name);
            alert("Preview: " + template.name + "\n\nThis template contains " + (Array.isArray(template.data) ? template.data.length : 1) + " elements.");
          }
        });
        console.log("Templates rendered successfully");
      },
      /**
       * Render widgets in sidebar
       */
      renderWidgets: function() {
        console.log("Rendering widgets...");
        const categories = {
          "layout": [],
          "basic": [],
          "advanced": [],
          "content": []
        };
        this.widgets.forEach((widget2) => {
          console.log("Processing widget:", widget2.name, "Category:", widget2.category);
          if (categories[widget2.category]) {
            categories[widget2.category].push(widget2);
          } else {
            console.warn("Unknown category:", widget2.category, "for widget:", widget2.name);
          }
        });
        Object.keys(categories).forEach((category) => {
          const $container = $2(`.probuilder-widgets-grid[data-category="${category}"]`);
          console.log(`Rendering ${category} category:`, categories[category].length, "widgets");
          console.log("Container found:", $container.length > 0 ? "YES" : "NO");
          if ($container.length === 0) {
            console.error(`Container for category "${category}" not found!`);
            return;
          }
          $container.empty();
          if (categories[category].length === 0) {
            console.warn(`No widgets in category: ${category}`);
            return;
          }
          categories[category].forEach((widget2) => {
            const $widget = $2(`
                        <div class="probuilder-widget" data-widget="${widget2.name}">
                            <div class="probuilder-widget-icon">
                                <i class="${widget2.icon}"></i>
                            </div>
                            <div class="probuilder-widget-title">${widget2.title}</div>
                        </div>
                    `);
            $container.append($widget);
            console.log("Added widget:", widget2.name);
          });
        });
        console.log("Widgets rendering complete!");
      },
      /**
       * Initialize drag and drop
       */
      initDragDrop: function() {
        const self2 = this;
        if (typeof $2.fn.draggable === "undefined") {
          console.error("jQuery UI Draggable not loaded!");
          alert("ProBuilder Error: jQuery UI is not loaded. Drag and drop will not work.");
          return;
        }
        console.log("Initializing drag and drop...");
        console.log("Widgets found:", $2(".probuilder-widget").length);
        $2(".probuilder-widget").draggable({
          helper: "clone",
          appendTo: "body",
          zIndex: 1e4,
          cursor: "move",
          revert: "invalid",
          revertDuration: 200,
          start: function(event, ui) {
            const widgetName = $2(this).data("widget");
            console.log("Started dragging widget:", widgetName);
            $2(ui.helper).css("opacity", "0.8");
          },
          stop: function(event, ui) {
            console.log("Stopped dragging widget");
            $2(ui.helper).remove();
            $2("body").css("cursor", "");
          }
        });
        console.log("\u2705 Widgets are now draggable:", $2(".probuilder-widget").length);
        const $previewArea = $2("#probuilder-preview-area");
        console.log("Preview area found:", $previewArea.length > 0 ? "YES" : "NO");
        $previewArea.sortable({
          items: "> .probuilder-element",
          handle: ".probuilder-element-drag",
          placeholder: "probuilder-element-placeholder",
          tolerance: "pointer",
          cursor: "grabbing",
          opacity: 0.8,
          distance: 10,
          delay: 100,
          revert: 150,
          cancel: "input, textarea, select, .probuilder-element-actions button, .probuilder-add-below-btn",
          start: function(event, ui) {
            console.log("\u{1F3AF} Started dragging element via handle");
            ui.item.addClass("dragging-element");
            ui.placeholder.height(ui.item.outerHeight());
            $2("body").css("cursor", "grabbing");
          },
          stop: function(event, ui) {
            console.log("\u{1F3AF} Stopped dragging element");
            ui.item.removeClass("dragging-element");
            $2("body").css("cursor", "");
          },
          update: function() {
            console.log("\u{1F3AF} Elements reordered - updating data");
            self2.updateElementsOrder();
          }
        });
        $previewArea.droppable({
          accept: ".probuilder-widget",
          tolerance: "pointer",
          greedy: false,
          // Allow both preview area and columns to receive events
          drop: function(event, ui) {
            if (self2.isNestedDropInProgress) {
              console.log("\u{1F3AF} Nested drop in progress - skipping canvas drop handler");
              return;
            }
            const $target = $2(event.target);
            if ($target.hasClass("probuilder-column") || $target.closest(".probuilder-column").length > 0) {
              console.log("\u{1F3AF} Drop intercepted by column, skipping preview area handler");
              return;
            }
            const clientX = event.clientX || event.originalEvent && event.originalEvent.clientX;
            const clientY = event.clientY || event.originalEvent && event.originalEvent.clientY;
            if (typeof clientX === "number" && typeof clientY === "number") {
              const elementUnderPointer = document.elementFromPoint(clientX, clientY);
              if (elementUnderPointer) {
                const $realTarget = $2(elementUnderPointer);
                if ($realTarget.closest(".grid-cell").length > 0 || $realTarget.closest(".probuilder-grid-layout").length > 0 || $realTarget.closest(".probuilder-tab-drop-zone").length > 0 || $realTarget.closest(".probuilder-drop-zone").length > 0) {
                  console.log("\u{1F3AF} Drop handled by nested zone, skipping canvas handler");
                  return;
                }
              }
            }
            const widgetName = ui.draggable.data("widget");
            console.log("\u{1F3AF} Dropped NEW widget on canvas:", widgetName);
            if (widgetName) {
              const dropY = event.pageY;
              const $elements = $previewArea.children(".probuilder-element");
              let insertIndex = $elements.length;
              $elements.each(function(index) {
                const $el = $2(this);
                const elTop = $el.offset().top;
                const elMiddle = elTop + $el.outerHeight() / 2;
                if (dropY < elMiddle && insertIndex === $elements.length) {
                  insertIndex = index;
                  return false;
                }
              });
              console.log("Inserting at index:", insertIndex);
              self2.addElementAtPosition(widgetName, insertIndex);
            }
          },
          over: function(event, ui) {
            if (ui.draggable.hasClass("probuilder-widget")) {
              $2(this).addClass("probuilder-drop-active");
            }
          },
          out: function() {
            $2(this).removeClass("probuilder-drop-active");
          }
        });
        $2(".probuilder-widget").each(function() {
          const $widget = $2(this);
          const widgetName = $widget.data("widget");
          $widget.draggable({
            helper: function() {
              return $2(this).clone().css({
                "width": $2(this).width(),
                "opacity": 0.8,
                "z-index": 1e4
              });
            },
            appendTo: "body",
            zIndex: 1e4,
            cursor: "move",
            revert: "invalid",
            revertDuration: 200,
            start: function() {
              console.log("Started dragging widget:", widgetName);
              $2(".probuilder-element-placeholder").show();
              $2(".probuilder-column").css("outline", "2px dashed #92003b");
              $2("#probuilder-preview-area, .probuilder-column").addClass("drop-ready");
            },
            stop: function() {
              $2(".probuilder-column").css("outline", "");
              $2("#probuilder-preview-area, .probuilder-column").removeClass("drop-ready");
            }
          });
        });
        console.log("\u2705 Preview area is now sortable - drag from anywhere on element!");
        this.makeContainersDroppable();
        console.log("\u2705 Drag and drop fully initialized!");
      },
      /**
       * Make containers droppable
       */
      makeContainersDroppable: function() {
        const self2 = this;
        try {
          console.log("\u{1F527} Reinitializing container drop zones...");
          $2('.probuilder-element[data-widget="container"], .probuilder-element[data-widget="container-2"]').each(function() {
            const $container = $2(this);
            const containerId = $container.data("id");
            if (!containerId) return;
            if ($container.data("ui-droppable")) {
              try {
                $container.droppable("destroy");
              } catch (e) {
                console.warn("Error destroying container droppable:", e);
              }
            }
            $container.droppable({
              accept: ".probuilder-widget",
              tolerance: "pointer",
              greedy: true,
              // Capture all drops inside container bounds
              drop: function(event, ui) {
                self2.isNestedDropInProgress = true;
                const finishNestedDrop = () => {
                  setTimeout(() => {
                    self2.isNestedDropInProgress = false;
                  }, 0);
                };
                const widgetName = ui.draggable.data("widget");
                $2(this).find(".probuilder-drop-overlay").remove();
                console.log("\u2705 Dropped widget in container (will add to end):", widgetName);
                if (widgetName && containerId) {
                  self2.addElementToContainer(widgetName, containerId, null);
                }
                finishNestedDrop();
              },
              over: function(event, ui) {
                if (ui.draggable.hasClass("probuilder-widget")) {
                  const $containerElement = $2(this);
                  if ($containerElement.find(".probuilder-drop-overlay").length === 0) {
                    const overlay = `
                                        <div class="probuilder-drop-overlay" style="
                                            position: absolute;
                                            top: 0;
                                            left: 0;
                                            right: 0;
                                            bottom: 0;
                                            background: rgba(254, 241, 246, 0.9);
                                            border: 3px dashed #92003b;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            z-index: 5;
                                            pointer-events: none;
                                            animation: pulse 1.5s ease-in-out infinite;
                                        ">
                                            <div style="
                                                background: white;
                                                padding: 20px 30px;
                                                border-radius: 8px;
                                                box-shadow: 0 4px 12px rgba(146, 0, 59, 0.2);
                                                text-align: center;
                                            ">
                                                <i class="dashicons dashicons-plus-alt2" style="font-size: 48px; color: #92003b; margin-bottom: 10px; display: block;"></i>
                                                <span style="font-size: 16px; font-weight: 600; color: #92003b; display: block;">Drop inside container</span>
                                                <span style="font-size: 13px; color: #666; margin-top: 5px; display: block;">Element will be added to the grid</span>
                                            </div>
                                        </div>
                                    `;
                    $containerElement.append(overlay);
                    console.log("\u2728 Drop overlay created");
                  }
                }
              },
              out: function(event, ui) {
                $2(this).find(".probuilder-drop-overlay").remove();
                console.log("\u{1F9F9} Drop overlay removed");
              }
            });
          });
          const $columns = $2('.probuilder-element[data-widget="container"] .probuilder-column');
          console.log("Found", $columns.length, "container columns");
          $columns.each(function() {
            const $column = $2(this);
            const containerId = $column.data("container-id");
            const columnIndex = $column.data("column-index");
            try {
              if ($column.data("ui-droppable")) {
                $column.droppable("destroy");
              }
              if ($column.data("ui-sortable")) {
                $column.sortable("destroy");
              }
            } catch (e) {
              console.warn("Error destroying old handlers:", e);
            }
            $column.droppable({
              accept: ".probuilder-widget",
              tolerance: "pointer",
              greedy: true,
              // Prevent parent elements from also handling the drop
              drop: function(event, ui) {
                event.preventDefault();
                self2.isNestedDropInProgress = true;
                const finishNestedDrop = () => {
                  setTimeout(() => {
                    self2.isNestedDropInProgress = false;
                  }, 0);
                };
                if (!$column.hasClass("probuilder-drop-zone")) {
                  console.log("Column has content, letting container handle drop");
                  finishNestedDrop();
                  return;
                }
                const widgetName = ui.draggable.data("widget");
                console.log("\u{1F3AF} Dropped widget in empty column:", widgetName, "column:", columnIndex);
                if (widgetName && containerId) {
                  self2.addElementToContainer(widgetName, containerId, columnIndex);
                  event.stopPropagation();
                }
                $2(this).css("background", "");
                finishNestedDrop();
              },
              over: function(event, ui) {
                if (ui.draggable.hasClass("probuilder-widget")) {
                  $2(this).css("background", "#fef1f6");
                }
              },
              out: function() {
                $2(this).css("background", "");
              }
            });
            $column.sortable({
              items: "> .probuilder-nested-element",
              placeholder: "probuilder-element-placeholder",
              tolerance: "pointer",
              cursor: "move",
              connectWith: ".probuilder-column",
              opacity: 0.7,
              distance: 10,
              handle: ".probuilder-nested-drag"
            });
          });
          console.log("\u2705 Container drop zones initialized successfully");
        } catch (error) {
          console.error("\u274C Error in makeContainersDroppable:", error);
        }
        $2(".probuilder-drop-zone").each(function() {
          var _a;
          const $zone = $2(this);
          const containerId = $zone.data("container-id") || $zone.data("grid-id");
          const columnIndex = (_a = $zone.data("column-index")) != null ? _a : parseInt($zone.attr("data-cell-index"), 10);
          $zone.off("click").on("click", function(e) {
            e.stopPropagation();
            e.preventDefault();
            const $target = $2(e.target);
            const isToolbar = $target.closest(".grid-cell-toolbar").length > 0;
            const isDelete = $target.closest(".grid-cell-delete-btn").length > 0;
            const isSettings = $target.closest(".settings-btn").length > 0;
            const isAddContent = $target.closest(".add-content-btn").length > 0;
            const isAddButton = $target.closest(".grid-cell-add-btn").length > 0;
            if (isToolbar || isDelete || isSettings || isAddContent) {
              console.log("\u23ED\uFE0F Drop zone click ignored - toolbar interaction");
              return false;
            }
            if ($zone.hasClass("has-content")) {
              console.log("\u23ED\uFE0F Drop zone click ignored - cell already has content");
              return false;
            }
            if (!isAddButton) {
              console.log("\u23ED\uFE0F Drop zone click ignored - outside add button");
              return false;
            }
            if (self2.isGridCellDeleting) {
              console.log("\u23F8\uFE0F Drop zone click ignored - grid cell delete in progress");
              return false;
            }
            if (self2.isGridCellResizing) {
              console.log("\u23F8\uFE0F Drop zone click ignored - grid cell resizing");
              return false;
            }
            console.log("Drop zone clicked:", containerId, "column/cell:", columnIndex);
            self2.showWidgetTemplateSelector(containerId, columnIndex);
          });
        });
      },
      /**
       * Bind events
       */
      bindEvents: function() {
        const self2 = this;
        $2("#probuilder-preview-area").on("click", ".probuilder-element-delete", function(e) {
          e.stopPropagation();
          e.preventDefault();
          const elementId = $2(this).closest(".probuilder-element").data("id");
          const element2 = self2.elements.find((el) => el.id === elementId);
          if (element2) {
            console.log("\u{1F5D1}\uFE0F Delete button clicked for:", elementId);
            self2.deleteElement(element2);
          }
          return false;
        });
        $2("#probuilder-preview-area").on("click", ".probuilder-element-duplicate", function(e) {
          e.stopPropagation();
          e.preventDefault();
          const elementId = $2(this).closest(".probuilder-element").data("id");
          const element2 = self2.elements.find((el) => el.id === elementId);
          if (element2) {
            console.log("\u{1F4CB} Duplicate button clicked for:", elementId);
            self2.duplicateElement(element2);
          }
          return false;
        });
        $2("#probuilder-preview-area").on("click", ".probuilder-element-edit", function(e) {
          e.stopPropagation();
          e.preventDefault();
          const elementId = $2(this).closest(".probuilder-element").data("id");
          const element2 = self2.elements.find((el) => el.id === elementId);
          if (element2) {
            console.log("\u270F\uFE0F Edit button clicked for:", elementId);
            self2.openSettings(element2);
          }
          return false;
        });
        const resolveGridId = ($el) => {
          return $el.attr("data-grid-id") || $el.data("grid-id") || $el.closest(".probuilder-grid-layout").attr("data-element-id") || $el.closest(".probuilder-grid-layout").data("element-id") || $el.closest(".probuilder-element").attr("data-id") || $el.closest(".probuilder-element").data("id") || null;
        };
        $2("#probuilder-preview-area").on("click", ".grid-cell-add-btn", function(e) {
          e.preventDefault();
          e.stopPropagation();
          const $btn = $2(this);
          const gridId = resolveGridId($btn);
          const cellIndexAttr = $btn.attr("data-cell-index");
          const cellIndex = parseInt(cellIndexAttr, 10);
          if (!gridId || Number.isNaN(cellIndex)) {
            console.error("\u274C Add button: missing grid ID or invalid cell index", { gridId, cellIndexAttr });
            return false;
          }
          if (self2.isGridCellDeleting || self2.isGridCellResizing) {
            console.log("\u23F8\uFE0F Add button click ignored - grid busy", {
              deleting: self2.isGridCellDeleting,
              resizing: self2.isGridCellResizing
            });
            return false;
          }
          console.log("\u2795 Add button clicked for grid cell", { gridId, cellIndex });
          self2.showWidgetTemplateSelector(gridId, cellIndex, true);
          return false;
        });
        $2("#probuilder-preview-area").on("click", ".grid-cell-delete-btn", function(e) {
          e.preventDefault();
          e.stopPropagation();
          e.stopImmediatePropagation();
          const $button = $2(this);
          const cellIndexAttr = $button.attr("data-cell-index");
          const cellIndex = parseInt(cellIndexAttr, 10);
          const gridIdAttr = resolveGridId($button);
          if (Number.isNaN(cellIndex)) {
            console.error("\u274C Global handler: invalid cell index on delete button:", cellIndexAttr);
            return false;
          }
          if (!gridIdAttr) {
            console.error("\u274C Global handler: grid ID not found for delete button", { cellIndexAttr });
            return false;
          }
          const gridElement = self2.elements.find((el) => el && el.id === gridIdAttr);
          if (!gridElement) {
            console.error("\u274C Global handler: grid element not found", { gridIdAttr, cellIndex });
            return false;
          }
          console.log("\u{1F5D1}\uFE0F Global grid cell delete handler triggered", { gridIdAttr, cellIndex });
          const $gridLayout = $button.closest(".probuilder-grid-layout");
          const domPattern = $gridLayout.attr("data-grid-pattern") || null;
          const domAreas = $gridLayout.find(".grid-cell").map(function() {
            return $2(this).attr("data-original-area") || null;
          }).get();
          const deleted = self2.handleGridCellDelete(gridElement, cellIndex, {
            triggerSource: "global-handler",
            skipConfirm: false,
            domPattern,
            domAreas
          });
          if (!deleted) {
            console.warn("\u26A0\uFE0F Global handler: grid cell delete helper returned false", { gridIdAttr, cellIndex });
          }
          return false;
        });
        $2(document).off("click.probuilderGridDeleteFallback", ".grid-cell-delete-btn").on("click.probuilderGridDeleteFallback", ".grid-cell-delete-btn", function(e) {
          console.log("\u{1F6D1} Document-level delete handler invoked");
          if ($2(this).closest("#probuilder-preview-area").length > 0) {
            return;
          }
          e.preventDefault();
          e.stopPropagation();
          const $button = $2(this);
          const cellIndexAttr = $button.attr("data-cell-index");
          const cellIndex = parseInt(cellIndexAttr, 10);
          const gridIdAttr = resolveGridId($button);
          console.log("\u{1F50D} Document fallback handler details", { cellIndexAttr, gridIdAttr });
          if (Number.isNaN(cellIndex) || !gridIdAttr) {
            console.error("\u274C Document-level handler: missing cell index or grid id", { cellIndexAttr, gridIdAttr });
            return false;
          }
          const gridElement = self2.elements.find((el) => el && el.id === gridIdAttr);
          if (!gridElement) {
            console.error("\u274C Document-level handler: grid element not found", { gridIdAttr, cellIndex });
            return false;
          }
          const $gridLayout = $button.closest(".probuilder-grid-layout");
          const domPattern = $gridLayout.attr("data-grid-pattern") || null;
          const domAreas = $gridLayout.find(".grid-cell").map(function() {
            return $2(this).attr("data-original-area") || null;
          }).get();
          const deleted = self2.handleGridCellDelete(gridElement, cellIndex, {
            triggerSource: "document-fallback",
            skipConfirm: false,
            domPattern,
            domAreas
          });
          if (!deleted) {
            console.warn("\u26A0\uFE0F Document-level handler: delete helper returned false", { gridIdAttr, cellIndex });
          }
          return false;
        });
        $2("#probuilder-preview-area").on("click", ".probuilder-add-below-btn", function(e) {
          e.stopPropagation();
          e.preventDefault();
          const elementId = $2(this).closest(".probuilder-element").data("id");
          const element2 = self2.elements.find((el) => el.id === elementId);
          if (element2) {
            console.log("\u2795 Add below button clicked for:", elementId);
            self2.showWidgetPicker(element2);
          }
          return false;
        });
        $2("#probuilder-new-page").on("click", function() {
          self2.createNewPage();
        });
        $2("#probuilder-clear-page").on("click", function() {
          self2.clearPage();
        });
        $2("#probuilder-save").on("click", function() {
          self2.savePage();
        });
        $2("#probuilder-page-settings").on("click", function() {
          self2.showPageSettings();
        });
        $2("#probuilder-undo").on("click", function() {
          self2.undo();
        });
        $2("#probuilder-redo").on("click", function() {
          self2.redo();
        });
        $2("#probuilder-preview").on("click", function() {
          const url = ProBuilderEditor.home_url + "/?p=" + ProBuilderEditor.post_id + "&preview=true";
          window.open(url, "_blank");
        });
        $2(".probuilder-device-btn").on("click", function() {
          const device = $2(this).data("device");
          console.log("Switching to device:", device);
          $2(".probuilder-device-btn").removeClass("active");
          $2(this).addClass("active");
          $2(".probuilder-canvas").attr("data-device", device);
          console.log("Canvas device set to:", device);
        });
        $2("#probuilder-add-first-element").on("click", function() {
          self2.showWidgetPicker(null);
        });
        $2(document).on("click", ".probuilder-add-element-btn", function() {
          self2.showWidgetPicker(null);
        });
        $2(document).on("mousedown", ".probuilder-resize-handle", function(e) {
          e.preventDefault();
          self2.startColumnResize($2(this), e);
        });
        let respTimer = null;
        $2(window).on("resize", function() {
          clearTimeout(respTimer);
          respTimer = setTimeout(function() {
            self2.applyResponsiveVisibilityToAll();
          }, 100);
        });
        $2(document).on("mousedown", ".column-resize-handle", function(e) {
          e.stopPropagation();
          e.preventDefault();
          e.stopImmediatePropagation();
          const $handle = $2(this);
          const elementId = $handle.data("element-id");
          const columnIndex = parseInt($handle.data("column-index"));
          const direction = $handle.data("direction");
          console.log("\u{1F3AF}\u{1F3AF}\u{1F3AF} COLUMN RESIZE HANDLE CLICKED! \u{1F3AF}\u{1F3AF}\u{1F3AF}", {
            elementId,
            columnIndex,
            direction,
            handleClass: $handle.attr("class"),
            handleElement: $handle[0]
          });
          const containerElement = self2.elements.find((el) => el.id === elementId);
          if (!containerElement) {
            console.error("\u274C Container element not found:", elementId);
            return;
          }
          if (isNaN(columnIndex)) {
            console.error("\u274C Invalid column index:", columnIndex);
            return;
          }
          $2(document).on("click.columnResizePrevent", function(clickEvent) {
            clickEvent.preventDefault();
            clickEvent.stopPropagation();
            $2(document).off("click.columnResizePrevent");
          });
          self2.startColumnDimensionResize(containerElement, columnIndex, direction, e);
          return false;
        });
        $2(document).on("mousedown", ".grid-resize-handle", function(e) {
          e.stopPropagation();
          e.preventDefault();
          const $handle = $2(this);
          const cellIndex = parseInt($handle.attr("data-cell-index"), 10);
          const direction = $handle.data("direction");
          const $gridContainer = $handle.closest(".probuilder-grid-layout");
          let gridId = null;
          if ($gridContainer.length) {
            gridId = $gridContainer.data("element-id") || $gridContainer.data("grid-element-id");
          }
          if (!gridId) {
            const $gridElement = $handle.closest(".probuilder-element");
            gridId = $gridElement.data("id");
          }
          console.log("\u{1F3AF} Global grid resize handler:", { gridId, cellIndex, direction, found: !!gridId });
          if (!gridId) {
            console.error("\u274C Could not find grid element ID");
            return;
          }
          const gridElement = self2.elements.find((e2) => e2.id === gridId);
          if (!gridElement) {
            console.error("\u274C Grid element not found in elements array:", gridId);
            console.log("Available elements:", self2.elements.map((e2) => ({ id: e2.id, type: e2.widgetType })));
            return;
          }
          $2(document).on("click.gridResizePrevent", function(clickEvent) {
            clickEvent.preventDefault();
            clickEvent.stopPropagation();
            $2(document).off("click.gridResizePrevent");
          });
          self2.startGridCellResize(gridElement, cellIndex, direction, e);
        });
        $2(document).on("mousedown", ".column-resize-handle", function(e) {
          e.stopPropagation();
          e.preventDefault();
          const $handle = $2(this);
          const columnIndex = $handle.data("column-index");
          const direction = $handle.data("direction");
          const $containerElement = $handle.closest(".probuilder-element");
          const containerId = $containerElement.data("id");
          console.log("\u{1F3AF} Global container column resize handler:", { containerId, columnIndex, direction });
          const containerElement = self2.elements.find((e2) => e2.id === containerId);
          if (!containerElement) {
            console.error("\u274C Container element not found:", containerId);
            return;
          }
          $2(document).on("click.columnResizePrevent", function(clickEvent) {
            clickEvent.preventDefault();
            clickEvent.stopPropagation();
            $2(document).off("click.columnResizePrevent");
          });
          self2.startContainerColumnResize(containerElement, columnIndex, direction, e);
        });
        $2(document).on("click", ".probuilder-nested-delete", function(e) {
          e.stopPropagation();
          e.preventDefault();
          const $button = $2(this);
          const $nestedEl = $button.closest(".probuilder-nested-element");
          const childId = $nestedEl.data("id");
          const $gridCell = $nestedEl.closest(".grid-cell");
          const cellIndex = parseInt($gridCell.attr("data-cell-index"), 10);
          const $gridElement = $nestedEl.closest(".probuilder-element");
          const gridId = $gridElement.data("id");
          console.log("\u{1F5D1}\uFE0F Global delete handler triggered:", { childId, cellIndex, gridId });
          const gridElement = self2.elements.find((e2) => e2.id === gridId);
          if (!gridElement) {
            console.error("\u274C Grid element not found:", gridId);
            return;
          }
          if (!gridElement.children) {
            gridElement.children = [];
          }
          if (gridElement.children[cellIndex]) {
            console.log("\u{1F5D1}\uFE0F Removing widget from cell:", cellIndex);
            gridElement.children[cellIndex] = null;
            const $oldElement = $2(`.probuilder-element[data-id="${gridId}"]`);
            const $parent = $oldElement.parent();
            const insertBefore = $oldElement.next()[0];
            $oldElement.remove();
            self2.renderElement(gridElement, insertBefore);
            self2.saveHistory();
            console.log("\u2705 Widget deleted from grid cell", cellIndex);
          } else {
            console.warn("\u26A0\uFE0F No widget found in cell:", cellIndex);
          }
        });
        $2(document).on("click", ".probuilder-grid-preset-item", function() {
          const $item = $2(this);
          const setting = $item.data("setting");
          const pattern = $item.data("pattern");
          $item.siblings(".probuilder-grid-preset-item").removeClass("selected");
          $item.siblings(".probuilder-grid-preset-item").css({
            "border-color": "#ddd",
            "background": "#fff"
          }).find("div:last-child").css({
            "color": "#666",
            "font-weight": "400"
          });
          $item.addClass("selected");
          $item.css({
            "border-color": "#007cba",
            "background": "rgba(0,124,186,0.05)"
          }).find("div:last-child").css({
            "color": "#007cba",
            "font-weight": "600"
          });
          if (self2.selectedElement) {
            const elementToUpdate = self2.selectedElement;
            elementToUpdate.settings[setting] = pattern;
            if (elementToUpdate.widgetType === "grid-layout") {
              const $oldElement = $2(`.probuilder-element[data-id="${elementToUpdate.id}"]`);
              const insertBefore = $oldElement.next()[0];
              $oldElement.remove();
              self2.renderElement(elementToUpdate, insertBefore);
              setTimeout(function() {
                self2.selectElement(elementToUpdate);
              }, 100);
              console.log("Grid pattern applied and element re-rendered:", pattern);
            } else {
              self2.updateContainerWithChildren(elementToUpdate);
            }
            self2.saveHistory();
          }
        });
        $2(document).on("click", ".probuilder-add-section-between button", function() {
          const index = $2(this).closest(".probuilder-add-section-between").data("index");
          self2.showWidgetPicker(index);
        });
        $2(".probuilder-tab-btn").on("click", function() {
          const tab = $2(this).data("tab");
          if (tab === "templates") {
            self2.showTemplatesModal();
            return;
          }
          $2(".probuilder-tab-btn").removeClass("active");
          $2(this).addClass("active");
          $2(".probuilder-tab-content").removeClass("active");
          $2(`.probuilder-tab-content[data-tab="${tab}"]`).addClass("active");
        });
        $2(document).on("click", ".probuilder-settings-tab", function() {
          const tab = $2(this).data("tab");
          console.log("Settings tab clicked:", tab);
          $2(".probuilder-settings-tab").removeClass("active");
          $2(this).addClass("active");
          if (self2.selectedElement) {
            const widget2 = self2.widgets.find((w) => w.name === self2.selectedElement.widgetType);
            if (widget2) {
              self2.renderSettings(self2.selectedElement, widget2, tab);
            }
          }
        });
        $2(".probuilder-close-settings").on("click", function() {
          self2.closeSettings();
        });
        $2("#probuilder-widget-search").on("keyup", function() {
          const search = $2(this).val().toLowerCase();
          $2(".probuilder-widget").each(function() {
            const title = $2(this).find(".probuilder-widget-title").text().toLowerCase();
            if (title.indexOf(search) > -1) {
              $2(this).show();
            } else {
              $2(this).hide();
            }
          });
        });
        $2(document).on("keydown", function(e) {
          if ((e.ctrlKey || e.metaKey) && e.key === "s") {
            e.preventDefault();
            self2.savePage();
          }
          if (e.key === "Delete" && self2.selectedElement) {
            self2.deleteElement(self2.selectedElement);
          }
        });
        $2(".probuilder-device-btn").on("click", function() {
          const device = $2(this).data("device");
          $2(".probuilder-device-btn").removeClass("active");
          $2(this).addClass("active");
          $2(".probuilder-canvas-inner").removeClass("device-desktop device-tablet device-mobile");
          $2(".probuilder-canvas-inner").addClass("device-" + device);
        });
      },
      /**
       * Add element
       */
      addElement: function(widgetName, settings2 = {}) {
        try {
          console.log("Adding element:", widgetName);
          if (!Array.isArray(this.elements)) {
            console.warn("\u26A0\uFE0F this.elements was not an array! Initializing as empty array.");
            this.elements = [];
          }
          const widget2 = this.widgets.find((w) => w.name === widgetName);
          if (!widget2) {
            console.error("Widget not found:", widgetName);
            alert('Error: Widget "' + widgetName + '" not found!');
            return;
          }
          const element2 = {
            id: "element-" + Date.now() + "-" + Math.random().toString(36).substr(2, 9),
            widgetType: widgetName,
            settings: Object.assign({}, this.getDefaultSettings(widget2), settings2),
            children: []
          };
          console.log("Element created:", element2);
          this.elements.push(element2);
          console.log("Element pushed to array");
          this.renderElement(element2);
          console.log("Element rendered");
          this.updateEmptyState();
          console.log("Empty state updated");
          this.saveHistory();
          setTimeout(() => {
            this.selectElement(element2);
            console.log("Element auto-selected:", element2.id);
          }, 100);
          setTimeout(() => {
            this.makeContainersDroppable();
          }, 50);
          console.log("\u2705 Element added successfully:", widgetName, element2.id);
          return element2;
        } catch (error) {
          console.error("\u274C Error adding element:", error);
          alert("Error adding element: " + error.message);
          return null;
        }
      },
      /**
       * Add element at specific position
       */
      addElementAtPosition: function(widgetName, insertIndex, settings2 = {}) {
        try {
          console.log("Adding element at position:", widgetName, "at index:", insertIndex);
          if (!Array.isArray(this.elements)) {
            console.warn("\u26A0\uFE0F this.elements was not an array! Initializing as empty array.");
            this.elements = [];
          }
          const widget2 = this.widgets.find((w) => w.name === widgetName);
          if (!widget2) {
            console.error("Widget not found:", widgetName);
            alert('Error: Widget "' + widgetName + '" not found!');
            return;
          }
          const element2 = {
            id: "element-" + Date.now() + "-" + Math.random().toString(36).substr(2, 9),
            widgetType: widgetName,
            settings: Object.assign({}, this.getDefaultSettings(widget2), settings2),
            children: []
          };
          console.log("Element created:", element2);
          this.elements.splice(insertIndex, 0, element2);
          console.log("Element inserted at index:", insertIndex);
          this.renderElements();
          console.log("All elements re-rendered");
          this.updateEmptyState();
          setTimeout(() => {
            this.selectElement(element2);
            console.log("Element auto-selected:", element2.id);
          }, 100);
          setTimeout(() => {
            this.makeContainersDroppable();
          }, 50);
          console.log("\u2705 Element added at position successfully:", widgetName, element2.id);
          return element2;
        } catch (error) {
          console.error("\u274C Error adding element at position:", error);
          alert("Error adding element: " + error.message);
          return null;
        }
      },
      /**
       * Add element to container
       */
      addElementToContainer: function(widgetName, containerId, columnIndex = null) {
        try {
          console.log("Adding element to container:", widgetName, "into", containerId, "at column:", columnIndex);
          const widget2 = this.widgets.find((w) => w.name === widgetName);
          if (!widget2) {
            console.error("Widget not found:", widgetName);
            alert("Error: Widget not found");
            return;
          }
          const containerElement = this.elements.find((e) => e.id === containerId);
          if (!containerElement) {
            console.error("Container not found:", containerId);
            alert("Error: Container not found");
            return;
          }
          const newElement = {
            id: "element-" + Date.now() + "-" + Math.random().toString(36).substr(2, 9),
            widgetType: widgetName,
            settings: Object.assign({}, this.getDefaultSettings(widget2)),
            children: []
          };
          if (!containerElement.children) {
            containerElement.children = [];
          }
          const columns = parseInt(containerElement.settings.columns || 1);
          const filledColumns = containerElement.children.length;
          if (columnIndex !== null && columnIndex >= 0) {
            if (columnIndex < filledColumns) {
              containerElement.children.push(newElement);
              console.log("Column", columnIndex, "already filled. Element appended as position:", containerElement.children.length - 1);
            } else {
              const insertIndex = Math.min(columnIndex, containerElement.children.length);
              containerElement.children.splice(insertIndex, 0, newElement);
              console.log("Element inserted at empty column:", insertIndex);
            }
          } else {
            containerElement.children.push(newElement);
            console.log("Element appended to end");
          }
          console.log(`Container layout: ${containerElement.children.length} elements in ${columns}-column grid`);
          const rows = Math.ceil(containerElement.children.length / columns);
          console.log(`This creates ${rows} row(s)`);
          if (containerElement.settings && containerElement.settings.direction === "vertical") {
            console.log("\u{1F535} Forcing canvas refresh for VERTICAL container with", containerElement.children.length, "children");
            this.renderCanvas();
          }
          this.updateContainerWithChildren(containerElement);
          setTimeout(() => {
            console.log("\u{1F504} Full drag & drop system reinitialization...");
            this.reinitializeSidebarWidgets();
            this.reinitializePreviewArea();
            setTimeout(() => {
              this.makeContainersDroppable();
            }, 150);
            console.log("\u2705 Drag & drop system fully reinitialized");
          }, 100);
          setTimeout(() => {
            this.selectElement(newElement);
            console.log("Element auto-selected:", newElement.id);
          }, 100);
          console.log("\u2705 Element successfully added to container");
          return newElement;
        } catch (error) {
          console.error("\u274C Error adding element to container:", error);
          alert("Error: " + error.message);
          return null;
        }
      },
      /**
       * Get grid patterns for visual selection
       */
      /**
       * Get grid template data (columns, rows, areas)
       */
      getGridTemplateData: function(pattern) {
        const templates = {
          "pattern-1": {
            columns: "repeat(4, 1fr)",
            rows: "repeat(4, 150px)",
            areas: [
              "1 / 1 / 3 / 3",
              // Large left
              "1 / 3 / 2 / 5",
              // Top right
              "2 / 3 / 3 / 4",
              // Mid right 1
              "2 / 4 / 3 / 5",
              // Mid right 2
              "3 / 1 / 5 / 2",
              // Bottom left 1
              "3 / 2 / 5 / 3",
              // Bottom left 2
              "3 / 3 / 5 / 5"
              // Bottom right
            ]
          },
          "pattern-2": {
            columns: "repeat(6, 1fr)",
            rows: "repeat(3, 200px)",
            areas: [
              "1 / 1 / 3 / 4",
              // Large featured
              "1 / 4 / 2 / 7",
              // Top right
              "2 / 4 / 3 / 5",
              // Bottom right 1
              "2 / 5 / 3 / 6",
              // Bottom right 2
              "2 / 6 / 3 / 7",
              // Bottom right 3
              "3 / 1 / 4 / 3",
              // Bottom left
              "3 / 3 / 4 / 5",
              // Bottom center
              "3 / 5 / 4 / 7"
              // Bottom right
            ]
          },
          "pattern-3": {
            columns: "repeat(4, 1fr)",
            rows: "repeat(5, 120px)",
            areas: [
              "1 / 1 / 3 / 2",
              "1 / 2 / 2 / 3",
              "1 / 3 / 3 / 4",
              "1 / 4 / 2 / 5",
              "2 / 2 / 4 / 3",
              "2 / 4 / 3 / 5",
              "3 / 1 / 4 / 2",
              "3 / 3 / 5 / 4",
              "3 / 4 / 5 / 5",
              "4 / 1 / 6 / 2",
              "4 / 2 / 5 / 3",
              "5 / 3 / 6 / 5"
            ]
          },
          "pattern-4": {
            columns: "repeat(12, 1fr)",
            rows: "repeat(4, 150px)",
            areas: [
              "1 / 1 / 2 / 4",
              "1 / 4 / 2 / 7",
              "1 / 7 / 2 / 10",
              "1 / 10 / 2 / 13",
              "2 / 1 / 4 / 9",
              "2 / 9 / 4 / 13",
              "4 / 1 / 5 / 7",
              "4 / 7 / 5 / 13"
            ]
          },
          "pattern-5": {
            columns: "repeat(5, 1fr)",
            rows: "repeat(3, 180px)",
            areas: [
              "1 / 1 / 3 / 3",
              "1 / 3 / 2 / 4",
              "1 / 4 / 2 / 5",
              "1 / 5 / 2 / 6",
              "2 / 3 / 3 / 6",
              "3 / 1 / 4 / 2",
              "3 / 2 / 4 / 3",
              "3 / 3 / 4 / 4",
              "3 / 4 / 4 / 5",
              "3 / 5 / 4 / 6"
            ]
          },
          "pattern-6": {
            columns: "repeat(4, 1fr)",
            rows: "repeat(4, 180px)",
            areas: [
              "1 / 1 / 3 / 3",
              "1 / 3 / 2 / 4",
              "1 / 4 / 2 / 5",
              "2 / 3 / 3 / 4",
              "2 / 4 / 3 / 5",
              "3 / 1 / 5 / 2",
              "3 / 2 / 4 / 3",
              "3 / 3 / 4 / 4",
              "3 / 4 / 4 / 5",
              "4 / 2 / 5 / 5"
            ]
          },
          "pattern-7": {
            columns: "repeat(6, 1fr)",
            rows: "repeat(4, 150px)",
            areas: [
              "1 / 1 / 2 / 3",
              "1 / 3 / 3 / 5",
              "1 / 5 / 2 / 7",
              "2 / 1 / 3 / 2",
              "2 / 2 / 3 / 3",
              "2 / 5 / 4 / 7",
              "3 / 1 / 5 / 3",
              "3 / 3 / 4 / 5",
              "4 / 3 / 5 / 7"
            ]
          },
          "pattern-8": {
            columns: "repeat(2, 1fr)",
            rows: "repeat(6, 120px)",
            areas: [
              "1 / 1 / 4 / 2",
              "1 / 2 / 2 / 3",
              "2 / 2 / 3 / 3",
              "3 / 2 / 4 / 3",
              "4 / 1 / 5 / 2",
              "4 / 2 / 5 / 3",
              "5 / 1 / 7 / 2",
              "5 / 2 / 7 / 3"
            ]
          },
          "pattern-9": {
            columns: "repeat(8, 1fr)",
            rows: "repeat(5, 140px)",
            areas: [
              "1 / 1 / 3 / 5",
              "1 / 5 / 2 / 9",
              "2 / 5 / 3 / 7",
              "2 / 7 / 3 / 9",
              "3 / 1 / 4 / 3",
              "3 / 3 / 4 / 5",
              "3 / 5 / 4 / 7",
              "3 / 7 / 4 / 9",
              "4 / 1 / 6 / 4",
              "4 / 4 / 5 / 6",
              "4 / 6 / 5 / 8",
              "4 / 8 / 5 / 9",
              "5 / 4 / 6 / 9"
            ]
          },
          "pattern-10": {
            columns: "repeat(10, 1fr)",
            rows: "repeat(6, 120px)",
            areas: [
              "1 / 1 / 3 / 4",
              "1 / 4 / 2 / 6",
              "1 / 6 / 3 / 8",
              "1 / 8 / 2 / 11",
              "2 / 4 / 3 / 6",
              "2 / 8 / 4 / 11",
              "3 / 1 / 5 / 3",
              "3 / 3 / 4 / 5",
              "3 / 5 / 5 / 8",
              "4 / 3 / 5 / 5",
              "4 / 8 / 6 / 10",
              "5 / 1 / 7 / 3",
              "5 / 3 / 6 / 6",
              "5 / 10 / 7 / 11",
              "6 / 3 / 7 / 5",
              "6 / 5 / 7 / 8",
              "6 / 8 / 7 / 10"
            ]
          }
        };
        return templates[pattern] || templates["pattern-1"];
      },
      getGridPatterns: function() {
        return [
          {
            id: "pattern-1",
            name: "Magazine Hero",
            svg: `<svg viewBox="0 0 100 100" style="width:100%;height:100%"><rect x="2" y="2" width="46" height="46" fill="#007cba" opacity="0.7"/><rect x="52" y="2" width="46" height="21" fill="#007cba" opacity="0.5"/><rect x="52" y="27" width="21" height="21" fill="#007cba" opacity="0.5"/><rect x="77" y="27" width="21" height="21" fill="#007cba" opacity="0.5"/><rect x="2" y="52" width="21" height="46" fill="#007cba" opacity="0.5"/><rect x="27" y="52" width="21" height="46" fill="#007cba" opacity="0.5"/><rect x="52" y="52" width="46" height="46" fill="#007cba" opacity="0.6"/></svg>`
          },
          {
            id: "pattern-2",
            name: "Featured Post",
            svg: `<svg viewBox="0 0 100 100" style="width:100%;height:100%"><rect x="2" y="2" width="44" height="63" fill="#007cba" opacity="0.7"/><rect x="50" y="2" width="48" height="28" fill="#007cba" opacity="0.5"/><rect x="50" y="34" width="14" height="31" fill="#007cba" opacity="0.5"/><rect x="68" y="34" width="14" height="31" fill="#007cba" opacity="0.5"/><rect x="86" y="34" width="12" height="31" fill="#007cba" opacity="0.5"/><rect x="2" y="69" width="30" height="29" fill="#007cba" opacity="0.5"/><rect x="36" y="69" width="30" height="29" fill="#007cba" opacity="0.6"/><rect x="70" y="69" width="28" height="29" fill="#007cba" opacity="0.5"/></svg>`
          },
          {
            id: "pattern-3",
            name: "Pinterest Masonry",
            svg: `<svg viewBox="0 0 100 100" style="width:100%;height:100%"><rect x="2" y="2" width="21" height="40" fill="#007cba" opacity="0.6"/><rect x="27" y="2" width="21" height="20" fill="#007cba" opacity="0.5"/><rect x="52" y="2" width="21" height="40" fill="#007cba" opacity="0.6"/><rect x="77" y="2" width="21" height="20" fill="#007cba" opacity="0.5"/><rect x="27" y="26" width="21" height="40" fill="#007cba" opacity="0.7"/><rect x="77" y="26" width="21" height="20" fill="#007cba" opacity="0.5"/><rect x="2" y="46" width="21" height="20" fill="#007cba" opacity="0.5"/><rect x="52" y="46" width="21" height="52" fill="#007cba" opacity="0.6"/><rect x="77" y="50" width="21" height="48" fill="#007cba" opacity="0.6"/><rect x="2" y="70" width="21" height="28" fill="#007cba" opacity="0.6"/><rect x="27" y="70" width="21" height="28" fill="#007cba" opacity="0.5"/></svg>`
          },
          {
            id: "pattern-4",
            name: "Dashboard",
            svg: `<svg viewBox="0 0 100 100" style="width:100%;height:100%"><rect x="2" y="2" width="21" height="14" fill="#007cba" opacity="0.5"/><rect x="27" y="2" width="21" height="14" fill="#007cba" opacity="0.5"/><rect x="52" y="2" width="21" height="14" fill="#007cba" opacity="0.5"/><rect x="77" y="2" width="21" height="14" fill="#007cba" opacity="0.5"/><rect x="2" y="20" width="60" height="44" fill="#007cba" opacity="0.7"/><rect x="66" y="20" width="32" height="44" fill="#007cba" opacity="0.6"/><rect x="2" y="68" width="46" height="30" fill="#007cba" opacity="0.5"/><rect x="52" y="68" width="46" height="30" fill="#007cba" opacity="0.5"/></svg>`
          },
          {
            id: "pattern-5",
            name: "Portfolio Showcase",
            svg: `<svg viewBox="0 0 100 100" style="width:100%;height:100%"><rect x="2" y="2" width="36" height="60" fill="#007cba" opacity="0.7"/><rect x="42" y="2" width="16" height="28" fill="#007cba" opacity="0.5"/><rect x="62" y="2" width="16" height="28" fill="#007cba" opacity="0.5"/><rect x="82" y="2" width="16" height="28" fill="#007cba" opacity="0.5"/><rect x="42" y="34" width="56" height="28" fill="#007cba" opacity="0.6"/><rect x="2" y="66" width="16" height="32" fill="#007cba" opacity="0.5"/><rect x="22" y="66" width="16" height="32" fill="#007cba" opacity="0.5"/><rect x="42" y="66" width="16" height="32" fill="#007cba" opacity="0.5"/><rect x="62" y="66" width="16" height="32" fill="#007cba" opacity="0.5"/><rect x="82" y="66" width="16" height="32" fill="#007cba" opacity="0.5"/></svg>`
          },
          {
            id: "pattern-6",
            name: "Product Grid",
            svg: `<svg viewBox="0 0 100 100" style="width:100%;height:100%"><rect x="2" y="2" width="44" height="44" fill="#007cba" opacity="0.7"/><rect x="50" y="2" width="21" height="20" fill="#007cba" opacity="0.5"/><rect x="75" y="2" width="23" height="20" fill="#007cba" opacity="0.5"/><rect x="50" y="26" width="21" height="20" fill="#007cba" opacity="0.5"/><rect x="75" y="26" width="23" height="20" fill="#007cba" opacity="0.5"/><rect x="2" y="50" width="21" height="48" fill="#007cba" opacity="0.6"/><rect x="27" y="50" width="21" height="20" fill="#007cba" opacity="0.5"/><rect x="52" y="50" width="21" height="20" fill="#007cba" opacity="0.5"/><rect x="77" y="50" width="21" height="20" fill="#007cba" opacity="0.5"/><rect x="27" y="74" width="71" height="24" fill="#007cba" opacity="0.6"/></svg>`
          },
          {
            id: "pattern-7",
            name: "Asymmetric Modern",
            svg: `<svg viewBox="0 0 100 100" style="width:100%;height:100%"><rect x="2" y="2" width="28" height="20" fill="#007cba" opacity="0.5"/><rect x="34" y="2" width="28" height="46" fill="#007cba" opacity="0.7"/><rect x="66" y="2" width="32" height="20" fill="#007cba" opacity="0.5"/><rect x="2" y="26" width="13" height="22" fill="#007cba" opacity="0.5"/><rect x="19" y="26" width="13" height="22" fill="#007cba" opacity="0.5"/><rect x="66" y="26" width="32" height="44" fill="#007cba" opacity="0.6"/><rect x="2" y="52" width="30" height="46" fill="#007cba" opacity="0.6"/><rect x="36" y="52" width="26" height="20" fill="#007cba" opacity="0.5"/><rect x="36" y="76" width="60" height="22" fill="#007cba" opacity="0.6"/></svg>`
          },
          {
            id: "pattern-8",
            name: "Split Screen",
            svg: `<svg viewBox="0 0 100 100" style="width:100%;height:100%"><rect x="2" y="2" width="46" height="56" fill="#007cba" opacity="0.7"/><rect x="52" y="2" width="46" height="16" fill="#007cba" opacity="0.5"/><rect x="52" y="22" width="46" height="16" fill="#007cba" opacity="0.5"/><rect x="52" y="42" width="46" height="16" fill="#007cba" opacity="0.5"/><rect x="2" y="62" width="46" height="16" fill="#007cba" opacity="0.6"/><rect x="52" y="62" width="46" height="16" fill="#007cba" opacity="0.5"/><rect x="2" y="82" width="46" height="16" fill="#007cba" opacity="0.6"/><rect x="52" y="82" width="46" height="16" fill="#007cba" opacity="0.6"/></svg>`
          },
          {
            id: "pattern-9",
            name: "Blog Magazine",
            svg: `<svg viewBox="0 0 100 100" style="width:100%;height:100%"><rect x="2" y="2" width="42" height="36" fill="#007cba" opacity="0.7"/><rect x="48" y="2" width="50" height="16" fill="#007cba" opacity="0.6"/><rect x="48" y="22" width="23" height="16" fill="#007cba" opacity="0.5"/><rect x="75" y="22" width="23" height="16" fill="#007cba" opacity="0.5"/><rect x="2" y="42" width="21" height="16" fill="#007cba" opacity="0.5"/><rect x="27" y="42" width="21" height="16" fill="#007cba" opacity="0.5"/><rect x="52" y="42" width="21" height="16" fill="#007cba" opacity="0.5"/><rect x="77" y="42" width="21" height="16" fill="#007cba" opacity="0.5"/><rect x="2" y="62" width="35" height="36" fill="#007cba" opacity="0.6"/><rect x="41" y="62" width="25" height="16" fill="#007cba" opacity="0.5"/><rect x="70" y="62" width="28" height="16" fill="#007cba" opacity="0.5"/><rect x="41" y="82" width="57" height="16" fill="#007cba" opacity="0.6"/></svg>`
          },
          {
            id: "pattern-10",
            name: "Creative Complex",
            svg: `<svg viewBox="0 0 100 100" style="width:100%;height:100%"><rect x="2" y="2" width="26" height="34" fill="#007cba" opacity="0.6"/><rect x="32" y="2" width="18" height="16" fill="#007cba" opacity="0.5"/><rect x="54" y="2" width="18" height="34" fill="#007cba" opacity="0.6"/><rect x="76" y="2" width="22" height="16" fill="#007cba" opacity="0.5"/><rect x="32" y="22" width="18" height="16" fill="#007cba" opacity="0.5"/><rect x="76" y="22" width="22" height="34" fill="#007cba" opacity="0.6"/><rect x="2" y="40" width="18" height="36" fill="#007cba" opacity="0.6"/><rect x="24" y="40" width="18" height="16" fill="#007cba" opacity="0.5"/><rect x="46" y="40" width="26" height="36" fill="#007cba" opacity="0.7"/><rect x="24" y="60" width="18" height="16" fill="#007cba" opacity="0.5"/><rect x="2" y="80" width="18" height="18" fill="#007cba" opacity="0.5"/><rect x="24" y="80" width="24" height="18" fill="#007cba" opacity="0.5"/><rect x="76" y="60" width="22" height="18" fill="#007cba" opacity="0.5"/><rect x="52" y="80" width="46" height="18" fill="#007cba" opacity="0.6"/></svg>`
          }
        ];
      },
      /**
       * Start column resize
       */
      startColumnResize: function(handle, e) {
        const self2 = this;
        const elementId = handle.data("element-id");
        const columnIndex = parseInt(handle.data("column"));
        console.log("\u{1F3AF} Starting column resize for element:", elementId, "column:", columnIndex);
        const containerElement = this.elements.find((el) => el.id === elementId);
        if (!containerElement) {
          console.error("Container element not found:", elementId);
          return;
        }
        const currentWidths = (containerElement.settings.column_widths || "50,50").split(",").map((w) => parseFloat(w.trim()));
        const startX = e.clientX;
        const startWidths = [...currentWidths];
        console.log("Start widths:", startWidths);
        const $containerColumns = handle.closest(".probuilder-container-columns");
        const containerWidth = $containerColumns.width();
        handle.css("opacity", "1");
        $2("body").css("cursor", "ew-resize");
        $2(document).on("mousemove.columnResize", function(moveEvent) {
          moveEvent.preventDefault();
          const deltaX = moveEvent.clientX - startX;
          const deltaPercent = deltaX / containerWidth * 100;
          console.log("Delta:", deltaPercent.toFixed(2) + "%");
          const newWidths = [...startWidths];
          newWidths[columnIndex] = Math.max(5, Math.min(95, startWidths[columnIndex] + deltaPercent));
          newWidths[columnIndex + 1] = Math.max(5, Math.min(95, startWidths[columnIndex + 1] - deltaPercent));
          containerElement.settings.column_widths = newWidths.map((w) => w.toFixed(2)).join(",");
          console.log("New widths:", containerElement.settings.column_widths);
          self2.updateContainerWithChildren(containerElement);
        });
        $2(document).on("mouseup.columnResize", function() {
          $2(document).off(".columnResize");
          $2("body").css("cursor", "");
          self2.saveHistory();
          console.log("\u2705 Column resize completed. Final widths:", containerElement.settings.column_widths);
        });
      },
      /**
       * Start column dimension resize - ABSOLUTE POSITIONING for anchored edges
       * Resizes individual column's height and width with opposite edge fixed
       */
      startColumnDimensionResize: function(containerElement, columnIndex, direction, e) {
        const self2 = this;
        console.log("\u{1F3AF} Starting column resize (ANCHORED):", containerElement.id, "column:", columnIndex, "direction:", direction);
        const $containerElement = $2(`.probuilder-element[data-id="${containerElement.id}"]`);
        const $column = $containerElement.find(`.probuilder-column[data-column-index="${columnIndex}"]`);
        const $containerColumns = $column.closest(".probuilder-container-columns");
        if (!$column.length) {
          console.error("Column not found:", columnIndex);
          return;
        }
        const columnRect = $column[0].getBoundingClientRect();
        const containerRect = $containerColumns[0].getBoundingClientRect();
        const startWidth = columnRect.width;
        const startHeight = columnRect.height;
        const startTop = columnRect.top - containerRect.top;
        const startLeft = columnRect.left - containerRect.left;
        const startX = e.clientX;
        const startY = e.clientY;
        const containerWidth = $containerColumns.width();
        if (!containerElement.settings.column_heights) {
          containerElement.settings.column_heights = [];
        }
        if (!containerElement.settings.column_paddings) {
          containerElement.settings.column_paddings = [];
        }
        const startColumnHeight = containerElement.settings.column_heights[columnIndex] || "auto";
        const startHeightValue = startColumnHeight === "auto" ? startHeight : parseInt(startColumnHeight);
        const columnPadding = containerElement.settings.column_paddings[columnIndex] || {};
        const startPaddingTop = columnPadding.top || 0;
        const startPaddingLeft = columnPadding.left || 0;
        const startPaddingRight = columnPadding.right || 0;
        const currentWidths = (containerElement.settings.column_widths || "50,50").split(",").map((w) => parseFloat(w.trim()));
        const startWidths = [...currentWidths];
        const columnsCount = parseInt(containerElement.settings.columns_count || "2");
        let finalLeft = startLeft;
        let finalTop = startTop;
        let finalWidth = startWidth;
        let finalHeight = startHeight;
        console.log("Start:", { columnIndex, width: startWidth, height: startHeight, top: startTop, left: startLeft });
        $column.css({
          "position": "absolute",
          "top": startTop + "px",
          "left": startLeft + "px",
          "width": startWidth + "px",
          "height": startHeight + "px",
          "z-index": "1000",
          "box-shadow": "0 0 20px rgba(0,124,186,0.4)",
          "border-color": "#007cba",
          "transition": "none"
        });
        const cursorMap = {
          "top": "row-resize",
          "left": "col-resize",
          "right": "col-resize",
          "bottom": "row-resize",
          "both": "nwse-resize"
        };
        $2("body").css("cursor", cursorMap[direction] || "default");
        const $indicator = $2('<div class="column-resize-indicator" style="position: fixed; top: 10px; right: 10px; background: rgba(0,124,186,0.9); color: white; padding: 10px 15px; border-radius: 4px; font-size: 12px; z-index: 99999; font-family: monospace; box-shadow: 0 4px 12px rgba(0,0,0,0.3);"></div>');
        $2("body").append($indicator);
        $2(document).on("mousemove.columnDimensionResize", function(moveEvent) {
          moveEvent.preventDefault();
          moveEvent.stopPropagation();
          const deltaX = moveEvent.clientX - startX;
          const deltaY = moveEvent.clientY - startY;
          let newWidth = startWidth;
          let newHeight = startHeight;
          let newLeft = startLeft;
          let newTop = startTop;
          let newWidths = [...startWidths];
          if (direction === "top") {
            newHeight = Math.max(50, startHeight - deltaY);
            newTop = startTop + (startHeight - newHeight);
            $column.css({
              "height": newHeight + "px",
              "top": newTop + "px"
            });
            finalHeight = newHeight;
            finalTop = newTop;
            $indicator.html(`
                        <div style="font-weight: bold; margin-bottom: 5px;">Resizing Column ${columnIndex + 1}</div>
                        <div>Height: ${Math.round(newHeight)}px</div>
                        <div style="margin-top: 5px; font-size: 10px; opacity: 0.8;">Bottom edge FIXED</div>
                    `);
          } else if (direction === "bottom") {
            newHeight = Math.max(50, startHeight + deltaY);
            $column.css({
              "height": newHeight + "px"
            });
            finalHeight = newHeight;
            $indicator.html(`
                        <div style="font-weight: bold; margin-bottom: 5px;">Resizing Column ${columnIndex + 1}</div>
                        <div>Height: ${Math.round(newHeight)}px</div>
                        <div style="margin-top: 5px; font-size: 10px; opacity: 0.8;">Top edge FIXED</div>
                    `);
          }
          if (direction === "left") {
            newWidth = Math.max(50, startWidth - deltaX);
            newLeft = startLeft + (startWidth - newWidth);
            $column.css({
              "width": newWidth + "px",
              "left": newLeft + "px"
            });
            finalWidth = newWidth;
            finalLeft = newLeft;
            $indicator.html(`
                        <div style="font-weight: bold; margin-bottom: 5px;">Resizing Column ${columnIndex + 1}</div>
                        <div>Width: ${Math.round(newWidth)}px</div>
                        <div style="margin-top: 5px; font-size: 10px; opacity: 0.8;">Right edge FIXED</div>
                    `);
          } else if (direction === "right") {
            newWidth = Math.max(50, startWidth + deltaX);
            $column.css({
              "width": newWidth + "px"
            });
            finalWidth = newWidth;
            $indicator.html(`
                        <div style="font-weight: bold; margin-bottom: 5px;">Resizing Column ${columnIndex + 1}</div>
                        <div>Width: ${Math.round(newWidth)}px</div>
                        <div style="margin-top: 5px; font-size: 10px; opacity: 0.8;">Left edge FIXED</div>
                    `);
          } else if (direction === "both") {
            newHeight = Math.max(50, startHeight + deltaY);
            newWidth = Math.max(50, startWidth + deltaX);
            $column.css({
              "height": newHeight + "px",
              "width": newWidth + "px"
            });
            finalHeight = newHeight;
            finalWidth = newWidth;
            $indicator.html(`
                        <div style="font-weight: bold; margin-bottom: 5px;">Resizing Column ${columnIndex + 1}</div>
                        <div>Width: ${Math.round(newWidth)}px</div>
                        <div>Height: ${Math.round(newHeight)}px</div>
                        <div style="margin-top: 5px; font-size: 10px; opacity: 0.8;">Top-left FIXED</div>
                    `);
          }
        });
        $2(document).on("mouseup.columnDimensionResize", function(upEvent) {
          upEvent.preventDefault();
          upEvent.stopPropagation();
          $2(document).off(".columnDimensionResize");
          $indicator.remove();
          $2("body").css("cursor", "");
          const exactWidth = Math.round(finalWidth);
          const exactHeight = Math.round(finalHeight);
          const widthPercent = exactWidth / containerRect.width * 100;
          console.log("Final dimensions:", {
            width: exactWidth,
            height: exactHeight,
            widthPercent: widthPercent.toFixed(2)
          });
          if (direction === "top" || direction === "bottom" || direction === "both") {
            containerElement.settings.column_heights[columnIndex] = exactHeight;
            console.log("\u2705 Saved column height:", exactHeight);
          }
          if (direction === "left" || direction === "right" || direction === "both") {
            const newWidthPercent = exactWidth / containerWidth * 100;
            const newWidths = [...startWidths];
            console.log("\u{1F4BE} Calculating new widths:", {
              exactWidth,
              containerWidth,
              newWidthPercent: newWidthPercent.toFixed(2),
              startWidths,
              columnIndex,
              direction
            });
            if (columnIndex > 0 && direction === "left") {
              const startWidthPx = containerWidth * (startWidths[columnIndex] / 100);
              const prevWidthPx = containerWidth * (startWidths[columnIndex - 1] / 100);
              const widthChange = exactWidth - startWidthPx;
              newWidths[columnIndex] = newWidthPercent;
              newWidths[columnIndex - 1] = (prevWidthPx - widthChange) / containerWidth * 100;
              console.log("Left edge resize:", {
                thisColumn: newWidths[columnIndex].toFixed(2),
                prevColumn: newWidths[columnIndex - 1].toFixed(2)
              });
            } else if (columnIndex < columnsCount - 1 && direction === "right") {
              const startWidthPx = containerWidth * (startWidths[columnIndex] / 100);
              const nextWidthPx = containerWidth * (startWidths[columnIndex + 1] / 100);
              const widthChange = exactWidth - startWidthPx;
              newWidths[columnIndex] = newWidthPercent;
              newWidths[columnIndex + 1] = (nextWidthPx - widthChange) / containerWidth * 100;
              console.log("Right edge resize:", {
                thisColumn: newWidths[columnIndex].toFixed(2),
                nextColumn: newWidths[columnIndex + 1].toFixed(2)
              });
            } else {
              const startWidthPx = containerWidth * (startWidths[columnIndex] / 100);
              const widthChange = exactWidth - startWidthPx;
              newWidths[columnIndex] = newWidthPercent;
              const otherIndices = [];
              for (let j = 0; j < columnsCount; j++) {
                if (j !== columnIndex) otherIndices.push(j);
              }
              if (otherIndices.length > 0) {
                const totalOthers = otherIndices.reduce((sum, j) => sum + startWidths[j], 0);
                const totalOthersPx = containerWidth * (totalOthers / 100);
                const newTotalOthersPx = totalOthersPx - widthChange;
                otherIndices.forEach((j) => {
                  const proportion = startWidths[j] / totalOthers;
                  newWidths[j] = newTotalOthersPx / containerWidth * 100 * proportion;
                });
              }
              console.log("Edge column resize:", {
                thisColumn: newWidths[columnIndex].toFixed(2),
                allWidths: newWidths.map((w) => w.toFixed(2))
              });
            }
            containerElement.settings.column_widths = newWidths.map((w) => w.toFixed(2)).join(",");
            console.log("\u2705\u2705\u2705 SAVED column widths:", containerElement.settings.column_widths);
          }
          console.log("\u{1F504} Final saved widths:", containerElement.settings.column_widths);
          if (direction === "left" || direction === "right" || direction === "both") {
            const savedWidths = containerElement.settings.column_widths.split(",").map((w) => parseFloat(w));
            const total = savedWidths.reduce((sum, w) => sum + w, 0);
            const gridTemplate = savedWidths.map((w) => w / total + "fr").join(" ");
            console.log("\u{1F3AF} Applying grid template directly:", {
              gridTemplate,
              savedWidths: containerElement.settings.column_widths
            });
            $column.css({
              "position": "",
              "top": "",
              "left": "",
              "width": "",
              "height": "",
              "z-index": "",
              "box-shadow": "",
              "border-color": "",
              "transition": ""
            });
            $containerColumns.css("grid-template-columns", gridTemplate);
            console.log("\u2705 Grid template applied, widths saved. NOT calling updateElementPreview.");
            self2.saveHistory();
            return;
          }
          $column.css({
            "position": "",
            "top": "",
            "left": "",
            "width": "",
            "height": "",
            "z-index": "",
            "box-shadow": "",
            "border-color": "",
            "transition": ""
          });
          self2.updateElementPreview(containerElement);
          self2.saveHistory();
        });
      },
      /**
       * Show alignment guides (Illustrator-style)
       */
      showAlignmentGuides: function($currentCell, $gridContainer, currentIndex, bounds, $guides, direction) {
        const tolerance = 3;
        Object.values($guides).forEach(($guide) => $guide.hide());
        const $allCells = $gridContainer.find(".grid-cell").not(`[data-cell-index="${currentIndex}"]`);
        if ($allCells.length === 0) return;
        const showVerticalGuides = direction === "left" || direction === "right" || direction === "both";
        const showHorizontalGuides = direction === "top" || direction === "bottom" || direction === "both";
        console.log("\u{1F3AF} Alignment guides for direction:", direction, {
          showVerticalGuides,
          showHorizontalGuides
        });
        $allCells.each(function() {
          const otherRect = this.getBoundingClientRect();
          if (showVerticalGuides) {
            if (Math.abs(bounds.left - otherRect.left) < tolerance) {
              $guides.left.css("left", otherRect.left + "px").show();
              console.log("  \u2503 Showing LEFT guide (vertical line)");
            }
            if (Math.abs(bounds.right - otherRect.right) < tolerance) {
              $guides.right.css("left", otherRect.right + "px").show();
              console.log("  \u2503 Showing RIGHT guide (vertical line)");
            }
            if (Math.abs(bounds.centerX - (otherRect.left + otherRect.width / 2)) < tolerance) {
              $guides.centerV.css("left", otherRect.left + otherRect.width / 2 + "px").show();
              console.log("  \u2503 Showing CENTER-V guide (vertical line)");
            }
          }
          if (showHorizontalGuides) {
            if (Math.abs(bounds.top - otherRect.top) < tolerance) {
              $guides.top.css("top", otherRect.top + "px").show();
              console.log("  \u2501 Showing TOP guide (horizontal line)");
            }
            if (Math.abs(bounds.bottom - otherRect.bottom) < tolerance) {
              $guides.bottom.css("top", otherRect.bottom + "px").show();
              console.log("  \u2501 Showing BOTTOM guide (horizontal line)");
            }
            if (Math.abs(bounds.centerY - (otherRect.top + otherRect.height / 2)) < tolerance) {
              $guides.centerH.css("top", otherRect.top + otherRect.height / 2 + "px").show();
              console.log("  \u2501 Showing CENTER-H guide (horizontal line)");
            }
          }
        });
      },
      /**
       * Start grid cell resize - ABSOLUTE POSITIONING METHOD
       * VERSION: 3.0.0-responsive-2024-10-26
       */
      startGridCellResize: function(gridElement, cellIndex, direction, e) {
        const self2 = this;
        console.log("\u{1F3AF} Starting absolute resize VERSION 3.0.0:", gridElement.id, "cell:", cellIndex, "direction:", direction);
        console.log("\u{1F4CC} CODE VERSION: 3.0.0-responsive-2024-10-26 - WITH RESPONSIVE NEIGHBORS");
        this.isGridCellResizing = true;
        const $gridContainer = $2(`.probuilder-element[data-id="${gridElement.id}"] .probuilder-grid-layout`);
        const $gridCell = $gridContainer.find(`.grid-cell[data-cell-index="${cellIndex}"]`);
        const cellRect = $gridCell[0].getBoundingClientRect();
        const containerRect = $gridContainer[0].getBoundingClientRect();
        const startWidth = cellRect.width;
        const startHeight = cellRect.height;
        const startTop = cellRect.top - containerRect.top;
        const startLeft = cellRect.left - containerRect.left;
        const startX = e.clientX;
        const startY = e.clientY;
        const originalArea = $gridCell.data("original-area");
        const originalPosition = $gridCell.css("position");
        const originalZIndex = $gridCell.css("z-index");
        let finalLeft = startLeft;
        let finalTop = startTop;
        let finalWidth = startWidth;
        let finalHeight = startHeight;
        let isResizing = false;
        console.log("Start:", { width: startWidth, height: startHeight, area: originalArea });
        $gridCell.css({
          "position": "absolute",
          "top": startTop + "px",
          "left": startLeft + "px",
          "width": startWidth + "px",
          "height": startHeight + "px",
          "grid-area": "unset",
          "z-index": "1000",
          "box-shadow": "0 0 20px rgba(0,124,186,0.4)",
          "border-color": "#007cba",
          "transition": "none"
        });
        const cursorMap = {
          "top": "row-resize",
          "left": "col-resize",
          "right": "col-resize",
          "bottom": "row-resize",
          "both": "nwse-resize"
        };
        $2("body").css("cursor", cursorMap[direction] || "default");
        const $indicator = $2('<div class="grid-resize-indicator" style="position: fixed; top: 10px; right: 10px; background: rgba(0,124,186,0.9); color: white; padding: 10px 15px; border-radius: 4px; font-size: 12px; z-index: 99999; font-family: monospace; box-shadow: 0 4px 12px rgba(0,0,0,0.3);"></div>');
        $2("body").append($indicator);
        const $guides = {
          left: $2('<div class="probuilder-alignment-guide vertical"></div>').appendTo("body"),
          right: $2('<div class="probuilder-alignment-guide vertical"></div>').appendTo("body"),
          top: $2('<div class="probuilder-alignment-guide horizontal"></div>').appendTo("body"),
          bottom: $2('<div class="probuilder-alignment-guide horizontal"></div>').appendTo("body"),
          centerV: $2('<div class="probuilder-alignment-guide vertical"></div>').appendTo("body"),
          centerH: $2('<div class="probuilder-alignment-guide horizontal"></div>').appendTo("body")
        };
        Object.values($guides).forEach(($guide) => $guide.hide());
        $2(document).on("mousemove.gridResize", function(moveEvent) {
          moveEvent.preventDefault();
          moveEvent.stopPropagation();
          const deltaX = moveEvent.clientX - startX;
          const deltaY = moveEvent.clientY - startY;
          if (Math.abs(deltaX) > 2 || Math.abs(deltaY) > 2) {
            isResizing = true;
          }
          let newWidth = startWidth;
          let newHeight = startHeight;
          let newLeft = startLeft;
          let newTop = startTop;
          if (direction === "top") {
            newHeight = Math.max(20, startHeight - deltaY);
            newTop = startTop + (startHeight - newHeight);
            $gridCell.css({
              "height": newHeight + "px",
              "top": newTop + "px"
            });
            finalHeight = newHeight;
            finalTop = newTop;
          } else if (direction === "bottom" || direction === "both") {
            newHeight = Math.max(20, startHeight + deltaY);
            $gridCell.css("height", newHeight + "px");
            finalHeight = newHeight;
            finalTop = startTop;
          }
          if (direction === "left") {
            newWidth = Math.max(20, startWidth - deltaX);
            newLeft = startLeft + (startWidth - newWidth);
            $gridCell.css({
              "width": newWidth + "px",
              "left": newLeft + "px"
            });
            finalWidth = newWidth;
            finalLeft = newLeft;
          } else if (direction === "right" || direction === "both") {
            newWidth = Math.max(20, startWidth + deltaX);
            $gridCell.css("width", newWidth + "px");
            finalWidth = newWidth;
            finalLeft = startLeft;
          }
          const widthPercent = Math.round(newWidth / containerRect.width * 100);
          const heightPercent = Math.round(newHeight / containerRect.height * 100);
          $indicator.html(`
                    <div style="font-weight: bold; margin-bottom: 5px;">Resizing Cell ${cellIndex + 1}</div>
                    <div>Width: ${Math.round(newWidth)}px (${widthPercent}%)</div>
                    <div>Height: ${Math.round(newHeight)}px (${heightPercent}%)</div>
                    <div style="margin-top: 5px; font-size: 10px; opacity: 0.8;">Release to apply</div>
                `);
          self2.showAlignmentGuides($gridCell, $gridContainer, cellIndex, {
            left: newLeft + containerRect.left,
            top: newTop + containerRect.top,
            right: newLeft + newWidth + containerRect.left,
            bottom: newTop + newHeight + containerRect.top,
            centerX: newLeft + newWidth / 2 + containerRect.left,
            centerY: newTop + newHeight / 2 + containerRect.top,
            width: newWidth,
            height: newHeight
          }, $guides, direction);
        });
        $2(document).on("mouseup.gridResize", function(upEvent) {
          upEvent.preventDefault();
          upEvent.stopPropagation();
          $2(document).off(".gridResize");
          $indicator.remove();
          Object.values($guides).forEach(($guide) => $guide.remove());
          const scaleX = finalWidth / startWidth;
          const scaleY = finalHeight / startHeight;
          const exactWidth = Math.round(finalWidth);
          const exactHeight = Math.round(finalHeight);
          console.log("Using tracked dimensions from drag:", {
            finalLeft,
            finalTop,
            exactWidth,
            exactHeight,
            direction
          });
          const parts = originalArea.split("/").map((p) => p.trim());
          let [rowStart, colStart, rowEnd, colEnd] = parts.map((p) => parseInt(p));
          const originalColSpan = colEnd - colStart;
          const originalRowSpan = rowEnd - rowStart;
          if (direction === "right" || direction === "both") {
            const newColSpan = Math.max(1, originalColSpan * scaleX);
            colEnd = colStart + newColSpan;
          } else if (direction === "left") {
            const newColSpan = Math.max(1, originalColSpan * scaleX);
            colStart = colEnd - newColSpan;
          }
          if (direction === "bottom" || direction === "both") {
            const newRowSpan = Math.max(1, originalRowSpan * scaleY);
            rowEnd = rowStart + newRowSpan;
          } else if (direction === "top") {
            const newRowSpan = Math.max(1, originalRowSpan * scaleY);
            rowStart = rowEnd - newRowSpan;
          }
          const finalArea = originalArea;
          console.log("Finalizing resize with tracked values:", {
            direction,
            finalLeft,
            finalTop,
            exactWidth,
            exactHeight,
            startLeft,
            startTop
          });
          if (!gridElement.settings) {
            gridElement.settings = {};
          }
          if (!gridElement.settings.custom_template || typeof gridElement.settings.custom_template !== "object") {
            gridElement.settings.custom_template = {};
          }
          gridElement.settings.custom_template.areas = gridElement.settings.custom_template.areas || [];
          gridElement.settings.custom_template.areas[cellIndex] = finalArea;
          if ($gridContainer.length) {
            const computedStyles = window.getComputedStyle($gridContainer[0]);
            gridElement.settings.custom_template.columns = (computedStyles.getPropertyValue("grid-template-columns") || "").trim();
            gridElement.settings.custom_template.rows = (computedStyles.getPropertyValue("grid-template-rows") || "").trim();
          }
          if (!Array.isArray(gridElement.settings.custom_template.cell_overrides)) {
            gridElement.settings.custom_template.cell_overrides = [];
          }
          const containerWidth = containerRect.width || 1;
          const containerHeight = containerRect.height || 1;
          const overrides = gridElement.settings.custom_template.cell_overrides;
          const $allCells = $gridContainer.find(".grid-cell");
          let maxRight = 0;
          let maxBottom = 0;
          $gridContainer.css({
            "position": "relative"
          });
          $allCells.each(function(idx) {
            const $cell = $2(this);
            const cellEl = $cell[0];
            const cellRect2 = idx === cellIndex ? {
              left: containerRect.left + finalLeft,
              top: containerRect.top + finalTop,
              width: finalWidth,
              height: finalHeight
            } : cellEl.getBoundingClientRect();
            const relativeLeft = Math.round(cellRect2.left - containerRect.left);
            const relativeTop = Math.round(cellRect2.top - containerRect.top);
            const exactCellWidth = Math.round(cellRect2.width);
            const exactCellHeight = Math.round(cellRect2.height);
            const leftPercent = parseFloat((relativeLeft / containerWidth * 100).toFixed(4));
            const topPercent = parseFloat((relativeTop / containerHeight * 100).toFixed(4));
            const widthPercent = parseFloat((exactCellWidth / containerWidth * 100).toFixed(4));
            const heightPercent = parseFloat((exactCellHeight / containerHeight * 100).toFixed(4));
            const existingOverride = overrides[idx] || {};
            const computedStyle = window.getComputedStyle(cellEl);
            let zIndex = existingOverride.zIndex;
            if (typeof zIndex === "undefined" || zIndex === null) {
              const computedZ = parseInt(computedStyle.zIndex, 10);
              zIndex = Number.isFinite(computedZ) ? computedZ : idx === cellIndex ? 2 : 1;
            }
            overrides[idx] = {
              left: relativeLeft,
              top: relativeTop,
              width: exactCellWidth,
              height: exactCellHeight,
              leftPercent,
              topPercent,
              widthPercent,
              heightPercent,
              position: "absolute",
              zIndex
            };
            $cell.css({
              "position": "absolute",
              "left": `${relativeLeft}px`,
              "top": `${relativeTop}px`,
              "width": `${exactCellWidth}px`,
              "height": `${exactCellHeight}px`,
              "grid-area": "unset",
              "z-index": zIndex || "",
              "box-shadow": "",
              "border-color": "",
              "transition": ""
            });
            const cellBottom = relativeTop + exactCellHeight;
            const cellRight = relativeLeft + exactCellWidth;
            if (cellBottom > maxBottom) {
              maxBottom = cellBottom;
            }
            if (cellRight > maxRight) {
              maxRight = cellRight;
            }
          });
          const adjustedHeight = Math.max(containerRect.height, maxBottom);
          gridElement.settings.custom_template.container_height = Math.round(adjustedHeight);
          gridElement.settings.custom_template.container_width = Math.round(Math.max(containerRect.width, maxRight));
          gridElement.settings.custom_template.layout_mode = "absolute";
          $gridContainer.css({
            "height": `${Math.round(adjustedHeight)}px`,
            "min-height": `${Math.round(adjustedHeight)}px`
          });
          $2("body").css("cursor", "");
          $gridCell.data("original-area", finalArea);
          $gridCell.attr("data-original-area", finalArea);
          if (isResizing) {
            setTimeout(function() {
              $2(document).one("click.preventAfterResize", function(e2) {
                e2.preventDefault();
                e2.stopPropagation();
                e2.stopImmediatePropagation();
                return false;
              });
            }, 10);
          }
          self2.saveHistory();
          console.log("\u2705 Resize complete:", {
            original: originalArea,
            final: finalArea,
            scaleX: scaleX.toFixed(2),
            scaleY: scaleY.toFixed(2),
            finalWidth: Math.round(finalWidth),
            finalHeight: Math.round(finalHeight),
            isResizing
          });
          setTimeout(function() {
            self2.isGridCellResizing = false;
            console.log("\u{1F7E2} Grid cell resize completed - clicks enabled again");
          }, 150);
        });
      },
      handleGridCellDelete: function(gridElement, cellIndex, options = {}) {
        const self2 = this;
        const settings2 = Object.assign({
          skipConfirm: false,
          triggerSource: "unknown",
          removeCell: true,
          confirmMessage: null,
          confirmCallback: null
        }, options || {});
        const normalizeToArray = (value) => self2.normalizeStructureToArray(value);
        if (!gridElement || !gridElement.id) {
          console.error("\u274C Cannot modify grid cell - grid element missing", { gridElement, cellIndex, settings: settings2 });
          return false;
        }
        if (!Number.isInteger(cellIndex) || cellIndex < 0) {
          console.error("\u274C Cannot modify grid cell - invalid cell index", { cellIndex, gridElement, settings: settings2 });
          return false;
        }
        gridElement.children = normalizeToArray(gridElement.children);
        if (gridElement.children.length === 0 && gridElement.settings) {
          const fallbackChildren = normalizeToArray(gridElement.settings._children);
          if (fallbackChildren.length > 0) {
            gridElement.children = fallbackChildren;
          }
        }
        if (!gridElement.settings || typeof gridElement.settings !== "object") {
          gridElement.settings = {};
        }
        const patternHint = settings2.domPattern || null;
        const patternKey = patternHint || gridElement.settings.grid_pattern || "pattern-1";
        gridElement.settings.grid_pattern = patternKey;
        if (!gridElement.settings.custom_template || typeof gridElement.settings.custom_template !== "object") {
          gridElement.settings.custom_template = {};
        }
        const baseTemplate = self2.getGridTemplateData(patternKey) || {};
        if (!gridElement.settings.custom_template.columns && baseTemplate.columns) {
          gridElement.settings.custom_template.columns = baseTemplate.columns;
        }
        if (!gridElement.settings.custom_template.rows && baseTemplate.rows) {
          gridElement.settings.custom_template.rows = baseTemplate.rows;
        }
        const domAreas = Array.isArray(settings2.domAreas) && settings2.domAreas.length > 0 ? settings2.domAreas.filter((area) => area) : null;
        if (domAreas && domAreas.length > 0) {
          gridElement.settings.custom_template.areas = domAreas.slice();
        } else if (!Array.isArray(gridElement.settings.custom_template.areas) || gridElement.settings.custom_template.areas.length === 0) {
          gridElement.settings.custom_template.areas = Array.isArray(baseTemplate.areas) ? baseTemplate.areas.slice() : [];
        } else {
          gridElement.settings.custom_template.areas = gridElement.settings.custom_template.areas.slice();
        }
        const targetAreasLength = gridElement.settings.custom_template.areas.length;
        for (let i = 0; i < targetAreasLength; i++) {
          if (typeof gridElement.children[i] === "undefined") {
            gridElement.children[i] = null;
          }
        }
        const confirmMessage = settings2.confirmMessage || (settings2.removeCell ? `Delete Cell ${cellIndex + 1} (including its content)?` : `Delete widget from Cell ${cellIndex + 1}?`);
        if (!settings2.skipConfirm) {
          const confirmed = window.confirm(confirmMessage);
          if (!confirmed) {
            console.log("\u274C Deletion cancelled by user");
            return false;
          }
        }
        if (typeof settings2.confirmCallback === "function") {
          try {
            settings2.confirmCallback({
              gridId: gridElement.id,
              cellIndex,
              triggerSource: settings2.triggerSource,
              mode: settings2.removeCell ? "remove-cell" : "clear-content"
            });
          } catch (callbackError) {
            console.warn("\u26A0\uFE0F Confirm callback threw error:", callbackError);
          }
        }
        self2.isGridCellDeleting = true;
        const updateStructure = (key) => {
          if (!gridElement.settings || typeof gridElement.settings !== "object") {
            return;
          }
          if (!gridElement.settings[key]) {
            return;
          }
          const structureArray = normalizeToArray(gridElement.settings[key]);
          if (settings2.removeCell) {
            if (cellIndex < structureArray.length) {
              structureArray.splice(cellIndex, 1);
            }
          } else {
            structureArray[cellIndex] = null;
          }
          gridElement.settings[key] = structureArray;
        };
        const logContext = {
          cellIndex,
          triggerSource: settings2.triggerSource,
          mode: settings2.removeCell ? "remove-cell" : "clear-content"
        };
        console.log("\u{1F5D1}\uFE0F Grid cell delete handler started", logContext);
        if (settings2.removeCell) {
          if (cellIndex < gridElement.children.length) {
            console.log("\u{1F50D} Before delete (children):", gridElement.children);
            gridElement.children.splice(cellIndex, 1);
            console.log("\u{1F50D} After delete (children):", gridElement.children);
          }
        } else {
          if (gridElement.children[cellIndex]) {
            console.log("\u{1F50D} Clearing widget only for cell", cellIndex);
            gridElement.children[cellIndex] = null;
          } else {
            console.log("\u2139\uFE0F Grid cell already empty", logContext);
          }
        }
        updateStructure("_children");
        updateStructure("children");
        if (settings2.removeCell) {
          let currentAreas = [];
          if (Array.isArray(gridElement.settings.custom_template.areas) && gridElement.settings.custom_template.areas.length > 0) {
            currentAreas = gridElement.settings.custom_template.areas.slice();
          } else {
            currentAreas = [];
          }
          if (cellIndex < currentAreas.length) {
            currentAreas.splice(cellIndex, 1);
            gridElement.settings.custom_template.areas = currentAreas;
            console.log("\u{1F9E9} Updated custom areas after delete:", currentAreas);
          } else {
            console.warn("\u26A0\uFE0F Cell index exceeds current areas length", { cellIndex, areasLength: currentAreas.length });
          }
        }
        const $oldElement = $2(`.probuilder-element[data-id="${gridElement.id}"]`);
        const insertBefore = $oldElement.next()[0];
        $oldElement.remove();
        self2.renderElement(gridElement, insertBefore);
        self2.saveHistory();
        setTimeout(() => {
          self2.isGridCellDeleting = false;
        }, 0);
        console.log("\u2705 Grid cell delete handler finished", logContext);
        return true;
      },
      /**
           * Start container column resize - similar to grid cell resize
           * Allows resizing from all directions: top, left, right, bottom, and corners
           */
      startContainerColumnResize: function(containerElement, columnIndex, direction, e) {
        const self2 = this;
        console.log("\u{1F3AF} Starting container column resize:", containerElement.id, "column:", columnIndex, "direction:", direction);
        const $container = $2(`.probuilder-element[data-id="${containerElement.id}"]`);
        const $column = $container.find(`.probuilder-column[data-column-index="${columnIndex}"]`);
        if ($column.length === 0) {
          console.error("Column not found:", columnIndex);
          return;
        }
        const columnRect = $column[0].getBoundingClientRect();
        const containerRect = $container[0].getBoundingClientRect();
        const startWidth = columnRect.width;
        const startHeight = columnRect.height;
        const startTop = columnRect.top - containerRect.top;
        const startLeft = columnRect.left - containerRect.left;
        const startX = e.clientX;
        const startY = e.clientY;
        const originalPosition = $column.css("position");
        const originalZIndex = $column.css("z-index");
        const originalWidth = $column.css("width");
        const originalHeight = $column.css("height");
        const settings2 = containerElement.settings;
        const columnHeights = settings2.column_heights || [];
        const columnPaddings = settings2.column_paddings || [];
        let finalWidth = startWidth;
        let finalHeight = startHeight;
        let finalTop = startTop;
        let finalLeft = startLeft;
        let isResizing = false;
        console.log("Start:", { width: startWidth, height: startHeight });
        $column.css({
          "position": "absolute",
          "top": startTop + "px",
          "left": startLeft + "px",
          "width": startWidth + "px",
          "height": startHeight + "px",
          "z-index": "1000",
          "box-shadow": "0 0 20px rgba(0,124,186,0.4)",
          "border-color": "#007cba",
          "transition": "none"
        });
        const cursorMap = {
          "top": "row-resize",
          "left": "col-resize",
          "right": "col-resize",
          "bottom": "row-resize",
          "both": "nwse-resize"
        };
        $2("body").css("cursor", cursorMap[direction] || "default");
        const $indicator = $2('<div class="column-resize-indicator" style="position: fixed; top: 10px; right: 10px; background: rgba(0,124,186,0.9); color: white; padding: 10px 15px; border-radius: 4px; font-size: 12px; z-index: 99999; font-family: monospace; box-shadow: 0 4px 12px rgba(0,0,0,0.3);"></div>');
        $2("body").append($indicator);
        $2(document).on("mousemove.columnResize", function(moveEvent) {
          moveEvent.preventDefault();
          moveEvent.stopPropagation();
          const deltaX = moveEvent.clientX - startX;
          const deltaY = moveEvent.clientY - startY;
          if (Math.abs(deltaX) > 2 || Math.abs(deltaY) > 2) {
            isResizing = true;
          }
          let newWidth = startWidth;
          let newHeight = startHeight;
          let newLeft = startLeft;
          let newTop = startTop;
          if (direction === "top") {
            newHeight = Math.max(50, startHeight - deltaY);
            newTop = startTop + (startHeight - newHeight);
            $column.css({
              "height": newHeight + "px",
              "top": newTop + "px"
            });
            finalHeight = newHeight;
            finalTop = newTop;
          } else if (direction === "bottom" || direction === "both") {
            newHeight = Math.max(50, startHeight + deltaY);
            $column.css("height", newHeight + "px");
            finalHeight = newHeight;
            finalTop = startTop;
          }
          if (direction === "left") {
            newWidth = Math.max(50, startWidth - deltaX);
            newLeft = startLeft + (startWidth - newWidth);
            $column.css({
              "width": newWidth + "px",
              "left": newLeft + "px"
            });
            finalWidth = newWidth;
            finalLeft = newLeft;
          } else if (direction === "right" || direction === "both") {
            newWidth = Math.max(50, startWidth + deltaX);
            $column.css("width", newWidth + "px");
            finalWidth = newWidth;
            finalLeft = startLeft;
          }
          const widthPercent = Math.round(newWidth / containerRect.width * 100);
          $indicator.html(`
                    <div style="font-weight: bold; margin-bottom: 5px;">Resizing Column ${columnIndex + 1}</div>
                    <div>Width: ${Math.round(newWidth)}px (${widthPercent}%)</div>
                    <div>Height: ${Math.round(newHeight)}px</div>
                    <div style="margin-top: 5px; font-size: 10px; opacity: 0.8;">Release to apply</div>
                `);
        });
        $2(document).on("mouseup.columnResize", function(upEvent) {
          upEvent.preventDefault();
          upEvent.stopPropagation();
          $2(document).off(".columnResize");
          $indicator.remove();
          $2("body").css("cursor", "");
          console.log("Finalizing column resize:", {
            finalWidth,
            finalHeight,
            direction,
            isResizing
          });
          const scaleX = finalWidth / startWidth;
          const scaleY = finalHeight / startHeight;
          if ((direction === "top" || direction === "bottom" || direction === "both") && isResizing) {
            if (!settings2.column_heights) {
              settings2.column_heights = [];
            }
            settings2.column_heights[columnIndex] = Math.round(finalHeight);
            console.log("Updated column height:", columnIndex, "=", Math.round(finalHeight));
          }
          if ((direction === "left" || direction === "right" || direction === "both") && isResizing) {
            const columnsCount = parseInt(settings2.columns || "1");
            const columnWidths = settings2.column_widths ? settings2.column_widths.split(",").map((w) => parseFloat(w.trim())) : [];
            while (columnWidths.length < columnsCount) {
              columnWidths.push(100 / columnsCount);
            }
            const oldWidthPercent = columnWidths[columnIndex];
            const newWidthPercent = finalWidth / containerRect.width * 100;
            const widthDifference = newWidthPercent - oldWidthPercent;
            console.log("Width adjustment:", {
              oldWidthPercent,
              newWidthPercent,
              widthDifference
            });
            columnWidths[columnIndex] = newWidthPercent;
            const otherColumns = [];
            for (let i = 0; i < columnsCount; i++) {
              if (i !== columnIndex) {
                otherColumns.push(i);
              }
            }
            if (otherColumns.length > 0 && widthDifference !== 0) {
              const adjustmentPerColumn = -widthDifference / otherColumns.length;
              otherColumns.forEach((idx) => {
                columnWidths[idx] = Math.max(5, columnWidths[idx] + adjustmentPerColumn);
              });
            }
            const total = columnWidths.reduce((sum, w) => sum + w, 0);
            const normalized = columnWidths.map((w) => w / total * 100);
            settings2.column_widths = normalized.map((w) => w.toFixed(2)).join(",");
            console.log("Updated column widths:", settings2.column_widths);
          }
          $column.css({
            "position": "",
            "top": "",
            "left": "",
            "width": "",
            "height": "",
            "z-index": "",
            "box-shadow": "",
            "transition": ""
          });
          self2.updateElementPreview(containerElement);
          self2.saveData();
          console.log("\u2705 Container column resize complete");
        });
      },
      /**
       * Start widget resize - for all regular widgets
       */
      startWidgetResize: function(element2, $element, direction, e) {
        const self2 = this;
        console.log("\u{1F3AF} Starting widget resize:", element2.id, "direction:", direction);
        const $preview = $element.find(".probuilder-element-preview");
        const startWidth = $preview.outerWidth();
        const startHeight = $preview.outerHeight();
        const startX = e.clientX;
        const startY = e.clientY;
        console.log("Start dimensions:", { width: startWidth, height: startHeight });
        $element.addClass("is-resizing");
        const cursorMap = {
          "top": "ns-resize",
          "bottom": "ns-resize",
          "left": "ew-resize",
          "right": "ew-resize",
          "both": "nwse-resize"
        };
        $2("body").css("cursor", cursorMap[direction] || "default");
        const $indicator = $2(`<div class="probuilder-resize-indicator" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0,0,0,0.8); color: white; padding: 8px 16px; border-radius: 3px; font-size: 13px; z-index: 99999; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, sans-serif; font-weight: 500; pointer-events: none;"></div>`);
        $2("body").append($indicator);
        if (!element2.settings._width) {
          element2.settings._width = "100%";
        }
        if (!element2.settings._height) {
          element2.settings._height = "auto";
        }
        $2(document).on("mousemove.widgetResize", function(moveEvent) {
          moveEvent.preventDefault();
          moveEvent.stopPropagation();
          const deltaX = moveEvent.clientX - startX;
          const deltaY = moveEvent.clientY - startY;
          let newWidth = startWidth;
          let newHeight = startHeight;
          if (direction === "right" || direction === "both") {
            newWidth = Math.max(20, startWidth + deltaX);
          } else if (direction === "left") {
            newWidth = Math.max(20, startWidth - deltaX);
          }
          if (direction === "bottom" || direction === "both") {
            newHeight = Math.max(20, startHeight + deltaY);
          } else if (direction === "top") {
            newHeight = Math.max(20, startHeight - deltaY);
          }
          if (direction === "left" || direction === "right" || direction === "both") {
            $element.css("width", newWidth + "px");
            $preview.css("width", newWidth + "px");
          }
          if (direction === "top" || direction === "bottom" || direction === "both") {
            $element.css("height", newHeight + "px");
            $preview.css("height", newHeight + "px");
          }
          $indicator.text(`${Math.round(newWidth)} \xD7 ${Math.round(newHeight)}`);
        });
        $2(document).on("mouseup.widgetResize", function(upEvent) {
          upEvent.preventDefault();
          upEvent.stopPropagation();
          $2(document).off(".widgetResize");
          $indicator.remove();
          $2("body").css("cursor", "");
          $element.removeClass("is-resizing");
          const deltaX = upEvent.clientX - startX;
          const deltaY = upEvent.clientY - startY;
          let finalWidth = startWidth;
          let finalHeight = startHeight;
          if (direction === "right" || direction === "both") {
            finalWidth = Math.max(20, startWidth + deltaX);
          } else if (direction === "left") {
            finalWidth = Math.max(20, startWidth - deltaX);
          }
          if (direction === "bottom" || direction === "both") {
            finalHeight = Math.max(20, startHeight + deltaY);
          } else if (direction === "top") {
            finalHeight = Math.max(20, startHeight - deltaY);
          }
          if (direction === "left" || direction === "right" || direction === "both") {
            element2.settings._width = Math.round(finalWidth) + "px";
            $element.css("width", element2.settings._width);
            $preview.css("width", element2.settings._width);
          }
          if (direction === "top" || direction === "bottom" || direction === "both") {
            element2.settings._height = Math.round(finalHeight) + "px";
            $element.css("height", element2.settings._height);
            $preview.css("height", element2.settings._height);
          }
          self2.saveHistory();
          console.log("\u2705 Widget resize complete:", {
            width: element2.settings._width,
            height: element2.settings._height
          });
        });
      },
      /**
       * Add new row to container
       */
      addRowToContainer: function(containerId) {
        try {
          console.log("Adding new row to container:", containerId);
          const containerElement = this.elements.find((e) => e.id === containerId);
          if (!containerElement) {
            console.error("Container not found:", containerId);
            alert("Error: Container not found");
            return;
          }
          if (!containerElement.settings.enable_rows) {
            containerElement.settings.enable_rows = "yes";
          }
          if (!containerElement.settings.rows) {
            containerElement.settings.rows = [];
          }
          const newRow = {
            row_columns: "2",
            row_columns_tablet: "2",
            row_columns_mobile: "1",
            row_gap: 20
          };
          containerElement.settings.rows.push(newRow);
          console.log("New row added. Total rows:", containerElement.settings.rows.length);
          this.updateContainerWithChildren(containerElement);
          this.showNotification("New row added to container!", "success");
          return true;
        } catch (error) {
          console.error("\u274C Error adding row to container:", error);
          alert("Error: " + error.message);
          return false;
        }
      },
      /**
       * Reinitialize sidebar widgets draggable
       */
      reinitializeSidebarWidgets: function() {
        const self2 = this;
        try {
          console.log("\u{1F527} Reinitializing sidebar widgets...");
          $2(".probuilder-widget").each(function() {
            const $widget = $2(this);
            const widgetName = $widget.data("widget");
            if ($widget.data("ui-draggable")) {
              $widget.draggable("destroy");
            }
            $widget.draggable({
              helper: function() {
                return $2(this).clone().css({
                  "width": $2(this).width(),
                  "opacity": 0.8,
                  "z-index": 1e4
                });
              },
              appendTo: "body",
              zIndex: 1e4,
              cursor: "move",
              revert: "invalid",
              revertDuration: 200,
              start: function(event, ui) {
                console.log("Started dragging widget:", widgetName);
                $2(".probuilder-element-placeholder").show();
                $2(".probuilder-column").css("outline", "2px dashed #344047");
                $2("#probuilder-preview-area, .probuilder-column").addClass("drop-ready");
              },
              stop: function(event, ui) {
                console.log("Stopped dragging widget");
                $2(".probuilder-column").css("outline", "");
                $2("#probuilder-preview-area, .probuilder-column").removeClass("drop-ready");
                $2(ui.helper).remove();
                $2("body").css("cursor", "");
              }
            });
          });
          console.log("\u2705 Sidebar widgets reinitialized successfully");
        } catch (error) {
          console.error("\u274C Error reinitializing sidebar widgets:", error);
        }
      },
      /**
       * Reinitialize preview area droppable
       */
      reinitializePreviewArea: function() {
        const self2 = this;
        try {
          console.log("\u{1F527} Reinitializing preview area...");
          const $previewArea = $2("#probuilder-preview-area");
          if ($previewArea.data("ui-droppable")) {
            $previewArea.droppable("destroy");
          }
          $previewArea.droppable({
            accept: ".probuilder-widget",
            tolerance: "pointer",
            greedy: false,
            drop: function(event, ui) {
              const $target = $2(event.target);
              if ($target.hasClass("probuilder-column") || $target.closest(".probuilder-column").length > 0) {
                console.log("\u{1F3AF} Drop intercepted by column, skipping preview area handler");
                return;
              }
              const widgetName = ui.draggable.data("widget");
              console.log("\u{1F3AF} Dropped NEW widget on canvas:", widgetName);
              if (widgetName) {
                const dropY = event.pageY;
                const $elements = $previewArea.children(".probuilder-element");
                let insertIndex = $elements.length;
                $elements.each(function(index) {
                  const $el = $2(this);
                  const elTop = $el.offset().top;
                  const elMiddle = elTop + $el.outerHeight() / 2;
                  if (dropY < elMiddle && insertIndex === $elements.length) {
                    insertIndex = index;
                    return false;
                  }
                });
                console.log("Inserting at index:", insertIndex);
                self2.addElementAtPosition(widgetName, insertIndex);
              }
            },
            over: function(event, ui) {
              if (ui.draggable.hasClass("probuilder-widget")) {
                $2(this).addClass("probuilder-drop-active");
              }
            },
            out: function() {
              $2(this).removeClass("probuilder-drop-active");
            }
          });
          console.log("\u2705 Preview area reinitialized successfully");
        } catch (error) {
          console.error("\u274C Error reinitializing preview area:", error);
        }
      },
      /**
       * Get default settings for widget
       */
      getDefaultSettings: function(widget2) {
        const settings2 = {};
        if (widget2.controls) {
          Object.keys(widget2.controls).forEach((key) => {
            const control = widget2.controls[key];
            if (control.default !== void 0) {
              settings2[key] = control.default;
            }
          });
        }
        return settings2;
      },
      /**
       * Render single element
       */
      renderElement: function(element2, insertBefore) {
        try {
          console.log("Rendering element:", element2.id, element2.widgetType);
          const widget2 = this.widgets.find((w) => w.name === element2.widgetType);
          if (!widget2) {
            console.error("Widget not found for element:", element2.widgetType);
            return;
          }
          if (element2.widgetType === "grid-layout") {
            this.ensureGridElementStructure(element2);
          }
          const preview = this.generatePreview(element2);
          console.log("Preview generated for:", element2.id);
          const columnSelector = element2.widgetType === "container" ? `
                    <div class="probuilder-container-controls">
                        <span class="probuilder-container-controls-label">Columns:</span>
                        <div class="probuilder-column-selector">
                            ${[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12].map(
            (num) => `<button class="probuilder-column-btn ${(element2.settings.columns || "1") == num ? "active" : ""}" data-columns="${num}">${num}</button>`
          ).join("")}
                        </div>
                        <div class="probuilder-row-controls" style="margin-top: 10px;">
                            <button class="probuilder-add-row-btn" data-element-id="${element2.id}" style="
                                background: #007cba;
                                color: white;
                                border: none;
                                padding: 5px 10px;
                                border-radius: 3px;
                                font-size: 12px;
                                cursor: pointer;
                                display: flex;
                                align-items: center;
                                gap: 5px;
                            ">
                                <i class="dashicons dashicons-plus-alt2" style="font-size: 14px;"></i>
                                Add Row
                            </button>
                        </div>
                    </div>
                ` : "";
          const $element = $2(`
                    <div class="probuilder-element" data-id="${element2.id}" data-widget="${element2.widgetType}">
                        ${columnSelector}
                        <div class="probuilder-element-controls">
                            <span class="probuilder-element-drag">
                                <i class="dashicons dashicons-move"></i>
                            </span>
                            <span class="probuilder-element-name">${widget2.title}</span>
                            <div class="probuilder-element-actions">
                                <button class="probuilder-element-edit" title="Edit">
                                    <i class="dashicons dashicons-edit"></i>
                                </button>
                                <button class="probuilder-element-duplicate" title="Duplicate">
                                    <i class="dashicons dashicons-admin-page"></i>
                                </button>
                                <button class="probuilder-element-delete" title="Delete">
                                    <i class="dashicons dashicons-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="probuilder-element-preview">
                            ${preview}
                        </div>
                        <div class="probuilder-element-resize-handles">
                            <div class="probuilder-widget-resize-handle probuilder-resize-n" data-direction="top" title="Resize height"></div>
                            <div class="probuilder-widget-resize-handle probuilder-resize-e" data-direction="right" title="Resize width"></div>
                            <div class="probuilder-widget-resize-handle probuilder-resize-s" data-direction="bottom" title="Resize height"></div>
                            <div class="probuilder-widget-resize-handle probuilder-resize-w" data-direction="left" title="Resize width"></div>
                            <div class="probuilder-widget-resize-handle probuilder-resize-ne" data-direction="both" title="Resize both"></div>
                            <div class="probuilder-widget-resize-handle probuilder-resize-se" data-direction="both" title="Resize both"></div>
                            <div class="probuilder-widget-resize-handle probuilder-resize-sw" data-direction="both" title="Resize both"></div>
                            <div class="probuilder-widget-resize-handle probuilder-resize-nw" data-direction="both" title="Resize both"></div>
                        </div>
                        <div class="probuilder-element-add-below">
                            <button class="probuilder-add-below-btn" title="Add Element Below">
                                <i class="dashicons dashicons-plus-alt2"></i>
                            </button>
                        </div>
                    </div>
                `);
          console.log("Element HTML created");
          const self2 = this;
          if (element2.settings._width && element2.settings._width !== "100%") {
            $element.css("width", element2.settings._width);
            $element.find(".probuilder-element-preview").css("width", element2.settings._width);
          }
          if (element2.settings._height && element2.settings._height !== "auto" && element2.widgetType !== "container" && element2.widgetType !== "grid-layout") {
            $element.css("height", element2.settings._height);
            $element.find(".probuilder-element-preview").css("height", element2.settings._height);
          }
          const spacingStyles2 = this.getSpacingStyles(element2.settings);
          const commonStyles = this.getCommonInlineStyles(element2.settings);
          const combinedInitStyles = [spacingStyles2, commonStyles].filter(Boolean).join("; ");
          if (combinedInitStyles) {
            const $previewInit = $element.find(".probuilder-element-preview");
            $previewInit.attr("style", (($previewInit.attr("style") || "") + "; " + combinedInitStyles).trim());
          }
          this.applyResponsiveVisibility(element2, $element);
          $element.find(".probuilder-element-edit").on("click", function(e) {
            e.stopPropagation();
            console.log("Edit button clicked for:", element2.id);
            self2.openSettings(element2);
          });
          $element.find(".probuilder-element-duplicate").on("click", function(e) {
            e.stopPropagation();
            console.log("Duplicate button clicked for:", element2.id);
            self2.duplicateElement(element2);
          });
          $element.find(".probuilder-element-delete").on("click", function(e) {
            e.stopPropagation();
            console.log("Delete button clicked for:", element2.id);
            self2.deleteElement(element2);
          });
          $element.find(".probuilder-add-below-btn").on("click", function(e) {
            e.stopPropagation();
            console.log("Add below button clicked for:", element2.id);
            self2.showWidgetPicker(element2);
          });
          $element.find(".probuilder-widget-resize-handle").on("mousedown", function(e) {
            e.preventDefault();
            e.stopPropagation();
            const direction = $2(this).data("direction");
            console.log("\u{1F3AF} Starting widget resize:", element2.id, "direction:", direction);
            self2.startWidgetResize(element2, $element, direction, e);
          });
          $element.on("click", function(e) {
            if (!$2(e.target).closest(".probuilder-element-actions").length && !$2(e.target).closest(".probuilder-column-btn").length && !$2(e.target).closest(".probuilder-drop-zone").length && !$2(e.target).closest(".probuilder-add-below-btn").length && !$2(e.target).closest(".probuilder-widget-resize-handle").length) {
              console.log("Element clicked, selecting:", element2.id);
              self2.selectElement(element2);
            }
          });
          if (element2.widgetType === "container") {
            $element.find(".probuilder-column-btn").on("click", function(e) {
              e.stopPropagation();
              const columns = $2(this).data("columns");
              console.log("Changing container columns to:", columns);
              element2.settings.columns = columns.toString();
              $2(this).siblings().removeClass("active");
              $2(this).addClass("active");
              self2.updateElementPreview(element2);
            });
            $element.find(".probuilder-add-row-btn").on("click", function(e) {
              e.stopPropagation();
              console.log("Adding new row to container:", element2.id);
              self2.addRowToContainer(element2.id);
            });
            setTimeout(function() {
              console.log("\u{1F527} Attaching initial drop zone handlers for container:", element2.id);
              const $dropZones = $element.find(".probuilder-drop-zone");
              console.log("Found", $dropZones.length, "drop zones");
              $dropZones.each(function() {
                const $zone = $2(this);
                const containerId = $zone.data("container-id");
                const columnIndex = $zone.data("column-index");
                $zone.off("click").on("click", function(e) {
                  e.stopPropagation();
                  e.preventDefault();
                  if (self2.isGridCellResizing) {
                    console.log("\u23F8\uFE0F Initial drop zone click ignored - grid cell resizing");
                    return false;
                  }
                  console.log("\u2705 Initial drop zone clicked:", containerId, "column:", columnIndex);
                  self2.showWidgetTemplateSelector(containerId, columnIndex);
                  return false;
                });
              });
              console.log("\u2705 Initial drop zone handlers attached");
            }, 100);
            setTimeout(function() {
              console.log("\u{1F527} Attaching container column resize handlers for:", element2.id);
              const $resizeHandles = $element.find(".column-resize-handle");
              console.log("Found", $resizeHandles.length, "resize handles");
              $element.off("mousedown.columnResize", ".column-resize-handle");
              $element.on("mousedown.columnResize", ".column-resize-handle", function(e) {
                e.stopPropagation();
                e.preventDefault();
                const columnIndex = $2(this).data("column-index");
                const direction = $2(this).data("direction");
                console.log("\u{1F3AF} Container column resize started:", columnIndex, direction);
                const containerElement = self2.elements.find((el) => el.id === element2.id);
                if (!containerElement) {
                  console.error("Container element not found:", element2.id);
                  return;
                }
                $2(document).on("click.columnResizePrevent", function(clickEvent) {
                  clickEvent.preventDefault();
                  clickEvent.stopPropagation();
                  $2(document).off("click.columnResizePrevent");
                });
                self2.startContainerColumnResize(containerElement, columnIndex, direction, e);
              });
              console.log("\u2705 Container column resize handlers attached");
            }, 100);
          }
          if (element2.widgetType === "grid-layout") {
            setTimeout(function() {
              console.log("\u{1F3A8} Attaching grid drop zone handlers for:", element2.id);
              const $gridDropZones = $element.find(".probuilder-drop-zone");
              console.log("Found", $gridDropZones.length, "grid cells");
              $gridDropZones.each(function() {
                const $zone = $2(this);
                const gridId = $zone.data("grid-id");
                const cellIndex = parseInt($zone.attr("data-cell-index"), 10);
                const $gridCell = $zone.closest(".grid-cell");
                const toolbarHeight = $gridCell.find(".grid-cell-toolbar").outerHeight(true) || 0;
                $gridCell.css("padding-top", toolbarHeight + 8);
                $zone.droppable({
                  accept: ".probuilder-widget",
                  tolerance: "pointer",
                  hoverClass: "probuilder-drop-hover",
                  greedy: true,
                  // Prevent parent elements from also handling the drop
                  drop: function(event, ui) {
                    event.stopPropagation();
                    event.preventDefault();
                    self2.isNestedDropInProgress = true;
                    const finishNestedDrop = () => {
                      setTimeout(() => {
                        self2.isNestedDropInProgress = false;
                      }, 0);
                    };
                    const widgetName = ui.draggable.data("widget");
                    console.log("\u2705 Widget dropped in grid cell:", widgetName, "grid:", gridId, "cell:", cellIndex);
                    const gridElement2 = self2.elements.find((e) => e.id === gridId);
                    if (!gridElement2) {
                      console.error("Grid element not found:", gridId);
                      finishNestedDrop();
                      return;
                    }
                    if (!gridElement2.children) {
                      gridElement2.children = [];
                    }
                    const newElement = {
                      id: "element-" + Date.now() + "-" + Math.random().toString(36).substr(2, 9),
                      widgetType: widgetName,
                      settings: {},
                      children: []
                    };
                    gridElement2.children[cellIndex] = newElement;
                    console.log("Grid children updated:", gridElement2.children);
                    self2.updateElementPreview(gridElement2);
                    setTimeout(() => {
                      self2.openSettings(newElement);
                    }, 100);
                    self2.saveHistory();
                    finishNestedDrop();
                  }
                });
                if (!$zone.hasClass("has-content")) {
                  $zone.off("click");
                  const $emptyContent = $zone.find(".grid-cell-empty-content");
                  const placeholderRect = this.getBoundingClientRect ? this.getBoundingClientRect() : null;
                  if (placeholderRect) {
                    const toolbarEl = $zone.find(".grid-cell-toolbar")[0];
                    const toolbarRect = toolbarEl ? toolbarEl.getBoundingClientRect() : null;
                    if (toolbarRect) {
                      const overlapBuffer = 8;
                      const newWidth = Math.max(placeholderRect.width - (toolbarRect.width + overlapBuffer), 0);
                      const newHeight = Math.max(placeholderRect.height - (toolbarRect.height + overlapBuffer), 0);
                      $emptyContent.css({
                        maxWidth: newWidth + "px",
                        maxHeight: newHeight + "px",
                        overflow: "hidden",
                        display: "inline-flex",
                        alignItems: "center",
                        justifyContent: "center"
                      });
                    }
                  }
                  $emptyContent.off("click").on("click", function(e) {
                    e.stopPropagation();
                    e.preventDefault();
                    if (self2.isGridCellDeleting) {
                      console.log("\u23F8\uFE0F Skipping widget selector - grid cell delete in progress");
                      return false;
                    }
                    if (self2.isGridCellResizing) {
                      console.log("\u23F8\uFE0F Skipping widget selector - grid cell resizing in progress");
                      return false;
                    }
                    console.log("\u2705 VERSION 6.0.0 - Empty content area clicked:", gridId, "cell:", cellIndex);
                    self2.showWidgetTemplateSelector(gridId, cellIndex, true);
                    return false;
                  });
                }
              });
              const $resizeHandles = $element.find(".grid-resize-handle");
              console.log("Found", $resizeHandles.length, "resize handles");
              $element.off("mousedown.gridResize", ".grid-resize-handle");
              $element.on("mousedown.gridResize", ".grid-resize-handle", function(e) {
                e.stopPropagation();
                e.preventDefault();
                const cellIndex = parseInt($2(this).attr("data-cell-index"), 10);
                const direction = $2(this).data("direction");
                console.log("\u{1F3AF} Grid resize started:", cellIndex, direction);
                const gridElement2 = self2.elements.find((el) => el.id === element2.id);
                if (!gridElement2) {
                  console.error("Grid element not found!");
                  return;
                }
                $2(document).on("click.gridResizePrevent", function(clickEvent) {
                  clickEvent.preventDefault();
                  clickEvent.stopPropagation();
                  $2(document).off("click.gridResizePrevent");
                });
                self2.startGridCellResize(gridElement2, cellIndex, direction, e);
              });
              console.log("\u{1F527} VERSION 5.0.0 - Setting up delegated event handlers for grid:", element2.id);
              const gridElement = self2.elements.find((e) => e.id === element2.id);
              $element.off("click.toolbarBtn", ".grid-cell-toolbar button").on("click.toolbarBtn", ".grid-cell-toolbar button", function(e) {
                e.stopPropagation();
                e.stopImmediatePropagation();
              });
              $element.off("click.addContent", ".grid-cell-toolbar .add-content-btn").on("click.addContent", ".grid-cell-toolbar .add-content-btn", function(e) {
                e.stopPropagation();
                e.preventDefault();
                e.stopImmediatePropagation();
                const cellIndexAttr = $2(this).attr("data-cell-index");
                const cellIndex = parseInt(cellIndexAttr, 10);
                if (Number.isNaN(cellIndex)) {
                  console.error("\u274C Invalid cell index on add button:", cellIndexAttr);
                  return false;
                }
                console.log("\u2795 Add content button clicked for cell:", cellIndex);
                self2.showWidgetTemplateSelector(element2.id, cellIndex, true);
                return false;
              });
              console.log("\u{1F527} Setting up cell delete button handlers for grid:", element2.id);
              $element.off("click.cellDelete", ".grid-cell-delete-btn").on("click.cellDelete", ".grid-cell-delete-btn", function(e) {
                e.stopPropagation();
                e.preventDefault();
                e.stopImmediatePropagation();
                const cellIndexAttr = $2(this).attr("data-cell-index");
                const cellIndex = parseInt(cellIndexAttr, 10);
                if (Number.isNaN(cellIndex)) {
                  console.error("\u274C Invalid cell index on delete button:", cellIndexAttr);
                  return false;
                }
                const $gridLayout = $2(this).closest(".probuilder-grid-layout");
                const gridIdAttr = $2(this).attr("data-grid-id") || $2(this).closest(".grid-cell-toolbar").attr("data-grid-id") || element2.id;
                const domPattern = $gridLayout.attr("data-grid-pattern") || null;
                const domAreas = $gridLayout.find(".grid-cell").map(function() {
                  return $2(this).attr("data-original-area") || null;
                }).get();
                const activeGridElement = self2.elements.find((el) => el && el.id === gridIdAttr) || gridElement;
                console.log("\u{1F5D1}\uFE0F Grid cell delete requested", { cellIndex, gridIdAttr, triggerSource: "toolbar-button" });
                const deleted = self2.handleGridCellDelete(activeGridElement, cellIndex, {
                  triggerSource: "toolbar-button",
                  skipConfirm: false,
                  domPattern,
                  domAreas
                });
                if (!deleted) {
                  console.warn("\u26A0\uFE0F Grid cell delete helper returned false", { cellIndex, gridIdAttr });
                }
                return false;
              });
              const $deleteButtons = $element.find(".grid-cell-delete-btn");
              console.log("\u{1F50D} Found", $deleteButtons.length, "cell delete buttons");
              $deleteButtons.each(function(i) {
                console.log("\u{1F50D} Delete button", i, ":", $2(this).attr("data-cell-index"));
              });
              console.log("\u{1F9E9} Initializing grid cell drag-and-drop reordering");
              const $gridContainer = $element.find(".probuilder-grid-layout");
              if ($gridContainer.length > 0) {
                $gridContainer.sortable({
                  items: "> .grid-cell",
                  handle: ".grid-cell-drag-handle",
                  placeholder: "ui-sortable-placeholder grid-cell",
                  tolerance: "pointer",
                  cursor: "grabbing",
                  opacity: 0.7,
                  distance: 10,
                  delay: 100,
                  start: function(event, ui) {
                    console.log("\u{1F3AF} Started dragging grid cell");
                    ui.item.addClass("ui-sortable-helper");
                    $2("body").css("cursor", "grabbing");
                  },
                  stop: function(event, ui) {
                    console.log("\u{1F3AF} Stopped dragging grid cell");
                    ui.item.removeClass("ui-sortable-helper");
                    $2("body").css("cursor", "");
                  },
                  update: function(event, ui) {
                    console.log("\u{1F504} Grid cells reordered - auto-adjusting layout");
                    const newChildren = [];
                    $gridContainer.find(".grid-cell").each(function(index) {
                      const oldIndex = parseInt($2(this).attr("data-cell-index"), 10);
                      const oldChild = gridElement.children ? gridElement.children[oldIndex] : null;
                      newChildren.push(oldChild);
                      $2(this).attr("data-cell-index", index);
                      $2(this).find(".grid-cell-drag-handle").attr("data-cell-index", index);
                      $2(this).find(".grid-cell-delete-btn").attr("data-cell-index", index);
                    });
                    gridElement.children = newChildren;
                    console.log("\u{1F504} Re-rendering grid with new cell order");
                    const $oldElement = $2(`.probuilder-element[data-id="${gridElement.id}"]`);
                    const insertBefore2 = $oldElement.next()[0];
                    $oldElement.remove();
                    self2.renderElement(gridElement, insertBefore2);
                    self2.saveHistory();
                    self2.showToast("\u2705 Grid cells reordered!");
                    console.log("\u2705 Grid layout auto-adjusted like flexbox");
                  }
                });
                console.log("\u2705 Grid cell sortable initialized");
              }
              $element.off("mouseenter.nestedHover mouseleave.nestedHover", ".probuilder-nested-element").on("mouseenter.nestedHover", ".probuilder-nested-element", function() {
                $2(this).find(".probuilder-nested-toolbar").show();
              }).on("mouseleave.nestedHover", ".probuilder-nested-element", function() {
                $2(this).find(".probuilder-nested-toolbar").hide();
              });
              $element.off("click.nestedEdit", ".probuilder-nested-edit").on("click.nestedEdit", ".probuilder-nested-edit", function(e) {
                e.stopPropagation();
                e.preventDefault();
                const $nestedEl = $2(this).closest(".probuilder-nested-element");
                const childId = $nestedEl.data("id");
                const childElement = gridElement.children ? gridElement.children.find((c) => c && c.id === childId) : null;
                if (childElement) {
                  console.log("\u270F\uFE0F Edit nested element:", childId);
                  self2.openSettings(childElement);
                }
              });
              console.log("\u{1F527} Setting up widget delete button handlers for grid:", element2.id);
              setTimeout(function() {
                $element.off("click.nestedDelete", ".probuilder-nested-delete").on("click.nestedDelete", ".probuilder-nested-delete", function(e) {
                  e.stopPropagation();
                  e.preventDefault();
                  const $nestedEl = $2(this).closest(".probuilder-nested-element");
                  const cellIndex = parseInt($nestedEl.closest(".grid-cell").attr("data-cell-index"), 10);
                  const $gridLayout = $nestedEl.closest(".probuilder-grid-layout");
                  const triggerOptions = {
                    triggerSource: "nested-widget-delete",
                    skipConfirm: true,
                    removeCell: false,
                    domPattern: $gridLayout.attr("data-grid-pattern") || null,
                    domAreas: $gridLayout.find(".grid-cell").map(function() {
                      return $2(this).attr("data-original-area") || null;
                    }).get()
                  };
                  if (!gridElement) {
                    console.error("\u274C Grid element not found!");
                    return;
                  }
                  const deleted = self2.handleGridCellDelete(gridElement, cellIndex, triggerOptions);
                  if (!deleted) {
                    console.warn("\u26A0\uFE0F Nested widget delete failed", { cellIndex, triggerOptions });
                  } else {
                    console.log("\u2705 Widget deleted from grid cell", cellIndex);
                  }
                });
                const $widgetDeleteButtons = $element.find(".probuilder-nested-delete");
                console.log("\u{1F50D} Found", $widgetDeleteButtons.length, "widget delete buttons");
                $widgetDeleteButtons.each(function(i) {
                  const $btn = $2(this);
                  const $nestedEl = $btn.closest(".probuilder-nested-element");
                  console.log("\u{1F50D} Widget delete button", i, ":", $nestedEl.data("id"));
                });
              }, 100);
              console.log("\u{1F527} VERSION 12.0.0 - Setting up widget resize handlers for grid:", element2.id);
              setTimeout(function() {
                const dotCount = $element.find(".widget-resize-dot").length;
                const tlCount = $element.find(".widget-resize-dot-tl").length;
                const trCount = $element.find(".widget-resize-dot-tr").length;
                const brCount = $element.find(".widget-resize-dot-br").length;
                const blCount = $element.find(".widget-resize-dot-bl").length;
                console.log("\u{1F50D} VERSION 14.0.0 - Resize Dot Debug:", {
                  total: dotCount,
                  topLeft: tlCount,
                  topRight: trCount,
                  bottomRight: brCount,
                  bottomLeft: blCount
                });
                if (dotCount > 0) {
                  console.log("\u2705 Resize dots found! They should be VISIBLE as colored circles on widget corners.");
                  console.log("\u{1F534} RED = Top-Left, \u{1F7E2} GREEN = Top-Right, \u{1F535} BLUE = Bottom-Right, \u{1F7E1} YELLOW = Bottom-Left");
                } else {
                  console.error("\u274C No resize dots found in DOM!");
                }
              }, 200);
              $element.off("mousedown.widgetResize", ".widget-resize-dot").on("mousedown.widgetResize", ".widget-resize-dot", function(e) {
                e.stopPropagation();
                e.preventDefault();
                const $handle = $2(this);
                const direction = $handle.data("direction");
                const $widget = $handle.closest(".probuilder-resizable-widget");
                const widgetId = $widget.data("id");
                const cellIndex = parseInt($widget.attr("data-cell-index"), 10);
                console.log("\u{1F3AF} VERSION 9.0.0 - Widget resize start:", widgetId, "direction:", direction);
                const widgetElement = gridElement.children[cellIndex];
                if (!widgetElement) return;
                const startX = e.clientX;
                const startY = e.clientY;
                const startWidth = $widget.outerWidth();
                const startHeight = $widget.outerHeight();
                const startLeft = parseFloat($widget.css("marginLeft")) || 0;
                const startTop = parseFloat($widget.css("marginTop")) || 0;
                console.log("\u{1F50D} Start dimensions:", startWidth, "x", startHeight, "position:", startLeft, startTop);
                const $indicator = $2("<div>").css({
                  position: "fixed",
                  top: e.clientY + 20,
                  left: e.clientX + 20,
                  background: "rgba(0,0,0,0.8)",
                  color: "white",
                  padding: "8px 12px",
                  borderRadius: "4px",
                  fontSize: "12px",
                  zIndex: 1e4,
                  pointerEvents: "none"
                }).text(`${Math.round(startWidth)}px \xD7 ${Math.round(startHeight)}px`);
                $2("body").append($indicator);
                $2(document).on("mousemove.widgetResize", function(moveEvent) {
                  const deltaX = moveEvent.clientX - startX;
                  const deltaY = moveEvent.clientY - startY;
                  let newWidth = startWidth;
                  let newHeight = startHeight;
                  let newLeft = startLeft;
                  let newTop = startTop;
                  switch (direction) {
                    case "right":
                      newWidth = Math.max(20, startWidth + deltaX);
                      break;
                    case "left":
                      newWidth = Math.max(20, startWidth - deltaX);
                      newLeft = startLeft + (startWidth - newWidth);
                      break;
                    case "bottom":
                      newHeight = Math.max(20, startHeight + deltaY);
                      break;
                    case "top":
                      newHeight = Math.max(20, startHeight - deltaY);
                      newTop = startTop + (startHeight - newHeight);
                      break;
                    case "top-left":
                      newWidth = Math.max(20, startWidth - deltaX);
                      newHeight = Math.max(20, startHeight - deltaY);
                      newLeft = startLeft + (startWidth - newWidth);
                      newTop = startTop + (startHeight - newHeight);
                      break;
                    case "top-right":
                      newWidth = Math.max(20, startWidth + deltaX);
                      newHeight = Math.max(20, startHeight - deltaY);
                      newTop = startTop + (startHeight - newHeight);
                      break;
                    case "bottom-right":
                      newWidth = Math.max(20, startWidth + deltaX);
                      newHeight = Math.max(20, startHeight + deltaY);
                      break;
                    case "bottom-left":
                      newWidth = Math.max(20, startWidth - deltaX);
                      newHeight = Math.max(20, startHeight + deltaY);
                      newLeft = startLeft + (startWidth - newWidth);
                      break;
                  }
                  $widget.css({
                    width: newWidth + "px",
                    height: newHeight + "px",
                    marginLeft: newLeft + "px",
                    marginTop: newTop + "px"
                  });
                  $indicator.text(`${Math.round(newWidth)}px \xD7 ${Math.round(newHeight)}px`);
                  $indicator.css({
                    top: moveEvent.clientY + 20,
                    left: moveEvent.clientX + 20
                  });
                });
                $2(document).on("mouseup.widgetResize", function(upEvent) {
                  $2(document).off(".widgetResize");
                  $indicator.remove();
                  const finalWidth = $widget.outerWidth();
                  const finalHeight = $widget.outerHeight();
                  const finalLeft = parseFloat($widget.css("marginLeft")) || 0;
                  const finalTop = parseFloat($widget.css("marginTop")) || 0;
                  console.log("\u2705 Final dimensions:", finalWidth, "x", finalHeight, "position:", finalLeft, finalTop);
                  if (!widgetElement.settings) {
                    widgetElement.settings = {};
                  }
                  widgetElement.settings.widget_width = finalWidth + "px";
                  widgetElement.settings.widget_height = finalHeight + "px";
                  widgetElement.settings.widget_margin_left = finalLeft + "px";
                  widgetElement.settings.widget_margin_top = finalTop + "px";
                  self2.saveHistory();
                  console.log("\u2705 Widget resized and saved");
                });
              });
              $element.off("mousedown.nestedDrag", ".probuilder-nested-element").on("mousedown.nestedDrag", ".probuilder-nested-element", function(e) {
                if ($2(e.target).closest(".probuilder-nested-toolbar").length > 0 || $2(e.target).closest(".widget-resize-dot").length > 0) {
                  console.log("\u274C Click on toolbar or resize dot - not dragging");
                  return;
                }
                console.log("\u2705 Click on widget content - can drag");
                const $nestedEl = $2(this);
                const childId = $nestedEl.data("id");
                const sourceCellIndex = parseInt($nestedEl.closest(".grid-cell").attr("data-cell-index"), 10);
                const $gridContainer2 = $nestedEl.closest(".probuilder-grid-layout");
                console.log("\u{1F3AF} VERSION 4.0.0 - Drag start:", childId, "from cell:", sourceCellIndex);
                const widgetData = gridElement.children[sourceCellIndex];
                if (!widgetData) return;
                const $clone = $nestedEl.clone().css({
                  position: "absolute",
                  zIndex: 9999,
                  pointerEvents: "none",
                  opacity: 0.7,
                  transform: "rotate(5deg)",
                  boxShadow: "0 5px 20px rgba(0,0,0,0.3)",
                  width: $nestedEl.outerWidth(),
                  height: $nestedEl.outerHeight()
                });
                $2("body").append($clone);
                $gridContainer2.find(".grid-cell").each(function() {
                  const cellIndex = parseInt($2(this).attr("data-cell-index"), 10);
                  if (cellIndex !== sourceCellIndex) {
                    $2(this).addClass("probuilder-drop-target");
                  }
                });
                let isDragging = false;
                const startX = e.clientX;
                const startY = e.clientY;
                $2(document).on("mousemove.nestedDrag", function(moveEvent) {
                  if (!isDragging) {
                    const deltaX = Math.abs(moveEvent.clientX - startX);
                    const deltaY = Math.abs(moveEvent.clientY - startY);
                    if (deltaX > 5 || deltaY > 5) {
                      isDragging = true;
                      $2("body").css("cursor", "grabbing");
                      console.log("\u{1F3AF} Drag activated");
                    }
                  }
                  if (isDragging) {
                    $clone.css({
                      left: moveEvent.clientX - 50,
                      top: moveEvent.clientY - 25
                    });
                    const $hoveredCell = $2(moveEvent.target).closest(".grid-cell");
                    if ($hoveredCell.length && $hoveredCell.hasClass("probuilder-drop-target")) {
                      $gridContainer2.find(".grid-cell").removeClass("probuilder-drop-hover");
                      $hoveredCell.addClass("probuilder-drop-hover");
                    } else {
                      $gridContainer2.find(".grid-cell").removeClass("probuilder-drop-hover");
                    }
                  }
                });
                $2(document).on("mouseup.nestedDrag", function(upEvent) {
                  $2(document).off(".nestedDrag");
                  $clone.remove();
                  $2("body").css("cursor", "");
                  $gridContainer2.find(".grid-cell").removeClass("probuilder-drop-target probuilder-drop-hover");
                  if (isDragging) {
                    const $droppedCell = $2(upEvent.target).closest(".grid-cell");
                    if ($droppedCell.length && parseInt($droppedCell.attr("data-cell-index"), 10) !== sourceCellIndex) {
                      const targetCellIndex = parseInt($droppedCell.attr("data-cell-index"), 10);
                      console.log("\u{1F3AF} Moving widget from cell", sourceCellIndex, "to cell", targetCellIndex);
                      const widgetToMove = gridElement.children[sourceCellIndex];
                      gridElement.children.splice(sourceCellIndex, 1);
                      let insertIndex = targetCellIndex;
                      if (targetCellIndex > sourceCellIndex) {
                        insertIndex = targetCellIndex - 1;
                      }
                      insertIndex = Math.max(0, Math.min(insertIndex, gridElement.children.length));
                      gridElement.children.splice(insertIndex, 0, widgetToMove);
                      self2.renderElement(gridElement);
                      self2.saveHistory();
                      console.log("\u2705 Widget moved successfully");
                    }
                  }
                });
              });
              console.log("\u2705 Grid drop zone and resize handlers attached");
            }, 100);
          }
          if (element2.widgetType === "container-2") {
            setTimeout(function() {
              console.log("\u{1F3A8} Attaching Container 2 drop zone handlers for:", element2.id);
              const $gridDropZones = $element.find(".probuilder-drop-zone");
              console.log("Found", $gridDropZones.length, "Container 2 cells");
              $gridDropZones.each(function() {
                const $zone = $2(this);
                const gridId = $zone.data("grid-id");
                const cellIndex = parseInt($zone.attr("data-cell-index"), 10);
                $zone.droppable({
                  accept: ".probuilder-widget",
                  tolerance: "pointer",
                  hoverClass: "probuilder-drop-hover",
                  greedy: true,
                  // Prevent parent elements from also handling the drop
                  drop: function(event, ui) {
                    event.stopPropagation();
                    event.preventDefault();
                    self2.isNestedDropInProgress = true;
                    const finishNestedDrop = () => {
                      setTimeout(() => {
                        self2.isNestedDropInProgress = false;
                      }, 0);
                    };
                    const widgetName = ui.draggable.data("widget");
                    console.log("\u2705 Widget dropped in Container 2 cell:", widgetName, "container:", gridId, "cell:", cellIndex);
                    const containerElement = self2.elements.find((e) => e.id === gridId);
                    if (!containerElement) {
                      console.error("Container 2 element not found:", gridId);
                      finishNestedDrop();
                      return;
                    }
                    if (!containerElement.children) {
                      containerElement.children = [];
                    }
                    const newElement = {
                      id: "element-" + Date.now() + "-" + Math.random().toString(36).substr(2, 9),
                      widgetType: widgetName,
                      settings: {},
                      children: []
                    };
                    containerElement.children[cellIndex] = newElement;
                    console.log("Container 2 children updated:", containerElement.children);
                    self2.updateElementPreview(containerElement);
                    self2.saveData();
                    finishNestedDrop();
                  }
                });
                $zone.off("click").on("click", function(e) {
                  e.stopPropagation();
                  console.log("Container 2 cell clicked:", gridId, "cell:", cellIndex);
                  self2.showWidgetPicker(null);
                });
              });
              const $resizeHandles = $element.find(".grid-resize-handle");
              console.log("Found", $resizeHandles.length, "resize handles in Container 2");
              $element.off("mousedown.gridResize", ".grid-resize-handle");
              $element.on("mousedown.gridResize", ".grid-resize-handle", function(e) {
                e.stopPropagation();
                e.preventDefault();
                const cellIndex = parseInt($2(this).attr("data-cell-index"), 10);
                const direction = $2(this).data("direction");
                console.log("\u{1F3AF} Container 2 resize started:", cellIndex, direction);
                const containerElement = self2.elements.find((el) => el.id === element2.id);
                if (!containerElement) {
                  console.error("Container 2 element not found:", element2.id);
                  return;
                }
                $2(document).on("click.gridResizePrevent", function(clickEvent) {
                  clickEvent.preventDefault();
                  clickEvent.stopPropagation();
                  $2(document).off("click.gridResizePrevent");
                });
                self2.startGridCellResize(containerElement, cellIndex, direction, e);
              });
              $2(document).off("mousedown.gridResizeGlobal-" + element2.id);
              $2(document).on("mousedown.gridResizeGlobal-" + element2.id, ".grid-resize-handle", function(e) {
                const $handle = $2(this);
                const $container2Element = $handle.closest('.probuilder-element[data-id="' + element2.id + '"]');
                if ($container2Element.length) {
                  e.stopPropagation();
                  e.preventDefault();
                  const cellIndex = parseInt($handle.attr("data-cell-index"), 10);
                  const direction = $handle.data("direction");
                  console.log("\u{1F3AF} Global Container 2 resize handler:", element2.id, "cell:", cellIndex, "direction:", direction);
                  const containerElement = self2.elements.find((el) => el.id === element2.id);
                  if (containerElement) {
                    $2(document).on("click.gridResizePrevent", function(clickEvent) {
                      clickEvent.preventDefault();
                      clickEvent.stopPropagation();
                      $2(document).off("click.gridResizePrevent");
                    });
                    self2.startGridCellResize(containerElement, cellIndex, direction, e);
                  }
                }
              });
              console.log("\u2705 Container 2 drop zone and resize handlers attached");
            }, 100);
          }
          if (insertBefore) {
            $2(insertBefore).before($element);
            console.log("\u2705 Element inserted before another element");
          } else {
            $2("#probuilder-preview-area").append($element);
            console.log("\u2705 Element appended to preview area");
          }
          this.applyMotionStyles(element2, $element);
          if ($2("#probuilder-preview-area").hasClass("ui-sortable")) {
            $2("#probuilder-preview-area").sortable("refresh");
          }
          console.log("\u2705 Element fully rendered and interactive:", element2.id);
        } catch (error) {
          console.error("\u274C Error rendering element:", error);
          alert("Error rendering element: " + error.message);
          return;
        }
      },
      /**
       * Get spacing styles (margin & padding) from settings
       * Supports dimensions type (grouped: {top, right, bottom, left})
       */
      getSpacingStyles: function(settings2) {
        const spacing = [];
        if (settings2.padding && typeof settings2.padding === "object") {
          const p = settings2.padding;
          if (p.top || p.right || p.bottom || p.left) {
            const pTop = p.top || "0";
            const pRight = p.right || "0";
            const pBottom = p.bottom || "0";
            const pLeft = p.left || "0";
            spacing.push(`padding: ${pTop}px ${pRight}px ${pBottom}px ${pLeft}px`);
          }
        }
        if (settings2.margin && typeof settings2.margin === "object") {
          const m = settings2.margin;
          if (m.top || m.right || m.bottom || m.left) {
            const mTop = m.top || "0";
            const mRight = m.right || "0";
            const mBottom = m.bottom || "0";
            const mLeft = m.left || "0";
            spacing.push(`margin: ${mTop}px ${mRight}px ${mBottom}px ${mLeft}px`);
          }
        }
        return spacing.join("; ");
      },
      /**
       * Build common inline styles from settings
       * Mirrors PHP get_inline_styles: background, border, radius, shadow, transform, opacity, z-index
       */
      getCommonInlineStyles: function(settings2) {
        const styles = [];
        const s = settings2 || {};
        const bgType = s.background_type || "none";
        if (bgType !== "none") {
          if (bgType === "color") {
            if (s.background_color) styles.push(`background-color: ${s.background_color}`);
          } else if (bgType === "gradient") {
            const start = s.background_gradient_start || "#667eea";
            const end = s.background_gradient_end || "#764ba2";
            const angle = typeof s.background_gradient_angle !== "undefined" ? s.background_gradient_angle : 135;
            styles.push(`background: linear-gradient(${parseInt(angle, 10)}deg, ${start}, ${end})`);
          } else if (bgType === "image") {
            const bg = s.background_image || {};
            if (bg.url) {
              styles.push(`background-image: url(${bg.url})`);
              styles.push(`background-size: ${s.background_size || "cover"}`);
              styles.push(`background-position: ${s.background_position || "center center"}`);
              styles.push(`background-repeat: ${s.background_repeat || "no-repeat"}`);
            }
          }
        }
        const borderStyle = s.border_style || "none";
        if (borderStyle !== "none") {
          const bw = s.border_width || { top: "1", right: "1", bottom: "1", left: "1" };
          const bc = s.border_color || "#000000";
          styles.push(`border-style: ${borderStyle}`);
          styles.push(`border-width: ${bw.top || 0}px ${bw.right || 0}px ${bw.bottom || 0}px ${bw.left || 0}px`);
          styles.push(`border-color: ${bc}`);
        }
        const br = s.border_radius;
        if (br && typeof br === "object") {
          const any = (val) => val !== void 0 && val !== "" && val !== "0";
          if (any(br.top) || any(br.right) || any(br.bottom) || any(br.left)) {
            styles.push(`border-radius: ${br.top || 0}px ${br.right || 0}px ${br.bottom || 0}px ${br.left || 0}px`);
          }
        }
        if (s.box_shadow_enable === "yes") {
          const h = parseInt(s.box_shadow_h || 0, 10);
          const v = parseInt(s.box_shadow_v || 5, 10);
          const blur = parseInt(s.box_shadow_blur || 15, 10);
          const spread = parseInt(s.box_shadow_spread || 0, 10);
          const color = s.box_shadow_color || "rgba(0,0,0,0.2)";
          styles.push(`box-shadow: ${h}px ${v}px ${blur}px ${spread}px ${color}`);
        }
        const t = [];
        if (typeof s.rotate !== "undefined" && s.rotate != 0) t.push(`rotate(${s.rotate}deg)`);
        if (typeof s.scale !== "undefined" && s.scale != 100) {
          const scaleDecimal = parseFloat(s.scale) / 100;
          t.push(`scale(${scaleDecimal})`);
        }
        if (typeof s.skew_x !== "undefined" && s.skew_x != 0 || typeof s.skew_y !== "undefined" && s.skew_y != 0) {
          const sx = s.skew_x || 0;
          const sy = s.skew_y || 0;
          t.push(`skew(${sx}deg, ${sy}deg)`);
        }
        if (t.length) styles.push(`transform: ${t.join(" ")}`);
        if (typeof s.opacity !== "undefined" && s.opacity !== "" && s.opacity != 100) {
          const opacityDecimal = parseFloat(s.opacity) / 100;
          styles.push(`opacity: ${opacityDecimal}`);
        }
        if (typeof s.z_index !== "undefined" && s.z_index !== "") styles.push(`z-index: ${parseInt(s.z_index, 10)}`);
        return styles.join("; ");
      },
      /**
       * Responsive visibility handling (compact, no extra CSS files)
       */
      applyResponsiveVisibility: function(element2, $element) {
        try {
          const s = element2.settings || {};
          const ww = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
          let shouldHide = false;
          if (ww <= 767 && s.hide_mobile === "yes") shouldHide = true;
          if (ww >= 768 && ww <= 1024 && s.hide_tablet === "yes") shouldHide = true;
          if (ww >= 1025 && s.hide_desktop === "yes") shouldHide = true;
          const $target = $element && $element.length ? $element : $2(`.probuilder-element[data-id="${element2.id}"]`);
          if ($target.length) {
            $target.toggle(shouldHide ? false : true);
          }
        } catch (e) {
          console.error("applyResponsiveVisibility error:", e);
        }
      },
      applyResponsiveVisibilityToAll: function() {
        if (!Array.isArray(this.elements)) return;
        const self2 = this;
        this.elements.forEach(function(el) {
          self2.applyResponsiveVisibility(el);
        });
      },
      /**
       * Generate preview HTML
       */
      generatePreview: function(element2, depth2 = 0) {
        try {
          if (depth2 > 10) {
            console.error("Max recursion depth reached for element:", element2.id);
            return '<div style="padding: 20px; color: #f00;">Max nesting depth reached</div>';
          }
          const widget2 = this.widgets.find((w) => w.name === element2.widgetType);
          if (!widget2) {
            console.error("Widget not found:", element2.widgetType);
            return '<div style="padding: 20px; color: #999; text-align: center;">Widget not found: ' + element2.widgetType + "</div>";
          }
          const settings2 = element2.settings || {};
          const spacingStyles2 = this.getSpacingStyles(settings2);
          const renderer = widgetRenderers[element2.widgetType];
          if (renderer) {
            return renderer({ element: element2, settings: settings2, spacingStyles: spacingStyles2, app: this, depth: depth2, widget: widget2 });
          }
          return `<div style="padding: 25px; background: #f8f9fa; text-align: center; border: 1px solid #e6e9ec; border-radius: 3px; color: #6d7882;"><i class="${widget2.icon}" style="font-size: 32px; color: #93003c; margin-bottom: 10px;"></i><br><strong>${widget2.title}</strong><br><small style="color: #a4afb7;">Click edit to customize</small></div>`;
        } catch (error) {
          console.error("Error generating preview:", error);
          return '<div style="padding: 20px; color: #f00; text-align: center;">Error generating preview: ' + error.message + "</div>";
        }
      },
      /**
       * Render WooCommerce products fallback (sample products)
       */
      renderWooProductsFallback: function(containerId, opts) {
        const sampleProducts = [
          { title: "Sample Product 1", price: "$29.99", image: "https://via.placeholder.com/300x300/92003b/ffffff?text=Product+1", sale: true, rating: 5 },
          { title: "Sample Product 2", price: "$39.99", image: "https://via.placeholder.com/300x300/667eea/ffffff?text=Product+2", sale: false, rating: 4 },
          { title: "Sample Product 3", price: "$49.99", image: "https://via.placeholder.com/300x300/4facfe/ffffff?text=Product+3", sale: false, rating: 5 },
          { title: "Sample Product 4", price: "$19.99", image: "https://via.placeholder.com/300x300/764ba2/ffffff?text=Product+4", sale: true, rating: 4 }
        ].slice(0, opts.wooPerPage);
        let html = `
                <div style="background: #fffbeb; border: 2px dashed #fbbf24; border-radius: 8px; padding: 12px; margin-bottom: 15px; text-align: center;">
                    <i class="dashicons dashicons-info" style="color: #f59e0b;"></i>
                    <strong style="color: #92400e; font-size: 13px;">Preview Mode</strong>
                    <p style="margin: 5px 0 0; font-size: 12px; color: #78350f;">Real products will show on frontend after save</p>
                </div>
                <div style="display: grid; grid-template-columns: repeat(${opts.wooColumns}, 1fr); gap: ${opts.wooRowGap}px ${opts.wooGap}px;">
            `;
        sampleProducts.forEach((p) => {
          html += `<div style="border-radius: ${opts.wooBorderRadius}px; overflow: hidden; background: ${opts.wooCardBg}; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">`;
          if (opts.wooShowImage) {
            html += `<div style="position: relative; background: #f8f9fa;"><img src="${p.image}" style="width: 100%; height: auto; display: block;">`;
            if (opts.wooShowBadge && p.sale) html += `<span style="position: absolute; top: 10px; right: 10px; background: #e74c3c; color: #fff; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: 600;">Sale</span>`;
            html += `</div>`;
          }
          html += `<div style="padding: 20px;">`;
          if (opts.wooShowTitle) html += `<h3 style="margin: 0 0 10px; font-size: 18px; color: ${opts.wooTitleColor};">${p.title}</h3>`;
          if (opts.wooShowRating) {
            html += `<div style="margin-bottom: 10px; color: #fbbf24;">`;
            for (let i = 0; i < 5; i++) html += i < p.rating ? "\u2605" : "\u2606";
            html += `</div>`;
          }
          if (opts.wooShowPrice) html += `<div style="margin-bottom: 15px; font-size: 20px; font-weight: 600; color: ${opts.wooPriceColor};">${p.price}</div>`;
          if (opts.wooShowCart) html += `<a href="#" style="background: ${opts.wooBtnBg}; color: ${opts.wooBtnText}; padding: 10px 20px; text-decoration: none; display: inline-block; border-radius: 4px; font-weight: 600;">Add to Cart</a>`;
          html += `</div></div>`;
        });
        html += `</div>`;
        $2("#" + containerId).html(html);
      },
      /**
       * Render all elements
       */
      renderElements: function() {
        $2("#probuilder-preview-area").empty();
        this.elements.forEach((element2) => {
          this.renderElement(element2);
        });
      },
      /**
       * Select element (automatically opens settings)
       */
      selectElement: function(element2) {
        this.selectedElement = element2;
        $2(".probuilder-element").removeClass("selected");
        $2(`.probuilder-element[data-id="${element2.id}"]`).addClass("selected");
        this.openSettings(element2);
      },
      /**
       * Open settings panel
       */
      openSettings: function(element2) {
        console.log("==================== OPENING SETTINGS ====================");
        console.log("Element:", element2.id, element2.widgetType);
        this.selectedElement = element2;
        $2(".probuilder-element").removeClass("selected");
        $2(`.probuilder-element[data-id="${element2.id}"]`).addClass("selected");
        const widget2 = this.widgets.find((w) => w.name === element2.widgetType);
        if (!widget2) {
          console.error("Widget not found for:", element2.widgetType);
          return;
        }
        console.log("Widget found:", widget2.title);
        console.log("Controls count:", widget2.controls ? Object.keys(widget2.controls).length : 0);
        console.log("Controls object:", widget2.controls);
        if (widget2.controls) {
          Object.keys(widget2.controls).forEach((key) => {
            const control = widget2.controls[key];
            console.log(`  ${key}: tab="${control.tab || "UNDEFINED"}" type="${control.type}"`);
          });
        }
        $2("#probuilder-settings-title").text(widget2.title);
        $2(".probuilder-settings-placeholder").hide();
        $2("#probuilder-settings-tabs").show();
        $2(".probuilder-settings-tab").removeClass("active");
        $2('.probuilder-settings-tab[data-tab="content"]').addClass("active");
        this.renderSettings(element2, widget2, "content");
        console.log("Settings rendered for:", widget2.title);
      },
      /**
       * Get motion controls for all widgets - Enhanced with comprehensive animations
       */
      getMotionControls: function() {
        return {
          // Entrance Animation
          "_motion_animation": {
            label: "Entrance Animation",
            type: "select",
            default: "none",
            options: {
              "none": "None",
              // Fade Animations
              "fadeIn": "Fade In",
              "fadeInUp": "Fade In Up",
              "fadeInDown": "Fade In Down",
              "fadeInLeft": "Fade In Left",
              "fadeInRight": "Fade In Right",
              // Zoom Animations
              "zoomIn": "Zoom In",
              "zoomInUp": "Zoom In Up",
              "zoomInDown": "Zoom In Down",
              "zoomInLeft": "Zoom In Left",
              "zoomInRight": "Zoom In Right",
              // Slide Animations
              "slideInUp": "Slide In Up",
              "slideInDown": "Slide In Down",
              "slideInLeft": "Slide In Left",
              "slideInRight": "Slide In Right",
              // Bounce Animations
              "bounceIn": "Bounce In",
              "bounceInUp": "Bounce In Up",
              "bounceInDown": "Bounce In Down",
              "bounceInLeft": "Bounce In Left",
              "bounceInRight": "Bounce In Right",
              // Flip & Rotate
              "flipInX": "Flip In X",
              "flipInY": "Flip In Y",
              "rotateIn": "Rotate In",
              "rotateInUpLeft": "Rotate In Up Left",
              "rotateInUpRight": "Rotate In Up Right",
              "rotateInDownLeft": "Rotate In Down Left",
              "rotateInDownRight": "Rotate In Down Right",
              // Special Effects
              "lightSpeedInRight": "Light Speed In Right",
              "lightSpeedInLeft": "Light Speed In Left",
              "rollIn": "Roll In",
              "jackInTheBox": "Jack In The Box",
              "backInUp": "Back In Up",
              "backInDown": "Back In Down",
              "backInLeft": "Back In Left",
              "backInRight": "Back In Right"
            },
            tab: "motion"
          },
          "_motion_duration": {
            label: "Animation Duration (ms)",
            type: "slider",
            default: 1e3,
            range: {
              px: { min: 200, max: 3e3, step: 100 }
            },
            unit: "ms",
            tab: "motion"
          },
          "_motion_delay": {
            label: "Animation Delay (ms)",
            type: "slider",
            default: 0,
            range: {
              px: { min: 0, max: 5e3, step: 100 }
            },
            unit: "ms",
            tab: "motion"
          },
          "_motion_easing": {
            label: "Easing Function",
            type: "select",
            default: "ease-in-out",
            options: {
              "linear": "Linear",
              "ease": "Ease",
              "ease-in": "Ease In",
              "ease-out": "Ease Out",
              "ease-in-out": "Ease In Out",
              "ease-in-back": "Ease In Back",
              "ease-out-back": "Ease Out Back",
              "ease-in-out-back": "Ease In Out Back"
            },
            tab: "motion"
          },
          // Hover Animation
          "_motion_hover": {
            label: "Hover Animation",
            type: "select",
            default: "none",
            options: {
              "none": "None",
              "grow": "Grow",
              "shrink": "Shrink",
              "pulse": "Pulse",
              "push": "Push",
              "pop": "Pop",
              "bounce": "Bounce",
              "rotate": "Rotate",
              "grow-rotate": "Grow & Rotate",
              "float": "Float",
              "sink": "Sink",
              "wobble": "Wobble",
              "skew": "Skew",
              "buzz": "Buzz"
            },
            tab: "motion"
          },
          // Advanced Options
          "_motion_repeat": {
            label: "Repeat Animation",
            type: "select",
            default: "1",
            options: {
              "1": "Once",
              "2": "Twice",
              "3": "Three Times",
              "infinite": "Infinite Loop"
            },
            tab: "motion"
          },
          "_motion_viewport": {
            label: "Animate on Scroll",
            type: "switcher",
            default: "yes",
            tab: "motion"
          },
          "_motion_viewport_offset": {
            label: "Viewport Offset (%)",
            type: "slider",
            default: 20,
            range: {
              px: { min: 0, max: 100, step: 5 }
            },
            unit: "%",
            tab: "motion"
          },
          "_motion_preview_btn": {
            label: "Preview Animation",
            type: "raw_html",
            html: '<button type="button" class="probuilder-button probuilder-preview-animation" style="width: 100%; margin-top: 10px;">\u25B6\uFE0F Preview Animation</button>',
            tab: "motion"
          }
        };
      },
      /**
       * Render settings
       */
      renderSettings: function(element2, widget2, activeTab = "content") {
        const $content = $2("#probuilder-settings-content");
        $content.empty();
        console.log("Rendering settings for:", widget2.name, "Tab:", activeTab);
        if (!widget2.controls) {
          console.warn("No controls defined for widget:", widget2.name);
          $content.html('<div style="padding: 20px; text-align: center; color: #999;">No settings available for this widget</div>');
          return;
        }
        const allControls = activeTab === "motion" ? this.getMotionControls() : widget2.controls;
        console.log("Controls to render:", Object.keys(allControls).length);
        const self2 = this;
        let controlsRendered = 0;
        let responsiveRowRendered = false;
        Object.keys(allControls).forEach((key) => {
          const control = allControls[key];
          const controlTab = control.tab || "content";
          const willShow = controlTab === activeTab;
          console.log(`  Processing: ${key} | Tab: ${controlTab} | Active: ${activeTab} | Show: ${willShow}`);
          if (controlTab !== activeTab) {
            console.log(`    \u21B3 Skipped (tab mismatch)`);
            return;
          }
          if (control.type === "section_start") {
            console.log(`    \u21B3 Adding section header: ${control.label}`);
            $content.append(`<div class="probuilder-control-section"><h4>${control.label}</h4></div>`);
            return;
          }
          if (activeTab === "style" && !responsiveRowRendered && key === "hide_desktop") {
            const vDesktop = element2.settings["hide_desktop"] === "yes";
            const vTablet = element2.settings["hide_tablet"] === "yes";
            const vMobile = element2.settings["hide_mobile"] === "yes";
            const rowHtml = `
						<div class="probuilder-control">
							<label>Responsive</label>
							<div class="probuilder-responsive-row" style="display: flex; gap: 10px; align-items: center; flex-wrap: nowrap;">
								<label class="probuilder-toggle-switch" style="display:flex; align-items:center; gap:6px; cursor:pointer;">
									<input type="checkbox" class="probuilder-responsive-toggle" data-device="desktop" ${vDesktop ? "checked" : ""} style="display:none;">
									<span class="probuilder-switcher-track" style="position:relative; width:44px; height:24px; background:${vDesktop ? "#92003b" : "#cbd5e1"}; border-radius:12px; transition:background 0.3s; display:inline-block;">
										<span class="probuilder-switcher-thumb" style="position:absolute; left:${vDesktop ? "22px" : "2px"}; top:2px; width:20px; height:20px; background:#fff; border-radius:50%; transition:left 0.3s; box-shadow:0 2px 4px rgba(0,0,0,0.2);"></span>
									</span>
									<i class="dashicons dashicons-desktop" style="opacity:.7"></i>
									<span style="font-size:12px; color:#374151;">Hide Desktop</span>
								</label>
								<label class="probuilder-toggle-switch" style="display:flex; align-items:center; gap:6px; cursor:pointer;">
									<input type="checkbox" class="probuilder-responsive-toggle" data-device="tablet" ${vTablet ? "checked" : ""} style="display:none;">
									<span class="probuilder-switcher-track" style="position:relative; width:44px; height:24px; background:${vTablet ? "#92003b" : "#cbd5e1"}; border-radius:12px; transition:background 0.3s; display:inline-block;">
										<span class="probuilder-switcher-thumb" style="position:absolute; left:${vTablet ? "22px" : "2px"}; top:2px; width:20px; height:20px; background:#fff; border-radius:50%; transition:left 0.3s; box-shadow:0 2px 4px rgba(0,0,0,0.2);"></span>
									</span>
									<i class="dashicons dashicons-tablet" style="opacity:.7"></i>
									<span style="font-size:12px; color:#374151;">Hide Tablet</span>
								</label>
								<label class="probuilder-toggle-switch" style="display:flex; align-items:center; gap:6px; cursor:pointer;">
									<input type="checkbox" class="probuilder-responsive-toggle" data-device="mobile" ${vMobile ? "checked" : ""} style="display:none;">
									<span class="probuilder-switcher-track" style="position:relative; width:44px; height:24px; background:${vMobile ? "#92003b" : "#cbd5e1"}; border-radius:12px; transition:background 0.3s; display:inline-block;">
										<span class="probuilder-switcher-thumb" style="position:absolute; left:${vMobile ? "22px" : "2px"}; top:2px; width:20px; height:20px; background:#fff; border-radius:50%; transition:left 0.3s; box-shadow:0 2px 4px rgba(0,0,0,0.2);"></span>
									</span>
									<i class="dashicons dashicons-smartphone" style="opacity:.7"></i>
									<span style="font-size:12px; color:#374151;">Hide Mobile</span>
								</label>
							</div>
						</div>`;
            const $row = $2(rowHtml);
            const keyMap = { desktop: "hide_desktop", tablet: "hide_tablet", mobile: "hide_mobile" };
            $row.find(".probuilder-responsive-toggle").on("change", function(e) {
              const device = $2(e.currentTarget).data("device");
              const settingKey = keyMap[device];
              const checked = $2(e.currentTarget).is(":checked");
              element2.settings[settingKey] = checked ? "yes" : "no";
              const $track = $2(e.currentTarget).siblings(".probuilder-switcher-track");
              const $thumb = $track.find(".probuilder-switcher-thumb");
              $track.css("background", checked ? "#92003b" : "#cbd5e1");
              $thumb.css("left", checked ? "22px" : "2px");
              console.log("\u2705 Responsive toggled:", settingKey, element2.settings[settingKey]);
              ProBuilder2.applyResponsiveVisibility(element2);
            });
            $content.append($row);
            controlsRendered++;
            responsiveRowRendered = true;
            return;
          }
          if (responsiveRowRendered && (key === "hide_tablet" || key === "hide_mobile")) {
            return;
          }
          const value = element2.settings[key] !== void 0 ? element2.settings[key] : control.default;
          const $control = this.renderControl(key, control, value, element2);
          controlsRendered++;
          if (control.type === "dimensions") {
            $control.find("input").on("input change", function() {
              const dimension = $2(this).data("dimension");
              if (!element2.settings[key]) {
                element2.settings[key] = {};
              }
              element2.settings[key][dimension] = $2(this).val();
              console.log("Dimension updated:", key, dimension, $2(this).val());
              self2.updateElementPreview(element2);
            });
          } else if (control.type === "border") {
            $control.find("input, select").on("input change", function() {
              const borderProp = $2(this).data("border");
              if (!element2.settings[key]) {
                element2.settings[key] = {};
              }
              element2.settings[key][borderProp] = $2(this).val();
              console.log("Border updated:", key, borderProp, $2(this).val());
              self2.updateElementPreview(element2);
            });
          } else if (control.type === "box-shadow") {
            $control.find("input").on("input change", function() {
              const shadowProp = $2(this).data("shadow");
              if (!element2.settings[key]) {
                element2.settings[key] = {};
              }
              element2.settings[key][shadowProp] = $2(this).val();
              console.log("Shadow updated:", key, shadowProp, $2(this).val());
              self2.updateElementPreview(element2);
            });
          } else if (control.type === "angle") {
            let updateAngleUI = function(newAngle) {
              newAngle = Math.max(0, Math.min(360, parseInt(newAngle) || 0));
              $angleInput.val(newAngle);
              const angleRad = (newAngle - 90) * Math.PI / 180;
              const handleX = 50 + 45 * Math.cos(angleRad);
              const handleY = 50 + 45 * Math.sin(angleRad);
              $angleHandle.css({
                "top": handleY + "px",
                "left": handleX + "px"
              });
              $angleLine.attr({
                "x2": handleX,
                "y2": handleY
              });
              const arcLength = newAngle / 360 * 283;
              $angleArc.attr("stroke-dasharray", `${arcLength} 283`);
              $angleDisplay.text(Math.round(newAngle) + "\xB0");
              element2.settings[key] = newAngle;
              self2.updateElementPreview(element2);
            };
            const $angleInput = $control.find(".probuilder-angle-input");
            const $angleHandle = $control.find(".angle-handle");
            const $angleArc = $control.find(`#${$angleInput.attr("id").replace("-input", "-arc")}`);
            const $angleLine = $control.find(`#${$angleInput.attr("id").replace("-input", "-line")}`);
            const $angleWrapper = $control.find(".angle-circle-wrapper");
            const $angleDisplay = $angleWrapper.find("div").last();
            $angleInput.on("input change", function() {
              updateAngleUI($2(this).val());
            });
            $control.find(".angle-preset-btn").on("click", function(e) {
              e.preventDefault();
              const presetAngle = $2(this).data("angle");
              updateAngleUI(presetAngle);
              $2(this).css({
                "background": "#92003b",
                "color": "white",
                "border-color": "#92003b"
              });
              setTimeout(() => {
                $2(this).css({
                  "background": "white",
                  "color": "inherit",
                  "border-color": "#d1d5db"
                });
              }, 200);
            });
            let isDragging = false;
            $angleWrapper.on("mousedown", function(e) {
              e.preventDefault();
              isDragging = true;
              $angleHandle.css("cursor", "grabbing");
              $angleWrapper.css("cursor", "grabbing");
              const wrapperOffset = $angleWrapper.offset();
              const centerX = wrapperOffset.left + 50;
              const centerY = wrapperOffset.top + 50;
              const mouseX = e.pageX;
              const mouseY = e.pageY;
              const deltaX = mouseX - centerX;
              const deltaY = mouseY - centerY;
              let angle = Math.atan2(deltaY, deltaX) * (180 / Math.PI);
              angle = (angle + 90) % 360;
              if (angle < 0) angle += 360;
              updateAngleUI(Math.round(angle));
            });
            $2(document).on("mousemove", function(e) {
              if (!isDragging) return;
              const wrapperOffset = $angleWrapper.offset();
              const centerX = wrapperOffset.left + 50;
              const centerY = wrapperOffset.top + 50;
              const mouseX = e.pageX;
              const mouseY = e.pageY;
              const deltaX = mouseX - centerX;
              const deltaY = mouseY - centerY;
              let angle = Math.atan2(deltaY, deltaX) * (180 / Math.PI);
              angle = (angle + 90) % 360;
              if (angle < 0) angle += 360;
              updateAngleUI(Math.round(angle));
            });
            $2(document).on("mouseup", function() {
              if (isDragging) {
                isDragging = false;
                $angleHandle.css("cursor", "grab");
                $angleWrapper.css("cursor", "pointer");
              }
            });
            $angleWrapper.on("mouseenter", function() {
              $angleHandle.css("transform", "translate(-50%, -50%) scale(1.2)");
            }).on("mouseleave", function() {
              if (!isDragging) {
                $angleHandle.css("transform", "translate(-50%, -50%) scale(1)");
              }
            });
          } else if (control.type === "typography") {
            $control.find("input, select").on("input change", function() {
              const typoProp = $2(this).data("typo");
              if (!element2.settings[key]) {
                element2.settings[key] = {};
              }
              element2.settings[key][typoProp] = $2(this).val();
              console.log("Typography updated:", key, typoProp, $2(this).val());
              self2.updateElementPreview(element2);
            });
          } else if (control.type === "switcher") {
            $control.find('input[type="checkbox"]').on("change", function() {
              element2.settings[key] = $2(this).is(":checked") ? "yes" : "no";
              console.log("Switcher updated:", key, element2.settings[key]);
              self2.updateElementPreview(element2);
            });
          } else if (control.type === "slider") {
            $control.find(".probuilder-slider").on("input change", function() {
              const newValue = parseFloat($2(this).val()) || 0;
              element2.settings[key] = newValue;
              console.log("\u2705 Slider updated:", key, "=", newValue);
              $2(this).next(".probuilder-slider-value").text(newValue + (control.unit || ""));
              if (key.startsWith("_motion_")) {
                console.log("\u{1F3AC} Motion slider changed! Applying animation...");
                self2.applyMotionStyles(element2);
              }
              self2.updateElementPreview(element2);
            });
          } else if (control.type === "color") {
            $control.find('input[type="color"]').on("input change", function() {
              element2.settings[key] = $2(this).val();
              console.log("\u2705 Color updated:", key, $2(this).val());
              self2.updateElementPreview(element2);
            });
          } else if (control.type === "select") {
            $control.find("select").on("change", function() {
              const newValue = $2(this).val();
              element2.settings[key] = newValue;
              console.log("\u2705 Select updated:", key, "=", newValue);
              if (key === "background_type") {
                self2.updateElementPreview(element2);
              } else {
                self2.updateElementPreview(element2);
              }
            });
            $control.find(".probuilder-gradient-angle-slider").on("input change", function() {
              const newAngle = parseFloat($2(this).val()) || 135;
              element2.settings[key] = newAngle;
              console.log("\u2705 Gradient angle updated:", newAngle);
              const $container = $2(this).closest(".probuilder-gradient-angle-wrapper");
              const circleSize = 120;
              const center = circleSize / 2;
              const radius = 40;
              const angleRad = (newAngle - 90) * Math.PI / 180;
              const dotX = center + radius * Math.cos(angleRad);
              const dotY = center + radius * Math.sin(angleRad);
              const $svg = $container.find("svg");
              $svg.find("line").attr({ x2: dotX, y2: dotY });
              $svg.find("circle:last-child").attr({ cx: dotX, cy: dotY });
              $container.find(".probuilder-angle-value").text(Math.round(newAngle) + "\xB0");
              self2.updateElementPreview(element2);
            });
          } else if (control.type === "repeater") {
            const $repeater = $control.find(".probuilder-repeater");
            const fields = control.fields || [];
            $repeater.find(".probuilder-repeater-toggle").off("click").on("click", function(e) {
              e.stopPropagation();
              const $item = $2(this).closest(".probuilder-repeater-item");
              const $fields = $item.find(".probuilder-repeater-item-fields");
              const $icon = $2(this).find(".dashicons");
              $fields.slideToggle(200);
              $icon.toggleClass("dashicons-arrow-down-alt2 dashicons-arrow-up-alt2");
            });
            $repeater.find(".probuilder-repeater-delete").off("click").on("click", function(e) {
              e.stopPropagation();
              const $item = $2(this).closest(".probuilder-repeater-item");
              const index = parseInt($item.data("index"));
              if (!Array.isArray(element2.settings[key])) {
                element2.settings[key] = [];
              }
              element2.settings[key].splice(index, 1);
              console.log("Repeater item deleted:", key, index);
              self2.renderSettings(element2, widget2, activeTab);
              self2.updateElementPreview(element2);
            });
            $repeater.find(".probuilder-repeater-item").each(function() {
              const $item = $2(this);
              const index = parseInt($item.data("index"));
              $item.find("input, textarea").off("input change").on("input change", function() {
                const fieldName = $2(this).data("field");
                const fieldValue = $2(this).val();
                if (!Array.isArray(element2.settings[key])) {
                  element2.settings[key] = [];
                }
                if (!element2.settings[key][index]) {
                  element2.settings[key][index] = {};
                }
                element2.settings[key][index][fieldName] = fieldValue;
                console.log("Repeater field updated:", key, index, fieldName, fieldValue);
                self2.updateElementPreview(element2);
              });
            });
            $repeater.find(".probuilder-repeater-add").off("click").on("click", function(e) {
              e.stopPropagation();
              if (!Array.isArray(element2.settings[key])) {
                element2.settings[key] = [];
              }
              const newItem = {};
              fields.forEach((field) => {
                newItem[field.name] = field.default || "";
              });
              element2.settings[key].push(newItem);
              console.log("Repeater item added:", key);
              self2.renderSettings(element2, widget2, activeTab);
              self2.updateElementPreview(element2);
            });
            $repeater.find(".probuilder-repeater-items").sortable({
              handle: ".probuilder-repeater-handle",
              axis: "y",
              placeholder: "probuilder-repeater-placeholder",
              update: function(event, ui) {
                const newOrder = [];
                $repeater.find(".probuilder-repeater-item").each(function() {
                  const index = parseInt($2(this).data("index"));
                  newOrder.push(element2.settings[key][index]);
                });
                element2.settings[key] = newOrder;
                console.log("Repeater items reordered:", key);
                self2.renderSettings(element2, widget2, activeTab);
                self2.updateElementPreview(element2);
              }
            });
          } else {
            $control.find(".probuilder-switcher-input").on("change", function() {
              element2.settings[key] = $2(this).is(":checked") ? "yes" : "no";
              console.log("\u2705 Switcher updated:", key, element2.settings[key]);
              self2.updateElementPreview(element2);
            });
            $control.find("input, select, textarea").not(".probuilder-switcher-input").on("input change", function() {
              const newValue = $2(this).val();
              element2.settings[key] = newValue;
              console.log("\u2705 Control updated:", key, "=", newValue);
              if ($2(this).hasClass("probuilder-slider")) {
                $2(this).next(".probuilder-slider-value").text(newValue + (control.unit || ""));
              }
              if (key.startsWith("_motion_")) {
                console.log("\u{1F3AC} Motion control changed! Applying animation...");
                self2.applyMotionStyles(element2);
              }
              self2.updateElementPreview(element2);
            });
          }
          $content.append($control);
        });
        console.log("Controls rendered in", activeTab, "tab:", controlsRendered);
        if (controlsRendered === 0) {
          $content.html(`
                    <div style="text-align: center; padding: 40px 20px; color: #a1a1aa;">
                        <i class="dashicons dashicons-info" style="font-size: 32px; margin-bottom: 15px; opacity: 0.3;"></i>
                        <h4 style="font-size: 14px; color: #71717a; margin: 0 0 8px 0;">No ${activeTab.charAt(0).toUpperCase() + activeTab.slice(1)} Settings</h4>
                        <p style="font-size: 12px; margin: 0;">This widget has no settings in this tab. Try other tabs.</p>
                    </div>
                `);
        }
        if (activeTab === "motion") {
          $content.find(".probuilder-preview-animation").off("click").on("click", function() {
            self2.previewAnimation(element2);
          });
        }
      },
      /**
       * Preview animation on canvas element - Replays the animation
       */
      previewAnimation: function(element2) {
        const animation = element2.settings._motion_animation || "fadeIn";
        const duration = element2.settings._motion_duration || 1e3;
        const easing = element2.settings._motion_easing || "ease-in-out";
        console.log("\u{1F3AC} Preview animation clicked for:", element2.id);
        console.log("Animation:", animation, "Duration:", duration);
        if (!animation || animation === "none") {
          alert("Please select an animation first!");
          return;
        }
        const $canvasElement = $2(`.probuilder-element[data-id="${element2.id}"]`);
        if ($canvasElement.length === 0) {
          console.error("\u274C Canvas element not found:", element2.id);
          return;
        }
        const $preview = $canvasElement.find(".probuilder-element-preview");
        if ($preview.length === 0) {
          console.error("\u274C Preview area not found");
          return;
        }
        console.log("\u2705 Element found, replaying animation...");
        $preview.css("animation", "none");
        void $preview[0].offsetWidth;
        $preview.css({
          "animation-name": animation,
          "animation-duration": duration + "ms",
          "animation-timing-function": easing,
          "animation-fill-mode": "both",
          "opacity": "1"
        });
        console.log("\u2705 Animation replayed!");
      },
      /**
       * Apply motion/animation styles to canvas element
       */
      applyMotionStyles: function(element2, $element) {
        const animation = element2.settings._motion_animation || "none";
        const duration = element2.settings._motion_duration || 1e3;
        const delay = element2.settings._motion_delay || 0;
        const easing = element2.settings._motion_easing || "ease-in-out";
        const hoverAnimation = element2.settings._motion_hover || "none";
        const repeat = element2.settings._motion_repeat || "1";
        console.log("\u{1F3AC} applyMotionStyles called for element:", element2.id);
        console.log("Animation settings:", { animation, duration, delay, easing, hoverAnimation, repeat });
        if (!$element || $element.length === 0) {
          $element = $2(`.probuilder-element[data-id="${element2.id}"]`);
          console.log("Found element:", $element.length);
        }
        if ($element.length === 0) {
          console.error("\u274C Element not found in DOM:", element2.id);
          return;
        }
        const $preview = $element.find(".probuilder-element-preview");
        if ($preview.length === 0) {
          console.error("\u274C Preview area not found for:", element2.id);
          return;
        }
        $preview.removeClass(function(index, className) {
          return (className.match(/(^|\s)probuilder-animate-\S+/g) || []).join(" ");
        });
        $preview.css("animation", "none");
        void $preview[0].offsetWidth;
        if (animation && animation !== "none") {
          console.log("\u2705 Applying animation:", animation);
          $preview.css({
            "animation-name": animation,
            "animation-duration": duration + "ms",
            "animation-delay": delay + "ms",
            "animation-timing-function": easing,
            "animation-fill-mode": "both",
            "animation-iteration-count": repeat === "infinite" ? "infinite" : repeat
          });
          $preview.addClass("probuilder-animate-" + animation);
          console.log("\u2705 Animation applied to preview area");
        } else {
          console.log("\u2139\uFE0F Animation set to none, clearing...");
          $preview.css("animation", "none");
        }
        if (hoverAnimation && hoverAnimation !== "none") {
          $preview.addClass("probuilder-hover-" + hoverAnimation);
          const hoverStyle = `
                    .probuilder-hover-${hoverAnimation}:hover {
                        animation: ${hoverAnimation} 0.5s ease-in-out !important;
                    }
                `;
          if (!$2("#probuilder-hover-styles").length) {
            $2("head").append('<style id="probuilder-hover-styles"></style>');
          }
          const $styleTag = $2("#probuilder-hover-styles");
          if ($styleTag.text().indexOf(hoverAnimation) === -1) {
            $styleTag.append(hoverStyle);
          }
          console.log("\u2705 Hover animation added:", hoverAnimation);
        }
        console.log("\u2705 Motion styles applied successfully");
      },
      /**
       * Render control
       */
      renderControl: function(key, control, value, element2) {
        var _a, _b, _c, _d, _e, _f;
        let html = `<div class="probuilder-control">
                <label>${control.label}</label>`;
        switch (control.type) {
          case "text":
            html += `<input type="text" class="probuilder-input" data-setting="${key}" value="${value || ""}" placeholder="${control.placeholder || ""}">`;
            break;
          case "textarea":
            html += `<textarea class="probuilder-textarea" data-setting="${key}" rows="5" placeholder="${control.placeholder || ""}">${value || ""}</textarea>`;
            break;
          case "editor":
          case "wysiwyg":
            const editorId = "probuilder-editor-" + key + "-" + element2.id;
            html += `<div class="probuilder-wysiwyg-wrapper" style="margin-top: 8px;">
                        <textarea id="${editorId}" class="probuilder-editor-textarea" data-setting="${key}" style="width: 100%; min-height: 200px; display: none;">${value || ""}</textarea>
                        
                        <!-- MS Word/Excel Style Ribbon -->
                        <div class="probuilder-editor-ribbon" style="
                            background: #f8f9fa;
                            border: 1px solid #d1d5db;
                            border-bottom: none;
                            border-radius: 4px 4px 0 0;
                        ">
                            <!-- Font Group -->
                            <div style="
                                padding: 6px 12px;
                                display: flex;
                                gap: 8px;
                                align-items: center;
                                flex-wrap: wrap;
                                border-bottom: 1px solid #e5e7eb;
                            ">
                                <div style="display: flex; gap: 4px; align-items: center;">
                                    <select class="probuilder-editor-format" style="
                                        background: #fff;
                                        border: 1px solid #d1d5db;
                                        padding: 4px 8px;
                                        font-size: 12px;
                                        border-radius: 3px;
                                        cursor: pointer;
                                        min-width: 110px;
                                    ">
                                        <option value="p">\xB6 Normal</option>
                                        <option value="h1">Heading 1</option>
                                        <option value="h2">Heading 2</option>
                                        <option value="h3">Heading 3</option>
                                        <option value="h4">Heading 4</option>
                                        <option value="h5">Heading 5</option>
                                        <option value="h6">Heading 6</option>
                                        <option value="blockquote">Quote</option>
                                        <option value="pre">Code</option>
                                    </select>
                                    
                                    <select class="probuilder-editor-font" style="
                                        background: #fff;
                                        border: 1px solid #d1d5db;
                                        padding: 4px 8px;
                                        font-size: 12px;
                                        border-radius: 3px;
                                        cursor: pointer;
                                        min-width: 130px;
                                    ">
                                        <option value="inherit">Calibri</option>
                                        <option value="Arial, sans-serif">Arial</option>
                                        <option value="'Times New Roman', serif">Times New Roman</option>
                                        <option value="'Georgia', serif">Georgia</option>
                                        <option value="'Courier New', monospace">Courier New</option>
                                        <option value="'Verdana', sans-serif">Verdana</option>
                                        <option value="'Trebuchet MS', sans-serif">Trebuchet MS</option>
                                        <option value="'Comic Sans MS', cursive">Comic Sans MS</option>
                                        <option value="'Roboto', sans-serif">Roboto</option>
                                        <option value="'Open Sans', sans-serif">Open Sans</option>
                                        <option value="'Lato', sans-serif">Lato</option>
                                        <option value="'Montserrat', sans-serif">Montserrat</option>
                                        <option value="'Poppins', sans-serif">Poppins</option>
                                    </select>
                                    
                                    <select class="probuilder-editor-size" style="
                                        background: #fff;
                                        border: 1px solid #d1d5db;
                                        padding: 4px 8px;
                                        font-size: 12px;
                                        border-radius: 3px;
                                        cursor: pointer;
                                        width: 65px;
                                    ">
                                        <option value="8px">8</option>
                                        <option value="9px">9</option>
                                        <option value="10px">10</option>
                                        <option value="11px">11</option>
                                        <option value="12px">12</option>
                                        <option value="14px">14</option>
                                        <option value="16px" selected>16</option>
                                        <option value="18px">18</option>
                                        <option value="20px">20</option>
                                        <option value="24px">24</option>
                                        <option value="28px">28</option>
                                        <option value="32px">32</option>
                                        <option value="36px">36</option>
                                        <option value="48px">48</option>
                                    </select>
                                </div>
                                
                                <div style="width: 1px; height: 24px; background: #d1d5db; margin: 0 4px;"></div>
                                
                                <!-- Text Style Buttons -->
                                <div style="display: flex; gap: 2px; background: #fff; border: 1px solid #d1d5db; border-radius: 3px; padding: 2px;">
                                    <button type="button" class="probuilder-editor-btn" data-cmd="bold" title="Bold (Ctrl+B)" style="
                                        background: transparent;
                                        border: none;
                                        padding: 4px 10px;
                                        font-size: 13px;
                                        font-weight: 700;
                                        cursor: pointer;
                                        border-radius: 2px;
                                    ">B</button>
                                    <button type="button" class="probuilder-editor-btn" data-cmd="italic" title="Italic (Ctrl+I)" style="
                                        background: transparent;
                                        border: none;
                                        padding: 4px 10px;
                                        font-size: 13px;
                                        font-style: italic;
                                        cursor: pointer;
                                        border-radius: 2px;
                                    ">I</button>
                                    <button type="button" class="probuilder-editor-btn" data-cmd="underline" title="Underline (Ctrl+U)" style="
                                        background: transparent;
                                        border: none;
                                        padding: 4px 10px;
                                        font-size: 13px;
                                        text-decoration: underline;
                                        cursor: pointer;
                                        border-radius: 2px;
                                    ">U</button>
                                    <button type="button" class="probuilder-editor-btn" data-cmd="strikethrough" title="Strikethrough" style="
                                        background: transparent;
                                        border: none;
                                        padding: 4px 10px;
                                        font-size: 13px;
                                        text-decoration: line-through;
                                        cursor: pointer;
                                        border-radius: 2px;
                                    ">abc</button>
                                </div>
                                
                                <div style="width: 1px; height: 24px; background: #d1d5db; margin: 0 4px;"></div>
                                
                                <!-- Color Pickers - MS Word Style -->
                                <div style="display: flex; gap: 4px; align-items: center;">
                                    <div style="position: relative;">
                                        <button type="button" class="probuilder-editor-color-btn" data-type="foreColor" style="
                                            background: #fff;
                                            border: 1px solid #d1d5db;
                                            width: 32px;
                                            height: 24px;
                                            cursor: pointer;
                                            border-radius: 3px;
                                            position: relative;
                                            padding: 0;
                                        ">
                                            <span style="
                                                font-size: 14px;
                                                font-weight: 700;
                                                color: #000;
                                                line-height: 22px;
                                            ">A</span>
                                            <div class="probuilder-color-indicator" style="
                                                position: absolute;
                                                bottom: 2px;
                                                left: 2px;
                                                right: 2px;
                                                height: 3px;
                                                background: #000;
                                                border-radius: 1px;
                                            "></div>
                                        </button>
                                        <input type="color" class="probuilder-editor-color-input" data-type="foreColor" style="
                                            position: absolute;
                                            opacity: 0;
                                            width: 100%;
                                            height: 100%;
                                            cursor: pointer;
                                            top: 0;
                                            left: 0;
                                        ">
                                    </div>
                                    
                                    <div style="position: relative;">
                                        <button type="button" class="probuilder-editor-color-btn" data-type="backColor" style="
                                            background: #fff;
                                            border: 1px solid #d1d5db;
                                            width: 32px;
                                            height: 24px;
                                            cursor: pointer;
                                            border-radius: 3px;
                                            position: relative;
                                            padding: 0;
                                        ">
                                            <span style="
                                                font-size: 10px;
                                                line-height: 22px;
                                            ">A</span>
                                            <div class="probuilder-bg-indicator" style="
                                                position: absolute;
                                                bottom: 2px;
                                                left: 2px;
                                                right: 2px;
                                                height: 6px;
                                                background: #ffff00;
                                                border-radius: 1px;
                                            "></div>
                                        </button>
                                        <input type="color" class="probuilder-editor-color-input" data-type="backColor" value="#ffff00" style="
                                            position: absolute;
                                            opacity: 0;
                                            width: 100%;
                                            height: 100%;
                                            cursor: pointer;
                                            top: 0;
                                            left: 0;
                                        ">
                                    </div>
                                </div>
                                
                                <div style="width: 1px; height: 24px; background: #d1d5db; margin: 0 4px;"></div>
                                
                                <!-- Simplified Alignment -->
                                <div style="display: flex; gap: 2px; background: #fff; border: 1px solid #d1d5db; border-radius: 3px; padding: 2px;">
                                    <button type="button" class="probuilder-editor-btn" data-cmd="justifyLeft" title="Align Left" style="
                                        background: transparent;
                                        border: none;
                                        padding: 4px 8px;
                                        cursor: pointer;
                                        border-radius: 2px;
                                        font-size: 14px;
                                    ">\u2630</button>
                                    <button type="button" class="probuilder-editor-btn" data-cmd="justifyCenter" title="Center" style="
                                        background: transparent;
                                        border: none;
                                        padding: 4px 8px;
                                        cursor: pointer;
                                        border-radius: 2px;
                                        font-size: 14px;
                                    ">\u2637</button>
                                    <button type="button" class="probuilder-editor-btn" data-cmd="justifyRight" title="Align Right" style="
                                        background: transparent;
                                        border: none;
                                        padding: 4px 8px;
                                        cursor: pointer;
                                        border-radius: 2px;
                                        font-size: 14px;
                                    ">\u2631</button>
                                </div>
                                
                                <div style="width: 1px; height: 24px; background: #d1d5db; margin: 0 4px;"></div>
                                
                                <!-- Lists & Indent -->
                                <div style="display: flex; gap: 2px;">
                                    <button type="button" class="probuilder-editor-btn" data-cmd="insertUnorderedList" title="Bullet List" style="
                                        background: #fff;
                                        border: 1px solid #d1d5db;
                                        padding: 4px 10px;
                                        font-size: 14px;
                                        cursor: pointer;
                                        border-radius: 3px;
                                    ">\u2022</button>
                                    <button type="button" class="probuilder-editor-btn" data-cmd="insertOrderedList" title="Numbered List" style="
                                        background: #fff;
                                        border: 1px solid #d1d5db;
                                        padding: 4px 10px;
                                        font-size: 14px;
                                        cursor: pointer;
                                        border-radius: 3px;
                                    ">1.</button>
                                    <button type="button" class="probuilder-editor-btn" data-cmd="outdent" title="Decrease Indent" style="
                                        background: #fff;
                                        border: 1px solid #d1d5db;
                                        padding: 4px 10px;
                                        font-size: 12px;
                                        cursor: pointer;
                                        border-radius: 3px;
                                    ">\u2190</button>
                                    <button type="button" class="probuilder-editor-btn" data-cmd="indent" title="Increase Indent" style="
                                        background: #fff;
                                        border: 1px solid #d1d5db;
                                        padding: 4px 10px;
                                        font-size: 12px;
                                        cursor: pointer;
                                        border-radius: 3px;
                                    ">\u2192</button>
                                </div>
                                
                                <div style="width: 1px; height: 24px; background: #d1d5db; margin: 0 4px;"></div>
                                
                                <!-- Link & Image -->
                                <button type="button" class="probuilder-editor-btn" data-cmd="createLink" title="Insert Link" style="
                                    background: #fff;
                                    border: 1px solid #d1d5db;
                                    padding: 4px 10px;
                                    font-size: 12px;
                                    cursor: pointer;
                                    border-radius: 3px;
                                ">\u{1F517}</button>
                                <button type="button" class="probuilder-editor-btn" data-cmd="insertImage" title="Insert Image" style="
                                    background: #fff;
                                    border: 1px solid #d1d5db;
                                    padding: 4px 10px;
                                    font-size: 12px;
                                    cursor: pointer;
                                    border-radius: 3px;
                                ">\u{1F5BC}\uFE0F</button>
                                
                                <select class="probuilder-editor-more" style="
                                    background: #fff;
                                    border: 1px solid #d1d5db;
                                    padding: 4px 8px;
                                    font-size: 12px;
                                    border-radius: 3px;
                                    cursor: pointer;
                                    min-width: 70px;
                                    margin-left: 4px;
                                ">
                                    <option value="">More \u25BC</option>
                                    <option value="undo">\u21B6 Undo</option>
                                    <option value="redo">\u21B7 Redo</option>
                                    <option value="unlink">\u{1F517}\u2717 Unlink</option>
                                    <option value="hr">\u2500 Horizontal Line</option>
                                    <option value="table">\u25A6 Insert Table</option>
                                    <option value="subscript">X\u2082 Subscript</option>
                                    <option value="superscript">X\xB2 Superscript</option>
                                    <option value="uppercase">ABC UPPERCASE</option>
                                    <option value="lowercase">abc lowercase</option>
                                    <option value="removeFormat">\u2717 Clear Formatting</option>
                                    <option value="selectAll">\u229E Select All</option>
                                </select>
                            </div>
                        </div>
                        <div id="${editorId}-editor" contenteditable="true" class="probuilder-editor-content" style="
                            min-height: 200px;
                            padding: 12px;
                            border: 1px solid #dee2e6;
                            background: #fff;
                            border-radius: 0 0 4px 4px;
                            outline: none;
                            font-family: inherit;
                            font-size: 14px;
                            line-height: 1.6;
                        ">${value || ""}</div>
                        <style>
                            /* MS Word/Excel Style Button Hover Effects */
                            .probuilder-editor-btn:hover {
                                background: #e3f2fd !important;
                            }
                            .probuilder-editor-btn:active {
                                background: #bbdefb !important;
                            }
                            
                            /* Color button hover */
                            .probuilder-editor-color-btn:hover {
                                background: #f5f5f5 !important;
                                border-color: #1976d2 !important;
                            }
                            .probuilder-editor-color-btn:active {
                                background: #e0e0e0 !important;
                            }
                            
                            /* Dropdown hover */
                            .probuilder-editor-format:hover,
                            .probuilder-editor-font:hover,
                            .probuilder-editor-size:hover,
                            .probuilder-editor-more:hover {
                                border-color: #1976d2 !important;
                            }
                            
                            /* Editor focus */
                            #${editorId}-editor:focus {
                                border-color: #1976d2;
                                box-shadow: 0 0 0 2px rgba(25,118,210,0.2);
                            }
                            
                            /* Grouped button container */
                            .probuilder-editor-ribbon > div > div[style*="background: #fff"] > button:hover {
                                background: #f5f5f5 !important;
                            }
                            
                            .probuilder-editor-ribbon > div > div[style*="background: #fff"] > button:active {
                                background: #e0e0e0 !important;
                            }
                            
                            #${editorId}-editor blockquote {
                                border-left: 4px solid #92003b;
                                margin: 16px 0;
                                padding: 12px 20px;
                                background: #f9f9f9;
                                font-style: italic;
                                color: #555;
                            }
                            #${editorId}-editor pre {
                                background: #2d2d2d;
                                color: #f8f8f2;
                                padding: 16px;
                                border-radius: 4px;
                                overflow-x: auto;
                                font-family: 'Courier New', monospace;
                                font-size: 13px;
                                line-height: 1.5;
                            }
                            #${editorId}-editor code {
                                background: #f4f4f4;
                                padding: 2px 6px;
                                border-radius: 3px;
                                font-family: 'Courier New', monospace;
                                font-size: 13px;
                                color: #c7254e;
                            }
                            #${editorId}-editor pre code {
                                background: transparent;
                                padding: 0;
                                color: #f8f8f2;
                            }
                            #${editorId}-editor h1, 
                            #${editorId}-editor h2, 
                            #${editorId}-editor h3, 
                            #${editorId}-editor h4, 
                            #${editorId}-editor h5, 
                            #${editorId}-editor h6 {
                                margin-top: 16px;
                                margin-bottom: 8px;
                                font-weight: 600;
                                line-height: 1.3;
                            }
                            #${editorId}-editor h1 { font-size: 32px; }
                            #${editorId}-editor h2 { font-size: 28px; }
                            #${editorId}-editor h3 { font-size: 24px; }
                            #${editorId}-editor h4 { font-size: 20px; }
                            #${editorId}-editor h5 { font-size: 18px; }
                            #${editorId}-editor h6 { font-size: 16px; }
                            #${editorId}-editor ul, 
                            #${editorId}-editor ol {
                                margin: 12px 0;
                                padding-left: 30px;
                            }
                            #${editorId}-editor li {
                                margin: 6px 0;
                            }
                            #${editorId}-editor a {
                                color: #92003b;
                                text-decoration: underline;
                            }
                            #${editorId}-editor img {
                                max-width: 100%;
                                height: auto;
                                border-radius: 4px;
                                margin: 8px 0;
                            }
                            #${editorId}-editor hr {
                                border: none;
                                border-top: 2px solid #ddd;
                                margin: 20px 0;
                            }
                        </style>
                    </div>`;
            setTimeout(() => {
              const $editor = $2(`#${editorId}-editor`);
              const $textarea = $2(`#${editorId}`);
              const syncContent = () => {
                $textarea.val($editor.html());
                element2.settings[key] = $editor.html();
                self.updateElementPreview(element2);
                self.saveData();
              };
              $editor.closest(".probuilder-wysiwyg-wrapper").find(".probuilder-editor-btn").on("click", function(e) {
                e.preventDefault();
                const cmd = $2(this).data("cmd");
                const value2 = $2(this).data("value") || null;
                if (cmd === "createLink") {
                  const url = prompt("Enter URL:", "https://");
                  if (url) {
                    document.execCommand(cmd, false, url);
                  }
                } else if (cmd === "insertImage") {
                  const url = prompt("Enter image URL:", "https://");
                  if (url) {
                    document.execCommand(cmd, false, url);
                  }
                } else {
                  document.execCommand(cmd, false, value2);
                }
                $editor.focus();
                syncContent();
              });
              $editor.closest(".probuilder-wysiwyg-wrapper").find(".probuilder-editor-format").on("change", function() {
                const format = $2(this).val();
                document.execCommand("formatBlock", false, format);
                $editor.focus();
                syncContent();
              });
              $editor.closest(".probuilder-wysiwyg-wrapper").find(".probuilder-editor-font").on("change", function() {
                const font = $2(this).val();
                document.execCommand("fontName", false, font);
                $editor.focus();
                syncContent();
              });
              $editor.closest(".probuilder-wysiwyg-wrapper").find(".probuilder-editor-size").on("change", function() {
                const size = $2(this).val();
                if (!size) return;
                if (window.getSelection && window.getSelection().rangeCount > 0) {
                  const selection = window.getSelection();
                  const range = selection.getRangeAt(0);
                  if (!range.collapsed) {
                    const span = document.createElement("span");
                    span.style.fontSize = size;
                    try {
                      range.surroundContents(span);
                    } catch (e) {
                      document.execCommand("fontSize", false, "3");
                      $editor.find('font[size="3"]').css("font-size", size);
                    }
                  } else {
                    document.execCommand("fontSize", false, "3");
                    $editor.find('font[size="3"]').css("font-size", size);
                  }
                }
                $editor.focus();
                syncContent();
              });
              $editor.closest(".probuilder-wysiwyg-wrapper").find(".probuilder-editor-color-input").on("change", function() {
                const color = $2(this).val();
                const type = $2(this).data("type");
                document.execCommand(type, false, color);
                if (type === "foreColor") {
                  $2(this).siblings(".probuilder-color-indicator").css("background", color);
                } else {
                  $2(this).siblings(".probuilder-bg-indicator").css("background", color);
                }
                $editor.focus();
                syncContent();
              });
              $editor.closest(".probuilder-wysiwyg-wrapper").find(".probuilder-editor-color-btn").on("click", function() {
                $2(this).siblings(".probuilder-editor-color-input").click();
              });
              $editor.closest(".probuilder-wysiwyg-wrapper").find(".probuilder-editor-more").on("change", function() {
                const action = $2(this).val();
                if (!action) return;
                switch (action) {
                  case "undo":
                    document.execCommand("undo");
                    break;
                  case "redo":
                    document.execCommand("redo");
                    break;
                  case "unlink":
                    document.execCommand("unlink");
                    break;
                  case "hr":
                    document.execCommand("insertHorizontalRule");
                    break;
                  case "table":
                    const table = '<table border="1" style="border-collapse: collapse; width: 100%;"><tr><td style="padding: 8px;">Cell 1</td><td style="padding: 8px;">Cell 2</td></tr><tr><td style="padding: 8px;">Cell 3</td><td style="padding: 8px;">Cell 4</td></tr></table>';
                    document.execCommand("insertHTML", false, table);
                    break;
                  case "subscript":
                    document.execCommand("subscript");
                    break;
                  case "superscript":
                    document.execCommand("superscript");
                    break;
                  case "uppercase":
                    const upperSel = window.getSelection();
                    if (upperSel.rangeCount > 0) {
                      const upperRange = upperSel.getRangeAt(0);
                      const upperText = upperRange.toString().toUpperCase();
                      upperRange.deleteContents();
                      upperRange.insertNode(document.createTextNode(upperText));
                    }
                    break;
                  case "lowercase":
                    const lowerSel = window.getSelection();
                    if (lowerSel.rangeCount > 0) {
                      const lowerRange = lowerSel.getRangeAt(0);
                      const lowerText = lowerRange.toString().toLowerCase();
                      lowerRange.deleteContents();
                      lowerRange.insertNode(document.createTextNode(lowerText));
                    }
                    break;
                  case "removeFormat":
                    document.execCommand("removeFormat");
                    break;
                  case "selectAll":
                    document.execCommand("selectAll");
                    break;
                }
                $2(this).val("");
                $editor.focus();
                syncContent();
              });
              $editor.on("keydown", function(e) {
                if (e.ctrlKey && e.key === "b") {
                  e.preventDefault();
                  document.execCommand("bold");
                  syncContent();
                }
                if (e.ctrlKey && e.key === "i") {
                  e.preventDefault();
                  document.execCommand("italic");
                  syncContent();
                }
                if (e.ctrlKey && e.key === "u") {
                  e.preventDefault();
                  document.execCommand("underline");
                  syncContent();
                }
                if (e.ctrlKey && e.key === "z" && !e.shiftKey) {
                  e.preventDefault();
                  document.execCommand("undo");
                  syncContent();
                }
                if (e.ctrlKey && e.key === "y" || e.ctrlKey && e.shiftKey && e.key === "z") {
                  e.preventDefault();
                  document.execCommand("redo");
                  syncContent();
                }
              });
              $editor.on("input paste keyup", function() {
                syncContent();
              });
            }, 100);
            break;
          case "select":
            if (key === "background_gradient_angle") {
              const angleValue2 = parseFloat(value) || 135;
              const circleSize = 120;
              const center = circleSize / 2;
              const radius = 40;
              const angleRad2 = (angleValue2 - 90) * Math.PI / 180;
              const dotX = center + radius * Math.cos(angleRad2);
              const dotY = center + radius * Math.sin(angleRad2);
              html += `
                            <div class="probuilder-gradient-angle-wrapper" style="position: relative; width: ${circleSize}px; height: ${circleSize + 30}px; margin: 10px auto;">
                                <svg width="${circleSize}" height="${circleSize}" style="position: absolute; top: 0; left: 0;">
                                    <circle cx="${center}" cy="${center}" r="${radius}" fill="none" stroke="#d1d5db" stroke-width="2"/>
                                    <line x1="${center}" y1="${center}" x2="${dotX}" y2="${dotY}" stroke="#92003b" stroke-width="2"/>
                                    <circle cx="${dotX}" cy="${dotY}" r="8" fill="#92003b" cursor="move"/>
                                </svg>
                                <input type="range" class="probuilder-gradient-angle-slider" data-setting="${key}" min="0" max="360" step="1" value="${angleValue2}" style="
                                    position: absolute;
                                    top: 0;
                                    left: 0;
                                    width: ${circleSize}px;
                                    height: ${circleSize}px;
                                    opacity: 0;
                                    cursor: pointer;
                                    z-index: 10;
                                " title="Drag to rotate gradient angle">
                                <div style="position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); font-size: 11px; color: #71717a; white-space: nowrap;">
                                    <span class="probuilder-angle-value">${Math.round(angleValue2)}\xB0</span>
                                </div>
                            </div>
                        `;
            } else {
              html += `<select class="probuilder-select" data-setting="${key}">`;
              Object.keys(control.options).forEach((optKey) => {
                html += `<option value="${optKey}" ${value === optKey ? "selected" : ""}>${control.options[optKey]}</option>`;
              });
              html += `</select>`;
            }
            break;
          case "grid_preset":
            const gridPatterns = this.getGridPatterns();
            html += `<div class="probuilder-grid-presets" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 12px;">`;
            gridPatterns.forEach((pattern) => {
              const isSelected = value === pattern.id || !value && pattern.id === "pattern-1";
              html += `
                            <div class="probuilder-grid-preset-item ${isSelected ? "selected" : ""}" 
                                 data-setting="${key}" 
                                 data-pattern="${pattern.id}"
                                 style="
                                     cursor: pointer;
                                     padding: 12px;
                                     border: 2px solid ${isSelected ? "#007cba" : "#ddd"};
                                     border-radius: 8px;
                                     background: ${isSelected ? "rgba(0,124,186,0.05)" : "#fff"};
                                     transition: all 0.2s;
                                 "
                                 title="${pattern.name}">
                                <div style="
                                    width: 100%;
                                    height: 80px;
                                    background: #f0f0f1;
                                    border-radius: 4px;
                                    padding: 4px;
                                    margin-bottom: 8px;
                                ">
                                    ${pattern.svg}
                                </div>
                                <div style="
                                    text-align: center;
                                    font-size: 10px;
                                    color: ${isSelected ? "#007cba" : "#666"};
                                    font-weight: ${isSelected ? "600" : "400"};
                                ">${pattern.name}</div>
                            </div>
                        `;
            });
            html += `</div>`;
            break;
          case "color":
            html += `<div style="display: flex; gap: 10px; align-items: center;">
                        <input type="color" class="probuilder-color" data-setting="${key}" value="${value || "#000000"}" style="width: 60px;">
                        <input type="text" class="probuilder-input" value="${value || "#000000"}" style="flex: 1; font-family: monospace;" readonly>
                    </div>`;
            break;
          case "slider":
            const unit = control.unit || "px";
            html += `<div style="display: flex; align-items: center; gap: 8px;">
                        <input type="range" class="probuilder-slider" data-setting="${key}" min="${((_b = (_a = control.range) == null ? void 0 : _a.px) == null ? void 0 : _b.min) || 0}" max="${((_d = (_c = control.range) == null ? void 0 : _c.px) == null ? void 0 : _d.max) || 100}" step="${((_f = (_e = control.range) == null ? void 0 : _e.px) == null ? void 0 : _f.step) || 1}" value="${value || control.default}">
                        <span class="probuilder-slider-value">${value || control.default}${unit}</span>
                    </div>`;
            break;
          case "dimensions":
            const dims = value || control.default || { top: 0, right: 0, bottom: 0, left: 0 };
            html += `<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;">
                        <div>
                            <label style="font-size: 9px; color: #71717a; display: block; margin-bottom: 4px;">Top</label>
                            <input type="number" class="probuilder-input" data-setting="${key}" data-dimension="top" value="${dims.top || 0}" placeholder="0" style="padding: 6px 8px; font-size: 11px;">
                        </div>
                        <div>
                            <label style="font-size: 9px; color: #71717a; display: block; margin-bottom: 4px;">Right</label>
                            <input type="number" class="probuilder-input" data-setting="${key}" data-dimension="right" value="${dims.right || 0}" placeholder="0" style="padding: 6px 8px; font-size: 11px;">
                        </div>
                        <div>
                            <label style="font-size: 9px; color: #71717a; display: block; margin-bottom: 4px;">Bottom</label>
                            <input type="number" class="probuilder-input" data-setting="${key}" data-dimension="bottom" value="${dims.bottom || 0}" placeholder="0" style="padding: 6px 8px; font-size: 11px;">
                        </div>
                        <div>
                            <label style="font-size: 9px; color: #71717a; display: block; margin-bottom: 4px;">Left</label>
                            <input type="number" class="probuilder-input" data-setting="${key}" data-dimension="left" value="${dims.left || 0}" placeholder="0" style="padding: 6px 8px; font-size: 11px;">
                        </div>
                    </div>`;
            break;
          case "border":
            const border = value || control.default || { width: 1, style: "solid", color: "#000000" };
            html += `<div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <input type="number" class="probuilder-input" data-setting="${key}" data-border="width" value="${border.width || 1}" placeholder="Width" style="width: 70px;">
                        <select class="probuilder-select" data-setting="${key}" data-border="style" style="flex: 1;">
                            <option value="solid" ${border.style === "solid" ? "selected" : ""}>Solid</option>
                            <option value="dashed" ${border.style === "dashed" ? "selected" : ""}>Dashed</option>
                            <option value="dotted" ${border.style === "dotted" ? "selected" : ""}>Dotted</option>
                            <option value="double" ${border.style === "double" ? "selected" : ""}>Double</option>
                        </select>
                        <input type="color" class="probuilder-color" data-setting="${key}" data-border="color" value="${border.color || "#000000"}" style="width: 60px;">
                    </div>`;
            break;
          case "box-shadow":
            const shadow = value || control.default || { x: 0, y: 4, blur: 10, color: "rgba(0,0,0,0.1)" };
            html += `<div style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">
                        <input type="number" class="probuilder-input" data-setting="${key}" data-shadow="x" value="${shadow.x || 0}" placeholder="X" style="width: 60px; padding: 6px 8px; font-size: 11px;">
                        <input type="number" class="probuilder-input" data-setting="${key}" data-shadow="y" value="${shadow.y || 0}" placeholder="Y" style="width: 60px; padding: 6px 8px; font-size: 11px;">
                        <input type="number" class="probuilder-input" data-setting="${key}" data-shadow="blur" value="${shadow.blur || 10}" placeholder="Blur" style="width: 60px; padding: 6px 8px; font-size: 11px;">
                        <input type="color" class="probuilder-color" data-setting="${key}" data-shadow="color" value="${shadow.color || "#000000"}" style="width: 40px; height: 32px; padding: 0; border: 1px solid #d1d5db; border-radius: 4px; cursor: pointer;">
                    </div>`;
            break;
          case "switcher":
            const switcherValue = value || control.default || "no";
            const isOn = switcherValue === "yes" || switcherValue === true || switcherValue === "on";
            html += `<label class="probuilder-switcher" style="
                        display: inline-flex;
                        align-items: center;
                        cursor: pointer;
                        user-select: none;
                    ">
                        <input type="checkbox" class="probuilder-switcher-input" data-setting="${key}" ${isOn ? "checked" : ""} style="display: none;">
                        <span class="probuilder-switcher-track" style="
                            position: relative;
                            width: 44px;
                            height: 24px;
                            background: ${isOn ? "#92003b" : "#cbd5e1"};
                            border-radius: 12px;
                            transition: background 0.3s;
                            display: inline-block;
                        ">
                            <span class="probuilder-switcher-thumb" style="
                                position: absolute;
                                top: 2px;
                                left: ${isOn ? "22px" : "2px"};
                                width: 20px;
                                height: 20px;
                                background: #ffffff;
                                border-radius: 10px;
                                transition: left 0.3s;
                                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                            "></span>
                        </span>
                    </label>`;
            break;
          case "typography":
            const typo = value || control.default || { family: "inherit", size: 16, weight: 400, lineHeight: 1.5 };
            html += `<div style="display: flex; flex-direction: column; gap: 12px;">
                        <select class="probuilder-select" data-setting="${key}" data-typo="family">
                            <option value="inherit" ${typo.family === "inherit" ? "selected" : ""}>Default</option>
                            <option value="Arial, sans-serif" ${typo.family === "Arial, sans-serif" ? "selected" : ""}>Arial</option>
                            <option value="'Georgia', serif" ${typo.family === "Georgia" ? "selected" : ""}>Georgia</option>
                            <option value="'Times New Roman', serif" ${typo.family === "Times New Roman" ? "selected" : ""}>Times New Roman</option>
                            <option value="'Roboto', sans-serif" ${typo.family === "Roboto" ? "selected" : ""}>Roboto</option>
                        </select>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                            <input type="number" class="probuilder-input" data-setting="${key}" data-typo="size" value="${typo.size || 16}" placeholder="Size">
                            <select class="probuilder-select" data-setting="${key}" data-typo="weight">
                                <option value="300" ${typo.weight == 300 ? "selected" : ""}>Light</option>
                                <option value="400" ${typo.weight == 400 ? "selected" : ""}>Normal</option>
                                <option value="600" ${typo.weight == 600 ? "selected" : ""}>Semi Bold</option>
                                <option value="700" ${typo.weight == 700 ? "selected" : ""}>Bold</option>
                            </select>
                        </div>
                        <input type="number" class="probuilder-input" data-setting="${key}" data-typo="lineHeight" value="${typo.lineHeight || 1.5}" step="0.1" placeholder="Line Height">
                    </div>`;
            break;
          case "url":
            html += `<input type="url" class="probuilder-input" data-setting="${key}" value="${value || ""}" placeholder="${control.placeholder || "https://"}">`;
            break;
          case "number":
            html += `<input type="number" class="probuilder-input" data-setting="${key}" value="${value || ""}" placeholder="${control.placeholder || ""}">`;
            break;
          case "angle":
            const angleValue = value || control.default || 0;
            const angleId = "angle-" + key + "-" + Date.now();
            const angleRad = (angleValue - 90) * Math.PI / 180;
            const handleX = 50 + 45 * Math.cos(angleRad);
            const handleY = 50 + 45 * Math.sin(angleRad);
            html += `
                        <div class="probuilder-angle-picker" style="display: flex; align-items: center; gap: 15px;">
                            <div class="angle-circle-wrapper" id="${angleId}-wrapper" 
                                 style="position: relative; 
                                        width: 100px; 
                                        height: 100px; 
                                        cursor: pointer;
                                        user-select: none;">
                                <svg width="100" height="100" viewBox="0 0 100 100" style="display: block;">
                                    <!-- Background circle -->
                                    <circle cx="50" cy="50" r="45" fill="white" stroke="#d1d5db" stroke-width="2"/>
                                    
                                    <!-- Angle indicator line (from center to edge) -->
                                    <line x1="50" y1="50" x2="${handleX}" y2="${handleY}" 
                                          stroke="#92003b" stroke-width="2" 
                                          id="${angleId}-line"
                                          style="transition: all 0.1s ease;"/>
                                    
                                    <!-- Gradient arc (shows angle) -->
                                    <circle cx="50" cy="50" r="45" fill="none" stroke="#92003b" stroke-width="4" 
                                            stroke-dasharray="${angleValue / 360 * 283} 283" 
                                            transform="rotate(-90 50 50)"
                                            id="${angleId}-arc"
                                            style="transition: stroke-dasharray 0.2s ease; opacity: 0.3;"/>
                                    
                                    <!-- Center dot -->
                                    <circle cx="50" cy="50" r="4" fill="#92003b"/>
                                </svg>
                                
                                <!-- Draggable handle (BIGGER and more visible) -->
                                <div class="angle-handle" id="${angleId}-handle" 
                                     style="position: absolute; 
                                            width: 24px; 
                                            height: 24px; 
                                            background: #92003b; 
                                            border: 4px solid white; 
                                            border-radius: 50%; 
                                            cursor: grab;
                                            box-shadow: 0 3px 12px rgba(146, 0, 59, 0.4);
                                            top: ${handleY}px; 
                                            left: ${handleX}px;
                                            transform: translate(-50%, -50%);
                                            transition: all 0.1s ease;
                                            z-index: 10;">
                                </div>
                                
                                <!-- Angle display in center -->
                                <div style="position: absolute; 
                                            top: 50%; 
                                            left: 50%; 
                                            transform: translate(-50%, -50%);
                                            font-size: 14px;
                                            font-weight: 700;
                                            color: #92003b;
                                            pointer-events: none;
                                            text-shadow: 0 0 3px white, 0 0 5px white;">
                                    ${Math.round(angleValue)}\xB0
                                </div>
                            </div>
                            <div style="flex: 1;">
                                <input type="number" class="probuilder-input probuilder-angle-input" 
                                       id="${angleId}-input"
                                       data-setting="${key}" 
                                       value="${angleValue}" 
                                       min="0" 
                                       max="360" 
                                       style="width: 100%; text-align: center; font-weight: 600; font-size: 16px; padding: 8px;">
                                <div style="margin-top: 10px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 5px;">
                                    <button type="button" class="angle-preset-btn" data-angle="0" style="padding: 6px; font-size: 11px; border: 2px solid #d1d5db; background: white; border-radius: 4px; cursor: pointer; font-weight: 600; transition: all 0.2s;">0\xB0</button>
                                    <button type="button" class="angle-preset-btn" data-angle="45" style="padding: 6px; font-size: 11px; border: 2px solid #d1d5db; background: white; border-radius: 4px; cursor: pointer; font-weight: 600; transition: all 0.2s;">45\xB0</button>
                                    <button type="button" class="angle-preset-btn" data-angle="90" style="padding: 6px; font-size: 11px; border: 2px solid #d1d5db; background: white; border-radius: 4px; cursor: pointer; font-weight: 600; transition: all 0.2s;">90\xB0</button>
                                    <button type="button" class="angle-preset-btn" data-angle="135" style="padding: 6px; font-size: 11px; border: 2px solid #d1d5db; background: white; border-radius: 4px; cursor: pointer; font-weight: 600; transition: all 0.2s;">135\xB0</button>
                                    <button type="button" class="angle-preset-btn" data-angle="180" style="padding: 6px; font-size: 11px; border: 2px solid #d1d5db; background: white; border-radius: 4px; cursor: pointer; font-weight: 600; transition: all 0.2s;">180\xB0</button>
                                    <button type="button" class="angle-preset-btn" data-angle="225" style="padding: 6px; font-size: 11px; border: 2px solid #d1d5db; background: white; border-radius: 4px; cursor: pointer; font-weight: 600; transition: all 0.2s;">225\xB0</button>
                                    <button type="button" class="angle-preset-btn" data-angle="270" style="padding: 6px; font-size: 11px; border: 2px solid #d1d5db; background: white; border-radius: 4px; cursor: pointer; font-weight: 600; transition: all 0.2s;">270\xB0</button>
                                    <button type="button" class="angle-preset-btn" data-angle="315" style="padding: 6px; font-size: 11px; border: 2px solid #d1d5db; background: white; border-radius: 4px; cursor: pointer; font-weight: 600; transition: all 0.2s;">315\xB0</button>
                                </div>
                            </div>
                        </div>
                    `;
            break;
          case "code":
            html += `<textarea class="probuilder-textarea probuilder-code-editor" data-setting="${key}" rows="8" placeholder="${control.placeholder || ""}" style="font-family: 'Courier New', monospace; font-size: 12px; background: #f8f9fa; border: 1px solid #d1d5db; padding: 10px;">${value || ""}</textarea>`;
            if (control.description) {
              html += `<small style="display: block; margin-top: 5px; color: #71717a; font-size: 11px;">${control.description}</small>`;
            }
            break;
          case "switcher":
            html += `<label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                        <input type="checkbox" data-setting="${key}" ${value === "yes" ? "checked" : ""} style="width: 20px; height: 20px; cursor: pointer;">
                        <span style="font-size: 12px; color: #71717a;">Enable</span>
                    </label>`;
            break;
          case "icon":
            html += `<input type="text" class="probuilder-input" data-setting="${key}" value="${value || ""}" placeholder="fa fa-star">
                    <small style="display: block; margin-top: 8px; color: #71717a; font-size: 11px;">Font Awesome icon class (e.g., fa fa-star)</small>`;
            break;
          case "media":
            html += `<button type="button" class="probuilder-media-btn probuilder-btn probuilder-btn-secondary" data-setting="${key}" style="width: 100%;">
                        <i class="dashicons dashicons-format-image"></i> Choose Image
                    </button>
                    <div class="probuilder-media-preview">
                        ${(value == null ? void 0 : value.url) ? `<img src="${value.url}" style="max-width: 100%; height: auto; margin-top: 10px; border-radius: 4px; border: 1px solid #e6e9ec;">` : ""}
                    </div>`;
            break;
          case "repeater":
            const items = Array.isArray(value) ? value : control.default || [];
            const fields = control.fields || [];
            html += `<div class="probuilder-repeater" data-setting="${key}" style="border: 1px solid #e6e9ec; border-radius: 4px; padding: 10px; background: #fafbfc;">`;
            html += `<div class="probuilder-repeater-items">`;
            items.forEach((item, index) => {
              html += `<div class="probuilder-repeater-item" data-index="${index}" style="background: #ffffff; border: 1px solid #e6e9ec; border-radius: 4px; padding: 12px; margin-bottom: 8px; position: relative;">`;
              const firstField = fields[0];
              const itemTitle = item[firstField == null ? void 0 : firstField.name] || `Item #${index + 1}`;
              html += `<div class="probuilder-repeater-item-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; padding-bottom: 8px; border-bottom: 1px solid #e6e9ec;">`;
              html += `<div style="display: flex; align-items: center; gap: 8px;">`;
              html += `<span class="probuilder-repeater-handle" style="cursor: move; color: #71717a;"><i class="dashicons dashicons-menu"></i></span>`;
              html += `<strong style="font-size: 13px;">${itemTitle}</strong>`;
              html += `</div>`;
              html += `<div style="display: flex; gap: 4px;">`;
              html += `<button class="probuilder-repeater-toggle" type="button" style="background: none; border: none; padding: 4px 8px; cursor: pointer; color: #92003b;"><i class="dashicons dashicons-arrow-down-alt2"></i></button>`;
              html += `<button class="probuilder-repeater-delete" type="button" style="background: none; border: none; padding: 4px 8px; cursor: pointer; color: #dc2626;"><i class="dashicons dashicons-trash"></i></button>`;
              html += `</div>`;
              html += `</div>`;
              html += `<div class="probuilder-repeater-item-fields" style="display: none;">`;
              fields.forEach((field) => {
                const fieldValue = item[field.name] || field.default || "";
                html += `<div style="margin-bottom: 10px;">`;
                html += `<label style="display: block; margin-bottom: 4px; font-size: 12px; font-weight: 500;">${field.label}</label>`;
                if (field.type === "textarea") {
                  html += `<textarea class="probuilder-input" data-field="${field.name}" rows="3" style="width: 100%; padding: 8px; font-size: 12px;">${fieldValue}</textarea>`;
                } else {
                  html += `<input type="text" class="probuilder-input" data-field="${field.name}" value="${fieldValue}" style="width: 100%; padding: 8px; font-size: 12px;">`;
                }
                html += `</div>`;
              });
              html += `</div>`;
              html += `</div>`;
            });
            html += `</div>`;
            html += `<button type="button" class="probuilder-repeater-add" style="width: 100%; padding: 10px; background: #92003b; color: #ffffff; border: none; border-radius: 4px; cursor: pointer; font-size: 13px; margin-top: 8px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                        <i class="dashicons dashicons-plus-alt2" style="font-size: 16px;"></i>
                        <span>Add Item</span>
                    </button>`;
            html += `</div>`;
            break;
          default:
            html += `<input type="text" class="probuilder-input" data-setting="${key}" value="${value || ""}">`;
        }
        html += `</div>`;
        const $control = $2(html);
        $control.find(".probuilder-slider").on("input", function() {
          const unit = control.unit || "px";
          $control.find(".probuilder-slider-value").text($2(this).val() + unit);
        });
        $control.find('input[type="color"]').on("input", function() {
          $2(this).siblings('input[type="text"]').val($2(this).val());
        });
        $control.find(".probuilder-switcher-input").on("change", function() {
          const isChecked = $2(this).is(":checked");
          const $track = $2(this).siblings(".probuilder-switcher-track");
          const $thumb = $track.find(".probuilder-switcher-thumb");
          $track.css("background", isChecked ? "#92003b" : "#cbd5e1");
          $thumb.css("left", isChecked ? "22px" : "2px");
        });
        $control.find(".probuilder-media-btn").on("click", function(e) {
          e.preventDefault();
          const $btn = $2(this);
          const settingKey = $btn.data("setting");
          if (typeof wp !== "undefined" && wp.media) {
            const mediaUploader = wp.media({
              title: "Choose Image",
              button: { text: "Select Image" },
              multiple: false
            });
            mediaUploader.on("select", function() {
              const attachment = mediaUploader.state().get("selection").first().toJSON();
              if (element2) {
                element2.settings[settingKey] = {
                  id: attachment.id,
                  url: attachment.url
                };
                $btn.next(".probuilder-media-preview").html(
                  `<img src="${attachment.url}" style="max-width: 100%; height: auto; margin-top: 10px; border-radius: 4px; border: 1px solid #e6e9ec;">`
                );
                console.log("\u2705 Media updated:", settingKey, attachment.url);
                ProBuilder2.updateElementPreview(element2);
              }
            });
            mediaUploader.open();
          } else {
            const url = prompt("Enter image URL:");
            if (url && element2) {
              element2.settings[settingKey] = { url };
              $btn.next(".probuilder-media-preview").html(
                `<img src="${url}" style="max-width: 100%; height: auto; margin-top: 10px; border-radius: 4px; border: 1px solid #e6e9ec;">`
              );
              console.log("\u2705 Media URL set:", settingKey, url);
              ProBuilder2.updateElementPreview(element2);
            }
          }
        });
        return $control;
      },
      /**
       * Close settings panel
       */
      closeSettings: function() {
        $2(".probuilder-element").removeClass("selected");
        this.selectedElement = null;
        this.showSettingsPlaceholder();
      },
      /**
       * Show settings placeholder
       */
      showSettingsPlaceholder: function() {
        console.log("Showing settings placeholder");
        $2("#probuilder-settings-title").text("Settings");
        $2("#probuilder-settings-content").html(`
                <div class="probuilder-settings-placeholder" style="display: block;">
                    <i class="dashicons dashicons-admin-settings"></i>
                    <h4>Element Settings</h4>
                    <p>Click on any element in the canvas to edit its settings</p>
                    <div style="margin-top: 30px; padding: 20px; background: #f4f4f5; border-radius: 4px; text-align: left;">
                        <p style="font-size: 12px; color: #71717a; margin: 0 0 10px 0;"><strong>Quick Tips:</strong></p>
                        <ul style="font-size: 11px; color: #71717a; margin: 0; padding-left: 20px; line-height: 1.8;">
                            <li>Click "Edit" button (pencil icon) on elements</li>
                            <li>Use Content/Style/Advanced tabs</li>
                            <li>Changes update instantly</li>
                            <li>Resize this panel by dragging left edge</li>
                        </ul>
                    </div>
                </div>
            `);
      },
      /**
       * Update element preview
       */
      updateElementPreview: function(element2) {
        console.log("Updating preview for:", element2.widgetType, element2.id);
        console.log("Current settings:", element2.settings);
        const $element = $2(`.probuilder-element[data-id="${element2.id}"]`);
        if ($element.length === 0) {
          console.error("Element not found in DOM:", element2.id);
          return;
        }
        const newPreview = this.generatePreview(element2);
        $element.find(".probuilder-element-preview").html(newPreview);
        const spacingAfter = this.getSpacingStyles(element2.settings || {});
        const commonAfter = this.getCommonInlineStyles(element2.settings || {});
        const combinedStyleStr = [spacingAfter, commonAfter].filter(Boolean).join("; ");
        if (combinedStyleStr) {
          const $preview = $element.find(".probuilder-element-preview");
          $preview.attr("style", (($preview.attr("style") || "") + "; " + combinedStyleStr).trim());
        }
        this.applyResponsiveVisibility(element2, $element);
        this.applyMotionStyles(element2, $element);
        clearTimeout(this.historyDebounceTimer);
        this.historyDebounceTimer = setTimeout(() => {
          this.saveHistory();
        }, 1e3);
        if (element2.widgetType === "grid-layout" || element2.widgetType === "container-2") {
          console.log("\u{1F501} Re-rendering grid-based element to refresh interactions:", element2.id);
          const insertBefore = $element.next()[0];
          $element.remove();
          this.renderElement(element2, insertBefore);
          return;
        }
        if (element2.widgetType === "container") {
          const self2 = this;
          setTimeout(function() {
            console.log("\u{1F527} Re-initializing container interactivity");
            self2.makeContainersDroppable();
            const $dropZones = $element.find(".probuilder-drop-zone");
            console.log("Found", $dropZones.length, "drop zones in container");
            $dropZones.each(function() {
              const $zone = $2(this);
              const containerId = $zone.data("container-id");
              const columnIndex = $zone.data("column-index");
              console.log("Attaching click to drop zone:", containerId, "column:", columnIndex);
              $zone.off("click").on("click", function(e) {
                e.stopPropagation();
                e.preventDefault();
                if (self2.isGridCellResizing) {
                  console.log("\u23F8\uFE0F Reinitialized drop zone click ignored - grid cell resizing");
                  return false;
                }
                console.log("\u2705 Drop zone clicked in container:", containerId, "column:", columnIndex);
                self2.showWidgetTemplateSelector(containerId, columnIndex);
                return false;
              });
            });
            const $resizeHandles = $element.find(".column-resize-handle");
            console.log("Found", $resizeHandles.length, "resize handles for re-initialization");
            $element.off("mousedown.columnResize", ".column-resize-handle");
            $element.on("mousedown.columnResize", ".column-resize-handle", function(e) {
              e.stopPropagation();
              e.preventDefault();
              const columnIndex = $2(this).data("column-index");
              const direction = $2(this).data("direction");
              console.log("\u{1F3AF} Container column resize started (re-initialized):", columnIndex, direction);
              const containerElement = self2.elements.find((el) => el.id === element2.id);
              if (!containerElement) {
                console.error("Container element not found:", element2.id);
                return;
              }
              $2(document).on("click.columnResizePrevent", function(clickEvent) {
                clickEvent.preventDefault();
                clickEvent.stopPropagation();
                $2(document).off("click.columnResizePrevent");
              });
              self2.startContainerColumnResize(containerElement, columnIndex, direction, e);
            });
            console.log("\u2705 Container click handlers and resize handlers attached");
          }, 50);
        }
        if (element2.widgetType === "tabs") {
          const self2 = this;
          setTimeout(function() {
            self2.makeTabsDroppable(element2);
            self2.attachTabNestedHandlers(element2, $element);
          }, 50);
        }
        if (element2.widgetType === "carousel") {
          const self2 = this;
          setTimeout(function() {
            console.log("\u{1F3A0} Re-initializing carousel:", element2.id);
            self2.initializeCarousel(element2, $element);
          }, 50);
        }
        console.log("Preview updated successfully");
      },
      /**
       * Update container with children (re-render with full interactivity)
       */
      updateContainerWithChildren: function(containerElement) {
        console.log("Re-rendering container with children:", containerElement.id, "Children count:", containerElement.children ? containerElement.children.length : 0);
        const $container = $2(`.probuilder-element[data-id="${containerElement.id}"]`);
        if ($container.length === 0) {
          console.error("Container not found in DOM");
          return;
        }
        const preview = this.generatePreview(containerElement, 0);
        const $preview = $container.find(".probuilder-element-preview");
        if ($preview.length > 0) {
          $preview.html(preview);
          console.log("\u2705 Container preview updated with", containerElement.children.length, "children");
        } else {
          console.error("\u274C Preview element not found for container");
        }
        const self2 = this;
        setTimeout(function() {
          self2.attachNestedElementHandlers(containerElement, $container);
          $container.find(".probuilder-drop-zone").off("click").on("click", function(e) {
            e.stopPropagation();
            const containerId = $2(this).data("container-id");
            const columnIndex = $2(this).data("column-index");
            if (self2.isGridCellResizing) {
              console.log("\u23F8\uFE0F Container drop zone click ignored - grid cell resizing");
              return false;
            }
            console.log("Drop zone clicked in container:", containerId, "column:", columnIndex);
            self2.showWidgetTemplateSelector(containerId, columnIndex);
          });
          setTimeout(function() {
            self2.makeContainersDroppable();
            const $widgets = $2(".probuilder-widget");
            let needsReinit = false;
            $widgets.each(function() {
              if (!$2(this).data("ui-draggable")) {
                needsReinit = true;
                return false;
              }
            });
            if (needsReinit) {
              console.log("\u26A0\uFE0F Sidebar widgets lost, reinitializing...");
              self2.reinitializeSidebarWidgets();
            }
            const $previewArea = $2("#probuilder-preview-area");
            if (!$previewArea.data("ui-droppable")) {
              console.log("\u26A0\uFE0F Preview area droppable lost, reinitializing...");
              self2.reinitializePreviewArea();
            }
            console.log("\u2705 All drag & drop components verified and reinitialized");
          }, 100);
          console.log("\u2705 Container fully updated with interactive children and drop zones");
        }, 50);
      },
      /**
       * Attach event handlers to nested elements in containers
       */
      attachNestedElementHandlers: function(containerElement, $containerDOM) {
        const self2 = this;
        if (!containerElement.children || containerElement.children.length === 0) {
          return;
        }
        console.log("\u{1F527} Attaching handlers to", containerElement.children.length, "nested elements");
        $containerDOM.find(".probuilder-nested-element").each(function() {
          if ($2(this).data("ui-draggable")) {
            $2(this).draggable("destroy");
          }
        });
        $containerDOM.find(".probuilder-column").each(function() {
          if ($2(this).data("ui-droppable")) {
            $2(this).droppable("destroy");
          }
        });
        containerElement.children.forEach((childElement) => {
          const $childPreview = $containerDOM.find(`.probuilder-nested-element[data-id="${childElement.id}"]`);
          if ($childPreview.length === 0) {
            console.warn("\u26A0\uFE0F Child element not found in DOM:", childElement.id);
            return;
          }
          console.log("\u2705 Found nested element:", childElement.id);
          $childPreview.find(".probuilder-nested-edit").off();
          $childPreview.find(".probuilder-nested-delete").off();
          $childPreview.off("click");
          $childPreview.find(".probuilder-nested-edit").on("mouseup click", function(e) {
            e.stopPropagation();
            e.preventDefault();
            console.log("\u270F\uFE0F Edit button clicked for:", childElement.id);
            self2.openSettings(childElement);
            return false;
          });
          $childPreview.find(".probuilder-nested-delete").on("mouseup click", function(e) {
            e.stopPropagation();
            e.preventDefault();
            console.log("\u{1F5D1}\uFE0F Delete button clicked for:", childElement.id);
            self2.deleteNestedElement(containerElement, childElement.id);
            return false;
          });
          $childPreview.on("click", function(e) {
            if (!$2(e.target).closest(".probuilder-nested-controls").length) {
              e.stopPropagation();
              console.log("\u{1F446} Nested element clicked:", childElement.id);
              self2.selectElement(childElement);
            }
          });
        });
        setTimeout(function() {
          console.log("\u{1F3AF} Setting up draggable for nested elements");
          $containerDOM.find(".probuilder-nested-element").each(function() {
            const $nestedEl = $2(this);
            const childId = $nestedEl.data("id");
            const childElement = containerElement.children.find((c) => c.id === childId);
            if (!childElement) return;
            $nestedEl.draggable({
              handle: ".probuilder-nested-drag",
              helper: "clone",
              cursor: "move",
              revert: "invalid",
              zIndex: 1e4,
              delay: 100,
              // Delay to distinguish from clicks
              start: function(event, ui) {
                console.log("\u{1F3AF} Started dragging:", childId);
                $2(this).css("opacity", "0.5");
                ui.helper.css({
                  "width": $2(this).width(),
                  "background": "#ffffff",
                  "border": "2px solid #92003b",
                  "box-shadow": "0 5px 15px rgba(0,0,0,0.3)"
                });
              },
              stop: function(event, ui) {
                console.log("\u{1F3AF} Stopped dragging:", childId);
                $2(this).css("opacity", "1");
              }
            });
          });
          $containerDOM.find(".probuilder-column").each(function(colIndex) {
            $2(this).droppable({
              accept: ".probuilder-nested-element",
              tolerance: "pointer",
              hoverClass: "probuilder-drop-hover",
              drop: function(event, ui) {
                const droppedId = ui.draggable.data("id");
                console.log("\u{1F3AF} Dropped:", droppedId, "into column:", colIndex);
                const childIndex = containerElement.children.findIndex((c) => c.id === droppedId);
                if (childIndex === -1) return;
                const movedElement = containerElement.children.splice(childIndex, 1)[0];
                containerElement.children.splice(colIndex, 0, movedElement);
                console.log("\u2705 Moved to column", colIndex);
                self2.updateContainerWithChildren(containerElement);
              }
            });
          });
          if (!$2("#probuilder-preview-area").data("nested-droppable-init")) {
            $2("#probuilder-preview-area").droppable({
              accept: ".probuilder-nested-element",
              tolerance: "pointer",
              drop: function(event, ui) {
                const droppedId = ui.draggable.data("id");
                console.log("\u{1F3AF} Dropped onto canvas:", droppedId);
                const childIndex = containerElement.children.findIndex((c) => c.id === droppedId);
                if (childIndex === -1) return;
                const movedElement = containerElement.children.splice(childIndex, 1)[0];
                self2.elements.push(movedElement);
                console.log("\u2705 Moved out of container");
                self2.updateContainerWithChildren(containerElement);
                self2.render();
              }
            });
            $2("#probuilder-preview-area").data("nested-droppable-init", true);
          }
          console.log("\u2705 All nested elements are now fully interactive");
        }, 100);
      },
      /**
       * Delete nested element from container
       */
      deleteNestedElement: function(containerElement, childId) {
        console.log("Deleting nested element:", childId, "from container:", containerElement.id);
        if (!containerElement.children) return;
        containerElement.children = containerElement.children.filter((child) => child.id !== childId);
        console.log("Container now has", containerElement.children.length, "children");
        this.updateContainerWithChildren(containerElement);
      },
      /**
       * Make tabs droppable
       */
      makeTabsDroppable: function(tabsElement) {
        const self2 = this;
        const $tabsContainer = $2(`[data-element-id="${tabsElement.id}"][data-tabs-id]`);
        $tabsContainer.find(".probuilder-tab-drop-zone").each(function() {
          const $zone = $2(this);
          const tabIndex = parseInt($zone.data("tab-index"));
          $zone.droppable({
            accept: ".probuilder-widget",
            tolerance: "pointer",
            hoverClass: "probuilder-drop-hover",
            greedy: true,
            // Prevent parent elements from also handling the drop
            drop: function(event, ui) {
              event.stopPropagation();
              event.preventDefault();
              self2.isNestedDropInProgress = true;
              const finishNestedDrop = () => {
                setTimeout(() => {
                  self2.isNestedDropInProgress = false;
                }, 0);
              };
              const widgetName = ui.draggable.data("widget");
              console.log("Widget dropped into tab:", widgetName, "Tab index:", tabIndex);
              const widget2 = self2.widgets.find((w) => w.name === widgetName);
              if (!widget2) {
                finishNestedDrop();
                return;
              }
              const newElement = {
                id: "element-" + Date.now(),
                widgetType: widgetName,
                settings: {}
              };
              if (widget2.controls) {
                Object.keys(widget2.controls).forEach((key) => {
                  const control = widget2.controls[key];
                  if (control.default !== void 0) {
                    newElement.settings[key] = control.default;
                  }
                });
              }
              if (!tabsElement.tabChildren) {
                tabsElement.tabChildren = [];
              }
              if (!tabsElement.tabChildren[tabIndex]) {
                tabsElement.tabChildren[tabIndex] = [];
              }
              tabsElement.tabChildren[tabIndex].push(newElement);
              console.log("Element added to tab", tabIndex);
              self2.updateElementPreview(tabsElement);
              finishNestedDrop();
            }
          });
          $zone.sortable({
            items: ".probuilder-nested-element",
            handle: ".probuilder-nested-drag",
            placeholder: "probuilder-nested-placeholder",
            tolerance: "pointer",
            update: function(event, ui) {
              console.log("Tab elements reordered");
              const newOrder = [];
              $zone.find(".probuilder-nested-element").each(function() {
                const childId = $2(this).data("id");
                const child = tabsElement.tabChildren[tabIndex].find((c) => c.id === childId);
                if (child) {
                  newOrder.push(child);
                }
              });
              tabsElement.tabChildren[tabIndex] = newOrder;
            }
          });
        });
      },
      /**
       * Attach handlers to nested elements in tabs
       */
      attachTabNestedHandlers: function(tabsElement, $tabsContainer) {
        const self2 = this;
        if (!tabsElement.tabChildren) return;
        tabsElement.tabChildren.forEach((tabChildren, tabIndex) => {
          if (!tabChildren || tabChildren.length === 0) return;
          tabChildren.forEach((childElement) => {
            const $childPreview = $tabsContainer.find(`.probuilder-nested-element[data-id="${childElement.id}"]`);
            if ($childPreview.length === 0) return;
            $childPreview.find(".probuilder-nested-edit").off("mouseup click").on("mouseup click", function(e) {
              e.stopPropagation();
              e.preventDefault();
              console.log("\u270F\uFE0F Tab nested element edit:", childElement.id);
              self2.openSettings(childElement);
              return false;
            });
            $childPreview.find(".probuilder-nested-delete").off("mouseup click").on("mouseup click", function(e) {
              e.stopPropagation();
              e.preventDefault();
              console.log("\u{1F5D1}\uFE0F Tab nested element delete:", childElement.id);
              tabsElement.tabChildren[tabIndex] = tabsElement.tabChildren[tabIndex].filter((c) => c.id !== childElement.id);
              self2.updateElementPreview(tabsElement);
              return false;
            });
            $childPreview.on("click", function(e) {
              if (!$2(e.target).closest(".probuilder-nested-controls").length) {
                e.stopPropagation();
                console.log("\u{1F446} Tab nested element clicked:", childElement.id);
                self2.selectElement(childElement);
              }
            });
          });
        });
      },
      /**
       * Initialize carousel functionality
       */
      initializeCarousel: function(element2, $elementDOM) {
        const settings2 = element2.settings || {};
        const carouselId = "carousel-" + element2.id;
        const $carousel = $elementDOM ? $elementDOM.find(`[data-carousel-id="${carouselId}"]`) : $2(`[data-carousel-id="${carouselId}"]`);
        if ($carousel.length === 0) {
          console.warn("Carousel not found:", carouselId);
          return;
        }
        console.log("\u{1F3A0} Initializing carousel:", carouselId);
        const carouselImages = Array.isArray(settings2.images) ? settings2.images : [
          { image_url: "https://via.placeholder.com/1200x600/92003b/ffffff?text=Slide+1", caption: "First Slide" },
          { image_url: "https://via.placeholder.com/1200x600/667eea/ffffff?text=Slide+2", caption: "Second Slide" },
          { image_url: "https://via.placeholder.com/1200x600/4facfe/ffffff?text=Slide+3", caption: "Third Slide" }
        ];
        const dotsColor = settings2.dots_color || "#92003b";
        const autoplay = settings2.autoplay !== "no";
        const autoplaySpeed = parseInt(settings2.autoplay_speed) || 3e3;
        let currentSlide = 0;
        const totalSlides = carouselImages.length;
        let autoplayInterval = null;
        console.log("Carousel settings:", {
          slides: totalSlides,
          autoplay,
          speed: autoplaySpeed,
          dotsColor
        });
        function showSlide(index) {
          if (index < 0) index = totalSlides - 1;
          if (index >= totalSlides) index = 0;
          currentSlide = index;
          console.log("Showing slide:", currentSlide);
          $carousel.find(".probuilder-carousel-slide").css("display", "none");
          $carousel.find(`.probuilder-carousel-slide[data-slide="${currentSlide}"]`).css("display", "flex");
          $carousel.find(".probuilder-carousel-dot").each(function() {
            const dotIndex = parseInt($2(this).data("slide"));
            const isActive = dotIndex === currentSlide;
            $2(this).css({
              "width": isActive ? "24px" : "12px",
              "background": isActive ? dotsColor : "transparent"
            }).toggleClass("active", isActive);
          });
        }
        function nextSlide() {
          showSlide(currentSlide + 1);
        }
        function prevSlide() {
          showSlide(currentSlide - 1);
        }
        $carousel.find(".probuilder-carousel-prev").off("click").on("click", function(e) {
          e.stopPropagation();
          console.log("\u2190 Previous clicked");
          prevSlide();
          if (autoplay && autoplayInterval) {
            clearInterval(autoplayInterval);
            startAutoplay();
          }
        });
        $carousel.find(".probuilder-carousel-next").off("click").on("click", function(e) {
          e.stopPropagation();
          console.log("\u2192 Next clicked");
          nextSlide();
          if (autoplay && autoplayInterval) {
            clearInterval(autoplayInterval);
            startAutoplay();
          }
        });
        $carousel.find(".probuilder-carousel-dot").off("click").on("click", function(e) {
          e.stopPropagation();
          const slideIndex = parseInt($2(this).data("slide"));
          console.log("\u25CF Dot clicked:", slideIndex);
          showSlide(slideIndex);
          if (autoplay && autoplayInterval) {
            clearInterval(autoplayInterval);
            startAutoplay();
          }
        });
        function startAutoplay() {
          if (autoplayInterval) {
            clearInterval(autoplayInterval);
          }
          console.log("\u25B6 Starting autoplay with speed:", autoplaySpeed);
          autoplayInterval = setInterval(nextSlide, autoplaySpeed);
        }
        if (autoplay) {
          startAutoplay();
        }
        $carousel.on("mouseenter", function() {
          if (autoplayInterval) {
            console.log("\u23F8 Pausing autoplay (hover)");
            clearInterval(autoplayInterval);
          }
        }).on("mouseleave", function() {
          if (autoplay) {
            console.log("\u25B6 Resuming autoplay");
            startAutoplay();
          }
        });
        console.log("\u2705 Carousel initialized successfully");
      },
      /**
       * Show widget picker for tab
       */
      showWidgetPickerForTab: function(tabsElementId, tabIndex) {
        const self2 = this;
        const tabsElement = this.elements.find((e) => e.id === tabsElementId);
        if (!tabsElement) return;
        const $overlay = $2('<div class="probuilder-widget-picker-overlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 100000; display: flex; align-items: center; justify-content: center;"></div>');
        const $modal = $2('<div class="probuilder-widget-picker-modal" style="background: #ffffff; border-radius: 8px; max-width: 900px; width: 90%; max-height: 80vh; overflow: auto; padding: 30px; box-shadow: 0 20px 60px rgba(0,0,0,0.3);"></div>');
        $modal.append('<h2 style="margin: 0 0 20px 0; font-size: 24px; color: #1e293b;">Add Widget to Tab ' + (tabIndex + 1) + "</h2>");
        const $grid = $2('<div class="probuilder-widget-picker-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 15px;"></div>');
        this.widgets.forEach((widget2) => {
          const $widgetCard = $2(`
                    <div class="probuilder-widget-picker-card" data-widget="${widget2.name}" style="
                        padding: 20px;
                        border: 2px solid #e2e8f0;
                        border-radius: 8px;
                        text-align: center;
                        cursor: pointer;
                        transition: all 0.2s;
                        background: #ffffff;
                    ">
                        <i class="${widget2.icon}" style="font-size: 32px; color: #92003b; margin-bottom: 10px; display: block;"></i>
                        <div style="font-size: 13px; font-weight: 500; color: #334155;">${widget2.title}</div>
                    </div>
                `);
          $widgetCard.on("mouseenter", function() {
            $2(this).css({ "border-color": "#92003b", "transform": "translateY(-2px)", "box-shadow": "0 4px 12px rgba(146,0,59,0.15)" });
          }).on("mouseleave", function() {
            $2(this).css({ "border-color": "#e2e8f0", "transform": "translateY(0)", "box-shadow": "none" });
          });
          $widgetCard.on("click", function() {
            const widgetName = $2(this).data("widget");
            const selectedWidget = self2.widgets.find((w) => w.name === widgetName);
            if (!selectedWidget) return;
            const newElement = {
              id: "element-" + Date.now(),
              widgetType: widgetName,
              settings: {}
            };
            if (selectedWidget.controls) {
              Object.keys(selectedWidget.controls).forEach((key) => {
                const control = selectedWidget.controls[key];
                if (control.default !== void 0) {
                  newElement.settings[key] = control.default;
                }
              });
            }
            if (!tabsElement.tabChildren) {
              tabsElement.tabChildren = [];
            }
            if (!tabsElement.tabChildren[tabIndex]) {
              tabsElement.tabChildren[tabIndex] = [];
            }
            tabsElement.tabChildren[tabIndex].push(newElement);
            console.log("Widget added to tab", tabIndex, ":", widgetName);
            self2.updateElementPreview(tabsElement);
            $overlay.remove();
          });
          $grid.append($widgetCard);
        });
        $modal.append($grid);
        const $closeBtn = $2('<button style="position: absolute; top: 20px; right: 20px; background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b; padding: 5px 10px;">\xD7</button>');
        $closeBtn.on("click", function() {
          $overlay.remove();
        });
        $modal.css("position", "relative").prepend($closeBtn);
        $overlay.on("click", function(e) {
          if (e.target === this) {
            $2(this).remove();
          }
        });
        $overlay.append($modal);
        $2("body").append($overlay);
      },
      /**
       * Duplicate element
       */
      duplicateElement: function(element2) {
        const newElement = JSON.parse(JSON.stringify(element2));
        newElement.id = "element-" + Date.now();
        this.elements.push(newElement);
        this.renderElement(newElement);
      },
      /**
       * Delete element
       */
      deleteElement: function(element2) {
        if (!Array.isArray(this.elements)) {
          console.warn("\u26A0\uFE0F this.elements was not an array! Initializing as empty array.");
          this.elements = [];
          return;
        }
        const index = this.elements.findIndex((e) => e.id === element2.id);
        if (index > -1) {
          this.elements.splice(index, 1);
        }
        $2(`.probuilder-element[data-id="${element2.id}"]`).remove();
        this.closeSettings();
        this.updateEmptyState();
        this.saveHistory();
      },
      /**
       * Update elements order
       */
      updateElementsOrder: function() {
        const newOrder = [];
        $2("#probuilder-preview-area .probuilder-element").each(function() {
          const id = $2(this).data("id");
          const element2 = ProBuilder2.elements.find((e) => e.id === id);
          if (element2) {
            newOrder.push(element2);
          }
        });
        this.elements = newOrder;
      },
      /**
       * Update empty state
       */
      updateEmptyState: function() {
        if (this.elements.length === 0) {
          $2("#probuilder-empty-state").show();
          $2("#probuilder-add-bottom").hide();
        } else {
          $2("#probuilder-empty-state").hide();
          $2("#probuilder-add-bottom").show();
        }
      },
      /**
       * Initialize resizable panels
       */
      initResizablePanels: function() {
        const self2 = this;
        let isResizing = false;
        let currentPanel = null;
        let startX = 0;
        let startWidth = 0;
        $2(".probuilder-sidebar").on("mousedown", function(e) {
          const rect = this.getBoundingClientRect();
          const clickX = e.clientX - rect.left;
          const width = rect.width;
          if (clickX > width - 15) {
            isResizing = true;
            currentPanel = "left";
            startX = e.pageX;
            startWidth = $2(this).width();
            $2(this).addClass("resizing");
            $2("body").css("cursor", "ew-resize").css("user-select", "none");
            e.preventDefault();
            e.stopPropagation();
          }
        });
        $2(".probuilder-settings-panel").on("mousedown", function(e) {
          const rect = this.getBoundingClientRect();
          const clickX = e.clientX - rect.left;
          if (clickX < 15) {
            isResizing = true;
            currentPanel = "right";
            startX = e.pageX;
            startWidth = $2(this).width();
            $2(this).addClass("resizing");
            $2("body").css("cursor", "ew-resize").css("user-select", "none");
            e.preventDefault();
            e.stopPropagation();
          }
        });
        $2(document).on("mousemove", function(e) {
          if (!isResizing) return;
          const dx = e.pageX - startX;
          if (currentPanel === "left") {
            const newWidth = Math.max(200, Math.min(500, startWidth + dx));
            $2(".probuilder-sidebar").width(newWidth);
            self2.updatePanelResponsiveness();
            localStorage.setItem("probuilder_sidebar_width", newWidth);
          } else if (currentPanel === "right") {
            const newWidth = Math.max(250, Math.min(600, startWidth - dx));
            $2(".probuilder-settings-panel").width(newWidth);
            self2.updatePanelResponsiveness();
            localStorage.setItem("probuilder_settings_width", newWidth);
          }
          e.preventDefault();
        });
        $2(document).on("mouseup", function() {
          if (isResizing) {
            isResizing = false;
            currentPanel = null;
            $2(".probuilder-sidebar, .probuilder-settings-panel").removeClass("resizing");
            $2("body").css("cursor", "").css("user-select", "");
          }
        });
        const savedSidebarWidth = localStorage.getItem("probuilder_sidebar_width");
        const savedSettingsWidth = localStorage.getItem("probuilder_settings_width");
        if (savedSidebarWidth) {
          $2(".probuilder-sidebar").width(parseInt(savedSidebarWidth));
        }
        if (savedSettingsWidth) {
          $2(".probuilder-settings-panel").width(parseInt(savedSettingsWidth));
        }
        self2.updatePanelResponsiveness();
        console.log("\u2705 Resizable panels initialized");
      },
      /**
       * Initialize sidebar collapse/expand toggles
       */
      initSidebarToggles: function() {
        const self2 = this;
        $2("#probuilder-left-sidebar-toggle").on("click", function() {
          $2(".probuilder-sidebar").toggleClass("collapsed");
          const isCollapsed = $2(".probuilder-sidebar").hasClass("collapsed");
          console.log("Left sidebar", isCollapsed ? "collapsed" : "expanded");
          setTimeout(() => {
            self2.updatePanelResponsiveness();
          }, 300);
        });
        $2("#probuilder-right-sidebar-toggle").on("click", function() {
          $2(".probuilder-settings-panel").toggleClass("collapsed");
          const isCollapsed = $2(".probuilder-settings-panel").hasClass("collapsed");
          console.log("Right sidebar", isCollapsed ? "collapsed" : "expanded");
          setTimeout(() => {
            self2.updatePanelResponsiveness();
          }, 300);
        });
        console.log("\u2705 Sidebar toggles initialized");
      },
      /**
       * Update panel responsiveness based on width
       */
      updatePanelResponsiveness: function() {
        const sidebarWidth = $2(".probuilder-sidebar").width();
        if (sidebarWidth < 250) {
          $2(".probuilder-sidebar").attr("data-width", "narrow");
        } else if (sidebarWidth > 380) {
          $2(".probuilder-sidebar").attr("data-width", "wide");
        } else {
          $2(".probuilder-sidebar").attr("data-width", "medium");
        }
        const settingsWidth = $2(".probuilder-settings-panel").width();
        if (settingsWidth < 300) {
          $2(".probuilder-settings-panel").attr("data-width", "narrow");
        } else if (settingsWidth > 420) {
          $2(".probuilder-settings-panel").attr("data-width", "wide");
        } else {
          $2(".probuilder-settings-panel").attr("data-width", "medium");
        }
        console.log("Panel widths updated - Sidebar:", sidebarWidth, "Settings:", settingsWidth);
      },
      /**
       * Show widget/template selector modal for drop zones
       */
      showWidgetTemplateSelector: function(containerId = null, columnIndex = null) {
        const self2 = this;
        const modalHTML = `
                <div class="probuilder-selector-overlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.8); z-index: 999999; display: flex; align-items: center; justify-content: center;">
                    <div class="probuilder-selector-modal" style="background: #ffffff; border-radius: 8px; width: 90%; max-width: 900px; max-height: 80vh; display: flex; flex-direction: column; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
                        <div style="padding: 20px 25px; border-bottom: 1px solid #e6e9ec; display: flex; justify-content: space-between; align-items: center;">
                            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #495157;">
                                <i class="dashicons dashicons-plus-alt2" style="color: #92003b;"></i>
                                Add Element
                            </h3>
                            <button class="probuilder-selector-close" style="background: transparent; border: none; font-size: 32px; cursor: pointer; color: #a1a1aa; line-height: 1; width: 32px; height: 32px; padding: 0;">&times;</button>
                        </div>
                        <div style="padding: 25px; overflow-y: auto; flex: 1;">
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 12px;">
                                ${this.widgets.map((widget2) => `
                                    <div class="probuilder-selector-widget" data-widget="${widget2.name}" style="background: #ffffff; border: 1px solid #e6e9ec; border-radius: 6px; padding: 15px 10px; text-align: center; cursor: pointer; transition: all 0.2s;">
                                        <i class="${widget2.icon}" style="font-size: 28px; color: #92003b; margin-bottom: 8px; display: block;"></i>
                                        <div style="font-size: 11px; font-weight: 600; color: #495157; line-height: 1.3;">${widget2.title}</div>
                                    </div>
                                `).join("")}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        $2("body").append(modalHTML);
        $2(".probuilder-selector-close, .probuilder-selector-overlay").on("click", function(e) {
          if (e.target === this) {
            $2(".probuilder-selector-overlay").fadeOut(200, function() {
              $2(this).remove();
            });
          }
        });
        $2(".probuilder-selector-widget").hover(
          function() {
            $2(this).css({
              "border-color": "#92003b",
              "transform": "translateY(-4px)",
              "box-shadow": "0 8px 16px rgba(146, 0, 59, 0.15)"
            });
          },
          function() {
            $2(this).css({
              "border-color": "#e6e9ec",
              "transform": "translateY(0)",
              "box-shadow": "none"
            });
          }
        );
        $2(".probuilder-selector-widget").on("click", function() {
          const widgetName = $2(this).data("widget");
          console.log("Widget selected:", widgetName, "for container:", containerId, "column:", columnIndex);
          if (containerId) {
            self2.addElementToContainer(widgetName, containerId, columnIndex);
          } else {
            self2.addElement(widgetName);
          }
          $2(".probuilder-selector-overlay").fadeOut(200, function() {
            $2(this).remove();
          });
        });
      },
      /**
       * Show add element modal (for adding below an element)
       */
      showAddElementModal: function(afterElement) {
        const self2 = this;
        const modalHTML = `
                <div class="probuilder-selector-overlay" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.8); z-index: 999999; display: flex; align-items: center; justify-content: center;">
                    <div class="probuilder-selector-modal" style="background: #ffffff; border-radius: 8px; width: 90%; max-width: 900px; max-height: 80vh; display: flex; flex-direction: column; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
                        <div style="padding: 20px 25px; border-bottom: 1px solid #e6e9ec; display: flex; justify-content: space-between; align-items: center;">
                            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #495157;">
                                <i class="dashicons dashicons-plus-alt2" style="color: #92003b;"></i>
                                Add Element Below
                            </h3>
                            <button class="probuilder-selector-close" style="background: transparent; border: none; font-size: 32px; cursor: pointer; color: #a1a1aa; line-height: 1; width: 32px; height: 32px; padding: 0;">&times;</button>
                        </div>
                        <div style="padding: 25px; overflow-y: auto; flex: 1;">
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 12px;">
                                ${this.widgets.map((widget2) => `
                                    <div class="probuilder-selector-widget" data-widget="${widget2.name}" style="background: #ffffff; border: 1px solid #e6e9ec; border-radius: 6px; padding: 15px 10px; text-align: center; cursor: pointer; transition: all 0.2s;">
                                        <i class="${widget2.icon}" style="font-size: 28px; color: #92003b; margin-bottom: 8px; display: block;"></i>
                                        <div style="font-size: 11px; font-weight: 600; color: #495157; line-height: 1.3;">${widget2.title}</div>
                                    </div>
                                `).join("")}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        $2("body").append(modalHTML);
        $2(".probuilder-selector-close, .probuilder-selector-overlay").on("click", function(e) {
          if (e.target === this) {
            $2(".probuilder-selector-overlay").fadeOut(200, function() {
              $2(this).remove();
            });
          }
        });
        $2(".probuilder-selector-widget").hover(
          function() {
            $2(this).css({
              "border-color": "#92003b",
              "transform": "translateY(-4px)",
              "box-shadow": "0 8px 16px rgba(146, 0, 59, 0.15)"
            });
          },
          function() {
            $2(this).css({
              "border-color": "#e6e9ec",
              "transform": "translateY(0)",
              "box-shadow": "none"
            });
          }
        );
        $2(".probuilder-selector-widget").on("click", function() {
          const widgetName = $2(this).data("widget");
          console.log("Widget selected to add after:", afterElement.id);
          const currentIndex = self2.elements.findIndex((e) => e.id === afterElement.id);
          if (currentIndex !== -1) {
            const widget2 = self2.widgets.find((w) => w.name === widgetName);
            if (widget2) {
              const newElement = {
                id: "element-" + Date.now() + "-" + Math.random().toString(36).substr(2, 9),
                widgetType: widgetName,
                settings: Object.assign({}, self2.getDefaultSettings(widget2)),
                children: []
              };
              self2.elements.splice(currentIndex + 1, 0, newElement);
              self2.renderElements();
              setTimeout(() => {
                self2.selectElement(newElement);
              }, 100);
              console.log("Element added after:", afterElement.id);
            }
          }
          $2(".probuilder-selector-overlay").fadeOut(200, function() {
            $2(this).remove();
          });
        });
      },
      /**
       * Generate template thumbnail
       */
      generateTemplateThumbnail: function(templateId) {
        const thumbnails = {
          // Full Page Templates
          "page-landing": `
                    <div style="background: white; height: 100%; overflow: hidden;">
                        <!-- Header -->
                        <div style="background: #1e293b; height: 18px; display: flex; align-items: center; padding: 0 8px; gap: 6px;">
                            <div style="width: 30px; height: 6px; background: #92003b; border-radius: 2px;"></div>
                            <div style="margin-left: auto; display: flex; gap: 3px;">
                                ${[1, 2, 3].map(() => `<div style="width: 12px; height: 4px; background: rgba(255,255,255,0.5); border-radius: 1px;"></div>`).join("")}
                            </div>
                        </div>
                        <!-- Hero -->
                        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 45px; display: flex; align-items: center; justify-content: center;">
                            <div style="text-align: center;">
                                <div style="width: 60px; height: 5px; background: rgba(255,255,255,0.9); margin: 0 auto 3px; border-radius: 2px;"></div>
                                <div style="width: 40px; height: 3px; background: rgba(255,255,255,0.7); margin: 0 auto 5px; border-radius: 2px;"></div>
                                <div style="width: 20px; height: 8px; background: white; margin: 0 auto; border-radius: 2px;"></div>
                            </div>
                        </div>
                        <!-- Features -->
                        <div style="padding: 8px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px;">
                            ${[1, 2, 3].map(() => `
                                <div style="text-align: center;">
                                    <div style="width: 12px; height: 12px; background: #92003b; border-radius: 50%; margin: 0 auto 3px;"></div>
                                    <div style="width: 80%; height: 2px; background: #e2e8f0; margin: 0 auto 2px; border-radius: 1px;"></div>
                                    <div style="width: 100%; height: 2px; background: #f1f5f9; margin: 0 auto; border-radius: 1px;"></div>
                                </div>
                            `).join("")}
                        </div>
                        <!-- Pricing -->
                        <div style="background: #f8f9fa; padding: 6px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 3px;">
                            ${[1, 2, 3].map((i) => `<div style="background: white; border: 1px solid ${i === 2 ? "#92003b" : "#e6e9ec"}; height: 20px; border-radius: 3px;"></div>`).join("")}
                        </div>
                        <!-- CTA -->
                        <div style="background: #92003b; height: 20px; display: flex; align-items: center; justify-content: center;">
                            <div style="width: 40px; height: 6px; background: rgba(255,255,255,0.9); border-radius: 2px;"></div>
                        </div>
                        <!-- Footer -->
                        <div style="background: #1e293b; height: 15px;"></div>
                    </div>
                `,
          "page-about": `
                    <div style="background: white; height: 100%; overflow: hidden;">
                        <div style="background: #1e293b; height: 15px;"></div>
                        <div style="background: #f8f9fa; height: 25px; display: flex; align-items: center; justify-content: center;">
                            <div style="width: 50px; height: 4px; background: #92003b; border-radius: 2px;"></div>
                        </div>
                        <div style="padding: 8px; display: grid; grid-template-columns: 1fr 1fr; gap: 6px;">
                            <div style="background: #e6e9ec; height: 40px; border-radius: 4px;"></div>
                            <div style="display: flex; flex-direction: column; gap: 3px; justify-content: center;">
                                ${[1, 2, 3, 4].map(() => `<div style="width: 100%; height: 3px; background: #e2e8f0; border-radius: 1px;"></div>`).join("")}
                            </div>
                        </div>
                        <div style="background: #f8f9fa; padding: 6px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 3px; margin: 4px 0;">
                            ${[1, 2, 3, 4].map(() => `
                                <div style="text-align: center;">
                                    <div style="width: 15px; height: 15px; background: #cbd5e1; border-radius: 50%; margin: 0 auto 2px;"></div>
                                    <div style="width: 80%; height: 2px; background: #e2e8f0; margin: 0 auto; border-radius: 1px;"></div>
                                </div>
                            `).join("")}
                        </div>
                        <div style="background: #1e293b; height: 15px;"></div>
                    </div>
                `,
          "page-services": `
                    <div style="background: white; height: 100%; overflow: hidden;">
                        <div style="background: #1e293b; height: 15px;"></div>
                        <div style="background: linear-gradient(135deg, #92003b 0%, #d5006d 100%); height: 30px; display: flex; align-items: center; justify-content: center;">
                            <div style="width: 45px; height: 5px; background: rgba(255,255,255,0.9); border-radius: 2px;"></div>
                        </div>
                        <div style="padding: 8px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px;">
                            ${[1, 2, 3].map(() => `
                                <div style="border: 1px solid #e6e9ec; padding: 6px; border-radius: 4px; text-align: center;">
                                    <div style="width: 12px; height: 12px; background: #92003b; border-radius: 50%; margin: 0 auto 3px;"></div>
                                    <div style="width: 80%; height: 3px; background: #e2e8f0; margin: 0 auto 2px; border-radius: 1px;"></div>
                                    <div style="width: 100%; height: 2px; background: #f1f5f9; margin: 0 auto 3px; border-radius: 1px;"></div>
                                    <div style="width: 50%; height: 6px; background: #cbd5e1; margin: 0 auto; border-radius: 2px;"></div>
                                </div>
                            `).join("")}
                        </div>
                        <div style="padding: 8px;">
                            <div style="background: #f8f9fa; padding: 8px; border-radius: 4px;">
                                ${[1, 2, 3].map(() => `<div style="width: 100%; height: 6px; background: white; margin-bottom: 3px; border-radius: 2px; border: 1px solid #e2e8f0;"></div>`).join("")}
                                <div style="width: 40%; height: 10px; background: #92003b; border-radius: 2px;"></div>
                            </div>
                        </div>
                    </div>
                `,
          "page-portfolio": `
                    <div style="background: white; height: 100%; overflow: hidden;">
                        <div style="background: #0f172a; height: 15px;"></div>
                        <div style="padding: 6px 8px; text-align: center;">
                            <div style="width: 40px; height: 5px; background: #92003b; margin: 0 auto 3px; border-radius: 2px;"></div>
                            <div style="width: 60px; height: 3px; background: #cbd5e1; margin: 0 auto; border-radius: 1px;"></div>
                        </div>
                        <div style="padding: 0 8px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px;">
                            ${[1, 2, 3, 4, 5, 6].map((i) => `
                                <div style="aspect-ratio: 1; background: linear-gradient(135deg, ${["#667eea", "#f59e0b", "#ec4899", "#10b981", "#3b82f6", "#8b5cf6"][i - 1]} 0%, ${["#764ba2", "#d97706", "#db2777", "#059669", "#2563eb", "#7c3aed"][i - 1]} 100%); border-radius: 4px;"></div>
                            `).join("")}
                        </div>
                    </div>
                `,
          "page-shop": `
                    <div style="background: white; height: 100%; overflow: hidden;">
                        <div style="background: #1e293b; height: 12px; display: flex; align-items: center; padding: 0 6px; justify-content: space-between;">
                            <div style="width: 20px; height: 4px; background: #92003b; border-radius: 1px;"></div>
                            <div style="display: flex; gap: 2px;">
                                ${[1, 2, 3].map(() => `<div style="width: 10px; height: 3px; background: rgba(255,255,255,0.5); border-radius: 1px;"></div>`).join("")}
                            </div>
                        </div>
                        <div style="background: #92003b; height: 25px; display: flex; align-items: center; justify-content: center;">
                            <div style="width: 50px; height: 6px; background: rgba(255,255,255,0.9); border-radius: 2px;"></div>
                        </div>
                        <div style="padding: 8px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 4px;">
                            ${[1, 2, 3, 4, 5, 6, 7, 8].map(() => `
                                <div style="border: 1px solid #e6e9ec; border-radius: 4px; overflow: hidden;">
                                    <div style="background: #f1f5f9; height: 25px;"></div>
                                    <div style="padding: 3px; text-align: center;">
                                        <div style="width: 80%; height: 2px; background: #cbd5e1; margin: 0 auto 2px; border-radius: 1px;"></div>
                                        <div style="width: 40%; height: 4px; background: #92003b; margin: 0 auto; border-radius: 1px;"></div>
                                    </div>
                                </div>
                            `).join("")}
                        </div>
                    </div>
                `,
          "page-blog": `
                    <div style="background: white; height: 100%; overflow: hidden;">
                        <div style="background: #1e293b; height: 15px;"></div>
                        <div style="padding: 8px; display: grid; grid-template-columns: 2fr 1fr; gap: 8px;">
                            <div>
                                ${[1, 2].map(() => `
                                    <div style="background: white; border: 1px solid #e6e9ec; margin-bottom: 6px; border-radius: 4px; overflow: hidden;">
                                        <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); height: 25px;"></div>
                                        <div style="padding: 5px;">
                                            <div style="width: 80%; height: 3px; background: #1e293b; margin-bottom: 2px; border-radius: 1px;"></div>
                                            <div style="width: 100%; height: 2px; background: #e2e8f0; margin-bottom: 2px; border-radius: 1px;"></div>
                                            <div style="width: 90%; height: 2px; background: #e2e8f0; border-radius: 1px;"></div>
                                        </div>
                                    </div>
                                `).join("")}
                            </div>
                            <div style="background: #f8f9fa; padding: 6px; border-radius: 4px;">
                                ${[1, 2, 3, 4].map(() => `<div style="width: 100%; height: 3px; background: #cbd5e1; margin-bottom: 3px; border-radius: 1px;"></div>`).join("")}
                            </div>
                        </div>
                    </div>
                `,
          // Hero Sections
          "hero-1": `
                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 100%; display: flex; align-items: center; justify-content: center; padding: 20px; position: relative;">
                        <div style="position: absolute; top: 8px; right: 8px; width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 50%; opacity: 0.3;"></div>
                        <div style="position: absolute; bottom: 8px; left: 8px; width: 30px; height: 30px; background: rgba(255,255,255,0.15); border-radius: 50%; opacity: 0.3;"></div>
                        <div style="text-align: center; color: white; max-width: 80%;">
                            <div style="width: 100%; height: 10px; background: rgba(255,255,255,0.95); margin: 0 auto 6px; border-radius: 3px; box-shadow: 0 2px 8px rgba(0,0,0,0.2);"></div>
                            <div style="width: 75%; height: 8px; background: rgba(255,255,255,0.8); margin: 0 auto 4px; border-radius: 3px;"></div>
                            <div style="width: 85%; height: 6px; background: rgba(255,255,255,0.6); margin: 0 auto 4px; border-radius: 2px;"></div>
                            <div style="width: 70%; height: 6px; background: rgba(255,255,255,0.5); margin: 0 auto 12px; border-radius: 2px;"></div>
                            <div style="display: flex; gap: 6px; justify-content: center;">
                                <div style="width: 35px; height: 18px; background: rgba(255,255,255,0.95); border-radius: 4px; box-shadow: 0 2px 6px rgba(0,0,0,0.2);"></div>
                                <div style="width: 35px; height: 18px; background: rgba(255,255,255,0.25); border: 1px solid rgba(255,255,255,0.6); border-radius: 4px;"></div>
                            </div>
                        </div>
                    </div>
                `,
          "hero-2": `
                    <div style="background: #1e293b; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px; position: relative;">
                        <div style="width: 80%; max-width: 200px; text-align: center;">
                            <div style="width: 100%; height: 12px; background: rgba(255,255,255,0.95); margin: 0 auto 6px; border-radius: 3px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>
                            <div style="width: 75%; height: 8px; background: rgba(255,255,255,0.75); margin: 0 auto 4px; border-radius: 3px;"></div>
                            <div style="width: 85%; height: 6px; background: rgba(255,255,255,0.6); margin: 0 auto 4px; border-radius: 2px;"></div>
                            <div style="width: 70%; height: 6px; background: rgba(255,255,255,0.5); margin: 0 auto 4px; border-radius: 2px;"></div>
                            <div style="width: 90%; height: 6px; background: rgba(255,255,255,0.4); margin: 0 auto 14px; border-radius: 2px;"></div>
                            <div style="width: 45%; height: 20px; background: #92003b; margin: 0 auto; border-radius: 5px; box-shadow: 0 4px 12px rgba(146, 0, 59, 0.4);"></div>
                        </div>
                        <div style="position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); display: flex; gap: 4px;">
                            ${[1, 2, 3].map((i) => `<div style="width: 6px; height: 6px; background: ${i === 2 ? "#92003b" : "rgba(255,255,255,0.3)"}; border-radius: 50%;"></div>`).join("")}
                        </div>
                    </div>
                `,
          "hero-3": `
                    <div style="background: #f8f9fa; height: 100%; display: grid; grid-template-columns: 1fr 1fr; gap: 0;">
                        <div style="background: linear-gradient(135deg, #e6e9ec 0%, #cbd5e1 100%); display: flex; align-items: center; justify-content: center; position: relative;">
                            <div style="width: 70px; height: 70px; border-radius: 10px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); box-shadow: 0 4px 12px rgba(0,0,0,0.15);"></div>
                            <div style="position: absolute; top: 8px; left: 8px; width: 20px; height: 20px; background: rgba(255,255,255,0.4); border-radius: 50%;"></div>
                        </div>
                        <div style="display: flex; flex-direction: column; justify-content: center; padding: 15px; background: white;">
                            <div style="width: 85%; height: 9px; background: #1e293b; margin-bottom: 6px; border-radius: 3px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);"></div>
                            <div style="width: 95%; height: 5px; background: #cbd5e1; margin-bottom: 4px; border-radius: 2px;"></div>
                            <div style="width: 75%; height: 5px; background: #e2e8f0; margin-bottom: 4px; border-radius: 2px;"></div>
                            <div style="width: 90%; height: 5px; background: #e2e8f0; margin-bottom: 12px; border-radius: 2px;"></div>
                            <div style="width: 45%; height: 16px; background: #92003b; border-radius: 4px; box-shadow: 0 2px 6px rgba(146, 0, 59, 0.3);"></div>
                        </div>
                    </div>
                `,
          // Features
          "features-1": `
                    <div style="background: white; height: 100%; padding: 15px;">
                        <div style="text-align: center; margin-bottom: 12px;">
                            <div style="width: 50px; height: 6px; background: #92003b; margin: 0 auto 4px; border-radius: 2px;"></div>
                            <div style="width: 70px; height: 4px; background: #cbd5e1; margin: 0 auto; border-radius: 2px;"></div>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                            ${[1, 2, 3].map(() => `
                                <div style="text-align: center; background: #f8f9fa; padding: 12px; border-radius: 8px; border: 1px solid #e6e9ec;">
                                    <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #92003b 0%, #d5006d 100%); border-radius: 50%; margin: 0 auto 8px; box-shadow: 0 2px 8px rgba(146, 0, 59, 0.3);"></div>
                                    <div style="width: 90%; height: 6px; background: #1e293b; margin: 0 auto 4px; border-radius: 2px;"></div>
                                    <div style="width: 100%; height: 4px; background: #cbd5e1; margin: 0 auto 3px; border-radius: 2px;"></div>
                                    <div style="width: 95%; height: 4px; background: #e2e8f0; margin: 0 auto; border-radius: 2px;"></div>
                                </div>
                            `).join("")}
                        </div>
                    </div>
                `,
          "features-2": `
                    <div style="background: #f8f9fa; height: 100%; padding: 15px;">
                        <div style="text-align: center; margin-bottom: 12px;">
                            <div style="width: 50px; height: 6px; background: #92003b; margin: 0 auto 3px; border-radius: 2px;"></div>
                            <div style="width: 65px; height: 4px; background: #cbd5e1; margin: 0 auto; border-radius: 2px;"></div>
                        </div>
                        ${[1, 2].map(() => `
                            <div style="display: flex; gap: 12px; align-items: flex-start; background: white; padding: 12px; border-radius: 8px; margin-bottom: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); border: 1px solid #e6e9ec;">
                                <div style="width: 28px; height: 28px; background: linear-gradient(135deg, #92003b 0%, #d5006d 100%); border-radius: 6px; flex-shrink: 0; box-shadow: 0 2px 6px rgba(146, 0, 59, 0.3); display: flex; align-items: center; justify-content: center;">
                                    <div style="width: 10px; height: 10px; border: 2px solid white; border-radius: 50%;"></div>
                                </div>
                                <div style="flex: 1;">
                                    <div style="width: 75%; height: 6px; background: #1e293b; margin-bottom: 5px; border-radius: 2px;"></div>
                                    <div style="width: 95%; height: 4px; background: #cbd5e1; margin-bottom: 3px; border-radius: 2px;"></div>
                                    <div style="width: 88%; height: 4px; background: #e2e8f0; border-radius: 2px;"></div>
                                </div>
                            </div>
                        `).join("")}
                    </div>
                `,
          "features-3": `
                    <div style="background: white; height: 100%; padding: 15px;">
                        ${[1, 2, 3, 4].map(() => `
                            <div style="display: flex; gap: 8px; margin-bottom: 8px; align-items: center;">
                                <div style="width: 12px; height: 12px; background: #92003b; border-radius: 50%; flex-shrink: 0;"></div>
                                <div style="flex: 1; height: 5px; background: #e2e8f0; border-radius: 2px;"></div>
                            </div>
                        `).join("")}
                    </div>
                `,
          // Pricing
          "pricing-1": `
                    <div style="background: #f8f9fa; height: 100%; padding: 12px;">
                        <div style="text-align: center; margin-bottom: 10px;">
                            <div style="width: 50px; height: 6px; background: #92003b; margin: 0 auto 3px; border-radius: 2px;"></div>
                            <div style="width: 70px; height: 4px; background: #cbd5e1; margin: 0 auto; border-radius: 2px;"></div>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;">
                            ${[1, 2, 3].map((i) => `
                                <div style="background: white; border: 2px solid ${i === 2 ? "#92003b" : "#e6e9ec"}; border-radius: 8px; padding: 12px; text-align: center; box-shadow: ${i === 2 ? "0 8px 20px rgba(146, 0, 59, 0.2)" : "0 2px 8px rgba(0,0,0,0.05)"}; transform: ${i === 2 ? "scale(1.05)" : "scale(1)"};">
                                    ${i === 2 ? '<div style="position: absolute; top: -8px; left: 50%; transform: translateX(-50%); background: #92003b; color: white; padding: 2px 8px; border-radius: 10px; font-size: 8px;">POPULAR</div>' : ""}
                                    <div style="width: 70%; height: 6px; background: #1e293b; margin: 0 auto 6px; border-radius: 2px;"></div>
                                    <div style="width: 50%; height: 14px; background: linear-gradient(135deg, #92003b 0%, #d5006d 100%); margin: 0 auto 8px; border-radius: 3px; box-shadow: 0 2px 6px rgba(146, 0, 59, 0.3);"></div>
                                    <div style="width: 85%; height: 3px; background: #e2e8f0; margin: 0 auto 3px; border-radius: 2px;"></div>
                                    <div style="width: 85%; height: 3px; background: #e2e8f0; margin: 0 auto 3px; border-radius: 2px;"></div>
                                    <div style="width: 85%; height: 3px; background: #e2e8f0; margin: 0 auto 8px; border-radius: 2px;"></div>
                                    <div style="width: 60%; height: 12px; background: ${i === 2 ? "linear-gradient(135deg, #92003b 0%, #d5006d 100%)" : "#cbd5e1"}; margin: 0 auto; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);"></div>
                                </div>
                            `).join("")}
                        </div>
                    </div>
                `,
          "pricing-2": `
                    <div style="background: white; height: 100%; padding: 15px;">
                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 4px; margin-bottom: 6px;">
                            ${[1, 2, 3, 4].map(() => `<div style="height: 12px; background: #92003b; border-radius: 2px;"></div>`).join("")}
                        </div>
                        ${[1, 2, 3].map(() => `
                            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 4px; margin-bottom: 4px;">
                                ${[1, 2, 3, 4].map(() => `<div style="height: 8px; background: #f1f5f9; border-radius: 2px;"></div>`).join("")}
                            </div>
                        `).join("")}
                    </div>
                `,
          // Testimonials
          "testimonial-1": `
                    <div style="background: linear-gradient(to bottom, #f8f9fa 0%, white 100%); height: 100%; display: flex; align-items: center; justify-content: center; padding: 20px;">
                        <div style="text-align: center; max-width: 75%; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 1px solid #e6e9ec;">
                            <div style="font-size: 24px; color: #92003b; margin-bottom: 10px;">"</div>
                            <div style="width: 100%; height: 5px; background: #1e293b; margin: 0 auto 4px; border-radius: 2px;"></div>
                            <div style="width: 90%; height: 4px; background: #cbd5e1; margin: 0 auto 4px; border-radius: 2px;"></div>
                            <div style="width: 85%; height: 4px; background: #e2e8f0; margin: 0 auto 10px; border-radius: 2px;"></div>
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 50%; margin: 0 auto 10px; border: 3px solid white; box-shadow: 0 3px 12px rgba(59, 130, 246, 0.3);"></div>
                            <div style="width: 60%; height: 5px; background: #1e293b; margin: 0 auto 3px; border-radius: 2px;"></div>
                            <div style="width: 45%; height: 3px; background: #cbd5e1; margin: 0 auto 6px; border-radius: 2px;"></div>
                            <div style="display: flex; gap: 3px; justify-content: center;">
                                ${[1, 2, 3, 4, 5].map(() => `<div style="width: 8px; height: 8px; background: #fbbf24; border-radius: 1px;"></div>`).join("")}
                            </div>
                        </div>
                    </div>
                `,
          "testimonial-2": `
                    <div style="background: #f8f9fa; height: 100%; padding: 12px;">
                        <div style="text-align: center; margin-bottom: 10px;">
                            <div style="width: 55px; height: 5px; background: #92003b; margin: 0 auto 3px; border-radius: 2px;"></div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            ${[1, 2].map((i) => `
                                <div style="background: white; padding: 12px; border-radius: 8px; border: 1px solid #e6e9ec; box-shadow: 0 2px 10px rgba(0,0,0,0.06);">
                                    <div style="color: #92003b; font-size: 20px; margin-bottom: 6px;">"</div>
                                    <div style="width: 100%; height: 4px; background: #cbd5e1; margin-bottom: 3px; border-radius: 2px;"></div>
                                    <div style="width: 90%; height: 4px; background: #e2e8f0; margin-bottom: 3px; border-radius: 2px;"></div>
                                    <div style="width: 75%; height: 4px; background: #e2e8f0; margin-bottom: 8px; border-radius: 2px;"></div>
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <div style="width: 26px; height: 26px; background: linear-gradient(135deg, ${i === 1 ? "#10b981" : "#f59e0b"} 0%, ${i === 1 ? "#059669" : "#d97706"} 100%); border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 6px rgba(0,0,0,0.15);"></div>
                                        <div style="flex: 1;">
                                            <div style="width: 80%; height: 4px; background: #1e293b; margin-bottom: 2px; border-radius: 1px;"></div>
                                            <div style="width: 60%; height: 3px; background: #cbd5e1; border-radius: 1px;"></div>
                                        </div>
                                    </div>
                                </div>
                            `).join("")}
                        </div>
                    </div>
                `,
          // CTA
          "cta-1": `
                    <div style="background: linear-gradient(135deg, #92003b 0%, #d5006d 100%); height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px;">
                        <div style="width: 70%; height: 10px; background: rgba(255,255,255,0.9); margin-bottom: 6px; border-radius: 3px;"></div>
                        <div style="width: 50%; height: 6px; background: rgba(255,255,255,0.6); margin-bottom: 12px; border-radius: 3px;"></div>
                        <div style="width: 35%; height: 18px; background: white; border-radius: 4px;"></div>
                    </div>
                `,
          "cta-2": `
                    <div style="background: #1e293b; height: 100%; padding: 15px; display: flex; flex-direction: column; justify-content: center;">
                        <div style="width: 60%; height: 8px; background: rgba(255,255,255,0.8); margin-bottom: 6px; border-radius: 3px;"></div>
                        <div style="width: 40%; height: 6px; background: rgba(255,255,255,0.5); margin-bottom: 12px; border-radius: 3px;"></div>
                        <div style="display: flex; gap: 6px;">
                            <div style="flex: 1; height: 16px; background: rgba(255,255,255,0.3); border-radius: 3px;"></div>
                            <div style="width: 30%; height: 16px; background: #92003b; border-radius: 3px;"></div>
                        </div>
                    </div>
                `,
          // Team
          "team-1": `
                    <div style="background: white; height: 100%; padding: 12px;">
                        <div style="text-align: center; margin-bottom: 10px;">
                            <div style="width: 45px; height: 5px; background: #92003b; margin: 0 auto 3px; border-radius: 2px;"></div>
                            <div style="width: 60px; height: 3px; background: #cbd5e1; margin: 0 auto; border-radius: 2px;"></div>
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;">
                            ${[1, 2, 3, 4].map((i) => `
                                <div style="text-align: center; background: #f8f9fa; padding: 10px; border-radius: 8px; border: 1px solid #e6e9ec;">
                                    <div style="width: 32px; height: 32px; background: linear-gradient(135deg, ${["#3b82f6", "#10b981", "#f59e0b", "#ec4899"][i - 1]} 0%, ${["#2563eb", "#059669", "#d97706", "#db2777"][i - 1]} 100%); border-radius: 50%; margin: 0 auto 6px; box-shadow: 0 2px 6px rgba(0,0,0,0.15); border: 2px solid white;"></div>
                                    <div style="width: 85%; height: 5px; background: #1e293b; margin: 0 auto 3px; border-radius: 2px;"></div>
                                    <div style="width: 65%; height: 3px; background: #cbd5e1; margin: 0 auto; border-radius: 2px;"></div>
                                </div>
                            `).join("")}
                        </div>
                    </div>
                `,
          "team-2": `
                    <div style="background: #f8f9fa; height: 100%; padding: 12px;">
                        <div style="text-align: center; margin-bottom: 10px;">
                            <div style="width: 45px; height: 5px; background: #92003b; margin: 0 auto 3px; border-radius: 2px;"></div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                            ${[1, 2].map((i) => `
                                <div style="background: white; padding: 12px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: 1px solid #e6e9ec;">
                                    <div style="width: 44px; height: 44px; background: linear-gradient(135deg, ${i === 1 ? "#3b82f6" : "#ec4899"} 0%, ${i === 1 ? "#2563eb" : "#db2777"} 100%); border-radius: 50%; margin: 0 auto 8px; border: 3px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.2);"></div>
                                    <div style="width: 75%; height: 6px; background: #1e293b; margin: 0 auto 4px; border-radius: 2px;"></div>
                                    <div style="width: 55%; height: 4px; background: #92003b; margin: 0 auto 6px; border-radius: 2px;"></div>
                                    <div style="width: 95%; height: 3px; background: #e2e8f0; margin: 0 auto 2px; border-radius: 1px;"></div>
                                    <div style="width: 90%; height: 3px; background: #e2e8f0; margin: 0 auto; border-radius: 1px;"></div>
                                </div>
                            `).join("")}
                        </div>
                    </div>
                `,
          // Gallery
          "gallery-1": `
                    <div style="background: white; height: 100%; display: grid; grid-template-columns: repeat(3, 1fr); grid-template-rows: repeat(2, 1fr); gap: 4px; padding: 10px;">
                        ${[1, 2, 3, 4, 5, 6].map((i) => `
                            <div style="background: linear-gradient(135deg, ${i % 2 === 0 ? "#fbbf24" : "#ec4899"} 0%, ${i % 2 === 0 ? "#f59e0b" : "#db2777"} 100%); border-radius: 4px;"></div>
                        `).join("")}
                    </div>
                `,
          "gallery-2": `
                    <div style="background: #1e293b; height: 100%; display: grid; grid-template-columns: repeat(4, 1fr); gap: 3px; padding: 10px;">
                        ${[1, 2, 3, 4, 5, 6, 7, 8].map(() => `
                            <div style="background: #334155; border-radius: 3px; aspect-ratio: 1;"></div>
                        `).join("")}
                    </div>
                `,
          // Contact
          "contact-1": `
                    <div style="background: white; height: 100%; padding: 15px;">
                        <div style="width: 60%; height: 8px; background: #92003b; margin-bottom: 10px; border-radius: 3px;"></div>
                        ${[1, 2, 3].map(() => `
                            <div style="width: 100%; height: 12px; background: #f1f5f9; margin-bottom: 6px; border-radius: 3px; border: 1px solid #e2e8f0;"></div>
                        `).join("")}
                        <div style="width: 40%; height: 16px; background: #92003b; margin-top: 8px; border-radius: 3px;"></div>
                    </div>
                `,
          "contact-2": `
                    <div style="background: white; height: 100%; display: grid; grid-template-columns: 1fr 1fr; gap: 8px; padding: 12px;">
                        <div>
                            ${[1, 2].map(() => `
                                <div style="width: 100%; height: 10px; background: #f1f5f9; margin-bottom: 6px; border-radius: 3px; border: 1px solid #e2e8f0;"></div>
                            `).join("")}
                            <div style="width: 50%; height: 14px; background: #92003b; margin-top: 6px; border-radius: 3px;"></div>
                        </div>
                        <div style="background: #e6e9ec; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                            <div style="width: 20px; height: 20px; background: #92003b; border-radius: 50%;"></div>
                        </div>
                    </div>
                `,
          // Footer
          "footer-1": `
                    <div style="background: #1e293b; height: 100%; padding: 15px; display: flex; flex-direction: column; justify-content: space-between;">
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px;">
                            ${[1, 2, 3].map(() => `
                                <div style="width: 100%; height: 4px; background: rgba(255,255,255,0.3); border-radius: 2px;"></div>
                            `).join("")}
                        </div>
                        <div style="width: 60%; height: 4px; background: rgba(255,255,255,0.2); margin: 0 auto; border-radius: 2px;"></div>
                    </div>
                `,
          "footer-2": `
                    <div style="background: #0f172a; height: 100%; display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; padding: 12px;">
                        ${[1, 2, 3, 4].map(() => `
                            <div>
                                <div style="width: 100%; height: 6px; background: rgba(255,255,255,0.6); margin-bottom: 4px; border-radius: 2px;"></div>
                                ${[1, 2, 3].map(() => `
                                    <div style="width: 80%; height: 3px; background: rgba(255,255,255,0.3); margin-bottom: 3px; border-radius: 2px;"></div>
                                `).join("")}
                            </div>
                        `).join("")}
                    </div>
                `
        };
        return thumbnails[templateId] || `
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 100%; display: flex; align-items: center; justify-content: center;">
                    <i class="dashicons dashicons-welcome-widgets-menus" style="font-size: 48px; color: rgba(255, 255, 255, 0.3);"></i>
                </div>
            `;
      },
      /**
       * Show templates modal
       */
      showTemplatesModal: function() {
        const self2 = this;
        console.log("=== LOADING TEMPLATES MODAL ===");
        const loadingHTML = `
                <div class="probuilder-templates-modal-overlay" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.8);
                    z-index: 999999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">
                    <div style="background: white; padding: 40px; border-radius: 12px; text-align: center;">
                        <div style="font-size: 48px; color: #92003b; margin-bottom: 20px;">
                            <i class="dashicons dashicons-update" style="animation: spin 1s linear infinite;"></i>
                        </div>
                        <h3 style="margin: 0; color: #1e293b;">Loading Templates...</h3>
                        <p style="margin: 10px 0 0; color: #64748b; font-size: 14px;">Please wait</p>
                    </div>
                </div>
                <style>
                @keyframes spin {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
                </style>
            `;
        $2("body").append(loadingHTML);
        $2.ajax({
          url: ProBuilderEditor.ajaxurl || ajaxurl,
          type: "POST",
          data: {
            action: "probuilder_get_templates"
          },
          timeout: 15e3,
          // 15 second timeout
          success: function(response) {
            console.log("\u2713 Templates loaded successfully");
            console.log("Response:", response);
            $2(".probuilder-templates-modal-overlay").remove();
            if (response.success && response.data) {
              const allTemplates = response.data.prebuilt || [];
              console.log("\u2713 Total templates:", allTemplates.length);
              const templatesByCategory = {};
              allTemplates.forEach((template) => {
                const cat = template.category || "other";
                if (!templatesByCategory[cat]) {
                  templatesByCategory[cat] = [];
                }
                templatesByCategory[cat].push(template);
              });
              console.log("\u2713 Templates grouped by category:", Object.keys(templatesByCategory));
              self2.buildTemplatesModal(templatesByCategory, allTemplates);
            } else {
              console.error("\u274C Failed to load templates");
              self2.showErrorModal("Failed to load templates. Please refresh and try again.");
            }
          },
          error: function(xhr, status, error) {
            console.error("\u274C AJAX Error:", status, error);
            console.error("Response:", xhr.responseText);
            $2(".probuilder-templates-modal-overlay").remove();
            let errorMsg = "Error loading templates";
            if (status === "timeout") {
              errorMsg = "Templates loading timed out. Please try again.";
            } else if (xhr.responseText) {
              errorMsg = "Server error: " + xhr.responseText.substring(0, 100);
            }
            self2.showErrorModal(errorMsg);
          }
        });
      },
      /**
       * Show error modal
       */
      showErrorModal: function(errorMessage) {
        const errorHTML = `
                <div class="probuilder-templates-modal-overlay" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.8);
                    z-index: 999999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">
                    <div style="
                        background: white;
                        padding: 40px;
                        border-radius: 12px;
                        text-align: center;
                        max-width: 500px;
                        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
                    ">
                        <div style="font-size: 64px; color: #dc2626; margin-bottom: 20px;">
                            <i class="dashicons dashicons-warning"></i>
                        </div>
                        <h3 style="margin: 0 0 15px; color: #1e293b; font-size: 20px;">Error Loading Templates</h3>
                        <p style="margin: 0 0 25px; color: #64748b; font-size: 14px; line-height: 1.6;">${errorMessage}</p>
                        <button onclick="jQuery('.probuilder-templates-modal-overlay').remove();" style="
                            background: #92003b;
                            color: white;
                            border: none;
                            padding: 12px 30px;
                            border-radius: 6px;
                            font-size: 14px;
                            font-weight: 600;
                            cursor: pointer;
                            transition: all 0.2s;
                        " onmouseover="this.style.background='#d5006d'" onmouseout="this.style.background='#92003b'">
                            Close
                        </button>
                    </div>
                </div>
            `;
        $2("body").append(errorHTML);
      },
      /**
       * Build templates modal UI
       */
      buildTemplatesModal: function(templatesByCategory, allTemplates) {
        const self2 = this;
        const categoryMeta = {
          "pages": { title: "Full Page Templates", icon: "dashicons-welcome-widgets-menus" },
          "hero": { title: "Hero Sections", icon: "dashicons-welcome-view-site" },
          "features": { title: "Features", icon: "dashicons-star-filled" },
          "pricing": { title: "Pricing Tables", icon: "dashicons-cart" },
          "testimonials": { title: "Testimonials", icon: "dashicons-format-quote" },
          "cta": { title: "Call to Action", icon: "dashicons-megaphone" },
          "team": { title: "Team Sections", icon: "dashicons-groups" },
          "gallery": { title: "Galleries", icon: "dashicons-format-gallery" },
          "contact": { title: "Contact Sections", icon: "dashicons-email" },
          "footer": { title: "Footers", icon: "dashicons-align-full-width" },
          "other": { title: "Other Templates", icon: "dashicons-admin-page" }
        };
        let modalHTML = `
                <div class="probuilder-templates-modal-overlay" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.8);
                    z-index: 999999;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">
                    <div class="probuilder-templates-modal" style="
                        background: #ffffff;
                        border-radius: 12px;
                        width: 95%;
                        max-width: 1200px;
                        max-height: 90vh;
                        display: flex;
                        flex-direction: column;
                        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
                        overflow: hidden;
                    ">
                        <!-- Header -->
                        <div style="
                            padding: 25px 30px;
                            border-bottom: 1px solid #e6e9ec;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            background: linear-gradient(135deg, #92003b 0%, #d5006d 100%);
                        ">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <i class="dashicons dashicons-layout" style="color: #ffffff; font-size: 32px;"></i>
                                <div>
                                    <h2 style="margin: 0; font-size: 24px; font-weight: 700; color: #ffffff;">Template Library</h2>
                                    <p style="margin: 5px 0 0 0; font-size: 13px; color: rgba(255, 255, 255, 0.8);">\u2728 ${allTemplates.length} Professional Templates Available</p>
                                </div>
                            </div>
                            <button class="probuilder-templates-close" style="
                                background: rgba(255, 255, 255, 0.2);
                                border: none;
                                color: #ffffff;
                                width: 36px;
                                height: 36px;
                                border-radius: 50%;
                                cursor: pointer;
                                font-size: 24px;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                transition: all 0.2s;
                            " onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">&times;</button>
                        </div>
                        
                        <!-- Search Box -->
                        <div style="padding: 20px 30px; border-bottom: 1px solid #e6e9ec; background: white;">
                            <div style="position: relative;">
                                <i class="dashicons dashicons-search" style="
                                    position: absolute;
                                    left: 12px;
                                    top: 50%;
                                    transform: translateY(-50%);
                                    color: #71717a;
                                    font-size: 18px;
                                "></i>
                                <input type="text" id="template-search" placeholder="Search templates..." style="
                                    width: 100%;
                                    padding: 12px 12px 12px 40px;
                                    border: 2px solid #e6e9ec;
                                    border-radius: 6px;
                                    font-size: 14px;
                                    color: #1e293b;
                                    transition: all 0.2s;
                                    outline: none;
                                " onfocus="this.style.borderColor='#92003b'; this.style.boxShadow='0 0 0 3px rgba(146, 0, 59, 0.1)'" onblur="this.style.borderColor='#e6e9ec'; this.style.boxShadow='none'">
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div id="templates-content" style="
                            flex: 1;
                            overflow-y: auto;
                            padding: 30px;
                            background: #f8f9fa;
                        ">
            `;
        Object.keys(templatesByCategory).forEach((categoryKey) => {
          const categoryTemplates = templatesByCategory[categoryKey];
          const catMeta = categoryMeta[categoryKey] || { title: categoryKey, icon: "dashicons-admin-page" };
          modalHTML += `
                    <div class="template-category" style="margin-bottom: 40px;">
                        <div style="
                            display: flex;
                            align-items: center;
                            gap: 10px;
                            margin-bottom: 20px;
                            padding-bottom: 12px;
                            border-bottom: 2px solid #92003b;
                        ">
                            <i class="dashicons ${catMeta.icon}" style="font-size: 24px; color: #92003b;"></i>
                            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #1e293b;">${catMeta.title}</h3>
                            <span style="
                                background: #92003b;
                                color: #ffffff;
                                padding: 2px 10px;
                                border-radius: 12px;
                                font-size: 11px;
                                font-weight: 600;
                            ">${categoryTemplates.length}</span>
                        </div>
                        
                        <div style="
                            display: grid;
                            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                            gap: 20px;
                        ">
                `;
          categoryTemplates.forEach((template) => {
            const thumbnail = template.thumbnail || "data:image/svg+xml;base64," + btoa(`<svg width="300" height="200" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="200" fill="#f3f4f6"/><text x="150" y="100" text-anchor="middle" fill="#9ca3af" font-size="16">Template</text></svg>`);
            modalHTML += `
                        <div class="template-card" data-template-id="${template.id}" data-template-name="${template.name.toLowerCase()}" data-template-category="${catMeta.title.toLowerCase()}" style="
                            background: #ffffff;
                            border: 2px solid #e6e9ec;
                            border-radius: 8px;
                            overflow: hidden;
                            cursor: pointer;
                            transition: all 0.2s;
                            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                        " onmouseover="this.style.transform='translateY(-4px)'; this.style.borderColor='#92003b'; this.style.boxShadow='0 8px 20px rgba(146, 0, 59, 0.15)'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='#e6e9ec'; this.style.boxShadow='0 2px 8px rgba(0, 0, 0, 0.05)'">
                            <!-- Preview Thumbnail -->
                            <div style="
                                height: 160px;
                                position: relative;
                                overflow: hidden;
                                border-bottom: 1px solid #e6e9ec;
                            ">
                                <img src="${thumbnail}" style="width: 100%; height: 100%; object-fit: cover;" />
                                <div style="
                                    position: absolute;
                                    top: 10px;
                                    right: 10px;
                                    background: rgba(146, 0, 59, 0.95);
                                    color: #ffffff;
                                    padding: 4px 10px;
                                    border-radius: 4px;
                                    font-size: 10px;
                                    font-weight: 700;
                                    text-transform: uppercase;
                                ">NEW</div>
                            </div>
                            
                            <!-- Content -->
                            <div style="padding: 15px;">
                                <h4 style="margin: 0 0 8px 0; font-size: 15px; font-weight: 700; color: #1e293b;">${template.name}</h4>
                                <button class="template-insert-btn" data-template-id="${template.id}" style="
                                    width: 100%;
                                    background: #92003b;
                                    color: #ffffff;
                                    border: none;
                                    padding: 8px 16px;
                                    border-radius: 4px;
                                    font-size: 12px;
                                    font-weight: 600;
                                    cursor: pointer;
                                    transition: all 0.2s;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    gap: 6px;
                                " onmouseover="this.style.background='#d5006d'" onmouseout="this.style.background='#92003b'">
                                    <i class="dashicons dashicons-download" style="font-size: 16px;"></i>
                                    Insert Template
                                </button>
                            </div>
                        </div>
                    `;
          });
          modalHTML += `
                        </div>
                    </div>
                `;
        });
        modalHTML += `
                        </div>
                    </div>
                </div>
            `;
        $2("body").append(modalHTML);
        $2(".probuilder-templates-close, .probuilder-templates-modal-overlay").on("click", function(e) {
          if (e.target === this) {
            $2(".probuilder-templates-modal-overlay").remove();
          }
        });
        $2("#template-search").on("input", function() {
          const searchTerm = $2(this).val().toLowerCase().trim();
          if (searchTerm === "") {
            $2(".template-card").show();
            $2(".template-category").show();
          } else {
            $2(".template-card").hide();
            $2(".template-card").each(function() {
              const $card = $2(this);
              const name = $card.data("template-name") || "";
              const category = $card.data("template-category") || "";
              const preview = $card.data("template-preview") || "";
              if (name.includes(searchTerm) || category.includes(searchTerm) || preview.includes(searchTerm)) {
                $card.show();
              }
            });
            $2(".template-category").each(function() {
              const $category = $2(this);
              const visibleCards = $category.find(".template-card:visible").length;
              if (visibleCards > 0) {
                $category.show();
              } else {
                $category.hide();
              }
            });
          }
        });
        $2(".template-insert-btn").on("click", function(e) {
          e.stopPropagation();
          const $btn = $2(this);
          const templateId = $btn.data("template-id");
          const template = allTemplates.find((t) => t.id === templateId);
          console.log("=== INSERTING TEMPLATE ===");
          console.log("Template ID:", templateId);
          if (!template) {
            console.error("\u274C Template not found:", templateId);
            alert("Error: Template not found");
            return;
          }
          const originalHTML = $btn.html();
          $btn.html('<i class="dashicons dashicons-update" style="font-size: 16px; animation: spin 1s linear infinite;"></i> Loading...').prop("disabled", true);
          $2.ajax({
            url: ProBuilderEditor.ajaxurl || ajaxurl,
            type: "POST",
            data: {
              action: "probuilder_get_template_data",
              template_id: templateId
            },
            timeout: 3e4,
            // 30 second timeout for large templates
            success: function(response) {
              console.log("\u2713 Template data loaded");
              if (response.success && response.data && response.data.data) {
                const templateData = response.data.data;
                console.log("Template elements:", Array.isArray(templateData) ? templateData.length : 1);
                console.log("Template type:", template.type);
                $2(".probuilder-templates-modal-overlay").remove();
                if (template.type === "page") {
                  console.log("\u{1F5D1}\uFE0F Full page template - clearing canvas first");
                  self2.clearCanvas();
                } else {
                  console.log("\u2795 Section template - adding to existing content");
                }
                if (Array.isArray(templateData)) {
                  console.log("Inserting", templateData.length, "elements...");
                  templateData.forEach(function(elementData, index) {
                    console.log(`Inserting element ${index + 1}:`, elementData.widgetType);
                    console.log("   Children count:", elementData.children ? elementData.children.length : 0);
                    if (elementData.children && elementData.children.length > 0) {
                      console.log("   First child:", elementData.children[0]);
                    }
                    if (elementData.widgetType) {
                      const newElement = self2.cloneElementData(elementData);
                      console.log("\u2705 Cloned element:", newElement.widgetType, "with", newElement.children.length, "children");
                      self2.elements.push(newElement);
                      self2.renderElement(newElement);
                    }
                  });
                } else {
                  console.log("Inserting single element...");
                  if (templateData.widgetType) {
                    const newElement = self2.cloneElementData(templateData);
                    self2.elements.push(newElement);
                    self2.renderElement(newElement);
                  }
                }
                self2.updateEmptyState();
                self2.makeContainersDroppable();
                self2.saveHistory();
                const action = template.type === "page" ? "inserted" : "added";
                self2.showToast("\u2713 Template " + action + ": " + template.name);
                $2('.probuilder-tab-btn[data-tab="widgets"]').click();
              } else {
                console.error("\u274C Invalid template data response");
                alert("Error: Could not load template data");
                $btn.html(originalHTML).prop("disabled", false);
              }
            },
            error: function(xhr, status, error) {
              console.error("\u274C AJAX Error loading template data:", status, error);
              let errorMsg = "Error loading template";
              if (status === "timeout") {
                errorMsg = "Template loading timed out. This template might be too large.";
              }
              alert(errorMsg);
              $btn.html(originalHTML).prop("disabled", false);
            }
          });
        });
      },
      /**
       * Import template data
       */
      importTemplate: function(templateId) {
        const templateData = this.getTemplateData(templateId);
        if (!templateData || templateData.length === 0) {
          alert("Template data not found for: " + templateId);
          return;
        }
        console.log("Importing template:", templateId, "with", templateData.length, "elements");
        templateData.forEach((elementData, index) => {
          setTimeout(() => {
            const widget2 = this.widgets.find((w) => w.name === elementData.widgetType);
            if (widget2) {
              const element2 = {
                id: "element-" + Date.now() + "-" + Math.random().toString(36).substr(2, 9),
                widgetType: elementData.widgetType,
                settings: Object.assign({}, this.getDefaultSettings(widget2), elementData.settings || {}),
                children: elementData.children || []
              };
              this.elements.push(element2);
              this.renderElement(element2);
            }
          }, index * 50);
        });
        setTimeout(() => {
          this.updateEmptyState();
          this.makeContainersDroppable();
          if (this.elements.length > 0) {
            setTimeout(() => {
              this.selectElement(this.elements[this.elements.length - templateData.length]);
            }, 100);
          }
          console.log("\u2705 Template imported successfully!");
        }, templateData.length * 50 + 100);
      },
      /**
       * Get template data
       */
      getTemplateData: function(templateId) {
        const templates = {
          // Hero Templates
          "hero-1": [
            {
              widgetType: "container",
              settings: {
                columns: "1",
                background_type: "gradient",
                background_gradient: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
                min_height: 500,
                padding: { top: 80, right: 40, bottom: 80, left: 40 }
              },
              children: [
                {
                  widgetType: "heading",
                  settings: {
                    title: "Build Amazing Websites",
                    html_tag: "h1",
                    font_size: 48,
                    color: "#ffffff",
                    align: "center",
                    font_weight: 700
                  }
                },
                {
                  widgetType: "text",
                  settings: {
                    content: "Create beautiful, professional websites with our powerful page builder. No coding required.",
                    color: "#ffffff",
                    font_size: 18,
                    text_align: "center"
                  }
                },
                {
                  widgetType: "button",
                  settings: {
                    text: "Get Started",
                    align: "center",
                    size: "large",
                    bg_color: "#ffffff",
                    text_color: "#667eea"
                  }
                }
              ]
            }
          ],
          "hero-2": [
            {
              widgetType: "container",
              settings: {
                columns: "1",
                background_type: "color",
                background_color: "#1e293b",
                min_height: 450,
                padding: { top: 60, right: 40, bottom: 60, left: 40 }
              },
              children: [
                {
                  widgetType: "heading",
                  settings: {
                    title: "Welcome to Our Platform",
                    html_tag: "h1",
                    font_size: 42,
                    color: "#ffffff",
                    align: "center"
                  }
                },
                {
                  widgetType: "text",
                  settings: {
                    content: "Discover the best solution for your business needs.",
                    color: "#cbd5e1",
                    font_size: 16,
                    text_align: "center"
                  }
                },
                {
                  widgetType: "button",
                  settings: {
                    text: "Learn More",
                    align: "center",
                    bg_color: "#92003b"
                  }
                }
              ]
            }
          ],
          "features-1": [
            {
              widgetType: "heading",
              settings: {
                title: "Our Features",
                html_tag: "h2",
                font_size: 36,
                align: "center",
                color: "#1e293b"
              }
            },
            {
              widgetType: "container",
              settings: {
                columns: "3",
                column_gap: 30,
                padding: { top: 40, right: 20, bottom: 40, left: 20 }
              },
              children: [
                {
                  widgetType: "icon-box",
                  settings: {
                    icon: "fa fa-rocket",
                    title: "Fast Performance",
                    description: "Lightning-fast load times and optimized performance."
                  }
                },
                {
                  widgetType: "icon-box",
                  settings: {
                    icon: "fa fa-shield",
                    title: "Secure & Safe",
                    description: "Enterprise-level security to protect your data."
                  }
                },
                {
                  widgetType: "icon-box",
                  settings: {
                    icon: "fa fa-heart",
                    title: "Easy to Use",
                    description: "Intuitive interface that anyone can master."
                  }
                }
              ]
            }
          ],
          "pricing-1": [
            {
              widgetType: "heading",
              settings: {
                title: "Choose Your Plan",
                html_tag: "h2",
                font_size: 36,
                align: "center"
              }
            },
            {
              widgetType: "container",
              settings: {
                columns: "3",
                column_gap: 25,
                background_color: "#f8f9fa",
                padding: { top: 50, right: 30, bottom: 50, left: 30 }
              },
              children: [
                {
                  widgetType: "pricing-table",
                  settings: {
                    title: "Basic",
                    price: "9",
                    period: "per month",
                    features: [
                      { text: "10 GB Storage" },
                      { text: "5 Users" },
                      { text: "Email Support" }
                    ],
                    button_text: "Get Started"
                  }
                },
                {
                  widgetType: "pricing-table",
                  settings: {
                    title: "Pro",
                    price: "29",
                    period: "per month",
                    featured: "yes",
                    features: [
                      { text: "100 GB Storage" },
                      { text: "Unlimited Users" },
                      { text: "Priority Support" },
                      { text: "Advanced Features" }
                    ],
                    button_text: "Get Started"
                  }
                },
                {
                  widgetType: "pricing-table",
                  settings: {
                    title: "Enterprise",
                    price: "99",
                    period: "per month",
                    features: [
                      { text: "Unlimited Storage" },
                      { text: "Unlimited Users" },
                      { text: "24/7 Support" },
                      { text: "Custom Solutions" }
                    ],
                    button_text: "Contact Sales"
                  }
                }
              ]
            }
          ],
          "page-landing": [
            // Hero Section
            {
              widgetType: "container",
              settings: {
                columns: "1",
                background_type: "gradient",
                background_gradient: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
                min_height: 600,
                padding: { top: 100, right: 40, bottom: 100, left: 40 }
              },
              children: [
                {
                  widgetType: "heading",
                  settings: {
                    title: "Build Your Dream Website Today",
                    html_tag: "h1",
                    font_size: 56,
                    color: "#ffffff",
                    align: "center",
                    font_weight: 700
                  }
                },
                {
                  widgetType: "text",
                  settings: {
                    content: "The easiest way to create professional, stunning websites without any coding knowledge. Start building in minutes!",
                    color: "#ffffff",
                    font_size: 20,
                    text_align: "center"
                  }
                },
                {
                  widgetType: "spacer",
                  settings: { height: 30 }
                },
                {
                  widgetType: "button",
                  settings: {
                    text: "Start Free Trial",
                    align: "center",
                    size: "large",
                    bg_color: "#ffffff",
                    text_color: "#667eea"
                  }
                }
              ]
            },
            // Features Section
            {
              widgetType: "spacer",
              settings: { height: 60 }
            },
            {
              widgetType: "heading",
              settings: {
                title: "Powerful Features",
                html_tag: "h2",
                font_size: 42,
                align: "center",
                color: "#1e293b"
              }
            },
            {
              widgetType: "text",
              settings: {
                content: "Everything you need to build amazing websites",
                font_size: 18,
                text_align: "center",
                color: "#64748b"
              }
            },
            {
              widgetType: "spacer",
              settings: { height: 40 }
            },
            {
              widgetType: "container",
              settings: {
                columns: "3",
                column_gap: 30,
                padding: { top: 20, right: 30, bottom: 20, left: 30 }
              },
              children: [
                {
                  widgetType: "icon-box",
                  settings: {
                    icon: "fa fa-rocket",
                    title: "Lightning Fast",
                    description: "Build pages at incredible speed with our intuitive drag-and-drop interface",
                    icon_size: 48,
                    icon_color: "#667eea"
                  }
                },
                {
                  widgetType: "icon-box",
                  settings: {
                    icon: "fa fa-paint-brush",
                    title: "Beautiful Designs",
                    description: "Access hundreds of pre-made templates and design elements",
                    icon_size: 48,
                    icon_color: "#667eea"
                  }
                },
                {
                  widgetType: "icon-box",
                  settings: {
                    icon: "fa fa-mobile",
                    title: "Mobile Ready",
                    description: "Fully responsive designs that look perfect on all devices",
                    icon_size: 48,
                    icon_color: "#667eea"
                  }
                }
              ]
            },
            // Stats Counter
            {
              widgetType: "spacer",
              settings: { height: 60 }
            },
            {
              widgetType: "container",
              settings: {
                columns: "4",
                column_gap: 30,
                background_color: "#f8f9fa",
                padding: { top: 60, right: 30, bottom: 60, left: 30 }
              },
              children: [
                {
                  widgetType: "counter",
                  settings: {
                    number: "10000",
                    title: "Happy Customers",
                    suffix: "+"
                  }
                },
                {
                  widgetType: "counter",
                  settings: {
                    number: "50000",
                    title: "Websites Created",
                    suffix: "+"
                  }
                },
                {
                  widgetType: "counter",
                  settings: {
                    number: "99",
                    title: "Satisfaction Rate",
                    suffix: "%"
                  }
                },
                {
                  widgetType: "counter",
                  settings: {
                    number: "24",
                    title: "Support Available",
                    suffix: "/7"
                  }
                }
              ]
            },
            // Pricing Section
            {
              widgetType: "spacer",
              settings: { height: 80 }
            },
            {
              widgetType: "heading",
              settings: {
                title: "Choose Your Plan",
                html_tag: "h2",
                font_size: 42,
                align: "center",
                color: "#1e293b"
              }
            },
            {
              widgetType: "text",
              settings: {
                content: "Select the perfect plan for your needs",
                font_size: 18,
                text_align: "center",
                color: "#64748b"
              }
            },
            {
              widgetType: "spacer",
              settings: { height: 40 }
            },
            {
              widgetType: "container",
              settings: {
                columns: "3",
                column_gap: 30,
                padding: { top: 20, right: 30, bottom: 20, left: 30 }
              },
              children: [
                {
                  widgetType: "pricing-table",
                  settings: {
                    title: "Starter",
                    price: "0",
                    period: "forever",
                    features: [
                      { text: "5 Pages" },
                      { text: "Basic Templates" },
                      { text: "Email Support" }
                    ],
                    button_text: "Get Started Free"
                  }
                },
                {
                  widgetType: "pricing-table",
                  settings: {
                    title: "Professional",
                    price: "29",
                    period: "per month",
                    featured: "yes",
                    features: [
                      { text: "Unlimited Pages" },
                      { text: "All Templates" },
                      { text: "Priority Support" },
                      { text: "Advanced Widgets" }
                    ],
                    button_text: "Start Free Trial"
                  }
                },
                {
                  widgetType: "pricing-table",
                  settings: {
                    title: "Agency",
                    price: "99",
                    period: "per month",
                    features: [
                      { text: "Everything in Pro" },
                      { text: "White Label" },
                      { text: "24/7 Support" },
                      { text: "Client Management" }
                    ],
                    button_text: "Contact Sales"
                  }
                }
              ]
            },
            // Testimonials
            {
              widgetType: "spacer",
              settings: { height: 80 }
            },
            {
              widgetType: "heading",
              settings: {
                title: "What Our Customers Say",
                html_tag: "h2",
                font_size: 42,
                align: "center",
                color: "#1e293b"
              }
            },
            {
              widgetType: "spacer",
              settings: { height: 40 }
            },
            {
              widgetType: "container",
              settings: {
                columns: "3",
                column_gap: 30,
                padding: { top: 20, right: 30, bottom: 20, left: 30 }
              },
              children: [
                {
                  widgetType: "testimonial",
                  settings: {
                    name: "Sarah Johnson",
                    position: "CEO, TechCorp",
                    content: "This page builder has completely transformed our workflow. We can now create stunning websites in a fraction of the time!",
                    rating: 5
                  }
                },
                {
                  widgetType: "testimonial",
                  settings: {
                    name: "Michael Chen",
                    position: "Designer",
                    content: "The design flexibility is incredible. I can bring any vision to life without touching code.",
                    rating: 5
                  }
                },
                {
                  widgetType: "testimonial",
                  settings: {
                    name: "Emily Davis",
                    position: "Marketing Director",
                    content: "Our conversion rates increased by 40% after redesigning our landing pages with this tool!",
                    rating: 5
                  }
                }
              ]
            },
            // Final CTA
            {
              widgetType: "spacer",
              settings: { height: 80 }
            },
            {
              widgetType: "call-to-action",
              settings: {
                title: "Ready to Transform Your Website?",
                description: "Join over 10,000 businesses creating stunning websites with our page builder",
                button_text: "Start Building Now",
                bg_color: "#92003b"
              }
            }
          ],
          "page-about": [
            // Hero/Title
            {
              widgetType: "container",
              settings: {
                columns: "1",
                background_color: "#f8f9fa",
                min_height: 250,
                padding: { top: 60, right: 40, bottom: 60, left: 40 }
              },
              children: [
                {
                  widgetType: "heading",
                  settings: {
                    title: "About Our Company",
                    html_tag: "h1",
                    font_size: 48,
                    align: "center",
                    color: "#1e293b"
                  }
                },
                {
                  widgetType: "text",
                  settings: {
                    content: "Building the future of web design, one page at a time",
                    font_size: 18,
                    text_align: "center",
                    color: "#64748b"
                  }
                }
              ]
            },
            // Story Section
            {
              widgetType: "spacer",
              settings: { height: 60 }
            },
            {
              widgetType: "container",
              settings: {
                columns: "2",
                column_gap: 50,
                padding: { top: 20, right: 40, bottom: 20, left: 40 }
              },
              children: [
                {
                  widgetType: "image",
                  settings: {
                    url: "https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=600",
                    align: "center"
                  }
                },
                {
                  widgetType: "container",
                  settings: {
                    columns: "1"
                  },
                  children: [
                    {
                      widgetType: "heading",
                      settings: {
                        title: "Our Story",
                        html_tag: "h2",
                        font_size: 36,
                        color: "#1e293b"
                      }
                    },
                    {
                      widgetType: "text",
                      settings: {
                        content: "<p>Founded in 2020, we started with a simple mission: make professional web design accessible to everyone.</p><p>Today, we serve over 10,000 customers worldwide, helping them create stunning websites without writing a single line of code.</p><p>Our team is passionate about innovation, design excellence, and customer success.</p>"
                      }
                    },
                    {
                      widgetType: "button",
                      settings: {
                        text: "Learn More",
                        bg_color: "#92003b"
                      }
                    }
                  ]
                }
              ]
            },
            // Values Section
            {
              widgetType: "spacer",
              settings: { height: 80 }
            },
            {
              widgetType: "heading",
              settings: {
                title: "Our Core Values",
                html_tag: "h2",
                font_size: 38,
                align: "center",
                color: "#1e293b"
              }
            },
            {
              widgetType: "spacer",
              settings: { height: 40 }
            },
            {
              widgetType: "container",
              settings: {
                columns: "3",
                column_gap: 30,
                padding: { top: 20, right: 30, bottom: 20, left: 30 }
              },
              children: [
                {
                  widgetType: "info-box",
                  settings: {
                    icon_type: "icon",
                    icon: "fa fa-lightbulb",
                    title: "Innovation",
                    description: "We constantly push boundaries to deliver cutting-edge solutions",
                    layout: "vertical",
                    icon_size: 80
                  }
                },
                {
                  widgetType: "info-box",
                  settings: {
                    icon_type: "icon",
                    icon: "fa fa-users",
                    title: "Customer First",
                    description: "Your success is our success. We put customers at the heart of everything",
                    layout: "vertical",
                    icon_size: 80
                  }
                },
                {
                  widgetType: "info-box",
                  settings: {
                    icon_type: "icon",
                    icon: "fa fa-star",
                    title: "Excellence",
                    description: "We strive for perfection in every detail of our products",
                    layout: "vertical",
                    icon_size: 80
                  }
                }
              ]
            },
            // Team Section
            {
              widgetType: "spacer",
              settings: { height: 80 }
            },
            {
              widgetType: "heading",
              settings: {
                title: "Meet Our Team",
                html_tag: "h2",
                font_size: 38,
                align: "center",
                color: "#1e293b"
              }
            },
            {
              widgetType: "text",
              settings: {
                content: "The talented people behind our success",
                font_size: 16,
                text_align: "center",
                color: "#64748b"
              }
            },
            {
              widgetType: "spacer",
              settings: { height: 40 }
            },
            {
              widgetType: "container",
              settings: {
                columns: "4",
                column_gap: 25,
                padding: { top: 20, right: 20, bottom: 20, left: 20 }
              },
              children: [
                {
                  widgetType: "team-member",
                  settings: {
                    name: "John Smith",
                    position: "CEO & Founder",
                    layout: "center"
                  }
                },
                {
                  widgetType: "team-member",
                  settings: {
                    name: "Sarah Johnson",
                    position: "CTO",
                    layout: "center"
                  }
                },
                {
                  widgetType: "team-member",
                  settings: {
                    name: "Mike Davis",
                    position: "Head of Design",
                    layout: "center"
                  }
                },
                {
                  widgetType: "team-member",
                  settings: {
                    name: "Lisa Chen",
                    position: "Lead Developer",
                    layout: "center"
                  }
                }
              ]
            },
            // Stats
            {
              widgetType: "spacer",
              settings: { height: 80 }
            },
            {
              widgetType: "container",
              settings: {
                columns: "4",
                column_gap: 30,
                background_color: "#1e293b",
                padding: { top: 60, right: 30, bottom: 60, left: 30 }
              },
              children: [
                {
                  widgetType: "counter",
                  settings: {
                    number: "5",
                    title: "Years in Business",
                    suffix: "+",
                    number_color: "#ffffff",
                    title_color: "#cbd5e1"
                  }
                },
                {
                  widgetType: "counter",
                  settings: {
                    number: "150",
                    title: "Team Members",
                    suffix: "+",
                    number_color: "#ffffff",
                    title_color: "#cbd5e1"
                  }
                },
                {
                  widgetType: "counter",
                  settings: {
                    number: "1000",
                    title: "Projects Completed",
                    suffix: "+",
                    number_color: "#ffffff",
                    title_color: "#cbd5e1"
                  }
                },
                {
                  widgetType: "counter",
                  settings: {
                    number: "50",
                    title: "Countries",
                    suffix: "+",
                    number_color: "#ffffff",
                    title_color: "#cbd5e1"
                  }
                }
              ]
            }
          ],
          "page-services": [
            // Hero
            {
              widgetType: "container",
              settings: {
                columns: "1",
                background_type: "gradient",
                background_gradient: "linear-gradient(135deg, #92003b 0%, #d5006d 100%)",
                min_height: 400,
                padding: { top: 80, right: 40, bottom: 80, left: 40 }
              },
              children: [
                {
                  widgetType: "heading",
                  settings: {
                    title: "Our Services",
                    html_tag: "h1",
                    font_size: 52,
                    color: "#ffffff",
                    align: "center",
                    font_weight: 700
                  }
                },
                {
                  widgetType: "text",
                  settings: {
                    content: "Professional solutions tailored to your business needs",
                    color: "#ffffff",
                    font_size: 20,
                    text_align: "center"
                  }
                }
              ]
            },
            // Services Grid
            {
              widgetType: "spacer",
              settings: { height: 80 }
            },
            {
              widgetType: "heading",
              settings: {
                title: "What We Offer",
                html_tag: "h2",
                font_size: 38,
                align: "center",
                color: "#1e293b"
              }
            },
            {
              widgetType: "spacer",
              settings: { height: 40 }
            },
            {
              widgetType: "container",
              settings: {
                columns: "3",
                column_gap: 30,
                padding: { top: 20, right: 30, bottom: 20, left: 30 }
              },
              children: [
                {
                  widgetType: "info-box",
                  settings: {
                    icon_type: "icon",
                    icon: "fa fa-code",
                    title: "Web Development",
                    description: "Custom website development using the latest technologies and best practices",
                    button_text: "Learn More",
                    layout: "vertical",
                    icon_size: 70
                  }
                },
                {
                  widgetType: "info-box",
                  settings: {
                    icon_type: "icon",
                    icon: "fa fa-paint-brush",
                    title: "UI/UX Design",
                    description: "Beautiful, user-friendly interface designs that convert visitors",
                    button_text: "Learn More",
                    layout: "vertical",
                    icon_size: 70
                  }
                },
                {
                  widgetType: "info-box",
                  settings: {
                    icon_type: "icon",
                    icon: "fa fa-chart-line",
                    title: "Digital Marketing",
                    description: "Grow your business with data-driven marketing strategies",
                    button_text: "Learn More",
                    layout: "vertical",
                    icon_size: 70
                  }
                }
              ]
            },
            // Second Row of Services
            {
              widgetType: "spacer",
              settings: { height: 30 }
            },
            {
              widgetType: "container",
              settings: {
                columns: "3",
                column_gap: 30,
                padding: { top: 20, right: 30, bottom: 20, left: 30 }
              },
              children: [
                {
                  widgetType: "info-box",
                  settings: {
                    icon_type: "icon",
                    icon: "fa fa-search",
                    title: "SEO Optimization",
                    description: "Rank higher on search engines and drive organic traffic",
                    button_text: "Learn More",
                    layout: "vertical",
                    icon_size: 70
                  }
                },
                {
                  widgetType: "info-box",
                  settings: {
                    icon_type: "icon",
                    icon: "fa fa-shield",
                    title: "Security & Maintenance",
                    description: "Keep your website secure and running smoothly 24/7",
                    button_text: "Learn More",
                    layout: "vertical",
                    icon_size: 70
                  }
                },
                {
                  widgetType: "info-box",
                  settings: {
                    icon_type: "icon",
                    icon: "fa fa-headset",
                    title: "Support & Training",
                    description: "Expert support and comprehensive training for your team",
                    button_text: "Learn More",
                    layout: "vertical",
                    icon_size: 70
                  }
                }
              ]
            },
            // Process Section
            {
              widgetType: "spacer",
              settings: { height: 80 }
            },
            {
              widgetType: "container",
              settings: {
                columns: "1",
                background_color: "#f8f9fa",
                padding: { top: 60, right: 40, bottom: 60, left: 40 }
              },
              children: [
                {
                  widgetType: "heading",
                  settings: {
                    title: "Our Process",
                    html_tag: "h2",
                    font_size: 38,
                    align: "center",
                    color: "#1e293b"
                  }
                },
                {
                  widgetType: "text",
                  settings: {
                    content: "Simple, streamlined, and effective",
                    font_size: 16,
                    text_align: "center",
                    color: "#64748b"
                  }
                }
              ]
            },
            {
              widgetType: "container",
              settings: {
                columns: "4",
                column_gap: 25,
                background_color: "#f8f9fa",
                padding: { top: 20, right: 30, bottom: 40, left: 30 }
              },
              children: [
                {
                  widgetType: "info-box",
                  settings: {
                    icon_type: "number",
                    number: "01",
                    title: "Consultation",
                    description: "We discuss your goals and requirements",
                    layout: "vertical"
                  }
                },
                {
                  widgetType: "info-box",
                  settings: {
                    icon_type: "number",
                    number: "02",
                    title: "Planning",
                    description: "We create a detailed project roadmap",
                    layout: "vertical"
                  }
                },
                {
                  widgetType: "info-box",
                  settings: {
                    icon_type: "number",
                    number: "03",
                    title: "Development",
                    description: "We bring your vision to life",
                    layout: "vertical"
                  }
                },
                {
                  widgetType: "info-box",
                  settings: {
                    icon_type: "number",
                    number: "04",
                    title: "Launch",
                    description: "We deploy and support your project",
                    layout: "vertical"
                  }
                }
              ]
            },
            // CTA
            {
              widgetType: "spacer",
              settings: { height: 60 }
            },
            {
              widgetType: "call-to-action",
              settings: {
                title: "Ready to Start Your Project?",
                description: "Contact us today for a free consultation",
                button_text: "Get in Touch",
                bg_color: "#667eea"
              }
            }
          ],
          "page-portfolio": [
            // Hero
            {
              widgetType: "container",
              settings: {
                columns: "1",
                background_color: "#0f172a",
                min_height: 300,
                padding: { top: 70, right: 40, bottom: 70, left: 40 }
              },
              children: [
                {
                  widgetType: "heading",
                  settings: {
                    title: "Our Portfolio",
                    html_tag: "h1",
                    font_size: 52,
                    align: "center",
                    color: "#ffffff",
                    font_weight: 700
                  }
                },
                {
                  widgetType: "text",
                  settings: {
                    content: "Explore our latest creative projects and success stories",
                    font_size: 18,
                    text_align: "center",
                    color: "#cbd5e1"
                  }
                }
              ]
            },
            // Categories
            {
              widgetType: "spacer",
              settings: { height: 60 }
            },
            {
              widgetType: "text",
              settings: {
                content: '<p style="text-align: center; font-size: 14px; color: #64748b; margin: 0;"><strong style="color: #92003b;">Filter:</strong> <a href="#" style="margin: 0 10px;">All</a> | <a href="#" style="margin: 0 10px;">Web Design</a> | <a href="#" style="margin: 0 10px;">Branding</a> | <a href="#" style="margin: 0 10px;">Mobile Apps</a></p>'
              }
            },
            // Gallery
            {
              widgetType: "spacer",
              settings: { height: 40 }
            },
            {
              widgetType: "gallery",
              settings: {
                columns: "3",
                gap: 20
              }
            },
            // Client Logos
            {
              widgetType: "spacer",
              settings: { height: 80 }
            },
            {
              widgetType: "heading",
              settings: {
                title: "Trusted By Leading Brands",
                html_tag: "h2",
                font_size: 32,
                align: "center",
                color: "#1e293b"
              }
            },
            {
              widgetType: "spacer",
              settings: { height: 30 }
            },
            {
              widgetType: "logo-grid",
              settings: {
                columns: "6"
              }
            },
            // CTA
            {
              widgetType: "spacer",
              settings: { height: 80 }
            },
            {
              widgetType: "call-to-action",
              settings: {
                title: "Have a Project in Mind?",
                description: "Let's work together to bring your vision to life",
                button_text: "Start a Project",
                bg_color: "#1e293b"
              }
            }
          ],
          "page-shop": [
            // Hero Banner
            {
              widgetType: "container",
              settings: {
                columns: "1",
                background_type: "gradient",
                background_gradient: "linear-gradient(135deg, #92003b 0%, #d5006d 100%)",
                min_height: 350,
                padding: { top: 70, right: 40, bottom: 70, left: 40 }
              },
              children: [
                {
                  widgetType: "heading",
                  settings: {
                    title: "Shop Our Collection",
                    html_tag: "h1",
                    font_size: 52,
                    color: "#ffffff",
                    align: "center",
                    font_weight: 700
                  }
                },
                {
                  widgetType: "text",
                  settings: {
                    content: "Premium quality products at amazing prices",
                    color: "#ffffff",
                    font_size: 20,
                    text_align: "center"
                  }
                },
                {
                  widgetType: "spacer",
                  settings: { height: 20 }
                },
                {
                  widgetType: "button",
                  settings: {
                    text: "Shop Now",
                    align: "center",
                    size: "large",
                    bg_color: "#ffffff",
                    text_color: "#92003b"
                  }
                }
              ]
            },
            // Featured Categories
            {
              widgetType: "spacer",
              settings: { height: 60 }
            },
            {
              widgetType: "heading",
              settings: {
                title: "Shop by Category",
                html_tag: "h2",
                font_size: 36,
                align: "center",
                color: "#1e293b"
              }
            },
            {
              widgetType: "spacer",
              settings: { height: 30 }
            },
            {
              widgetType: "container",
              settings: {
                columns: "4",
                column_gap: 20,
                padding: { top: 20, right: 30, bottom: 20, left: 30 }
              },
              children: [
                {
                  widgetType: "image-box",
                  settings: {
                    title: "Electronics",
                    description: "Latest gadgets",
                    button_text: "Browse"
                  }
                },
                {
                  widgetType: "image-box",
                  settings: {
                    title: "Fashion",
                    description: "Trending styles",
                    button_text: "Browse"
                  }
                },
                {
                  widgetType: "image-box",
                  settings: {
                    title: "Home & Living",
                    description: "Decor items",
                    button_text: "Browse"
                  }
                },
                {
                  widgetType: "image-box",
                  settings: {
                    title: "Sports",
                    description: "Fitness gear",
                    button_text: "Browse"
                  }
                }
              ]
            },
            // Featured Products
            {
              widgetType: "spacer",
              settings: { height: 60 }
            },
            {
              widgetType: "heading",
              settings: {
                title: "Featured Products",
                html_tag: "h2",
                font_size: 36,
                align: "center",
                color: "#1e293b"
              }
            },
            {
              widgetType: "spacer",
              settings: { height: 30 }
            },
            {
              widgetType: "text",
              settings: {
                content: '<p style="text-align: center; color: #64748b;">This section would display WooCommerce products. Install WooCommerce to show your products here.</p>'
              }
            },
            // Newsletter
            {
              widgetType: "spacer",
              settings: { height: 80 }
            },
            {
              widgetType: "container",
              settings: {
                columns: "1",
                background_color: "#f8f9fa",
                padding: { top: 50, right: 40, bottom: 50, left: 40 }
              },
              children: [
                {
                  widgetType: "heading",
                  settings: {
                    title: "Get Exclusive Deals",
                    html_tag: "h2",
                    font_size: 32,
                    align: "center",
                    color: "#1e293b"
                  }
                },
                {
                  widgetType: "text",
                  settings: {
                    content: "Subscribe to our newsletter for special offers and updates",
                    font_size: 16,
                    text_align: "center",
                    color: "#64748b"
                  }
                },
                {
                  widgetType: "spacer",
                  settings: { height: 20 }
                },
                {
                  widgetType: "newsletter",
                  settings: {
                    placeholder: "Enter your email address",
                    button_text: "Subscribe"
                  }
                }
              ]
            }
          ],
          "page-blog": [
            // Hero
            {
              widgetType: "container",
              settings: {
                columns: "1",
                background_color: "#f8f9fa",
                min_height: 250,
                padding: { top: 60, right: 40, bottom: 60, left: 40 }
              },
              children: [
                {
                  widgetType: "heading",
                  settings: {
                    title: "Our Blog",
                    html_tag: "h1",
                    font_size: 52,
                    align: "center",
                    color: "#1e293b",
                    font_weight: 700
                  }
                },
                {
                  widgetType: "text",
                  settings: {
                    content: "Insights, tips, and stories from our team",
                    font_size: 18,
                    text_align: "center",
                    color: "#64748b"
                  }
                }
              ]
            },
            // Categories
            {
              widgetType: "spacer",
              settings: { height: 40 }
            },
            {
              widgetType: "text",
              settings: {
                content: '<p style="text-align: center; font-size: 14px; color: #64748b;"><strong style="color: #92003b;">Categories:</strong> <a href="#" style="margin: 0 8px;">All</a> | <a href="#" style="margin: 0 8px;">Design</a> | <a href="#" style="margin: 0 8px;">Development</a> | <a href="#" style="margin: 0 8px;">Marketing</a> | <a href="#" style="margin: 0 8px;">Business</a></p>'
              }
            },
            // Featured Post
            {
              widgetType: "spacer",
              settings: { height: 50 }
            },
            {
              widgetType: "heading",
              settings: {
                title: "Featured Article",
                html_tag: "h2",
                font_size: 28,
                align: "center",
                color: "#1e293b"
              }
            },
            {
              widgetType: "spacer",
              settings: { height: 20 }
            },
            {
              widgetType: "container",
              settings: {
                columns: "1",
                padding: { top: 20, right: 40, bottom: 20, left: 40 }
              },
              children: [
                {
                  widgetType: "image",
                  settings: {
                    url: "https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=1200",
                    align: "center"
                  }
                },
                {
                  widgetType: "heading",
                  settings: {
                    title: "10 Tips for Better Web Design",
                    html_tag: "h3",
                    font_size: 32,
                    color: "#1e293b"
                  }
                },
                {
                  widgetType: "text",
                  settings: {
                    content: "<p>Discover the essential principles and best practices that will take your web design skills to the next level. From typography to color theory, we cover everything you need to know.</p>"
                  }
                },
                {
                  widgetType: "button",
                  settings: {
                    text: "Read More",
                    bg_color: "#92003b"
                  }
                }
              ]
            },
            // Recent Posts
            {
              widgetType: "spacer",
              settings: { height: 80 }
            },
            {
              widgetType: "heading",
              settings: {
                title: "Recent Posts",
                html_tag: "h2",
                font_size: 36,
                align: "center",
                color: "#1e293b"
              }
            },
            {
              widgetType: "spacer",
              settings: { height: 40 }
            },
            {
              widgetType: "container",
              settings: {
                columns: "3",
                column_gap: 30,
                padding: { top: 20, right: 30, bottom: 20, left: 30 }
              },
              children: [
                {
                  widgetType: "image-box",
                  settings: {
                    title: "Getting Started with ProBuilder",
                    description: "A complete guide for beginners",
                    button_text: "Read Article"
                  }
                },
                {
                  widgetType: "image-box",
                  settings: {
                    title: "Advanced Design Techniques",
                    description: "Pro tips and tricks",
                    button_text: "Read Article"
                  }
                },
                {
                  widgetType: "image-box",
                  settings: {
                    title: "Performance Optimization",
                    description: "Speed up your website",
                    button_text: "Read Article"
                  }
                }
              ]
            },
            // Newsletter
            {
              widgetType: "spacer",
              settings: { height: 80 }
            },
            {
              widgetType: "container",
              settings: {
                columns: "1",
                background_color: "#92003b",
                padding: { top: 60, right: 40, bottom: 60, left: 40 }
              },
              children: [
                {
                  widgetType: "heading",
                  settings: {
                    title: "Never Miss an Update",
                    html_tag: "h2",
                    font_size: 36,
                    align: "center",
                    color: "#ffffff"
                  }
                },
                {
                  widgetType: "text",
                  settings: {
                    content: "Get the latest articles delivered to your inbox",
                    font_size: 16,
                    text_align: "center",
                    color: "#ffffff"
                  }
                },
                {
                  widgetType: "spacer",
                  settings: { height: 20 }
                },
                {
                  widgetType: "newsletter",
                  settings: {
                    placeholder: "Your email address",
                    button_text: "Subscribe Now"
                  }
                }
              ]
            }
          ],
          "hero-3": [
            {
              widgetType: "container",
              settings: {
                columns: "2",
                column_gap: 40,
                padding: { top: 60, right: 40, bottom: 60, left: 40 },
                min_height: 450
              },
              children: [
                {
                  widgetType: "image",
                  settings: {
                    url: "https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600",
                    align: "center"
                  }
                },
                {
                  widgetType: "container",
                  settings: {
                    columns: "1"
                  },
                  children: [
                    {
                      widgetType: "heading",
                      settings: {
                        title: "Innovative Solutions",
                        html_tag: "h2",
                        font_size: 38
                      }
                    },
                    {
                      widgetType: "text",
                      settings: {
                        content: "We provide cutting-edge technology solutions that help your business grow and succeed in the digital age."
                      }
                    },
                    {
                      widgetType: "button",
                      settings: {
                        text: "Learn More",
                        bg_color: "#92003b"
                      }
                    }
                  ]
                }
              ]
            }
          ],
          "features-2": [
            {
              widgetType: "heading",
              settings: {
                title: "Why Choose Us",
                html_tag: "h2",
                font_size: 36,
                align: "center"
              }
            },
            {
              widgetType: "feature-list",
              settings: {
                features: [
                  { icon: "fa fa-check-circle", title: "Premium Quality", description: "Top-notch products and services" },
                  { icon: "fa fa-check-circle", title: "24/7 Support", description: "Always here when you need us" },
                  { icon: "fa fa-check-circle", title: "Money Back Guarantee", description: "30-day refund policy" }
                ]
              }
            }
          ],
          "features-3": [
            {
              widgetType: "heading",
              settings: {
                title: "Key Features",
                html_tag: "h2",
                font_size: 36,
                align: "center"
              }
            },
            {
              widgetType: "icon-list",
              settings: {
                items: [
                  { text: "Drag & Drop Builder" },
                  { text: "Mobile Responsive" },
                  { text: "SEO Optimized" },
                  { text: "Fast Loading" },
                  { text: "Regular Updates" }
                ]
              }
            }
          ],
          "testimonial-1": [
            {
              widgetType: "heading",
              settings: {
                title: "What Our Customers Say",
                html_tag: "h2",
                font_size: 36,
                align: "center"
              }
            },
            {
              widgetType: "testimonial",
              settings: {
                name: "Jane Cooper",
                position: "CEO, TechCorp",
                content: "This page builder has transformed how we create websites. It's intuitive, powerful, and saves us countless hours.",
                rating: 5
              }
            }
          ],
          "testimonial-2": [
            {
              widgetType: "heading",
              settings: {
                title: "Client Testimonials",
                html_tag: "h2",
                font_size: 36,
                align: "center"
              }
            },
            {
              widgetType: "container",
              settings: {
                columns: "2",
                column_gap: 30,
                padding: { top: 40, right: 20, bottom: 40, left: 20 }
              },
              children: [
                {
                  widgetType: "testimonial",
                  settings: {
                    name: "Robert Fox",
                    position: "Marketing Director",
                    content: "Outstanding tool! Easy to use and produces amazing results.",
                    rating: 5
                  }
                },
                {
                  widgetType: "testimonial",
                  settings: {
                    name: "Emily Watson",
                    position: "Designer",
                    content: "Love the flexibility and design options. Highly recommended!",
                    rating: 5
                  }
                }
              ]
            }
          ],
          "cta-1": [
            {
              widgetType: "call-to-action",
              settings: {
                title: "Start Building Today",
                description: "Join over 10,000 users creating amazing websites",
                button_text: "Get Started Now",
                bg_color: "#92003b"
              }
            }
          ],
          "cta-2": [
            {
              widgetType: "container",
              settings: {
                columns: "1",
                background_type: "color",
                background_color: "#1e293b",
                padding: { top: 50, right: 40, bottom: 50, left: 40 }
              },
              children: [
                {
                  widgetType: "heading",
                  settings: {
                    title: "Subscribe to Our Newsletter",
                    html_tag: "h2",
                    font_size: 32,
                    color: "#ffffff",
                    align: "center"
                  }
                },
                {
                  widgetType: "newsletter",
                  settings: {
                    placeholder: "Enter your email",
                    button_text: "Subscribe"
                  }
                }
              ]
            }
          ],
          "team-1": [
            {
              widgetType: "heading",
              settings: {
                title: "Our Team",
                html_tag: "h2",
                font_size: 36,
                align: "center"
              }
            },
            {
              widgetType: "container",
              settings: {
                columns: "4",
                column_gap: 25,
                padding: { top: 40, right: 20, bottom: 40, left: 20 }
              },
              children: [
                { widgetType: "team-member", settings: { name: "Alex Morgan", position: "CEO", layout: "center" } },
                { widgetType: "team-member", settings: { name: "Sam Wilson", position: "Designer", layout: "center" } },
                { widgetType: "team-member", settings: { name: "Chris Lee", position: "Developer", layout: "center" } },
                { widgetType: "team-member", settings: { name: "Jordan Taylor", position: "Marketer", layout: "center" } }
              ]
            }
          ],
          "team-2": [
            {
              widgetType: "heading",
              settings: {
                title: "Meet the Team",
                html_tag: "h2",
                font_size: 36,
                align: "center"
              }
            },
            {
              widgetType: "container",
              settings: {
                columns: "2",
                column_gap: 30,
                padding: { top: 40, right: 30, bottom: 40, left: 30 }
              },
              children: [
                {
                  widgetType: "team-member",
                  settings: {
                    name: "Jessica Brown",
                    position: "Lead Designer",
                    bio: "Creative designer with 10+ years of experience",
                    layout: "left"
                  }
                },
                {
                  widgetType: "team-member",
                  settings: {
                    name: "David Miller",
                    position: "Senior Developer",
                    bio: "Full-stack developer specializing in modern web apps",
                    layout: "left"
                  }
                }
              ]
            }
          ],
          "gallery-1": [
            {
              widgetType: "heading",
              settings: {
                title: "Photo Gallery",
                html_tag: "h2",
                font_size: 36,
                align: "center"
              }
            },
            {
              widgetType: "gallery",
              settings: {
                columns: "3",
                gap: 15
              }
            }
          ],
          "gallery-2": [
            {
              widgetType: "heading",
              settings: {
                title: "Our Work",
                html_tag: "h2",
                font_size: 36,
                align: "center"
              }
            },
            {
              widgetType: "gallery",
              settings: {
                columns: "4",
                gap: 10
              }
            }
          ],
          "contact-1": [
            {
              widgetType: "heading",
              settings: {
                title: "Get in Touch",
                html_tag: "h2",
                font_size: 36,
                align: "center"
              }
            },
            {
              widgetType: "contact-form",
              settings: {
                show_labels: "yes"
              }
            }
          ],
          "contact-2": [
            {
              widgetType: "heading",
              settings: {
                title: "Contact Us",
                html_tag: "h2",
                font_size: 36,
                align: "center"
              }
            },
            {
              widgetType: "container",
              settings: {
                columns: "2",
                column_gap: 40,
                padding: { top: 40, right: 30, bottom: 40, left: 30 }
              },
              children: [
                {
                  widgetType: "contact-form",
                  settings: {
                    show_labels: "yes"
                  }
                },
                {
                  widgetType: "map",
                  settings: {
                    address: "New York, NY, USA",
                    height: 400
                  }
                }
              ]
            }
          ],
          "pricing-2": [
            {
              widgetType: "heading",
              settings: {
                title: "Pricing Comparison",
                html_tag: "h2",
                font_size: 36,
                align: "center"
              }
            },
            {
              widgetType: "text",
              settings: {
                content: '<p style="text-align: center;">Detailed comparison of all our plans and features</p>'
              }
            }
          ],
          "footer-1": [
            {
              widgetType: "container",
              settings: {
                columns: "3",
                column_gap: 30,
                background_color: "#1e293b",
                padding: { top: 50, right: 30, bottom: 30, left: 30 }
              },
              children: [
                {
                  widgetType: "text",
                  settings: {
                    content: '<h4 style="color: white;">About</h4><p style="color: #cbd5e1; font-size: 14px;">We create amazing websites.</p>',
                    color: "#ffffff"
                  }
                },
                {
                  widgetType: "text",
                  settings: {
                    content: '<h4 style="color: white;">Quick Links</h4><ul style="color: #cbd5e1; font-size: 14px;"><li>Home</li><li>Services</li><li>Contact</li></ul>',
                    color: "#ffffff"
                  }
                },
                {
                  widgetType: "text",
                  settings: {
                    content: '<h4 style="color: white;">Contact</h4><p style="color: #cbd5e1; font-size: 14px;">Email: info@example.com</p>',
                    color: "#ffffff"
                  }
                }
              ]
            }
          ],
          "footer-2": [
            {
              widgetType: "container",
              settings: {
                columns: "4",
                column_gap: 25,
                background_color: "#0f172a",
                padding: { top: 50, right: 30, bottom: 30, left: 30 }
              },
              children: [
                {
                  widgetType: "text",
                  settings: {
                    content: '<h4 style="color: white;">Company</h4><ul style="color: #94a3b8; font-size: 13px; list-style: none; padding: 0;"><li>About</li><li>Team</li><li>Careers</li></ul>'
                  }
                },
                {
                  widgetType: "text",
                  settings: {
                    content: '<h4 style="color: white;">Products</h4><ul style="color: #94a3b8; font-size: 13px; list-style: none; padding: 0;"><li>Features</li><li>Pricing</li><li>FAQ</li></ul>'
                  }
                },
                {
                  widgetType: "text",
                  settings: {
                    content: '<h4 style="color: white;">Resources</h4><ul style="color: #94a3b8; font-size: 13px; list-style: none; padding: 0;"><li>Blog</li><li>Docs</li><li>Support</li></ul>'
                  }
                },
                {
                  widgetType: "text",
                  settings: {
                    content: '<h4 style="color: white;">Legal</h4><ul style="color: #94a3b8; font-size: 13px; list-style: none; padding: 0;"><li>Privacy</li><li>Terms</li><li>Cookies</li></ul>'
                  }
                }
              ]
            }
          ]
        };
        return templates[templateId] || [];
      },
      /**
       * Show widget picker modal
       */
      showWidgetPicker: function(insertIndex) {
        const self2 = this;
        const $overlay = $2(`
                <div class="probuilder-widget-picker-overlay" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.7);
                    z-index: 10000;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    backdrop-filter: blur(3px);
                ">
                    <div class="probuilder-widget-picker" style="
                        background: #ffffff;
                        border-radius: 8px;
                        padding: 0;
                        max-width: 700px;
                        width: 90%;
                        max-height: 85vh;
                        overflow: hidden;
                        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
                        display: flex;
                        flex-direction: column;
                    ">
                        <!-- Header -->
                        <div style="padding: 25px 30px 20px; border-bottom: 1px solid #e6e9ec;">
                            <h3 style="margin: 0 0 15px 0; font-size: 22px; color: #27272a; font-weight: 600;">Select a Widget</h3>
                            
                            <!-- Search Input -->
                            <input type="text" 
                                   class="probuilder-picker-search" 
                                   placeholder="Search widgets..." 
                                   style="
                                       width: 100%;
                                       padding: 12px 15px 12px 40px;
                                       border: 2px solid #e6e9ec;
                                       border-radius: 6px;
                                       font-size: 14px;
                                       outline: none;
                                       transition: all 0.2s;
                                       background: #fafafa url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxOCIgaGVpZ2h0PSIxOCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiM3MTcxN2EiIHN0cm9rZS13aWR0aD0iMiI+PGNpcmNsZSBjeD0iMTEiIGN5PSIxMSIgcj0iOCIvPjxwYXRoIGQ9Im0yMSAyMS00LjM1LTQuMzUiLz48L3N2Zz4=') no-repeat 12px center;
                                   ">
                        </div>
                        
                        <!-- Tabs -->
                        <div class="probuilder-picker-tabs" style="
                            display: flex;
                            border-bottom: 1px solid #e6e9ec;
                            background: #fafafa;
                        ">
                            <button class="probuilder-picker-tab active" data-tab="widgets" style="
                                flex: 1;
                                padding: 15px 20px;
                                border: none;
                                background: #ffffff;
                                cursor: pointer;
                                font-size: 14px;
                                font-weight: 600;
                                color: #344047;
                                border-bottom: 3px solid #344047;
                                transition: all 0.2s;
                            ">
                                <i class="dashicons dashicons-screenoptions" style="font-size: 18px; margin-right: 6px;"></i>
                                Widgets
                            </button>
                            <button class="probuilder-picker-tab" data-tab="templates" style="
                                flex: 1;
                                padding: 15px 20px;
                                border: none;
                                background: transparent;
                                cursor: pointer;
                                font-size: 14px;
                                font-weight: 600;
                                color: #71717a;
                                border-bottom: 3px solid transparent;
                                transition: all 0.2s;
                            ">
                                <i class="dashicons dashicons-layout" style="font-size: 18px; margin-right: 6px;"></i>
                                Templates
                            </button>
                        </div>
                        
                        <!-- Content Area -->
                        <div class="probuilder-picker-content" style="
                            flex: 1;
                            overflow-y: auto;
                            padding: 25px 30px;
                        ">
                            <!-- Widgets Tab Content -->
                            <div class="probuilder-picker-tab-content active" data-tab="widgets">
                                <div class="probuilder-picker-grid" style="
                                    display: grid;
                                    grid-template-columns: repeat(3, 1fr);
                                    gap: 15px;
                                "></div>
                            </div>
                            
                            <!-- Templates Tab Content -->
                            <div class="probuilder-picker-tab-content" data-tab="templates" style="display: none;">
                                <div style="text-align: center; padding: 40px 20px; color: #71717a;">
                                    <i class="dashicons dashicons-layout" style="font-size: 64px; opacity: 0.2; margin-bottom: 15px;"></i>
                                    <h4 style="margin: 0 0 10px; font-size: 16px; color: #344047;">Templates Coming Soon</h4>
                                    <p style="margin: 0; font-size: 13px;">Pre-made templates will be available here for quick page building.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Footer -->
                        <div style="padding: 15px 30px; border-top: 1px solid #e6e9ec; background: #fafafa; text-align: right;">
                            <button class="probuilder-picker-close" style="
                                padding: 10px 25px;
                                background: #344047;
                                color: #ffffff;
                                border: none;
                                border-radius: 4px;
                                cursor: pointer;
                                font-weight: 600;
                                font-size: 13px;
                                transition: all 0.2s;
                            ">Close</button>
                        </div>
                    </div>
                </div>
            `);
        const $grid = $overlay.find(".probuilder-picker-grid");
        this.widgets.forEach((widget2) => {
          const $card = $2(`
                    <div class="probuilder-picker-widget" data-widget="${widget2.name}" style="
                        background: #fafafa;
                        border: 2px solid #e6e9ec;
                        border-radius: 4px;
                        padding: 20px 15px;
                        text-align: center;
                        cursor: pointer;
                        transition: all 0.2s;
                    ">
                        <i class="${widget2.icon}" style="font-size: 32px; color: #92003b; margin-bottom: 10px;"></i>
                        <div style="font-size: 12px; font-weight: 600; color: #27272a;">${widget2.title}</div>
                    </div>
                `);
          $card.on("click", function() {
            const widgetName = $2(this).data("widget");
            if (insertIndex !== null) {
              self2.insertElementAt(widgetName, insertIndex);
            } else {
              self2.addElement(widgetName);
            }
            $overlay.remove();
          });
          $card.on("mouseenter", function() {
            $2(this).css({
              "background": "#ffffff",
              "border-color": "#92003b",
              "transform": "translateY(-3px)",
              "box-shadow": "0 4px 16px rgba(147, 0, 60, 0.15)"
            });
          }).on("mouseleave", function() {
            $2(this).css({
              "background": "#fafafa",
              "border-color": "#e6e9ec",
              "transform": "translateY(0)",
              "box-shadow": "none"
            });
          });
          $grid.append($card);
        });
        $overlay.find(".probuilder-picker-tab").on("click", function() {
          const tab = $2(this).data("tab");
          $overlay.find(".probuilder-picker-tab").removeClass("active").css({
            "background": "transparent",
            "color": "#71717a",
            "border-bottom-color": "transparent"
          });
          $2(this).addClass("active").css({
            "background": "#ffffff",
            "color": "#344047",
            "border-bottom-color": "#344047"
          });
          $overlay.find(".probuilder-picker-tab-content").hide();
          $overlay.find(`.probuilder-picker-tab-content[data-tab="${tab}"]`).show();
          console.log("Switched to tab:", tab);
        });
        $overlay.find(".probuilder-picker-search").on("input", function() {
          const searchTerm = $2(this).val().toLowerCase().trim();
          if (searchTerm === "") {
            $overlay.find(".probuilder-picker-widget").show();
          } else {
            $overlay.find(".probuilder-picker-widget").each(function() {
              const widgetTitle = $2(this).find("div").text().toLowerCase();
              const widgetName = $2(this).data("widget").toLowerCase();
              if (widgetTitle.includes(searchTerm) || widgetName.includes(searchTerm)) {
                $2(this).show();
              } else {
                $2(this).hide();
              }
            });
          }
        });
        $overlay.find(".probuilder-picker-search").on("focus", function() {
          $2(this).css({
            "border-color": "#344047",
            "background": "#ffffff"
          });
        }).on("blur", function() {
          $2(this).css({
            "border-color": "#e6e9ec",
            "background": "#fafafa"
          });
        });
        $overlay.find(".probuilder-picker-close").on("click", function() {
          $overlay.remove();
        });
        $overlay.on("click", function(e) {
          if ($2(e.target).hasClass("probuilder-widget-picker-overlay")) {
            $overlay.remove();
          }
        });
        $2(document).on("keydown.widgetPicker", function(e) {
          if (e.key === "Escape") {
            $overlay.remove();
            $2(document).off("keydown.widgetPicker");
          }
        });
        $2("body").append($overlay);
        setTimeout(() => {
          $overlay.find(".probuilder-picker-search").focus();
        }, 100);
      },
      /**
       * Insert element at specific index
       */
      insertElementAt: function(widgetName, index) {
        if (!Array.isArray(this.elements)) {
          console.warn("\u26A0\uFE0F this.elements was not an array! Initializing as empty array.");
          this.elements = [];
        }
        const widget2 = this.widgets.find((w) => w.name === widgetName);
        if (!widget2) {
          console.error("Widget not found:", widgetName);
          return;
        }
        const element2 = {
          id: "element-" + Date.now() + "-" + Math.random().toString(36).substr(2, 9),
          widgetType: widgetName,
          settings: this.getDefaultSettings(widget2),
          children: []
        };
        this.elements.splice(index, 0, element2);
        this.renderElements();
        this.updateEmptyState();
        this.makeContainersDroppable();
        console.log("Element inserted at index:", index, widgetName);
        return element2;
      },
      /**
       * Create New Page
       */
      createNewPage: function() {
        const self2 = this;
        if (!confirm("Create a new blank page? Current unsaved changes will be lost.")) {
          return;
        }
        $2("#probuilder-loading").show();
        const urlParams = new URLSearchParams(window.location.search);
        const postType = urlParams.get("post_type") || "page";
        $2.ajax({
          url: ProBuilderEditor.ajax_url,
          type: "POST",
          data: {
            action: "probuilder_create_new_page",
            nonce: ProBuilderEditor.nonce,
            post_type: postType
          },
          success: function(response) {
            if (response.success) {
              const newUrl = response.data.editor_url;
              window.location.href = newUrl;
            } else {
              alert("Failed to create new page: " + (response.data.message || "Unknown error"));
              $2("#probuilder-loading").hide();
            }
          },
          error: function() {
            alert("Failed to create new page. Please try again.");
            $2("#probuilder-loading").hide();
          }
        });
      },
      /**
       * Clear Page
       */
      clearPage: function() {
        const self2 = this;
        if (!confirm("Clear all content from this page? This action cannot be undone!")) {
          return;
        }
        self2.elements = [];
        $2("#probuilder-preview-area").empty();
        self2.saveHistory();
        self2.showNotification("Page cleared! Add widgets to start building.", "success");
        console.log("\u2705 Page cleared");
      },
      /**
       * Show page settings modal
       */
      showPageSettings: function() {
        const self2 = this;
        const postId = ProBuilderEditor.post_id;
        $2.ajax({
          url: ProBuilderEditor.ajaxurl,
          type: "POST",
          data: {
            action: "probuilder_get_page_settings",
            nonce: ProBuilderEditor.nonce,
            post_id: postId
          },
          success: function(response) {
            if (response.success) {
              const data = response.data;
              self2.renderPageSettingsModal(data);
            } else {
              alert("Error loading page settings");
            }
          },
          error: function() {
            alert("Error loading page settings");
          }
        });
      },
      /**
       * Render page settings modal
       */
      renderPageSettingsModal: function(data) {
        const self2 = this;
        const $modal = $2(`
                <div class="probuilder-page-settings-overlay" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.7);
                    z-index: 100000;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    backdrop-filter: blur(3px);
                ">
                    <div class="probuilder-page-settings-modal" style="
                        background: #ffffff;
                        border-radius: 8px;
                        width: 600px;
                        max-width: 90%;
                        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                        overflow: hidden;
                    ">
                        <div style="padding: 25px 30px; border-bottom: 1px solid #e6e9ec; background: #fafafa;">
                            <h3 style="margin: 0; font-size: 20px; color: #344047; font-weight: 600;">
                                <i class="dashicons dashicons-admin-generic" style="font-size: 24px; vertical-align: middle; margin-right: 8px;"></i>
                                Page Settings
                            </h3>
                        </div>
                        
                        <div style="padding: 30px;">
                            <!-- Page Title -->
                            <div style="margin-bottom: 25px;">
                                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #344047; font-size: 13px;">
                                    <i class="dashicons dashicons-edit" style="font-size: 16px; vertical-align: middle; margin-right: 4px;"></i>
                                    Page Title
                                </label>
                                <input type="text" 
                                       id="probuilder-page-title-input" 
                                       value="${data.title || ""}"
                                       placeholder="Enter page title..."
                                       style="
                                           width: 100%;
                                           padding: 12px 15px;
                                           border: 2px solid #e6e9ec;
                                           border-radius: 6px;
                                           font-size: 14px;
                                           outline: none;
                                           transition: all 0.2s;
                                       ">
                            </div>
                            
                            <!-- Page URL/Slug -->
                            <div style="margin-bottom: 25px;">
                                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #344047; font-size: 13px;">
                                    <i class="dashicons dashicons-admin-links" style="font-size: 16px; vertical-align: middle; margin-right: 4px;"></i>
                                    Page URL (Slug)
                                </label>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <span style="color: #71717a; font-size: 13px; white-space: nowrap;">${data.site_url}/</span>
                                    <input type="text" 
                                           id="probuilder-page-slug-input" 
                                           value="${data.slug || ""}"
                                           placeholder="page-url"
                                           style="
                                               flex: 1;
                                               padding: 12px 15px;
                                               border: 2px solid #e6e9ec;
                                               border-radius: 6px;
                                               font-size: 14px;
                                               outline: none;
                                               transition: all 0.2s;
                                           ">
                                </div>
                                <p style="margin: 8px 0 0; font-size: 12px; color: #71717a;">
                                    <i class="dashicons dashicons-info" style="font-size: 14px; vertical-align: middle;"></i>
                                    URL-friendly characters only (lowercase, numbers, hyphens)
                                </p>
                            </div>
                            
                            <!-- Current URL Display -->
                            <div style="padding: 15px; background: #f8f9fa; border-radius: 6px; margin-bottom: 20px;">
                                <p style="margin: 0 0 5px; font-size: 11px; font-weight: 600; color: #71717a; text-transform: uppercase;">Current URL:</p>
                                <p id="probuilder-current-url" style="margin: 0; font-size: 13px; color: #344047; word-break: break-all;">
                                    ${data.permalink || ""}
                                </p>
                            </div>
                        </div>
                        
                        <div style="padding: 20px 30px; border-top: 1px solid #e6e9ec; background: #fafafa; display: flex; justify-content: flex-end; gap: 10px;">
                            <button class="probuilder-page-settings-cancel" style="
                                padding: 10px 25px;
                                background: #f4f4f5;
                                color: #344047;
                                border: 2px solid #e6e9ec;
                                border-radius: 4px;
                                cursor: pointer;
                                font-weight: 600;
                                font-size: 13px;
                                transition: all 0.2s;
                            ">Cancel</button>
                            <button class="probuilder-page-settings-save" style="
                                padding: 10px 25px;
                                background: #344047;
                                color: #ffffff;
                                border: none;
                                border-radius: 4px;
                                cursor: pointer;
                                font-weight: 600;
                                font-size: 13px;
                                transition: all 0.2s;
                            ">
                                <i class="dashicons dashicons-saved" style="font-size: 16px; vertical-align: middle; margin-right: 4px;"></i>
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            `);
        $modal.find("#probuilder-page-title-input").on("input", function() {
          const title = $2(this).val();
          const currentSlug = $2("#probuilder-page-slug-input").val();
          if (!currentSlug || currentSlug === "") {
            const slug = title.toLowerCase().replace(/[^a-z0-9\s-]/g, "").replace(/\s+/g, "-").replace(/-+/g, "-").replace(/^-|-$/g, "");
            $2("#probuilder-page-slug-input").val(slug);
            $2("#probuilder-current-url").text(data.site_url + "/" + slug + "/");
          }
        });
        $modal.find("#probuilder-page-slug-input").on("input", function() {
          let slug = $2(this).val();
          slug = slug.toLowerCase().replace(/[^a-z0-9-]/g, "").replace(/-+/g, "-").replace(/^-|-$/g, "");
          $2(this).val(slug);
          $2("#probuilder-current-url").text(data.site_url + "/" + slug + "/");
        });
        $modal.find("input").on("focus", function() {
          $2(this).css("border-color", "#344047");
        }).on("blur", function() {
          $2(this).css("border-color", "#e6e9ec");
        });
        $modal.find(".probuilder-page-settings-cancel").hover(
          function() {
            $2(this).css({ "background": "#e6e9ec", "transform": "translateY(-1px)" });
          },
          function() {
            $2(this).css({ "background": "#f4f4f5", "transform": "translateY(0)" });
          }
        );
        $modal.find(".probuilder-page-settings-save").hover(
          function() {
            $2(this).css({ "background": "#2c3540", "transform": "translateY(-1px)" });
          },
          function() {
            $2(this).css({ "background": "#344047", "transform": "translateY(0)" });
          }
        );
        $modal.find(".probuilder-page-settings-cancel").on("click", function() {
          $modal.remove();
        });
        $modal.on("click", function(e) {
          if ($2(e.target).hasClass("probuilder-page-settings-overlay")) {
            $modal.remove();
          }
        });
        $modal.find(".probuilder-page-settings-save").on("click", function() {
          const newTitle = $2("#probuilder-page-title-input").val().trim();
          const newSlug = $2("#probuilder-page-slug-input").val().trim();
          if (!newTitle) {
            alert("Please enter a page title");
            return;
          }
          if (!newSlug) {
            alert("Please enter a page URL");
            return;
          }
          $2(this).prop("disabled", true).text("Saving...");
          $2.ajax({
            url: ProBuilderEditor.ajaxurl,
            type: "POST",
            data: {
              action: "probuilder_save_page_settings",
              nonce: ProBuilderEditor.nonce,
              post_id: ProBuilderEditor.post_id,
              title: newTitle,
              slug: newSlug
            },
            success: function(response) {
              var _a;
              if (response.success) {
                $2(".probuilder-page-title").text(newTitle);
                self2.showToast("\u2705 Page settings updated!");
                $modal.remove();
                console.log("\u2705 Page settings saved:", { title: newTitle, slug: newSlug });
              } else {
                alert("Error saving page settings: " + (((_a = response.data) == null ? void 0 : _a.message) || "Unknown error"));
                $2(this).prop("disabled", false).html('<i class="dashicons dashicons-saved"></i> Save Changes');
              }
            },
            error: function() {
              alert("Error saving page settings. Please try again.");
              $2(this).prop("disabled", false).html('<i class="dashicons dashicons-saved"></i> Save Changes');
            }
          });
        });
        $2("body").append($modal);
        setTimeout(() => {
          $2("#probuilder-page-title-input").focus().select();
        }, 100);
      },
      /**
       * Save page
       */
      savePage: function() {
        var _a;
        $2("#probuilder-loading").show();
        console.log("=== SAVING PAGE ===");
        console.log("Post ID:", ProBuilderEditor.post_id);
        console.log("Elements count:", this.elements.length);
        console.log("Elements array:", this.elements);
        if (this.elements.length > 0) {
          console.log("First element:", this.elements[0]);
          if (this.elements[0].widgetType === "heading") {
            console.log("Heading text:", (_a = this.elements[0].settings) == null ? void 0 : _a.title);
          }
        }
        if (!Array.isArray(this.elements)) {
          console.error("\u274C this.elements is not an array!");
          alert("Error: Cannot save - data is corrupted. Please refresh the page.");
          $2("#probuilder-loading").hide();
          return;
        }
        const elementsForSave = this.prepareElementsForSave(this.elements);
        const elementsJSON = JSON.stringify(elementsForSave);
        console.log("JSON length:", elementsJSON.length);
        console.log("JSON preview:", elementsJSON.substring(0, 200));
        $2.ajax({
          url: ProBuilderEditor.ajaxurl,
          type: "POST",
          data: {
            action: "probuilder_save_page",
            nonce: ProBuilderEditor.nonce,
            post_id: ProBuilderEditor.post_id,
            elements: elementsJSON
          },
          success: function(response) {
            var _a2, _b, _c;
            $2("#probuilder-loading").hide();
            if (response.success) {
              const permalink = ((_a2 = response.data) == null ? void 0 : _a2.permalink) || "";
              const elementCount = ((_b = response.data) == null ? void 0 : _b.element_count) || 0;
              const listAllPagesUrl = ProBuilderEditor.home_url + "/wp-content/plugins/probuilder/list-all-pages.php";
              const $message = $2(`
                            <div class="probuilder-notice probuilder-notice-success" style="
                                position: fixed;
                                top: 80px;
                                left: 50%;
                                transform: translateX(-50%);
                                background: #ffffff;
                                border-left: 4px solid #22c55e;
                                padding: 25px 30px;
                                border-radius: 12px;
                                box-shadow: 0 10px 40px rgba(0,0,0,0.25);
                                z-index: 99999;
                                min-width: 450px;
                                max-width: 650px;
                            ">
                                <div style="font-size: 18px; font-weight: 700; color: #16a34a; margin-bottom: 8px;">
                                    <i class="dashicons dashicons-yes-alt" style="font-size: 24px; vertical-align: middle; margin-right: 8px;"></i>
                                    Page Saved Successfully!
                                </div>
                                <div style="font-size: 14px; color: #71717a; margin-bottom: 18px;">
                                    <strong>${elementCount}</strong> element(s) saved to this page
                                </div>
                                ${permalink ? `
                                    <div style="background: #f8f9fa; padding: 12px 15px; border-radius: 6px; margin-bottom: 15px;">
                                        <div style="font-size: 11px; color: #71717a; margin-bottom: 5px; font-weight: 600; text-transform: uppercase;">Page URL:</div>
                                        <div style="font-size: 13px; color: #344047; word-break: break-all; font-family: monospace;">
                                            ${permalink}
                                        </div>
                                    </div>
                                    <div style="display: flex; gap: 10px;">
                                        <a href="${permalink}" target="_blank" style="
                                            flex: 1;
                                            display: inline-flex;
                                            align-items: center;
                                            justify-content: center;
                                            background: #22c55e;
                                            color: #ffffff;
                                            padding: 12px 20px;
                                            border-radius: 6px;
                                            text-decoration: none;
                                            font-size: 14px;
                                            font-weight: 600;
                                            transition: all 0.2s;
                                            gap: 8px;
                                        " onmouseover="this.style.background='#16a34a'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(34,197,94,0.3)'" onmouseout="this.style.background='#22c55e'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                            <i class="dashicons dashicons-external" style="font-size: 18px;"></i>
                                            View This Page
                                        </a>
                                        <a href="${listAllPagesUrl}" target="_blank" style="
                                            display: inline-flex;
                                            align-items: center;
                                            justify-content: center;
                                            background: #344047;
                                            color: #ffffff;
                                            padding: 12px 20px;
                                            border-radius: 6px;
                                            text-decoration: none;
                                            font-size: 14px;
                                            font-weight: 600;
                                            transition: all 0.2s;
                                            gap: 8px;
                                        " onmouseover="this.style.background='#2c3540'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#344047'; this.style.transform='translateY(0)'">
                                            <i class="dashicons dashicons-list-view" style="font-size: 18px;"></i>
                                            All Pages
                                        </a>
                                    </div>
                                ` : ""}
                            </div>
                        `);
              $2("body").append($message);
              setTimeout(() => $message.fadeOut(() => $message.remove()), 5e3);
              console.log("\u2705 Page saved with", elementCount, "elements");
              console.log("\u{1F4CD} View at:", permalink);
            } else {
              alert("Error saving page: " + (((_c = response.data) == null ? void 0 : _c.message) || "Unknown error"));
            }
          },
          error: function() {
            $2("#probuilder-loading").hide();
            alert("Error saving page. Please try again.");
          }
        });
      }
    };
    $2(document).ready(function() {
      console.log("Document ready, initializing ProBuilder...");
      console.log("jQuery version:", $2.fn.jquery);
      console.log("Sidebar exists:", $2(".probuilder-sidebar").length > 0);
      console.log("Canvas exists:", $2(".probuilder-canvas").length > 0);
      console.log("Preview area exists:", $2("#probuilder-preview-area").length > 0);
      setTimeout(function() {
        ProBuilder2.init();
      }, 100);
    });
    window.ProBuilder = ProBuilder2;
    console.log("ProBuilder Editor JavaScript loaded successfully!");
  })(jQuery);
})();
//# sourceMappingURL=editor.js.map

export default // Widget renderer for "slider" (auto-generated)
function renderSlider(context) {
  const {
    element,
    settings,
    spacingStyles,
    app,
    depth = 0,
    widget
  } = context;
  const slSlides = settings.slides || [{
    image: {
      url: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200'
    },
    title: 'Welcome to Our Website',
    description: 'Discover amazing products and services',
    button_text: 'Get Started',
    content_position: 'center'
  }];
  const slSliderStyle = settings.slider_style || 'classic';
  const slSliderHeight = settings.slider_height || {
    size: 500
  };
  const slShowArrows = settings.show_arrows !== 'no';
  const slShowDots = settings.show_dots !== 'no';
  const slShowProgressBar = settings.show_progress_bar === 'yes';
  const slShowFraction = settings.show_fraction === 'yes';
  const slAutoplay = settings.autoplay !== 'no';
  const slAutoplaySpeed = settings.autoplay_speed?.size || 5;
  const slTransitionSpeed = settings.transition_speed || 500;

  // Overlay settings
  // Overlay settings
  const slOverlayType = settings.overlay_type || 'color';
  const slOverlayColor = settings.overlay_color || 'rgba(0,0,0,0.4)';
  const slOverlayGradStart = settings.overlay_gradient_start || 'rgba(0,0,0,0.6)';
  const slOverlayGradEnd = settings.overlay_gradient_end || 'rgba(0,0,0,0.2)';

  // Typography
  // Typography
  const slTitleColor = settings.title_color || '#ffffff';
  const slTitleSize = settings.title_size || 48;
  const slTitleWeight = settings.title_weight || '700';
  const slDescColor = settings.description_color || '#ffffff';
  const slDescSize = settings.description_size || 18;
  const slContentMaxWidth = settings.content_max_width || 600;
  const slContentBgEnable = settings.content_bg_enable === 'yes';
  const slContentBgColor = settings.content_bg_color || 'rgba(255,255,255,0.1)';
  const slContentBgBlur = settings.content_bg_blur === 'yes';

  // Button
  // Button
  const slButtonBgColor = settings.button_bg_color || '#92003b';
  const slButtonTextColor = settings.button_text_color || '#ffffff';
  const slButtonSize = settings.button_size || 'medium';
  const slButtonRadius = settings.button_border_radius || 5;

  // Navigation
  // Navigation
  const slArrowStyle = settings.arrow_style || 'circle';
  const slArrowSize = settings.arrow_size || 50;
  const slArrowColor = settings.arrow_color || '#ffffff';
  const slArrowBgColor = settings.arrow_bg_color || 'rgba(0,0,0,0.5)';
  const slDotStyle = settings.dot_style || 'circle';
  const slDotSize = settings.dot_size || 12;
  const slDotColor = settings.dot_color || 'rgba(255,255,255,0.5)';
  const slActiveDotColor = settings.active_dot_color || '#ffffff';
  const slDotPosition = settings.dot_position || 'bottom-center';

  // Content animation
  // Content animation
  const slContentAnimation = settings.content_animation || 'fade-up';
  const slAnimationDelay = settings.animation_delay || 200;
  const heightValue = slSliderHeight.size || 500;

  // Button size
  // Button size
  const buttonPadding = slButtonSize === 'small' ? '10px 20px' : slButtonSize === 'large' ? '18px 40px' : '15px 30px';
  const buttonFontSize = slButtonSize === 'small' ? '14px' : slButtonSize === 'large' ? '18px' : '16px';

  // Arrow style
  // Arrow style
  const arrowBorderRadius = slArrowStyle === 'circle' ? '50%' : slArrowStyle === 'rounded' ? '8px' : slArrowStyle === 'square' ? '0' : '50%';
  const arrowBackground = slArrowStyle === 'chevron' || slArrowStyle === 'minimal' ? 'transparent' : slArrowBgColor;
  const arrowButtonSize = slArrowSize + 'px';
  const arrowFontSize = slArrowSize / 2 + 'px';

  // Dot shape
  // Dot shape
  const dotBorderRadius = slDotStyle === 'circle' ? '50%' : slDotStyle === 'square' ? '0' : '2px';
  const dotWidth = slDotStyle === 'line' ? slDotSize * 3 + 'px' : slDotStyle === 'dash' ? slDotSize * 2 + 'px' : slDotSize + 'px';
  const dotHeight = slDotStyle === 'line' ? slDotSize / 2 + 'px' : slDotStyle === 'dash' ? slDotSize / 2 + 'px' : slDotSize + 'px';

  // Generate unique ID for this slider instance
  // Generate unique ID for this slider instance
  const sliderId = 'pb-slider-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);

  // Build slider HTML with ALL slides
  // Build slider HTML with ALL slides
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

  // Create slides container
  // Create slides container
  slSliderHTML += `<div class="pb-slides" style="position: relative; width: 100%; height: 100%;">`;

  // Generate all slides
  // Generate all slides
  slSlides.forEach((slide, index) => {
    const slideImage = slide.image?.url || 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200';
    const slideTitle = slide.title || 'Slide Title';
    const slideDesc = slide.description || 'Slide description goes here...';
    const slideButton = slide.button_text || 'Learn More';
    const slidePosition = slide.content_position || 'center';
    const contentAlign = slidePosition === 'left' ? 'flex-start' : slidePosition === 'right' ? 'flex-end' : 'center';

    // Overlay style
    let overlayStyle = '';
    if (slOverlayType === 'gradient') {
      overlayStyle = `background: linear-gradient(135deg, ${slOverlayGradStart}, ${slOverlayGradEnd});`;
    } else if (slOverlayType === 'color') {
      overlayStyle = `background-color: ${slOverlayColor};`;
    }

    // Content container style
    let contentContainerStyle = `max-width: ${slContentMaxWidth}px; padding: 40px; text-align: ${slidePosition};`;
    if (slContentBgEnable) {
      contentContainerStyle += ` background-color: ${slContentBgColor}; border-radius: 12px;`;
      if (slContentBgBlur) {
        contentContainerStyle += ` backdrop-filter: blur(10px);`;
      }
    }
    const isActive = index === 0;
    const displayStyle = isActive ? 'flex' : 'none';
    slSliderHTML += `<div class="pb-slide" data-slide-index="${index}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: url('${slideImage}'); background-size: cover; background-position: center; display: ${displayStyle}; align-items: center; justify-content: ${contentAlign}; transition: opacity ${slTransitionSpeed}ms ease-in-out;">`;

    // Overlay
    if (slOverlayType !== 'none') {
      slSliderHTML += `<div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; ${overlayStyle}"></div>`;
    }

    // Content with animation
    const animationClass = `pb-animate-${slContentAnimation}`;
    const animationStyle = isActive ? `animation: ${animationClass} 0.8s ease-out ${slAnimationDelay}ms both;` : '';
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
  slSliderHTML += `</div>`; // Close slides container

  // Navigation Arrows
  // Close slides container

  // Navigation Arrows
  if (slShowArrows) {
    slSliderHTML += `<button class="pb-slider-prev" onclick="pbSliderPrev('${sliderId}')" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); background: ${arrowBackground}; border: none; color: ${slArrowColor}; font-size: ${arrowFontSize}; width: ${arrowButtonSize}; height: ${arrowButtonSize}; border-radius: ${arrowBorderRadius}; cursor: pointer; z-index: 3; display: flex; align-items: center; justify-content: center; padding: 0; line-height: 1;">‹</button>`;
    slSliderHTML += `<button class="pb-slider-next" onclick="pbSliderNext('${sliderId}')" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); background: ${arrowBackground}; border: none; color: ${slArrowColor}; font-size: ${arrowFontSize}; width: ${arrowButtonSize}; height: ${arrowButtonSize}; border-radius: ${arrowBorderRadius}; cursor: pointer; z-index: 3; display: flex; align-items: center; justify-content: center; padding: 0; line-height: 1;">›</button>`;
  }

  // Dots Navigation
  // Dots Navigation
  if (slShowDots) {
    // Calculate dot position
    const dotBottom = slDotPosition.includes('bottom') ? '20px' : 'auto';
    const dotTop = slDotPosition.includes('top') ? '20px' : 'auto';
    let dotLeft = '50%';
    let dotTransform = 'translateX(-50%)';
    let dotRight = 'auto';
    if (slDotPosition === 'bottom-left' || slDotPosition === 'top-left') {
      dotLeft = '20px';
      dotTransform = 'none';
    } else if (slDotPosition === 'bottom-right' || slDotPosition === 'top-right') {
      dotLeft = 'auto';
      dotRight = '20px';
      dotTransform = 'none';
    }
    slSliderHTML += `<div class="pb-slider-dots" style="position: absolute; bottom: ${dotBottom}; top: ${dotTop}; left: ${dotLeft}; right: ${dotRight}; transform: ${dotTransform}; display: flex; gap: 10px; z-index: 3;">`;
    slSlides.forEach((slide, index) => {
      const isActive = index === 0;
      const dotColor = isActive ? slActiveDotColor : slDotColor;
      slSliderHTML += `<div class="pb-slider-dot" data-slide="${index}" onclick="pbSliderGoTo('${sliderId}', ${index})" style="width: ${dotWidth}; height: ${dotHeight}; border-radius: ${dotBorderRadius}; background-color: ${dotColor}; cursor: pointer; transition: all 0.3s;"></div>`;
    });
    slSliderHTML += `</div>`;
  }

  // Progress Bar
  // Progress Bar
  if (slShowProgressBar) {
    const progressBarColor = settings.progress_bar_color || '#92003b';
    slSliderHTML += `<div class="pb-slider-progress" style="position: absolute; bottom: 0; left: 0; width: 0%; height: 4px; background-color: ${progressBarColor}; z-index: 4; transition: width linear ${slAutoplaySpeed * 1000}ms;"></div>`;
  }

  // Fraction Counter
  // Fraction Counter
  if (slShowFraction) {
    const fractionColor = settings.fraction_color || '#ffffff';
    slSliderHTML += `<div class="pb-slider-fraction" style="position: absolute; top: 20px; right: 20px; color: ${fractionColor}; font-size: 14px; font-weight: 600; z-index: 3; background: rgba(0,0,0,0.3); padding: 8px 16px; border-radius: 20px;">1 / ${slSlides.length}</div>`;
  }

  // Slide indicator (total slides)
  // Slide indicator (total slides)
  if (slSlides.length > 1) {
    slSliderHTML += `<div style="position: absolute; top: 10px; left: 10px; background: rgba(0,0,0,0.5); color: white; padding: 4px 10px; border-radius: 12px; font-size: 11px; z-index: 3;">${slSlides.length} Slides</div>`;
  }
  slSliderHTML += `</div>`; // Close main slider container

  // Add initialization script
  // Close main slider container

  // Add initialization script
  slSliderHTML += `<script>
                        (function() {
                            const sliderId = '${sliderId}';
                            const autoplay = ${slAutoplay};
                            const autoplaySpeed = ${slAutoplaySpeed * 1000};
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
                    </script>`;
  return slSliderHTML;
}
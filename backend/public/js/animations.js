// Splash Screen - BlurText Animation
(function() {
  window.initSplash = function(onComplete) {
    const splash = document.getElementById('splash-screen');
    if (!splash) return;

    // Check sessionStorage to avoid showing splash on every page load
    if (sessionStorage.getItem('redsim_splash_shown')) {
      splash.remove();
      if (onComplete) onComplete();
      return;
    }

    const text = 'REDSIM';
    const container = splash.querySelector('.splash-text');
    if (!container) return;

    // Create individual letter spans
    text.split('').forEach(function(char, i) {
      const span = document.createElement('span');
      span.textContent = char;
      span.className = 'splash-letter';
      span.style.animationDelay = (i * 0.15) + 's';
      container.appendChild(span);
    });

    // After animation completes, fade out splash
    setTimeout(function() {
      splash.classList.add('splash-exit');
      setTimeout(function() {
        splash.remove();
        sessionStorage.setItem('redsim_splash_shown', '1');
        if (onComplete) onComplete();
      }, 800);
    }, text.length * 150 + 1200);
  };
})();

// TextType Animation
(function() {
  window.initTextType = function(element, sentences, opts) {
    opts = Object.assign({
      typingSpeed: 50,
      deletingSpeed: 30,
      pauseDuration: 2000,
      loop: true,
      cursorChar: '|'
    }, opts);

    if (!element) return;

    const contentEl = document.createElement('span');
    contentEl.className = 'texttype-content';
    const cursorEl = document.createElement('span');
    cursorEl.className = 'texttype-cursor';
    cursorEl.textContent = opts.cursorChar;
    element.appendChild(contentEl);
    element.appendChild(cursorEl);

    let sentenceIdx = 0, charIdx = 0, isDeleting = false, displayedText = '';

    function tick() {
      const current = sentences[sentenceIdx];
      if (isDeleting) {
        if (displayedText.length > 0) {
          displayedText = displayedText.slice(0, -1);
          contentEl.textContent = displayedText;
          setTimeout(tick, opts.deletingSpeed);
        } else {
          isDeleting = false;
          sentenceIdx = (sentenceIdx + 1) % sentences.length;
          charIdx = 0;
          if (!opts.loop && sentenceIdx === 0) return;
          setTimeout(tick, opts.pauseDuration / 2);
        }
      } else {
        if (charIdx < current.length) {
          displayedText += current[charIdx];
          charIdx++;
          contentEl.textContent = displayedText;
          setTimeout(tick, opts.typingSpeed);
        } else {
          if (!opts.loop && sentenceIdx === sentences.length - 1) return;
          setTimeout(function() {
            isDeleting = true;
            tick();
          }, opts.pauseDuration);
        }
      }
    }
    setTimeout(tick, 500);
  };
})();

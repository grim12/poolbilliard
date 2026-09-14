// Ported piece by piece from ui/src/js/main.js as each widget/layout piece gets its Blade
// port — see skills/web-component-guide.md.
document.addEventListener('DOMContentLoaded', () => {
  // Header: mobile menu toggle
  const headerToggle = document.querySelector('[data-header-toggle]');
  const headerPanel = document.querySelector('[data-header-panel]');

  if (headerToggle && headerPanel) {
    headerToggle.addEventListener('click', () => {
      const isOpen = headerToggle.getAttribute('aria-expanded') === 'true';
      headerToggle.setAttribute('aria-expanded', String(!isOpen));
      headerPanel.hidden = isOpen;
    });
  }

  // Header: mobile nav accordion (second-level dropdown items, tap to expand)
  document.querySelectorAll('[data-accordion-toggle]').forEach((toggle) => {
    toggle.addEventListener('click', () => {
      const item = toggle.closest('[data-accordion]');
      const isOpen = toggle.getAttribute('aria-expanded') === 'true';
      toggle.setAttribute('aria-expanded', String(!isOpen));
      if (item) {
        item.classList.toggle('is-open', !isOpen);
      }
    });
  });

  // Header: hide the topbar on scroll down, reveal it on scroll up (desktop/sticky only)
  const header = document.querySelector('.c-header');
  const desktopMedia = window.matchMedia('(min-width: 64rem)');

  if (header) {
    const SCROLL_THRESHOLD = 64;
    let lastScrollY = window.scrollY;

    window.addEventListener('scroll', () => {
      const currentScrollY = window.scrollY;
      const delta = currentScrollY - lastScrollY;

      if (!desktopMedia.matches || currentScrollY <= 0) {
        header.classList.remove('is-topbar-hidden');
        lastScrollY = currentScrollY;
      } else if (delta > SCROLL_THRESHOLD) {
        header.classList.add('is-topbar-hidden');
        lastScrollY = currentScrollY;
      } else if (delta < -SCROLL_THRESHOLD) {
        header.classList.remove('is-topbar-hidden');
        lastScrollY = currentScrollY;
      }
    }, { passive: true });
  }

  // Header: keep --header-height in sync with its actual rendered height (it changes as the
  // topbar hides/reveals on scroll, see above) so sticky elements below it can offset by
  // exactly that much instead of a guessed constant.
  if (header && 'ResizeObserver' in window) {
    const setHeaderHeightVar = () => {
      document.documentElement.style.setProperty('--header-height', `${header.offsetHeight}px`);
    };
    setHeaderHeightVar();
    new ResizeObserver(setHeaderHeightVar).observe(header);
  }

  // Header: search panel toggle (desktop bar + mobile menu each have their own panel/trigger)
  const searchToggles = document.querySelectorAll('[data-search-toggle]');
  const searchPanels = document.querySelectorAll('[data-search-panel]');
  const searchCloses = document.querySelectorAll('[data-search-close]');

  if (searchToggles.length && searchPanels.length) {
    const focusVisibleInput = () => {
      const visiblePanel = Array.from(searchPanels).find((panel) => panel.offsetParent !== null);
      const input = visiblePanel && visiblePanel.querySelector('[data-search-input]');
      if (input) {
        input.focus();
      }
    };

    const openSearch = () => {
      searchToggles.forEach((toggle) => toggle.setAttribute('aria-expanded', 'true'));
      searchPanels.forEach((panel) => panel.classList.add('is-open'));
      window.requestAnimationFrame(focusVisibleInput);
    };

    const closeSearch = (focusToggle) => {
      searchToggles.forEach((toggle) => toggle.setAttribute('aria-expanded', 'false'));
      searchPanels.forEach((panel) => panel.classList.remove('is-open'));
      if (focusToggle) {
        focusToggle.focus();
      }
    };

    searchToggles.forEach((toggle) => {
      toggle.addEventListener('click', () => {
        const isOpen = toggle.getAttribute('aria-expanded') === 'true';
        isOpen ? closeSearch() : openSearch();
      });
    });

    searchCloses.forEach((closeBtn) => {
      closeBtn.addEventListener('click', () => closeSearch());
    });

    document.addEventListener('keydown', (event) => {
      const isAnyOpen = Array.from(searchPanels).some((panel) => panel.classList.contains('is-open'));
      if (event.key === 'Escape' && isAnyOpen) {
        closeSearch(searchToggles[0]);
      }
    });
  }

  // FAQ accordion (see resources/views/components/faq.blade.php): only one question open at a
  // time — each .c-faq container is scoped independently, so a page can have more than one
  // group without them fighting over which item is open. Animated via max-height (set to the
  // panel's measured scrollHeight to open, 0 to close) since the panel's real height varies
  // with content/viewport and can't be a fixed CSS value.
  document.querySelectorAll('.c-faq').forEach((group) => {
    const toggles = Array.from(group.querySelectorAll('[data-faq-toggle]'));

    const closeToggle = (toggle) => {
      const panel = document.getElementById(toggle.getAttribute('aria-controls'));
      toggle.setAttribute('aria-expanded', 'false');
      if (panel) panel.style.maxHeight = '0px';
    };

    toggles.forEach((toggle) => {
      const panel = document.getElementById(toggle.getAttribute('aria-controls'));
      if (!panel) return;

      if (toggle.getAttribute('aria-expanded') === 'true') {
        panel.style.maxHeight = `${panel.scrollHeight}px`;
      }

      toggle.addEventListener('click', () => {
        const isOpen = toggle.getAttribute('aria-expanded') === 'true';

        toggles.forEach((other) => {
          if (other !== toggle) closeToggle(other);
        });

        toggle.setAttribute('aria-expanded', String(!isOpen));
        panel.style.maxHeight = isOpen ? '0px' : `${panel.scrollHeight}px`;
      });
    });
  });
});

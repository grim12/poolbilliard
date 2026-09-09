// Main JavaScript entry point
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
});

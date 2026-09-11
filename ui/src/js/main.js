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

  // Gallery lightbox (GLightbox, loaded only on pages that have a .glightbox gallery)
  if (window.GLightbox && document.querySelector('.glightbox')) {
    window.GLightbox({ selector: '.glightbox', touchNavigation: true, loop: true });
  }

  // Kluby/Herny: Leaflet map with one marker per item (loaded only on pages with a [data-club-map] element)
  const clubMapEl = document.querySelector('[data-club-map]');

  if (clubMapEl && window.L) {
    const clubs = JSON.parse(clubMapEl.dataset.clubMap || '[]');

    // Custom marker icon (a plain styled <div>, see .c-map-pin) instead of Leaflet's default
    // image-based icon, which isn't vendored. data-pin-color picks a color modifier (e.g.
    // "primary" for the blue Herny pins) — red (.c-map-pin's own default) when omitted.
    const pinColor = clubMapEl.dataset.pinColor;
    const clubIcon = L.divIcon({
      className: pinColor ? `c-map-pin c-map-pin--${pinColor}` : 'c-map-pin',
      iconSize: [18, 18],
      iconAnchor: [9, 9],
      popupAnchor: [0, -9],
    });

    const validClubs = clubs.filter((club) => typeof club.lat === 'number' && typeof club.lng === 'number');

    const map = L.map(clubMapEl, { scrollWheelZoom: false });

    // One club (club detail page): center + zoom in on it. Several (Kluby overview): whole-country view.
    if (validClubs.length === 1) {
      map.setView([validClubs[0].lat, validClubs[0].lng], 15);
    } else {
      map.setView([49.8, 15.5], 7);
    }

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> přispěvatelé',
      maxZoom: 18,
    }).addTo(map);

    validClubs.forEach((club) => {
      L.marker([club.lat, club.lng], { icon: clubIcon })
        .addTo(map)
        .bindPopup(`
          <div class="c-map-popup">
            <p class="c-map-popup__name">${club.name}</p>
            <p class="c-map-popup__address">${[club.fullName, club.address].filter(Boolean).join(', ')}</p>
            ${club.url ? `<a class="c-map-popup__link" href="${club.url}">${club.linkText || 'Detail klubu'} →</a>` : ''}
          </div>
        `);
    });
  }
});

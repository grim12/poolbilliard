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

  // FAQ accordion (see macros/faq.njk): only one question open at a time — each .c-faq
  // container is scoped independently, so a page can have more than one group (e.g. one FAQ
  // per persona section) without them fighting over which item is open. Animated via
  // max-height (set to the panel's measured scrollHeight to open, 0 to close) since the
  // panel's real height varies with content/viewport and can't be a fixed CSS value.
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

      // Markup can mark an item pre-opened (aria-expanded="true", e.g. mythFaq's first item)
      // — panel starts at max-h-0 in CSS regardless, so expand it to its measured height once.
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

  // Document year tabs (see widgets/documents.njk): switch which year's accordion panel is
  // visible. Each panel is its own independent .c-faq group, already wired up above — this
  // only toggles which one is shown, it doesn't touch their internal open/close state.
  document.querySelectorAll('[data-doc-tabs]').forEach((tabList) => {
    const tabs = Array.from(tabList.querySelectorAll('[data-doc-tab]'));

    tabs.forEach((tab) => {
      tab.addEventListener('click', () => {
        const panelId = tab.getAttribute('aria-controls');

        tabs.forEach((other) => {
          const isActive = other === tab;
          other.classList.toggle('is-active', isActive);
          other.setAttribute('aria-selected', String(isActive));
        });

        document.querySelectorAll('[data-doc-panel]').forEach((panel) => {
          const isShown = panel.id === panelId;
          panel.hidden = !isShown;

          // A panel that starts hidden (display: none) measures its pre-opened accordion
          // item's scrollHeight as 0 at page load (see the FAQ init above) — recompute now
          // that it's actually visible, or its first group would look open but render collapsed.
          if (isShown) {
            panel.querySelectorAll('[data-faq-toggle][aria-expanded="true"]').forEach((toggle) => {
              const itemPanel = document.getElementById(toggle.getAttribute('aria-controls'));
              if (itemPanel) itemPanel.style.maxHeight = `${itemPanel.scrollHeight}px`;
            });
          }
        });
      });
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
  // topbar hides/reveals on scroll, see above) so sticky elements below it (e.g. jump-nav)
  // can offset by exactly that much instead of a guessed constant.
  if (header && 'ResizeObserver' in window) {
    const setHeaderHeightVar = () => {
      document.documentElement.style.setProperty('--header-height', `${header.offsetHeight}px`);
    };
    setHeaderHeightVar();
    new ResizeObserver(setHeaderHeightVar).observe(header);
  }

  // Jump nav: sticky pill row linking to sections further down the same page (see
  // macros/jump-nav.njk). Highlights the pill for whichever section is currently in view,
  // and scrolls that pill into view within the row itself — relevant once the row is wider
  // than the viewport (mobile, or just many items).
  document.querySelectorAll('[data-jump-nav]').forEach((nav) => {
    const links = Array.from(nav.querySelectorAll('[data-jump-link]'));
    const sections = links
      .map((link) => document.getElementById(link.getAttribute('href').slice(1)))
      .filter(Boolean);

    if (!sections.length || !('IntersectionObserver' in window)) return;

    const setActive = (id) => {
      links.forEach((link) => {
        const isActive = link.getAttribute('href') === `#${id}`;
        link.classList.toggle('is-active', isActive);
        if (isActive) {
          link.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        }
      });
    };

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) setActive(entry.target.id);
        });
      },
      // A thin detection band a bit above center — a section counts as "active" once it
      // crosses it, not only once it's fully in view.
      { rootMargin: '-45% 0px -50% 0px' }
    );

    sections.forEach((section) => observer.observe(section));
  });

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

// Ported piece by piece from ui/src/js/main.js as each widget gets its Blade port — see
// skills/web-component-guide.md. Header/mobile-nav JS is NOT here yet (site chrome isn't
// ported), only widget-level behavior that already has a Blade component.
document.addEventListener('DOMContentLoaded', () => {
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

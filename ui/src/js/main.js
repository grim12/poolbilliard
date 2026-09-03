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
});

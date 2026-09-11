const fs = require("fs");
const path = require("path");

// --- Icons: build-time inlined SVG (Heroicons solid for UI, Simple Icons for brand/social) ---
const HEROICONS_DIR = path.join(__dirname, "node_modules/heroicons");
const SIMPLE_ICONS_DIR = path.join(__dirname, "node_modules/simple-icons/icons");
const iconFileCache = new Map();

function readIconFile(filePath) {
  if (!iconFileCache.has(filePath)) {
    iconFileCache.set(filePath, fs.readFileSync(filePath, "utf8").trim());
  }
  return iconFileCache.get(filePath);
}

// Heroicons solid ships two geometries (not just scaled copies): 20/solid ("mini",
// tuned for small sizes) and 24/solid (tuned for larger sizes). Picked by requested
// size, unless `set` forces one explicitly (e.g. to keep the 24 geometry at a small
// rendered size instead of falling back to the mini set).
function icon(name, size = 20, extraClass = "", set) {
  const resolvedSet = set || (size >= 22 ? 24 : 20);
  const filePath = path.join(HEROICONS_DIR, String(resolvedSet), "solid", `${name}.svg`);
  const svg = readIconFile(filePath);
  const attrs = `width="${size}" height="${size}"${extraClass ? ` class="${extraClass}"` : ""}`;
  return svg.replace("<svg ", `<svg ${attrs} `);
}

function brandIcon(name, size = 20, extraClass = "") {
  const filePath = path.join(SIMPLE_ICONS_DIR, `${name.toLowerCase()}.svg`);
  const svg = readIconFile(filePath).replace(/<title>.*?<\/title>/s, "");
  const attrs = `width="${size}" height="${size}" fill="currentColor" aria-hidden="true"${extraClass ? ` class="${extraClass}"` : ""}`;
  return svg.replace("<svg ", `<svg ${attrs} `);
}

module.exports = function (eleventyConfig) {
  eleventyConfig.setUseGitIgnore(false);
  eleventyConfig.setServerPassthroughCopyBehavior("copy");

  // Icons — usage: {% icon "chevron-down" %} / {% icon "bars-3", 22 %} / {% brandIcon "facebook", 16, "c-footer__icon" %}
  eleventyConfig.addShortcode("icon", icon);
  eleventyConfig.addShortcode("brandIcon", brandIcon);

  // Passthrough copies
  eleventyConfig.addPassthroughCopy("src/css");
  eleventyConfig.addPassthroughCopy("src/js");
  eleventyConfig.addPassthroughCopy("src/assets");
  eleventyConfig.addPassthroughCopy("src/uploads");
  eleventyConfig.addPassthroughCopy({ "src/.htaccess": ".htaccess" });

  // Gallery lightbox (GLightbox) — vendored dist files, loaded only on pages with `hasGallery: true`
  eleventyConfig.addPassthroughCopy({ "node_modules/glightbox/dist/css/glightbox.min.css": "css/vendor/glightbox.min.css" });
  eleventyConfig.addPassthroughCopy({ "node_modules/glightbox/dist/js/glightbox.min.js": "js/vendor/glightbox.min.js" });

  // Leaflet map — vendored dist files, loaded only on pages with `hasMap: true` (e.g. Kluby).
  // Marker/zoom-control icons copied next to the CSS as "images/…", matching leaflet.css's own
  // relative url(images/…) references; main.js also points L.Icon.Default at this same path.
  eleventyConfig.addPassthroughCopy({ "node_modules/leaflet/dist/leaflet.css": "css/vendor/leaflet.css" });
  eleventyConfig.addPassthroughCopy({ "node_modules/leaflet/dist/leaflet.js": "js/vendor/leaflet.js" });
  eleventyConfig.addPassthroughCopy({ "node_modules/leaflet/dist/images": "css/vendor/images" });

  // Date filters
  eleventyConfig.addFilter("isoDate", (d) => new Date(d).toISOString().slice(0, 10));
  eleventyConfig.addFilter("czDate", (d) => {
    const date = new Date(d);
    return `${date.getDate()}. ${date.getMonth() + 1}. ${date.getFullYear()}`;
  });

  // URL & string filters
  eleventyConfig.addFilter("absoluteUrl", (path, base = "https://poolbilliard.cz") =>
    new URL(path, base).toString()
  );

  // {{ "Billiard Club Harlequin Praha" | initials }} -> "BCH" — badge label for herna cards
  eleventyConfig.addFilter("initials", (name, max = 3) =>
    String(name)
      .split(/\s+/)
      .filter(Boolean)
      .slice(0, max)
      .map((word) => word[0].toUpperCase())
      .join("")
  );

  // Number filters — usage: {% for page in totalPages | range %} (Nunjucks has no built-in range())
  eleventyConfig.addFilter("range", (n) => Array.from({ length: n }, (_, i) => i + 1));

  return {
    dir: {
      input: "src",
      includes: "_includes",
      data: "_data",
      output: "_site",
    },
    htmlTemplateEngine: "njk",
    markdownTemplateEngine: "njk",
  };
};

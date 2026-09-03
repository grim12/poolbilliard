module.exports = function (eleventyConfig) {
  eleventyConfig.setUseGitIgnore(false);
  eleventyConfig.setServerPassthroughCopyBehavior("copy");

  // Passthrough copies
  eleventyConfig.addPassthroughCopy("src/css");
  eleventyConfig.addPassthroughCopy("src/js");
  eleventyConfig.addPassthroughCopy("src/assets");

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

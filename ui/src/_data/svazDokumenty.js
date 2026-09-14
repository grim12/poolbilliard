const YEARS = ["2026", "2025", "2024", "2023", "2022", "2021", "2020", "2019", "2018", "2017", "2016", "2015", "2014"];
const ACTIVE_YEAR = YEARS[0];

// Same document taxonomy every year (the section reissues these each season) — only the
// year in the filename and, in reality, the file sizes would differ.
function yearGroups(year) {
  return [
    {
      title: "Soutěžní předpisy",
      count: 4,
      files: [
        { name: `Soutěžní řád ${year}`, meta: "PDF · 850 KB" },
        { name: `Prováděcí předpis soutěžního řádu ${year}`, meta: "PDF · 724 KB" },
        { name: `Etický kodex hráče ${year}`, meta: "PDF · 391 KB" },
        { name: `Kalendář soutěží ${year}`, meta: "PDF · 431 KB" },
      ],
    },
    {
      title: "Zápisy ze schůzí VVS",
      count: 1,
      files: [{ name: `Zápis ze schůze VVS ${year}`, meta: "PDF · 210 KB" }],
    },
    {
      title: "Zápisy z VH sekce",
      count: 1,
      files: [{ name: `Zápis z valné hromady sekce ${year}`, meta: "PDF · 198 KB" }],
    },
    {
      title: "Hospodaření sekce",
      count: 1,
      files: [{ name: `Hospodaření sekce ${year}`, meta: "PDF · 156 KB" }],
    },
  ];
}

module.exports = function () {
  const years = YEARS.map((year) => ({
    label: year,
    active: year === ACTIVE_YEAR,
    groups: yearGroups(year),
  }));

  // Undated, evergreen documents (statutes, forms) — not tied to a competition season.
  years.push({
    label: "Obecné",
    active: false,
    groups: [
      {
        title: "Základní dokumenty",
        count: 3,
        files: [
          { name: "Stanovy ČMBS", meta: "PDF · 512 KB" },
          { name: "Registrační řád", meta: "PDF · 340 KB" },
          { name: "Etický kodex hráče", meta: "PDF · 280 KB" },
        ],
      },
      {
        title: "Formuláře",
        count: 3,
        files: [
          { name: "Přihláška do klubu", meta: "PDF · 120 KB" },
          { name: "Žádost o hostování", meta: "PDF · 118 KB" },
          { name: "Žádost o přestup", meta: "PDF · 122 KB" },
        ],
      },
    ],
  });

  return years;
};

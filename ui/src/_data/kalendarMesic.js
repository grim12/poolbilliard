const events = require("./kalendarUdalosti.json");

const WEEKDAY_LABELS = ["PO", "ÚT", "ST", "ČT", "PÁ", "SO", "NE"];
const TODAY_ISO = "2026-09-10";

function toISO(date) {
  return date.toISOString().slice(0, 10);
}

function addDays(iso, days) {
  const date = new Date(`${iso}T00:00:00Z`);
  date.setUTCDate(date.getUTCDate() + days);
  return toISO(date);
}

// All ISO dates an event touches, clamped to the displayed month —
// a multi-day event (e.g. a weekend cup) gets a pill on every day it spans.
function eventDatesInMonth(event, monthStartIso, monthEndIso) {
  const start = event.startDate > monthStartIso ? event.startDate : monthStartIso;
  const end = event.endDate < monthEndIso ? event.endDate : monthEndIso;
  const dates = [];
  for (let iso = start; iso <= end; iso = addDays(iso, 1)) {
    dates.push(iso);
  }
  return dates;
}

function buildMonth(year, monthIndex, label) {
  const first = new Date(Date.UTC(year, monthIndex, 1));
  const startWeekday = (first.getUTCDay() + 6) % 7; // Monday = 0 .. Sunday = 6
  const daysInMonth = new Date(Date.UTC(year, monthIndex + 1, 0)).getUTCDate();
  const monthStartIso = toISO(first);
  const monthEndIso = toISO(new Date(Date.UTC(year, monthIndex, daysInMonth)));

  const eventsByDate = new Map();
  for (const event of events) {
    if (event.endDate < monthStartIso || event.startDate > monthEndIso) continue;
    for (const iso of eventDatesInMonth(event, monthStartIso, monthEndIso)) {
      if (!eventsByDate.has(iso)) eventsByDate.set(iso, []);
      eventsByDate.get(iso).push(event);
    }
  }

  const cells = [];
  for (let i = 0; i < startWeekday; i++) cells.push(null);
  for (let day = 1; day <= daysInMonth; day++) {
    const date = new Date(Date.UTC(year, monthIndex, day));
    const iso = toISO(date);
    const weekday = (date.getUTCDay() + 6) % 7;
    cells.push({
      day,
      iso,
      isToday: iso === TODAY_ISO,
      isWeekend: weekday >= 5,
      events: (eventsByDate.get(iso) || []).map((event) => ({
        title: event.shortTitle || event.title,
        url: event.url,
        tagColor: event.tagColor,
      })),
    });
  }
  while (cells.length % 7 !== 0) cells.push(null);

  const weeks = [];
  for (let i = 0; i < cells.length; i += 7) weeks.push(cells.slice(i, i + 7));

  return { label, weekdays: WEEKDAY_LABELS, weeks };
}

module.exports = function () {
  return buildMonth(2026, 8, "Září 2026");
};

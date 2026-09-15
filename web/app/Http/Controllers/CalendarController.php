<?php

namespace App\Http\Controllers;

use App\Models\RecurringTournament;
use App\Models\Tournament;
use App\Settings\KalendarSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CalendarController extends Controller
{
    /**
     * Mirrors ui/src/kalendar.njk. The Svaz/Klub/Zahraniční checkbox filter stays decorative
     * (same "not wired up yet" treatment as /novinky's category tabs) — only the month
     * mini-calendar's prev/next/today navigation is real, driven by a `month` (Y-m) query
     * param and built from actual Tournament dates, since that's cheap and not "filtering" in
     * the same sense (closer to /novinky's real pagination). This is the only place tournament
     * cards get a public listing page — there's no separate /turnaje anymore, Kalendář is the
     * hub (cards + calendar + recurring tournaments together).
     */
    public function index(Request $request, KalendarSettings $settings): View
    {
        $month = $request->query('month')
            ? Carbon::createFromFormat('Y-m', $request->query('month'))->startOfMonth()
            : now()->startOfMonth();

        return view('kalendar', [
            'settings' => $settings,
            'tournaments' => Tournament::with('category')->currentAndUpcoming()->orderedByStartDate()->get(),
            'calendarMonth' => $this->buildCalendarMonth($month),
            'recurringTournaments' => RecurringTournament::with('herna')->orderBy('sort_order')->get(),
        ]);
    }

    /**
     * Builds the month-grid data shape macros/calendar.njk's calendarMonth() expects (label,
     * weekdays, weeks of 7-cell rows), same logic as ui/'s build-time-only kalendarMesic.js but
     * against real Tournament rows instead of the mock JSON, and re-derivable for any month via
     * the `month` query param instead of being frozen to one hardcoded month.
     *
     * @return array{label: string, weekdays: list<string>, weeks: array, prevUrl: string, nextUrl: string, todayUrl: string}
     */
    private function buildCalendarMonth(Carbon $month): array
    {
        $monthStart = $month->copy()->startOfMonth();
        $monthEnd = $month->copy()->endOfMonth();

        $tournaments = Tournament::with('category')
            ->whereNotNull('start_date')
            ->where('start_date', '<=', $monthEnd->toDateString())
            ->whereRaw('COALESCE(end_date, start_date) >= ?', [$monthStart->toDateString()])
            ->get();

        $eventsByDay = [];
        foreach ($tournaments as $tournament) {
            $start = $tournament->start_date->max($monthStart);
            $end = ($tournament->end_date ?? $tournament->start_date)->min($monthEnd);

            for ($day = $start->copy(); $day->lte($end); $day->addDay()) {
                $eventsByDay[$day->toDateString()][] = [
                    'title' => $tournament->title,
                    'url' => route('turnaj.show', $tournament),
                    'tagColor' => $tournament->category?->color ?? 'primary',
                ];
            }
        }

        $today = now()->toDateString();
        $cells = array_fill(0, $monthStart->dayOfWeekIso - 1, null);

        for ($day = $monthStart->copy(); $day->lte($monthEnd); $day->addDay()) {
            $iso = $day->toDateString();
            $cells[] = [
                'day' => $day->day,
                'iso' => $iso,
                'isToday' => $iso === $today,
                'isWeekend' => $day->isWeekend(),
                'events' => $eventsByDay[$iso] ?? [],
            ];
        }

        while (count($cells) % 7 !== 0) {
            $cells[] = null;
        }

        return [
            'label' => Str::ucfirst($monthStart->locale('cs')->translatedFormat('F Y')),
            'weekdays' => ['PO', 'ÚT', 'ST', 'ČT', 'PÁ', 'SO', 'NE'],
            'weeks' => array_chunk($cells, 7),
            'prevUrl' => route('kalendar', ['month' => $monthStart->copy()->subMonth()->format('Y-m')]),
            'nextUrl' => route('kalendar', ['month' => $monthStart->copy()->addMonth()->format('Y-m')]),
            'todayUrl' => route('kalendar'),
        ];
    }
}

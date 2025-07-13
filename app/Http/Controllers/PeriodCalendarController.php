<?php

namespace App\Http\Controllers;

use App\Models\PeriodCalendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PeriodCalendarController extends Controller
{
    /**
     * Display a listing of the period calendar.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Check if user is authorized (only iva@wuvu.com)
        if ($user->email !== 'iva@wuvu.com') {
            abort(403, 'Unauthorized access to period calendar.');
        }

        // Set Carbon locale for European formatting
        Carbon::setLocale('en_EU');

        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $currentDate = Carbon::create($year, $month, 1);
        $periods = PeriodCalendar::getPeriodsForMonth($user->id, $year, $month);

        // Generate calendar data
        $calendarData = $this->generateCalendarData($currentDate, $periods);

        // Get next predicted period
        $latestPeriod = PeriodCalendar::getLatestForUser($user->id);
        $nextPeriod = $latestPeriod ? $latestPeriod->next_period_date : null;

        return view('period-calendar.index', compact('calendarData', 'currentDate', 'periods', 'nextPeriod'));
    }

    /**
     * Show the form for creating a new period entry.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->email !== 'iva@wuvu.com') {
            abort(403, 'Unauthorized access to period calendar.');
        }

        return view('period-calendar.create');
    }

    /**
     * Store a newly created period entry.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->email !== 'iva@wuvu.com') {
            abort(403, 'Unauthorized access to period calendar.');
        }

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'cycle_length' => 'required|integer|min:20|max:40',
            'period_length' => 'required|integer|min:1|max:10',
            'flow_intensity' => 'required|in:light,normal,heavy',
            'symptoms' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Convert dates to proper timezone (treat input as user's local date)
        $validated['start_date'] = Carbon::createFromFormat('Y-m-d', $validated['start_date'])->startOfDay();
        if ($validated['end_date']) {
            $validated['end_date'] = Carbon::createFromFormat('Y-m-d', $validated['end_date'])->startOfDay();
        }

        $validated['user_id'] = $user->id;

        PeriodCalendar::create($validated);

        return redirect()->route('period-calendar.index')
            ->with('success', 'Period entry added successfully.');
    }

    /**
     * Display the specified period entry.
     */
    public function show(PeriodCalendar $periodCalendar)
    {
        $user = Auth::user();

        if ($user->email !== 'iva@wuvu.com' || $periodCalendar->user_id !== $user->id) {
            abort(403, 'Unauthorized access to period calendar.');
        }

        return view('period-calendar.show', compact('periodCalendar'));
    }

    /**
     * Show the form for editing the specified period entry.
     */
    public function edit(PeriodCalendar $periodCalendar)
    {
        $user = Auth::user();

        if ($user->email !== 'iva@wuvu.com' || $periodCalendar->user_id !== $user->id) {
            abort(403, 'Unauthorized access to period calendar.');
        }

        return view('period-calendar.edit', compact('periodCalendar'));
    }

    /**
     * Update the specified period entry.
     */
    public function update(Request $request, PeriodCalendar $periodCalendar)
    {
        $user = Auth::user();

        if ($user->email !== 'iva@wuvu.com' || $periodCalendar->user_id !== $user->id) {
            abort(403, 'Unauthorized access to period calendar.');
        }

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'cycle_length' => 'required|integer|min:20|max:40',
            'period_length' => 'required|integer|min:1|max:10',
            'flow_intensity' => 'required|in:light,normal,heavy',
            'symptoms' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Convert dates to proper timezone (treat input as user's local date)
        $validated['start_date'] = Carbon::createFromFormat('Y-m-d', $validated['start_date'])->startOfDay();
        if ($validated['end_date']) {
            $validated['end_date'] = Carbon::createFromFormat('Y-m-d', $validated['end_date'])->startOfDay();
        }

        $periodCalendar->update($validated);

        return redirect()->route('period-calendar.index')
            ->with('success', 'Period entry updated successfully.');
    }

    /**
     * Remove the specified period entry.
     */
    public function destroy(PeriodCalendar $periodCalendar)
    {
        $user = Auth::user();

        if ($user->email !== 'iva@wuvu.com' || $periodCalendar->user_id !== $user->id) {
            abort(403, 'Unauthorized access to period calendar.');
        }

        $periodCalendar->delete();

        return redirect()->route('period-calendar.index')
            ->with('success', 'Period entry deleted successfully.');
    }

    /**
     * Generate calendar data for the given month.
     */
    private function generateCalendarData(Carbon $currentDate, $periods)
    {
        $startOfMonth = $currentDate->copy()->startOfMonth();
        $endOfMonth = $currentDate->copy()->endOfMonth();

        // Get the first day of the calendar (might be from previous month)
        // Force Sunday as the first day of the week to match the calendar header
        $startOfCalendar = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);

        // Get the last day of the calendar (might be from next month)
        $endOfCalendar = $endOfMonth->copy()->endOfWeek(Carbon::MONDAY);

        // Get the latest period to predict next period
        $latestPeriod = $periods->sortByDesc('start_date')->first();
        $predictedPeriods = [];

        if ($latestPeriod) {
            // Generate predicted periods for the next 6 months
            $nextPeriodDate = $latestPeriod->next_period_date;
            $cycleLength = $latestPeriod->cycle_length;
            $periodLength = $latestPeriod->period_length;

            for ($i = 0; $i < 6; $i++) {
                $predictedStart = $nextPeriodDate->copy()->addDays($cycleLength * $i);
                $predictedEnd = $predictedStart->copy()->addDays($periodLength - 1);

                // Only include if it falls within our calendar range
                if ($predictedStart <= $endOfCalendar && $predictedEnd >= $startOfCalendar) {
                    $predictedPeriods[] = [
                        'start_date' => $predictedStart,
                        'end_date' => $predictedEnd,
                        'cycle_length' => $cycleLength,
                        'period_length' => $periodLength,
                    ];
                }
            }
        }

        $calendarData = [];
        $currentCalendarDate = $startOfCalendar->copy();

        while ($currentCalendarDate <= $endOfCalendar) {
            $dayData = [
                'date' => $currentCalendarDate->copy(),
                'is_current_month' => $currentCalendarDate->month === $currentDate->month,
                'is_today' => $currentCalendarDate->isToday(),
                'is_period' => false,
                'is_fertility_window' => false,
                'is_ovulation' => false,
                'is_predicted_period' => false,
                'period_entry' => null,
            ];

            // Check if this date falls within any actual period
            foreach ($periods as $period) {
                if ($period->isWithinPeriod($currentCalendarDate)) {
                    $dayData['is_period'] = true;
                    $dayData['period_entry'] = $period;
                }

                if ($period->isWithinFertilityWindow($currentCalendarDate)) {
                    $dayData['is_fertility_window'] = true;
                }

                if ($currentCalendarDate->toDateString() === $period->ovulation_date->toDateString()) {
                    $dayData['is_ovulation'] = true;
                }
            }

            // Check if this date falls within any predicted period (only if not an actual period)
            if (!$dayData['is_period']) {
                foreach ($predictedPeriods as $predictedPeriod) {
                    if (
                        $currentCalendarDate->toDateString() >= $predictedPeriod['start_date']->toDateString() &&
                        $currentCalendarDate->toDateString() <= $predictedPeriod['end_date']->toDateString()
                    ) {
                        $dayData['is_predicted_period'] = true;
                        break;
                    }
                }
            }

            $calendarData[] = $dayData;
            $currentCalendarDate->addDay();
        }

        return $calendarData;
    }
}

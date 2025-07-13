@extends('layouts.app')

@section('content')
    <div class="py-4 sm:py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div
                 class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                <div class="p-4 sm:p-6 bg-gradient-to-r from-pink-500 to-purple-600 text-white">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold">Period Calendar</h1>
                            <p class="text-pink-100 mt-1 text-sm sm:text-base">Track your cycle and stay informed</p>
                        </div>
                        <div class="flex justify-center sm:justify-end">
                            <a href="{{ route('period-calendar.create') }}"
                               class="bg-white text-pink-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-50 transition-colors text-sm sm:text-base">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Period
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar Navigation -->
            <div
                 class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0 mb-4">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white text-center sm:text-left">
                            {{ $currentDate->format('F Y') }}
                        </h2>
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('period-calendar.index', ['year' => $currentDate->copy()->subMonth()->year, 'month' => $currentDate->copy()->subMonth()->month]) }}"
                               class="px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-sm">
                                ← Prev
                            </a>
                            <a href="{{ route('period-calendar.index', ['year' => now()->year, 'month' => now()->month]) }}"
                               class="px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors text-sm">
                                Today
                            </a>
                            <a href="{{ route('period-calendar.index', ['year' => $currentDate->copy()->addMonth()->year, 'month' => $currentDate->copy()->addMonth()->month]) }}"
                               class="px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-sm">
                                Next →
                            </a>
                        </div>
                    </div>

                    <!-- Calendar Grid -->
                    <div class="grid grid-cols-7 gap-1 mb-2">
                        <!-- Day headers -->
                        @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                            <div class="p-2 text-center text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ $day }}
                            </div>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-7 gap-1">
                        @foreach ($calendarData as $dayData)
                            <div
                                 class="min-h-[60px] sm:min-h-[80px] p-1 sm:p-2 border border-gray-200 dark:border-gray-700 rounded-lg
                            @if (!$dayData['is_current_month']) bg-gray-50 dark:bg-gray-900 @else bg-white dark:bg-gray-800 @endif
                            @if ($dayData['is_today']) ring-2 ring-blue-500 @endif
                            @if ($dayData['is_period']) bg-red-100 dark:bg-red-900 @endif
                            @if ($dayData['is_predicted_period'] && !$dayData['is_period']) bg-red-50 dark:bg-red-950 border-red-200 dark:border-red-800 border-dashed @endif
                            @if ($dayData['is_fertility_window'] && !$dayData['is_period'] && !$dayData['is_predicted_period']) bg-green-100 dark:bg-green-900 @endif
                            @if ($dayData['is_ovulation']) bg-yellow-100 dark:bg-yellow-900 @endif
                            hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">

                                <div class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white mb-1">
                                    {{ $dayData['date']->format('j') }}
                                </div>

                                <div class="space-y-1">
                                    @if ($dayData['is_period'])
                                        <div class="text-xs bg-red-500 text-white px-1 sm:px-2 py-0.5 sm:py-1 rounded">
                                            <span class="hidden sm:inline">Period</span>
                                            <span class="sm:hidden">P</span>
                                        </div>
                                    @endif

                                    @if ($dayData['is_predicted_period'] && !$dayData['is_period'])
                                        <div
                                             class="text-xs bg-red-300 text-red-800 dark:bg-red-800 dark:text-red-200 px-1 sm:px-2 py-0.5 sm:py-1 rounded border border-red-400 dark:border-red-600">
                                            <span class="hidden sm:inline">Expected</span>
                                            <span class="sm:hidden">E</span>
                                        </div>
                                    @endif

                                    @if ($dayData['is_ovulation'])
                                        <div class="text-xs bg-yellow-500 text-white px-1 sm:px-2 py-0.5 sm:py-1 rounded">
                                            <span class="hidden sm:inline">Ovulation</span>
                                            <span class="sm:hidden">O</span>
                                        </div>
                                    @endif

                                    @if (
                                        $dayData['is_fertility_window'] &&
                                            !$dayData['is_period'] &&
                                            !$dayData['is_ovulation'] &&
                                            !$dayData['is_predicted_period']
                                    )
                                        <div class="text-xs bg-green-500 text-white px-1 sm:px-2 py-0.5 sm:py-1 rounded">
                                            <span class="hidden sm:inline">Fertile</span>
                                            <span class="sm:hidden">F</span>
                                        </div>
                                    @endif

                                    @if ($dayData['period_entry'])
                                        <div class="text-xs text-gray-600 dark:text-gray-400 hidden sm:block">
                                            {{ ucfirst($dayData['period_entry']->flow_intensity) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Legend and Info -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <!-- Legend -->
                <div
                     class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-white mb-4">Legend</h3>
                        <div class="grid grid-cols-2 lg:grid-cols-1 gap-3">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-red-500 rounded mr-3"></div>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Period Days</span>
                            </div>
                            <div class="flex items-center">
                                <div
                                     class="w-4 h-4 bg-red-50 dark:bg-red-950 border border-red-200 dark:border-red-800 border-dashed rounded mr-3">
                                </div>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Expected Period</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-yellow-500 rounded mr-3"></div>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Ovulation Day</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-green-500 rounded mr-3"></div>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Fertility Window</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-blue-500 rounded mr-3"></div>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Today</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Next Period Prediction -->
                <div
                     class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-white mb-4">Predictions</h3>
                        @if ($nextPeriod)
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Next Period:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $nextPeriod->format('M j, Y') }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Days Until:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ now()->diffInDays($nextPeriod, false) }} days
                                    </span>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">Add a period entry to see predictions.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Periods -->
            @if ($periods->count() > 0)
                <div
                     class="mt-4 sm:mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Periods</h3>
                        <div class="space-y-3">
                            @foreach ($periods->take(5) as $period)
                                <div
                                     class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg space-y-2 sm:space-y-0">
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $period->start_date->format('M j, Y') }}
                                            @if ($period->end_date)
                                                - {{ $period->end_date->format('M j, Y') }}
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ ucfirst($period->flow_intensity) }} flow
                                            @if ($period->symptoms)
                                                • {{ Str::limit($period->symptoms, 20) }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex space-x-2 justify-end sm:justify-start">
                                        <a href="{{ route('period-calendar.edit', $period) }}"
                                           class="text-blue-600 hover:text-blue-800 text-sm">Edit</a>
                                        <form method="POST" action="{{ route('period-calendar.destroy', $period) }}"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Are you sure you want to delete this period entry?')"
                                                    class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

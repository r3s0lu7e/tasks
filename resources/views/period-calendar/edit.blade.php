@extends('layouts.app')

@section('content')
    <div class="py-4 sm:py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div
                 class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                <div class="p-4 sm:p-6 bg-gradient-to-r from-pink-500 to-purple-600 text-white">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold">Edit Period Entry</h1>
                            <p class="text-pink-100 mt-1 text-sm sm:text-base">Update your menstrual cycle information</p>
                        </div>
                        <a href="{{ route('period-calendar.index') }}"
                           class="bg-white text-pink-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-50 transition-colors text-sm sm:text-base self-center sm:self-auto">
                            Back to Calendar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div
                 class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-4 sm:p-6">
                    <form method="POST" action="{{ route('period-calendar.update', $periodCalendar) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Start Date -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Start Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="start_date" name="start_date"
                                   value="{{ old('start_date', $periodCalendar->start_date->format('Y-m-d')) }}" required
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('start_date') border-red-300 @enderror">
                            @error('start_date')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                End Date (Optional)
                            </label>
                            <input type="date" id="end_date" name="end_date"
                                   value="{{ old('end_date', $periodCalendar->end_date ? $periodCalendar->end_date->format('Y-m-d') : '') }}"
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('end_date') border-red-300 @enderror">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Leave empty if period is ongoing</p>
                            @error('end_date')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cycle Length -->
                        <div>
                            <label for="cycle_length" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Cycle Length (days) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="cycle_length" name="cycle_length"
                                   value="{{ old('cycle_length', $periodCalendar->cycle_length) }}" min="20"
                                   max="40" required
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('cycle_length') border-red-300 @enderror">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Average days between periods (20-40
                                days)</p>
                            @error('cycle_length')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Period Length -->
                        <div>
                            <label for="period_length" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Period Length (days) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="period_length" name="period_length"
                                   value="{{ old('period_length', $periodCalendar->period_length) }}" min="1"
                                   max="10" required
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('period_length') border-red-300 @enderror">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Typical duration of your period (1-10
                                days)</p>
                            @error('period_length')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Flow Intensity -->
                        <div>
                            <label for="flow_intensity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Flow Intensity <span class="text-red-500">*</span>
                            </label>
                            <select id="flow_intensity" name="flow_intensity" required
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('flow_intensity') border-red-300 @enderror">
                                <option value="">Select intensity</option>
                                <option value="light"
                                        {{ old('flow_intensity', $periodCalendar->flow_intensity) == 'light' ? 'selected' : '' }}>
                                    Light</option>
                                <option value="normal"
                                        {{ old('flow_intensity', $periodCalendar->flow_intensity) == 'normal' ? 'selected' : '' }}>
                                    Normal</option>
                                <option value="heavy"
                                        {{ old('flow_intensity', $periodCalendar->flow_intensity) == 'heavy' ? 'selected' : '' }}>
                                    Heavy</option>
                            </select>
                            @error('flow_intensity')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Symptoms -->
                        <div>
                            <label for="symptoms" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Symptoms
                            </label>
                            <textarea id="symptoms" name="symptoms" rows="3" placeholder="e.g., cramps, headache, mood changes..."
                                      class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('symptoms') border-red-300 @enderror">{{ old('symptoms', $periodCalendar->symptoms) }}</textarea>
                            @error('symptoms')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Notes
                            </label>
                            <textarea id="notes" name="notes" rows="3" placeholder="Additional notes about this period..."
                                      class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:text-white sm:text-sm @error('notes') border-red-300 @enderror">{{ old('notes', $periodCalendar->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('period-calendar.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-pink-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-pink-700 focus:bg-pink-700 active:bg-pink-900 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Update Period Entry
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

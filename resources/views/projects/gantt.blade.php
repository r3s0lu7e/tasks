@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $project->name }} - Gantt Chart</h1>
                <a href="{{ route('projects.show', $project) }}" class="text-blue-500 hover:underline">Back to Project</a>
            </div>

            <div id="gantt"></div>
        </div>
    </div>

    <style>
        /* Add any additional styling for the gantt chart here if needed */
        .gantt .bar-label {
            font-weight: bold;
        }
    </style>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/frappe-gantt/dist/frappe-gantt.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt/dist/frappe-gantt.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tasks = {!! $tasks !!};

            if (tasks.length > 0) {
                var gantt = new Gantt("#gantt", tasks, {
                    header_height: 50,
                    column_width: 30,
                    step: 24,
                    view_modes: ['Quarter Day', 'Half Day', 'Day', 'Week', 'Month'],
                    bar_height: 20,
                    bar_corner_radius: 3,
                    arrow_curve: 5,
                    padding: 18,
                    view_mode: 'Week',
                    date_format: 'YYYY-MM-DD',
                    language: 'en',
                    custom_popup_html: function(task) {
                        return `
                        <div class="details-container">
                            <h5>${task.name}</h5>
                            <p>Start: ${task.start}</p>
                            <p>End: ${task.end}</p>
                            <p>Progress: ${task.progress}%</p>
                        </div>
                    `;
                    }
                });
            } else {
                document.getElementById('gantt').innerHTML =
                    '<p class="text-gray-500">No tasks in this project to display in the Gantt chart.</p>';
            }
        });
    </script>
@endpush

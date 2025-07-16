@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Header & Controls -->
            <div
                 class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200 dark:border-gray-700">
                <div class="p-6 bg-gradient-to-r from-blue-500 to-purple-600 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold">Welcome back, {{ Auth::user()->name }}!</h1>
                            <p class="text-blue-100 mt-1">Manage your team and track project progress</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div id="dashboard-controls">
                                <button id="edit-layout-btn"
                                        class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                    <i class="fa-solid fa-pen-to-square mr-2"></i>Edit Layout
                                </button>
                                <button id="save-layout-btn"
                                        class="hidden bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                    <i class="fa-solid fa-check mr-2"></i>Save
                                </button>
                                <button id="cancel-layout-btn"
                                        class="hidden bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                    <i class="fa-solid fa-xmark mr-2"></i>Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid-stack">
                @foreach ($layout as $widget)
                    <div class="grid-stack-item" gs-id="{{ $widget['id'] }}" gs-x="{{ $widget['x'] }}"
                         gs-y="{{ $widget['y'] }}" gs-w="{{ $widget['w'] }}" gs-h="{{ $widget['h'] }}">
                        <div class="grid-stack-item-content">
                            @include('dashboard.widgets.' . $widget['id'])
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/gridstack@9.5.1/dist/gridstack.min.css" />
    <style>
        .grid-stack-item-content {
            background-color: #fff;
            border-radius: .5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, .1), 0 1px 2px 0 rgba(0, 0, 0, .06);
            overflow: hidden;
        }

        .dark .grid-stack-item-content {
            background-color: #1f2937;
        }

        .grid-stack-item.ui-draggable-dragging,
        .grid-stack-item.ui-resizable-resizing {
            z-index: 100;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/gridstack@9.5.1/dist/gridstack-all.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editBtn = document.getElementById('edit-layout-btn');
            const saveBtn = document.getElementById('save-layout-btn');
            const cancelBtn = document.getElementById('cancel-layout-btn');

            let grid = GridStack.init({
                float: true,
                cellHeight: 70,
                margin: 10,
                staticGrid: true,
            });

            const savedLayout = {!! json_encode($layout, JSON_PRETTY_PRINT) !!};

            editBtn.addEventListener('click', function() {
                grid.setStatic(false);
                editBtn.classList.add('hidden');
                saveBtn.classList.remove('hidden');
                cancelBtn.classList.remove('hidden');
            });

            cancelBtn.addEventListener('click', function() {
                grid.load(savedLayout);
                grid.setStatic(true);
                editBtn.classList.remove('hidden');
                saveBtn.classList.add('hidden');
                cancelBtn.classList.add('hidden');
            });

            saveBtn.addEventListener('click', function() {
                const newLayout = grid.save();

                grid.setStatic(true);
                editBtn.classList.remove('hidden');
                saveBtn.classList.add('hidden');
                cancelBtn.classList.add('hidden');

                fetch('{{ route('profile.dashboard-layout') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: JSON.stringify({
                        layout: newLayout
                    })
                }).then(response => {
                    // You might want to add a success notification here
                });
            });
        });
    </script>
@endpush

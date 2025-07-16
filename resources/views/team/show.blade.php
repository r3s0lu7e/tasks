@extends('layouts.app')

@section('content')
    <!-- Heart Animation Container -->
    @if ($team->email === 'r3s0lu7e@gmail.com' || $team->email === 'iva@wuvu.com')
        <div id="heart-container"
             class="fixed inset-0 pointer-events-none z-[9999] opacity-0 transition-opacity duration-1000"
             style="overflow: visible !important;">
            <div id="heart-half" class="relative w-full h-full" style="overflow: visible !important;">
                <!-- This will be dynamically filled with the appropriate half of the heart -->
            </div>
        </div>
    @endif

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="h-16 w-16 rounded-full flex items-center justify-center"
                                 style="background-color: rgba({{ $team->status_color_rgb }}, 0.2)">
                                <span class="text-2xl font-medium" style="color: {{ $team->status_color }}">
                                    {{ $team->getInitials() }}
                                </span>
                            </div>
                            <div class="ml-6">
                                <h2 class="font-semibold text-2xl text-gray-800 dark:text-white leading-tight">
                                    {{ $team->name }}
                                </h2>
                                <p class="text-gray-600 dark:text-gray-400">{{ $team->email }}</p>
                                <div class="flex items-center space-x-4 mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                          style="background-color: rgba({{ $team->status_color_rgb }}, 0.2); color: {{ $team->status_color }}">
                                        {{ ucfirst($team->status) }}
                                    </span>
                                    <span
                                          class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst(str_replace('_', ' ', $team->role)) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('team.edit', $team) }}" class="btn btn-primary">
                                Edit Member
                            </a>
                            <a href="{{ route('team.index') }}" class="btn btn-secondary">
                                Back to Team
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Performance Metrics -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="font-semibold text-lg text-gray-800 dark:text-white">Performance Metrics</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Tasks</span>
                                    <span
                                          class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalTasks }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Completed</span>
                                    <span
                                          class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $completedTasks }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Current
                                        Workload</span>
                                    <span
                                          class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $currentWorkload }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Overdue Tasks</span>
                                    <span
                                          class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $overdueTasks }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Completion
                                        Rate</span>
                                    <span
                                          class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $completionRate }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Info -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="font-semibold text-lg text-gray-800 dark:text-white">Personal Information</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @if ($team->department)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Department</dt>
                                        <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $team->department }}</dd>
                                    </div>
                                @endif
                                @if ($team->phone)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                        <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $team->phone }}</dd>
                                    </div>
                                @endif
                                @if ($team->hourly_rate)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Hourly Rate</dt>
                                        <dd class="text-sm text-gray-900 dark:text-gray-200">${{ $team->hourly_rate }}</dd>
                                    </div>
                                @endif
                                @if ($team->hire_date)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Hire Date</dt>
                                        <dd class="text-sm text-gray-900 dark:text-gray-200">
                                            {{ $team->hire_date->format('d.m.Y') }}</dd>
                                    </div>
                                @endif
                                @if ($team->notes)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Notes</dt>
                                        <dd class="text-sm text-gray-900 dark:text-gray-200">{{ $team->notes }}</dd>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tasks -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="font-semibold text-lg text-gray-800 dark:text-white">Assigned Tasks</h3>
                        </div>
                        <div class="p-6">
                            @if ($team->assignedTasks->count() > 0)
                                <div class="space-y-4">
                                    @foreach ($team->assignedTasks as $task)
                                        <div
                                             class="flex items-start justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0 h-8 w-8 flex items-center justify-center rounded-full text-xs font-medium dark:text-gray-200"
                                                     style="background-color: {{ $task->type->color }};">
                                                    <i class="fa-solid {{ $task->type->icon }}"
                                                       style="color: {{ $task->type->icon_color }};"></i>
                                                </div>
                                                <div>
                                                    <a href="{{ route('tasks.show', $task) }}"
                                                       class="text-sm font-medium text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400">
                                                        {{ $task->title }}
                                                    </a>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        #{{ $task->id }} •
                                                        <a href="{{ route('projects.show', $task->project) }}"
                                                           class="hover:underline">{{ $task->project->name }}</a>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="text-right flex-shrink-0">
                                                <div class="flex items-center space-x-2">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium dark:text-gray-200"
                                                          style="background-color: rgba({{ $task->status->rgb_color }}, 0.2); color: {{ $task->status->color }}">
                                                        {{ $task->status->name }}
                                                    </span>
                                                    <span
                                                          class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ config('colors.task_priority')[$task->priority] ?? '' }}">
                                                        {{ ucfirst($task->priority) }}
                                                    </span>
                                                </div>
                                                @if ($task->due_date)
                                                    <p
                                                       class="mt-1 text-xs {{ $task->due_date->isPast() && !in_array($task->status->alias, ['completed', 'cancelled']) ? 'text-red-500 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}">
                                                        Due: {{ $task->due_date->format('d.m.Y') }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div
                                     class="text-center py-12 bg-gray-50 dark:bg-gray-700 rounded-lg shadow-inner col-span-1 lg:col-span-2">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No tasks assigned
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This team member has no
                                        assigned tasks yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@if ($team->email === 'r3s0lu7e@gmail.com' || $team->email === 'iva@wuvu.com')
    @push('scripts')
        <script>
            // Window proximity heart animation
            (function() {
                const channel = new BroadcastChannel('team_windows');
                const windowId = Math.random().toString(36).substr(2, 9);
                const currentTeamEmail = '{{ $team->email }}';
                let otherWindows = {};
                let heartVisible = false;
                let isLeftHalf = null;

                // Send window position periodically
                function broadcastPosition() {
                    const screenX = window.screenX || window.screenLeft || 0;
                    const screenY = window.screenY || window.screenTop || 0;
                    const width = window.outerWidth || window.innerWidth;
                    const height = window.outerHeight || window.innerHeight;

                    channel.postMessage({
                        type: 'position',
                        id: windowId,
                        teamEmail: currentTeamEmail,
                        x: screenX,
                        y: screenY,
                        width: width,
                        height: height,
                        timestamp: Date.now()
                    });
                }

                // Check if windows are close enough
                function checkProximity() {
                    const currentX = window.screenX || window.screenLeft || 0;
                    const currentY = window.screenY || window.screenTop || 0;
                    const currentWidth = window.outerWidth || window.innerWidth;

                    let closestGap = Infinity;
                    let closestWindow = null;
                    let isLeft = false;

                    for (const [id, data] of Object.entries(otherWindows)) {
                        // Remove stale windows (not updated in last 3 seconds)
                        if (Date.now() - data.timestamp > 3000) {
                            delete otherWindows[id];
                            continue;
                        }

                        // Only show heart if this window and the other window are the matching pair
                        const isMatchingPair = (currentTeamEmail === 'r3s0lu7e@gmail.com' && data.teamEmail ===
                                'iva@wuvu.com') ||
                            (currentTeamEmail === 'iva@wuvu.com' && data.teamEmail === 'r3s0lu7e@gmail.com');

                        if (!isMatchingPair) {
                            continue;
                        }

                        // Calculate distance between window edges
                        let horizontalGap;
                        let tempIsLeft = false;

                        if (currentX + currentWidth <= data.x + 50) {
                            // Current window is on the left (with 50px tolerance for overlapping)
                            horizontalGap = Math.max(0, data.x - (currentX + currentWidth));
                            tempIsLeft = true; // This window is on the left, so it should show the left half
                        } else if (data.x + data.width <= currentX + 50) {
                            // Current window is on the right (with 50px tolerance for overlapping)
                            horizontalGap = Math.max(0, currentX - (data.x + data.width));
                            tempIsLeft = false; // This window is on the right, so it should show the right half
                        } else {
                            // Windows are overlapping significantly (more than 50px)
                            continue;
                        }

                        const verticalAlignment = Math.abs(currentY - data.y);

                        // Check if windows are aligned vertically and within proximity range
                        if (horizontalGap < 300 && verticalAlignment < 150) {
                            if (horizontalGap < closestGap) {
                                closestGap = horizontalGap;
                                closestWindow = data;
                                isLeft = tempIsLeft;
                            }
                        }
                    }

                    if (closestWindow && closestGap < 300) {
                        // Calculate opacity based on distance (300px to 0px maps to 0.2 to 1.0 opacity)
                        // When gap is 0-50px, opacity is 1.0 (fully visible)
                        let opacity;
                        if (closestGap <= 50) {
                            opacity = 1; // Fully visible when very close
                        } else {
                            opacity = Math.max(0.2, Math.min(1, 1.2 - (closestGap / 250)));
                        }
                        const scale = 0.6 + (0.4 * (1 - closestGap / 300)); // Scale from 0.6 to 1.0

                        if (!heartVisible || isLeftHalf !== isLeft) {
                            showHeart(isLeft, opacity, scale);
                            isLeftHalf = isLeft;
                        } else {
                            // Update opacity and scale
                            updateHeartAppearance(opacity, scale);
                        }

                        // Send acknowledgment to ensure both windows show their hearts
                        channel.postMessage({
                            type: 'heart_active',
                            id: windowId,
                            showingHeart: true,
                            isLeft: isLeft,
                            opacity: opacity,
                            gap: closestGap
                        });
                    } else {
                        // Hide heart if no close windows
                        if (heartVisible) {
                            hideHeart();
                        }
                    }
                }

                // Show heart animation
                function showHeart(isLeft, opacity = 1, scale = 1) {
                    heartVisible = true;
                    const container = document.getElementById('heart-container');
                    const heartHalf = document.getElementById('heart-half');

                    if (!container || !heartHalf) {
                        return; // Exit if elements don't exist
                    }

                    // Create the appropriate half of the heart
                    if (isLeft) {
                        // Left window shows left half of heart at right edge - clipped to show only left half
                        heartHalf.innerHTML = `
                        <div class="fixed top-1/2 -translate-y-1/2" style="right: 0; width: 120px; height: 240px; overflow: hidden;">
                            <svg id="heart-svg" class="absolute" style="right: -120px; transform: scale(${scale}); opacity: ${opacity}; width: 240px; height: 240px;" viewBox="0 0 200 220">
                                <defs>
                                    <linearGradient id="heartGradientLeft" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#ff006e;stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:#ff4458;stop-opacity:1" />
                                    </linearGradient>
                                    <filter id="heartGlow">
                                        <feGaussianBlur stdDeviation="3" result="coloredBlur"/>
                                        <feMerge>
                                            <feMergeNode in="coloredBlur"/>
                                            <feMergeNode in="SourceGraphic"/>
                                        </feMerge>
                                    </filter>
                                </defs>
                                <path d="M 100,60 C 100,30 80,10 50,10 C 20,10 0,30 0,60 C 0,120 50,170 100,220 C 150,170 200,120 200,60 C 200,30 180,10 150,10 C 120,10 100,30 100,60 Z" 
                                      fill="url(#heartGradientLeft)" 
                                      filter="url(#heartGlow)" />
                            </svg>
                        </div>
                    `;
                    } else {
                        // Right window shows right half of heart at left edge - clipped to show only right half
                        heartHalf.innerHTML = `
                        <div class="fixed top-1/2 -translate-y-1/2" style="left: 0; width: 120px; height: 240px; overflow: hidden;">
                            <svg id="heart-svg" class="absolute" style="left: -120px; transform: scale(${scale}); opacity: ${opacity}; width: 240px; height: 240px;" viewBox="0 0 200 220">
                                <defs>
                                    <linearGradient id="heartGradientRight" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#ff4458;stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:#ff006e;stop-opacity:1" />
                                    </linearGradient>
                                    <filter id="heartGlow">
                                        <feGaussianBlur stdDeviation="3" result="coloredBlur"/>
                                        <feMerge>
                                            <feMergeNode in="coloredBlur"/>
                                            <feMergeNode in="SourceGraphic"/>
                                        </feMerge>
                                    </filter>
                                </defs>
                                <path d="M 100,60 C 100,30 80,10 50,10 C 20,10 0,30 0,60 C 0,120 50,170 100,220 C 150,170 200,120 200,60 C 200,30 180,10 150,10 C 120,10 100,30 100,60 Z" 
                                      fill="url(#heartGradientRight)" 
                                      filter="url(#heartGlow)" />
                            </svg>
                        </div>
                    `;
                    }

                    // Only add floating hearts when very close
                    if (opacity > 0.8) {
                        createFloatingHearts();
                    }

                    // Show container
                    container.style.display = 'block';
                    container.classList.remove('opacity-0');
                    container.classList.add('opacity-100');
                }

                // Update heart appearance without recreating
                function updateHeartAppearance(opacity, scale) {
                    const heartSvg = document.getElementById('heart-svg');
                    if (heartSvg) {
                        heartSvg.style.opacity = opacity;
                        heartSvg.style.transform = `scale(${scale})`;

                        // Add floating hearts when very close
                        if (opacity > 0.8 && document.querySelectorAll('.floating-heart').length === 0) {
                            createFloatingHearts();
                        }
                    }
                }

                // Hide heart animation
                function hideHeart() {
                    heartVisible = false;
                    isLeftHalf = null;
                    const container = document.getElementById('heart-container');
                    if (container) {
                        container.classList.remove('opacity-100');
                        container.classList.add('opacity-0');
                    }

                    // Remove floating hearts
                    const floatingHearts = document.querySelectorAll('.floating-heart');
                    floatingHearts.forEach(heart => heart.remove());
                }

                // Create floating hearts effect
                function createFloatingHearts() {
                    const colors = ['#ff006e', '#ff4458', '#ff6b6b', '#ee5a6f'];
                    const container = document.getElementById('heart-container');
                    if (!container) return;

                    for (let i = 0; i < 5; i++) {
                        setTimeout(() => {
                            const heart = document.createElement('div');
                            heart.className = 'floating-heart absolute pointer-events-none';
                            heart.style.left = Math.random() * 100 + '%';
                            heart.style.bottom = '-20px';
                            heart.style.color = colors[Math.floor(Math.random() * colors.length)];
                            heart.style.fontSize = (Math.random() * 20 + 10) + 'px';
                            heart.style.animation = `floatUp ${Math.random() * 3 + 2}s ease-out forwards`;
                            heart.innerHTML = '❤️';

                            container.appendChild(heart);

                            // Remove after animation
                            setTimeout(() => heart.remove(), 5000);
                        }, i * 200);
                    }
                }

                // Listen for messages from other windows
                channel.onmessage = (event) => {
                    if (event.data.type === 'position' && event.data.id !== windowId) {
                        otherWindows[event.data.id] = event.data;
                        checkProximity();
                    } else if (event.data.type === 'closing' && event.data.id !== windowId) {
                        delete otherWindows[event.data.id];
                        checkProximity();
                    } else if (event.data.type === 'heart_active' && event.data.id !== windowId) {
                        // Store the other window's heart state
                        if (otherWindows[event.data.id]) {
                            otherWindows[event.data.id].showingHeart = event.data.showingHeart;
                            otherWindows[event.data.id].heartIsLeft = event.data.isLeft;
                        }
                    }
                };

                // Start broadcasting position
                setInterval(broadcastPosition, 500);

                // Initial broadcast
                broadcastPosition();

                // Notify when closing
                window.addEventListener('beforeunload', () => {
                    channel.postMessage({
                        type: 'closing',
                        id: windowId
                    });
                });

                // Add CSS for floating hearts animation
                const style = document.createElement('style');
                style.textContent = `
                @keyframes floatUp {
                    0% {
                        transform: translateY(0) scale(1);
                        opacity: 1;
                    }
                    100% {
                        transform: translateY(-200px) scale(0.5);
                        opacity: 0;
                    }
                }
                
                #heart-container {
                    overflow: visible !important;
                }
                
                #heart-half {
                    overflow: visible !important;
                }
                
                #heart-svg {
                    transition: opacity 0.3s ease-out, transform 0.3s ease-out;
                }
                
                @keyframes heartbeat {
                    0%, 100% {
                        filter: brightness(1);
                    }
                    50% {
                        filter: brightness(1.2);
                    }
                }
            `;
                document.head.appendChild(style);
            })();
        </script>
    @endpush
@endif

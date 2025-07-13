<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ getWorkstationName() }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Prevent dark mode flash -->
    <script>
        // Initialize theme immediately to prevent flash
        (function() {
            try {
                if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                        '(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark')
                } else {
                    document.documentElement.classList.remove('dark')
                }
            } catch (e) {
                // Fallback to light mode if localStorage is not available
                document.documentElement.classList.remove('dark')
            }
        })()
    </script>
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}"
                               class="text-xl font-bold text-jira-blue dark:text-blue-400">
                                {{ getWorkstationName() }}
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            @auth
                                <a href="{{ route('dashboard') }}"
                                   class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-jira-blue text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600' }} text-sm font-medium">
                                    Dashboard
                                </a>
                                <a href="{{ route('projects.index') }}"
                                   class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('projects.*') ? 'border-jira-blue text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600' }} text-sm font-medium">
                                    Projects
                                </a>
                                <a href="{{ route('tasks.index') }}"
                                   class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('tasks.*') ? 'border-jira-blue text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600' }} text-sm font-medium">
                                    Tasks
                                </a>
                                <a href="{{ route('personal-notes.index') }}"
                                   class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('personal-notes.*') ? 'border-jira-blue text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600' }} text-sm font-medium">
                                    Notes
                                </a>
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('team.index') }}"
                                       class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('team.*') ? 'border-jira-blue text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600' }} text-sm font-medium">
                                        Team
                                    </a>
                                @endif

                                <!-- Search -->
                                <div class="flex items-center">
                                    <form method="GET" action="{{ route('search.index') }}" class="flex items-center">
                                        <input type="text" name="q" placeholder="Search..."
                                               class="w-64 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
                                        <button type="submit"
                                                class="ml-2 p-2 text-gray-400 dark:text-gray-300 hover:text-gray-600 dark:hover:text-gray-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endauth
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        @auth
                            <!-- Dark Mode Toggle -->
                            <button id="theme-toggle" type="button"
                                    class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 mr-3">
                                <svg id="theme-toggle-dark-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                </svg>
                                <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor"
                                     viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </button>

                            <!-- User Dropdown -->
                            <div class="ml-3 relative">
                                <button id="user-menu-button" type="button"
                                        class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-jira-blue"
                                        aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <div class="h-8 w-8 rounded-full bg-jira-blue flex items-center justify-center">
                                        <span class="text-white text-sm font-medium">
                                            {{ auth()->user()->getInitials() }}
                                        </span>
                                    </div>
                                </button>

                                <div id="user-menu-dropdown"
                                     class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-700 ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="py-1">
                                        <div
                                             class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-600">
                                            <div class="font-medium">{{ auth()->user()->name }}@if (auth()->user()->department)
                                                    - {{ auth()->user()->department }}
                                                @endif
                                            </div>
                                            <div class="text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</div>
                                        </div>
                                        <a href="{{ route('profile.show') }}"
                                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                                            Profile
                                        </a>
                                        <a href="{{ route('profile.edit') }}"
                                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                                            Settings
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                Sign out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex space-x-4">
                                <a href="{{ route('login') }}"
                                   class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-200 px-3 py-2 rounded-md text-sm font-medium">
                                    Sign in
                                </a>
                                <a href="{{ route('register') }}"
                                   class="bg-jira-blue text-white px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 dark:hover:bg-blue-600">
                                    Sign up
                                </a>
                            </div>
                        @endauth
                    </div>

                    <!-- Mobile menu button -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button id="mobile-menu-button" type="button"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-300 hover:text-gray-500 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 focus:text-gray-500 dark:focus:text-gray-200 transition duration-150 ease-in-out"
                                aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg id="mobile-menu-icon-open" class="block h-6 w-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg id="mobile-menu-icon-close" class="hidden h-6 w-6" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div id="mobile-menu" class="hidden sm:hidden">
                <div
                     class="px-2 pt-2 pb-3 space-y-1 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                    @auth
                        <a href="{{ route('dashboard') }}"
                           class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('dashboard') ? 'text-jira-blue bg-blue-50 dark:bg-blue-900 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('projects.index') }}"
                           class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('projects.*') ? 'text-jira-blue bg-blue-50 dark:bg-blue-900 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                            Projects
                        </a>
                        <a href="{{ route('tasks.index') }}"
                           class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('tasks.*') ? 'text-jira-blue bg-blue-50 dark:bg-blue-900 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                            Tasks
                        </a>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('team.index') }}"
                               class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('team.*') ? 'text-jira-blue bg-blue-50 dark:bg-blue-900 dark:text-blue-400' : 'text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                Team
                            </a>
                        @endif

                        <!-- Mobile Search -->
                        <div class="px-3 py-2">
                            <form method="GET" action="{{ route('search.index') }}" class="flex">
                                <input type="text" name="q" placeholder="Search..."
                                       class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-l-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
                                <button type="submit"
                                        class="px-3 py-2 bg-jira-blue text-white rounded-r-lg hover:bg-blue-700 dark:hover:bg-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>

                <!-- Mobile user menu -->
                @auth
                    <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <div class="flex items-center px-5">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-jira-blue flex items-center justify-center">
                                    <span class="text-white text-sm font-medium">
                                        {{ auth()->user()->getInitials() }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium text-gray-800 dark:text-gray-200">
                                    {{ auth()->user()->name }}
                                </div>
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ auth()->user()->email }}
                                </div>
                            </div>
                            <div class="ml-auto">
                                <!-- Mobile Dark Mode Toggle -->
                                <button id="mobile-theme-toggle" type="button"
                                        class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
                                    <svg id="mobile-theme-toggle-dark-icon" class="w-5 h-5" fill="currentColor"
                                         viewBox="0 0 20 20">
                                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                    </svg>
                                    <svg id="mobile-theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor"
                                         viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <a href="{{ route('profile.show') }}"
                               class="block px-4 py-2 text-base font-medium text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Profile
                            </a>
                            <a href="{{ route('profile.edit') }}"
                               class="block px-4 py-2 text-base font-medium text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        <div class="space-y-1">
                            <a href="{{ route('login') }}"
                               class="block px-4 py-2 text-base font-medium text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Sign in
                            </a>
                            <a href="{{ route('register') }}"
                               class="block px-4 py-2 text-base font-medium text-jira-blue hover:text-blue-700 dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Sign up
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            @if (session('success'))
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pt-4">
                    <div
                         class="mb-4 p-4 rounded-md bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-800">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400 dark:text-green-300" fill="currentColor"
                                     viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                          clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pt-4">
                    <div
                         class="mb-4 p-4 rounded-md bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-800">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400 dark:text-red-300" fill="currentColor"
                                     viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                          clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>


</body>

</html>

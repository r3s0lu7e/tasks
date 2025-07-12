@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <h2 class="mt-6 text-3xl font-bold text-gray-900 dark:text-white">
                    {{ __('Sign in to your account') }}
                </h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Or
                    <a href="{{ route('register') }}"
                       class="font-medium text-jira-blue dark:text-blue-400 hover:text-blue-500">
                        create a new account
                    </a>
                </p>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div
                 class="bg-white dark:bg-gray-800 py-8 px-4 shadow-lg sm:rounded-lg sm:px-10 border border-gray-200 dark:border-gray-700">
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Email Address') }}
                        </label>
                        <div class="mt-1">
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                   autocomplete="email" autofocus
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('email') border-red-300 dark:border-red-600 @enderror">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Password') }}
                        </label>
                        <div class="mt-1">
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-jira-blue focus:border-jira-blue sm:text-sm @error('password') border-red-300 dark:border-red-600 @enderror">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}
                                   class="h-4 w-4 text-jira-blue focus:ring-jira-blue border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700">
                            <label for="remember" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                {{ __('Remember me') }}
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-sm">
                                <a href="{{ route('password.request') }}"
                                   class="font-medium text-jira-blue dark:text-blue-400 hover:text-blue-500">
                                    {{ __('Forgot your password?') }}
                                </a>
                            </div>
                        @endif
                    </div>

                    <div>
                        <button type="submit"
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-jira-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-jira-blue dark:focus:ring-offset-gray-800">
                            {{ __('Sign in') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('getWorkstationName')) {
    /**
     * Get the workstation name based on the currently logged-in user.
     * Returns "Ива's workstation" for the default user, or "[User's name]'s workstation" for others.
     * If no user is logged in, returns "Ива's workstation" as default.
     */
    function getWorkstationName(): string
    {
        if (Auth::check()) {
            $user = Auth::user();
            $firstName = explode(' ', $user->name)[0];
            return $firstName . "'s workstation";
        }

        return "Your workstation";
    }
}

if (!function_exists('getWorkstationDescription')) {
    /**
     * Get the workstation description based on the currently logged-in user.
     */
    function getWorkstationDescription(): string
    {
        if (Auth::check()) {
            $user = Auth::user();
            $firstName = explode(' ', $user->name)[0];
            return "Welcome to {$firstName}'s personal task management workstation";
        }

        return "Welcome to Ива's personal task management workstation";
    }
}

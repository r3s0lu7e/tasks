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

if (!function_exists('hex_to_rgb')) {
    /**
     * Convert hex color to an RGB array.
     */
    function hex_to_rgb(string $hex): array
    {
        $hex = str_replace('#', '', $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }

        return ['r' => $r, 'g' => $g, 'b' => $b];
    }
}

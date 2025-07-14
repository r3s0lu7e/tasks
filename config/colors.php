<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Color Mappings
    |--------------------------------------------------------------------------
    |
    | Here you can define color mappings for different statuses and priorities
    | used throughout the application. These are used to dynamically apply
    | CSS classes for consistent styling.
    |
    */

    'project_status' => [
        'active' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-gray-200',
        'planning' => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-gray-200',
        'on_hold' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-gray-200',
        'completed' => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200',
        'cancelled' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-gray-200',
        'default' => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200',
    ],

    'task_priority' => [
        'critical' => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-gray-200',
        'high' => 'bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-gray-200',
        'medium' => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-gray-200',
        'low' => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-gray-200',
    ],
];

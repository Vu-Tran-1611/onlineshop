<?php


return [
    'stages' => [
        ['key' => 'pending', 'label' => 'Pending', 'icon' => 'fa-clock', 'color' => 'yellow'],
        ['key' => 'confirmed', 'label' => 'Confirmed', 'icon' => 'fa-check', 'color' => 'blue'],
        ['key' => 'delivered', 'label' => 'Delivered', 'icon' => 'fa-truck', 'color' => 'green'],
        ['key' => 'cancelled', 'label' => 'Canceled', 'icon' => 'fa-times', 'color' => 'red'],
    ],

    'statuses' => ['pending', 'confirmed', 'delivered', 'cancelled'],
    'circle_colors' => [
        'yellow' => 'bg-yellow-400 text-yellow-900',
        'blue' => 'bg-blue-500 text-white',
        'green' => 'bg-green-500 text-white',
        'red' => 'bg-red-500 text-white',
    ],
    'border_colors' => [
        'yellow' => 'border-yellow-400',
        'blue' => 'border-blue-500',
        'green' => 'border-green-500',
        'red' => 'border-red-500',
    ]
];

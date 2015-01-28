<?php
$tabs = [
    'friday'    => [],
    'saturday'  => [],
    'sunday'    => [],
    'wcop'      => [],
    'gambling'  => []
];

if (!(isset($activeTab) && array_key_exists($activeTab, $tabs))) {
    $activeTab = 'friday';
}

return [
    'filename' => 'results.phtml',
    'data' => [
        'tabs' => $tabs,
        'activeTab' => $activeTab
    ]
];

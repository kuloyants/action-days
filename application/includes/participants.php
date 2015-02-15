<?php
$tabs = [
    'main_tournament'   => [],
    'seeded'            => [],
    'wcop'              => [],
];

if (!(isset($activeTab) && array_key_exists($activeTab, $tabs))) {
    $activeTab = 'main_tournament';
}

$tabs[$activeTab] = include(APPLICATION_PATH . "includes/participants/{$activeTab}.php");

return [
    'filename' => 'participants.phtml',
    'data' => [
        'tabs' => $tabs,
        'activeTab' => $activeTab
    ]
];

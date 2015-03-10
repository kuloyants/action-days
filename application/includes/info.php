<?php
$tabs = [
    'main_tournament'   => [],
    'wcop'              => [],
    'gambling'          => [],
];

if (!(isset($activeTab) && array_key_exists($activeTab, $tabs))) {
    $activeTab = 'main_tournament';
}

return [
    'filename' => 'info.phtml',
    'data' => [
        'tabs' => $tabs,
        'activeTab' => $activeTab
    ]
];

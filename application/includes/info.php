<?php
$tabs = [
    'main'      => [],
    'gambling'  => [],
    'wcop'      => [],
];

if (!(isset($activeTab) && array_key_exists($activeTab, $tabs))) {
    $activeTab = 'main';
}

return [
    'filename' => 'info.phtml',
    'data' => [
        'tabs' => $tabs,
        'activeTab' => $activeTab
    ]
];

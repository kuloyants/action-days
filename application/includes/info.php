<?php
$tabs = [
    'main_tournament'   => [],
    'wcop'              => [],
    'gambling'          => [],
];

if (!(isset($activeTab) && array_key_exists($activeTab, $tabs))) {
    $activeTab = 'main_tournament';
}

$tabFile = APPLICATION_PATH . "includes/info/{$activeTab}.php";
if (file_exists($tabFile)) {
    $tabs[$activeTab] = include($tabFile);
}

return [
    'filename' => 'info.phtml',
    'data' => [
        'tabs' => $tabs,
        'activeTab' => $activeTab
    ]
];

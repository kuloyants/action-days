<?php
include(APPLICATION_PATH . 'includes/results/methods.php');
$tabs = [
    'friday'    => [],
    'saturday'  => [],
    'sunday'    => [],
    'wcop'      => [],
];
if (!(isset($activeTab) && array_key_exists($activeTab, $tabs))) {
    $activeTab = 'friday';
}

$tabs[$activeTab] = include(APPLICATION_PATH . "backoffice/includes/results/{$activeTab}.php");

return [
    'filename' => 'results.phtml',
    'data' => [
        'tabs' => $tabs,
        'activeTab' => $activeTab
    ]
];

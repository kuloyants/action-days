<?php
$tabs = [
    'previous_2013'      => [],
    'friday'    => [],
    'saturday'  => [],
    'sunday'    => [],
];

if (!(isset($activeTab) && array_key_exists($activeTab, $tabs))) {
    $activeTab = 'previous_2013';
}

return [
    'filename' => 'gallery.phtml',
    'data' => [
        'tabs' => $tabs,
        'activeTab' => $activeTab
    ]
];

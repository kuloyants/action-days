<?php
$return = [
    'filename' => 'errors.phtml',
    'data' => []
];

$tabs = [
    'general'    => [],
    '404'  => [],
];

if (!(isset($activeTab) && array_key_exists($activeTab, $tabs))) {
    $activeTab = 'general';
}

$return['data'] = [
    'tabs' => $tabs,
    'activeTab' => $activeTab
];
return $return;

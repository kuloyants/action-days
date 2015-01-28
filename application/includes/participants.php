<?php
$tabs = [
    'main'    => include(APPLICATION_PATH . 'includes/participants/main.php'),
    'seeded'  => include(APPLICATION_PATH . 'includes/participants/seeded.php'),
    'wcop'    => include(APPLICATION_PATH . 'includes/participants/wcop.php')
];

if (!(isset($activeTab) && array_key_exists($activeTab, $tabs))) {
    $activeTab = 'main';
}

return [
    'filename' => 'participants.phtml',
    'data' => [
        'tabs' => $tabs,
        'activeTab' => $activeTab
    ]
];

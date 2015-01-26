<?php
    switch ($activeTab) {
        case 'participants':
        case 'seeded':
        case 'results':
            break;
        default:
            $activeTab = 'participants';
    }

return [
    'filename' => 'tournament.phtml',
    'data' => [
        'tabs' => [
            'participants'  => include(APPLICATION_PATH . 'includes/tournament/participants.php'),
            'seeded'        => include(APPLICATION_PATH . 'includes/tournament/seeded.php'),
            'results'       => include(APPLICATION_PATH . 'includes/tournament/results.php'),
        ],
        'activeTab' => $activeTab
    ]
];

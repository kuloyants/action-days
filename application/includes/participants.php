<?php
$tabs = [
    'friday'            => [],
    'saturday'          => [],
    'seeded'            => [],
];

if (!(isset($activeTab) && array_key_exists($activeTab, $tabs))) {
    $activeTab = 'friday';
}

$tabFile = APPLICATION_PATH . "includes/participants/{$activeTab}.php";
if (file_exists($tabFile)) {
    $tabs[$activeTab] = include($tabFile);
}

return [
    'filename' => 'participants.phtml',
    'data' => [
        'tabs' => $tabs,
        'activeTab' => $activeTab
    ]
];

function getParticipants($day, $time, $sort) {
    $db = Db::getInstance();

    $players = [];
    $sql = "
      SELECT
        p.firstname,
        p.surname,
        p.country_code,
        pr.reg_status
        FROM player AS p INNER JOIN player_registration AS pr ON p.id = pr.player_id
        WHERE pr.reg_status in ('confirmed', 'pending') AND pr.start_day = '$day' AND pr.start_time = '$time'
        ORDER BY $sort ASC, p.created ASC";
    $result = $db->query($sql);

    if (!$result) {
        throw new \Exception('Konnte den Folgenden Query nicht senden: '.$sql."<br />\nFehlermeldung: ".$db->error);
    }
    if (!$result->num_rows) {
        throw new \Exception('no players found');
    } else {
        while ($row = $result->fetch_assoc()) {
            $players[] = $row;
        }
    }
    return $players;
}

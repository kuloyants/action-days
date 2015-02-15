<?php
$return = [
    'filename' => 'profile.phtml',
    'data' => []
];

$db = Db::getInstance();

if (!isset($_GET['profile'])) {
    throw new Exception('required parameter profile');
} else {
    $playerId = trim($_GET['profile']);
    $sql = "
    SELECT
        p.firstname,
        p.surname,
        p.nickname,
        p.country_code
    FROM player AS p INNER JOIN player_registration AS pr ON p.id = pr.player_id
    WHERE pr.reg_status in ('seeded') AND p.id = ? ";
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        throw new Exception('Es konnte kein SQL-Query vorbereitet werden: '.$db->error);
    }
    $stmt->bind_param('i', $playerId);
    if (!$stmt->execute()) {
        throw new Exception ('Query konnte nicht ausgeführt werden: '.$stmt->error);
    }

    $stmt->bind_result($firstname, $surname, $nickname, $country_code);
    if (!$stmt->fetch()) {
        $stmt->close();
        return false;
    }
    $stmt->close();

    $return['data'] = [
        'firstname' => $firstname,
        'surname' => $surname,
        'nickname' => $nickname,
        'country' => $country_code
    ];
}

return $return;
?>

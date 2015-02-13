<?php
$return = [
    'matches'   => [],
    'players'   => []
];

$db = Db::getInstance();

if ('POST' == $_SERVER['REQUEST_METHOD']) {

    $match_nr           = trim($_POST['match_nr']);
    $player1_id         = trim($_POST['player1_id']);
    $player2_id         = trim($_POST['player2_id']);
    $score_p1_match     = trim($_POST['score_p1_match']);
    $score_p2_match     = trim($_POST['score_p2_match']);
    $score_p1_set1      = trim($_POST['score_p1_set1']);
    $score_p1_set2      = trim($_POST['score_p1_set2']);
    $score_p1_set3      = trim($_POST['score_p1_set3']);
    $score_p2_set1      = trim($_POST['score_p2_set1']);
    $score_p2_set2      = trim($_POST['score_p2_set2']);
    $score_p2_set3      = trim($_POST['score_p2_set3']);
    $status             = trim($_POST['status']);

    if (empty($player1_id)) {
        $player1_id = null;
    }

    if (empty($player2_id)) {
        $player2_id = null;
    }

    $sql = "UPDATE matches AS m
    SET
    m.player_1 = ?,
    m.player_2 = ?,
    m.score_p1_match = ?,
    m.score_p2_match = ?,
    m.score_p1_set1 = ?,
    m.score_p1_set2 = ?,
    m.score_p1_set3 = ?,
    m.score_p2_set1 = ?,
    m.score_p2_set2 = ?,
    m.score_p2_set3 = ?,
    m.status = ?
    WHERE m.Nr = ?
    ";
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        return $db->error;
    }

    $stmt->bind_param(
        'iissssssssss',
        $player1_id,
        $player2_id,
        $score_p1_match,
        $score_p2_match,
        $score_p1_set1,
        $score_p1_set2,
        $score_p1_set3,
        $score_p2_set1,
        $score_p2_set2,
        $score_p2_set3,
        $status,
        $match_nr);
    if (!$stmt->execute()) {
        return $stmt->error;
    }
    $stmt->close();

    if (isset($_POST['winner'])) {
        if (isset($_POST['winnerToMatch']) && isset($_POST['winnerToSlot'])) {
            $winner = $_POST['winner'];
            $winnerToMatch = $_POST['winnerToMatch'];
            $winnerToSlot = $_POST['winnerToSlot'];

            switch ($winnerToSlot) {
                case '1':
                    $sql = "
                        UPDATE matches AS m
                        SET m.player_1 = ?
                        WHERE m.Nr = ?";
                    break;
                case '2':
                    $sql = "
                        UPDATE matches AS m
                        SET m.player_2 = ?
                        WHERE m.Nr = ?";
                    break;
                default:
                    throw new Exception('unknown slot');
            }
            $stmt = $db->prepare($sql);
            if (!$stmt) {
                return $db->error;
            }

            $stmt->bind_param('is', $winner, $winnerToMatch);
            if (!$stmt->execute()) {
                return $stmt->error;
            }
            $stmt->close();
        }
    }
}


$sql = "
SELECT
m.*,
DATE_FORMAT(m.starttime,'%h:%i') as starttime,
p1.firstname as p1_firstname,
p1.surname as p1_surname,
p1.country_code as p1_country,
p2.firstname as p2_firstname,
p2.surname as p2_surname,
p2.country_code as p2_country
FROM matches as m LEFT JOIN player as p1 ON p1.id = m.player_1 LEFT JOIN player as p2 ON p2.id = m.player_2
WHERE m.startdate = '2015-05-01' AND m.status IN ('scheduled', 'progress', 'finished')
ORDER BY Nr ASC
";
$result = $db->query($sql);

if (!$result) {
    throw new \Exception('Konnte den Folgenden Query nicht senden: '.$sql."<br />\nFehlermeldung: ".$db->error);
}
if (!$result->num_rows) {
    throw new \Exception('no matches found');
} else {
    while ($row = $result->fetch_assoc()) {
        $return['matches'][] = $row;
    }
}

$sql = "
SELECT
p.id,
p.firstname,
p.surname,
p.country_code,
pr.reg_status
FROM player AS p INNER JOIN player_registration AS pr ON p.id = pr.player_id
WHERE pr.reg_status in ('confirmed', 'pending') AND pr.start_day = 'friday'
";
$result = $db->query($sql);

if (!$result) {
    throw new \Exception('Konnte den Folgenden Query nicht senden: '.$sql."<br />\nFehlermeldung: ".$db->error);
}
if (!$result->num_rows) {
    throw new \Exception('no players found');
} else {
    while ($row = $result->fetch_assoc()) {
        $return['players'][] = $row;
    }
}

return $return;
?>
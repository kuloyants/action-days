<?php
$return = [
    'filename' => 'participants/seeded.phtml',
    'data' => []
];

$db = getDB();

$sql = "
SELECT
    p.firstname,
    p.surname,
    p.nickname,
    p.country_code,
    pr.registration_status
FROM player AS p INNER JOIN player_registration AS pr ON p.id = pr.player_id
WHERE pr.registration_status in ('seeded')";
$result = $db->query($sql);
if (!$result) {
    throw new \Exception('Konnte den Folgenden Query nicht senden: '.$sql."<br />\nFehlermeldung: ".$db->error);
}
if (!$result->num_rows) {
    throw new \Exception('no players found');
} else {
    while ($row = $result->fetch_assoc()) {
        $return['data']['players'][] = $row;
    }
}

return $return;
?>

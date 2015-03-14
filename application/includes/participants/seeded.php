<?php
$return['players'] = [];

$db = Db::getInstance();

$sql = "
SELECT
    p.id,
    p.firstname,
    p.surname,
    p.nickname,
    p.country_code,
    pr.reg_status
FROM player AS p INNER JOIN player_registration AS pr ON p.id = pr.player_id
WHERE pr.reg_status in ('seeded')";
$result = $db->query($sql);
if (!$result) {
    throw new \Exception('Konnte den Folgenden Query nicht senden: '.$sql."<br />\nFehlermeldung: ".$db->error);
}

while ($row = $result->fetch_assoc()) {
    $return['players'][] = $row;
}

return $return;
?>

<?php
$return = [];

$columns = ['firstname', 'surname', 'country_code', 'reg_status', 'start_day', 'start_time'];

$db = Db::getInstance();
if (isset($_GET['sort']) && in_array($_GET['sort'], $columns)) {
    $sort = $_GET['sort'];
} else {
    $sort = 'reg_status';
}
$sql = "
SELECT
    p.firstname,
    p.surname,
    p.country_code,
    pr.reg_status,
    pr.start_day,
    pr.start_time
FROM player AS p INNER JOIN player_registration AS pr ON p.id = pr.player_id
WHERE pr.reg_status in ('confirmed', 'pending')
ORDER BY $sort ASC, p.surname ASC";
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

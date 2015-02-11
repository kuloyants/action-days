<?php
$return = [
    'players' => []
];

$columns = ['firstname', 'surname', 'country_code', 'reg_status', 'start_day', 'start_time'];

$db = Db::getInstance();
if (isset($_GET['sort']) && in_array($_GET['sort'], $columns)) {
    $sort = $_GET['sort'];
} else {
    $sort = 'reg_status';
}

$startDay = ['friday', 'saturday'];
$startTime = ['1000', '1200'];
foreach ($startDay as $day) {
    foreach ($startTime as $time) {
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
            $arrayKey = $day . '_' . $time;
            while ($row = $result->fetch_assoc()) {
                $return['players'][$arrayKey][] = $row;
            }
        }
    }
}

return $return;
?>

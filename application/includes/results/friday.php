<?php
$return = [
    'matches' => []
];

$db = Db::getInstance();

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
return $return;
?>

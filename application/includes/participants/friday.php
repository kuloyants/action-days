<?php
$return = [
    'players' => []
];

$columns = ['firstname', 'surname', 'country_code', 'reg_status', 'start_day', 'start_time'];

if (isset($_GET['sort']) && in_array($_GET['sort'], $columns)) {
    $sort = $_GET['sort'];
} else {
    $sort = 'reg_status';
}

$return['players']['friday_1000'] = getParticipants('friday', '1000', $sort);
$return['players']['friday_1200'] = getParticipants('friday', '1200', $sort);

return $return;
?>

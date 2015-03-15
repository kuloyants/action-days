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

$return['players']['saturday_1000'] = getParticipants('saturday', '1000', $sort);
$return['players']['saturday_1200'] = getParticipants('saturday', '1200', $sort);

return $return;
?>

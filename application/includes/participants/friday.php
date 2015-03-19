<?php
$return = [
    'players' => []
];

$columns = ['firstname', 'surname', 'country_code', 'reg_status', 'start_day', 'start_time'];

$return['players']['friday_1000'] = getParticipants('friday', '1000');
$return['players']['friday_1200'] = getParticipants('friday', '1200');

return $return;
?>

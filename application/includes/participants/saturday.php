<?php
$return = [
    'players' => []
];

$columns = ['firstname', 'surname', 'country_code', 'reg_status', 'start_day', 'start_time'];

$return['players']['saturday_1000'] = getParticipants('saturday', '1000');
$return['players']['saturday_1200'] = getParticipants('saturday', '1200');

return $return;
?>

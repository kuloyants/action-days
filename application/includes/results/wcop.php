<?php
$return = [
    'matches' => []
];

$return['matches'] = getMatchesForDay('wcop');
$return['countries'] = include('config/wcop_players.php');

return $return;
?>

<?php
$return = [
    'matches'   => [],
    'players'   => []
];

if ('POST' == $_SERVER['REQUEST_METHOD']) {
    updateMatch();
}

$return['matches']  = getMatchesForDay('sunday');
$return['players']  = getPlayersForSunday();

return $return;
?>

<?php
$return = [
    'matches'   => [],
    'players'   => []
];

if ('POST' == $_SERVER['REQUEST_METHOD']) {
    updateMatch();
}

$return['matches']  = getMatchesForDay('saturday');
$return['players']  = getPlayersForDay('saturday');

return $return;
?>

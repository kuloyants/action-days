<?php
$return = [
    'matches'   => [],
    'players'   => []
];

if ('POST' == $_SERVER['REQUEST_METHOD']) {
    updateMatch();
}

$return['matches']  = getMatchesForDay('friday');
$return['players']  = getPlayersForDay('friday');

return $return;
?>

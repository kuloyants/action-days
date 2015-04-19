<?php
$return = [
    'matches' => []
];

if ('POST' == $_SERVER['REQUEST_METHOD']) {
    updateMatch();
}

$return['matches'] = getMatchesForDay('wcop');
$return['players'] = getPlayersForWCOP();

return $return;
?>

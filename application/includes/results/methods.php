<?php
/**
 * @param $day string friday | saturday | sunday | wcop
 */
function getMatchesForDay($day)
{
    $matches = [];
    switch ($day) {
        case 'friday':
            $matchNr = 'FR%';
            break;
        case 'saturday':
            $matchNr = 'SA%';
            break;
        case 'sunday':
            $matchNr = 'SU%';
            break;
        case 'wcop':
            $matchNr = 'NC%';
            break;
        default:
            throw new Exception("unknown play day {$day} given");
    }

    $db = Db::getInstance();

    $sql = "
        SELECT
        m.*,
        DATE_FORMAT(m.starttime,'%H:%i') as starttime,
        p1.firstname as p1_firstname,
        p1.surname as p1_surname,
        p1.country_code as p1_country,
        p2.firstname as p2_firstname,
        p2.surname as p2_surname,
        p2.country_code as p2_country
        FROM matches as m LEFT JOIN player as p1 ON p1.id = m.player_1 LEFT JOIN player as p2 ON p2.id = m.player_2
        WHERE m.Nr like '{$matchNr}' AND m.status IN ('scheduled', 'progress', 'finished')
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
            $matches[] = $row;
        }
    }

    return $matches;
}

/**
 * @param $day string friday | saturday
 * @throws Exception
 */
function getPlayersForDay($day)
{
    $db = Db::getInstance();
    $players = [];
    switch ($day) {
        case 'friday':
            break;
        case 'saturday':
            break;
        default:
            throw new Exception("unknown play day {$day} given");
    }

    $sql = "
        SELECT
        p.id,
        p.firstname,
        p.surname,
        p.country_code,
        pr.reg_status
        FROM player AS p INNER JOIN player_registration AS pr ON p.id = pr.player_id
        WHERE pr.reg_status in ('confirmed', 'pending') AND pr.start_day = '{$day}'
        ";
    $result = $db->query($sql);

    if (!$result) {
        throw new \Exception('Konnte den Folgenden Query nicht senden: '.$sql."<br />\nFehlermeldung: ".$db->error);
    }
    while ($row = $result->fetch_assoc()) {
        $players[] = $row;
    }

    return $players;
}

function getPlayersForWCOP()
{
    $db = Db::getInstance();
    $players = [];

    $sql = "
        SELECT
        p.id,
        p.firstname,
        p.surname,
        p.country_code,
        pr.reg_status
        FROM player AS p INNER JOIN player_registration AS pr ON p.id = pr.player_id
        WHERE pr.reg_status in ('wcop')
        ";
    $result = $db->query($sql);

    if (!$result) {
        throw new \Exception('Konnte den Folgenden Query nicht senden: '.$sql."<br />\nFehlermeldung: ".$db->error);
    }
    if (!$result->num_rows) {
        throw new \Exception('no players found');
    } else {
        while ($row = $result->fetch_assoc()) {
            $players[] = $row;
        }
    }

    return $players;
}

function getPlayersForSunday()
{
    $db = Db::getInstance();
    $players = [];

    $sql = "
        SELECT
        p.id,
        p.firstname,
        p.surname,
        p.country_code,
        pr.reg_status
        FROM player AS p INNER JOIN player_registration AS pr ON p.id = pr.player_id
        WHERE p.qualified = 1 OR pr.reg_status = 'seeded'
        ORDER BY p.surname
        ";
    $result = $db->query($sql);

    if (!$result) {
        throw new \Exception('Konnte den Folgenden Query nicht senden: '.$sql."<br />\nFehlermeldung: ".$db->error);
    }

    while ($row = $result->fetch_assoc()) {
        $players[] = $row;
    }

    return $players;
}

function updateMatch()
{
    $db = Db::getInstance();
    $match_nr           = trim($_POST['match_nr']);
    $player1_id         = trim($_POST['player1_id']);
    $player2_id         = trim($_POST['player2_id']);
    $score_p1_match     = trim($_POST['score_p1_match']);
    $score_p2_match     = trim($_POST['score_p2_match']);
    $score_p1_set1      = trim($_POST['score_p1_set1']);
    $score_p1_set2      = trim($_POST['score_p1_set2']);
    $score_p1_set3      = trim($_POST['score_p1_set3']);
    $score_p2_set1      = trim($_POST['score_p2_set1']);
    $score_p2_set2      = trim($_POST['score_p2_set2']);
    $score_p2_set3      = trim($_POST['score_p2_set3']);
    $status             = trim($_POST['status']);
    $walkover           = trim($_POST['walkover']);

    if (empty($player1_id) || $player1_id == 'walkover') {
        $player1_id = null;
    }

    if (empty($player2_id) || $player2_id == 'walkover') {
        $player2_id = null;
    }

    if (empty($walkover)) {
        $walkover = null;
    }

    $sql = "UPDATE matches AS m
    SET
    m.player_1 = ?,
    m.player_2 = ?,
    m.score_p1_match = ?,
    m.score_p2_match = ?,
    m.score_p1_set1 = ?,
    m.score_p1_set2 = ?,
    m.score_p1_set3 = ?,
    m.score_p2_set1 = ?,
    m.score_p2_set2 = ?,
    m.score_p2_set3 = ?,
    m.status = ?,
    m.walkover = ?
    WHERE m.Nr = ?
    ";
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        return $db->error;
    }

    $stmt->bind_param(
        'iisssssssssss',
        $player1_id,
        $player2_id,
        $score_p1_match,
        $score_p2_match,
        $score_p1_set1,
        $score_p1_set2,
        $score_p1_set3,
        $score_p2_set1,
        $score_p2_set2,
        $score_p2_set3,
        $status,
        $walkover,
        $match_nr
    );
    if (!$stmt->execute()) {
        return $stmt->error;
    }
    $stmt->close();

    if (isset($_POST['winner'])) {
        if (isset($_POST['winnerToMatch']) && isset($_POST['winnerToSlot'])) {
            $winner = $_POST['winner'];
            $winnerToMatch = $_POST['winnerToMatch'];
            $winnerToSlot = $_POST['winnerToSlot'];

            switch ($winnerToSlot) {
                case '1':
                    $sql = "
                        UPDATE matches AS m
                        SET m.player_1 = ?
                        WHERE m.Nr = ?";
                    break;
                case '2':
                    $sql = "
                        UPDATE matches AS m
                        SET m.player_2 = ?
                        WHERE m.Nr = ?";
                    break;
                default:
                    throw new Exception('unknown slot');
            }
            $stmt = $db->prepare($sql);
            if (!$stmt) {
                return $db->error;
            }

            $stmt->bind_param('is', $winner, $winnerToMatch);
            if (!$stmt->execute()) {
                return $stmt->error;
            }
            $stmt->close();

            $qualifiedMatchNrs = ['FR25', 'FR26', 'FR27', 'FR28', 'SA25', 'SA26', 'SA27', 'SA28'];
            if (in_array($winnerToMatch, $qualifiedMatchNrs)) {
                $sql = "
                        UPDATE player AS p
                        SET p.qualified = 1
                        WHERE p.id = ?";
                $stmt = $db->prepare($sql);
                if (!$stmt) {
                    return $db->error;
                }

                $stmt->bind_param('i', $winner);
                if (!$stmt->execute()) {
                    return $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}

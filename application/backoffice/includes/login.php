<?php
if (getUserID()) {
    return 'Sie sind bereits eingeloggt.';
}
$return = [
    'filename' => 'login.phtml',
    'data' => []
];

$db = Db::getInstance();
if ('POST' == $_SERVER['REQUEST_METHOD']) {
    if (!isset($_POST['Username'], $_POST['Password'], $_POST['formaction'])) {
        throw new Exception('unregistered post params');
    }
    if (('' == $Username = trim($_POST['Username'])) OR
        ('' == $Password = trim($_POST['Password']))) {
        throw new Exception('empty post params');
    }

    $sql = 'SELECT ID FROM user
            WHERE Username = ?';
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        return $db->error;
    }
    $stmt->bind_param('s', $Username);
    if (!$stmt->execute()) {
        return $stmt->error;
    }
    $stmt->bind_result($UserID);
    if (!$stmt->fetch()) {
        return 'Es wurde kein Benutzer mit den angegebenen Namen gefunden.';
    }
    $stmt->close();

    $sql = 'SELECT
                Password
            FROM
                user
            WHERE
                ID = ? AND
                Password = ?';
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        return $db->error;
    }
    $Hash = md5(md5($UserID).$Password);
    $stmt->bind_param('is', $UserID, $Hash);
    if (!$stmt->execute()) {
        return $stmt->error;
    }
    $stmt->bind_result($Hash);
    if (!$stmt->fetch()) {
        return 'Das eingegebene Password ist ungültig.';
    }
    $stmt->close();
    setcookie('UserID', $UserID, time()+3600);
    setcookie('Password', $Hash, time()+3600);
    $_COOKIE['UserID'] = $UserID; // fake-cookie setzen
    $_COOKIE['Password'] = $Hash; // fake-cookie setzen
}

return $return;

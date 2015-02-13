<?php
if (!getUserID()) {
    return 'Sie sind ausgeloggt';
}
$return = [
    'filename' => 'login.phtml',
    'data' => []
];

if ('POST' == $_SERVER['REQUEST_METHOD']) {
    if (!isset($_POST['formaction'])) {
        throw new Exception('inkonsistente Formular-Daten');
    }
    setcookie('UserID', '', time()-3600);
    setcookie('Password', '', time()-3600);
    unset($_COOKIE['UserID']);
    unset($_COOKIE['Password']);
}

return $return;

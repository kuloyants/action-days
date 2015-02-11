<?php

define('APPLICATION_PATH', realpath(__DIR__) . '/application/');
define('VIEW_PATH', APPLICATION_PATH . 'view/');
define('IMG_PATH', 'assets/img/');
define('CSS_PATH', realpath(__DIR__) . '\\assets\\css');
define('CSV_PATH', realpath(__DIR__) . '\\assets\\csv');
define('JS_PATH', realpath(__DIR__) . '\\assets\\js');
define('PDF_PATH', realpath(__DIR__) . '\\assets\\pdf');

session_start();
header('Cache-control: private'); // IE 6 FIX
if (isSet($_GET['lang'])) {
    $lang = $_GET['lang'];

// register the session and set the cookie
    $_SESSION['lang'] = $lang;

    setcookie("lang", $lang, time() + (3600 * 24 * 30));
    $GLOBALS['locale'] = $lang;
}
elseif (isSet($_SESSION['lang'])) {
    $GLOBALS['locale'] = $_SESSION['lang'];
}
elseif (isSet($_COOKIE['lang'])) {
    $GLOBALS['locale'] = $_COOKIE['lang'];
}
else {
    $GLOBALS['locale'] = 'd';
}

function translate($key, $locale = null)
{
    if ($locale !== null) {
        $lang = $locale;
    } else {
        $lang = $GLOBALS['locale'];
    }
    $db = Db::getInstance();
    $sql = "SELECT * FROM l10n
            inner join l10n_translation as lt on l10n.id = lt.l10n_id
            inner join locale on locale.id = lt.locale_id
            WHERE l10n.key LIKE '{$key}' AND locale.language like '{$lang}'";
    $result = $db->query($sql);
    if (!$result) {
        die ('Konnte den Folgenden Query nicht senden: '.$sql."<br />\nFehlermeldung: ".$db->error);
    }
    if (!$result->num_rows) {
        $return = $key;
    } else {
        $row = $result->fetch_assoc();
        if (empty($row['translation'])) {
            $return = $key;
        } else {
            $return = $row['translation'];
        }
    }
    return $return;
}
?>

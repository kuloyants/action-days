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
    $GLOBALS['locale'] = 'de';
}

function translate($key, $locale = null)
{
    if ($locale !== null) {
        $lang = $locale;
    } else {
        $lang = getLang();
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

function getLang() {
    return $GLOBALS['locale'];
}

function fatalHandler()
{
    $last_error = error_get_last();
    if (in_array($last_error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING])) {
        // fatal error
        errorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
    }
}

function errorHandler($code, $message, $file, $line)
{
    if (0 == error_reporting()) {
        return;
    }

    throw new ErrorException($message, $code, 1, $file, $line);
}

function exceptionHandler($exception)
{
    // these are our templates
    $traceline = "#%s %s(%s): %s(%s)";
    $msg = "PHP Fatal error:  Uncaught exception '%s' with message '%s' in %s:%s\nStack trace:\n%s\n  thrown in %s on line %s";

    // alter your trace as you please, here
    $trace = $exception->getTrace();
    foreach ($trace as $key => $stackPoint) {
        // I'm converting arguments to their type
        // (prevents passwords from ever getting logged as anything other than 'string')
        if (array_key_exists('args', $trace[$key])) {
            $trace[$key]['args'] = array_map('gettype', $trace[$key]['args']);
        }
    }

    // build your tracelines
    $result = array();
    foreach ($trace as $key => $stackPoint) {
        $stackFile = array_key_exists('file', $stackPoint) ? $stackPoint['file'] : 'kein file vorhanden';
        $stackLine = array_key_exists('line', $stackPoint) ? $stackPoint['line'] : 'keine line vorhanden';
        $result[] = sprintf(
            $traceline,
            $key,
            $stackFile,
            $stackLine,
            $stackPoint['function'],
            implode(', ', [])
        );
    }
    // trace always ends with {main}
    $result[] = '#' . ++$key . ' {main}';

    // write tracelines into main template
    $msg = sprintf(
        $msg,
        get_class($exception),
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine(),
        implode("\n", $result),
        $exception->getFile(),
        $exception->getLine()
    );

    error_log($msg);

    $isXmlHttpRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    if ($isXmlHttpRequest) {
        header('Content-Type: application/json');
        ob_start();
        include(VIEW_PATH . 'general_error.phtml');
        $data['php_error'] = ob_get_clean();
        echo json_encode($data);
        return;
    } else {
        include 'header.html'; // doctype, <html> und das komplette <head>-element
        echo "<html><body class='page-top'>\n";
        include 'menu.phtml';
        include(VIEW_PATH . 'general_error.phtml');
        include('footer.html');
        echo "</body></html>\n";
    }
    exit();
}
?>

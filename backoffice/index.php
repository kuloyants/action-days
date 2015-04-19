<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../Bootstrap.php');
include('../service/Db.php');
include('../config.php');
include(APPLICATION_PATH . 'view/helpers.php');
header("Cache-Control: no-cache, must-revalidate");

$section = [
    'login'         => APPLICATION_PATH . 'backoffice/includes/login.php',
    'logout'        => APPLICATION_PATH . 'backoffice/includes/logout.php',
    'results'       => APPLICATION_PATH . 'backoffice/includes/results.php',
];

$isXmlHttpRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if (!$isXmlHttpRequest) {
//    include 'header.html'; // doctype, <html> und das komplette <head>-element
} else {

}

if (get_magic_quotes_gpc()) {
    $in = array(&$_GET, &$_POST, &$_COOKIE);
    while (list($k,$v) = each($in)) {
        foreach ($v as $key => $val) {
            if (!is_array($val)) {
                $in[$k][$key] = stripslashes($val);
                continue;
            }
            $in[] =& $in[$k][$key];
        }
    }
    unset($in);
}
/**
 * saves the return value of include, default : 1
 */
$ret = 1;
/**
 * the include file must have a return statement with following values
 * - DEFAULT:
 *   Array('filename' => string, -- filename of phtml template
 *         'data' => Array())    -- array with data for the template
 * - ERROR
 *   string  -- error message.
 */
if (is_string($error = getUserID())) { // String, also ein MySQL Fehler
    throw new Exception($error); // die Fehlermeldung in $ret speichern, damit sie angezeigt wird.
} elseif($error === false) {
    //no logged in user
    $ret = include $section['login'];
} else {
    if (isset($_GET['section'], $section[$_GET['section']])) {
        if (file_exists($section[$_GET['section']])) {
            $activeTab = null;
            if (isset($_GET['tab'])) {
                $activeTab = $_GET['tab'];
            }
            $ret = include $section[$_GET['section']];
        } else {
            $ret = "Include-Datei konnte nicht geladen werden: 'includes/".$section[$_GET['section']]."'";
        }
    } else {
        //load default section
        $ret = include $section['login'];
    }
}

if (!$isXmlHttpRequest && is_int(getUserID())) {
    include 'header.html'; // doctype, <html> und das komplette <head>-element
    echo "    <body class='page-top'>\n";
    include 'menu.phtml';
}

if (
    is_array($ret) && isset($ret['filename'], $ret['data']) &&
    is_string($ret['filename']) &&
    is_array($ret['data']))
{
    // valid return value
    if (file_exists($file = APPLICATION_PATH . 'backoffice/view/' . $ret['filename'])) {
        $data = $ret['data']; // $data is available in the template
        $view = new View();
        include $file;
    } else {
        $data = [];
        $data['msg'] = 'File "'.$file.'" is missing.';
        include VIEW_PATH . '/error.phtml';
    }
} else if (is_string($ret)) {
    // include file returned an an error message
    $data = [];
    $data['msg'] = $ret;
    include VIEW_PATH . '/error.phtml';
} else if (1 === $ret) {
    // return statement in the include file missing
    $data = array();
    $data['msg'] = 'In der Include-Datei wurde die return Anweisung vergessen.';
    include VIEW_PATH . '/error.phtml';
} else {
    //a generally not valid value
    $data = [];
    $data['msg'] = 'Die Include-Datei hat einen ungültigen Wert zurückgeliefert.';
    include VIEW_PATH . '/error.phtml';
}
if (!$isXmlHttpRequest) {
    echo "    </body>\n";
    echo "</html>\n";
}

function getUserID() {
    $db = DB::getInstance();
    if (!isset($_COOKIE['UserID'], $_COOKIE['Password'])) {
        return false;
    }
    $sql = 'SELECT
                ID
            FROM
                user
            WHERE
                ID = ? AND
                Password = ?';
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        return $db->error;
    }
    $stmt->bind_param('is', $_COOKIE['UserID'], $_COOKIE['Password']);
    if (!$stmt->execute()) {
        $str = $stmt->error;
        $stmt->close();
        return $str;
    }
    $stmt->bind_result($UserID);
    if (!$stmt->fetch()) {
        $stmt->close();
        return false;
    }
    $stmt->close();
    return $UserID;
}
?>

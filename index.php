<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('Bootstrap.php');
include('service/Db.php');
include('config.php');
include(APPLICATION_PATH . 'view/helpers.php');
$section = [
    'info'          => APPLICATION_PATH . 'includes/info.php',
    'participants'  => APPLICATION_PATH . 'includes/participants.php',
    'results'       => APPLICATION_PATH . 'includes/results.php',
    'registration'  => APPLICATION_PATH . 'includes/registration.php',
    'contact'       => APPLICATION_PATH . 'includes/contact.php',
    'gallery'       => APPLICATION_PATH . 'includes/gallery.php',
];

include 'header.html'; // doctype, <html> und das komplette <head>-element
echo "    <body class='page-top'>\n";
include 'menu.phtml';

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
    $ret = include $section['participants'];
}

if (
    is_array($ret) && isset($ret['filename'], $ret['data']) &&
    is_string($ret['filename']) &&
    is_array($ret['data']))
{
    // valid return value
    if (file_exists($file = VIEW_PATH . $ret['filename'])) {
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
include('footer.html');
echo "    </body>\n";
echo "</html>\n";
?>

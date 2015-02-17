<?php
/**
 * Class Service_Db
 */
class Db
{
    protected static $instance;

    public static function getInstance()
    {
        $config = getConfig()['db'];
        if (empty($instance)) {
            self::$instance = new mysqli($config['host'], $config['user'], $config['password'], $config['database']);
            if (mysqli_connect_errno()) {
                throw new \Exception("Konnte keine Verbindung zu Datenbank aufbauen, MySQL meldete: {mysqli_connect_error()}");
            }
            self::$instance->set_charset("utf8");
        }
        return self::$instance;
    }
}

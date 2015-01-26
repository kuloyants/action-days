<?php

/**
 * Class Csv
 */
class Service_Csv
{
    protected static $instance;

    public function getLines($source, $delimiter)
    {
        $file = fopen($source, 'r');
        $fieldNames = fgetcsv($file, null, $delimiter);

        $lines = [];
        while ($line = fgetcsv($file, null, $delimiter)) {
            $lines[] = array_combine($fieldNames, $line);
        }
        fclose($file);
        return $lines;
    }

    public static function getInstance()
    {
        if (empty($instance)) {
            self::$instance = new static();
        }
        return new self::$instance;
    }

    public function getParticipants()
    {

    }

    public function getSeededPlayers()
    {

    }
}

<?php
class View {
    public function playerName($firstname, $surname) {

        $return = null;
        if (!empty(trim($firstname)) && !empty(trim($surname))) {
            $return = strtoupper($surname) . ', ' . $firstname;
        }
        return $return;
    }
}

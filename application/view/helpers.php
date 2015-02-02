<?php
class View {
    public function playerName($firstname, $surname) {
        return strtoupper($surname) . ', ' . $firstname;
    }
}

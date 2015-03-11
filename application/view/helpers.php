<?php
class View {
    public function playerName($firstname = '', $surname = '') {
        return $firstname . ' ' . strtoupper($surname);
    }
}

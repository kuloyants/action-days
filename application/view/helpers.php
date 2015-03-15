<?php
class View {
    public function playerName($firstname = '', $surname = '') {
        return $firstname . ' ' . mb_strtoupper($surname, 'UTF-8');
    }
}

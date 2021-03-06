<?php
Flight::set('days', [
    1 => 'Mo',
    2 => 'Di',
    3 => 'Mi',
    4 => 'Do',
    5 => 'Fr',
]);

Flight::set('full_days', [
    1 => 'Montag',
    2 => 'Dienstag',
    3 => 'Mittwoch',
    4 => 'Donnerstag',
    5 => 'Freitag',
]);

Flight::set('hour_times', [
    1 => '7:30 - 8:15',
    2 => '8:15 - 9:00',     // Pause 15min
    3 => '9:15 - 10:00',
    4 => '10:00 - 10:45',   // Pause 15min
    5 => '11:00 - 11:45',
    6 => '11:45 - 12:30',   // Pause 30min
    7 => '13:00 - 13:45',
    8 => '13:45 - 14:30',   // Pause 15min
    9 => '14:45 - 15:15',
]);
?>
<?php
require 'flight/Flight.php';
require 'constants.php';
require 'medoo.min.php';
header("Access-Control-Allow-Origin: *");
Flight::register('db', 'medoo');

Flight::route('/', function(){
    Flight::json(
        [
            'documentation_url' => 'http://e2fi-stundenplan.readme.io/',
            'version'           => '1.0.5',
            'last_update'       => date('Y-m-d H:i:s', filemtime('index.php'))
        ]
    );
});

Flight::route('/day/@day:[1-5]{1}/', function($day){
    $table = Flight::db()->select(
        'timetable',
        [
            "[><]teachers" => ['teacher' => 'id'],
            "[><]subjects" => ['subject' => 'id']
        ],
        [
            'timetable.subject',
            'timetable.room',
            'timetable.hour',
            'timetable.day',
            'teachers.short(teacher)',
            'subjects.short(subject)'
        ],
        [
            'day'   => Flight::get('days')[$day],
            'ORDER' => 'timetable.hour ASC',
        ]
    );

    foreach ($table as $key => $row) {
        list($table[$key]['time_start'], $table[$key]['time_end']) = explode(' - ', Flight::get('hour_times')[$row['hour']]);
        $table[$key]['time']       = Flight::get('hour_times')[$row['hour']];
        $table[$key]['day_full']   = Flight::get('full_days')[$day];
        $table[$key]['day_number'] = $day;
        $table[$key]['room']       = ($table[$key]['room'] === NULL) ? '-' : $table[$key]['room'];
        ksort($table[$key]);
    }
    Flight::json($table);
});

Flight::route('/teachers', function() {
    $teachers = Flight::db()->select('teachers', ['name', 'short'], ['ORDER' => 'name ASC']);
    Flight::json($teachers);
});

Flight::route('/subjects', function(){
    $subjects = Flight::db()->select('subjects', ['name', 'short'], ['ORDER' => 'name ASC']);
    Flight::json($subjects);
});

Flight::route('/hour_times', function(){
    Flight::json(Flight::get('hour_times'));
});

Flight::route('/full_days', function(){
    Flight::json(Flight::get('full_days'));
});

Flight::route('/weeks', function() {
    $weeks = Flight::db()->select('weeks', ['start_date', 'end_date'], ['ORDER' => 'start_date ASC']);
    Flight::json($weeks);
});

Flight::start();
?>

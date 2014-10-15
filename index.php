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
            'version'           => '1.0.2',
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
        ksort($table[$key]);
        $table[$key]['time'] = Flight::get('hour_times')[$row['hour']];
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

Flight::start();
?>

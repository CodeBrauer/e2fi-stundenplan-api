<?php 
if (!function_exists('array_column')) {
    die('Minimum PHP Version: 5.5.0');
}

function call_api($param)
{
    $curl = curl_init();
    $day = 2;
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL            => 'http://e2fi.eu/s/api'.$param,
        ));

    $data = curl_exec($curl);
    curl_close($curl);

    $array = json_decode($data, true);
    return $array;
}

// init all days to array
$days = [];
for ($i=1; $i <= 5; $i++) { 
    $days[$i] = call_api('/day/'.$i);
}

// "rotate" the array colums => rows / rows => colums
for ($i=0; $i <= 10; $i++) {
    $table_rows[$i] = array_column($days, $i); // need php 5.5 for that (array_column)!
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sample with PHP</title>
    <meta name="author" content="gabrielw.de">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootswatch/3.2.0/paper/bootstrap.min.css">
    <style>
        body {padding-top: 20px; padding-bottom: 20px; }
        .navbar {margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">

        <!-- Static navbar -->
        <div class="navbar navbar-default navbar-inverse" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">E2FI - SP-API</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="#">Version <?= call_api('/')['version'] ?></a></li>
                        <li><a href="<?= call_api('/')['documentation_url'] ?>">Doku</a></li>
                        <li><a href="#">Letztes Update: <?= call_api('/')['last_update'] ?></a></li>
                    </ul>
                </div>
            </div>
        </div>

        <h3>Stundenplan</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="text-center">Montag</th>
                        <th class="text-center">Dienstag</th>
                        <th class="text-center">Mittwoch</th>
                        <th class="text-center">Donnerstag</th>
                        <th class="text-center">Freitag</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($table_rows as $row): ?>
                        <tr>
                            <?php foreach ($row as $item): ?>
                                <td data-day="<?= $item['day']?>" class="text-center"><?= $item['hour'].'. '.$item['subject'] ?> </td>
                            <?php endforeach ?>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>

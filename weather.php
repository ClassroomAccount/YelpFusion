<?php
//get weather by city name or using zipcode

$apiKey = "b137ecd77ed9d210f03a93b7af543372";

//weather by city
$cityId = "Paris";
//$googleApiUrl = "https://api.openweathermap.org/data/2.5/weather?q=" . $cityId . "&lang=en&units=metric&APPID=" . $apiKey;

//weather by zip code
$zipCode = "02478";
$countryCode = "us";
$googleApiUrl = "https://api.openweathermap.org/data/2.5/weather?zip=" . $zipCode.",".$countryCode . "&lang=en&units=metric&APPID=" . $apiKey;

try {
//open connection
    $ch = curl_init($googleApiUrl);

    if (FALSE === $ch)
        throw new Exception('Failed to initialize');

//set parameters
    curl_setopt($ch, CURLOPT_HEADER, false);    //no header in output
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  //return as string

//search
    $response = curl_exec($ch);

//check for successful response
    if ($response === false)
        throw new Exception(curl_error($ch), curl_errno($ch));
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($http_status != 200)
        throw new Exception($response, $http_status);

    curl_close($ch);
} catch (Exception $e) {
    trigger_error(sprintf(
        'Curl failed with error #%d: %s',
        $e->getCode(), $e->getMessage()),
        E_USER_ERROR);
}
//JSON return data
$data = json_decode($response);
$currentTime = time();
?>

<!doctype html>
<html>
<head>
    <title>Forecast Weather using OpenWeatherMap with PHP</title>

    <style>
        body {
            font-family: Arial;
            font-size: 0.95em;
            color: #929292;
        }

        .report-container {
            border: #E0E0E0 1px solid;
            padding: 20px 40px 40px 40px;
            border-radius: 2px;
            width: 550px;
            margin: 0 auto;
        }

        .weather-icon {
            vertical-align: middle;
            margin-right: 20px;
        }

        .weather-forecast {
            color: #212121;
            font-size: 1.2em;
            font-weight: bold;
            margin: 20px 0px;
        }

        span.min-temperature {
            margin-left: 15px;
            color: #929292;
        }

        .time {
            line-height: 25px;
        }
    </style>

</head>
<body>

<div class="report-container">
    <h2><?php echo $data->name; ?> Weather Status</h2>
    <div class="time">
        <div><?php echo date("l g:i a", $currentTime); ?></div>
        <div><?php echo date("jS F, Y", $currentTime); ?></div>
        <div><?php echo ucwords($data->weather[0]->description); ?></div>
    </div>
    <div class="weather-forecast">
        <img
                src="http://openweathermap.org/img/w/<?php echo $data->weather[0]->icon; ?>.png"
                class="weather-icon"> <?php echo $data->main->temp_max; ?>&deg;C<span
                class="min-temperature"><?php echo $data->main->temp_min; ?>&deg;C</span>
    </div>
    <div class="time">
        <div>Humidity: <?php echo $data->main->humidity; ?> %</div>
        <div>Wind: <?php echo $data->wind->speed; ?> km/h</div>
    </div>
</div>


</body>
</html>
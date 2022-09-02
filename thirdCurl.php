<?php

//previous example doing GET request
try {

//initialize curl session
    $ch = curl_init('https://www.geeksforgeeks.org');

    if (FALSE === $ch)
        throw new Exception('Failed to initialize');

//set curl options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// execute
    $response = curl_exec($ch);

//test for errors
    if ($response === false)
        throw new Exception(curl_error($ch), curl_errno($ch));
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($http_status != 200)
        throw new Exception($response, $http_status);


// close the connection, release resources used
    curl_close($ch);

} catch (Exception $e) {
    trigger_error(sprintf(
        'Curl failed with error #%d: %s',
        $e->getCode(), $e->getMessage()),
        E_USER_ERROR);
}


// do anything you want with your response
var_dump($response);
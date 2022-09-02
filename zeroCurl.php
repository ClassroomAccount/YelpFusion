<?php
// Create curl resource
$ch = curl_init('https://www.example.com?name=harry');

//set curl options
// Return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// execute
$response = curl_exec($ch);

// close the connection, release resources used
curl_close($ch);

// do anything you want with your response
var_dump($response);
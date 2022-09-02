<?php
//example using POST request

//compose query string
$post = [
    'username' => 'harry',
    'password' => 'bentley',
    'gender' => 'male'
];

// Create curl resource
$ch = curl_init('https://www.example.com');

//set curl options
// Return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//identify query string
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

// execute
$response = curl_exec($ch);

// close the connection, release resources used
curl_close($ch);

// do anything you want with your response
var_dump($response);
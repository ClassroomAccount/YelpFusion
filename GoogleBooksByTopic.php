Lookup Author or Topic on GoogleBooks

<!doctype html>
<html>
<body>
<pre>
<?php

//previous example using GET request

try {

//initialize curl session
    $ch = curl_init();

//set options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/books/v1/volumes?q=inauthor:hemingway&maxResults=20");
    ///curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/books/v1/volumes?q=robotics&maxResults=20&orderBy=newest");
    //  curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/books/v1/volumes?q=java+inauthor:Gaddis");

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

//JSON return data
$data = json_decode($response);

$pretty_response = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
//print "$pretty_response\n";


$items = $data ->items;

for($i = 0; $i<count($items); $i++){
$volumeInfoObj = $items[$i];

$volumeInfo = $volumeInfoObj -> volumeInfo;

echo "<p>" . $volumeInfo -> title . ", "
           . $volumeInfo -> authors[0] . ", "
      //     . $volumeInfo -> publisher . ", "
           . $volumeInfo -> publishedDate . "</p>";

}
?>
 </pre>
</body>
</html>
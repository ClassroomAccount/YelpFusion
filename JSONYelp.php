<!doctype html>
<html>
<head>
    <style>
        table  {border:1px solid green; border-collapse:collapse;
                         margin-right: auto; margin-left: auto;
                        font-size:20px; }
    th, td {border:1px solid green; padding:5px;}
    h2 {text-align:center;color:blue;}
    </style>
</head>
<body>
<pre>
<table>
<?php
/**
 * Yelp Fusion API code sample.
 *
 * This program demonstrates the capability of the Yelp Fusion API
 * by using the Business Search API to query for businesses by a
 * search term and location, and the Business API to query additional
 * information about the top result from the search query.
 *
 * Please refer to http://www.yelp.com/developers/v3/documentation
 * for the API documentation.
 *
 * Sample usage of the program:
 * `php sample.php --term="dinner" --location="San Francisco, CA"`
 */

// API key placeholders that must be filled in by users.
// You can find it on
// https://www.yelp.com/developers/v3/manage_app
$API_KEY = "TSKoW6Rmk9zXWMZP_-t6BeC0BPTPBbFxZR3I7GYSxs6UHnMiN3hOwDeNFkvNqx3d7S5f9e4Qt7iFEoZ6b_wWE4W-k1EsHpqyBktOS4lMR4M8zcyrMzO9BMpezECgWnYx";

// Complain if credentials haven't been filled out.
assert($API_KEY, "Please supply your API key.");

// API constants, you shouldn't have to change these.
$API_HOST = "https://api.yelp.com";
$SEARCH_PATH = "/v3/businesses/search";
$BUSINESS_PATH = "/v3/businesses/";  // Business ID will come after slash.

// Defaults for our simple example.
$DEFAULT_TERM = "dinner";
$DEFAULT_LOCATION = "New York, NY";
$SEARCH_LIMIT = 10;


/**
 * Makes a request to the Yelp API and returns the response
 *
 * @param    $host    The domain host of the API
 * @param    $path    The path of the API after the domain.
 * @param    $url_params    Array of query-string parameters.
 * @return   The JSON response from the request
 */
function request($host, $path, $url_params = array()) {
    // Send Yelp API Call
    try {
        $curl = curl_init();

        if (FALSE === $curl)
            throw new Exception('Failed to initialize');

        $url = $host . $path . "?" . http_build_query($url_params);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,  // Capture response.
            CURLOPT_ENCODING => "",  // Accept gzip/deflate/whatever.
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . $GLOBALS['API_KEY'],
                "cache-control: no-cache",
            ),
        ));

        $response = curl_exec($curl);

        if ($response === false)
            throw new Exception(curl_error($curl), curl_errno($curl));
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($http_status != 200)
            throw new Exception($response, $http_status);

        curl_close($curl);
    } catch(Exception $e) {
        trigger_error(sprintf(
            'Curl failed with error #%d: %s',
            $e->getCode(), $e->getMessage()),
            E_USER_ERROR);
    }

    return $response;
}

/**
 * Query the Search API by a search term and location
 *
 * @param    $term        The search term passed to the API
 * @param    $location    The search location passed to the API
 * @return   The JSON response from the request
 */
function search($term, $location) {
    $url_params = array();

    $url_params['term'] = $term;
    $url_params['location'] = $location;
    $url_params['limit'] = $GLOBALS['SEARCH_LIMIT'];

    return request($GLOBALS['API_HOST'], $GLOBALS['SEARCH_PATH'], $url_params);
}

/**
 * Query the Business API by business_id
 *
 * @param    $business_id    The ID of the business to query
 * @return   The JSON response from the request
 */
function get_business($business_id) {
    $business_path = $GLOBALS['BUSINESS_PATH'] . urlencode($business_id);

    return request($GLOBALS['API_HOST'], $business_path);
}

/**
 * Queries the API by the input values from the user
 *
 * @param    $term        The search term to query
 * @param    $location    The location of the business to query
 */
function query_api($term, $location) {
    $response = json_decode(search($term, $location));

    echo "<h2>$term"." in ". "$location</h2>";
    echo "<tr><th>Name</th><th>Specialty</th><th>Address</th><th>Rating</th><th>Review Count</th></tr>";

    //loop over resulting restaurants
   for($i = 0; $i<$GLOBALS['SEARCH_LIMIT']; $i++){

    $business_id = $response->businesses[$i]->id;

    $responseBiz = get_business($business_id);

        //get information from JSON objects returned
       $decoded_json = json_decode($responseBiz);
       $decoded_name = $decoded_json->name;
       $decoded_rating = $decoded_json->rating;
       $decoded_location = $decoded_json->location;
       $decoded_address = $decoded_location->address1;
       $decoded_categories = $decoded_json->categories;
       $decoded_title = $decoded_categories[0]->title;
       $decoded_review_count = $decoded_json->review_count;

      echo "<tr><td>$decoded_name</td><td>$decoded_title</td> <td>$decoded_address</td><td>$decoded_rating</td>
                          <td>$decoded_review_count</td></td></tr>";
 //  $pretty_response = json_encode($decoded_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
 //   print "$pretty_response\n";

    };
}

query_api($GLOBALS['DEFAULT_TERM'], $GLOBALS['DEFAULT_LOCATION']);

?>

</table>
</pre>
</body>
</html>

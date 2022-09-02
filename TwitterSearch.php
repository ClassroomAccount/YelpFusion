<?php

//search and receive decoded json
$decoded_json = queryTwitter("boston");
//$pretty_response = json_encode($decoded_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
//print "$pretty_response\n";

$statuses = $decoded_json->statuses;

//loop over each resulting array of items
for ($i = 0; $i < sizeof($statuses); $i++){

    $text = $statuses[$i]-> text;
    $created_at = $statuses[$i]->created_at;
    print $created_at."\n";
    print $text."\n";
}

function queryTwitter($search)
{
    $url = "https://api.twitter.com/1.1/search/tweets.json";
    if($search != "")
        $search = "#".$search;
    $query = array( 'count' => 20, 'q' => urlencode($search), "result_type" => "recent");
    $oauth_access_token = "611005927-ZPCbTO2dCNNroutda5oWgTIhoeG2EE5fTjsZIyeN";
    $oauth_access_token_secret = "3QeQSvw0UmRmcBiEy0mEDQpcnH2G2rSHs3kbY1rnnZKTG";
    $consumer_key = "V6TJ18GeZnn4sxii9w08vUizs";
    $consumer_secret = "2Urd8Nnb27XeeVmR4llbtMRZzGA3OHvz8HBT3oAx7BQwOGPffH";

    $oauth = array(
        'oauth_consumer_key' => $consumer_key,
        'oauth_nonce' => time(),
        'oauth_signature_method' => 'HMAC-SHA1',
        'oauth_token' => $oauth_access_token,
        'oauth_timestamp' => time(),
        'oauth_version' => '1.0');

    $base_params = empty($query) ? $oauth : array_merge($query,$oauth);
    $base_info = buildBaseString($url, 'GET', $base_params);
    $url = empty($query) ? $url : $url . "?" . http_build_query($query);

    $composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
    $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
    $oauth['oauth_signature'] = $oauth_signature;

    $header = array(buildAuthorizationHeader($oauth), 'Expect:');
    $options = array( CURLOPT_HTTPHEADER => $header,
        CURLOPT_HEADER => false,
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false);

    $feed = curl_init();
    curl_setopt_array($feed, $options);
    $json = curl_exec($feed);
    curl_close($feed);
    return  json_decode($json);
}

function buildBaseString($baseURI, $method, $params)
{
    $r = array();
    ksort($params);
    foreach($params as $key=>$value){
        $r[] = "$key=" . rawurlencode($value);
    }
    return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
}

function buildAuthorizationHeader($oauth)
{
    $r = 'Authorization: OAuth ';
    $values = array();
    foreach($oauth as $key=>$value)
        $values[] = "$key=\"" . rawurlencode($value) . "\"";
    $r .= implode(', ', $values);
    return $r;
}

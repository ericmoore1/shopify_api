<?php
// http://localhost/fodername/generate_token.php?
// code=33db0e2e7ee1e4ca5403678100a165d0&
// hmac=0c4e3e8e3eebe2033f0b9efb132743839e65fe63ec8309b7c488a4efeb48d533&
//shop=yourshopname.myshopify.com&
//timestamp=1560000755

// code : used for oauth
// Set variables for our request
require_once('./config.php');
$api_key = API_KEY;
$shared_secret = SECRET_KEY;
$params = $_GET; // Retrieve all request parameters
$hmac = $_GET['hmac']; // Retrieve HMAC request parameter
$params = array_diff_key($params, array('hmac' => '')); // Remove hmac from params
ksort($params); // Sort params lexographically
// Compute SHA256 digest
$computed_hmac = hash_hmac('sha256', http_build_query($params), $shared_secret);
// Use hmac data to check that the response is from Shopify or not
$is_good = true;
if (!hash_equals($hmac, $computed_hmac)) {
	$is_good = false;
}

if(!$is_good){
	echo "BAD REQUEST";
	die();
}
// Set variables for our request
$query = array(
  "client_id" => $api_key, // Your API key
  "client_secret" => $shared_secret, // Your app credentials (secret key)
  "code" => $params['code'] // Grab the access key from the URL
);

// Generate access token URL
$access_token_url = "https://" . $params['shop'] . "/admin/oauth/access_token";

// Configure curl client and execute request
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $access_token_url);
curl_setopt($ch, CURLOPT_POST, count($query));
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
$result = curl_exec($ch);
curl_close($ch);
// Store the access token
file_put_contents(dirname(__FILE__) . '/json/token.json', $result);

echo $result;
?>

<?php
	require_once('./config.php');
  header('Content-type: application/json');
  // get token information from created json file.
  $the_file = dirname(__FILE__) . '/json/token.json';
  if(!file_exists($the_file)){
    echo "Need to create json";
    die();
  }
  $json = file_get_contents($the_file);
  echo $json;
	// Set variables for our request
	$shop = SHOP_NAME;
	$token = json_decode($json,true)['access_token'];
	$api_endpoint = '';

	$allowedReads = array(
	   'products' => '/admin/api/2019-04/products.json',
	   'collects' => '/admin/api/2019-04/collects.json'
	);

	if(!isset($allowedReads[$_GET['read']])){
		echo json_encode( array( 'error' => 'not a valid space...') );
		die();
	}

	$api_endpoint = $allowedReads[$_GET['read']];

	$query = array(
		"Content-type" => "application/json" // Tell Shopify that we're expecting a response in JSON format
	);

	// Run API call to get all products
	$url = "https://" . $shop . ".myshopify.com" . $api_endpoint;
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array( 'X-Shopify-Access-Token: ' . $token ));
    $response = curl_exec($curl);
    curl_close($curl);
	$res = json_decode($response,true);
	echo json_encode($res);
?>

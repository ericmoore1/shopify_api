<?php

	require_once('./config.php');
	// Set variables for our request
  header('Content-type: application/json');
  // get token information from created json file.
  $the_file = dirname(__FILE__) . '/json/token.json';
  if(!file_exists($the_file)){
    echo "Need to create json";
    die();
  }
  $json = file_get_contents($the_file);
	$shop = SHOP_NAME;
	$token = json_decode($json,true)['access_token'];

/*START API AND QUERY*/
// the stuff in here needs to be made dynamic.
  $api_endpoint = '/admin/api/2019-04/products.json';

	$query = array(
		"product" => array(
		"title" => "Eric Testing 2000",
		"body_html" => "<strong>Good snowboard!</strong>",
		"vendor" => "Burton",
		"product_type" => "Snowboard",
		"published" => false
		)
	);
/*END API AND QUERY*/

	// Run API call to get all products
	$url = "https://" . $shop . ".myshopify.com" . $api_endpoint;
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
  curl_setopt($curl, CURLOPT_HTTPHEADER, array( 'X-Shopify-Access-Token: ' . $token ));
  curl_setopt ($curl, CURLOPT_POSTFIELDS, http_build_query($query));
  $response = curl_exec($curl);
  curl_close($curl);
	$res = json_decode($response,true);
	echo $response;
?>

<?php
// allows us to go to store to get oauth token.
// You will need to login.
require_once('./config.php');

$api_key = API_KEY;
$secret_name = SECRET_KEY;
$shop_name = SHOP_NAME;
$redirect_url = REDIRECT_URL;

// get scopes: https://help.shopify.com/en/api/getting-started/authentication/oauth/scopes
$scopes = "unauthenticated_read_product_listings,read_products,write_products";

// Build install/approval URL to redirect to
$install_url = "https://" . $shop_name . ".myshopify.com/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . urlencode($redirect_url);

// redirect to get oauth token.
header("Location: " . $install_url);
die();
?>

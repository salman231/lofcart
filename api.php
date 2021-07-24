<?php
$adminAuthorizationUrl = 'https://www.trufflecollection.co.in/oauth/authorize';
$accessTokenRequestUrl = 'https://www.trufflecollection.co.in/oauth/token';
$apiUrl = 'https://www.trufflecollection.co.in/api/rest';
$consumerKey = '47e9e65227724b94c276ef6902756107';
$consumerSecret = 'ae809f498d5543f2b2645fc0c2d761ae';
$access_token = '247f26aefe78937707727198aa873ae4';
$access_token_secret = '72c084af02c46a008d56afa288810db4';

session_start();
try {
    // $authType = ($_SESSION['state'] == 2) ? 'OAUTH_AUTH_TYPE_AUTHORIZATION' : 'OAUTH_AUTH_TYPE_URI';
    $authType = 'OAUTH_AUTH_TYPE_URI';
    $oauthClient = new OAuth($consumerKey, $consumerSecret, 'OAUTH_SIG_METHOD_HMACSHA1', $authType);
    $oauthClient->enableDebug();

    $oauthClient->setToken($access_token, $access_token_secret);
    $resourceUrl = "$apiUrl/products";
    $oauthClient->fetch($resourceUrl, array(), 'GET', array('Content-Type' => 'application/json', 'Accept' => '*/*'));
    $productsList = json_decode($oauthClient->getLastResponse());
    print_r($productsList);
} catch (OAuthException $e) {
    print_r($e);
}
?>
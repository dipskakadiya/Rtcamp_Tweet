<?php 
//disable warnings . delete the code beofre moving to production
//error_reporting(E_ALL ^ E_NOTICE);
ob_start();
session_start();
require_once('lib/twitteroauth/twitteroauth.php');
require_once('config.php');

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
$request_token = $connection->getRequestToken(OAUTH_CALLBACK);

$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

//delete before uploading
print_r($request_token );

switch ($connection->http_code) {
    case 200:
        //Build authorize URL and redirect user to Twitter.
        $url = $connection->getAuthorizeURL($_SESSION['oauth_token']);
        header('Location: ' . $url); 
        break;
    default:
        //Error
        echo "some error occured";
}


?>


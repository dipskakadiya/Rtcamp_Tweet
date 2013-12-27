<?php
// user is redirected to this page after succesfull authentication
// set required variables into  the session
session_start();
require_once('lib/twitteroauth/twitteroauth.php');
require_once('config.php');

// if access tokens are not available redirect to login page.
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
    $_SESSION['oauth_status'] = 'expired_token';
    header('Location: clearsession.php');
}

// create TwitteroAuth object with app key/secret and token key/secret
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

// request access tokens from twitter
$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

// save this access token into session
$_SESSION['access_token'] = $access_token;

// remove no longer needed request tokens that were set in the login page
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

// proceed furhter is HTTP response is 200 or else send to error page 
if (200 == $connection->http_code) {
    $_SESSION['status'] = 'verified';
    header('Location: timeline#home');
} else {
//error page
    header('Location: error.html');
}
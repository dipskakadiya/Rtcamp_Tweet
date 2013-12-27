<?php
session_start();
require_once ('lib/twitteroauth/twitteroauth.php');
require_once ('config.php');

$request = $_POST['type'];

// tf access tokens are not available,clear session and redirect to login page.
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
	header('Location: clearsession.php');
}
// get user access tokens from the session.
$access_token = $_SESSION['access_token'];

// create a TwitterOauth object with tokens.
$twitteroauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

// get the current user's info
	$user_info = $twitteroauth -> get('account/verify_credentials');


if ($request == "home") {
	
	//get the latest 10 tweets of the current user from his home timline
	$tweets = $twitteroauth -> get("https://api.twitter.com/1.1/statuses/home_timeline.json?screen_name=" . $user_info -> screen_name . "&count=15&contributor_details=true");
}

if ($request == "followers") {
	$username = $_POST['userName'];
	
	if($username=="me"){
		$username=$user_info -> screen_name;
	}
	
	//get the latest 10 tweets of the current user from his timline
	$tweets = $twitteroauth -> get("https://api.twitter.com/1.1/statuses/user_timeline.json?include_entities=true&screen_name=" . $username . "&count=15");

	if (isset($tweets -> error) && $tweets -> error == "Not authorized") {
		echo "unauthorized";
		// access to specified user's timeline is not allowed
		exit ;
	}

}

header('Content-type: application/json; charset=utf-8');
echo json_encode($tweets);
?>
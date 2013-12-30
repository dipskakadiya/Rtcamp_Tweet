<?php
session_start();
//Twitter authentication required file
require_once ('lib/twitteroauth/twitteroauth.php');
require_once ('config.php');

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

//get the latest 10 tweets of the current user from his home timline
$tweets = $twitteroauth -> get("https://api.twitter.com/1.1/statuses/home_timeline.json?screen_name=" . $user_info -> screen_name . "&count=15&contributor_details=true");

//Set the file name
$filename = "export.json";

//created json array for tweets
$jsonArray = array();
$i = 0;
foreach ($tweets as $line) {
	$tweet = array();
	$tweet['idStr'] = "'" . $line -> id_str . "'";
	$tweet['createdAt'] = $line -> created_at;
	$tweet['text'] = $line -> text;
	$tweet['name'] = $line -> user -> name;
	$tweet['screeNname'] = $line -> user -> screen_name;
	$tweet['profileImageUrl'] = $line -> user -> profile_image_url;
	$jsonArray[$i] = $tweet;
	$i++;
}

header('Content-Description: File Transfer');
// tell the browser it's going to be a octet-stream file
header('Content-Type: application/octet-stream');
// tell the browser we want to save it instead of displaying it
header('Content-Disposition: attachment; filename=' . basename($filename));
//tell the browser Content-Transfer-Encoding of file
header('Content-Transfer-Encoding: binary');
//tell the browser Expires
header('Expires: 0');
//tell the browser Cache-Control
header('Cache-Control: Pragma');
//tell the browser Cache-Control
header('Pragma: public');
// tell the browser file size
header('Content-Length: ' . filesize($filename));
ob_clean();
flush();
//Convert tweets array into json and print it.
echo json_encode($jsonArray);
?>
<?php
session_start();
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

$filename="export.json";

$jsonArray=array();
$i=0;
foreach ($tweets as $line) {
	$tweet=array();
	$tweet['idStr']="'" . $line -> id_str . "'";
	$tweet['createdAt']=$line -> created_at;
	$tweet['text']=$line -> text;
	$tweet['name']=$line -> user -> name;
	$tweet['screeNname']=$line -> user -> screen_name;
	$tweet['profileImageUrl']=$line -> user -> profile_image_url;
	$jsonArray[$i]=$tweet;
	$i++;
}

	header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($filename));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filename));
    ob_clean();
    flush();

echo json_encode($jsonArray);

?>
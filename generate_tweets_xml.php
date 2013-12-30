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

$filename="export.xml";

$doc = new DOMDocument('1.0');
// we want a nice output
$doc -> formatOutput = true;

// generate xml Tweets object
$root = $doc -> createElement('Tweets');
$root = $doc -> appendChild($root);

foreach ($tweets as $line) {
	// generate xml Tweet object into Tweets root object
	$title = $doc -> createElement('Tweet');
	$title = $root -> appendChild($title);

	$id_str = $doc -> createElement('idStr');
	$id_str = $title -> appendChild($id_str);
	$text = $doc -> createTextNode("'" . $line -> id_str . "'");
	$text = $id_str -> appendChild($text);
	
	$created_at = $doc -> createElement('createdAt');
	$created_at = $title -> appendChild($created_at);
	$text = $doc -> createTextNode($line -> created_at);
	$text = $created_at -> appendChild($text);
	
	$tweet_text = $doc -> createElement('text');
	$tweet_text = $title -> appendChild($tweet_text);
	$text = $doc -> createTextNode($line -> text);
	$text = $tweet_text -> appendChild($text);
	
	$user = $doc -> createElement('name');
	$user = $title -> appendChild($user);
	$text = $doc -> createTextNode($line -> user -> name);
	$text = $user -> appendChild($text);
	
	$screen_name = $doc -> createElement('screeNname');
	$screen_name = $title -> appendChild($screen_name);
	$text = $doc -> createTextNode($line -> user -> screen_name);
	$text = $screen_name -> appendChild($text);
	
	$profile_image_url = $doc -> createElement('profileImageUrl');
	$profile_image_url = $title -> appendChild($screen_name);
	$text = $doc -> createTextNode($line -> user -> profile_image_url);
	$text = $profile_image_url -> appendChild($text);

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
//save DomDocument and print it.
	echo $doc -> saveXML() . "\n";
?>
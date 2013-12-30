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

$filename = "export.csv";
$delimiter=",";

// open raw memory as file so no temp files needed, you might run out of memory though
$f = fopen('php://memory', 'w');

fputcsv($f,array("id_str","created_at","text","name","screen_name","profile_image_url"), $delimiter);
// loop over the input array
foreach ($tweets as $line) {
	// generate csv lines from the inner arrays
	fputcsv($f, array("'".$line->id_str."'",$line->created_at,$line->text,$line->user->name,$line->user->screen_name,$line->user->profile_image_url), $delimiter);
}
// rewrind the "file" with the csv lines
fseek($f, 0);
// tell the browser it's going to be a csv file
header('Content-Type: application/csv');
// tell the browser we want to save it instead of displaying it
header('Content-Disposition: attachement; filename="' . $filename . '"');
// make php send the generated csv lines to the browser
fpassthru($f);
?>
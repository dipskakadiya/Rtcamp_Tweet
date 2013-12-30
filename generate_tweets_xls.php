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

//file for generate excel documents
include ("lib/excel.php");

//create new excel file
$excel = new ExcelWriter("download/export.xls");

if ($excel == false)
	echo $excel -> error;

//write first row with field name
$excel -> writeLine(array("id_str", "created_at", "text", "name", "screen_name", "profile_image_url"));

foreach ($tweets as $line) {
	//write single row into excel document
	$excel -> writeRow();
	$excel -> writeCol("'" . $line -> id_str . "'");
	$excel -> writeCol($line -> created_at);
	$excel -> writeCol($line -> text);
	$excel -> writeCol($line -> user -> name);
	$excel -> writeCol($line -> user -> screen_name);
	$excel -> writeCol($line -> user -> profile_image_url);
}

//new created file close
$excel -> close();
echo "data is write into myXls.xls Successfully.";

// tell the browser we want to save it instead of displaying it
header("location:download/export.xls");
?>
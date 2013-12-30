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

$filename = "download/export.csv";
$delimiter = ",";

// open raw memory as file so no temp files needed, you might run out of memory though
$f = fopen($filename, 'w');

fputcsv($f, array("id_str", "created_at", "text", "name", "screen_name", "profile_image_url"), $delimiter);
// loop over the input array
foreach ($tweets as $line) {
	// generate csv lines from the inner arrays
	fputcsv($f, array("'" . $line -> id_str . "'", $line -> created_at, $line -> text, $line -> user -> name, $line -> user -> screen_name, $line -> user -> profile_image_url), $delimiter);
}
fseek($f, 0);

//Google  authentication required file
require_once 'lib/google-api-php-client/src/Google_Client.php';
require_once 'lib/google-api-php-client/src/contrib/Google_DriveService.php';

//Create obj of google client
$client = new Google_Client();
//set ClientId for google authentication
$client -> setClientId("828657041989-u131fg93ems3g5dtip9qfmj4vfi3j8kk.apps.googleusercontent.com");
//set ClientSecret for google authentication
$client -> setClientSecret('F64SW-3Gf0QkwbxufsBINJQC');
//set RedirectUri for google authentication
$client -> setRedirectUri('http://127.0.0.1/Rtcamp_Tweet/generate_tweets_GSpreadsheet.php');
//set Scopes for google authentication
$client -> setScopes(array('https://www.googleapis.com/auth/drive'));
$client -> setUseObjects(true);

//Create obj of google DriveService for access drive
$service = new Google_DriveService($client);

try {
	//check google authentication
	$client -> authenticate();
	$client -> createAuthUrl();
	//Create google drive file
	$file = new Google_DriveFile();
	//set Title for new created file
	$file -> setTitle("tweet");
	//set Description for new created file
	$file -> setDescription("TweetyFly Tweet Download");
	//set MimeType for new created file
	$file -> setMimeType("application/vnd.google-apps.spreadsheet");
	if (isset($file -> parentId) && $file -> parentId != null) {
		//get parent reference from google
		$parent = new ParentReference();
		//set id for file parent
		$parent -> setId($file -> parentId);
		//set parent to the new creted file
		$file -> setParents(array($parent));
	}
	//created object fot file content
	$data = file_get_contents($filename);

	//insert the file into google drive
	$createdFile = $service -> files -> insert($file, array('data' => $data, 'mimeType' => "application/vnd.google-apps.spreadsheet", 'convert' => true));
	echo "File Was Successfully Created. File ID: " . $createdFile -> id;
	//page redirect to the home page
	header("location:timeline#home");
} catch (Exception $ex) {
	echo $ex;
}
?>
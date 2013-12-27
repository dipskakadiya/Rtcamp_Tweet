<?php 
// this file gets the followers and tweets from the limeline of the requested tweeter user
session_start();
require_once('lib/twitteroauth/twitteroauth.php');
require_once('config.php');

$status_id = $_POST['statusId'];
$action = $_POST['action'];
$response = array( 'status' => 'false' );


if(!empty($status_id) && !empty($action))
{
	if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret']))
	{
		header('Content-type: application/json; charset=utf-8');	
		echo json_encode($response);
	}
	else
	{
		// get user access tokens from the session.
		$access_token = $_SESSION['access_token'];
		
		// create a TwitterOauth object with tokens.
		$twitteroauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
		
		
		//if action is delete, delete the post
		if($action == "delete")
		{
			//delete the status
			$delete_status = $twitteroauth->POST("https://api.twitter.com/1.1/statuses/destroy/".$status_id.".json");
		
			if($delete_status->id_str !='') // if status is deleted succesfully;
			{
				$response['status'] = 'true';
			}
		}
		
		//if action is retweet
		if($action == "retweet")
		{
			//delete the status
			$retweet = $twitteroauth->POST("https://api.twitter.com/1.1/statuses/retweet/".$status_id.".json");
			
			$retweet_id = $retweet->id_str; // get the retweet id of the status
		
			if($retweet_id!='') // if retweet is successfully done, pass the retweet id;
			{
				$response['status'] = 'true';
			}
					
		}
		
		//if action is undo retweet
		if($action == "undo_retweet")
		{
			//get the tweet from the id
			$tweet_details = $twitteroauth->GET('https://api.twitter.com/1.1/statuses/show.json?id='.$status_id.'&include_my_retweet=1');
			
			//get retweet id from the response recieved
			$rid = $tweet_details->current_user_retweet->id_str;
			
			//undo rewteet . 
			$undo_retweet = $twitteroauth->POST("https://api.twitter.com/1.1/statuses/destroy/".$rid.".json");
		
			if($undo_retweet->id_str !='') // if undo of retweet is succesfully;
			{
				$response['status'] = 'true';
			}
					
		}
		
		//if action is undo retweet
		if($action == "favorite")
		{
			//favorite the status
			$favorite = $twitteroauth->POST("https://api.twitter.com/1.1/favorites/create.json?id=".$status_id);
		
			if($favorite->id_str !='') // if status is favorited succesfully;
			{
				$response['status'] = 'true';
			}		
		}
		
		//if action is undo retweet
		if($action == "unfavorite")
		{
			//favorite the status
			$unfavorite = $twitteroauth->POST("https://api.twitter.com/1.1/favorites/destroy.json?id=".$status_id);
		
			if($unfavorite->id_str !='') // if status is unfavorited succesfully;
			{
				$response['status'] = 'true';
			}		
		}
		
		//update status if request is "update"
		if($action == "update")
		{
			$status = urlencode($_POST['tweet']);
			$update_status = $twitteroauth->POST("https://api.twitter.com/1.1/statuses/update.json?status=".$status);
			if($update_status->user->id != "") // if status is updated
				{
				$response['status'] = 'true';
			}	
		}
		
		header('Content-type: application/json; charset=utf-8');	
		echo json_encode($response);
		
	}
	
}
else
{
	header('Content-type: application/json; charset=utf-8');	
	echo json_encode($response);
}
?>
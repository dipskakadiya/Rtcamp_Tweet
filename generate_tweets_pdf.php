<?php
//include mpdf
require_once ('lib/mPDF/mpdf.php');

$data = $_POST['html'];

$html = "
		<html>
		<link rel='stylesheet' href='css/foundation.css' />
		<link rel='stylesheet' href='css/style.css'/>
		<body>
			<h1 style='text-align: center;'><img src='images/Tweet.png' style='width:30px;height:30px;vertical-align:middle;margin-right:10px'>TweetyFlyApps</h1>
			<div class='tweet-thread'>";

$html .= $data;

$html .= "
		</div>
		</body>
		</html>
	";

$mpdf = new mPDF('c');
$mpdf -> SetDisplayMode('fullpage');
$mpdf -> WriteHTML($html);
$mpdf -> Output("download/tweets.pdf", "F");

header('Content-type: application/json; charset=utf-8');
echo json_encode(array('status' => 'false'));
?>
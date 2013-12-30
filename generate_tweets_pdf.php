<?php
//include mpdf for pfd generate
require_once ('lib/mPDF/mpdf.php');

//get data
$data = $_POST['html'];

//cretaed object of pdf content
$html = "
		<html>
		<link rel='stylesheet' href='css/foundation.css' />
		<link rel='stylesheet' href='css/style.css'/>
		<body>
			<h1 style='text-align: center;'><img src='images/logoimage.PNG' style='width:50px;height:50px;vertical-align:middle;margin-top:-10px'>TweetyFlyApps</h1>
			<div class='tweet-thread'>";

$html .= $data;

$html .= "
		</div>
		</body>
		</html>
	";

//Create new pdf file
$mpdf = new mPDF('c');
//set DisplayMode for new created file
$mpdf -> SetDisplayMode('fullpage');
//write data content into file
$mpdf -> WriteHTML($html);
//open new creted file into browser 
$mpdf -> Output("download/tweets.pdf", "F");
// tell the browser it's going to be a json file
header('Content-type: application/json; charset=utf-8');
//print the satus of the new created file
echo json_encode(array('status' => 'false'));
?>
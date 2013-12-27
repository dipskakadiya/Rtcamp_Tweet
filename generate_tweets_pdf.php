<?php 
//include mpdf
require_once ('lib/mPDF/mpdf.php');

$data = $_POST['html'];

	$html = "
		<html>
		<style type='text/css'>
		h1{
			text-align:center;
			font-size:24px;
			margin-bottom:30px;
			color:#5e94be;
			}
		
		p.links {
		display:none;
		}
		
		img {
			width:20px;
			height:20px;
			vartical-align:middle;
		}
		
		.tweet {
			margin:10px 0;	
			padding:5px 10px;	
		}
		 .tweet p {
			font-size: 13px;
		}
		
		 .tweet label {
			font-size: 13px;
			font-weight: bold;
			color: #5A5A5A;
		}
		
		 .tweet .tweet-user {
			margin-left: 3px;
			margin-bottom: 5px;
		}
		
		 .tweet .tweet-user span a, .tweet p.tweet-text a{
			font-size:12px;
			color: #7b7b7b;
		}
		 .tweet .tweet-timestamp {
			margin-right: 3px;
			text-align: right;
		}
		.bubble-left {
			background: #B7CC90;
		}
		.tweet-thread {
			padding-left: 10px;
			padding-right: 10px;
			background: #F5F5F5;
		}

		</style>
		<body>
			<h1><img src='images/Tweet.png' style='width:30px;height:30px;vertical-align:middle;margin-right:10px'>Chirp</h1>
			<div class='tweet-thread'>";
		
		
	$html.= $data;	
		
	$html.="
		</div>
		</body>
		</html>
	";
	
$mpdf = new mPDF('c');
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($html);
$mpdf->Output("tweets.pdf","F");

header('Content-type: application/json; charset=utf-8');	
echo json_encode(array( 'status' => 'false' ));

?>
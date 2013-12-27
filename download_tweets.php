<?php 
	header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
	header("Content-Type: application/force-download");
    header('Content-Disposition: attachment; filename="tweets.pdf"');
    header('Content-Transfer-Encoding: binary');
    readfile('tweets.pdf');
	exit;

?>

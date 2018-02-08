<?php 
if(isset($_GET['search']) && strlen($_GET['search'])>0 && preg_match('/^[0-9]+$/',$_GET['search']))
{
	require_once("./class/msisdn.php");
	$result = Msisdn::search($_GET['search']);
	header("HTTP:/1.0 200 Success");
	header('Content-Type: application/json');
	echo json_encode($result);
}
else 
	error();

function error()
{
	echo "Invalid number format!";
	header("HTTP/1.0 400 Bad Request");
}
?>
<?php 
/**
* @author    Jure Škorc <jure.skorc@gmail.com>
* Intercepts any GET requests with parameter search. It checks phone number validity
* and passes it on to Msisdn class for parsing, or triggers error function.
* @returns: JSON object with MNO data, also sets 200 and JSON header.
* @params: search
**/	
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

/**
* PHP Function error, returns error if invalid number format detected and sets 400 header.
* @name: error
**/	
function error()
{
	echo "Invalid number format!";
	header("HTTP/1.0 400 Bad Request");
}
?>
<?php
	
$domainname = preg_replace('/^www[.]/i','',$_SERVER["SERVER_NAME"]);

$whattobackup = $HTTP_GET_VARS['whattobackup'];

if (!$dbhost)
{
	$dbhost = 'localhost';
}

$suppressoutput = $HTTP_GET_VARS['suppressoutput'];
if (!$suppressoutput)
{
?>
	<html>
	<head>
	<meta http-equiv='Pragma' content='no-cache'>
	<base target="_blank">
	<title></title>
	<link rel="stylesheet" href="../../templates/subSilver/subSilver.css" type="text/css">
	<style type="text/css">
	<!-- 
	body { background: #FFFFFF; font-size: 70%; }
	h1 { margin: 0; font: bold 1.8em "Lucida Grande", 'Trebuchet MS', Verdana, sans-serif; text-decoration: none; color: #323D4F; }
	-->
	</style>
	</head>
	<body>
	<h1>Website Backup Suite</h1>	
	<p></p>
<?php
}
?>
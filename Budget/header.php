<!DOCTYPE html>

<?php
error_reporting ( E_ERROR | E_WARNING );
extract ( $_POST );
extract ( $_GET );
extract ( $_FILES );
if (isset ( $_GET ['file'] )) {
	if ($_GET ['file'] != "") {
		unlink ( $file );
	}
}
?>

<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>JIO</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<style type="text/css">
	.bs-example{
    	margin: 20px;
    }
</style>

<script type="text/javascript" src="js/jsDatePick.min.1.3.js"></script>

<link rel="stylesheet" href="css/jsDatePick_ltr.min.css"/>
<link rel="stylesheet" href="css/style.css"/>

</head>
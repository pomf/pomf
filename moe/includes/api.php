<?php
// This file will act as a APIish thing
// Include core file
require_once('core.php');

if(isset($_GET['do'])){

	$butt = $_GET['do'];

	switch($butt){

		case "login":
			login($_POST['email'], $_POST['pass']);
		break;

		case "register":
			register($_POST['email'], $_POST['pass']);
		break;

	default: echo "We could call this a 404 not found... Or what are you doing here?";
	}
}
?>
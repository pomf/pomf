<?php
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
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
			register($_POST['email'], $_POST['pass'], $_POST['code']);
		break;

		case "invite-generate":
			generate($_GET['email'], $_GET['level']);
		break;

		case "mod":
			mod($_GET['action'], $_GET['date'], $_GET['count'], $_GET['why'], $_GET['file'], $_GET['keyword'], $_GET['fileid'], $_GET['hash'], $_GET['originalname']);
		break;

		case "cp":
			header('Location: ../panel');
		break;

		case "search":
			search($_GET['q']);
		break;
		
		case "delete":
			delete($_GET['f'],$_GET['delid']);
		break;

		case "logout":
			session_unset();
			session_destroy();
			session_write_close();
			header('Location: ../login');
		break;

	default: echo "We could call this a 404 not found... Or what are you doing here?";
	}
}
?>

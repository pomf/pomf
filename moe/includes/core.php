<?php
// This file will handle all functions used by Pomf's Moe Panel
$db = new PDO('mysql:host=127.0.0.1;port=3307;dbname=pomf', 'xxx', 'xxx', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));

function register ($email, $pass) {
	global $db;
	$do = $db->prepare("SELECT email FROM accounts WHERE email = (:email)");
	$do->bindParam(':email', $email);
	$do->execute();
	$result = $do->fetch();
	if($result['email'] === $email){
		header('Location: ../register/index.html#fail');
	}else{
	$do = $db->prepare("INSERT INTO accounts (email, pass) VALUES (:email, :pass)");
	$do->bindParam(':email', $email);
	$hash = password_hash($pass, PASSWORD_DEFAULT);
	$do->bindParam(':pass', $hash);
	$do->execute();
	header('Location: api.php?do=cp');
	}
}

function login ($email, $pass) {
	global $db;
	$do = $db->prepare("SELECT pass FROM accounts WHERE email = (:email)");
	$do->bindParam(':email', $email);
	$do->execute();
	$result = $do->fetch(PDO::FETCH_ASSOC);

	if (password_verify($pass, $result['pass'])) {
		header('Location: api.php?do=cp');
	}else{
		header('Location: ../login/index.html#fail');
	}
}
?>
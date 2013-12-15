<?php
// This file will handle all functions used by Pomf's Moe Panel
session_start();
$db = new PDO('mysql:unix_socket=/var/run/mysqld/mysqld.sock;dbname=pomf', 'xxx', 'xxx', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));

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
	$_SESSION['id'] = $result['id'];
	$_SESSION['email'] = $result['email'];
	header('Location: api.php?do=cp');
	}
}

function login ($email, $pass) {
	global $db;
	$do = $db->prepare("SELECT pass, id, email, level FROM accounts WHERE email = (:email)");
	$do->bindParam(':email', $email);
	$do->execute();
	$result = $do->fetch(PDO::FETCH_ASSOC);

	if (password_verify($pass, $result['pass'])) {
		$_SESSION['id'] = $result['id'];
		$_SESSION['email'] = $result['email'];
		$_SESSION['level'] = $result['level'];
		header('Location: api.php?do=cp');
	}else{
		header('Location: ../login/index.html#fail');
	}
}

function search ($word) {
	global $db;
	$str = "%".$word."%";
	$do = $db->prepare("SELECT * FROM files WHERE orginalname LIKE (:1) OR filename LIKE (:2) LIMIT 5");
	$do->bindParam(':1', $str);
	$do->bindParam(':2', $str);
	$do->execute();

	while ($row = $do->fetch(PDO::FETCH_ASSOC)) {
		print $row['orginalname'].' - '.$row['filename'].'<br/>';
	}
}
?>

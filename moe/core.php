<?php
// This file will handle all functions used by Pomf's Moe Panel

// Include the settings file
include_once('settings.php');

// Setup new PDO connection
$db = new PDO(db_conn, db_name, db_user, db_pass);

// Login Function
function login ($username, $password) {
	global $db;

	// Generate password hash
	$hash = password_hash($password, PASSWORD_BCRYPT, bsettings)."\n";

	// Check username and password
	$do = $db->prepare("SELECT password FROM users WHERE username = (:user)");
	$do->bindParam(':user', $username);
	$do->execute();
	$pass = $do->fetch();

	// Check if hash is the same as the one in DB
	if($pass['0']===$password){
		// Return good result
		$return = array('action' => 'cp', 'info' => 'Logged in.');
	}else{
		// Return bad result
		$return = array('info' => 'Wrong username or password, try again.');
	}
		// Return result with JSON
		json_encode($return);
)

// Registration function
function register ($username, $password, $email) {
	global $db;

	// Check if username, email, etc exists
	$do = $db->prepare("SELECT user,email FROM users WHERE username = (:user) OR email = (:email)");
	$do->bindParam(':user', $username);
	$do->bindParam(':email', $email);
	$do->exeute();
	$count = $do->rowCount();

	// If already exists display mess, otherwise continue
	if(!$count==='0'){
		// Return result
		$return = array('info' => 'User already exists.');
	}else{
		// Generate password hash
		$hash = password_hash($password, PASSWORD_BCRYPT, bsettings)."\n";
		// Register new user
		$do = $db->prepare("INSERT INTO users (username, password, email, date) VALUES (:user, :pass, :email, :date)");
		$do->bindParam(':user', $username);
		$do->bindParam(':pass', $hash);
		$do->bindParam(':email', $email);
		$do->bindValue(':date', date('Y-m-d'));
		$do->exeute();
		// Return that user has been made and goto login
		$return = array('action' => 'login', 'info' => 'User has been made, login.');
	}
	// Return JSON
	json_encode($return);
}

function delete ($id, $delid) {
	global $db;

}
?>
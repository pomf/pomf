<?php
// This file will handle all functions used by Pomf's Moe Panel
// and just like the rest of the moe code this is a mess and will be
// replaced soon
session_set_cookie_params(0, '/', '.pomf.se');
session_start();
$db = new PDO('mysql:unix_socket=/var/run/mysqld/mysqld.sock;dbname=xxx', 'xxx', 'xxx', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));

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
	if($_SESSION['level'] === '1'){
	$do = $db->prepare("SELECT orginalname, filename FROM files WHERE orginalname LIKE (:1) OR filename LIKE (:2)");
	$do->bindParam(':1', $str);
	$do->bindParam(':2', $str);
	$do->execute();

	while ($row = $do->fetch(PDO::FETCH_ASSOC)) {
		print $row['orginalname'].' - '.'<a href="http://a.pomf.se/'.$row['filename'].'" target="_BLANK">'.$row['filename'].' </a> '.'<a href="http://moe.pomf.se/includes/api.php?do=delete&f='.$row['filename'].'" target="_BLANK"> Delete</a><br/>';
	}
	
	//Yes I love not being efficient, deal with it.
	}else{
	
	$do = $db->prepare("SELECT orginalname, filename FROM files WHERE orginalname LIKE (:1) OR filename LIKE (:2) LIMIT 5");
	$do->bindParam(':1', $str);
	$do->bindParam(':2', $str);
	$do->execute();

	while ($row = $do->fetch(PDO::FETCH_ASSOC)) {
		print $row['orginalname'].' - '.$row['filename'].'<br/>';
	}
    }
}

function cfdelete ($file) {

	$butts = array(
	'a' => 'zone_file_purge',
	'tkn' => 'xxx',
	'email' => 'xxx',
	'z' => 'pomf.se',
	'url' => urlencode('http://a.pomf.se/'.$file),
		);

	foreach($butts as $dick=>$cum) { $butts_string .= $dick.'='.$cum.'&'; }
		rtrim($butts_string, '&');

	$hue = curl_init();
	curl_setopt($hue,CURLOPT_URL, 'https://www.cloudflare.com/api_json.html');
	curl_setopt($hue,CURLOPT_POST, count($butts));
	curl_setopt($hue,CURLOPT_POSTFIELDS, $butts_string);
	curl_setopt($hue,CURLOPT_RETURNTRANSFER, true);
	curl_exec($hue);
	curl_close($hue);
}

function delete ($filename, $deleteid) {
	if(empty($filename)){
	echo "You did something wrong, baka.";
	}else{
	global $db;
	$do = $db->prepare("SELECT filename, delid, id FROM files WHERE filename = (:filename)");
	$do->bindParam(':filename', $filename);
	$do->execute();
	$result = $do->fetch(PDO::FETCH_ASSOC);

	if($_SESSION['level'] === '1'){
		$do = $db->prepare("DELETE FROM files WHERE id = (:id)");
		$do->bindParam(':id', $result['id']);
		$do->execute();
		unlink('/mnt/disk1/pomf/files/'.$filename);
		cfdelete($filename);
		echo "<br/>File deleted and hopefully deleted from Cloudflares cache in a moment.<br/>";
	}else{
	if(empty($result['delid'])){
	echo "This file doesn't even exist...";
	}else{
	if($result['delid'] === $deleteid){
		$do = $db->prepare("DELETE FROM files WHERE id = (:id)");
		$do->bindParam(':id', $result['id']);
		$do->execute();
		cfdelete($filename);
		unlink('/mnt/disk1/pomf/files/'.$filename);
		cfdelete($filename);
		echo "<br/>File deleted and hopefully deleted from Cloudflares cache in a moment.<br/>";
	}else{
		echo "Wrong delete ID...";
     }//hue
    }//hue
   }//hue
  }//hue
 }//penis
?>

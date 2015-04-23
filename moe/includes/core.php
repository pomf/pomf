<?php
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
// This file will handle all functions used by Pomf's Moe Panel
session_start();
$db = new PDO('mysql:unix_socket=/var/run/mysqld/mysqld.sock;dbname=pomf', 'xxx', 'xxx', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));

function register ($email, $pass, $code) {
	global $db;
	$do = $db->prepare("SELECT code, used, level FROM invites WHERE email = (:email)");
	$do->bindParam(':email', $email);
	$do->execute();
	$result = $do->fetch();
	if(!$result['code'] == $code){
		header('Location: ../register/index.html#fail2');
	}elseif($result['used'] == '1'){
		header('Location: ../register/index.html#fail3');
	}else{
	$do = $db->prepare("INSERT INTO accounts (email, pass, level) VALUES (:email, :pass, :level)");
	$do->bindParam(':email', $email);
	$do->bindParam(':level', $result['level']);
	$hash = password_hash($pass, PASSWORD_DEFAULT);
	$do->bindParam(':pass', $hash);
	$do->execute();
	$do = $db->prepare("UPDATE invites SET used = (:used) WHERE email = (:email)");
	$do->bindValue(':used', '1');
	$do->bindParam(':email', $email);
	$do->execute();
	$_SESSION['id'] = $result['id'];
	$_SESSION['email'] = $result['email'];
	header('Location: api.php?do=cp');
	}
}

function generate ($email, $level){
	global $db;
	if($_SESSION['level'] === '1'){
		$do = $db->prepare("INSERT INTO invites (email, code, level) VALUES (:email, :code, :level)");
		$do->bindParam(':email', $email);
		$code = generateRandomString();
		$do->bindParam(':code', $code);
		$do->bindParam(':level', $level);
		$do->execute();
		require_once('Mail.php');
		$from = "Invites <invites@pomf.se>";
		$to = $email;
		$subject = "Pomf.se Account Invite";
		$body = "This is a automated message from Pomf.se \n Your invite code is: ".$code."\n Your invite email is: ".$email." \n Access level: ".$level." \n Register at http://cayootie.pomf.se/user/register";

		$host = "xxx";
		$username = "xxx";
		$password = "xxx";

$headers = array ('From' => $from,
   'To' => $to,
   'Subject' => $subject);
 $smtp = Mail::factory('smtp',
   array ('host' => $host,
     'auth' => true,
     'username' => $username,
     'password' => $password));
 
 $mail = $smtp->send($to, $headers, $body);
 
 if (PEAR::isError($mail)) {
   echo("<p>" . $mail->getMessage() . "</p>");
  } else {
   echo("<p>Message successfully sent!</p>");
  }

	}else{
		echo 'What are you doing here? Go away!';
	}

}

function generateRandomString($length = 36) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
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
	if(empty($word)) return;
	global $db;
	$str = "%".$word."%";
	$id = $_SESSION['id'];
	if($_SESSION['level'] === '1'){
	$do = $db->prepare("SELECT orginalname, filename FROM files WHERE orginalname LIKE (:1) OR filename LIKE (:2)");
	$do->bindParam(':1', $str);
	$do->bindParam(':2', $str);
	$do->execute();

	while ($row = $do->fetch(PDO::FETCH_ASSOC)) {
		print strip_tags($row['orginalname']).' - '.'<a href="http://a.pomf.se/'.$row['filename'].'" target="_BLANK">'.$row['filename'].' </a> '.'<a href="http://cayootie.pomf.se/user/includes/api.php?do=delete&f='.$row['filename'].'" target="_BLANK"> Delete</a><br/>';
	}
	
	//Yes I love not being efficient, deal with it.
	}else{
	$do = $db->prepare("SELECT orginalname, filename FROM files WHERE orginalname LIKE (:1) AND user = (:3) OR filename LIKE (:2) AND user = (:3)");
	$do->bindParam(':1', $str);
	$do->bindParam(':2', $str);
	$do->bindParam(':3', $id);
	$do->execute();

	while ($row = $do->fetch(PDO::FETCH_ASSOC)) {
	print strip_tags($row['orginalname']).' - '.'<a href="http://a.pomf.se/'.$row['filename'].'" target="_BLANK">'.$row['filename'].' </a> '.'<a href="http://cayootie.pomf.se/user/includes/api.php?do=delete&f='.$row['filename'].'" target="_BLANK"> Delete</a><br/>';
	}
    }
}

function cfdelete ($file) {

	$butts = array(
	'a' => 'zone_file_purge',
	'tkn' => 'xxxx',
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

function delete ($filename, $deleteid, $mod) {
	if(empty($filename)){
	echo "You did something wrong, baka.";
	}else{
	global $db;
	$do = $db->prepare("SELECT filename, delid, id, user FROM files WHERE filename = (:filename)");
	$do->bindParam(':filename', $filename);
	$do->execute();
	$result = $do->fetch(PDO::FETCH_ASSOC);

	if($_SESSION['level'] === '1' || $result['user'] === $_SESSION['id']){
		$do = $db->prepare("DELETE FROM files WHERE id = (:id)");
		$do->bindParam(':id', $result['id']);
		$do->execute();
		unlink('/home/neku/pomf/files/'.$filename);
		cfdelete($filename);
		echo "<br/>File deleted and hopefully deleted from Cloudflares cache in a moment..<br/>";
	}else{
	echo 'Shame on you';
   }//hue
  }//hue
 }//penis

 function mod ($action, $date, $count, $why, $file, $keyword, $fileid, $hash, $oginalname) {
 	if($_SESSION['level'] > '0'){
 		global $db;
 		switch($action){

 			case "fetch":
 			if($_SESSION['level'] > '0'){
                        $do = $db->prepare("SELECT * FROM files WHERE orginalname LIKE (:keyword) AND date LIKE (:date) OR filename LIKE (:keyword) AND date LIKE (:date) ORDER BY id DESC LIMIT 0,:amount");
                        }else{
                        $do = $db->prepare("SELECT * FROM files WHERE orginalname LIKE (:keyword) AND date LIKE (:date) AND user = (:userid) OR filename LIKE (:keyword) AND date LIKE (:date) AND user = (:userid) ORDER BY id$
                        $do->bindValue(':userid', $_SESSION['id']);}

 			$do->bindValue(':date', "%".$date."%");
 			$do->bindValue(':amount', (int) $count, PDO::PARAM_INT);
 			$do->bindValue(':keyword', "%".$keyword."%");

 				$do->execute();
 				$i = 0;
 				echo'<!DOCTYPE html><html><head><title>Mod</title>
					<style>
					table,th,td{border:1px solid black; border-collapse:collapse;}
					th,td{padding:5px;}
					</style></head><body>
					<p>Keep in mind that this is a alpha version of the mod panel, click <a href="http://cayootie.pomf.se/user/includes/api.php?do=logout">here</a> to logout or <a href="http://cayootie.pomf.se/user/panel" target="_BLANK">here</a> to go to the panel for your personal account.</p>
					<form action="http://cayootie.pomf.se/user/includes/api.php" method="get">
					<input type="hidden" name="do" value="mod">
					<input type="hidden" name="action" value="fetch">
					Date: <input type="text" name="date" value="'.date('Y-m-d').'">
					Amount: <input type="text" name="count" value="30">
					Keyword: <input type="text" name="keyword">
					<input type="submit" value="fetch">
					</form><br>
					<table id="result" style="width:100%">
					<tr><th>ID</th><th>Orginal Name</th><th>Filename</th><th>Size (bytes)</th><th>Action</th></tr>';
 				while ($row = $do->fetch(PDO::FETCH_ASSOC)) {
 					$i++;
 					echo '<tr><td>'.$row['id'].'</td>
 						 <td>'.strip_tags($row['orginalname']).'</td>
 						 <td><a href="http://a.pomf.se/'.$row['filename'].'" target="_BANK">'.$row['filename'].'</a> ('.$row['orginalname'].')</td>
 						 <td>'.$row['size'].'</td>
 						 <td><a href="http://cayootie.pomf.se/user/includes/api.php?do=mod&action=remove&fileid='.$row['id'].'&file='.$row['filename'].'" target="_BANK">Remove</a></td></tr>';

 				}
 				echo '</table></body></html>';
 				echo $i.' Files in total at being shown.';

 			break;

 			case "report":
 				$do = $db->prepare("INSERT INTO reports (hash, date, file, fileid, reporter) VALUES (:hash, :date, :file, :fileid, :reporter)");
 				$do->bindValue(':file', strip_tags($file));
 				$do->bindValue(':date', date('Y-m-d'));
 				$do->bindValue(':reporter', $_SESSION['email']);
 				$do->bindValue(':fileid', $fileid);
 				$do->bindValue(':hash', $hash);
 				$do->execute();
 				echo 'Thank you, report has been sent. The file will be reviewed and probably deleted.';
			break;

			case "reports":
			if($_SESSION['id'] === '1'){
				$do = $db->prepare("SELECT * FROM reports WHERE status = '0'");
				$do->execute();

				$i = 0;
 				echo'<!DOCTYPE html><html><head><title>Mod</title>
					<style>
					table,th,td{border:1px solid black; border-collapse:collapse;}
					th,td{padding:5px;}
					</style></head><body>
					<p> Status 0 = not removed</p>
					<p> Status 1 = removed (not shown)</p>
					<table id="result" style="width:100%">
					<tr><th>ID</th><th>File</th><th>File ID</th><th>Reporter</th><th>Status</th><th>Action</th></tr>';
 				while ($row = $do->fetch(PDO::FETCH_ASSOC)) {
 					$i++;
 					echo '<tr><td>'.$row['id'].'</td>
 						 <td><a href="http://a.pomf.se/'.strip_tags($row['file']).'" target="_BLANK">'.strip_tags($row['file']).'</td>
 						 <td>'.$row['fileid'].'</td>
 						 <td>'.$row['reporter'].'</td>
 						 <td>'.$row['status'].'</td>
 						 <td><a href="http://cayootie.pomf.se/user/includes/api.php?do=mod&action=remove&fileid='.$row['fileid'].'&file='.$row['file'].'" target="_BANK">Remove file</a></td></tr>';

 				}
 				echo '</table></body></html>';
 				echo $i.' Reports in total at being shown.';
			}else{
				echo 'You are not allowed to be here, yet.';
			}
			break;

			case "remove":
			if($_SESSION['id'] < '0'){
                        delete($file, $fileid);}
			if($_SESSION['id'] > '0'){
			$do = $db->prepare("DELETE FROM files WHERE id = (:id)");
			$do->bindParam(':id', $fileid);
			$do->execute();
			unlink('/home/neku/pomf/files/'.$file);
			cfdelete($file);
			$do = $db->prepare("UPDATE reports SET status = (:status) WHERE fileid = (:fileid)");
			$do->bindValue(':status', '1');
			$do->bindValue(':fileid', $fileid);
			$do->execute();
			echo 'Deleted';
			break;
 		}
 	}
 }
?>

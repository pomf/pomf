<?php
//This file is for the Gyazo uploader
function generate_name ($name, $grill) {
        if (!isset($tries)) $tries = 5;
        $fuck = substr($grill, 0, 3);
        do {
                $newname = '';
                $newname .= chr(mt_rand(ord("a"), ord("z")));
                $newname .= $fuck;
                $newname .= chr(mt_rand(ord("a"), ord("z")));
                $newname .= '.png';

                if (--$tries == 0) return false;
        } while (file_exists('/mnt/disk1/pomf/files/'.$newname));

        return $newname;
}
if(isset($_FILES['imagedata']['name']))
{

		//Check if file exists already
		$filehash = sha1_file($_FILES['imagedata']['tmp_name']);
		$con = new PDO('mysql:unix_socket=/var/run/mysqld/mysqld.sock;dbname=XXX', 'XXX', 'XXX');
		$do = $con->prepare("SELECT hash, filename FROM files WHERE hash = (:hash)");
		$do->bindParam(':hash', $filehash);
		$do->execute();
		$result = $do->fetch();
        if($result[0]==$filehash){
        	echo 'http://a.pomf.se/' . $result[1];
        }else{
        $catshit = hash_file('crc32b', $_FILES['imagedata']['tmp_name']);
        $newName = generate_name($_FILES['imagedata']['name'], $catshit);
        $delid = sha1($_FILES['imagedata']['tmp_name']);
		//Insert info into DB
		$do = $con->prepare("INSERT INTO files (hash, orginalname, filename, size, date, expire, delid) VALUES (:hash, :orginname, :filename, :size, :date, :expire, :delid)");
		$do->bindParam(':hash', $filehash);
		$do->bindParam(':orginname', $_FILES['imagedata']['name']);
		$do->bindParam(':filename', $newName);
		$do->bindParam(':size', $_FILES['imagedata']['size']);
		$do->bindParam(':date', date('Y-m-d'));
		$do->bindParam(':expire', $expire);
		$do->bindParam(':delid', $delid);
		$do->execute();
$hurr = '/mnt/disk1/pomf/files/' . $newName;
move_uploaded_file($_FILES['imagedata']['tmp_name'], $hurr);
echo 'http://a.pomf.se/' . $newName;
}
$con = null;
}
?>

<?php
function generate_name ($name, $grill) {
        if (!isset($tries)) $tries = 5;
        $ext = '.' . pathinfo($name, PATHINFO_EXTENSION);
        $fuck = substr($grill, 0, 3);
        do {
                $newname = '';
                $newname .= chr(mt_rand(ord("a"), ord("z")));
                $newname .= $fuck;
                $newname .= chr(mt_rand(ord("a"), ord("z")));
                $newname .= $ext;

                if (--$tries == 0) return false;
        } while (file_exists('/mnt/disk1/pomf/files/'.$newname));

        return $newname;
}


	//Upload and whatnot
	function upload (){
		$static_root = "/mnt/disk1/pomf/files/";
		$out = array( "files" => array(), "success" => true);
		$files = $_FILES["files"];
		$num_files = count($files["name"]);

		for ($i = 0; $i < $num_files; $i++) {
		//Check if file exists already
		$filehash = sha1_file($files["tmp_name"][$i]);
		$con = new PDO('mysql:unix_socket=/var/run/mysqld/mysqld.sock;dbname=XXX', 'XXX', 'XXX');
		$do = $con->prepare("SELECT hash, filename FROM files WHERE hash = (:hash)");
		$do->bindParam(':hash', $filehash);
		$do->execute();
		$result = $do->fetch();
        if($result[0]==$filehash){
        	    $out["files"][] = array(
                        "name" => $files["name"][$i],
                        "url" => $result[1]
                );
        }else{
        $catshit = hash_file('crc32b', $files["tmp_name"][$i]);
        $newname = generate_name($files["name"][$i], $catshit);
        //Generate delid
	$delid = sha1($files["tmp_name"][$i]);
        //Try to upload and other shit
        if ($newname && $files["error"][$i] == 0 && move_uploaded_file($files["tmp_name"][$i], $static_root.$newname)) {
		//Insert info into DB
		$do = $con->prepare("INSERT INTO files (hash, orginalname, filename, size, date, expire, delid) VALUES (:hash, :orginname, :filename, :size, :date, :expire, :delid)");
		$do->bindParam(':hash', $filehash);
		$do->bindParam(':orginname', $files["name"][$i]);
		$do->bindParam(':filename', $newname);
		$do->bindParam(':size', $files["size"][$i]);
		$do->bindParam(':date', date('Y-m-d'));
		$do->bindParam(':expire', $expire);
		$do->bindParam(':delid', $delid);
		$do->execute();
                $out["files"][] = array(
                        "name" => $files["name"][$i],
                        "url" => $newname
                );
        //Otherwise return error
        } else {
                http_response_code(500);
                echo json_encode(array(
                        "success" => false
                ));
        	}
		}
	}	
                $con = null;
		exit(json_encode($out, JSON_PRETTY_PRINT));
}

if(isset($_FILES["files"])){
upload();
}else{
exit("What ya doing here nigga?");
}


?>

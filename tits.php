<?php
function generate_nuck ($name, $tries) {
	if (!isset($tries)) $tries = 5;
	$ext = '.' . pathinfo($name, PATHINFO_EXTENSION);

	do {
		$newname = '';
		for ($i = 4; $i > 0; $i--) {
			$newname .= base_convert(mt_rand(0, 35), 10, 36);
		}
		$newname .= $ext;

		if (--$tries == 0) return false;
	} while (file_exists($newname));

	return $newname;
}

function generate_neku ($name) {
	if (!isset($tries)) $tries = 5;
	$ext = '.' . pathinfo($name, PATHINFO_EXTENSION);

	do {
		$newname = '';
		$newname .= mt_rand(0, 9);
		$newname .= chr(mt_rand(ord("A"), ord("Z")));
		$newname .= chr(mt_rand(ord("a"), ord("z")));
		$newname .= mt_rand(0, 9);
		$newname .= $ext;

		if (--$tries == 0) return false;
	} while (file_exists($newname));

	return $newname;
}
?>
<b>Nuck:</b> dicks.html --&gt; <?=generate_nuck("dicks.html");?><br />
<b>Neku:</b> dicks.html --&gt; <?=generate_neku("dicks.html");?>

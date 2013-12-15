<?php
// This file will act as a APIish thing

// Include core file
require_once('core.php');

// Decode incoming json
$in = json_decode($_POST['in']);

// Perform wanted function
if($in['action']['0']=='register'){

	// Check if reg is open
	if(reg=='1'){

	// Perform register function with data from json string
	register($in['info']['0'], $in['info']['1'], $in['info']['2']);

	}else{
		// Re-direct to closed-reg info page?
		$return = array('action' => 'closedreg');
		json_encode($return);
	}

}elseif ($in['action']['0']=='login') {
	login($in['info']['0'], $in['info']['1']);
}
?>
<?php
include_once 'settings.inc.php';

/* NOTE: we don't have to unref the PDO because we're not long-running */
$db = new PDO(POMF_DB_CONN, POMF_DB_USER, POMF_DB_PASS);
?>

<?php

function conect_db() {

	$user = 'root';
	$passdb = '';
	$db = 'bookmark';
	$connection = new mysqli('localhost', $user, $passdb, $db);
	/*$connection->set_charset("utf8");*/
	return $connection;
}

?>
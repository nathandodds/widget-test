<?php

include '../core/settings/database.php';

include '../core/config/config.php';

mysql_connect( DB_HOST, DB_USER, DB_PASS ) or die ( mysql_error () );
//mysql_query ( 'CREATE DATABASE IF NOT EXISTS `' . $this->_db_name . '`');

mysql_select_db( DB_NAME );

$dump = '../'.DB_NAME.'.sql';

if (file_exists($dump)) {

	$dump_contents = file_get_contents($dump);

	$result = mysql_query("SELECT * FROM pegisis_comments");

	die (var_dump($result));
	
} else {
	die ('File does not exist');
}

?>
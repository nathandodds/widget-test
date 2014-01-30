<?php

$settings['LIVE'] = false;
include($_SERVER['DOCUMENT_ROOT'].'core/settings/database.php');

$mysqli = new mysqli($settings[ 'DB_HOST' ], $settings[ 'DB_USER' ], $settings[ 'DB_PASS' ], $settings[ 'DB_NAME' ]);
 
if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
}
 
echo 'Successfully connected to database... ' . $mysqli->host_info . "\n";
echo 'Retrieving dumpfile' . "\n";
 
$sql = file_get_contents($_SERVER['DOCUMENT_ROOT'].'tests/_data/dump.sql');

if (!$sql){
	die ('Error opening file');
}
 
echo 'processing file'."\n";
mysqli_multi_query($mysqli,$sql);
 
echo 'done.';
$mysqli->close();

?>
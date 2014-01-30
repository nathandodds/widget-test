<?php
define ( 'DR', $_SERVER['DOCUMENT_ROOT'] . '/pegisis/tests/' );

include ( DR . 'core/settings/database.php' );
include ( DR . 'core/settings/site.php' );
include ( DR . 'core/config/config.php' );
include ( DR . 'core/loader/autoloader.php' );

define('DB_SUFFIX', 'pegisis');

error_reporting ( E_ALL );
ini_set ( 'display_errors', 'on' );

?>
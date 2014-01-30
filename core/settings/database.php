<?php
/*
|----------------------------------------------------------------
| Application Database Settings
|----------------------------------------------------------------
|
| These are all the settings that are associated
| to anything to do with the database
|
*/

/**
 * Sets the production or development database settings
 * 
 * @param string database host
 * @param string database name
 * @param string database username
 * @param string database password
 */
if ($settings['LIVE']) {

    $settings[ 'DB_HOST' ] = 'localhost';
    $settings[ 'DB_NAME' ] = 'pegisis';
    $settings[ 'DB_USER' ] = 'root';
    $settings[ 'DB_PASS' ] = 'root';

} else {

    $settings[ 'DB_HOST' ] = 'localhost';
    $settings[ 'DB_NAME' ] = 'pegisis';
    $settings[ 'DB_USER' ] = 'root';
    $settings[ 'DB_PASS' ] = 'root';

}

/**
 * The database suffix - all tables are prepended with the name
 * of the application.
 * 
 * This is optional and can be changed
 */
$settings[ 'DB_SUFFIX' ] = str_replace('/', '', $settings['DIRECTORY']);


/**
 * Controls connection of the database - boolean
 * 
 * If a site is a static site, there is no
 * requirement for a database so this ensures
 * no unneccessary connection is made if false
 */
$settings[ 'USE_DB' ] = TRUE;
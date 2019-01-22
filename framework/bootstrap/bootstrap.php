<?php

/*
|--------------------------------------------------------------------------
| Register The Composer Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so that we do not have to worry about the
| loading of any our classes "manually". Feels great to relax.
|
*/

session_start();
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/config.php';
require_once  __DIR__ . '/../registerFunctions.php';



/**
 * Returns an associative array containing app service objects
 *
 * @return array
 */
function getAppServices()
{
    // Instantiate the View engine core
    $view = new NOMVC\Core\View();

    $dbConn = null;
    if(DB_TYPE == 'PDO')
    {
        // Instantiate the SQLDatabase engine core
        $dbConn = new NOMVC\Core\SQLDatabase();
    }
    elseif(DB_TYPE == 'MongoDB')
    {
        $dbConn = new NOMVC\Core\MongoDatabase();
    }

    return ['view' => $view, 'database' => $dbConn];
}
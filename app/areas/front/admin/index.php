<?php

$view = \NOMVC\Core\View::toView(getAppServices()['view']);
$db = \NOMVC\Core\SQLDatabase::toSQLDatabase(getAppServices()['database'])->db;
$currentView = 'front/admin/index.html.twig';


if(!isset($_SESSION['userid']))
{
    autoRedirectTo(AUTO_SITE_HOME_URL . '/login');
}

if(isset($_GET['logout']))
{
    unset($_SESSION['userid']);
    unset($_SESSION['admin']);
    autoRedirectTo(AUTO_SITE_HOME_URL . '/login');
}
else
{
    return $view->render($currentView);
}




<?php

$view = \NOMVC\Core\View::toView(getAppServices()['view']);
$db = \NOMVC\Core\SQLDatabase::toSQLDatabase(getAppServices()['database'])->db;
$currentView = 'front/admin/blog/new.html.twig';

if($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    // Show the View
    if(!isset($_SESSION['userid']) || empty($_SESSION['userid']))
    {
        // Error, user must be logged in to use this feature
        autoRedirectTo(AUTO_SITE_HOME_URL . '/login');
    }

    return $view->render($currentView);
}
else
{
    // Request is POST
    if(!isset($_SESSION['userid']) || empty($_SESSION['userid']))
    {
        // Error, user must be logged in to use this feature
        autoRedirectTo(AUTO_SITE_HOME_URL . '/login');
    }

    $user_id = $_SESSION['userid'];

    $title = stripslashes(strip_tags($_POST['title']));
    $content = $_POST['content'];
    $status = isset($_POST['status']) ? $_POST['status'] : 'draft';

    $edt = new EasyDateTime('UTC', 'gregorian');

    try
    {
        $stmt = $db->prepare("INSERT INTO posts (title, content, status, created_at, updated_at)
         VALUES (?, ?, ?, ?, ?)");
        $res = $stmt->execute([$title, $content, $status, $edt->date(), $edt->date()]);

        $id = $db->lastInsertId();

        autoRedirectTo(SITE_HOME_URL . "/admin/edit/$id?submit_success=true");
    }
    catch (\Exception $e)
    {
        die('failed ' . $e);
    }
}
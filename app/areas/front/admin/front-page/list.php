<?php

$view = \NOMVC\Core\View::toView(getAppServices()['view']);
$db = \NOMVC\Core\SQLDatabase::toSQLDatabase(getAppServices()['database'])->db;
$currentView = 'front/admin/front-page/list.html.twig';

if(!isset($_SESSION['userid']))
{
    autoRedirectTo(AUTO_SITE_HOME_URL . '/login?redirect=' . $_SERVER['REQUEST_URI']);
}

if(!isset($_page) || !isset($_section))
{
    dieWithHttpNotFound();
}
else
{
    $page = strip_tags(stripslashes($_page));
    $section = strip_tags(stripslashes($_section));
}

$stmt = $db->prepare("SELECT * FROM frontpage WHERE page = ? AND section = ? ORDER BY id");
$stmt->execute([$page, $section]);
$items = $stmt->fetchAll();


return $view->render($currentView, ['page' => $page, 'section' => $section, 'items' => $items]);

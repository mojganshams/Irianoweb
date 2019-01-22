<?php

$view = \NOMVC\Core\View::toView(getAppServices()['view']);
$db = \NOMVC\Core\SQLDatabase::toSQLDatabase(getAppServices()['database'])->db;
$currentView = 'front/home/about.html.twig';

$stmt = $db->prepare("SELECT * FROM frontpage WHERE page = ?");
$stmt->execute(['about']);
$about = $stmt->fetch();


return $view->render($currentView, ['about' => $about]);

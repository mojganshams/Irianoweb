<?php

$view = \NOMVC\Core\View::toView(getAppServices()['view']);
$db = \NOMVC\Core\SQLDatabase::toSQLDatabase(getAppServices()['database'])->db;
$currentView = 'front/home/index.html.twig';

$stmt = $db->prepare("SELECT * FROM frontpage WHERE page = ?");
$stmt->execute(['home']);
$contents = $stmt->fetchAll();
$header = array();
$hexagon = array();
$services = array();
$description = array();
$contact = array();

foreach ($contents as $content)
{
    if($content['section'] == 'header')
    {
        $header[] = $content;
    }
    if($content['section'] == 'hexagon')
    {
        $hexagon[] = $content;
    }
    if($content['section'] == 'services')
    {
        $services[] = $content;
    }
    if($content['section'] == 'description')
    {
        $description[] = $content;
    }
    if($content['section'] == 'contact')
    {
        $contact[] = $content;
    }
}

/*echo "<pre>";
print_r($services);
echo "</pre>";
die();*/


return $view->render($currentView, ['header' => $header, 'hexagon' => $hexagon, 'services' => $services,
    'description' => $description, 'contact' => $contact]);
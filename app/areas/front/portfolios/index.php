<?php

$view = \NOMVC\Core\View::toView(getAppServices()['view']);
$db = \NOMVC\Core\SQLDatabase::toSQLDatabase(getAppServices()['database'])->db;
$currentView = 'front/portfolios/index.html.twig';

if(isGetRequest())
{

    $websites = array();
    $posters = array();
    $catalogs = array();
    $banners = array();
    $intervals = array();
    $titles = array();

    $stmt = $db->prepare("SELECT * FROM frontpage WHERE page = ? ORDER BY id");
    $stmt->execute(['portfolios']);
    $portfolios = $stmt->fetchAll();
    
    foreach ($portfolios as $portfolio)
    {
        if($portfolio['section'] == 'websites')
        {
            $websites[] = $portfolio;
        }
        if($portfolio['section'] == 'posters')
        {
            $posters[] = $portfolio;
        }
        if($portfolio['section'] == 'catalogs')
        {
            $catalogs[] = $portfolio;
        }
        if($portfolio['section'] == 'banners')
        {
            $banners[] = $portfolio;
        }
        if($portfolio['section'] == 'intervals')
        {
            $intervals[] = $portfolio;
        }
        if($portfolio['section'] == 'titles')
        {
            $titles[] = $portfolio;
        }
    }
    
    return $view->render($currentView, ['websites' => $websites, 'posters' => $posters, 'catalogs' => $catalogs,
        'banners' => $banners, 'intervals' => $intervals, 'titles' => $titles]);
}


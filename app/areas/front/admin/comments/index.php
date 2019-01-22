<?php

$view = \NOMVC\Core\View::toView(getAppServices()['view']);
$db = \NOMVC\Core\SQLDatabase::toSQLDatabase(getAppServices()['database'])->db;
$currentView = 'front/admin/comments/index.html.twig';

$per_page = isset($_num_perpage) ? $_num_perpage : 4;
$skip = isset($_GET['page']) ? ($_GET['page'] - 1) * $per_page : (0) * $per_page;

if(!isset($_SESSION['userid']))
{
    autoRedirectTo(AUTO_SITE_HOME_URL . '/login');
}

$stmt = $db->prepare("SELECT comments.*, UNIX_TIMESTAMP(CONVERT_TZ(comments.created_at, 'GMT', 'Asia/Tehran')) AS 'created_at' FROM 
comments ORDER BY comments.created_at DESC LIMIT ?, ?");
$stmt->execute([$skip, $per_page]);

$comments = $stmt->fetchAll();

$stmt = $db->prepare("SELECT COUNT(*) AS 'count' FROM comments");
$stmt->execute();

$totalContentCount = $stmt->fetch()['count'];
$totalPages = ceil($totalContentCount / $per_page);

return $view->render($currentView, ['comments' => $comments, 'total_pages' => $totalPages]);
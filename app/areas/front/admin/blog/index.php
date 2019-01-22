<?php

$view = \NOMVC\Core\View::toView(getAppServices()['view']);
$db = \NOMVC\Core\SQLDatabase::toSQLDatabase(getAppServices()['database'])->db;
$currentView = 'front/admin/blog/index.html.twig';

$per_page = isset($_num_perpage) ? $_num_perpage : 4;
$skip = isset($_GET['page']) ? ($_GET['page'] - 1) * $per_page : (0) * $per_page;

if(!isset($_SESSION['userid']))
{
    autoRedirectTo(AUTO_SITE_HOME_URL . '/login');
}

$stmt = $db->prepare("SELECT posts.*, UNIX_TIMESTAMP(CONVERT_TZ(posts.created_at, 'GMT', 'Asia/Tehran')) AS 'created_at'
 FROM posts ORDER BY posts.created_at DESC LIMIT ?, ?");
$stmt->execute([$skip, $per_page]);
$posts = $stmt->fetchAll();


$stmt = $db->prepare("SELECT COUNT(*) AS 'count' FROM posts");
$stmt->execute();

$totalContentCount = $stmt->fetch()['count'];
$totalPages = ceil($totalContentCount / $per_page);

return $view->render($currentView, ['posts' => $posts, 'total_pages' => $totalPages]);
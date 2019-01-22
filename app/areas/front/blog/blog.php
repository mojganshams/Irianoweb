<?php
/*$title = "وبلاگ";
require_once("../models/initialize.php");

// 1. the current page number ($current_page)
$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;

// 2. records per page ($per_page)
$per_page = 5;

// 3. total record count ($total_count)
$total_count = Post::count_all();


// Find all photos
// use pagination instead
//$photos = Photograph::find_all();

$pagination = new Pagination($page, $per_page, $total_count);

// Instead of finding all records, just find the records
// for this page
$sql = "SELECT * FROM posts ";
$sql .= "LIMIT {$per_page} ";
$sql .= "OFFSET {$pagination->offset()}";
$posts = Post::find_by_sql($sql);

// Need to add ?page=$page to all links we want to
// maintain the current page (or store $page in $session)

require_once "../templates/site.header.tpl.php";
require_once "../templates/site.navbar.tpl.php";
require_once "../templates/site.blogheader.tp.php";*/

//require_once(AUTO_SITE_HOME_URL . "../app/models/initialize.php");

$upload_dir = AUTO_SITE_HOME_URL . '/img';

$view = \NOMVC\Core\View::toView(getAppServices()['view']);
$db = \NOMVC\Core\SQLDatabase::toSQLDatabase(getAppServices()['database'])->db;

$currentView = 'front/blog/blog.html.twig';

$per_page = isset($_num_perpage) ? $_num_perpage : 4;
$skip = isset($_GET['page']) ? ($_GET['page'] - 1) * $per_page : (0) * $per_page;

/*$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;*/

$stmt = $db->prepare("SELECT * FROM frontpage WHERE page = ?");
$stmt->execute(['blog']);
$blog = $stmt->fetch();


$stmt = $db->prepare("SELECT posts.*, UNIX_TIMESTAMP(CONVERT_TZ(created_at, 'GMT', 'Asia/Tehran')) AS 'created_at' FROM posts WHERE status = 'published' ORDER BY posts.created_at DESC LIMIT ?, ?");
$stmt->execute([$skip, $per_page]);
$posts = $stmt->fetchAll();

$stmt = $db->prepare("SELECT COUNT(*) AS 'count' FROM posts");
$stmt->execute();

$totalContentCount = $stmt->fetch()['count'];
$totalPages = ceil($totalContentCount / $per_page);

return $view->render($currentView, ['posts' => $posts,'blog' => $blog, 'total_pages' => $totalPages]);
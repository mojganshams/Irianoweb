<?php

$view = \NOMVC\Core\View::toView(getAppServices()['view']);
$db = \NOMVC\Core\SQLDatabase::toSQLDatabase(getAppServices()['database'])->db;
$currentView = 'front/blog/post.html.twig';

$id = isset($_id) ? $_id : null;
$post_title = isset($_title) ? $_title : null;
$errors = Array();

if($id == null || 0)
{
    // error
    dieWithHttpNotFound();
}

$stmt = $db->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

$stmt = $db->prepare("SELECT comments.*, UNIX_TIMESTAMP(CONVERT_TZ(comments.created_at, 'GMT', 'Asia/Tehran')) AS 'created_at' FROM comments 
WHERE post_id = ?");
$stmt->execute([$id]);
$comments = $stmt->fetchAll();

if(isGetRequest())
{
    if($post['status'] !== 'published')
    {
        dieWithHttpNotFound();
    }


    return $view->render($currentView, ['post' => $post, 'comments' => $comments]);
}
else
{
    if(isset($_POST['submit']) /*&& isset($_SESSION['loggedin'])*/)
    {
        if(!isset($_POST['author']) || !isset($_POST['email']) || $_POST['body'] == '')
        {
            // error
            $errors[] = "لطفا فیلد های خواسته شده را تکمیل نمایید.";
            return $view->render($currentView, ['post' => $post, 'comments' => $comments, 'errors' => $errors]);
        }

        $author = $_POST['author'];
        $body = $_POST['body'];
        $email = trim($_POST['email']);
        $post_id = $id;
        $edt = new EasyDateTime('UTC', 'gregorian');

        $stmt = $db->prepare("INSERT INTO comments (post_id, created_at, author, body, email) VALUES (?, ?, ?, ?, ?)");
        $res = $stmt->execute([$id, $edt->date(), $author, strip_tags($body), $email]);

        if($res)
        {
            autoRedirectTo(AUTO_SITE_HOME_URL . '/blog/' . $id . '/?comment_submit=success');
        }
        autoRedirectTo(AUTO_SITE_HOME_URL . '/blog/' . $id . '/?comment_submit=failure');
    }
}
<?php

$view = \NOMVC\Core\View::toView(getAppServices()['view']);
$db = \NOMVC\Core\SQLDatabase::toSQLDatabase(getAppServices()['database'])->db;
$currentView = 'front/admin/comments/edit.html.twig';

if(!isset($_SESSION['userid']))
{
    dieWithHttpUnauthorized();
}

if($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    if(!isset($_id) || !is_numeric($_id))
    {
        dieWithHttpNotFound();
    }
    else
    {
        $id = strip_tags(stripslashes($_id));
    }

    $stmt = $db->prepare("SELECT comments.*, CONVERT_TZ(created_at, 'GMT', 'Asia/Tehran') AS 'created_at',
 (SELECT title FROM posts WHERE comments.post_id = posts.id) AS 'post' FROM comments WHERE comments.id = ?");
    $stmt->execute([$id]);

    $comment = $stmt->fetch();

    if(!isset($comment) || $comment == null || $comment === false)
    {
        dieWithHttpNotFound();
    }

    return $view->render($currentView, ['comment' => $comment]);
}
else
{
    // Request method is POST

    $error = false;

    if(!isset($_id) || !is_numeric($_id))
    {
        dieWithHttpNotFound();
    }
    else
    {
        $id = strip_tags(stripslashes($_id));
    }

    $stmt = $db->prepare("SELECT * FROM comments WHERE id = ?");
    $stmt->execute([$id]);

    $comment = $stmt->fetch();

    if(!isset($comment) || $comment == null || $comment === false)
    {
        dieWithHttpNotFound();
    }


    if(isset($_POST['delete']))
    {
        $stmt = $db->prepare("DELETE FROM comments WHERE id = ?");
        $res = $stmt->execute([$id]);

        if($res)
        {
            autoRedirectTo(AUTO_SITE_HOME_URL . '/admin/comments/?delete=success');
        }
        autoRedirectTo(AUTO_SITE_HOME_URL . $_SERVER['REQUEST_URI'] . '?delete=failure');
    }
    elseif(isset($_POST['edit']))
    {
        $text = isset($_POST['content']) ? $_POST['content'] : null;
        $status = isset($_POST['status']) ? $_POST['status'] : null;

        $stmt = $db->prepare("UPDATE comments SET body = ?, status = ? WHERE id = ?");
        $res = $stmt->execute([$text, $status, $id]);

        if(isset($res) && $res)
        {
            // Successful
            autoRedirectTo(SITE_HOME_URL . "/admin/comments/$id?edit_success=true");
        }
        else
        {
            $system_msg = <<<MSG
                <div class="ui negative icon message">
                  <i class="remove icon"></i>
                  <div class="content">
                    <div class="header">
                      <span>متاسفانه مشکلی به وجود آمد.</span>
                    </div>
                    <p>لطفا دوباره تلاش کنید یا در صورت بروز دوباره مشکل، به ما اطلاع دهید.</p>
                  </div>
                </div>
MSG;

            $stmt = $db->prepare("SELECT * FROM comments WHERE id = ?");
            $stmt->execute([$id]);

            $comment = $stmt->fetch();

            return $view->render($currentView, ['comment' => $comment, 'system_msg' => $system_msg]);
        }
    }
    else
    {
        // Unknown request
        dieWithHttpNotFound();
    }
}

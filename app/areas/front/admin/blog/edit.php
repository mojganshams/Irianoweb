<?php

$view = \NOMVC\Core\View::toView(getAppServices()['view']);
$db = \NOMVC\Core\SQLDatabase::toSQLDatabase(getAppServices()['database'])->db;
$currentView = 'front/admin/blog/edit.html.twig';

if($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    // Show the View

    if(!isset($_SESSION['userid']))
    {
        autoRedirectTo(AUTO_SITE_HOME_URL . '/login?redirect=' . $_SERVER['REQUEST_URI']);
    }

    if(!isset($_id) || !is_numeric($_id))
    {
        dieWithHttpNotFound();
    }
    else
    {
        $id = strip_tags(stripslashes($_id));
    }

    if(isset($_GET['delete']))
    {
        $stmt = $db->prepare("DELETE FROM posts WHERE id = ?");
        $res = $stmt->execute([$id]);

        if($res)
        {
            autoRedirectTo(SITE_HOME_URL . '/admin/blog/?delete=success');
        }
        autoRedirectTo(SITE_HOME_URL . $_SERVER['REQUEST_URI'] . '?delete=failure');
    }

    $stmt = $db->prepare("SELECT posts.* FROM posts WHERE id = ?");
    $stmt->execute([$id]);

    $post = $stmt->fetch();


    if(!isset($post) || $post == null || $post === false)
    {
        dieWithHttpNotFound();
    }

    // Collect the comments

    $stmt = $db->prepare("SELECT comments.*, UNIX_TIMESTAMP(CONVERT_TZ(comments.created_at, 'GMT', 'Asia/Tehran')) AS 'created_at' FROM comments 
WHERE comments.post_id = ?");
    $stmt->execute([$id]);

    $comments = $stmt->fetchAll();

    return $view->render($currentView, ['post' => $post, 'comments' => $comments]);
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


    $stmt = $db->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);

    $post = $stmt->fetch();

    if(!isset($post) || $post == null || $post === false)
    {
        dieWithHttpNotFound();
    }

    $user_id = $_SESSION['userid'];

    $edt = new EasyDateTime('UTC', 'gregorian');

    if(isset($_POST['delete']))
    {
        $stmt = $db->prepare("DELETE FROM posts WHERE id = ?");
        $res = $stmt->execute([$id]);

        if($res)
        {
            autoRedirectTo(SITE_HOME_URL . '/admin/blog/?delete=success');
        }
        autoRedirectTo(SITE_HOME_URL . $_SERVER['REQUEST_URI'] . '?delete=failure');
    }
    elseif(isset($_POST['edit']) || isset($_POST['remove_thumbnail']))
    {
        $title = stripslashes(strip_tags($_POST['title']));
        $content = $_POST['content'];
        $status = isset($_POST['status']) ? $_POST['status'] : null;

        $shouldKeepLatestUpdateDate = isset($_POST['keep-latest-update-time']) ? true : false;
        $updated_at = $edt->date();
        if($shouldKeepLatestUpdateDate)
        {
            $updated_at = $post['updated_at'];
        }

//        $tags = '';
//        if(isset($_POST['tags']))
//        {
//            foreach ($_POST['tags'] as $tag)
//            {
//                $tags = $tags . ',' . stripslashes(strip_tags($tag));
//            }
//        }
//        $tags = mb_substr($tags, 1, mb_strlen($tags, 'utf-8') - 1, 'utf-8');

        $thumbnail = false;
        $removeThumbnail = false;

        if(!file_exists($_FILES['thumbnail']['tmp_name']) || !is_uploaded_file($_FILES['thumbnail']['tmp_name']))
        {
            if(isset($_POST['remove_thumbnail']) && $_POST['remove_thumbnail'])
            {
                $removeThumbnail = true;
            }
            goto SKIP_UPLOAD;
        }
        $thumbnail = true;
        $storage = new \Upload\Storage\FileSystem('img/posts');
        // \Upload\File($postedFileInputName, \Upload\Storage\FileSystem $storage)
        $file = new \Upload\File('thumbnail', $storage);
        // Rename the file on upload
        $new_filename = uniqid() . time();
        $file->setName($new_filename);
        // Validate file upload
        // MimeType List => http://www.iana.org/assignments/media-types/media-types.xhtml
        $file->addValidations([
            new Upload\Validation\Extension(['png', 'jpg']),
            // Ensure file is no larger than 5M (use "B", "K", M", or "G")
            new \Upload\Validation\Size('10M')
        ]);
        // Access data about the file that has been uploaded
        $data = array(
            'name'       => $file->getNameWithExtension(),
            'extension'  => $file->getExtension(),
            'mime'       => $file->getMimetype(),
            'size'       => $file->getSize(),
        );

        // Try to upload file
        try
        {
            // Success!
            $file->upload();
        }
        catch (\Exception $e)
        {
            // Fail!
            $errors = $file->getErrors();
            die('مشکل سیستمی. لطفا با مدیر تماس بگیرید.');
        }

        SKIP_UPLOAD:
        if($thumbnail !== true)
        {
            $remove_thumbnail_query = "";
            if($removeThumbnail)
            {
                // Remove the thumbnail
                $remove_thumbnail_query = "filename = null,";
            }
            $stmt = $db->prepare("UPDATE posts SET title = ?, content = ?, $remove_thumbnail_query status = ?, updated_at = ? WHERE id = ?");
            $res = $stmt->execute([$title, $content, $status, $updated_at, $id]);
        }
        else
        {
            $stmt = $db->prepare("UPDATE posts SET title = ?, content = ?, filename = ?, status = ?, updated_at = ? WHERE id = ?");
            $res = $stmt->execute([$title, $content, ($thumbnail ? $data['name'] : null), $status, $updated_at, $id]);
        }

        END:
        if(isset($res) && $res)
        {
            // Successful
            autoRedirectTo(SITE_HOME_URL . "/admin/edit/$id?edit_success=true");
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


            return $view->render($currentView, ['post' => $post, 'system_msg' => $system_msg]);
        }
    }
    else
    {
        // Unknown request
        dieWithHttpNotFound();
    }
}

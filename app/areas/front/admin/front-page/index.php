<?php

$view = \NOMVC\Core\View::toView(getAppServices()['view']);
$db = \NOMVC\Core\SQLDatabase::toSQLDatabase(getAppServices()['database'])->db;
$currentView = 'front/admin/front-page/index.html.twig';

if(isGetRequest())
{
    
    if(!isset($_SESSION['userid']))
    {
        autoRedirectTo(AUTO_SITE_HOME_URL . '/login?redirect=' . $_SERVER['REQUEST_URI']);
    }
    
    return $view->render($currentView);
}
else
{
    $title = stripslashes(strip_tags($_POST['title']));
    $content = stripslashes(strip_tags($_POST['content']));
    $page = stripslashes(strip_tags($_POST['page']));
    $section = stripslashes(strip_tags($_POST['section']));
    $filename = stripslashes(strip_tags($_POST['filename']));

    if(!file_exists($_FILES['thumbnail']['tmp_name']) || !is_uploaded_file($_FILES['thumbnail']['tmp_name']))
    {
        if(isset($_POST['remove_thumbnail']) && $_POST['remove_thumbnail'])
        {
            $removeThumbnail = true;
        }
        goto SKIP_UPLOAD;
    }

    $name = preg_replace('/(.+)\.\w+$/U', '$1', $_FILES['thumbnail']['name']);
    $thumbnail = true;
    if(isset($_GET['portfolios']))
    {
        $storage = new \Upload\Storage\FileSystem('img/portfolios');
    }
    else
    {
        $storage = new \Upload\Storage\FileSystem('img/front-page');
    }

    // \Upload\File($postedFileInputName, \Upload\Storage\FileSystem $storage)
    $file = new \Upload\File('thumbnail', $storage);
    // Rename the file on upload
    $new_filename = !empty($filename) ? $filename : $name;
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

    $data['name'] = !empty($filename) ? $filename . '.' . $data['extension'] : $data['name'];

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

    try
    {
        $stmt = $db->prepare("INSERT INTO frontpage (page, section, title, content, filename)
         VALUES (?, ?, ?, ?, ?)");
        $res = $stmt->execute([$page, $section, $title, $content, $data['name']]);

        $id = $db->lastInsertId();

        if(isset($_GET['portfolios']))
        {
            autoRedirectTo(SITE_HOME_URL . "/admin/front-page?submit_success=true&id=$id&portfolios=true");
        }
        else
        {
            autoRedirectTo(SITE_HOME_URL . "/admin/front-page?submit_success=true&id=$id");
        }


    }
    catch (\Exception $e)
    {
        die('failed ' . $e);
    }
}
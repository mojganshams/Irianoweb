<?php

$view = \NOMVC\Core\View::toView(getAppServices()['view']);
$db = \NOMVC\Core\SQLDatabase::toSQLDatabase(getAppServices()['database'])->db;
$currentView = 'front/admin/front-page/edit.html.twig';

if(!isset($_SESSION['userid']))
{
    autoRedirectTo(AUTO_SITE_HOME_URL . '/login?redirect=' . $_SERVER['REQUEST_URI']);
}

if(isGetRequest())
{
    if(!isset($_id) || !is_numeric($_id))
    {
        dieWithHttpNotFound();
    }
    else
    {
        $page = isset($_page) ? strip_tags(stripslashes($_page)) : null;
        $section = isset($_section) ? strip_tags(stripslashes($_section)) : null;
        $id = strip_tags(stripslashes($_id));
    }

    $stmt = $db->prepare("SELECT * FROM frontpage WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();

    switch ($section)
    {
        case 'header':
            $ratio = '3:2';
            break;
        case 'hexagon':
            $ratio = '1:1';
            break;
        case 'services':
            $ratio = '1:1';
            break;
        case 'description':
            $ratio = '880x700';
            break;
        case 'contact':
            $ratio = '3:2';
            break;
        case 'about':
            $ratio = '3:2';
            break;
        case 'websites':
            $ratio = '4:3';
            break;
        case 'posters':
            $ratio = '3:4';
            break;
        case 'catalogs':
            $ratio = '3:4';
            break;
        case 'banners':
            $ratio = '4:3';
            break;
    }



    return $view->render($currentView, ['page' => $page, 'section' => $section, 'item' => $item, 'ratio' => $ratio]);
}
else
{
    $errors = [];

    if(!isset($_id) || !is_numeric($_id))
    {
        dieWithHttpNotFound();
    }
    else
    {
        $id = strip_tags(stripslashes($_id));
    }
    
    if(empty($_POST['page']))
    {
        $errors[] = 'فیلد مربوط به صفحه نمی تواند خالی باشد.';
    }
    if(empty($_POST['section']))
    {
        $errors[] = 'فیلد مربوط به بخش نمی تواند خالی باشد.';
    }
    if(empty($_POST['title']))
    {
        $errors[] = 'فیلد مربوط به عنوان نمی تواند خالی باشد.';
    }

    $stmt = $db->prepare("SELECT * FROM frontpage WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();



    $title = stripslashes(strip_tags($_POST['title']));
    $content = stripslashes(strip_tags($_POST['content']));
    $page = stripslashes(strip_tags($_POST['page']));
    $section = stripslashes(strip_tags($_POST['section']));
    $filename = stripslashes(strip_tags($_POST['filename']));

    if(!file_exists($_FILES['thumbnail']['tmp_name']) || !is_uploaded_file($_FILES['thumbnail']['tmp_name']))
    {
        if(isset($_POST['remove_thumbnail']) && $_POST['remove_thumbnail'])
        {
            $stmt = $db->prepare("UPDATE frontpage SET filename = NULL WHERE id = ?");
            $res = $stmt->execute([$id]);
            if($res)
            {
                autoRedirectTo(SITE_HOME_URL . "/admin/front-page/list/$page/$section/$id?submit_success=true");
            }
            else
            {
                $errors[] = 'خطایی هنگام حذف تصویر ایجاد شد.';
            }

        }
        goto SKIP_UPLOAD;
    }

    $name = preg_replace('/(.+)\.\w+$/U', '$1', $_FILES['thumbnail']['name']);
    $thumbnail = true;
    if($page == 'portfolios')
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
        $stmt = $db->prepare("UPDATE frontpage SET page = ?, section = ?, title = ?, content = ?, filename = ? WHERE 
        id = ?");
        $res = $stmt->execute([$page, $section, $title, $content, $data['name'], $id]);

        if(!empty($errors))
        {
            return $view->render($currentView, ['errors' => $errors, 'success' => false, 'item' => $item]);
        }
        else
        {
            autoRedirectTo(SITE_HOME_URL . "/admin/front-page/list/$page/$section/$id?submit_success=true");
        }
        


        /*autoRedirectTo(SITE_HOME_URL . "/admin/front-page/list/$page/$section?submit_success=true&id=$id");*/


    }
    catch (\Exception $e)
    {
        die('failed ' . $e);
    }
}
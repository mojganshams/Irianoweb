<?php

$view = \NOMVC\Core\View::toView(getAppServices()['view']);
$db = \NOMVC\Core\SQLDatabase::toSQLDatabase(getAppServices()['database'])->db;
$currentView = 'front/admin/users/new.html.twig';

if(isGetRequest())
{
    if(!isset($_SESSION['userid']))
    {
        // Error, user must be logged in to use this feature
        autoRedirectTo(AUTO_SITE_HOME_URL . '/login?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    }

    return $view->render($currentView);
}
else
{

    $firstname = stripslashes(strip_tags($_POST['firstname']));
    $lastname = stripslashes(strip_tags($_POST['lastname']));
    $username = stripslashes(strip_tags($_POST['username']));
    $email = mb_strtolower(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL), 'UTF-8');


    $stmt = $db->prepare("SELECT COUNT(*) AS total FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if($stmt->fetch()['total'] > 0)
    {
        // Error, A user with the same Email/Username already exists
        $msg = ['type' => 'failure', 'text' => 'ایمیل قبلا در سیستم ثبت شده است.'];
        return $view->render($currentView, ['msg' => $msg]);
    }

    $stmt = $db->prepare("SELECT COUNT(*) AS total FROM users WHERE username = ?");
    $stmt->execute([$username]);

    if($stmt->fetch()['total'] > 0)
    {
        // Error, A user with the same Email/Username already exists
        $msg = ['type' => 'failure', 'text' => 'نام کاربری قبلا در سیستم ثبت شده است.'];
        return $view->render($currentView, ['msg' => $msg]);
    }

    $password = stripslashes(strip_tags($_POST['password']));
    $password_repeat = stripslashes(strip_tags($_POST['password-repeat']));

    if($password !== $password_repeat)
    {
//                die('passwords not the same');
        // Error, Passwords must match
        $msg = ['type' => 'failure', 'text' => 'کلمه ی عبور و تکرار آن باید دقیقا یکسان باشند'];
        return $view->render($currentView, ['msg' => $msg]);
    }

    if(strlen($password) < 6 || strlen($password) > 16)
    {
        // Error, Password must be between 6 and 16 characters
        $msg = ['type' => 'failure', 'text' => 'کلمه عبور باید بین ۶ تا ۱۶ کاراکتر باشد.'];
        return $view->render($currentView, ['msg' => $msg]);
    }

    $password = \NOMVC\Utils\PasswordV1::passwordHash($password);

    $pictureName = null;

    // Image Upload
    if(empty($_POST['avatar']) || $_POST['avatar'] === '') {
        goto NO_IMAGE;
    }

    $picturesPath = realpath('../wwwroot/img/avatars') .  '/';
    $input = $_POST['avatar'];
    $imageData = base64_decode(getBase64WithoutHeader($input));
    $finfo = finfo_open();
    $mime_type = finfo_buffer($finfo, $imageData, FILEINFO_MIME_TYPE);
    $split = explode( '/', $mime_type );
    $extension = $split[1];
    $pictureName = generateRandomToken() . '.' . $extension;
    file_put_contents($picturesPath . $pictureName, $imageData);
    $user['avatar'] = $pictureName;
    /*$stmt = $db->prepare("UPDATE users SET avatar = ? WHERE id = ?");
    $res = $stmt->execute([$pictureName, $id]);*/


    NO_IMAGE:

    $edt = new EasyDateTime('UTC', 'gregorian');
    // Sign Up
    $stmt = $db->prepare("INSERT INTO users (first_name, last_name, username, password, email, created_at, updated_at, avatar) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    try
    {
        $res = $stmt->execute([$firstname, $lastname, $username, $password, $email, $edt->date(), $edt->date(), $pictureName]);

        if($res)
        {
            // success
            autoRedirectTo(AUTO_SITE_HOME_URL . "/admin/users?create_user=success");
        }
        else
        {
            // failure
            $msg = ['type' => 'failure', 'text' => 'خطایی نامشخص رخ داد. لطفا با مدیریت سیستم تماس بگیرید.'];
            return $view->render($currentView, ['msg' => $msg]);
        }
    }
    catch (Exception $e)
    {
//            die($e->getMessage());
        $msg = ['type' => 'failure', 'text' => 'خطایی نامشخص رخ داد. لطفا با مدیریت سیستم تماس بگیرید.'];
        return $view->render($currentView, ['msg' => $msg]);
    }
}
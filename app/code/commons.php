<?php

function generateToken()
{
    return bin2hex(random_bytes(32));
}

function autoRedirectTo($location)
{
    if( $location != null ) {
        ob_end_clean();
        header("Location: {$location}");
        exit;
    }
    return;
}

function dieWithHttpNotFound()
{
    header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    require_once getAppRoot(true) . AREAS_DIR . '/common/404.php';
    exit;
}

function dieWithHttpUnauthorized()
{
    header($_SERVER["SERVER_PROTOCOL"] . ' 401 Unauthorized');
    require_once getAppRoot(true) . AREAS_DIR . '/common/401.php';
    exit;
}

function dieWithContentLocked()
{
    require_once getAppRoot(true) . AREAS_DIR . '/common/locked_content.php';
    exit;
}

function clearUserLogin()
{
    unset($_SESSION['loggedin']);
    unset($_SESSION['userid']);
    unset($_SESSION['username']);
    unset($_SESSION['fullname']);
    unset($_SESSION['admin']);

    if (isset($_COOKIE['login_token_pt1']))
    {
        unset($_COOKIE['login_token_pt1']);
        setcookie('login_token_pt1', '', time() - 3600, '/'); // empty value and old timestamp
    }
    if (isset($_COOKIE['login_token_pt2']))
    {
        unset($_COOKIE['login_token_pt2']);
        setcookie('login_token_pt2', '', time() - 3600, '/'); // empty value and old timestamp
    }
}

function translateToPersian($str)
{
    switch ($str)
    {
        case 'pamphlet':
            return 'جزوه';
        case 'book':
            return 'کتاب';
        case 'paper':
            return 'امتحانی';
        case 'project':
            return 'پروژه';
        case 'draft':
            return 'پیش نویس';
        case 'published':
            return 'منتشر شده';
        case 'readonly':
            return 'قفل شده';
        case 'trash':
            return 'زباله';
        case 'sent':
            return 'ارسال شده';
        case 'read':
            return 'بررسی شده';
    }
}

function isHttps()
{
    if(CLOUD_FLARE)
    {
        if(!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https')
        {
            return true;
        }
    }
    else
    {
        if(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] || ($_SERVER['HTTPS'] == 'on')))
        {
            return true;
        }
    }
    return false;
}

/**
 * Print a more web-friendly print_r() output.
 *
 * @param mixed $content
 * @param bool $return
 * @return mixed
 */
function print_rr($content, $return=false)
{
    $output = '<html><head><meta charset="utf-8" /></head><div style="border: 1px solid; resize: both; overflow: auto;"><pre>'
        . print_r($content, true) . '</pre></div></html>';

    if ($return) {
        return $output;
    } else {
        echo $output;
    }
}

/**
 * Returns an array containing user activity info
 *
 * @param $db
 * @return array | null
 */
function getUserActivityInfo($db)
{
    if(!isset($_SESSION['loggedin']) || !isset($_SESSION['userid']))
    {
        return null;
    }
    $query = <<<QUE
        SELECT
            (SELECT COUNT(*) FROM comments WHERE author = ?) AS 'comments_count',
            (SELECT COUNT(*) FROM content WHERE added_by = ?) AS 'content_created',
            (SELECT COUNT(*) FROM content WHERE added_by = ? AND status = 'published') AS 'content_published',
            (SELECT reputation FROM pg_users.users WHERE id = ?) AS 'user_rep'
QUE;

    $stmt = $db->prepare($query);
    $user_id = $_SESSION['userid'];
    $stmt->execute([$user_id, $user_id, $user_id, $user_id]);

    $count = $stmt->fetch();

    return $count;
}

/**
 * @param $date integer of unixtimestamp format, not actual date type
 * @return string
 */
function toRelativeDate($date)
{
    $now = time();
    $diff = $now - $date;

    if ($diff < 60){
        return sprintf($diff > 1 ? '%s ثانیه پیش' : 'چند لحظه پیش', $diff);
    }

    $diff = floor($diff/60);

    if ($diff < 60){
        return sprintf($diff > 1 ? '%s دقیقه پیش' : 'یک دقیقه پیش', $diff);
    }

    $diff = floor($diff/60);

    if ($diff < 24){
        return sprintf($diff > 1 ? '%s ساعت پیش' : 'یک ساعت پیش', $diff);
    }

    $diff = floor($diff/24);

    if ($diff < 7){
        return sprintf($diff > 1 ? '%s روز پیش' : 'دیروز', $diff);
    }

    if ($diff < 30)
    {
        $diff = floor($diff / 7);

        return sprintf($diff > 1 ? '%s هفته پیش' : 'یک هفته پیش', $diff);
    }

    $diff = floor($diff/30);

    if ($diff < 12){
        return sprintf($diff > 1 ? '%s ماه پیش' : 'یک ماه پیش', $diff);
    }

    $diff = date('Y', $now) - date('Y', $date);

    return sprintf($diff > 1 ? '%s سال پیش' : 'یک سال پیش', $diff);
}

/**
 * Returns a relative string based on the passed $datetime
 * --VERY IMPORTANT-- If you pass the datetime in a localtime rather than UTC
 * you must also pass the EasyDateTime instance as that localtime instead of UTC
 *
 * @param EasyDateTime $edt
 * @param string $datetime
 * @param int $depth
 * @return string
 */
function toRelativeTimeWithDepth($edt, $datetime, $depth = 1)
{
    $units = [
        "سال"=>31104000,
        "ماه"=>2592000,
        "هفته"=>604800,
        "روز"=>86400,
        "ساعت"=>3600,
        "دقیقه"=>60,
        "ثانیه"=>1
    ];

    $plural = "";
    $conjugator = " و ";
    $separator = ", ";
    $suffix1 = " پیش";
    $suffix2 = " گذشته";
    $now = "حالا";
    $empty = "";


    $time = strtotime($edt->date());
    $timediff = $time - strtotime($datetime);
    // Original code from the function creator, with this code you won't need EasyDateTime,
    // But it will require you to pass the datetime as UTC at all times
//    $timediff = time() - strtotime($datetime);
    if ($timediff == 0) return $now;
    if ($depth < 1) return $empty;

    $max_depth = count($units);
    $remainder = abs($timediff);
    $output = "";
    $count_depth = 0;
    $fix_depth = true;

    foreach ($units as $unit=>$value) {
        if ($remainder>$value && $depth-->0) {
            if ($fix_depth) {
                $max_depth -= ++$count_depth;
                if ($depth>=$max_depth) $depth=$max_depth;
                $fix_depth = false;
            }
            $u = (int)($remainder/$value);
            $remainder %= $value;
            $pluralise = $u>1?$plural:$empty;
            $separate = $remainder==0||$depth==0?$empty:
                ($depth==1?$conjugator:$separator);
            $output .= "{$u} {$unit}{$pluralise}{$separate}";
        }
        $count_depth++;
    }
    return $output.($timediff<0?$suffix2:$suffix1);
}

function curl_request_async($url, $params, $type='POST')
{
    foreach ($params as $key => &$val) {
        if (is_array($val)) $val = implode(',', $val);
        $post_params[] = $key.'='.urlencode($val);
    }
    $post_string = implode('&', $post_params);

    $parts=parse_url($url);

    $fp = fsockopen($parts['host'],
        isset($parts['port'])?$parts['port']:80,
        $errno, $errstr, 30);

    // Data goes in the path for a GET request
    if('GET' == $type) $parts['path'] .= '?'.$post_string;

    $out = "$type ".$parts['path']." HTTP/1.1\r\n";
    $out.= "Host: ".$parts['host']."\r\n";
    $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
    $out.= "Content-Length: ".strlen($post_string)."\r\n";
    $out.= "Connection: Close\r\n\r\n";
    // Data goes in the request body for a POST request
    if ('POST' == $type && isset($post_string)) $out.= $post_string;

    fwrite($fp, $out);
    fclose($fp);
}

function getHeaders()
{
    return getallheaders();
}

function getValidationTokens()
{
    $headers = getHeaders();

    if(isset($headers['IS_AJAX']) && ($headers['IS_AJAX'] != null))
    {
        $ajax = $headers['IS_AJAX'];
    }
    else
    {
        if(isset($_POST['IS_AJAX']))
        {
            $ajax = $_POST['IS_AJAX'];
        }
        elseif(isset($_GET['IS_AJAX']))
        {
            $ajax = $_GET['IS_AJAX'];
        }
    }

    if(isset($headers['AUTH_TOKEN']) && ($headers['AUTH_TOKEN'] != null))
    {
        $token = $headers['AUTH_TOKEN'];
    }
    else
    {
        if(isset($_POST['AUTH_TOKEN']))
        {
            $token = $_POST['AUTH_TOKEN'];
        }
        elseif(isset($_GET['AUTH_TOKEN']))
        {
            $token = $_GET['AUTH_TOKEN'];
        }
    }

    if($token == null)
    {
        return false;
    }

    return ['success' => true, 'IS_AJAX' => $ajax, 'AUTH_TOKEN' => $token];
}

function prepareFile($fileName)
{
    return explode('%-%', $fileName)[1];
}

function base64ToImage($imageData, $pathToSaveTo)
{
    list($type, $imageData) = explode(';', $imageData);
    list(,$extension) = explode('/',$type);
    list(,$imageData)      = explode(',', $imageData);
    $fileName = uniqid() . time() .'.'.$extension;
    $fileNameWithPath = $pathToSaveTo . $fileName;
    $imageData = base64_decode($imageData);
    file_put_contents($fileNameWithPath, $imageData);

    return $fileName;
}

function newUrlEncode($string) {
    $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
    $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
    return str_replace($entities, $replacements, urlencode($string));
}

function isGetRequest()
{
    return $_SERVER['REQUEST_METHOD'] === 'GET' ? true : false;
}

function isPostRequest()
{
    return $_SERVER['REQUEST_METHOD'] === 'POST' ? true : false;
}

function getRegisterCaptcha($preserve_state = false)
{
    $captchaBuilder = new \Gregwar\Captcha\CaptchaBuilder();
    $captchaBuilder->setBackgroundColor(225, 225, 225);
    $captchaBuilder->build();

    if(!$preserve_state)
        $_SESSION['registerCaptcha'] = $captchaBuilder->getPhrase();

    return $captchaBuilder;
}

function verifyRegisterCaptcha($input)
{
    if(isset($_SESSION['registerCaptcha']))
    {
        if($input === $_SESSION['registerCaptcha'])
            return true;
        else
            return false;
    }
    return false;
}

function convertNumbers($srting, $toPersian = true)
{
    $en_num = array('0','1','2','3','4','5','6','7','8','9');
    $fa_num = array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹');
    if( $toPersian ) return str_replace($en_num, $fa_num, $srting);
    else return str_replace($fa_num, $en_num, $srting);
}

function getUrlWithoutQueryString($server)
{
    return parse_url($server["REQUEST_URI"], PHP_URL_PATH);
}

function generatePageUrl($server, $get, $goto = null)
{
    $abs_url_without_query = AUTO_SITE_HOME_URL . parse_url($server["REQUEST_URI"], PHP_URL_PATH);
    if(count($get) == 0 && $goto == null)
        return $abs_url_without_query;

    if(!isset($get['page']))
    {
        $get['page'] = 1;
    }

    if(mb_stripos($abs_url_without_query, '?', null, 'UTF-8') === false)
    {
        $abs_url_without_query .= '?';
    }

    $j = true;
    foreach ($get as $key => $value)
    {
        if($key == 'page')
        {
            if($goto != null)
            {
                if($goto == 'next_page')
                    $value += 1;
                elseif($goto == 'previous_page')
                    $value -= 1;
                elseif($goto == 'first_page')
                    $value = 1;
            }
        }
        if(!$j)
        {
            $abs_url_without_query .= "&$key=$value";
        }
        else
        {
            $abs_url_without_query .= "$key=$value";
            $j = false;
        }
    }
    return $abs_url_without_query;
}

function getHumanReadableFileSize($bytes)
{
    $bytes = floatval($bytes);
    $arBytes = array(
        0 => array(
            "UNIT" => "ترابایت",
            "VALUE" => pow(1024, 4)
        ),
        1 => array(
            "UNIT" => "گیگابایت",
            "VALUE" => pow(1024, 3)
        ),
        2 => array(
            "UNIT" => "مگابایت",
            "VALUE" => pow(1024, 2)
        ),
        3 => array(
            "UNIT" => "کیلوبایت",
            "VALUE" => 1024
        ),
        4 => array(
            "UNIT" => "بایت",
            "VALUE" => 1
        ),
    );

    foreach($arBytes as $arItem)
    {
        if($bytes >= $arItem["VALUE"])
        {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
            break;
        }
    }
    return $result;
}

function generateAvatarAbsoluteUrl($fileName)
{
    if($fileName == null)
    {
        return AUTO_SITE_HOME_URL . '/img/user.png';
    }
    else
    {
        return AUTO_SITE_HOME_URL . '/img/avatars/' . $fileName;
    }
}

function generateThumbnailAbsoluteUrl($fileName)
{
    if($fileName == null)
    {
        return AUTO_SITE_HOME_URL . '/img/image_placeholder.jpg';
    }
    else
    {
        return AUTO_SITE_HOME_URL . '/img/posts/' . $fileName;
    }
}

function generateImageAbsoluteUrl($fileName)
{
    if($fileName == null)
    {
        return AUTO_SITE_HOME_URL . '/img/image_placeholder.jpg';
    }
    else
    {
        return AUTO_SITE_HOME_URL . '/img/front-page/' . $fileName;
    }
}

function generatePortfolioAbsoluteUrl($fileName)
{
    if($fileName == null)
    {
        return AUTO_SITE_HOME_URL . '/img/image_placeholder.jpg';
    }
    else
    {
        return AUTO_SITE_HOME_URL . '/img/portfolios/' . $fileName;
    }
}

function getSocialNetworksUrlById($id, $network)
{
    if($network == 'telegram')
        return 'https://telegram.me/' . $id;
    elseif($network == 'instagram')
        return 'https://instagram.com/' . $id;
    elseif($network == 'linkedin')
        return 'https://linkedin.com/in/' . $id;

    return null;
}

function getBase64WithoutHeader($base64)
{
    return preg_replace('#^data:image/[^;]+;base64,#', '', $base64);
}

function generateRandomToken($length = 10, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}
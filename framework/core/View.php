<?php

namespace NOMVC\Core;

require_once __DIR__ . '/../../app/code/commons.php';

class View
{
    public $twig;

    public function __construct()
    {
        $approot = getAppRoot(true);
        // Directories for Twig_Loader_Filesystem MUST exist!
        $loader = new \Twig_Loader_Filesystem([
            $approot,
            $approot . 'areas', // Recommended naming convention,
            $approot . 'areas' . '/front/_shared',
        ]);

        $this->twig = new \Twig_Environment($loader, [
            'cache' => (TWIG_CACHE === true ? getProjectRoot(true) . 'cache/views' : false),
            'debug' => TWIG_DEBUG
        ]);

        $dbConn = new SQLDatabase();
        $detect = new \Mobile_Detect;

        $this->twig->addGlobal('is_production', IS_PRODUCTION);
        $this->twig->addGlobal('auto_site_base_url', AUTO_SITE_HOME_URL);
        $this->twig->addGlobal('id_site_url', ID_SITE_URL);
        $this->twig->addGlobal('site_name_to_show', SITE_NAME_TO_SHOW);
        $this->twig->addGlobal('max_upload_size', MAX_UPLOAD_SIZE);
        $this->twig->addGlobal('session', $_SESSION);
        $this->twig->addGlobal('server', $_SERVER);
        $this->twig->addGlobal('get', $_GET);
        $this->twig->addGlobal('edt', new \EasyDateTime('Asia/Tehran', 'jalali'));
        //$this->twig->addGlobal('user_info', getUserActivityInfo($dbConn->db));
        $this->twig->addGlobal('is_https', isHttps());
        //$this->twig->addGlobal('is_mobile', $detect->isMobile());
        //$this->twig->addGlobal('is_tablet', $detect->isTablet());
        //$this->twig->addGlobal('is_android', $detect->is('AndroidOS'));

        $translateToPersianFunction = new \Twig_SimpleFunction('translateToPersian', function ($str) {
            return translateToPersian($str);
        });
        $relativeDateFunction = new \Twig_SimpleFunction('toRelativeDate', function ($timestamp) {
            return toRelativeDate($timestamp);
        });
        $convertNumbersFunction = new \Twig_SimpleFunction('convertNumbers', function ($input, $toPersian = true) {
            return convertNumbers($input, $toPersian);
        });
        $strtotimeFunction = new \Twig_SimpleFunction('strtotime', function ($datetime) {
            return strtotime($datetime);
        });
        $getUrlWithoutQueryStringFunction = new \Twig_SimpleFunction('getUrlWithoutQueryString', function ($server) {
            return getUrlWithoutQueryString($server);
        });
        $generatePageUrlFunction = new \Twig_SimpleFunction('generatePageUrl', function ($server, $get, $goto) {
            return generatePageUrl($server, $get, $goto);
        });
        $generateAvatarAbsoluteUrlFunction = new \Twig_SimpleFunction('generateAvatarAbsoluteUrl', function ($fileName) {
            return generateAvatarAbsoluteUrl($fileName);
        });
        $generateThumbnailAbsoluteUrlFunction = new \Twig_SimpleFunction('generateThumbnailAbsoluteUrl', function ($fileName) {
            return generateThumbnailAbsoluteUrl($fileName);
        });
        $generateImageAbsoluteUrlFunction = new \Twig_SimpleFunction('generateImageAbsoluteUrl', function ($fileName) {
            return generateImageAbsoluteUrl($fileName);
        });
        $generatePortfolioAbsoluteUrlFunction = new \Twig_SimpleFunction('generatePortfolioAbsoluteUrl', function ($fileName) {
            return generatePortfolioAbsoluteUrl($fileName);
        });
        $getSocialNetworksUrlByIdFunction = new \Twig_SimpleFunction('getSocialNetworksUrlById', function ($id, $network) {
            return getSocialNetworksUrlById($id, $network);
        });

        $this->twig->addFunction($translateToPersianFunction);
        $this->twig->addFunction($relativeDateFunction);
        $this->twig->addFunction($convertNumbersFunction);
        $this->twig->addFunction($strtotimeFunction);
        $this->twig->addFunction($getUrlWithoutQueryStringFunction);
        $this->twig->addFunction($generatePageUrlFunction);
        $this->twig->addFunction($generateAvatarAbsoluteUrlFunction);
        $this->twig->addFunction($generateThumbnailAbsoluteUrlFunction);
        $this->twig->addFunction($generateImageAbsoluteUrlFunction);
        $this->twig->addFunction($generatePortfolioAbsoluteUrlFunction);
        $this->twig->addFunction($getSocialNetworksUrlByIdFunction);
    }

    public function render($pathToTemplate, $additionalData = [])
    {
        if($pathToTemplate == null)
        {
            return new \Exception("Template path is not set");
        }

        echo $this->twig->render($pathToTemplate, $additionalData);
    }

//    public static function toView($view) : View
//    {
//        return $view;
//    }

    public static function toView($view)
    {
        return $view;
    }
}
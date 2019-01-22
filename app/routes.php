<?php

use Phroute\Phroute\RouteCollector;

$router = new RouteCollector();
require_once 'code/commons.php';

$router->get('/', function () {
    require_once getAppRoot(true) . AREAS_DIR . '/home/index.php';
});
$router->get('blog', function () {
    require_once getAppRoot(true) . AREAS_DIR . '/blog/blog.php';
});
$router->any('blog/{_id:i}/{_title}?', function ($_id, $_title = null) {
    if($_title != null)
        $_title = urldecode($_title);
    require_once getAppRoot(true) . AREAS_DIR . '/blog/post.php';
});
$router->get('about', function () {
    require_once getAppRoot(true) . AREAS_DIR . '/home/about.php';
});
$router->get('portfolios', function () {
    require_once getAppRoot(true) . AREAS_DIR . '/portfolios/index.php';
});
$router->any('login', function () {
    require_once getAppRoot(true) . AREAS_DIR . '/admin/login.php';
});
$router->group(['prefix' => 'admin'], function($router) {
    $router->any('/', function() {
        require_once getAppRoot(true) . AREAS_DIR . '/admin/index.php';
    });
    $router->any('blog', function() {
        require_once getAppRoot(true) . AREAS_DIR . '/admin/blog/index.php';
    });
    $router->any('edit/{_id:i}', function($_id) {
        $_id = urldecode($_id);
        require_once getAppRoot(true) . AREAS_DIR . '/admin/blog/edit.php';
    });
    $router->any('newpost', function() {
        require_once getAppRoot(true) . AREAS_DIR . '/admin/blog/new.php';
    });
    $router->any('comments', function() {
        require_once getAppRoot(true) . AREAS_DIR . '/admin/comments/index.php';
    });
    $router->any('comments/{_id:i}', function($_id) {
        $_id = urldecode($_id);
        require_once getAppRoot(true) . AREAS_DIR . '/admin/comments/edit.php';
    });
    $router->any('users', function() {
        require_once getAppRoot(true) . AREAS_DIR . '/admin/users/index.php';
    });
    $router->any('users/new', function() {
        require_once getAppRoot(true) . AREAS_DIR . '/admin/users/new.php';
    });
    $router->any('front-page', function() {
        require_once getAppRoot(true) . AREAS_DIR . '/admin/front-page/index.php';
    });
    $router->any('front-page/list/{_page}/{_section}', function($_page, $_section) {
        $_page = urldecode($_page);
        $_section = urldecode($_section);
        require_once getAppRoot(true) . AREAS_DIR . '/admin/front-page/list.php';
    });
    $router->any('front-page/list/{_page}?/{_section}?/{_id:i}', function($_page, $_section, $_id) {
        $_page = urldecode($_page);
        $_section = urldecode($_section);
        $_id = urldecode($_id);
        require_once getAppRoot(true) . AREAS_DIR . '/admin/front-page/edit.php';
    });
    $router->any('courses/{_id:i}', function($_id) {
        $_id = urldecode($_id);
        require_once getAppRoot(true) . AREAS_DIR . '/admin/courses/edit.php';
    });
});




$dispatcher = new Phroute\Phroute\Dispatcher($router->getData());
try
{
    $response = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
}
catch (Phroute\Phroute\Exception\HttpRouteNotFoundException $e)
{
    header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    require_once getAppRoot(true) . AREAS_DIR . '/common/404.php';
}
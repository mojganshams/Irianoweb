<?php

$view = \NOMVC\Core\View::toView(getAppServices()['view']);

$currentView = 'front/common/404.html.twig';

return $view->render($currentView);
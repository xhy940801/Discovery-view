<?php

define('__APP_DIR__', 'app');
define('__PUBLIC_DIR__', 'public');
define('__ROOT__', dirname(__FILE__));
define('__WEBROOT_DIR__', 'webroot');
define('__WWW_ROOT__', __ROOT__ . DIRECTORY_SEPARATOR . __APP_DIR__ . DIRECTORY_SEPARATOR . __WEBROOT_DIR__ . DIRECTORY_SEPARATOR);
define('__HOST_URL__', substr(__ROOT__, strlen($_SERVER['DOCUMENT_ROOT'])));

require(__ROOT__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'godle' . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'App.class.php');

App::import('common', './', 'lib' . DIRECTORY_SEPARATOR . 'godle', '.php');
App::import('Controller', 'Controller', 'lib' . DIRECTORY_SEPARATOR . 'godle');

$conf = App::getConf();
App::dispacher(substr($_SERVER['DOCUMENT_ROOT'] . $_SERVER['REQUEST_URI'], strlen(__ROOT__)), $conf);
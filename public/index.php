<?php

require_once __DIR__.'/../vendor/autoload.php';

use app\controllers\Contoh;
use app\controllers\Api;
use app\controllers\User;
use app\core\Application;
use app\core\Router;

$app = new Application();

$app->router->get('/',[Api::class,'getMainMenu']);
$app->router->get('/menu',[Api::class,'setMainMenu']);
$app->router->post('/login',[User::class,'auth']);
$app->router->get('/create',[Contoh::class,'createUser']);
$app->router->post('/bot-salam',function() {
});

$app->run();
<?php

require_once __DIR__.'/../vendor/autoload.php';

use app\controllers\Contoh;
use app\core\Application;

$app = new Application();

$app->router->get('/',[Contoh::class,'getMainMenu']); // contoh route get
$app->router->get('/menu',[Contoh::class,'setMainMenu']); // contoh route get
$app->router->post('/',[Contoh::class,'subContoh']); // contoh route postc


$app->run();
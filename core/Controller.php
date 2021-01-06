<?php
namespace app\core;

class Controller
{
    public function jsonResponse($code,$arr = [])
    {
        error_reporting(0);
        return Application::$app->response->jsonOut($code,$arr);
    }
}

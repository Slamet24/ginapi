<?php
namespace app\core;

class Controller
{
    public function jsonResponse($code,$arr = [])
    {
        return Application::$app->response->jsonOut($code,$arr);
    }
}

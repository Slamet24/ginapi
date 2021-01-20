<?php
namespace app\core;
use app\services\Tokens;
class Controller
{
    // Fungsi menampilkan data berupa json
    public function jsonResponse($code,$arr = [])
    {
        error_reporting(0);
        return Application::$app->response->jsonOut($code,$arr);
    }
}

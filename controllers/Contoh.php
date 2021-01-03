<?php
/*
    Aturan membuat controller :
    - Tambahkan namespace app\controllers
    - Tambahkan use app\core\Controllers
    - Tambahkan use app\core\Request
    - Extends class controller yang kamu buat dengan Controller
    
    Aturan mengambil data GET/POST :
    - Masukkan $request->getBody() ke variable $body
    - Jika ingin mengambil data spesifik, contoh :
        http://localhost/home?id=100&var1=apa&var2=ini
        // jika ingin mengambil data var2, maka :
        $body = $request->getBody();
        $body['var2'];
        // Cara ini bisa digunakan di program baik method get/post
*/
namespace app\controllers;
use app\core\Controller;
use app\core\Request;
use app\models\Database;

class Contoh extends Controller {

    public function subContoh(Request $request)
    {
        $db = new Database();
        $body = $request->getBody();
        return $this->jsonResponse(200,["message"=>$body]);
    }

    public function contohCtrl(Request $request)
    {
        return $this->jsonResponse(200,["message"=>"success"]);
    }
}
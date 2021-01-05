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
use app\core\Database;
use Nowakowskir\JWT\JWT;
use Nowakowskir\JWT\TokenDecoded;
use Nowakowskir\JWT\TokenEncoded;
use Nowakowskir\JWT\Exceptions\IntegrityViolationException;
use Nowakowskir\JWT\Exceptions\AlgorithmMismatchException;
use Nowakowskir\JWT\Exceptions\TokenExpiredException;

class Contoh extends Controller {

    public function cektoken(Request $request)
    {
        $tokenDecoded = new TokenDecoded(['username' => 'slametfaisal1@gmail.com'], ['alg' => 'HS512','typ' => 'JWT']);
        $tokenEncoded = $tokenDecoded->encode('sha2gin', JWT::ALGORITHM_HS512);
        return $this->jsonResponse(200,["token" => $tokenEncoded->toString(),"expired_on" => date("h:i",time() + 60)]);
    }

    public function getMainMenu(Request $request)
    {
        $db = new Database();
        $q = $db->selectAll("pertanyaan");
        return $this->jsonResponse(200,["message"=>"success","menu"=>$q]);
    }

    public function setMainMenu(Request $request)
    {
        error_reporting(0);
        $db = new Database();
        $body = $request->getBody();
        if (!$body['id']) {
            $q = $db->selectAll("pertanyaan");
            return $this->jsonResponse(200,["message"=>"success","menu"=>$q]);
        } else if (count($body) > 1 && $body['id'] && $body['sub1']) {
            $q = $db->getInstance()->pdo->query("SELECT * FROM pertanyaan JOIN sub_pertanyaan ON pertanyaan.q_id=sub_pertanyaan.q_id WHERE pertanyaan.q_id='sha20$body[id]' AND sq_id='sha2$body[id]$body[sub1]'");
            $row = $q->fetch(\PDO::FETCH_ASSOC);
            return $this->jsonResponse(200,["message"=>"success","menu"=>$row]);
        }
        $q = $db->select("sub_pertanyaan","q_id","sha20$body[id]");
        return $this->jsonResponse(200,["message"=>"success","menu"=>$q]);
    }
}
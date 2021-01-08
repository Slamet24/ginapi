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
use app\services\Tokens;

class User extends Controller {

    public function auth(Request $request)
    {
        $db = new Database();
        $t = new Tokens();
        if ($request->isPost()) {
            $body = $request->getBody();
            $q = $db->getInstance()->pdo->query("SELECT * FROM users WHERE email = '$body[email]'");
            $user = $q->fetch(\PDO::FETCH_ASSOC);
            if (password_verify($body['sandi'],$user['password'])) {
                $payload = [
                    'email' => $user['email'],
                    'sandi' => $user['password']
                ];
                $dateAccess = date("Y-m-d h:i");
                $db->getInstance()->pdo->query("UPDATE users SET date_access = '$dateAccess' WHERE email = '$body[email]'");
                $token = $t->getToken($payload);
                $codeauth = $t->validateToken($token);
                switch ($codeauth) {
                    case 400:
                        return $this->jsonResponse(401,['message'=>'token tidak valid']);
                        break;
                    
                    case 4460:
                        return $this->jsonResponse(401,['message'=>'token kadaluwarsa']);
                        break;

                    case 4461:
                        return $this->jsonResponse(401,['message'=>'token tidak valid']);
                        break;
                    
                    case 4461:
                        return $this->jsonResponse(401,['message'=>'token tidak valid']);
                        break;
                    
                    case 200:
                        return $this->jsonResponse(200,['message'=>'token tervalidasi','token'=>$token]);
                        break;
                    default:
                        break;
                }
            }
            return $this->jsonResponse(401,['message' => 'login tidak valid.']);
        }
        return $this->jsonResponse(401,['message' => 'hanya menerima POST method.']);
    }
}

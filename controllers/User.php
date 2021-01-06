<?php
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
                        return $this->jsonResponse(400,['message'=>'token tidak valid']);
                        break;
                    
                    case 4460:
                        return $this->jsonResponse(400,['message'=>'token kadaluwarsa']);
                        break;

                    case 4461:
                        return $this->jsonResponse(400,['message'=>'token tidak valid']);
                        break;
                    
                    case 4461:
                        return $this->jsonResponse(400,['message'=>'token tidak valid']);
                        break;
                    
                    case 200:
                        return $this->jsonResponse(200,['message'=>'token tervalidasi']);
                        break;
                    default:
                        break;
                }
            }
            return $this->jsonResponse(400,['message' => 'login tidak valid.']);
        }
        return $this->jsonResponse(400,['message' => 'hanya menerima POST method.']);
    }
}

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

class Api extends Controller {

    private Request $req;
    public function __construct()
    {
        $req = new Request();
        $body = $req->getBody();
        if (!$this->getAccess($body['token'] ?? null)) {
            echo $this->jsonResponse(401,['status'=>'failed','message'=>'periksa kembali token anda.']);
            exit;
        }
    }

    public function getMainMenu(Request $request)
    {
        $db = new Database();
        $q = $db->selectAll("pertanyaan");
        return $this->jsonResponse(200,["status"=>"success","menu"=>$q]);
    }

    public function setMainMenu(Request $request)
    {
        error_reporting(0);
        $db = new Database();
        $body = $request->getBody();
        if (!$body['id']) {
            $q = $db->selectAll("pertanyaan");
            return $this->jsonResponse(200,["status"=>"success","menu"=>$q]);
        } else if (count($body) > 2 && $body['id']) {
            $where = "";
            $table = "";
            $query = "";
            $sub = $body['id'];
            for ($i=1; $i <= count($body); $i++) { 
                if ($body["sub$i"]) {
                    if ($i==1) {
                        $table = "sub_pertanyaan_$i";
                        $where = "sub_pertanyaan_".($i+1).".sq".$i."_id = '".$body['id'].$body["sub$i"]."' ";
                        $query = "SELECT * FROM $table JOIN sub_pertanyaan_".($i+1)." ON $table.sq1_id = sub_pertanyaan_".($i+1).".sq1_id WHERE $where";
                    } else {
                        $sub_bf = $body['sub'.$i-1]; 
                        $sub .= $sub_bf.$body["sub$i"];
                        $table = "sub_pertanyaan_$i";
                        $table_bf = "sub_pertanyaan_".($i-1);
                        $where = "sub_pertanyaan_$i.sq".($i-1)."_id = '$sub' ";
                        $query = "SELECT * FROM $table JOIN $table_bf ON $table.sq".($i-1)."_id = $table_bf.sq".($i-1)."_id WHERE $where";
                    }
                }
            }
            $q = $db->getInstance()->pdo->query($query);
            $stocks = [];
            while ($row = $q->fetch(\PDO::FETCH_ASSOC)) {
                array_push($stocks,$row);
            }
            return $this->jsonResponse(200,["status"=>"success","menu"=>$stocks]);
        }
        $q = $db->getInstance()->pdo->query("SELECT * FROM sub_pertanyaan_1 WHERE q_id = '$body[id]'");
        $stocks = [];
        while ($row = $q->fetch(\PDO::FETCH_ASSOC)) {
            $stocks[] = [
                'id' => $row['sq1_id'],
                'id_pertanyaan' => $row['q_id'],
                'sub' => $row['sub1_question']
            ];
        }
        return $this->jsonResponse(200,["status"=>"success","menu"=>$stocks]);
    }

    public function getAccess($token)
    {
        $token;
        $t = new Tokens();
        if ($token != null) {
            $codeauth = $t->validateToken($token);
            switch ($codeauth) {
                case 400:
                    return false;
                    break;
                
                case 4460:
                    return false;
                    break;

                case 4461:
                    return false;
                    break;
                
                case 4462:
                    return false;
                    break;
                
                case 4463:
                    return false;
                    break;
                
                case 200:
                    return true;
                    break;
                default:
                    break;
            }
        }
        return false;
    }

    // GinBot

    public function getMenu()
    {
        $db = new Database();
        $q = $db->selectAll("pertanyaan");
        return json_encode($q);
    }

    public function setMenu($id)
    {
        $db = new Database();
        $q = $db->select("sub_pertanyaan_1","q_id",$id);
        return json_encode($q);
    }

    public function setSub1($id)
    {
        $db = new Database();
        $q = $db->select("sub_pertanyaan_2","sq1_id",$id);
        return json_encode($q);
    }

    public function setSub1($id)
    {
        $db = new Database();
        $q = $db->select("sub_pertanyaan_3","sq2_id",$id);
        return json_encode($q);
    }
}
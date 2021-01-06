<?php
namespace app\controllers;
use app\core\Controller;
use app\core\Request;
use app\core\Database;
use app\services\Tokens;

class Api extends Controllers {

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
        } else if (count($body) > 1 && $body['id']) {
            $where = "";
            $table = "";
            $join = "";
            $sub = "sha2".$body['id'];
            for ($i=1; $i <= count($body); $i++) { 
                if ($body["sub$i"]) {
                    if ($i==1) {
                        $table = "sub_pertanyaan_$i";
                        $where .= "sub_pertanyaan_$i.sq".$i."_id = 'sha2".$body['id'].$body["sub$i"]."' ";
                        $join .= "JOIN $table ON $table.q_id = pertanyaan.q_id ";
                    } else {
                        $sub_bf = $body['sub'.$i-1]; 
                        $sub .= $sub_bf.$body["sub$i"];
                        $table = "sub_pertanyaan_$i";
                        $table_bf = "sub_pertanyaan_".($i-1);
                        $where .= "AND sub_pertanyaan_$i.sq".($i-1)."_id = '$sub' ";
                        $join .= "JOIN $table ON $table.sq".($i-1)."_id = $table_bf.sq".($i-1)."_id ";
                    }
                }
            }
            $query = "SELECT * FROM pertanyaan $join WHERE $where";
            $q = $db->getInstance()->pdo->query($query);
            $stocks = [];
            while ($row = $q->fetch(\PDO::FETCH_ASSOC)) {
                array_push($stocks,$row);
            }
            return $this->jsonResponse(200,["message"=>"success","menu"=>$stocks]);
        }
        $q = $db->getInstance()->pdo->query("SELECT * FROM sub_pertanyaan_1 WHERE q_id = 'sha20$body[id]'");
        $stocks = [];
        while ($row = $q->fetch(\PDO::FETCH_ASSOC)) {
            $stocks[] = [
                'id' => $row['sq1_id'],
                'id_pertanyaan' => $row['q_id'],
                'sub' => $row['sub1_question']
            ];
        }
        return $this->jsonResponse(200,["message"=>"success","menu"=>$stocks]);
    }
}
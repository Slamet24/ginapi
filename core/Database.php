<?php
namespace app\core;

class Database{

    private static $_instance = null;
    public \PDO $pdo;
	private $db = "pgsql";
	private $serv = "localhost";
    private $user = "sha2";
    private $pass = "ginapi";
    private $dbname = "core_api";
    
    public function __construct(){
        $this->pdo = new \PDO("$this->db:host=$this->serv;port=5433;dbname=$this->dbname", $this->user, $this->pass);
    }

    public function check()
    {
        var_dump($this->pdo);
    }

    public function getInstance(){
		if(!isset(self::$_instance)){
			self::$_instance = new Database();
		}

		return self::$_instance;
    }
    
    public function selectAll($tabel)
    {
        $hasil = $this->pdo->query("SELECT * FROM $tabel");
        $row = $hasil->fetchAll(\PDO::FETCH_ASSOC);
		return $row;
        $hasil = $this->pdo->query("SELECT * FROM $tabel");
        $stocks = [];
        while ($row = $hasil->fetch(\PDO::FETCH_ASSOC)) {
            $stocks[] = [
                'id_pertanyaan' => $row['q_id'],
                'pertanyaan' => $row['question']
            ];
        }
        return $stocks;
    }

    public function select($tabel, $key, $kondisi){
        $hasil = $this->pdo->query("SELECT * FROM $tabel WHERE $key = '$kondisi'");
        $stocks = [];
        while ($row = $hasil->fetch(\PDO::FETCH_ASSOC)) {
            array_push($stocks,$row);
        }
        return $stocks;
    }
}

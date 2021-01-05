<?php
namespace app\core;

class Database{

    private static $_instance = null;
    public \PDO $pdo;
	private $db = "pgsql";
	private $serv = "localhost";
    private $user = "postgres";
    private $pass = "Nidumila";
    private $dbname = "ginapi";
    
    public function __construct(){
        $this->pdo = new \PDO("$this->db:host=$this->serv;port=5432;dbname=$this->dbname", $this->user, $this->pass);
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
        $hasil = $this->connect->query("SELECT * FROM $tabel");
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
            $stocks[] = [
                'id' => $row['sq_id'],
                'id_pertanyaan' => $row['q_id'],
                'sub' => $row['sub_question']
            ];
        }
        return $stocks;
    }
}

<?php
namespace app\models;

class Database{

    private static $_instance = null;
	private \PDO $pdo;
	private $db = "pgsql";
	private $serv = "localhost";
    private $user = "sha2";
    private $pass = "ginapi";
    private $dbname = "core_api";
    
    public function __construct(){
        $this->pdo = new \PDO("$this->db:host=$this->serv;port=5433;dbname=$this->dbname", $this->user, $this->pass);
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
        $row = $hasil->fetchAll(PDO::FETCH_ASSOC);
		return $row;
    }

    public function select($tabel, $key, $kondisi){
        $hasil = $this->konek->query("SELECT * FROM $tabel WHERE $key = '$kondisi'");
        $row = $hasil->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
}?>
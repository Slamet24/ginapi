<?php

class Database{

    private static $_instance = null;
	private $connect;
	private $db = "pgsql";
	private $serv = "localhost";
    private $user = "postgres";
    private $pass = "Nidumila";
    private $dbname = "Argonaunt";
    
    public function __construct(){
        $this->connect = new PDO("$this->db:host=$this->serv; dbname=$this->dbname", $this->user, $this->pass);
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
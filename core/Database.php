<?php

namespace app\core;

class Database
{

    private static $_instance = null;
    public \PDO $pdo;
    private $db = "pgsql";
    private $serv = "localhost";
    private $user = "postgres";
    private $pass = "Nidumila";
    private $dbname = "ginapi";

    public function __construct()
    {
        $this->pdo = new \PDO("$this->db:host=$this->serv;port=5432;dbname=$this->dbname", $this->user, $this->pass);
    }

    public function check()
    {
        var_dump($this->pdo);
    }

    public function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new Database();
        }
        return self::$_instance;
    }

    public function selectAll($tabel)
    {
        $hasil = $this->pdo->query("SELECT * FROM $tabel");
        $stocks = [];
        while ($row = $hasil->fetch(\PDO::FETCH_ASSOC)) {
            $stocks[] = [
                'q_id' => $row['q_id'],
                'question' => $row['question']
            ];
        }
        return $stocks;
    }

    public function select($tabel, $key, $kondisi)
    {
        $hasil = $this->pdo->query("SELECT * FROM $tabel WHERE $key = '$kondisi'");
        $stocks = [];
        while ($row = $hasil->fetch(\PDO::FETCH_ASSOC)) {
            array_push($stocks, $row);
        }
        return $stocks;
    }

    public function login($email)
    {
        $hasil = $this->pdo->query("SELECT * FROM users WHERE email = '$email'");
        $row = $hasil->fetch(\PDO::FETCH_ASSOC);
        return $row;
    }

    public function setToken($token, $email)
    {
        $this->pdo->query("UPDATE users SET date_access = '$token' WHERE email = '$email'");
    }
}

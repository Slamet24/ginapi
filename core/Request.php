<?php
namespace app\core;

class Request {

    // Mendapatkan alamat url
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = stripos($path,'?');
        if ($position === false) {
            return $path;
        }
        return substr($path,0,$position);
    }

    // Mendapatkan info method yang digunakan
    public function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    // Mencheck apakah method berupa Get
    public function isGet()
    {
        return $this->method() === 'get';
    }

    // Mencheck apakah method berupa Post
    public function isPost()
    {
        return $this->method() === 'post';
    }

    // Mendapatkan data yang dikirim baik menggunakan method get atau post
    public function getBody()
    {
        $body = [];
        if ($this->method() === 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET,$key,FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($this->method() === 'post') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST,$key,FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }
}
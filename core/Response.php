<?php
namespace app\core;
// use Rcubitto\JsonPretty\JsonPretty;

class Response {

    // Set status kode ketika memberikan response
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }

    // Fungsi menampilkan data berupa json
    public function jsonOut(int $code,array $json)
    {
        // $jsonPretty = new JsonPretty();
        $this->setStatusCode($code);
        return json_encode($json,JSON_PRETTY_PRINT);
        // return $jsonPretty->print($json);
    }
}
<?php
namespace app\core;
use Rcubitto\JsonPretty\JsonPretty;

class Response {
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }

    public function jsonOut(int $code,array $json)
    {
        $jsonPretty = new JsonPretty();
        $this->setStatusCode($code);
        // return json_encode($json,JSON_PRETTY_PRINT);
        return $jsonPretty->print($json);
    }
}
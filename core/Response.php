<?php
namespace app\core;

class Response {
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }

    public function jsonOut(int $code,array $json)
    {
        $this->setStatusCode($code);
        return json_encode($json,JSON_PRETTY_PRINT);
    }
}
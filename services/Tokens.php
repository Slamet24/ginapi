<?php
namespace app\services;
use Nowakowskir\JWT\JWT;
use Nowakowskir\JWT\TokenDecoded;
use Nowakowskir\JWT\TokenEncoded;
use Nowakowskir\JWT\Exceptions\IntegrityViolationException;
use Nowakowskir\JWT\Exceptions\TokenExpiredException;
use Nowakowskir\JWT\Exceptions\InvalidStructureException;

class Tokens {

    public function getToken($payload)
    {
        $payload['sandi'] = hash("sha256",$payload['sandi']);
        $tokenDecoded = new TokenDecoded($payload, ['alg' => 'HS512','typ' => 'JWT'],['exp' => time() + 60]);
        $tokenEncoded = $tokenDecoded->encode('sha2gin'.date("Y-m-d"), JWT::ALGORITHM_HS512, 500);
        return $tokenEncoded->toString();
    }

    public function validateToken($token)
    {
        $tokenEncoded = new TokenEncoded($token);
        try {
            $tokenEncoded->validate('sha2gin'.date("Y-m-d"), JWT::ALGORITHM_HS512, 500);
            return 200;
        } catch(Exception $e) {
            return 400;
        } catch(TokenExpiredException $e) {
            return 4460;
        } catch(IntegrityViolationException $e) {
            return 4461;
        } catch(AlgorithmMismatchException $e) {
            return 4462;
        }
    }
}
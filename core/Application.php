<?php
namespace app\core;

class Application {
    
    public Router $router;
    public Request $request;
    public Response $response;
    public static Application $app;
    // Membuat Instance
    public function __construct()
    {
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request,$this->response);
    }

    // Menjalankan Program
    public function run()
    {
        echo $this->router->resolve();
    }
}
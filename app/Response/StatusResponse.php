<?php

namespace App\Response;

use Psr\Http\Message\ResponseInterface as Response;

class StatusResponse implements Response {

    use ResponseWrapperTrait;

    public function __construct(Response $response, String $message, int $code)
    {
        $response->getBody()->write(json_encode(array('status' => $message)));
        $this->res = $response->withStatus($code)->withHeader('Content-Type', 'application/json');
    }
}
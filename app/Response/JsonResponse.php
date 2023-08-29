<?php

namespace App\Response;

use Psr\Http\Message\ResponseInterface as Response;

class JsonResponse implements Response {

    use ResponseWrapperTrait;

    public function __construct(Response $response, $payload, int $code)
    {
        $response->getBody()->write(json_encode($payload));
        $this->res = $response->withStatus($code)->withHeader('Content-Type', 'application/json');
    }
}
<?php

namespace App\Controller;

use App\Model\Todo;

use App\Response\JsonResponse;
use App\Response\StatusResponse;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TodoController {

    private $db;

    public function __construct(ContainerInterface $container) 
    {
       $this->db = $container->get('DB');
    }

    # GET
    public function getTodo(Request $req, Response $res, $args) 
    {
        $todo = new Todo($this->db, $args['todoId']);

        if ( ! $todo->exists()) 
        {
            return new StatusResponse($res, "Todo not found", 404);
        }

        return new JsonResponse($res, $todo, 200);
    }

    # PUT
    public function updateTodo(Request $req, Response $res, $args) 
    {
        $todo = new Todo($this->db, $args['todoId']);

        if ( ! $todo->exists()) 
        {
            return new StatusResponse($res, "Todo not found", 404);
        }

        $reqJson = $req->getParsedBody();

        if ( ! isset($reqJson['text'])) 
        {
            return new StatusResponse($res, "Bad input format", 400);
        }

        if ( ! $todo->update($reqJson['text']) ) 
        {
            return new StatusResponse($res, "Failed to update Todo", 400);
        }

        return new JsonResponse($res, $todo, 200);
    }

    # DELETE
    public function deleteTodo(Request $req, Response $res, $args) 
    {
        $todo = new Todo($this->db, $args['todoId']);

        if ( ! $todo->delete() ) 
        {
            return new StatusResponse($res, "Todo not found", 404);
        }

        return new StatusResponse($res, "Todo deleted", 200);
    }
}
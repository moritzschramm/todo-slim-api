<?php

namespace App\Controller;

use App\Model\Todo;
use App\Model\TodoList;

use App\Response\JsonResponse;
use App\Response\StatusResponse;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ListController {

    private $db;

    public function __construct(ContainerInterface $container) 
    {
       $this->db = $container->get('DB');
    }

    # GET
    public function showLists(Request $req, Response $res) 
    {
        return new JsonResponse($res, TodoList::all($this->db), 200);
    }

    # POST
    public function createList(Request $req, Response $res, $args) 
    {
        $reqJson = $req->getParsedBody();
    
        if ( ! isset($reqJson["name"])) 
        {
            return new StatusResponse($res, "Bad input format", 400);
        }

        $todolist = new TodoList($this->db);

        $todolist->create($reqJson["name"]);

        return new JsonResponse($res, $todolist, 201);
    }

    # GET
    public function getList(Request $req, Response $res, $args) 
    {
        $list = new TodoList($this->db, $args['listId']);

        if( ! $list->exists()) 
        {
            return new StatusResponse($res, "List not found", 404);
        }

        return new JsonResponse($res, $list, 200);
    }

    # PUT
    public function updateList(Request $req, Response $res, $args) 
    {
        $list = new TodoList($this->db, $args['listId']);

        if ( ! $list->exists()) 
        {
            return new StatusResponse($res, "List not found", 404);
        }

        $reqJson = $req->getParsedBody();

        if ( ! isset($reqJson['name'])) 
        {
            return new StatusResponse($res, "Bad input format", 400);
        }

        if ( ! $list->update($reqJson['name']) ) 
        {
            return new StatusResponse($res, "Failed to update Todo", 401);
        }

        return new JsonResponse($res, $list, 200);
    }

    # DELETE
    public function deleteList(Request $req, Response $res, $args) 
    {
        $list = new TodoList($this->db, $args['listId']);

        if ( ! $list->delete()) 
        {
            return new StatusResponse($res, "List not found", 404);
        }

        return new StatusResponse($res, "List deleted", 200);
    }

    # POST
    public function createTodo(Request $req, Response $res, $args) 
    {
        $list = new TodoList($this->db, $args['listId']);
        $todo = new Todo($this->db, '');

        if ( ! $list->exists()) 
        {
            return new StatusResponse($res, "List not found", 404);
        }

        $reqJson = $req->getParsedBody();

        if ( ! isset($reqJson["text"]))
        {
            return new StatusResponse($res, "Bad input format", 400);
        }

        if ( ! $todo->create($args['listId'], $reqJson["text"])) 
        {
            return new StatusResponse($res, "Failed to create todo", 501);
        }

        return new JsonResponse($res, $todo, 201);
    }
}
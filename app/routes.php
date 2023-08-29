<?php

namespace App;

use Slim\Routing\RouteCollectorProxy;

use App\Controller\ListController;
use App\Controller\TodoController;


$app->group('/api/v0/', function(RouteCollectorProxy $group) {

    $group->get('',                      [ListController::class, 'showLists']);

    $group->post('list',                 [ListController::class, 'createList']);
    $group->get('list/{listId}',         [ListController::class, 'getList']);
    $group->put('list/{listId}',         [ListController::class, 'updateList']);
    $group->delete('list/{listId}',      [ListController::class, 'deleteList']);

    $group->post('list/{listId}/todo',   [ListController::class, 'createTodo']);

    $group->get('todo/{todoId}',         [TodoController::class, 'getTodo']);
    $group->put('todo/{todoId}',         [TodoController::class, 'updateTodo']);
    $group->delete('todo/{todoId}',      [TodoController::class, 'deleteTodo']);
});
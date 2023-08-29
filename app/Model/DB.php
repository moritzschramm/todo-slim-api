<?php

namespace App\Model;

use PDO;

class DB {

    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO('mysql:host=db;dbname=indibit', 'root', 'example');
    }

    public function query(string $query, array $params = array())
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);

        return $stmt;
    }

    public function selectOne(string $table, string $id) 
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `".$table."` WHERE `id` = ? LIMIT 1");
        $stmt->execute([$id]);

        if ($stmt->rowCount() === 0) 
        {
            return null;
        } 

        return $stmt->fetchAll()[0];
    }

    public function insert(string $table, array $columns, array $values): string|false
    {
        $params = array_fill(0, count($values), "?");

        $stmt = $this->pdo->prepare("INSERT INTO `".$table."` (". implode(",", $columns) .") VALUES (".implode(",", $params).") RETURNING id");
        $stmt->execute($values);

        return $stmt->fetchColumn();
    }

    public function update(string $table, array $columns, array $values, string $id): bool 
    {
        $params = array();

        foreach($columns as $column) 
        {
            $params[] = "`".$column."`=?";
        }

        $stmt = $this->pdo->prepare("UPDATE `".$table."` SET ".implode(",", $params)." WHERE id = ?");
        return $stmt->execute(array_merge($values, [$id]));
    }

    public function delete(string $table, string $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM `".$table."` WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
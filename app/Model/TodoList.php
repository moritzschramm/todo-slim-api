<?php

namespace App\Model;

use JsonSerializable;

class TodoList implements JsonSerializable {

    const TABLE = 'lists';

    private $properties = array();
    private $db;

    public function __construct(DB $db, string $id = "") 
    {
        $this->db = $db;

        if ($id !== "") 
        {
            $row = $this->db->selectOne(TodoList::TABLE, $id);

            if ($row) 
            {
                $this->properties['id'] = $id;
                $this->properties['name'] = $row['name'];
                $this->properties['created_at'] = $row['created_at'];
                $this->properties['updated_at'] = $row['updated_at'];
            }
        }
    }

    public function jsonSerialize(): mixed
    {
        return $this->properties;
    }

    public static function all(DB $db) 
    {
        $stmt = $db->query("SELECT * FROM `".TodoList::TABLE."`");

        $lists = array();

        foreach($stmt->fetchAll() as $row) 
        {
            $lists[$row['id']] = array(
                'id' => $row['id'],
                'name' => $row['name'],
                'updated_at' => $row['updated_at'],
                'created_at' => $row['created_at'],
            );
        }

        $stmt = $db->query("SELECT * FROM `".Todo::TABLE."`");

        foreach($stmt->fetchAll() as $row) 
        {
            if (!isset($lists[$row['list_id']]['todos'])) 
            {
                $lists[$row['list_id']]['todos'] = array();
            }

            $lists[$row['list_id']]['todos'][] = array(
                'id' => $row['id'],
                'text' => $row['text'],
                'updated_at' => $row['updated_at'],
                'created_at' => $row['created_at'],
                'list_id' => $row['list_id']
            );
        }

        return array_values($lists);
    }

    public function exists() 
    {
        return isset($this->properties['id']);
    }

    public function create(string $name) 
    {
        if (! $this->exists()) 
        {
            $id = $this->db->insert(TodoList::TABLE, ['name'], [$name]);

            if ($id) 
            {
                $rows = $this->db->selectOne(TodoList::TABLE, $id);

                if ($rows) {

                    $this->properties['id'] = $id;
                    $this->properties['name'] = $rows['name'];
                    $this->properties['created_at'] = $rows['created_at'];
                    $this->properties['updated_at'] = $rows['updated_at'];
                    
                    return true;
                }
            }

            return false;
        }
    }

    public function update(string $name) 
    {
        if ($this->exists()) {

            $timestamp = date('Y-m-d H:i:s', time());

            if ($this->db->update(TodoList::TABLE, ['name', 'updated_at'], [$name, $timestamp], $this->properties['id'])) {

                $this->properties['name'] = $name;
                $this->properties['updated_at'] = $timestamp;

                return true;
            }
        }

        return false;
    }

    public function delete() 
    {
        if ($this->db->delete(TodoList::TABLE, $this->properties['id'])) 
        {
            unset($this->properties['id']);

            return true;
        }

        return false;
    }
}
<?php

namespace App\Model;

use JsonSerializable;

class Todo implements JsonSerializable {

    const TABLE = "todos";

    private $properties = array();
    private $db;

    public function __construct(DB $db, string $id = "") 
    {
        $this->db = $db;

        if ($id !== "") 
        {
            $row = $this->db->selectOne(Todo::TABLE, $id);

            if ($row) 
            {
                $this->properties['id'] = $id;
                $this->properties['text'] = $row['text'];
                $this->properties['created_at'] = $row['created_at'];
                $this->properties['updated_at'] = $row['updated_at'];
                $this->properties['list_id'] = $row['list_id'];
            }
        }
    }

    public function jsonSerialize(): mixed
    {
        return $this->properties;
    }

    public function exists() {
        return isset($this->properties['id']);
    }

    public function create(String $listId, String $text) 
    {
        if ( !$this->exists()) 
        {
            $id = $this->db->insert(Todo::TABLE, ['text', 'list_id'], [$text, $listId]);            

            if ($id) 
            {
                $rows = $this->db->selectOne(Todo::TABLE, $id);

                if ($rows) 
                {
                    $this->properties['id'] = $id;
                    $this->properties['text'] = $text;
                    $this->properties['created_at'] = $rows['created_at'];
                    $this->properties['updated_at'] = $rows['updated_at'];
                    $this->properties['list_id'] = $rows['list_id'];

                    return true;
                }
            }
            return false;
        }
    }

    public function update(String $text): bool 
    {
        if($this->exists())
        {
            $timestamp = date('Y-m-d H:i:s', time());

            if ($this->db->update(Todo::TABLE, ['text', 'updated_at'], [$text, $timestamp], $this->properties['id'])) 
            {
                $this->properties['text'] = $text;
                $this->properties['updated_at'] = $timestamp;

                return true;
            }
        }
        return false;
    }

    public function delete(): bool 
    {
        if ($this->db->delete(Todo::TABLE, $this->properties['id'])) 
        {
            unset($this->properties['id']);

            return true;
        }
        return false;
    }
}
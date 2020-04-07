<?php
namespace loginModel\modelAdmin;
use loginModel\Model;
class ModelAdmin extends Model {
	public function getAllWithoutAdmin () {
	    $query = 'SELECT * FROM users WHERE login <> "admin" ORDER BY id';
	    return $this->zapros($query);
    }

    public function getActive () {
        $query = 'SELECT * FROM users WHERE login <> "admin" AND deleted IS EMPTY ORDER BY id';
        return $this->zapros($query);
    }

    public function getDeleted () {
        $query = 'SELECT * FROM users WHERE login <> "admin" AND deleted NOT IS_EMPTY ORDER BY id';
        return $this->zapros($query);
    }

    public function deleteUserId () {
	    $id = $this->args('id');
        $query = 'REPLACE users SET deleted = NOW() WHERE id = ' . $id;
        return $this->zapros($query);
    }

}
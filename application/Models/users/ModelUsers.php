<?php
namespace loginModel\users;
use loginModel\Model;
class ModelUsers extends Model {
	public function getUserById ($id) {
		$query = 'SELECT * FROM users WHERE id = ' . $id . ' AND deleted IS EMPTY';
	}

    public function getUserIdByLogin ($login) {
        $query = 'SELECT * FROM users WHERE login = "' . $login . '"' . ' AND deleted IS EMPTY';
    }
	
}
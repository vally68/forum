<?php

namespace Model\Managers;

use App\Manager;
use App\DAO;

class UserManager extends Manager
{
    // on indique la classe POO et la table correspondante en BDD pour le manager concerné
    protected $className = "Model\Entities\User";
    protected $tableName = "user";

    public function __construct()
    {
        parent::connect();
    }

    public function findOneById($id)
    {
        $sql = "SELECT * FROM user WHERE id_user = :id";

        return $this->getOneOrNullResult(
            DAO::select($sql, ['id' => $id], false),
            $this->className
        );
    }

    public function findAllUsers()
    {
        $sql = "SELECT * FROM user ORDER BY creationDate DESC";

        return $this->getMultipleResults(
            DAO::select($sql),
            $this->className
        );
    }
    public function deleteUser($id)
    {
        $sql = "DELETE FROM user WHERE id_user = :id";
        return DAO::delete($sql, ['id' => $id]);
    }

    public function updateUser($id, $data)
    {
        $fields = [];
        $params = ["id" => $id];

        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[$key] = $value;
        }

        $sql = "UPDATE user SET " . implode(", ", $fields) . " WHERE id_user = :id";

        return DAO::update($sql, $params);
    }

}

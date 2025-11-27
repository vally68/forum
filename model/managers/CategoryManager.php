<?php
namespace Model\Managers;

use App\Manager;
use App\DAO;

class CategoryManager extends Manager{

    // on indique la classe POO et la table correspondante en BDD pour le manager concerné
    protected $className = "Model\Entities\Category";
    protected $tableName = "category";

    public function __construct(){
        parent::connect();
    }

public function update($id, $data)
{
    $sql = "UPDATE " . $this->tableName . "
            SET name = :name
            WHERE id_category = :id";

    DAO::update($sql, [
        "name" => $data["name"],
        "id"   => $id
    ]);
}

}
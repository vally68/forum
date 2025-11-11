<?php
namespace Model\Managers;

use App\Manager;
use App\DAO;

class TopicManager extends Manager{

    protected $className = "Model\Entities\Topic";
    protected $tableName = "topic";

    public function __construct(){
        parent::connect();
    }

    // récupérer tous les topics d'une catégorie spécifique (par son id)
    public function findTopicsByCategory($id) {

    $sql = "SELECT * 
            FROM " . $this->tableName . " 
            WHERE id_category = :id
            ORDER BY creationDate DESC";

    // la requête renvoie plusieurs enregistrements --> getMultipleResults
    return $this->getMultipleResults(
        DAO::select($sql, ['id' => $id]),
        $this->className
    );
}

}

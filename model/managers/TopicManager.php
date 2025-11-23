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
public function findTopicsByCategory($idCategory)
{
    $sql = "
        SELECT 
            t.id_topic, 
            t.title, 
            t.creationDate, 
            
            t.id_category, 
            t.user_id
        FROM topic t
        WHERE t.id_category = :id
        ORDER BY t.creationDate DESC
    ";

    return $this->getMultipleResults(
        DAO::select($sql, ['id' => $idCategory]),
        $this->className
    );
}

}

<?php
namespace Model\Managers;

use App\Manager;
use App\DAO;

class MessageManager extends Manager{

    // on indique la classe POO et la table correspondante en BDD pour le manager concerné
    protected $className = "Model\Entities\Message";
    protected $tableName = "message";

    public function __construct(){
        parent::connect();
    }

    public function findMessagesByTopic($idTopic)
{
    $sql = "
        SELECT *
        FROM message
        WHERE id_topic = :idTopic
        ORDER BY creationDate ASC
    ";

    return $this->getMultipleResults(
        DAO::select($sql, ["idTopic" => $idTopic]),
        $this->className
    );
}
}
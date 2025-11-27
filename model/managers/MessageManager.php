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

public function update($id, $data)
{
    $sql = "UPDATE " . $this->tableName . "
            SET texte = :texte
            WHERE id_message = :id";

    DAO::update($sql, [
        "texte" => $data["texte"],
        "id"    => $id
    ]);
}

public function findLastMessages(int $limit = 10)
{
    // Sécurise le LIMIT (pas de param nommé dans LIMIT)
    $limit = max(1, (int)$limit);

    $sql = "SELECT 
                id_message AS id,
                texte,
                creationDate,
                id_topic
            FROM " . $this->tableName . "
            ORDER BY creationDate DESC
            LIMIT $limit";

    return $this->getMultipleResults(
        DAO::select($sql),   // pas de params
        $this->className
    );
}




}
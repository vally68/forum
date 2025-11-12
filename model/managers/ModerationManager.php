<?php
namespace Model\Managers;

use App\Manager;
use App\DAO;

class ModerationManager extends Manager{

    // on indique la classe POO et la table correspondante en BDD pour le manager concerné
    protected $className = "Model\Entities\Moderation";
    protected $tableName = "moderation";

    public function __construct(){
        parent::connect();
    }
}
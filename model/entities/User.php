<?php
namespace Model\Entities;

use App\Entity;

/*
    En programmation orientée objet, une classe finale (final class) est une classe que vous ne pouvez pas étendre, c'est-à-dire qu'aucune autre classe ne peut hériter de cette classe. En d'autres termes, une classe finale ne peut pas être utilisée comme classe parente.
*/

final class User extends Entity {

    private $id;
    private $nickName;
    private $email;
    private $password;
    private $statut;

    public function __construct($data){         
        $this->hydrate($data);        
    }

    public function getId(){ return $this->id; }
    public function setId($id){ $this->id = $id; return $this; }

    public function getNickName(){ return $this->nickName; }
    public function setNickName($nickName){ $this->nickName = $nickName; return $this; }

    public function getEmail(){ return $this->email; }
    public function setEmail($email){ $this->email = $email; return $this; }

    public function getPassword(){ return $this->password; }
    public function setPassword($password){ $this->password = $password; return $this; }

    public function getStatut(){ return $this->statut; }
    public function setStatut($statut){ $this->statut = $statut; return $this; }

    public function hasRole($role){
        return $this->statut === $role;
    }

    public function __toString() { return $this->nickName; }
}

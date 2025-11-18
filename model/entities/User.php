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
    private $creationDate;
    


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
 public function setStatut($statut){
    // si c’est un tableau, on prend le premier élément
    if (is_array($statut)) {
        $statut = $statut[0] ?? null;
    }

    // si null ou vide, on enregistre null
    if ($statut === null || $statut === '') {
        $this->statut = null;
        return $this;
    }

    // normalisation
    $this->statut = ucfirst(strtolower($statut));

    return $this;
}


    public function getCreationDate(){ return $this->creationDate; }
    public function setCreationDate($creationDate){ $this->creationDate = $creationDate; return $this; }

public function hasRole($role)
{
    // Si aucun statut défini, aucun rôle
    if ($this->statut === null) {
        return false;
    }

    // Normaliser le statut courant
    $current = strtolower($this->statut);

    // 💡 Si $role est un tableau → vérifier si l'un des rôles correspond
    if (is_array($role)) {
        foreach ($role as $r) {
            if ($current === strtolower($r)) {
                return true;
            }
        }
        return false;
    }

    // 💡 Si $role est une simple string
    return $current === strtolower($role);
}



 

    public function __toString() { return $this->nickName; }
    
}

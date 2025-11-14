<?php
namespace Model\Entities;

use App\Entity;

final class Topic extends Entity {

    private $id;
    private $title;
    private $user;
    private $category;
    private $creationDate;
    private $closed;

    public function __construct($data){         
        $this->hydrate($data);        
    }

    /** ID */
    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
        return $this;
    }

    /** TITLE */
    public function getTitle(){
        return $this->title;
    }

    public function setTitle($title){
        $this->title = $title;
        return $this;
    }

    /** USER */
    public function getUser(){
        return $this->user;
    }

    public function setUser($user){
        $this->user = $user;
        return $this;
    }

    /** CATEGORY */
    public function getCategory(){
        return $this->category;
    }

    public function setCategory($category){
        $this->category = $category;
        return $this;
    }

    /** CREATION DATE */
    public function getCreationDate(){
        return $this->creationDate;
    }

    public function setCreationDate($creationDate){
        $this->creationDate = $creationDate;
        return $this;
    }

    /** CLOSED */
    public function isClosed(){
        return $this->closed;
    }

    public function setClosed($closed){
        $this->closed = $closed;
        return $this;
    }

    public function __toString(){
        return $this->title;
    }
}

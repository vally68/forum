<?php
namespace App;

abstract class Manager{

    protected function connect(){
        DAO::connect();
    }

    /**
     * get all the records of a table, sorted by optionnal field and order
     * 
     * @param array $order an array with field and order option
     * @return Collection a collection of objects hydrated by DAO, which are results of the request sent
     */
    public function findAll($order = null){

        $orderQuery = ($order) ?                 
            "ORDER BY ".$order[0]. " ".$order[1] :
            "";

        $sql = "SELECT *
                FROM ".$this->tableName." a
                ".$orderQuery;

        return $this->getMultipleResults(
            DAO::select($sql), 
            $this->className
        );
    }
    
public function findOneById($id) {

    $primaryKey = "id_" . $this->tableName; // ex: id_topic, id_user, id_category

    $sql = "SELECT *
            FROM " . $this->tableName . " a
            WHERE $primaryKey = :id";

    return $this->getOneOrNullResult(
        DAO::select($sql, ['id' => $id], false),
        $this->className
    );
}


    //$data = ['username' => 'Squalli', 'password' => 'dfsyfshfbzeifbqefbq', 'email' => 'sql@gmail.com'];

public function add($data)
{
    $keys = array_keys($data);
    $fields = implode(', ', $keys);
    $placeholders = ':' . implode(', :', $keys);

    $sql = "INSERT INTO {$this->tableName} ($fields) VALUES ($placeholders)";

    try {
        return DAO::insert($sql, $data);
    } catch (\PDOException $e) {
        echo $e->getMessage();
        die();
    }
}

    
   public function delete($id){
    $primaryKey = "id_" . $this->tableName;

    $sql = "DELETE FROM ".$this->tableName."
            WHERE $primaryKey = :id";

    return DAO::delete($sql, ['id' => $id]); 
}



    
protected function getMultipleResults($rows, $class)
{
    if (is_iterable($rows)) {
        $results = [];
        foreach ($rows as $row) {
            $results[] = new $class($row);
        }
        return $results;
    }
    return [];
}



    protected function getOneOrNullResult($row, $class){

        if($row != null){
            return new $class($row);
        }
        return false;
    }

    protected function getSingleScalarResult($row){

        if($row != null){
            $value = array_values($row);
            return $value[0];
        }
        return false;
    }

}
<?php

namespace App;

abstract class Entity
{
    protected function hydrate($data)
    {
        foreach ($data as $field => $value) {

            // --- Gestion de la clé primaire ---
            if ($field === 'id_' . strtolower((new \ReflectionClass($this))->getShortName())) {
                // ex: id_topic → setId()
                $method = 'setId';
            }
            // --- Gestion des clés étrangères ---
            elseif (substr($field, -3) === '_id') {
                $entity = ucfirst(substr($field, 0, -3)); // ex: user_id → User
                $managerClass = "Model\\Managers\\" . $entity . "Manager";
                if (class_exists($managerClass)) {
                    $manager = new $managerClass();
                    $value = $manager->findOneById($value);
                }
                $method = "set" . $entity;
            }
            // --- Champs normaux ---
            else {
                $method = 'set' . ucfirst($field);
            }

            // --- Appel du setter si existant ---
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function getClass()
    {
        return get_class($this);
    }
}

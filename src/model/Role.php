<?php

class Role {

    private $id;
    private $description;

    public function getId() {
        return $this->id;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function toString() {
        return $this->id . ' - ' . $this->description;
    }

}

<?php

class User {

    private $id;
    private $username;
    private $age;
    private $firstname;
    private $surname;
    private $registrationDate;
    private $roles; //array di ruoli associati; 

    public function __construct() {
        $this->roles = [];
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getAge() {
        return $this->age;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function getSurname() {
        return $this->surname;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setAge($age) {
        $this->age = $age;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    public function setSurname($surname) {
        $this->surname = $surname;
    }

    public function getRegistrationDate() {
        return $this->registrationDate;
    }

    public function setRegistrationDate($registrationDate) {
        $this->registrationDate = $registrationDate;
    }

    function getRoles() {
        return $this->roles;
    }

    public function addRole(Role $role) {
        $this->roles[] = $role;
    }

    public function getRole1() {
        if (count($this->roles)) {
            return $this->roles[0]->getDescription();
        }
        return null;
    }

    public function getRole2() {
        if (count($this->roles) > 1) {
            return $this->roles[1]->getDescription();
        }
        return null;
    }

    public function toString() {
        return $this->id . ' - ' . $this->username;
    }

}

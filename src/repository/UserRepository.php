<?php

class UserRepository {

    private $logger;
    private $roleRepository;

    public function __construct() {
        $this->logger = App::getInstance()->getLogger("UserRepository");
        $this->roleRepository = new RoleRepository();
    }

    public function findAll() {
        try {
            $conn = Db::getConnection();
            $sql = 'SELECT * FROM user ORDER BY id DESC';
            $st = $conn->prepare($sql);
            $st->execute();
            $data = $st->fetchAll(PDO::FETCH_ASSOC);
            $st->closeCursor();

            $users = array();
            foreach ($data as $row) {
                $users[] = $this->rawDataToModel($row);
            }
            return $users;
        } catch (Exception $ex) {
            $this->logger->error($ex->getMessage());
            return null;
        }
    }

    public function findByID($id) {
        try {
            $conn = Db::getConnection();
            $sql = 'SELECT * FROM user WHERE id=:id';
            $st = $conn->prepare($sql);
            $st->bindValue(":id", $id, PDO::PARAM_INT);
            $st->execute();
            $data = $st->fetchAll(PDO::FETCH_ASSOC);
            $st->closeCursor();


            if (count($data) === 1) {
                $model = $this->rawDataToModel($data[0]);

                $sql = 'SELECT role.* FROM role INNER JOIN user_role on role.id =user_role.roleId WHERE user_role.userId=:userId';
                $st = $conn->prepare($sql);
                $st->bindValue(":userId", $id, PDO::PARAM_INT);
                $st->execute();
                $rolesRelation = $st->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rolesRelation as $role) {
                    $model->addRole($this->roleRepository->rawDataToModel($role));
                }
                $st->closeCursor();
                return $model;
            }
            return null;
        } catch (Exception $ex) {
            $this->logger->error($ex->getMessage());
            return null;
        }
    }

    public function insert(User $user) {
        $conn = Db::getConnection();
        try {
            $conn->beginTransaction();

            $sql = 'INSERT INTO user 
                        (username, age, firstname, surname, registrationDate) 
                    VALUES 
                        (:username, :age, :firstname, :surname ,:registrationDate )';
            $st = $conn->prepare($sql);
            $st->execute($this->modelToRawData($user));
            $st->closeCursor();

            $lastInsert = $conn->lastInsertId();

            foreach ($user->getRoles() as $role) {
                $sql = 'INSERT INTO user_role 
                        (roleId, userId) 
                    VALUES 
                        (:roleId, :userId)';

                $st = $conn->prepare($sql);
                $st->execute(array('roleId' => $role->getId(), "userId" => $lastInsert));
                $st->closeCursor();
            }


            $conn->commit();

            $this->logger->info("Nuovo utente inserito: " . $user->getUsername());

            return true;
        } catch (Exception $ex) {
            $conn->rollBack();
            $this->logger->error($ex->getMessage());

            return false;
        }
    }

    public function update(User $user) {
        try {
            $conn = Db::getConnection();

            $conn->beginTransaction();

            $sql = 'UPDATE user 
                    SET username=:username, age=:age, firstname=:firstname, surname=:surname, registrationDate= :registrationDate
                    WHERE id=:id';

            $st = $conn->prepare($sql);
            $st->bindValue(":id", $user->getId(), PDO::PARAM_INT);
            $st->bindValue(":username", $user->getUsername(), PDO::PARAM_STR);
            $st->bindValue(":age", $user->getAge(), PDO::PARAM_INT);
            $st->bindValue(":firstname", $user->getFirstname(), PDO::PARAM_STR);
            $st->bindValue(":surname", $user->getSurname(), PDO::PARAM_STR);
            $st->bindValue(":registrationDate", $user->getRegistrationDate(), PDO::PARAM_STR);
            $st->execute();
            $st->closeCursor();

            $conn->commit();

            $this->logger->info("Utente modificato: " . $user->toString());

            return true;
        } catch (Exception $ex) {
            $conn->rollBack();
            $this->logger->error($ex->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $conn = Db::getConnection();

            $conn->beginTransaction();

            $sql = 'DELETE FROM user WHERE id=:id';
            $st = $conn->prepare($sql);
            $st->bindValue(":id", $id, PDO::PARAM_INT);
            $st->execute();
            $st->closeCursor();


            $sql = 'DELETE FROM user_role WHERE userId=:userId';
            $st = $conn->prepare($sql);
            $st->bindValue(":userId", userId, PDO::PARAM_INT);
            $st->execute();
            $st->closeCursor();

            $conn->commit();

            $this->logger->info("User $id eliminato");

            return true;
        } catch (Exception $ex) {
            $conn->rollBack();
            $this->logger->error($ex->getMessage());
            return false;
        }
    }

    private function rawDataToModel($rawData) {
        $user = new User();
        $user->setId($rawData['id']);
        $user->setUsername($rawData['username']);
        $user->setFirstname($rawData['firstname']);
        $user->setAge($rawData['age']);
        $user->setSurname($rawData['surname']);
        $user->setRegistrationDate($rawData['registrationDate']);
        return $user;
    }

    private function modelToRawData(User $model) {
        return array(
            'username' => $model->getUsername(),
            'firstname' => $model->getFirstname(),
            'surname' => $model->getSurname(),
            'age' => $model->getAge(),
            'registrationDate' => $model->getPublicationDate() ? : date('Y-m-d')
        );
    }

}

<?php

class RoleRepository {

    private $logger;

    public function __construct() {
        $this->logger = App::getInstance()->getLogger("RoleRepository");
    }

    public function findAll() {
        try {
            $conn = Db::getConnection();
            $sql = 'SELECT * FROM role ORDER BY id DESC';
            $st = $conn->prepare($sql);
            $st->execute();
            $data = $st->fetchAll(PDO::FETCH_ASSOC);
            $st->closeCursor();

            $roles = array();
            foreach ($data as $row) {
                $roles[] = $this->rawDataToModel($row);
            }
            return $roles;
        } catch (Exception $ex) {
            $this->logger->error($ex->getMessage());
            return null;
        }
    }

    public function findByDescription($description) {
        try {
            $conn = Db::getConnection();
            $sql = 'SELECT * FROM role WHERE description like :description';
            $st = $conn->prepare($sql);
            $st->bindValue(":description", '%' . $description . '%', PDO::PARAM_STR);
            $st->execute();
            $data = $st->fetchAll(PDO::FETCH_ASSOC);
            $st->closeCursor();
            if (count($data) === 1) {
                return $this->rawDataToModel($data[0]);
            }
            return null;
        } catch (Exception $ex) {
            $this->logger->error($ex->getMessage());
            return null;
        }
    }

    public function rawDataToModel($rawData) {
        $role = new Role();
        $role->setId($rawData['id']);
        $role->setDescription($rawData['description']);
        return $role;
    }

    public function insert(Role $role) {
        $conn = Db::getConnection();
        try {
            $conn->beginTransaction();

            $sql = 'INSERT INTO role 
                        (description) 
                    VALUES 
                        (:description )';
            $st = $conn->prepare($sql);
            $st->execute($this->modelToRawData($role));
            $st->closeCursor();

            $conn->commit();

            $this->logger->info("Nuovo ruolo inserito: " . $role->getDescription());

            return true;
        } catch (Exception $ex) {
            $conn->rollBack();
            $this->logger->error($ex->getMessage());

            return false;
        }
    }

    public function update(Role $role) {

        $conn = Db::getConnection();
        $conn->beginTransaction();
        try {
            $sql = 'UPDATE role 
                    SET description=:description WHERE id=:id';

            $st = $conn->prepare($sql);
            $st->bindValue(":id", $role->getId(), PDO::PARAM_INT);
            $st->bindValue(":description", $role->getDescription(), PDO::PARAM_STR);

            $st->execute();
            $st->closeCursor();

            $conn->commit();

            $this->logger->info("Role modificato: " . $role->toString());

            return true;
        } catch (Exception $ex) {
            $conn->rollBack();
            $this->logger->error($ex->getMessage());
            return false;
        }
    }

    private function modelToRawData(Role $model) {
        return array(
            'description' => $model->getDescription()
        );
    }

    public function delete($id) {
        try {
            $conn = Db::getConnection();

            $conn->beginTransaction();

            $sql = 'DELETE FROM role WHERE id=:id';
            $st = $conn->prepare($sql);
            $st->bindValue(":id", $id, PDO::PARAM_INT);
            $st->execute();
            $st->closeCursor();
            $conn->commit();

            $this->logger->info("Role $id eliminato");

            return true;
        } catch (Exception $ex) {
            $conn->rollBack();
            $this->logger->error($ex->getMessage());
            return false;
        }
    }

    public function findByID($id) {
        try {
            $conn = Db::getConnection();
            $sql = 'SELECT * FROM role WHERE id=:id';
            $st = $conn->prepare($sql);
            $st->bindValue(":id", $id, PDO::PARAM_INT);
            $st->execute();
            $data = $st->fetchAll(PDO::FETCH_ASSOC);
            $st->closeCursor();


            if (count($data) === 1) {
                return $this->rawDataToModel($data[0]);
            }
            return null;
        } catch (Exception $ex) {
            $this->logger->error($ex->getMessage());
            return null;
        }
    }

}

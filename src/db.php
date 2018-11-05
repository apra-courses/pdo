<?php

class Db {

    public static function getConnection() {
        static $conn = null;
        if (!$conn) {
            $conn = new PDO(
                    App::getInstance()->getConfig('DB_DNS'), App::getInstance()->getConfig('DB_USERNAME'), App::getInstance()->getConfig('DB_PASSWORD')
            );
        }
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        return $conn;
    }

}

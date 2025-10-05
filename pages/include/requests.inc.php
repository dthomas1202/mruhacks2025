<!-- Requests from link.db
 Use these commands to request data in the forms -->
<?php
// Define connString pointer to link.db
define('connString','sqlite:./database/link.db');
// connection class 
class dataLink {
    public static function createConnection () {
        $pdo = new PDO(connString);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,
                            PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,
                            PDO::FETCH_ASSOC);
        return $pdo;
    }
// stolen from Web 2 labs
    public static function runQuery($connection, $sql, $params){
        $statement = null;
        if (isset($params)){
            if (!is_array($params)) {
                $params = array($params);
            }
            $statement = $connection->prepare($sql);
            $executedOK = $statement->execute($params);
            if (!$executedOK) throw new PDOException;
        }
        return $statement;
    }

}

?>
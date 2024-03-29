<?php

abstract class PDORepository {
    
    const USERNAME = "grupo27";
    const PASSWORD = "wudohz4OhZ";
	const HOST ="localhost";
	const DB = "grupo27";
    
    
    private function getConnection(){
        $u=self::USERNAME;
        $p=self::PASSWORD;
        $db=self::DB;
        $host=self::HOST;
        $connection = new PDO("mysql:dbname=$db;host=$host", $u, $p);
        return $connection;
    }
    
    public function queryList($sql, $args){
        $connection = $this->getConnection();
        $stmt = $connection->prepare($sql);
        $stmt->execute($args);
        $lastId = $connection->lastInsertId();
        $resultado[]=$stmt;
        $resultado[]=$lastId;
        return $resultado;
    }

    public function queryAll($sql){
        $connection = $this->getConnection();
        $stmt = $connection->prepare($sql);
        $stmt->execute();
        return $stmt;
    }
}

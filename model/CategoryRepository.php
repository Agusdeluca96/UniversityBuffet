<?php

class CategoryRepository extends PDORepository {

    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function listAll() {

        $query = $this->queryList("SELECT * FROM categoria", array());
        foreach ($query[0] as $row) {
            $category = new Category($row['id'], $row['nombre']);
            $categories[]=$category;
        }
        return $categories;
    }
}
?>
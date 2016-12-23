<?php

class ConfigRepository extends PDORepository {

    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function config_modify($titulo, $descripcion, $contacto, $mensaje, $paginas) {

        $this->queryList("UPDATE configuracion SET titulo=?, descripcion=?, contacto=?, mensaje=?, paginas=?", array($titulo, $descripcion, $contacto, $mensaje, $paginas));

    }

    public function listAll() {

        $query = $this->queryList("SELECT * FROM configuracion", array());
        foreach ($query[0] as $row) {
            $config = new Config ( $row['id'], $row['titulo'], $row['descripcion'], $row['contacto'], $row['mensaje'], $row['paginas']);
        }
        return $config;
    }

    public function page_size() {

        $query = $this->queryList("SELECT * FROM configuracion", array());
        foreach ($query[0] as $row) {
            $config = new Config ( $row['id'], $row['titulo'], $row['descripcion'], $row['contacto'], $row['mensaje'], $row['paginas']);
        }
        return $config->getPaginas();
    }
}
?>
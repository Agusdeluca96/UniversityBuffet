<?php

class Sale {
    
    private $id;
    private $fecha;

    public function __construct($id, $fecha) {

        $this->id = $id;
        $this->fecha = $fecha;

    }

    public function getId() {
        return $this->id;
    }

    public function getFecha() {
        return $this->fecha;
    }
}
?>
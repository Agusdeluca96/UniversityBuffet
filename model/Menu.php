<?php

class Menu {
    
    private $id;
    private $productos;
    private $fecha;
    private $habilitado;
    private $total;


    public function __construct($id, $productos, $fecha, $habilitado, $total) {

        $this->id = $id;
        $this->productos = $productos;
        $this->fecha = $fecha;
        $this->habilitado = $habilitado;
        $this->total = $total;

    }

    public function getId() {
        return $this->id;
    }

    public function getProductos() {
        return $this->productos;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getHabilitado() {
        return $this->habilitado;
    }

    public function getTotal() {
        return $this->total;
    }

}
?>
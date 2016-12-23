<?php

class ExpenseProduct {
    
    private $id;
    private $compra_id;
    private $producto_id;
    private $cantidad;
    private $precio_unidad;
    
    public function __construct($id, $compra_id, $producto_id, $cantidad, $precio_unidad) {

        $this->id = $id;
        $this->compra_id = $compra_id;
        $this->producto_id = $producto_id;
        $this->cantidad = $cantidad;
        $this->precio_unidad = $precio_unidad;

    }

    public function getId() {
        return $this->id;
    }

    public function getCompraId() {
        return $this->compra_id;
    }

    public function getProductoId() {
        return $this->producto_id;
    }

    public function getCantidad() {
        return $this->cantidad;
    }

    public function getPrecioUnidad() {
        return $this->precio_unidad;
    }
}
?>
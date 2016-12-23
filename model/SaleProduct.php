<?php

class SaleProduct {
    
    private $id;
    private $venta_id;
    private $producto_id;
    private $cantidad;
    private $precio_unitario;

    public function __construct($id, $venta_id, $producto_id, $cantidad, $precio_unitario) {

        $this->id = $id;
        $this->venta_id = $venta_id;
        $this->producto_id = $producto_id;
        $this->cantidad = $cantidad;
        $this->precio_unitario = $precio_unitario;

    }

    public function getId() {
        return $this->id;
    }

    public function getVentaId() {
        return $this->venta_id;
    }

    public function getProductoId() {
        return $this->producto_id;
    }

    public function getCantidad() {
        return $this->cantidad;
    }

    public function getPrecioUnitario() {
        return $this->precio_unitario;
    }
}
?>
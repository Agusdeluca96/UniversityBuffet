<?php

class OrderProduct {
    
    private $id;
    private $pedido_id;
    private $producto;
    private $cantidad;

    public function __construct($id, $pedido_id, $producto, $cantidad) {

        $this->id = $id;
        $this->pedido_id = $pedido_id;
        $this->producto = $producto;
        $this->cantidad = $cantidad;

    }

    public function getId() {
        return $this->id;
    }

    public function getPedidoId() {
        return $this->pedido_id;
    }

    public function getProducto() {
        return $this->producto;
    }

    public function getCantidad() {
        return $this->cantidad;
    }
}
?>
<?php

class Expense {
    
    private $id;
    private $factura;
    private $proveedor_cuit;
    private $fecha;
    private $num_factura;
    
    public function __construct($id, $factura, $proveedor_cuit, $fecha, $num_factura) {

        $this->id = $id;
        $this->factura = $factura;
        $this->proveedor_cuit = $proveedor_cuit;
        $this->fecha = $fecha;
        $this->num_factura = $num_factura;

    }

    public function getId() {
        return $this->id;
    }

    public function getFactura() {
        return $this->num_factura;
    }

    public function getCuit() {
        return $this->proveedor_cuit;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getImagenFactura() {
        return $this->factura;
    }
    
}
?>
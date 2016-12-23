<?php

class Product {
    
    private $id;
    private $nombre;
    private $marca;
    private $stock;
    private $stock_minimo;
    private $categoria;
    private $proveedor;
    private $precio_venta_unitario;
    private $descripcion;
    private $fecha_alta;
    
    public function __construct($id, $nombre, $marca, $stock, $stock_minimo, $categoria, $proveedor, $precio_venta_unitario, $descripcion) {

        $this->id = $id;
        $this->nombre = $nombre;
        $this->marca = $marca;
        $this->stock = $stock;
        $this->stock_minimo = $stock_minimo;
        $this->categoria = $categoria;
        $this->proveedor = $proveedor;
        $this->precio_venta_unitario = $precio_venta_unitario;
        $this->descripcion = $descripcion;
    }

    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getMarca() {
        return $this->marca;
    }

    public function getStock() {
        return $this->stock;
    }

    public function getStockMinimo() {
        return $this->stock_minimo;
    }

    public function getCategoria() {
        return $this->categoria;
    }

    public function getProveedor() {
        return $this->proveedor;
    }

    public function getPrecioVentaUnitario() {
        return $this->precio_venta_unitario;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }
}

?>
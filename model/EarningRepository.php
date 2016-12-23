<?php

class EarningRepository extends PDORepository {

    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function earning_date($date) {
        $fecha = $date->format('Y-m-d H:i:s');
        $fecha1 = date('Y-m-d H:i:s', strtotime($fecha . ' +1 day'));
        $venta_id = $this->queryList("SELECT * FROM venta WHERE fecha BETWEEN ? AND ?", array($fecha, $fecha1));
        $total_ventas=0;
        foreach ($venta_id[0] as $venta) {
            $id = $venta['id'];
            $venta_productos = $this->queryList("SELECT * FROM venta_producto WHERE venta_id = ?", array($id));
            foreach ($venta_productos[0] as $venta_prod) {            
                $cantidad_producto = $venta_prod['cantidad'];
                $precio_producto = $venta_prod['precio_unitario'];
                $total_ventas = $total_ventas+($cantidad_producto*$precio_producto);
            }
        }
        $fecha = $date->format('Y-m-d');
        $compra_id = $this->queryList("SELECT * FROM compra WHERE fecha = ?", array($fecha));
        $total_compras=0;
        foreach ($compra_id[0] as $compra) {
            $id = $compra['id'];
            $compra_productos = $this->queryList("SELECT * FROM compra_producto WHERE compra_id = ?", array($id));
            foreach ($compra_productos[0] as $compra_prod) {            
                $cantidad_producto = $compra_prod['cantidad'];
                $precio_producto = $compra_prod['precio_unitario'];
                $total_compras = $total_compras+($cantidad_producto*$precio_producto);
            }
        }
        $total = $total_ventas - $total_compras;
        $fecha = $date->format('d-m-Y');
        $ganancia = new Earning ($fecha, $total);
        return $ganancia;
    }      

    public function products_count_date($desde, $hasta) {
        $query = $this->queryList("SELECT producto_id, SUM(cantidad) AS cant FROM venta_producto WHERE venta_id IN (SELECT id FROM venta WHERE fecha BETWEEN ? AND ?) GROUP BY producto_id", array($desde, $hasta));
        foreach ($query[0] as $row) {
            $prodId= $row['producto_id'];
            $cantidad = $row['cant'];
            $answer = $this->queryList("SELECT * FROM producto WHERE id = ?", array($prodId));
            $producto = $answer[0]->fetch(PDO::FETCH_ASSOC);
            $summaryProduct = new SummaryProducts($producto['nombre'], $cantidad);
            $summaryProducts[]=$summaryProduct;
        }
        return $summaryProducts;
    }

    public function products_count_date_pages($desde, $hasta, $inicio, $tam_pagina) {
        $query = $this->queryList("SELECT producto_id, SUM(cantidad) AS cant FROM venta_producto WHERE venta_id IN (SELECT id FROM venta WHERE fecha BETWEEN ? AND ?) GROUP BY producto_id LIMIT $inicio, $tam_pagina", array($desde, $hasta));
        foreach ($query[0] as $row) {
            $prodId= $row['producto_id'];
            $cantidad = $row['cant'];
            $answer = $this->queryList("SELECT * FROM producto WHERE id = ?", array($prodId));
            $producto = $answer[0]->fetch(PDO::FETCH_ASSOC);
            $summaryProduct = new SummaryProducts($producto['nombre'], $cantidad);
            $summaryProducts[]=$summaryProduct;
        }
        return $summaryProducts;
    }
}
?>
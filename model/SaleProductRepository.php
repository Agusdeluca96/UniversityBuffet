<?php

class SaleProductRepository extends PDORepository {

    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function listAll() {

        $query = $this->queryList("SELECT * FROM venta_producto", array());
        foreach ($query as $row) {
            $venta_producto = new SaleProduct($row['id'], $row['venta_id'], $row['producto_id'], $row['cantidad'], $row['precio_unitario']);
            $ventas_productos[]=$venta_producto;
        }
        return $ventas_productos;
    }

    public function sale_modify_check($id) {
        $productos = null;
        $query = $this->queryList("SELECT * FROM venta_producto WHERE venta_id = ?", array($id));
        foreach ($query[0] as $row) {
            $producto = new SaleProduct($row['id'], $row['venta_id'], $row['producto_id'], $row['cantidad'], $row['precio_unitario']);
            $productos[]=$producto;
        }
        return $productos;
    }
}
?>
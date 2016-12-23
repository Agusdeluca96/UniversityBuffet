<?php

class ExpenseProductRepository extends PDORepository {

    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function listAll() {

        $query = $this->queryList("SELECT * FROM compra_producto", array());
        foreach ($query[0] as $row) {
            $compra_producto = new ExpenseProduct($row['id'], $row['compra_id'], $row['producto_id'], $row['cantidad'], $row['precio_unidad']);
            $compras_productos[]=$compra_producto;
        }
        return $compras_productos;
    }

    public function expense_product_add($compra_id, $producto_id, $cantidad, $precio_unidad) {

        $this->queryList("INSERT INTO compra_producto (compra_id, producto_id, cantidad, precio_unidad) VALUES (?,?,?,?')", array($compra_id, $producto_id, $cantidad, $precio_unidad));
        
    }

    public function expense_modify_check($id) {

        $query = $this->queryList("SELECT * FROM compra_producto WHERE compra_id = ?", array($id));
        foreach ($query[0] as $row) {
            $producto = new ExpenseProduct($row['id'], $row['compra_id'], $row['producto_id'], $row['cantidad'], $row['precio_unitario']);
            $productos[]=$producto;
        }
        return $productos; 
    }
}
?>
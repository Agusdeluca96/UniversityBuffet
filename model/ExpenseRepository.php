<?php

class ExpenseRepository extends PDORepository {

    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function listAll($inicio, $tam_pagina) {

        $compras=null;
        $query = ExpenseRepository::getInstance()->queryList("SELECT * FROM compra LIMIT $inicio, $tam_pagina", array());
        foreach ($query[0] as $row) {
            $compra = new Expense($row['id'], $row['factura'], $row['proveedor_cuit'], $row['fecha'], $row['num_factura']);
            $compras[]=$compra;
        }
        return $compras;
    }

    public function expense_add($fecha, $proveedor_cuit, $factura, $productos, $cantidad, $precio, $num_factura) {

        $query = self::queryList("INSERT INTO compra (fecha, proveedor_cuit, factura, num_factura) VALUES (?,?,?,?)", array($fecha, $proveedor_cuit, $factura, $num_factura));
        $compra_id=$query[1];
        foreach ($productos as $index => $value) {
            self::queryList("INSERT INTO compra_producto (compra_id, producto_id, cantidad, precio_unitario) VALUES (?,?,?,?)", array($compra_id, $productos[$index], $cantidad[$index], $precio[$index]));
            self::queryList("UPDATE producto SET stock=stock+? WHERE id = ?", array($cantidad[$index], $productos[$index]));
        }

    }

    public function anyExpense($id) {
        $res = self::getInstance()->queryList("SELECT * FROM compra_producto WHERE producto_id = ? ", array($id));
        $resul = $res[0]->fetch(PDO::FETCH_ASSOC);
        $resultado = true;
        if($resul['producto_id'] == $id){
            $resultado = false; 
        }
        return $resultado;
    }

    public function countExpenses() {
        $res = self::getInstance()->queryList("SELECT COUNT(*) FROM compra", array());
        return $res;
    }

    public function expense_delete($id) {

        $query2 = ProductRepository::getInstance()->queryList("SELECT * FROM compra_producto WHERE compra_id = ?", array($id));
        self::queryList("DELETE FROM compra_producto WHERE compra_id = ?", array($id));
        self::queryList("DELETE FROM compra WHERE id = ?", array($id));
        foreach ($query2[0] as $producto) { 
            self::queryList("UPDATE producto SET stock=stock-? WHERE id = ?", array($producto['cantidad'], $producto['producto_id']));
        }
    }

    public function expense_modify($compra_id, $num_factura, $factura, $proveedor_cuit, $fecha, $productos, $cantidad, $precio) {

        $query = ProductRepository::getInstance()->queryList("SELECT * FROM compra_producto WHERE compra_id = ?", array($compra_id));
        self::queryList("DELETE FROM compra_producto WHERE compra_id = ?", array($compra_id));
        foreach ($query[0] as $producto) { 
            self::queryList("UPDATE producto SET stock=stock-? WHERE id = ?", array($producto['cantidad'], $producto['producto_id']));
        }
        self::queryList("UPDATE compra SET factura=?, proveedor_cuit=?, fecha=?, num_factura=? WHERE id = ?", array($factura, $proveedor_cuit, $fecha, $num_factura, $compra_id));
        foreach ($productos as $index => $value) {
            self::queryList("INSERT INTO compra_producto (compra_id, producto_id, cantidad, precio_unitario) VALUES (?,?,?,?)", array($compra_id, $productos[$index], $cantidad[$index], $precio[$index]));
            self::queryList("UPDATE producto SET stock=stock+? WHERE id = ?", array($cantidad[$index], $productos[$index]));
        }
    }

    public function expense_modify_check($id) {

        $query = ExpenseRepository::getInstance()->queryList("SELECT * FROM compra WHERE id = ?", array($id));
        foreach ($query[0] as $row) {
            $expense = new Expense($row['id'], $row['factura'], $row['proveedor_cuit'], $row['fecha'], $row['num_factura']);
            $expenses[]=$expense;
        }
        return $expenses;
    }
}
?>
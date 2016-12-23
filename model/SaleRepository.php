<?php

class SaleRepository extends PDORepository {

    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function listAll($inicio, $tam_pagina) {
        $ventas = null;
        $query = $this->queryList("SELECT * FROM venta LIMIT $inicio, $tam_pagina", array());
        foreach ($query[0] as $row) {
            $venta = new Sale($row['id'], $row['fecha']);
            $ventas[]=$venta;
        }
        return $ventas;
    }

    public function countSales() {

        $cant = $this->queryList("SELECT COUNT(*) FROM venta ", array());
        return $cant;
    }

    public function sale_add($productos, $cantidades) {

        $query = self::queryList("INSERT INTO venta (codigo) VALUES (1)", array());
        $venta_id= $query[1];
        foreach ($productos as $index => $value) {
            $query2 = $this->queryList("SELECT * FROM producto WHERE id = ?", array($productos[$index]));
            foreach ($query2[0] as $row) {
                $precio=$row['precio_venta_unitario'];
            }
            self::queryList("INSERT INTO venta_producto (venta_id, producto_id, cantidad, precio_unitario) VALUES (?,?,?,?)", array($venta_id, $productos[$index], $cantidades[$index], $precio));
            self::queryList("UPDATE producto SET stock=stock-? WHERE id = ?", array($cantidades[$index], $productos[$index]));
        }
    }

    public function anySale($id) {
        $res = $this->queryList("SELECT * FROM venta_producto WHERE producto_id = ? ", array($id));
        $resul = $res[0]->fetch(PDO::FETCH_ASSOC);
        $resultado = true;
        if($resul['producto_id'] == $id){
            $resultado = false; 
        }
        return $resultado;
    }


    public function sale_order_add($productos, $cantidades) {

        $query = $this->queryList("INSERT INTO venta (codigo) VALUES (1)", array());
        $venta_id=$query[1];
        
        foreach ($productos as $index => $value) {
            $query2 = $this->queryList("SELECT * FROM producto WHERE id = ?", array($productos[$index]));
            foreach ($query2[0] as $row) {
                $precio=$row['precio_venta_unitario'];
            }
            $this->queryList("INSERT INTO venta_producto (venta_id, producto_id, cantidad, precio_unitario) VALUES (?,?,?,?)", array($venta_id, $productos[$index], $cantidades[$index], $precio));
        }
    }


    public function sale_delete($id) {
        $query2 = $this->queryList("SELECT * FROM venta_producto WHERE venta_id = ?", array($id));
        $this->queryList("DELETE FROM venta_producto WHERE venta_id = ?", array($id));
        $this->queryList("DELETE FROM venta WHERE id = ?", array($id));
        foreach ($query2[0] as $producto) { 
            $this->queryList("UPDATE producto SET stock=stock+? WHERE id = ?", array($producto['cantidad'], $producto['producto_id']));
        }    
    }

    public function sale_modify($venta_id, $productos, $cantidad) {
        $precios[] = null;
        $query = $this->queryList("SELECT * FROM venta_producto WHERE venta_id = ?", array($venta_id));
        $this->queryList("DELETE FROM venta_producto WHERE venta_id = ?", array($venta_id));
        foreach ($query[0] as $producto) { 
            $this->queryList("UPDATE producto SET stock=stock+? WHERE id = ?", array($producto['cantidad'], $producto['producto_id']));
        }
        $this->queryList("UPDATE venta SET codigo=1 WHERE id = ?", array($venta_id));
        foreach ($productos as $index => $value) {
            $query2 = $this->queryList("SELECT precio_venta_unitario FROM producto WHERE id = ?", array($productos[$index]));
            $precio_unitario = 0;
            foreach ($query2[0] as $row) {
                $precio_unitario = $row['precio_venta_unitario'];
            }
            $this->queryList("INSERT INTO venta_producto (venta_id, producto_id, cantidad, precio_unitario) VALUES (?,?,?,?)", array($venta_id, $productos[$index], $cantidad[$index], $precio_unitario));
            $this->queryList("UPDATE producto SET stock=stock-? WHERE id = ?", array($cantidad[$index], $productos[$index]));
        }
    }

    public function sale_modify_check($id) {

        $query = $this->queryList("SELECT * FROM venta WHERE id = ?", array($id));
        foreach ($query[0] as $row) {
            $sale = new Sale($row['id'], $row['fecha']);
            $sales[]=$sale;
        }
        return $sales;
    }
}
?>
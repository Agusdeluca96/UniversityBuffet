<?php

class ProductRepository extends PDORepository {

    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function addProduct($nombre, $marca, $stock, $cantMin, $categoria, $proveedor, $precio, $descripcion) {

        $this->queryList("INSERT INTO producto (nombre, marca, stock, stock_minimo, categoria, proveedor, precio_venta_unitario, descripcion) VALUES (?,?,?,?,?,?,?,?)", array($nombre, $marca, $stock, $cantMin, $categoria, $proveedor, $precio, $descripcion));
    }


    public function product_delete($id) {

        $this->queryList("DELETE FROM producto WHERE id = ?", array($id));
    }

    public function product_modify($id, $nombre, $marca, $stock, $stock_minimo, $categoria, $proveedor, $precio_venta_unitario, $descripcion) {

        $this->queryList("UPDATE producto SET nombre=?, marca=?, stock=?, stock_minimo=?, categoria=?, proveedor=?, precio_venta_unitario=?, descripcion=? WHERE id = ?", array($nombre, $marca, $stock, $stock_minimo, $categoria, $proveedor, $precio_venta_unitario, $descripcion, $id));
    }

    public function product_modify_check($id) {

        $query = $this->queryList("SELECT * FROM producto WHERE id = ?", array($id));
        foreach ($query[0] as $row) {
            $product = new Product($row['id'], $row['nombre'], $row['marca'], $row['stock'], $row['stock_minimo'], $row['categoria'], $row['proveedor'], $row['precio_venta_unitario'], $row['descripcion']);
            $products[]=$product;
        }
        return $products;
    }

    public function product_view($id) {

        $query = $this->queryList("SELECT * FROM producto WHERE id = ?", array($id));
        foreach ($query[0] as $row) {
            $product = new Product($row['id'], $row['nombre'], $row['marca'], $row['stock'], $row['stock_minimo'], $row['categoria'], $row['proveedor'], $row['precio_venta_unitario'], $row['descripcion']);
            $products[]=$product;
        }
        return $products;
    }

    public function in_stock($productoId, $cantidad) {

        $query = $this->queryList("SELECT stock FROM producto WHERE id = ?", array($productoId));
        $resultado=false;
        foreach ($query[0] as $row) {
            $stock=$row['stock'];
        }
        if ($stock >= $cantidad){
            $resultado=true;
        }
        return $resultado;
    }

    public function daily_menu_products($fecha) {
        $query = $this->queryList("SELECT * FROM menu_del_dia WHERE fecha = ? AND habilitado = 1", array($fecha));
        $menu_id = null;
        foreach ($query[0] as $row) {
            $menu_id=$row['id'];
        }

        $query2 = $this->queryList("SELECT * FROM producto WHERE id IN (SELECT producto_id FROM menu_del_dia_producto WHERE menu_id = ?)", array($menu_id));
        $products = null;
        foreach ($query2[0] as $row) {
            $product = new Product($row['id'], $row['nombre'], $row['marca'], $row['stock'], $row['stock_minimo'], $row['categoria'], $row['proveedor'], $row['precio_venta_unitario'], $row['descripcion']);
            $products[]=$product;
        }
        return $products;
     }

    public function listAll() {

        $query = $this->queryList("SELECT * FROM producto", array());
        foreach ($query[0] as $row) {
            $product = new Product ( $row['id'], $row['nombre'], $row['marca'], $row['stock'], $row['stock_minimo'], $row['categoria'], $row['proveedor'], $row['precio_venta_unitario'], $row['descripcion']);
            $products[]=$product;
        }
        return $products;
    }

    public function list_stock() {

        $query = $this->queryList("SELECT * FROM producto WHERE stock > 0", array());
        foreach ($query[0] as $row) {
            $product = new Product ( $row['id'], $row['nombre'], $row['marca'], $row['stock'], $row['stock_minimo'], $row['categoria'], $row['proveedor'], $row['precio_venta_unitario'], $row['descripcion']);
            $products[]=$product;
        }
        return $products;
    }

    public function listAll_pages($inicio, $tam_pagina) {

        $products = null;
        $query = $this->queryList("SELECT * FROM producto LIMIT $inicio, $tam_pagina", array());
        foreach ($query[0] as $row) {
            $product = new Product ( $row['id'], $row['nombre'], $row['marca'], $row['stock'], $row['stock_minimo'], $row['categoria'], $row['proveedor'], $row['precio_venta_unitario'], $row['descripcion']);
            $products[]=$product;
        }
        return $products;
    }

    public function countProducts() {
        $res = $this->queryList("SELECT COUNT(*) FROM producto", array());
        return $res;
    }

    public function countProductsLowStock() {
        $res = $this->queryList("SELECT COUNT(*) FROM producto WHERE stock < stock_minimo", array());
        return $res;
    }

    public function countProductsLimitStock() {
        $res = $this->queryList("SELECT COUNT(*) FROM producto WHERE stock = stock_minimo", array());
        return $res;
    }

    public function list_limit_stock($inicio, $tam_pagina) {
        
        $products= null;
        $query = $this->queryList("SELECT * FROM producto WHERE stock = stock_minimo LIMIT $inicio, $tam_pagina", array());
        foreach ($query[0] as $row) {
            $product = new Product($row['id'], $row['nombre'], $row['marca'], $row['stock'], $row['stock_minimo'], $row['categoria'], $row['proveedor'], $row['precio_venta_unitario'], $row['descripcion']);
            $products[]=$product;
        }
        return $products;
    }

    public function list_low_stock($inicio, $tam_pagina) {
        
        $products=null;
        $query = $this->queryList("SELECT * FROM producto WHERE stock < stock_minimo LIMIT $inicio, $tam_pagina", array());
        foreach ($query[0] as $row) {
            $product = new Product($row['id'], $row['nombre'], $row['marca'], $row['stock'], $row['stock_minimo'], $row['categoria'], $row['proveedor'], $row['precio_venta_unitario'], $row['descripcion']);
            $products[]=$product;
        }
        return $products;
    }
}
?>
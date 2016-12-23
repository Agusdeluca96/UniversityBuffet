<?php

class MenuRepository extends PDORepository {

    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function menu_add($productos_id, $fecha) {

        $query = $this->queryList("INSERT INTO menu_del_dia (fecha, habilitado) VALUES (?,0)", array($fecha));
        $menu_id=$query[1];
        foreach ($productos_id as $prod) {
            $this->queryList("INSERT INTO menu_del_dia_producto (producto_id, menu_id) VALUES (?,?)", array($prod, $menu_id));
        }
    }

    public function menu_delete($id) {

        $this->queryList("DELETE FROM menu_del_dia_producto WHERE menu_id = ?", array($id));      
        $this->queryList("DELETE FROM menu_del_dia WHERE id = ?", array($id));
    }

    public function anyMenu($id) {
        $res = $this->queryList("SELECT * FROM menu_del_dia_producto WHERE producto_id = ? ", array($id));
        $resul = $res[0]->fetch(PDO::FETCH_ASSOC);
        $resultado = true;
        if($resul['producto_id'] == $id){
            $resultado = false; 
        }
        return $resultado;
    }

    public function menu_list($inicio, $tam_pagina) {

        $menues = null;
        $query = $this->queryList("SELECT * FROM menu_del_dia ORDER BY fecha LIMIT $inicio, $tam_pagina", array());
        foreach ($query[0] as $row) {
            $productos_menu= null;
            $total = 0;
            $menu = new Menu($row['id'], $productos_menu, $row['fecha'], $row['habilitado'], $total);
            $menues[]=$menu;
        }
        return $menues;
    }

    public function getDailyMenu($fecha) {
        $menu = null;
        $query = $this->queryList("SELECT * FROM menu_del_dia WHERE fecha = ? AND habilitado = 1", array($fecha));
        $res= null;
        foreach ($query[0] as $row) {
            $res=$row['fecha'];
        }
        if (!(is_null($res))){
            $menu_id=$row['id'];
            $habilitado=$row['habilitado'];
            $productos_id_menu = $this->queryList("SELECT * FROM menu_del_dia_producto WHERE menu_id = ?", array($menu_id));
            $total = 0;
            foreach ($productos_id_menu[0] as $row) {
                $producto_menu = $this->queryList("SELECT * FROM producto WHERE id = ?", array($row['producto_id']));
                foreach ($producto_menu[0] as $prod_menu){
                    $producto = new Product($prod_menu['id'], $prod_menu['nombre'], $prod_menu['marca'], $prod_menu['stock'], $prod_menu['stock_minimo'], $prod_menu['categoria'], $prod_menu['proveedor'], $prod_menu['precio_venta_unitario'], $prod_menu['descripcion']);
                    $productos_menu[]=$producto;
                    $total = $total + $prod_menu['precio_venta_unitario'];
                }
            }
            $menu = new Menu($menu_id, $productos_menu, $fecha, $habilitado, $total);
        }
        return $menu;
    }

    public function countMenus() {
        $res = $this->queryList("SELECT COUNT(*) FROM menu_del_dia", array());
        return $res;
    }

    public function menu_modify_check($menu_id) {

        $query = $this->queryList("SELECT * FROM menu_del_dia WHERE id = ?", array($menu_id));  
        foreach ($query[0] as $row) {
            $menu_id=$row['id'];
            $productos_menu= null;
            $productos_id_menu = $this->queryList("SELECT producto_id FROM menu_del_dia_producto WHERE menu_id = ?", array($menu_id));
            $total = 0;
            foreach ($productos_id_menu[0] as $row2) {
                $producto_menu = $this->queryList("SELECT * FROM producto WHERE id = ?", array($row2['producto_id']));
                
                foreach ($producto_menu[0] as $row3) {
                    $producto = new Product($row3['id'], $row3['nombre'], $row3['marca'], $row3['stock'], $row3['stock_minimo'], $row3['categoria'], $row3['proveedor'], $row3['precio_venta_unitario'], $row3['descripcion']);
                    $productos_menu[]=$producto;
                    $total = $total + $row3['precio_venta_unitario'];
                }
            }
            $resultado[] = $productos_menu;
            $menu = new Menu($row['id'], $productos_menu, $row['fecha'], $row['habilitado'], $total);
        }
        $resultado[] = $menu;
        return $resultado;
    }

    public function menu_modify($menu_id, $productos, $fecha, $habilitado) {

        $this->queryList("DELETE FROM menu_del_dia_producto WHERE menu_id = ?", array($menu_id));

        $this->queryList("UPDATE menu_del_dia SET fecha=?, habilitado=? WHERE id = ?", array($fecha, $habilitado, $menu_id));

        foreach ($productos as $prod) {
            $this->queryList("INSERT INTO menu_del_dia_producto (producto_id, menu_id) VALUES (?,?)", array($prod, $menu_id));
        }
    }

    public function otherEnabledMenuFor($fecha, $id){
        $res = $this->queryList("SELECT * FROM menu_del_dia WHERE fecha = ? AND habilitado = 1 AND id <> ? ", array($fecha, $id));
        $resul = $res[0]->fetch(PDO::FETCH_ASSOC);
        $resultado = false;
        if($resul['fecha'] == $fecha){
            $resultado = true; 
        }
        return $resultado;
    }

}
?>
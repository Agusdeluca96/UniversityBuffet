<?php

class OrderRepository extends PDORepository {

    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function listPending_pages($inicio, $tam_pagina) {
        $orders= null;
        $query = $this->queryList("SELECT * FROM pedido LIMIT $inicio, $tam_pagina", array());
        foreach ($query[0] as $row) {
            $order = new Order ( $row['id'], $row['estado'], $row['fecha_alta'], $row['usuario_id'], $row['observaciones'], 0);
            $orders[]=$order;
        }
        return $orders;
    }

   public function listUser_pages($inicio, $tam_pagina, $desde, $hasta, $user_id) {
        $query = $this->queryList("SELECT * FROM pedido WHERE usuario_id=? AND fecha_alta BETWEEN ? AND ? LIMIT $inicio, $tam_pagina", array($user_id, $desde, $hasta));
        $orders = null;
        foreach ($query[0] as $row) {
            $hora_inicio=strtotime($row['fecha_alta']);
            $hora_actual=strtotime(date('Y-m-d H:m:s'));
            $tiempo=($hora_actual-$hora_inicio)/60;
            $order = new Order ( $row['id'], $row['estado'], $row['fecha_alta'], $row['usuario_id'], $row['observaciones'], $tiempo);
            $orders[]=$order;
        }
        return $orders;
    }

    public function countOrders() {

        $cant = $this->queryList("SELECT COUNT(*) FROM pedido", array());
        return $cant;
    }

    public function countOrdersUser($user_id, $desde, $hasta) {

        $cant = $this->queryList("SELECT COUNT(*) FROM pedido WHERE usuario_id=? AND fecha_alta BETWEEN ? AND ?", array($user_id, $desde, $hasta));
        return $cant;
    }

    public function order_add($user_id, $observaciones, $fecha_alta, $productos, $cantidad) {

        $query = $this->queryList("INSERT INTO pedido (estado, fecha_alta, usuario_id, observaciones) VALUES (0,?,?,?)", array($fecha_alta, $user_id, $observaciones));
        $pedido_id = $query[1];
        foreach ($productos as $index => $value) {
            if ($cantidad[$index] != 0){
                $this->queryList("INSERT INTO pedido_detalle (pedido_id, producto_id, cantidad) VALUES (?,?,?)", array($pedido_id, $productos[$index], $cantidad[$index]));
                $this->queryList("UPDATE producto SET stock=stock-? WHERE id = ?", array($cantidad[$index], $productos[$index]));
            }
        }
    }

    public function order_view($id) {

        $pedido = $this->queryList("SELECT * FROM pedido WHERE id = ?", array($id));
        foreach ($pedido[0] as $row) {
            $hora_inicio=strtotime($row['fecha_alta']);
            $hora_actual=strtotime(date('Y-m-d H:m:s'));
            $tiempo=($hora_actual-$hora_inicio)/60;
            $productos_pedido = $this->queryList("SELECT * FROM pedido_detalle WHERE pedido_id = ?", array($id));
            foreach ($productos_pedido[0] as $row3) {
                $query = ProductRepository::getInstance()->queryList("SELECT * FROM producto WHERE id = ?", array($row3['producto_id']));
                foreach ($query[0] as $row2) {
                    $producto = new Product($row2['id'], $row2['nombre'], $row2['marca'], $row2['stock'], $row2['stock_minimo'], $row2['categoria'], $row2['proveedor'], $row2['precio_venta_unitario'], $row2['descripcion']);
                }
                $orderProd = new OrderProduct($row3['id'], $row3['pedido_id'], $producto, $row3['cantidad']);
                $ordersProd[] = $orderProd;
             }
            $order = new Order ( $row['id'], $row['estado'], $row['fecha_alta'], $row['usuario_id'], $row['observaciones'], $tiempo);
        }
        $res[] = $ordersProd;
        $res[] = $order;
        return $res;
    }

    public function order_cancel_check ($id) {

        $query = $this->queryList("SELECT * FROM pedido WHERE id = ?", array($id));
        $order= null;
        foreach ($query[0] as $row) {
            $order = new Order ( $row['id'], $row['estado'], $row['fecha_alta'], $row['usuario_id'], $row['observaciones'], 0);
        }
        return $order;
    }

    public function order_accept ($pedido_id) {
 
        $this->queryList("UPDATE pedido SET estado=1 WHERE id = ?", array($pedido_id));

        $order = $this->queryList("SELECT producto_id, cantidad FROM pedido_detalle WHERE pedido_id = ?", array($pedido_id));
        foreach ($order[0] as $row) {
            $productos[] = $row['producto_id'];
            $cantidades[] = $row['cantidad'];
        }
        $orders[] = $productos;
        $orders[] = $cantidades;
        return $orders;
    }

    public function anyOrderFromUser($id) {
        $res = $this->queryList("SELECT * FROM pedido WHERE usuario_id = ?", array($id));
        $resul = $res[0]->fetch(PDO::FETCH_ASSOC);
        $resultado = true;
        if($resul['usuario_id'] == $id){
            $resultado = false; 
        }
        return $resultado;
    }

    public function anyOrder($id) {
        $res = $this->queryList("SELECT * FROM pedido WHERE producto_id = ? ", array($id));
        $resul = $res[0]->fetch(PDO::FETCH_ASSOC);
        $resultado = true;
        if($resul['producto_id'] == $id){
            $resultado = false; 
        }
        return $resultado;
    }

    public function isPendingOrder($id) {
        $res = $this->queryList("SELECT * FROM pedido WHERE id = ? AND estado = 0", array($id));
        $resul = $res[0]->fetch(PDO::FETCH_ASSOC);
        $resultado = false;
        if($resul['id'] == $id){
            $resultado = true; 
        }
        return $resultado;
    }

    public function order_cancel($pedido_id, $observaciones) {

        $query = $this->queryList("SELECT observaciones FROM pedido WHERE id=?", array($pedido_id));
        foreach ($query[0] as $row) {
            $motivo = $row['observaciones'] . ' - Motivo Cancelacion:  ' . $observaciones;
        }
        $this->queryList("UPDATE pedido SET estado=2, observaciones= ? WHERE id = ?", array($motivo, $pedido_id));

        $producto = $this->queryList("SELECT producto_id, cantidad FROM pedido_detalle WHERE pedido_id = ?", array($pedido_id));
        foreach ($producto[0] as $row) {
            $this->queryList("UPDATE producto SET stock=stock+? WHERE id = ?", array($row['cantidad'], $row['producto_id']));
        }

    }
}
?>
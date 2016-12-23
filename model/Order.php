<?php

class Order {
    
    private $id;
    private $estado;
    private $fecha_alta;
    private $usuario_id;
    private $observaciones;
    private $tiempo;

    public function __construct($id, $estado, $fecha_alta, $usuario_id, $observaciones, $tiempo) {

        $this->id = $id;
        $this->estado = $estado;
        $this->fecha_alta = $fecha_alta;
        $this->usuario_id = $usuario_id;
        $this->observaciones = $observaciones;
        $this->tiempo = $tiempo;

    }

    public function getId() {
        return $this->id;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getFechaAlta() {
        return $this->fecha_alta;
    }

    public function getUsuarioId() {
        return $this->usuario_id;
    }

    public function getObservaciones() {
        return $this->observaciones;
    }

    public function getTiempo() {
        return $this->tiempo;
    }
    
}
?>
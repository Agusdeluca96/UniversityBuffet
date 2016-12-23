<?php

class Earning {
    
    private $fecha;
    private $monto;
    
    public function __construct($fecha, $monto) {

        $this->fecha = $fecha;
        $this->monto = $monto;

    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getMonto() {
        return $this->monto;
    }
    
}
?>
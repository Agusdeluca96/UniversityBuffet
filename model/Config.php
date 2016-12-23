<?php

class Config {
    
    private $id;
    private $titulo;
    private $descripcion;
    private $contacto;
    private $mensaje;
    private $paginas;
    
    public function __construct($id, $titulo, $descripcion, $contacto, $mensaje, $paginas) {

        $this->id = $id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->contacto = $contacto;
        $this->mensaje = $mensaje;
        $this->paginas = $paginas;
    }

    public function getId() {
        return $this->id;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getContacto() {
        return $this->contacto;
    }

    public function getMensaje() {
        return $this->mensaje;
    }

    public function getPaginas() {
        return $this->paginas;
    }
}

?>
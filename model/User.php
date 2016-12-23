<?php

class User {
    
    private $id;
    private $usuario;
    private $clave;
    private $nombre;
    private $apellido;
    private $documento;
    private $email;
    private $telefono;
    private $rol;
    private $habilitado;
    private $ubicacion_id;
    private $departamento;
    
    public function __construct($id, $usuario, $clave, $nombre, $apellido, $documento, $email, $telefono, $rol, $habilitado, $ubicacion_id, $departamento) {

        $this->id = $id;
        $this->usuario = $usuario;
        $this->clave = $clave;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->documento = $documento;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->rol = $rol;
        $this->habilitado = $habilitado;
        $this->ubicacion_id = $ubicacion_id;
        $this->departamento = $departamento;
    }

    public function getId() {
        return $this->id;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function getClave() {
        return $this->clave;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function getDocumento() {
        return $this->documento;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getRol() {
        return $this->rol;
    }

     public function getHabilitado() {
        return $this->habilitado;
    }

    public function getUbicacionId() {
        return $this->ubicacion_id;
    }

    public function getDepartamento() {
        return $this->departamento;
    }

}

?>
<?php

class UserRepository extends PDORepository {

    private static $instance;

    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function login_user($username, $password) {

        if(!is_null($username) AND !is_null($password)){
            $array = array(
                ':username' => $username,
                ':password' => $password
            );

            $res = $this->queryList("SELECT * FROM usuario WHERE usuario = ? AND clave = ?", array($username, $password));
            $user = $res[0]->fetch(PDO::FETCH_ASSOC);
            if(($user['usuario'] == $username) && ($user['clave'] == $password)){     
                if($user['habilitado']==1){
                    $data[0] = true;
                    $data[1] = $user['rol'];
                    $data[2] = $user['usuario'];
                    $data[3] = $user['id'];
                    $res = null;
                    return $data;
                }
                else{
                    return $data[0] = false;
                }
            }
            else{
                return $data[0] = false;
            }
        }
    }

    public function countUsers() {

        $cant = $this->queryList("SELECT COUNT(*) FROM usuario", array());
        return $cant;
    }

    public function user_add($usuario, $clave, $nombre, $apellido, $documento, $email, $telefono, $rol, $habilitado, $ubicacion, $departamento) {

        $this->queryList("INSERT INTO usuario (usuario, clave, nombre, apellido, documento, email, telefono, rol, habilitado, ubicacion, departamento) VALUES (?,?,?,?,?,?,?,?,?,?,?)", array($usuario, $clave, $nombre, $apellido, $documento, $email, $telefono, $rol, $habilitado, $ubicacion, $departamento));
    }

    public function user_delete($id) {

        $this->queryList("DELETE FROM usuario WHERE id = ?", array($id));       
    }

    public function user_modify($usuario, $clave, $nombre, $apellido, $documento, $email, $telefono, $rol, $habilitado, $ubicacion, $departamento, $id) {

        $this->queryList("UPDATE usuario SET usuario=?, clave=?, nombre=?, apellido=?, documento=?, email=?, telefono=?, rol=?, habilitado=?, ubicacion=?, departamento=? WHERE id = ?", array($usuario, $clave, $nombre, $apellido, $documento, $email, $telefono, $rol, $habilitado, $ubicacion, $departamento, $id));
    }

    public function user_modify_check($id) {

        $query = $this->queryList("SELECT * FROM usuario WHERE id = ?", array($id));
        foreach ($query[0] as $row) {
            $user = new User($row['id'], $row['usuario'], $row['clave'], $row['nombre'], $row['apellido'], $row['documento'], $row['email'], $row['telefono'], $row['rol'], $row['habilitado'], $row['ubicacion'], $row['departamento']);
            $users[]=$user;
        }
        return $users;
    }

    public function listAll($inicio, $tam_pagina) {
        $users=null;
        $query = $this->queryList("SELECT * FROM usuario LIMIT $inicio, $tam_pagina", array());
        foreach ($query[0] as $row) {
            $user = new User($row['id'], $row['usuario'], $row['clave'], $row['nombre'], $row['apellido'], $row['documento'], $row['email'], $row['telefono'], $row['rol'], $row['habilitado'], $row['ubicacion'], $row['departamento']);
            $users[]=$user;
        }
        return $users;
    }

    public function listAll_users() {

        $query = $this->queryList("SELECT * FROM usuario", array());
        foreach ($query[0] as $row) {
            $user = new User($row['id'], $row['usuario'], $row['clave'], $row['nombre'], $row['apellido'], $row['documento'], $row['email'], $row['telefono'], $row['rol'], $row['habilitado'], $row['ubicacion'], $row['departamento']);
            $users[]=$user;
        }
        return $users;
    }

}
?>
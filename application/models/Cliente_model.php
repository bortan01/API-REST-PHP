<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Cliente_model extends CI_Model
{
    public $id;
    public $nombre;
    public $correo;
    public $activo;
    public $zip;
    public $telefono1;
    public $telefono2;
    public $pais;
    public $direccion;




    public function obtenerCliente($id)
    {
        //colocamos las condiciones 
        $this->db->where(array('id' => $id, 'activo' => 1));
        //hacemos la consulta 
        $query = $this->db->get('clientes');


        //el 0 representa el resultado que deseamos (en caso fueran varios registros)
        //Cliente_model represente el nombre del modelo
        //con esto lo que devolveremos sera un objeto del tipo cliente model
        $row = $query->custom_row_object(0, 'Cliente_model');


        ///para transformar los campos que son int hacemos lo siguite
        if (isset($row)) {
            $row->id = intval($row->id);
            $row->activo = intval($row->activo);
        }


        return $row;
    }
}
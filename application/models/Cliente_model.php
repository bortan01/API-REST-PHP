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


    public function verificar_campos($dataCruda)
    {
        ///par aquitar campos no existentes 
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Cliente_model', $nombre_campo)) {
                $this->$nombre_campo = $valor_campo;
            }
        }

        if ($this->activo == NULL) {
            $this->activo = 1;
        }
        //este es un objeto tipo cliente model
        return $this;
    }

    public function guardar()
    {
        //VERIFICAMOS  QUE EL CORREO NO ESTE DUPLICADO
        $query = $this->db->get_where('clientes', array('correo' => $this->correo));
        $cliente_correo = $query->row();

        if (isset($cliente_correo)) {
            # si ya existe el correo 
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'este correo electronico ya existe',
                'cliente' => null
            );
            return $respuesta;
        } else {
            ///VAMOS A INSERTAR UN REGISTRO
            $hecho =  $this->db->insert('clientes', $this);
            if ($hecho) {
                ///LOGRO GUARDAR
                $respuesta = array(
                    'err' => FALSE,
                    'mensaje' => 'Registro Guardado Exitosamente',
                    'cliente' => $this->db->insert_id()
                );
                return $respuesta;
            } else {
                //NO GUARDO
                $respuesta = array(
                    'err' => TRUE,
                    'mensaje' => 'Error al insertar ', $this->db->error_message(),
                    'error_number' => $this->db->error_number(),
                    'cliente' => null
                );
                return $respuesta;
            }
        }
    }

    public function actualizar()
    {
        //VERIFICAMOS  QUE EL CORREO
        $this->db->where('correo', $this->correo);
        $this->db->where('id !=', $this->id);
        $query = $this->db->get('clientes');
        ///es lo mismo que select * from clientes where correo ="correo@gmail.com" and id != '2'

        $cliente_correo = $query->row();

        if (isset($cliente_correo)) {
            # si ya existe el correo 
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'este correo electronico ya esta registrado por otro usuario',
                'cliente' => $cliente_correo
            );
            return $respuesta;
        } else {
            ///VAMOS A ACTUALIZAR UN REGISTRO
            //LIMPIAMOS EL QUERY PARA VOLVER A HACER CONSULTAS
            $this->db->reset_query();
            $this->db->where('id', $this->id);
            $hecho =  $this->db->update('clientes', $this);

            if ($hecho) {
                ///LOGRO GUARDAR
                $respuesta = array(
                    'err' => FALSE,
                    'mensaje' => 'Registro Actualizado Exitosamente',
                    'cliente' => $this->id
                );
                return $respuesta;
            } else {
                //NO GUARDO
                $respuesta = array(
                    'err' => TRUE,
                    'mensaje' => 'Error al actualizar ', $this->db->error_message(),
                    'error_number' => $this->db->error_number(),
                    'cliente' => null
                );
                return $respuesta;
            }
        }
    }
}
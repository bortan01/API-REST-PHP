<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Facturacion_detalle_model extends CI_Model
{

    public $detalle_id;
    public $factura_id;
    public $producto;
    public $precio_unitario;
    public $cantidad;



    public function obtenerFacturas($id)
    {
        //colocamos las condiciones 
        $this->db->where(array('factura_id' => $id));
        //hacemos la consulta 
        $query = $this->db->get('facturacion_detalle');


        //el 0 representa el resultado que deseamos (en caso fueran varios registros)
        //Cliente_model represente el nombre del modelo
        //con esto lo que devolveremos sera un objeto del tipo cliente model
        // $row = $query->custom_row_object(0, 'Facturacion_detalle_model');


        ///para transformar los campos que son int hacemos lo siguite



        return $query->result();
    }
}
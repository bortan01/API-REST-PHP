<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Facturacion_model extends CI_Model
{

    public $factura_id;
    public $cliente_id;
    public $Nombre;
    public $total_pagar;

    public function obtenerFactura($id)
    {
        //colocamos las condiciones 
        $this->db->where(array('factura_id' => $id));
        //hacemos la consulta 
        $query = $this->db->get('facturacion');


        //el 0 representa el resultado que deseamos (en caso fueran varios registros)
        //Cliente_model represente el nombre del modelo
        //con esto lo que devolveremos sera un objeto del tipo cliente model
        $row = $query->custom_row_object(0, 'Facturacion_model');


        ///para transformar los campos que son int hacemos lo siguite
        if (isset($row)) {
            $row->factura_id    = intval($row->factura_id);
            $row->cliente_id    = intval($row->cliente_id);
            $row->total_pagar   = doubleval($row->total_pagar);
        }


        return $row;
    }
}
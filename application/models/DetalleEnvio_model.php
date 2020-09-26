<?php
defined('BASEPATH') or exit('No direct script access allowed');
class DetalleEnvio_model extends CI_Model
{

public $id_detalle_envio;
public $id_encomienda;
public $fecha;
public $hora;
public $lugar;
public $descripcion;

public function get_pregunta(){


 	$query=$this->db->get('detalle_envio');
 	
 		return $query->result();
 	}


    public function set_datos( $data_cruda){

 		foreach ($data_cruda as $nombre_campo => $valor_campo) {

 		if (property_exists('DetalleEnvio_model',$nombre_campo)) {
 			$this->$nombre_campo=$valor_campo;
 		
 		}
 			
 		}
 		return $this; //retornamos el objeto de clase
 	}//fin de capitalizar los datos segun el modelo y campos correctos de la base
}

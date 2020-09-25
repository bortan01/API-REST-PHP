<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Cita_model extends CI_Model
{
public $id_cita;
public $id_usuario;
public $descripcion;
public $motivo;
public $color;
public $textColor;
public $start;
public $end;

public function get_citas(){


 	$query=$this->db->get('cita');
 	
 		return $query->result();
 	}

    public function set_datos( $data_cruda){

 		foreach ($data_cruda as $nombre_campo => $valor_campo) {

 		if (property_exists('cita_model',$nombre_campo)) {
 			$this->$nombre_campo=$valor_campo;
 		
 		}
 			
 		}
 		return $this; //retornamos el objeto de clase
 	}//fin de capitalizar los datos segun el modelo y campos correctos de la base


}
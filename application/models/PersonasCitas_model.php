<?php
defined('BASEPATH') or exit('No direct script access allowed');
class PersonasCitas_model extends CI_Model
{
//public $id_persona;
public $id_cita;
public $nombres_personas;
//public $cantidad_personas;

public function get_personas($id){

	$this->db->where(array('id_cita'=>$id));

 	$query=$this->db->get('personas_cita');
 	return $query->result();
 }

public function insertarPersonas($cita,$personas){

	$cuantos=count($personas);
	
	for ($i=0; $i < $cuantos ; $i++) { 
		# code...
		$this->id_cita=$cita;
		
	    $this->nombres_personas=$personas[$i];
	    $hecho=$this->db->insert('personas_cita',$this);
	   }

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente',
					'personas_id'=>$this->db->insert_id(),
					'ver'=>$this
				);
			}else{
				//error

				$respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Error al insertar',
					'error'=>$this->db->_error_message(),
					'error_num'=>$this->db->_error_number()
				);
			
			}



 		return $respuesta;
}

}
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

public function modificarPersona($id_cita,$input,$asistiran){


	

	if ($input!=NULL) {// SI ES NULL NO ARA NADA PORQ NO TRAE DATOS PARA EVITAR PROCEDIMIENTOS
		# code...
		$nuevos=count($input);
		for ($i=0; $i < $nuevos ; $i++) { 
		# code...
		$this->id_cita=$id_cita;
		
	    $this->nombres_personas=$input[$i];
	    $hecho=$this->db->insert('personas_cita',$this);
	    }//for

	     if ($hecho) {
				#actualizado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Actualizado'
				);
			}else{

				$respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Error'
				);

			}
	}else{

		$respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Error al insertar'
				);
	}
	
	if($asistiran!=NULL){
	$modificar=count($asistiran);
	for ($i=0; $i <$modificar ; $i++) { 
		# code...

		$this->id_cita=$id_cita;
		$this->nombres_personas=$asistiran[$i];

		$this->db->set($this);
        $this->db->where('id_cita',$id_cita);
        $hecho=$this->db->update('personas_cita');
        
	   }//for

	   if ($hecho) {
				#actualizado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Actualizado'
				);
			}else{

				$respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Error'
				);

			}

	}else {
		$respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Error al insertar',
					'error'=>$this->db->_error_message(),
					'error_num'=>$this->db->_error_number()
				);
	}
	


 		return $respuesta;

}//modificarPersonas

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
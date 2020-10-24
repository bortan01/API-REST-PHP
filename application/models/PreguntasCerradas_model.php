<?php
defined('BASEPATH') or exit('No direct script access allowed');
class PreguntasCerradas_model extends CI_Model
{
  public $id_opcion;
  public $opciones_respuestas;
  public $id_pregunta;

  public function get_opciones($id){

  	$this->db->select('*');
    $this->db->from('opciones_respuestas');
 	$this->db->where('id_pregunta',$id);
    $query=$this->db->get();
   

        return $query->result();

  }

public function actualizarOpciones($data,$cuantos,$id){

	//BORRAMOS POR EL TIPO DE PROCEDIMIENTO
	$this->db->where('id_pregunta',$id);
    $this->db->delete('opciones_respuestas');

	for ($i=0; $i < $cuantos ; $i++) { 
		
		$this->id_pregunta=$id;
		$this->opciones_respuestas=$data[$i];
		$hecho=$this->db->insert('opciones_respuestas',$this);

		}

		 if ($hecho) {
				#actualizado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registrado'
				);
			}else{

				$respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Error'
				);

			}
	return $respuesta;

}


  public function insertarCerrada($data,$cuantos,$id){
	
		for ($i=0; $i < $cuantos ; $i++) { 

		$this->id_pregunta=$id;
		$this->opciones_respuestas=$data[$i];
		$hecho=$this->db->insert('opciones_respuestas',$this);
		}

		 if ($hecho) {
				#actualizado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registrado'
				);
			}else{

				$respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Error'
				);

			}
	return $respuesta;
	} 

}
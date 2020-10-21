<?php
defined('BASEPATH') or exit('No direct script access allowed');
class PreguntasCerradas_model extends CI_Model
{
  public $id_opcion;
  public $opciones_respuestas;
  public $id_pregunta;

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
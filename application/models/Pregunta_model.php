<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pregunta_model extends CI_Model
{

public $id_pregunta;
public $pregunta;
public $opcion_respuesta;
public $id_rama;


	public function get_pregunta(){


 	$query=$this->db->get('pregunta');
 	
 		return $query->result();
 	}


    public function set_datos( $data_cruda){

 		foreach ($data_cruda as $nombre_campo => $valor_campo) {

 		if (property_exists('Pregunta_model',$nombre_campo)) {
 			$this->$nombre_campo=$valor_campo;
 		
 		}
 			
 		}
 		return $this; //retornamos el objeto de clase
 	}//fin de capitalizar los datos segun el modelo y campos correctos de la base

 	public function insert(){

 		//verificar el correo
		$query=$this->db->get_where('pregunta',array('pregunta'=>$this->pregunta) );
		$pregunta_ya=$query->row();

			if (isset($pregunta_ya)) {
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'La pregunta ya esta registrada'
			);
			return $respuesta;
			}

			//insertar el registro
			$hecho=$this->db->insert('pregunta',$this);

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente',
					'pregunta_id'=>$this->db->insert_id()
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
 	}//fin de insertar la pregunta


}
<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Encomienda_model extends CI_Model
{

public $id_encomienda;
public $id_usuario;
public $direccion;
public $costo;
public $estado;
public $fecha;
public $destino_final;


public function get_encomienda(){


 	$query=$this->db->get('encomienda');
 	
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

			//insertar el registro
			$hecho=$this->db->insert('encomienda',$this);

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente',
					'encomienda_id'=>$this->db->insert_id()
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

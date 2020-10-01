<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Cita_model extends CI_Model
{
public $id_cita;
public $descripcion;
public $title;
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

 	public function insertCita($title,$descripcion,$start,$fecha){

			//insertar el registro
			$this->title =$title;
			$this->descripcion=$descripcion;
			$this->start=$fecha.' '.$start;
			$this->textColor="#FFFFFF";
			$this->color="#007bff";


			$hecho=$this->db->insert('cita',$this);

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente',
					'cita_id'=>$this->db->insert_id(),
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
 	}//fin de insertar la pregunta


}
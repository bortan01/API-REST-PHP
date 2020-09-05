<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Rama_model extends CI_Model
{
	public $id_rama;
	public $nombre_rama;
	public $numero_rama;


	public function get_rama($id){

 	$this->db->where(array('id_rama'=>$id));

 	$query=$this->db->get('ramas');

 //	select *from clientes where id=id and status='activo'
 	
 	$row=$query->custom_row_object(0,'Rama_model');
 	
 	if (isset($row)) {
 		$row->id_rama = intval($row->id_rama);
 		$row->numero_rama = intval($row->numero_rama);
 	}
 	
 		return $row;
 	}//fin de mostrar la ramita


	public function set_datos( $data_cruda){

 		foreach ($data_cruda as $nombre_campo => $valor_campo) {

 		if (property_exists('Rama_model',$nombre_campo)) {
 			$this->$nombre_campo=$valor_campo;
 		
 		}
 			
 		}
 		return $this; //retornamos el objeto de clase
 	}//fin de capitalizar los datos segun el modelo y campos correctos de la base

 	public function insert(){

 		//verificar el correo
		$query=$this->db->get_where('ramas',array('nombre_rama'=>$this->nombre_rama ) );
		$rama_ya=$query->row();

			if (isset($rama_ya)) {
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'La rama ya esta registrada'
			);
			return $respuesta;
			}

			//insertar el registro
			$hecho=$this->db->insert('ramas',$this);

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente',
					'rama_id'=>$this->db->insert_id()
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
 	}//fin insertar rama


}
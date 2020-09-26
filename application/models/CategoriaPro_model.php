<?php
defined('BASEPATH') or exit('No direct script access allowed');
class CategoriaPro_model extends CI_Model
{

	public $id_categoria;
	public $nombre;

	public function get_categoria(){


 	$query=$this->db->get('categoria');
 	
 		return $query->result();
 	}   



    public function set_datos( $data_cruda){

 		foreach ($data_cruda as $nombre_campo => $valor_campo) {

 		if (property_exists('CategoriaPro_model',$nombre_campo)) {
 			$this->$nombre_campo=$valor_campo;
 		
 		}
 			
 		}
 		return $this; //retornamos el objeto de clase
 	}//fin de capitalizar los datos segun el modelo y campos correctos de la base 

 		public function insert(){

 		//verificar el correo
		$query=$this->db->get_where('categoria',array('nombre'=>$this->nombre) );
		$categoria=$query->row();

			if (isset($categoria)) {
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'La categoria ya esta registrada'
			);
			return $respuesta;
			}

			//insertar el registro
			$hecho=$this->db->insert('categoria',$this);

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente',
					'categoria_id'=>$this->db->insert_id()
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
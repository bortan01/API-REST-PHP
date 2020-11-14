<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Personalizada_model extends CI_Model
{
	public $id_personalizada;
	public $costo;
	public $direccion;
	public $punto_referencia;
	public $id_producto;
	public $id_municipios;
	public $id_empresa;

	public function insertarPersonalizada($id_producto,$datos){

		//extraer la empresa
		$query=$this->db->get_where('empresa',array('nombre_empresa'=>$data['nombre_empresa']) );
		$id_empresa=$query->row('id_empresa');

		$this->id_empresa=$id_empresa;
		$this->id_producto=$id_producto;
		$this->id_municipios=$datos['id_municipios'];
		$this->costo
		$this->direccion
		$this->punto_referencia

			//insertar el registro
			$hecho=$this->db->insert('personalizada',$this);

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente',
					'producto_id'=>$this->db->insert_id(),
					'datos'=>$datos
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
 	}//fin de insertar
}
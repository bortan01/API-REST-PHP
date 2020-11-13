<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Estandar_model extends CI_Model
{

public $id_estandar;
public $costo;
public $direccion;
public $punto_referencia;
public $id_empresa;
public $id_municipios;

 public function set_datos( $data_cruda){

 		foreach ($data_cruda as $nombre_campo => $valor_campo) {

 		if (property_exists('Estandar_model',$nombre_campo)) {
 			$this->$nombre_campo=$valor_campo;
 		
 		}
 			
 		}
 		return $this; //retornamos el objeto de clase
 	}//fin de capitalizar los datos segun el modelo y campos correctos de la base

	public function insertarEstandar($data){

 		//verificar el correo
		$query=$this->db->get_where('empresa',array('nombre_empresa'=>$data['nombre_empresa']) );
		$id_empresa=$query->row('id_empresa');

		$this->id_empresa=$id_empresa;

			//insertar el registro
			$hecho=$this->db->insert('estandar',$this);

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Empresa registrada correctamente',
					'estandar_id'=>$this->db->insert_id()
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
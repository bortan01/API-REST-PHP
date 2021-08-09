<?php
defined('BASEPATH') or exit('No direct script access allowed');
class unidad_model extends CI_Model
{
public $id_unidad;
public $unidad_medida;

public function get_unidad(){
    $this->db->select('*');
    $this->db->from('unidades_medidas');
    $query=$this->db->get();
    return $query->result();
}

public function insertarUnidad($datos){
			//insertar el registro
 		    $this->unidad_medida=$datos['unidad_medida'];
			$hecho=$this->db->insert('unidades_medidas',$this);

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

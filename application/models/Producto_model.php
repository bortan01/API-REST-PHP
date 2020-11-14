<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Producto_model extends CI_Model
{
	public $id_producto;
	public $id_categoria;
	public $nombre;
	public $permitido;

	public function eliminar($datos){

		$query=$this->db->get_where('producto',array('id_producto'=>$datos["id_producto"]) );
		$producto=$query->row();

			if (!isset($producto)) {
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'El producto no existe'
			);
			return $respuesta;
			}

		$this->db->where('id_producto',$datos["id_producto"]);

 		$hecho=$this->db->delete('producto');

 		if ($hecho) {
				#borrado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro eliminado correctamente'
				);
			}else{
				//error

				$respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Error al eliminar',
					'error'=>$this->db->_error_message(),
					'error_num'=>$this->db->_error_number()
				);
			
			}
 		return $respuesta;
	}//fin metodo

	public function modificar_producto($datos){
		$this->db->set($datos);
 		$this->db->where('id_producto',$datos["id_producto"]);

 		$hecho=$this->db->update('producto');

 		if ($hecho) {
				#borrado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro actualizado correctamente',
					'Producto'=>$datos
				);

			

			}else{
				//error

				$respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Error al actualizar',
					'error'=>$this->db->_error_message(),
					'error_num'=>$this->db->_error_number()
				);
			
			}
 		return $respuesta;
 	}//fin metodo

	public function get_producto(){


 	$query=$this->db->get('producto');
 	
 		return $query->result();
 	}


	public function set_datos($data_cruda){
    	 $objeto =array();
        ///par aquitar campos no existentes
        foreach ($data_cruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Producto_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        return $objeto;
 	}//fin de capitalizar los datos segun el modelo y campos correctos de la base

 	public function insert($datos){


			//insertar el registro
			$hecho=$this->db->insert('producto',$this);
			$this->load->model('Personalizada_model');
			$id_producto=$this->db->insert_id();
			$this->Personalizada_model->insertarPersonalizada($id_producto,$datos);

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


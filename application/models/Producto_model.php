<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Producto_model extends CI_Model
{
	public $id_producto;
	public $nombre_producto;


	public function eliminarProducto($datos){

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

        $this->db->where('id_producto',$datos["id_producto"]);

 		$this->db->delete('tarifa');

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

 		//para modificar
 		
 			$this->db->set(array('nombre_producto'=> $datos['nombre_producto']));
 		    $this->db->where('id_producto',$datos["id_producto"]);
            $hecho=$this->db->update('producto');
            $this->load->model('Tarifa_model');
			$this->Tarifa_model->modificar_tarifa($datos);

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro Actualizado correctamente',
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
 	}//fin metodo

	public function get_producto(){
    $this->db->select('*');
    $this->db->from('producto');
    $this->db->join('tarifa', 'tarifa.id_producto=producto.id_producto','inner');
    $query=$this->db->get();
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

 	public function insertarProducto($datos){
			//insertar el registro
 		    $this->nombre_producto=$datos['nombre_producto'];
			$hecho=$this->db->insert('producto',$this);
			$this->load->model('Tarifa_model');
			$id_producto=$this->db->insert_id();
			$this->Tarifa_model->insertarTarifa($id_producto,$datos);

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


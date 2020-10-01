<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Encomienda_model extends CI_Model
{

public $id_encomienda;
public $id_usuario;
public $direccion;
public $estado;
public $fecha;
public $destino_final;

public function eliminar($datos){

		$query=$this->db->get_where('encomienda',array('id_encomienda'=>$datos["id_encomienda"]) );
		$encomienda=$query->row();

			if (!isset($encomienda)) {
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'La encomienda no existe'
			);
			return $respuesta;
			}

		$this->db->where('id_encomienda',$datos["id_encomienda"]);

 		$hecho=$this->db->delete('encomienda');

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

public function modificar_encomienda($datos){
		$this->db->set($datos);
 		$this->db->where('id_encomienda',$datos["id_encomienda"]);

 		$hecho=$this->db->update('encomienda');

 		if ($hecho) {
				#borrado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro actualizado correctamente',
					'Formularios'=>$datos
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
 	}


public function get_encomienda(){


 	$query=$this->db->get('encomienda');
 	
 		return $query->result();
 	}


  public function set_datos($data_cruda){

 		 $objeto =array();
        ///par aquitar campos no existentes
        foreach ($data_cruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Encomienda_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        return $objeto;
 	}//fin de capitalizar los datos segun el modelo y campos correctos de la base

 	public function insert($datos){

			//insertar el registro
			$hecho=$this->db->insert('encomienda',$datos);

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

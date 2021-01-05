<?php
defined('BASEPATH') or exit('No direct script access allowed');
class DetalleEnvio_model extends CI_Model
{

public $id_detalle_envio;
public $id_encomienda;
public $fecha;
public $hora;
public $lugar;
public $descripcion;

public function eliminar($datos){

		$query=$this->db->get_where('detalle_envio',array('id_detalle_envio'=>$datos["id_detalle_envio"]) );
		$detatalle=$query->row();

			if (!isset($detatalle)) {
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'El detatalle de envio no existe'
			);
			return $respuesta;
			}

		$this->db->where('id_detalle_envio',$datos["id_detalle_envio"]);

 		$hecho=$this->db->delete('detalle_envio');

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

public function modificar_detalle($datos){
		$this->db->set($datos);
 		$this->db->where('id_detalle_envio',$datos["id_detalle_envio"]);

 		$hecho=$this->db->update('detalle_envio');

 		if ($hecho) {
				#borrado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro actualizado correctamente',
					'Detalles'=>$datos
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

public function get_detallesEnvio($data){

	$this->db->select('*');
    $this->db->from('detalle_envio');
    $this->db->where(array('id_encomienda'=>$data['id_encomienda']));
    $query=$this->db->get();
    return $query->result();

}


    public function set_datos( $data_cruda){
    	 $objeto =array();
        ///par aquitar campos no existentes
        foreach ($data_cruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('DetalleEnvio_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        return $objeto;
 	}//fin de capitalizar los datos segun el modelo y campos correctos de la base

 	public function insertarDetalle($datos){

 	
			//insertar el registro
			$hecho=$this->db->insert('detalle_envio',$datos);

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente',
					'Detalle_id'=>$this->db->insert_id(),
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
 	}
}

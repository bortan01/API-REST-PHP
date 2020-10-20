<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Rama_model extends CI_Model
{
	public $id_rama;
	public $categoria_rama;
	public $numero_rama;

	public function eliminar($datos){
		$query=$this->db->get_where('ramas_preguntas',array('id_rama'=>$datos["id_rama"]) );
		$rama=$query->row();

			if (!isset($rama)) {
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'La Rama no existe'

			);
			return $respuesta;
			}

		$this->db->where('id_rama',$datos["id_rama"]);

 		$hecho=$this->db->delete('ramas_preguntas');

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
	}

	public function modificar_rama($datos){
		$this->db->set($datos);
 		$this->db->where('id_rama',$datos["id_rama"]);

 		$hecho=$this->db->update('ramas_preguntas');

 		if ($hecho) {
				#borrado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro actualizado correctamente',
					'ramas'=>$datos
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


	public function get_rama(){
		$query=$this->db->get('ramas_preguntas');
        $respu=$query->result();
        if (count($respu)<1) {
          $respuesta=array('err'=>FALSE,'mensaje'=>'Error al cargar los datos');

          return $respuesta;
        }else{
          $respuesta=array('err'=>TRUE,'mensaje'=>'Registro Cargado correctamente',
            'ramas'=>$respu);

          return $respuesta;
        }
 	}//fin de mostrar la ramita

 	public function verificar_campos($dataCruda)
    {
        $objeto =array();
        ///par aquitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Rama_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        //este es un objeto tipo cliente model
        return $objeto;
    }



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
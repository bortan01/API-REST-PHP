<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pregunta_model extends CI_Model
{

public $id_pregunta;
public $pregunta;
public $opcion_respuesta;
public $id_rama;


	public function eliminar($datos){

		$query=$this->db->get_where('pregunta',array('id_pregunta'=>$datos["id_pregunta"]) );
		$pregunta=$query->row();

			if (!isset($pregunta)) {
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'La pregunta no existe'

			);
			return $respuesta;
			}

		$this->db->where('id_pregunta',$datos["id_pregunta"]);

 		$hecho=$this->db->delete('pregunta');

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


	public function modificar_pregunta($datos){
		$this->db->set($datos);
 		$this->db->where('id_pregunta',$datos["id_pregunta"]);

 		$hecho=$this->db->update('pregunta');

 		if ($hecho) {
				#borrado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro actualizado correctamente',
					'preguntas'=>$datos
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


	public function get_pregunta(){


 	$query=$this->db->get('pregunta');
 	
 		return $query->result();
 	}

 	public function verificar_campos($dataCruda)
    {
        $objeto =array();
        ///par aquitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Pregunta_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        //este es un objeto tipo cliente model
        return $objeto;
    }


    public function set_datos( $data_cruda){

 		foreach ($data_cruda as $nombre_campo => $valor_campo) {

 		if (property_exists('Pregunta_model',$nombre_campo)) {
 			$this->$nombre_campo=$valor_campo;
 		
 		}
 			
 		}
 		return $this; //retornamos el objeto de clase
 	}//fin de capitalizar los datos segun el modelo y campos correctos de la base

 	public function insert(){

 		//verificar el correo
		$query=$this->db->get_where('pregunta',array('pregunta'=>$this->pregunta) );
		$pregunta_ya=$query->row();

			if (isset($pregunta_ya)) {
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'La pregunta ya esta registrada'
			);
			return $respuesta;
			}

			//insertar el registro
			$hecho=$this->db->insert('pregunta',$this);

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente',
					'pregunta_id'=>$this->db->insert_id()
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
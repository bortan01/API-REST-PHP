<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Rama_model extends CI_Model
{
	public $id_rama;
	public $categoria_rama;
	public $num_rama;

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

 	public function rama_auto(){

 		$query=$this->db->get('ramas_preguntas');
            $respu=$query->result();
            $cuantos=count($respu);

            if ($cuantos==0) {
            	$dato1=array('categoria_rama' =>'Información Personal','num_rama'=>1);
            	$dato2=array('categoria_rama' =>'Información de Viaje','num_rama'=>2);
            	$this->db->insert('ramas_preguntas',$dato1);
            	$this->db->insert('ramas_preguntas',$dato2);

            	$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente'
					
					);
            	return $respuesta;
            }else{
            	$respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Ya existen ramas'
					
					);
            	return $respuesta;
            }

 	}

 	public function insert(){

 		//verificar el correo
		$query=$this->db->get_where('ramas_preguntas',array('categoria_rama'=>$this->categoria_rama ) );
		$rama_ya=$query->row();

			if (isset($rama_ya)) {
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'La rama ya esta registrada'
			);
			return $respuesta;
			}
			//para el num_rama
			$query=$this->db->get('ramas_preguntas');
            $respu=$query->result();
            $cuantos=count($respu);

            if ($cuantos==0) {
            	# code...
            	$this->num_rama=1;
            }else{
            	$this->num_rama=$cuantos+1;
            }

			//insertar el registro
			$hecho=$this->db->insert('ramas_preguntas',$this);

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
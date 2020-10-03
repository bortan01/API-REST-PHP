<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Cajas_model extends CI_Model
{
public $id_caja;
public $ancho;
public $largo;
public $alto;
public $capacidad;

public function get_caja(){


 	$query=$this->db->get('caja');
 	
 		return $query->result();
 	}

 public function set_datos($data_cruda){
    	 $objeto =array();
        ///par aquitar campos no existentes
        foreach ($data_cruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Cajas_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        return $objeto;
 	}//fin de capitalizar los datos segun el modelo y campos correctos de la base

 	public function insert($datos){


			//insertar el registro
			$hecho=$this->db->insert('caja',$datos);

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente',
					'caja_id'=>$this->db->insert_id(),
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
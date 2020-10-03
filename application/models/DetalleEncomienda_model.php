<?php
defined('BASEPATH') or exit('No direct script access allowed');
class DetalleEncomienda_model extends CI_Model
{
public $id_producto;
public $id_encomienda;
public $cantidad;
public $id_caja;
public $id_tarifa;

public function get_detalle(){


 	$query=$this->db->get('detalle_encomienda');
 	
 		return $query->result();
 	}

public function set_datos($data_cruda){
    	 $objeto =array();
        ///par aquitar campos no existentes
        foreach ($data_cruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('DetalleEncomienda_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        return $objeto;
 	}//fin de capitalizar los datos segun el modelo y campos correctos de la base

 	public function insert($datos){


			//insertar el registro
			$hecho=$this->db->insert('detalle_encomienda',$datos);

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente',
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
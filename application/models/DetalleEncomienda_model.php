<?php
defined('BASEPATH') or exit('No direct script access allowed');
class DetalleEncomienda_model extends CI_Model
{
public $id_producto;
public $id_encomienda;
public $cantidad;


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

 	public function guardarDetalle(array $detalles, string $id){

 		$nombreTabla = "detalle_encomienda";

        if (count($detalles) < 1) {
            $respuesta = array(
                'err'          => TRUE,
                'mensaje'      => "NO SE INSERTO NINGUN REGISTRO",
                'detalle_encomienda'      => null
            );
            return $respuesta;
        } else {
            for ($i = 0; $i < count($detalles); $i++) {
                $detalles[$i]["id_encomienda"] = $id;
            }
            //$servicio = $this->verificar_camposEntrada($data);
            $insert = $this->db->insert_batch($nombreTabla, $detalles);
            if (!$insert) {
                //NO GUARDO
                $respuesta = array(
                    'err'          => TRUE,
                    'mensaje'      => 'Error al insertar ', $this->db->error_message(),
                    'error_number' => $this->db->error_number(),
                    'serrvicio'      => null
                );
                return $respuesta;
            } else {
                $respuesta = array(
                    'err'          => FALSE,
                    'mensaje'      => 'Registro Guardado Exitosamente',
                    //'servicio'   => $servicio
                );
                return $respuesta;
            }
        }


 	}//fin de insertar


}
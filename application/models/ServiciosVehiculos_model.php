<?php
defined('BASEPATH') or exit('No direct script access allowed');
class ServiciosVehiculos_model extends CI_Model
{
public $iddetalle_servicios;
public $id_detalle;
public $servicio_adicional;
public $costo_servicio;
public $cantidad_servicio;



public function get_detalle(array $data){
    
    $this->db->select('*');
    $this->db->from('detalle_encomienda');
    $this->db->join('producto', 'producto.id_producto=detalle_encomienda.id_producto','inner');
    $this->db->join('tarifa', 'tarifa.id_producto=producto.id_producto','inner');
    $this->db->where(array('id_encomienda'=>$data['id_encomienda']));
    $query=$this->db->get();
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

 		$nombreTabla = "detalle_serviciosVehiculo";

        if (count($detalles) < 1) {
            $respuesta = array(
                'err'          => TRUE,
                'mensaje'      => "NO SE INSERTO NINGUN REGISTRO",
                'detalle_servicio'      => null
            );
            return $respuesta;
        } else {
            for ($i = 0; $i < count($detalles); $i++) {
                $detalles[$i]["id_detalle"] = $id; 
            }
            //$servicio = $this->verificar_camposEntrada($data);
            $insert = $this->db->insert_batch($nombreTabla, $detalles);
            if (!$insert) {
                //NO GUARDO
                $respuesta = array(
                    'err'          => TRUE,
                    'mensaje'      => 'Error al insertar ', $this->db->error_message(),
                    'error_number' => $this->db->error_number(),
                    'detalle_servicio'      => null
                );
                return $respuesta;
            } else {
                $respuesta = array(
                    'err'          => FALSE,
                    'mensaje'      => 'Registro Guardado Exitosamente',
                    'detalle_encomienda'   => $detalles
                );
                return $respuesta;
            }
        }


 	}//fin de insertar


}
<?php
defined('BASEPATH') or exit('No direct script access allowed');
class DetalleDestino_model extends CI_Model
{
public $id_destino;
public $id_encomienda;
public $nombre_cliente_destini;
public $telefono;
public $ciudad_destino;
public $codigo_postal_destino;
public $direccion_destino;
public $alterna_destino;

public function guardarDetalleDestino(array $detalles, string $id){

 		$nombreTabla = "detalle_destino";

        if (count($detalles) < 1) {
            $respuesta = array(
                'err'          => TRUE,
                'mensaje'      => "NO SE INSERTO NINGUN REGISTRO",
                'detalle_destino'      => null
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
                    'detalle_destino'      => null
                );
                return $respuesta;
            } else {
                $respuesta = array(
                    'err'          => FALSE,
                    'mensaje'      => 'Registro Guardado Exitosamente',
                    'detalle_destino'   => $detalles
                );
                return $respuesta;
            }
        }


 	}//fin de insertar







}
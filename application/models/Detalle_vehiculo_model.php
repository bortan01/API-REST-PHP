<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Detalle_vehiculo_model extends CI_Model
{
    public $id_detalle;
    public $id_vehiculo;
    public $id_cliente;
    public $total_detalle;
    public $urlQrCodeEnlace;
    public $urlEnlace;
    public $nombre_detalle;
    public $direccionRecogida_detalle;
    public $direccionDevolucion_detalle;
    public $fechaHora_detalle;
    public $fechaDevolucion;
    public $horaDevolucion;
    public $porcentajeMora;
    public $totalDevolucion;
  
    public $activo_detalle=TRUE;

    public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///par aquitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Detalle_vehiculo_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }
    public function guardar($data)
    {
        $camposVehiculo = $this->verificar_camposEntrada($data);
        $nombreTabla = "detalle_vehiculo";

        $this->db->where('idvehiculo', $data["id_vehiculo"]);
        $this->db->update('vehiculo',array('activo'=>2));

        $insertTur = $this->db->insert($nombreTabla, $camposVehiculo);
        if (!$insertTur) {
            //NO GUARDO
            $respuesta = array(
                'err'             => TRUE,
                'mensaje'         => 'Error al insertar detalle ', $this->db->error_message(),
                'error_number'    => $this->db->error_number()
            );
            return $respuesta;
        } else {
            //GUARDADO
            $respuesta = array(
                'err'             => FALSE,
                'mensaje'         => 'Registro Guardado Exitosamente',
                'id_detalle_vehiculo' => $this->db->insert_id()
            );
            return $respuesta;
        }
    }
    public function guardarByCliente($data)
    {
        $this->load->model('Wompi_model');
        $this->load->model('Imagen_model');
        $urlWebHook  = "https://api.christianmeza.com/ReservaVehiculo/save";
        $foto        = $this->Imagen_model->obtenerImagenUnica("vehiculo", $data["id_vehiculo"]);

        if (!isset($foto)) {
            $foto = "https://www.pagina.christianmeza.com/img/logo.jpg";
        }
        $respuestaWompi = $this->Wompi_model->crearEnlacePagopPrueba($data["total"], $data["nombre"], $data["descripcion"], $foto, $urlWebHook);
        if (!isset($respuestaWompi["idEnlace"])) {
            //HAY ERROR DE WOMPI
            $respuesta = array(
                'err'     => TRUE,
                'mensaje' => $respuestaWompi["err"],
            );
            return $respuesta;
        } else {
            //RECUPERAMOS LA INFORMACION DE WOMPI Y TRATAMOS DE GUARDAR EN LA BD
            $data["id_detalle"]        = $respuestaWompi["idEnlace"];
            $data["urlQrCodeEnlace"]   = $respuestaWompi["urlQrCodeEnlace"];
            $data["urlEnlace"]         = $respuestaWompi["urlEnlace"];
            $guardado =  $this->guardar($data);

            if ($guardado["err"]) {
                return $guardado;
            } else {
                $respuesta = array(
                    'err'             => FALSE,
                    'idEnlace' =>   $respuestaWompi["idEnlace"],
                    'urlQrCodeEnlace' => $respuestaWompi["urlQrCodeEnlace"],
                    'urlEnlace' => $respuestaWompi["urlEnlace"]
                );
                return $respuesta;
            }
        }
    }
    public function obtenerDetalle(array $data = array())
    {
        $this->load->model("Utils_model");
        $nombreTabla = "detalle_vehiculo";
        

        try {
            $parametros = $this->verificar_camposEntrada($data);

            $this->db->select('*');
            $this->db->from('detalle_vehiculo' );
            $this->db->join('usuario', 'detalle_vehiculo.id_cliente = usuario.id_cliente');
            $this->db->join('vehiculo', 'detalle_vehiculo.id_vehiculo = vehiculo.idvehiculo');
            //$this->db->join('detalle_serviciosvehiculo', 'detalle_vehiculo.id_detalle = detalle_serviciosvehiculo.id_detalle');
            $this->db->join('modelo', 'vehiculo.idmodelo = modelo.idmodelo');
            
          
            $this->db->where($parametros);
            $this->db->where_in('vehiculo.activo',2);
            $this->db->where_in('activo_detalle',2);
            $this->db->order_by('detalle_vehiculo.fechaHora_detalle', 'desc');

            $query=$this->db->get();
    
            $z = $query->result();

            $this->db->select('*');
            $this->db->from('detalle_serviciosvehiculo' );        
          
            $this->db->where($parametros);
           
            $query2=$this->db->get();
    
            $z2 = $query2->result();

            $deetalleSeleccionado = $this->Utils_model->selectTabla($nombreTabla, $parametros);

            if (count($deetalleSeleccionado) < 1) {
                $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro el detalle vehiculo');
                return $respuesta;
            } else {
                $respuesta = array('err' => FALSE, 'detalleVehiculo' => $z,'detalle_servicio'=>$z2);
                return $respuesta;
            }
        } catch (Exception $e) {
            return array('err' => TRUE, 'status' => 400, 'mensaje' => $e->getMessage());
        }

        return $z;
    }


    
    public function editar($data)
    {
        $nombreTabla = "detalle_vehiculo";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Detalle_vehiculo_model->verificar_camposEntrada($data);

        //$campos["activo_detalle"]=1;
        $this->db->where('idvehiculo', $data["idvehiculo"]);
        $this->db->update('vehiculo',array('kilometraje'=>$data['kilometraje'],
                                            'activo'=>1));
        
        $this->db->where('id_detalle', $campos["id_detalle"]);
        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'Detalle' => $campos,
                'Data que llega'=>$data

            );
            return $respuesta;
        } 
        else 
        {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'Detalle' => null
            );
            return $respuesta;
        }
    }
}
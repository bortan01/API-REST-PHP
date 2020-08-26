<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Hook_model extends CI_Model
{
    public $idTransaccion;
    public $fechaTransaccion;
    public $monto;
    public $moduloUtilizado;
    public $formaPagoUtilizada;
    public $resultadoTransaccion;
    public $cantidad;
    public $esProductiva;
    public $enlacePagoId;
    public $enlacePagoNombreProducto;
    public $clienteNombre;
    public $clienteEmail;
    public $clienteCelular;
    public $clienteDireccion;

    public function set_campos($data)
    {
        $this->idTransaccion = $data["IdTransaccion"];
        $this->fechaTransaccion = $data["FechaTransaccion"];
        $this->monto = $data["Monto"];
        $this->moduloUtilizado = $data["ModuloUtilizado"];
        $this->formaPagoUtilizada = $data["FormaPagoUtilizada"];
        $this->resultadoTransaccion = $data["ResultadoTransaccion"];
        $this->cantidad = $data["Cantidad"];
        $this->esProductiva = $data["EsProductiva"];

        $this->enlacePagoId = $data["EnlacePago"]["Id"];
        $this->enlacePagoNombreProducto = $data["EnlacePago"]["NombreProducto"];

        $this->clienteNombre = $data["Cliente"]["Nombre"];
        $this->clienteEmail = $data["Cliente"]["Email"];
        $this->clienteCelular = $data["Cliente"]["additionalProp1"];
        $this->clienteDireccion = $data["Cliente"]["additionalProp2"];

        if ($this->clienteNombre == null) {
            $this->clienteNombre = "Desconocido";
        }

        if ($this->clienteEmail == null) {
            $this->clienteEmail = "noEmail";
        }

        if ($this->clienteCelular == null) {
            $this->clienteCelular = "0000-0000";
        }
        if ($this->clienteDireccion == null) {
            $this->clienteDireccion = "No direccion";
        }

        return $this;
    }

    public function guardar()
    {
        //VERIFICAMOS  QUE EL CORREO NO ESTE DUPLICADO
        $query = $this->db->get_where('clientes', array('correo' => $this->correo));
        $cliente_correo = $query->row();

        if (isset($cliente_correo)) {
            # si ya existe el correo 
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'este correo electronico ya existe',
                'cliente' => null
            );
            return $respuesta;
        } else {
            ///VAMOS A INSERTAR UN REGISTRO
            $hecho =  $this->db->insert('clientes', $this);
            if ($hecho) {
                ///LOGRO GUARDAR
                $respuesta = array(
                    'err' => FALSE,
                    'mensaje' => 'Registro Guardado Exitosamente',
                    'cliente' => $this->db->insert_id()
                );
                return $respuesta;
            } else {
                //NO GUARDO
                $respuesta = array(
                    'err' => TRUE,
                    'mensaje' => 'Error al insertar ', $this->db->error_message(),
                    'error_number' => $this->db->error_number(),
                    'cliente' => null
                );
                return $respuesta;
            }
        }
    }
}
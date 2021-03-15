<?php
defined('BASEPATH') or exit('No direct script access allowed');
class cotizarVuelo_model extends CI_Model
{
    public $id_cotizacion;
    public $id_cliente;
    public $opc_avanzadas;
    public $idaerolinea;
    public $idclase;
    public $idtipo_viaje;
    public $total;
    public $descuentos;
    public $ciudad_partida;
    public $fechaPartida;
    public $HoraPartida;
    public $ciudad_llegada;
    public $fechaLlegada;
    public $HoraLlegada;
    public $adultos;
    public $ninos;
    public $bebes;
    public $maletas;
    public $detallePasajero;
    public $activo = TRUE;

    public function get_cotizar(array $data)
    {

        $parametros = $this->verificar_camposEntrada($data);
        $this->db->select('*');
        $this->db->from('cotizacion_vuelo');
        $this->db->join('aerolinea', 'cotizacion_vuelo.idaerolinea = aerolinea.idaerolinea');
        $this->db->join('alianza', 'aerolinea.idalianza = alianza.idalianza');
        $this->db->join('tipo_clase', 'cotizacion_vuelo.idclase = tipo_clase.idclase');
        $this->db->join('tipo_viaje', 'cotizacion_vuelo.idtipo_viaje = tipo_viaje.idtipo_viaje');
        $this->db->join('usuario', 'cotizacion_vuelo.id_cliente = usuario.id_cliente');


        $this->db->where($parametros);
        $this->db->where_in('cotizacion_vuelo.activo',1);
        $query=$this->db->get();

        $respuesta = $query->result();
      
        
        foreach ($respuesta as $opciones) {
            $opciones->opc_avanzadas =   explode(",", $opciones->opc_avanzadas);
        }

            return $respuesta;
    }
   
   
       public function set_datos( $data_cruda){
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
            if (property_exists('cotizarVuelo_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            }
            }
            return $this; 
        }
   
        public function insert(){
           $query=$this->db->get_where('cotizacion_vuelo',array('id_cotizacion'=>$this->id_cotizacion) );
           $cotizar=$query->row();
   
               if (isset($cotizar)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'Informacion adicional fue registrada'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('cotizacion_vuelo',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'cotizacion_id'=>$this->db->insert_id()
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
        }
    //MODIFICAR
    public function editar($data)
    {
        $nombreTabla = "cotizacion_vuelo";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->cotizarVuelo_model->verificar_camposEntrada($data);
        $this->db->where('id_cotizacion', $campos["id_cotizacion"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'Cotizacion' => $campos

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
                'Cotizacion' => null
            );
            return $respuesta;
        }
    }

    //VERIFICAR DATOS
    public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///quitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('cotizarVuelo_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }

    //ELIMINAR
    public function borrar($campos)
    {
        //ELIMINAR UN REGISTRO
        $this->db->where('id_cotizacion', $campos["id_cotizacion"]);
        $hecho = $this->db->update('cotizacion_vuelo', $campos);
        if ($hecho) {
            //ELIMINANDO REGISTRO
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Eliminado Exitosamente'
            );
            return $respuesta;
        } else {
            //NO ELIMINO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al eliminar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'cotizacion' => null
            );
            return $respuesta;
        }
    }
   
}
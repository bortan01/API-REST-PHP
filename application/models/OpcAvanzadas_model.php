<?php
defined('BASEPATH') or exit('No direct script access allowed');
class opcAvanzadas_model extends CI_Model
{
    public $idopc_avanzadas;
    public $idaerolinea;
    public $idclase;
    public $idtipo_viaje;
    public $vuelosin_escala;
    public $misma_aerolinea;
    public $equipaje_extra;
    public $activo = TRUE;

    public function get_opciones()
    {
        $query=$this->db->get('opc_avanzadas');
            return $query->result();
        }
   
       public function set_datos( $data_cruda){
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('opcAvanzadas_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;   
            }
            }
            return $this; 
        }
   
        public function insert(){
   
           $query=$this->db->get_where('opc_avanzadas',array('idopc_avanzadas'=>$this->idopc_avanzadas) );
           $carrito=$query->row();
   
               if (isset($carrito)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'Informacion adicional fue registrada'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('opc_avanzadas',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'opc_id'=>$this->db->insert_id()
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
        $nombreTabla = "opc_avanzadas";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->opcAvanzadas_model->verificar_camposEntrada($data);
        $this->db->where('idopc_avanzadas', $campos["idopc_avanzadas"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'Opciones Avanzadas' => $campos

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
                'Opciones Avanzadas' => null
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
            if (property_exists('opcAvanzadas_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }

    //ELIMINAR
    public function borrar($campos)
    {
        //ELIMINAR UN REGISTRO
        $this->db->where('idopc_avanzadas', $campos["idopc_avanzadas"]);
        $hecho = $this->db->update('opc_avanzadas', $campos);
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
                'Opciones Avanzadas' => null
            );
            return $respuesta;
        }
    }
   
   
}

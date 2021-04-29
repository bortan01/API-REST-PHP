<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Bitacora_model extends CI_Model
{
    public $idbitacora;
    public $idusuario;
    public $hora_bitacora;
    public $fecha_bitacora;
    public $detalle_bitacora;

    public function get_bitacora(array $data){

        $parametros = $this->verificar_camposEntrada($data);

        $this->db->select('*');
        $this->db->from('bitacora');
        $this->db->join('usuario', 'bitacora.idusuario = usuario.id_cliente');
        $this->db->where($parametros);
        $query = $this->db->get();
        return $query->result();

        }



       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Bitacora_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;            
            }
            }
            return $this; 
        }

        public function insert(){
            
           $query=$this->db->get_where('bitacora',array('idbitacora'=>$this->idbitacora));
           $detalle=$query->row();
   
               if (isset($detalle)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'detalle de bitacora registrado'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('bitacora',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'bitacora_id'=>$this->db->insert_id()
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
        $nombreTabla = "bitacora";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Bitacora_model->verificar_camposEntrada($data);
        $this->db->where('idbitacora', $campos["idbitacora"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'detalle bitacora' => $campos

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
                'detalle bitacora' => null
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
            if (property_exists('Bitacora_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }

    //ELIMINAR
    public function borrar($campos)
    {
        //ELIMINAR UN REGISTRO
        $this->db->where('idbitacora', $campos["idbitacora"]);
        $hecho = $this->db->update('bitacora', $campos);
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
                'detalle' => null
            );
            return $respuesta;
        }
    }
   
}
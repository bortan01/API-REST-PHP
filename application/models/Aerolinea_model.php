<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Aerolinea_model extends CI_Model
{
    public $idaerolinea;
    public $idalianza;
    public $nombre_aerolinea;
    public $sitioWeb;
    public $telefonoContacto;
    public $activo = TRUE;
    

    public function get_aerolinea(array $data){

        $parametros = $this->verificar_camposEntrada($data);

        $this->db->select('*');
        $this->db->from('aerolinea');
        $this->db->join('alianza', 'aerolinea.idalianza = alianza.idalianza');
        $this->db->where($parametros);
        $this->db->where_in('aerolinea.activo',1);
        $query = $this->db->get();

        return $query->result();

        }



       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Aerolinea_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;            
            }
            }
            return $this; 
        }

        public function insert(){
            
           $query=$this->db->get_where('aerolinea',array('nombre_aerolinea'=>$this->nombre_aerolinea));
           $carrito=$query->row();
   
               if (isset($carrito)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'Aerolinea fue registrada'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('aerolinea',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'aerolinea_id'=>$this->db->insert_id()
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
        $nombreTabla = "aerolinea";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Aerolinea_model->verificar_camposEntrada($data);
        $this->db->where('idaerolinea', $campos["idaerolinea"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'Aerolinea' => $campos

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
                'Aerolinea' => null
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
            if (property_exists('Aerolinea_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }

    //ELIMINAR
    public function borrar($campos)
    {
        //ELIMINAR UN REGISTRO
        $this->db->where('idaerolinea', $campos["idaerolinea"]);
        $hecho = $this->db->update('aerolinea', $campos);
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
                'aerolinea' => null
            );
            return $respuesta;
        }
    }
   
}
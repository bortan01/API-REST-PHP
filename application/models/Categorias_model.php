<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Categorias_model extends CI_Model
{
    public $idcategoria;
    public $nombre;
    public $descripcion;
    public $activo = TRUE;

    
    public function get_categorias(){

        $query=$this->db->get('categoria');

            return $query->result();
        }
       public function set_datos( $data_cruda){
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Categorias_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            
            }
            }
            return $this; 
        }
   
        public function insert(){
   
           $query=$this->db->get_where('categoria',array('nombre'=>$this->nombre) );
           $categorias=$query->row();
   
               if (isset($categorias)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'Categoria fue registrada'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('categoria',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'categoria_id'=>$this->db->insert_id()
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
        $nombreTabla = "categoria";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Categorias_model->verificar_camposEntrada($data);
        $this->db->where('idcategoria', $campos["idcategoria"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'Categoria' => $campos

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
                'Categoria' => null
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
            if (property_exists('Categorias_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }

     //ELIMINAR
     public function borrar($campos)
     {
         //ELIMINAR UN REGISTRO
         $this->db->where('idcategoria', $campos["idcategoria"]);
         $hecho = $this->db->update('categoria', $campos);
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
                 'categoria' => null
             );
             return $respuesta;
         }
     }
}
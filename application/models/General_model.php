<?php
defined('BASEPATH') or exit('No direct script access allowed');
class General_model extends CI_Model
{
    public $idgeneral;
    public $nombre_agencia;
    public $direccion_agencia;
    public $telefono_agencia;
    public $email_agencia;

    public function get_general(array $data){

        $parametros = $this->verificar_camposEntrada($data);

        $this->db->select('*');
        $this->db->from('general');
        $this->db->where($parametros);
        $query = $this->db->get();

        return $query->result();

        }



       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('General_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;            
            }
            }
            return $this; 
        }

    //MODIFICAR
    public function editar($data)
    {
        $nombreTabla ="general";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->General_model->verificar_camposEntrada($data);
        $this->db->where('idgeneral', $campos["idgeneral"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'Informacion' => $campos

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
                'Informacion' => null
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
            if (property_exists('General_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }

   
}
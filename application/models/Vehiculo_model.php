<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Vehiculo_model extends CI_Model
{
    public $idvehiculo;
    public $id_rentaCarFK;
    public $id_marcaFK;
    public $id_transmicionFK;
    public $idcategoria;
    public $placa;
    public $anio;
    public $color;
    public $puertas;
    public $pasajeros;
    public $precio_diario;
    public $descripcion;
    public $detalles;
    public $sonido;
    public $wifi;
    public $aireAcondicionado;
    public $activo = TRUE;
    public $kilometraje;
    public $tipoCombustible;

    public function get_vehiculo(array $data)
    {
        $parametros = $this->verificar_camposEntrada($data);

        /*SELECT*FROM vehiculo INNER JOIN marca_vehiculo ON vehiculo.id_marcaFK=marca_vehiculo.id_marca 
        INNER JOIN transmisionvehiculo ON vehiculo.id_transmicionFK=transmisionvehiculo.idtransmicion 
        INNEr JOIN modelo ON marca_vehiculo.id_marca=modelo.id_marca */
        $this->db->select('*');
        $this->db->from('vehiculo');
        $this->db->join('marca_vehiculo', 'vehiculo.id_marcaFK=marca_vehiculo.id_marca');
        $this->db->join('transmisionvehiculo', 'vehiculo.id_transmicionFK=transmisionvehiculo.idtransmicion');
        $this->db->join('modelo', 'marca_vehiculo.id_marca=modelo.id_marca');
        $this->db->join('categoria', 'vehiculo.idcategoria=categoria.idcategoria');
        $this->db->join('rentacar', 'vehiculo.id_rentaCarFK=rentacar.id_rentaCar');
        $this->db->where($parametros);
        $query = $this->db->get();

        //$query=$this->db->get('vehiculo');

        return $query->result();
    }
       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Vehiculo_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            
            }
                
            }
            return $this; 
        }
   
        public function insert(){
            
           $query=$this->db->get_where('vehiculo',array('placa'=>$this->placa) );
           $carrito=$query->row();
   
               if (isset($carrito)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'El vehiculo fue registrado'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('vehiculo',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'vehiculo_id'=>$this->db->insert_id()
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
        $nombreTabla = "vehiculo";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Vehiculo_model->verificar_camposEntrada($data);
        $this->db->where('idvehiculo', $campos["idvehiculo"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'Vehiculo' => $campos

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
                'Vehiculo' => null
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
            if (property_exists('Vehiculo_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }
   
    //ELIMINAR
    public function borrar($campos)
    {
        //ELIMINAR UN REGISTRO
        $this->db->where('idvehiculo', $campos["idvehiculo"]);
        $hecho = $this->db->update('vehiculo', $campos);
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
                'vehiculo' => null
            );
            return $respuesta;
        }
    }
}
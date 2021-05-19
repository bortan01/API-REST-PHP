<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Comision_model extends CI_Model
{
	public $id_comision;
	public $porcentaje;

	public function insertarComisionAuto(){
		$this->db->select('porcentaje');
        $this->db->from('comision');
        $comision=$this->db->get();
         $row = $comision->row('porcentaje');
        if ($row > 0) {
                $respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Ya esta la comision'
					);
                
			return $respuesta;
        }else{

        	
		$this->porcentaje=10;
		$hecho=$this->db->insert('comision',$this);

		if ($hecho) {
			$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente'
				);
		}
		return $respuesta;
	   }
	}
}
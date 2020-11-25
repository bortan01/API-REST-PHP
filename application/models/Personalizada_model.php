<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Personalizada_model extends CI_Model
{
	public $id_personalizada;
	public $costo;
	public $punto_referencia;
	public $envoltura;
	public $id_producto;
	public $id_municipios;
	public $id_empresa;


	public function get_productosCosto(array $data){

		$query=$this->db->get_where('empresa',array('nombre_empresa'=>$data['nombre_empresa']) );
		$id_empresa=$query->row('id_empresa');

        $this->db->select('*');
        $this->db->from('personalizada');
        $this->db->join('producto', 'producto.id_producto=personalizada.id_producto','inner');
        $this->db->where(array('personalizada.id_empresa'=>$id_empresa,'personalizada.id_producto'=>$data['id_producto']));
        $query = $this->db->get();

        //$query=$this->db->get('vehiculo');

        return $query->result();

}

	public function get_productosCombo(array $data){

		$query=$this->db->get_where('empresa',array('nombre_empresa'=>$data['nombre_empresa']) );
		$id_empresa=$query->row('id_empresa');

        $this->db->select('*');
        $this->db->from('personalizada');
        $this->db->join('producto', 'producto.id_producto=personalizada.id_producto','inner');
        $this->db->where(array('id_empresa'=>$id_empresa,'id_municipios'=>$data['id_municipios']));
        $query = $this->db->get();

        //$query=$this->db->get('vehiculo');

        return $query->result();

}

	public function get_puntoPersonalizada(array $data){

		$query=$this->db->get_where('empresa',array('nombre_empresa'=>$data['nombre_empresa']) );
		$id_empresa=$query->row('id_empresa');

        $this->db->select('*');
        $this->db->from('personalizada');
        $this->db->where(array('id_empresa'=>$id_empresa,'id_municipios'=>$data['id_municipios']));
        $query = $this->db->get();

        //$query=$this->db->get('vehiculo');

        return $query->result();

}

	public function insertarPersonalizada($id_producto,$datos){

		//extraer la empresa
		$query=$this->db->get_where('empresa',array('nombre_empresa'=>$datos['nombre_empresa']) );
		$id_empresa=$query->row('id_empresa');

		$this->id_empresa = $id_empresa;
		$this->id_producto = $id_producto;
		$this->id_municipios = $datos['id_municipios'];
		$this->costo = $datos['costo'];
		$this->punto_referencia = $datos['punto_referencia'];
		$this->envoltura =$datos['envoltura'];

			//insertar el registro
			$hecho=$this->db->insert('personalizada',$this);

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente',
					'producto_id'=>$this->db->insert_id(),
					'datos'=>$datos
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
 	}//fin de insertar
}
<?php
defined('BASEPATH') or exit('No direct script access allowed');
class CategoriaPro_model extends CI_Model
{

	public $id_categoria;
	public $nombre;

	public function eliminar($datos){

		$query=$this->db->get_where('categoria',array('id_categoria'=>$datos["id_categoria"]) );
		$cate=$query->row();

			if (!isset($cate)) {
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'La categoria no existe'
			);
			return $respuesta;
			}

		$this->db->where('id_categoria',$datos["id_categoria"]);

 		$hecho=$this->db->delete('categoria');

 		if ($hecho) {
				#borrado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro eliminado correctamente'
				);
			}else{
				//error

				$respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Error al eliminar',
					'error'=>$this->db->_error_message(),
					'error_num'=>$this->db->_error_number()
				);
			
			}
 		return $respuesta;
	}//fin metodo

	public function modificar_categoria($datos){
		$this->db->set($datos);
 		$this->db->where('id_categoria',$datos["id_categoria"]);

 		$hecho=$this->db->update('categoria');

 		if ($hecho) {
				#borrado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro actualizado correctamente',
					'Categorias'=>$datos
				);

			

			}else{
				//error

				$respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Error al actualizar',
					'error'=>$this->db->_error_message(),
					'error_num'=>$this->db->_error_number()
				);
			
			}
 		return $respuesta;
 	}//fin metodo

	public function get_categoria(){


 	$query=$this->db->get('categoria');
 	
 		return $query->result();
 	}   



    public function set_datos( $data_cruda){
    	 $objeto =array();
        ///par aquitar campos no existentes
        foreach ($data_cruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('CategoriaPro_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        return $objeto;
 	}//fin de capitalizar los datos segun el modelo y campos correctos de la base 

 		public function insert($datos){

 		//verificar el correo
		$query=$this->db->get_where('categoria',array('nombre'=>$datos["nombre"]) );
		$categoria=$query->row();

			if (isset($categoria)) {
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'La categoria ya esta registrada'
			);
			return $respuesta;
			}

			//insertar el registro
			$hecho=$this->db->insert('categoria',$datos);

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
 	}//fin de insertar la pregunta
}
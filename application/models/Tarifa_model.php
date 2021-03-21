<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Tarifa_model extends CI_Model
{
	public $id_tarifa;
	public $tarifa;
	public $id_unidad_medida;
	public $id_producto;

public function eliminar($datos){

		$query=$this->db->get_where('tarifa',array('id_tarifa'=>$datos["id_tarifa"]) );
		$ta=$query->row();

			if (!isset($ta)) {
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'La tarifa no existe'
			);
			return $respuesta;
			}

		$this->db->where('id_tarifa',$datos["id_tarifa"]);

 		$hecho=$this->db->delete('tarifa');

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

public function modificar_tarifa($datos){
		$this->db->set(array('tarifa'=>$datos['tarifa'],
			                 'unidades_medidas'=>$datos['unidades_medidas']
	                  ));
 		$this->db->where('id_producto',$datos["id_producto"]);
        $hecho=$this->db->update('tarifa');

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente',
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
}//fin metodo

public function get_tarifa($data){
 	    $this->db->select('*');
        $this->db->from('tarifa');
         $this->db->join('unidades_medidas', 'tarifa.id_unidad_medida=unidades_medidas.id_unidad','inner');
        $this->db->where($data);
        $query = $this->db->get();
 	
 		return $query->result();
 	}

public function set_datos($data_cruda){
    	 $objeto =array();
        ///par aquitar campos no existentes
        foreach ($data_cruda as $nombre_campo => $valor_campo)
        {
            # para verificar si la propiedad existe..
            if (property_exists('Tarifa_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        return $objeto;
}//fin de capitalizar los datos segun el modelo y campos correctos de la base

public function insertarTarifa($id_producto,$datos){

 			$this->tarifa = $datos['tarifa'];
 			$this->id_unidad_medida = $datos['id_unidad'];
 			$this->id_producto = $id_producto;
			//insertar el registro
			$hecho=$this->db->insert('tarifa',$this);

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente',
					'tarifa_id'=>$this->db->insert_id(),
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
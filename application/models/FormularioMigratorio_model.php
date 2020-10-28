<?php
defined('BASEPATH') or exit('No direct script access allowed');
class FormularioMigratorio_model extends CI_Model
{

	public $id_formulario;
	public $id_pregunta;
	public $id_cita;
	public $respuesta;

public function eliminar($datos){

		$query=$this->db->get_where('formulario_migratorio',array('id_formulario'=>$datos["id_formulario"]) );
		$formulario=$query->row();

			if (!isset($formulario)) {
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'La pregunta no existe'
			);
			return $respuesta;
			}

		$this->db->where('id_formulario',$datos["id_formulario"]);

 		$hecho=$this->db->delete('formulario_migratorio');

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


public function modificar_formulario($datos){
		$this->db->set($datos);
 		$this->db->where('id_formulario',$datos["id_formulario"]);

 		$hecho=$this->db->update('formulario_migratorio');

 		if ($hecho) {
				#borrado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro actualizado correctamente',
					'Formularios'=>$datos
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


public function get_pregunta(){


 	$query=$this->db->get('formulario_migratorio');
 	
 		return $query->result();
}

public function set_datos($data_cruda){
    	 $objeto =array();
        ///par aquitar campos no existentes
        foreach ($data_cruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('FormularioMigratorio_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        return $objeto;
}//fin de capitalizar los datos segun el modelo y campos correctos de la base

public function insert($id_cita,$id_pregunta,$respuestas){

	$recorrer=count($id_pregunta);

	for ($i=0; $i < $recorrer ; $i++) { 
		# code...
		$this->id_cita=$id_cita;
		$this->id_pregunta=$id_pregunta[$i];
		$this->respuesta=$respuestas[$i];
		//insertar el registro
			$hecho=$this->db->insert('formulario_migratorio',$this);
	}


			

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente',
					'pregunta_id'=>$this->db->insert_id(),
					'datos'=>$this
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
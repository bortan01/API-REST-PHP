<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Cita_model extends CI_Model
{
public $id_cita;
public $id_cliente;
public $descripcion;
public $title;
public $color;
public $textColor;
public $start;
public $fecha;
public $hora;

public function eliminar($datos){

		$query=$this->db->get_where('cita',array('id_cita'=>$datos["id_cita"]) );
		$cita=$query->row();

			if (!isset($cita)) {
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'La cita no existe'
			);
			return $respuesta;
			}

		$this->db->where('id_cita',$datos["id_cita"]);

 		$hecho=$this->db->delete('cita');

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


public function modificar_cita($datos){
		$this->db->set($datos);
 		$this->db->where('id_cita',$datos["id_cita"]);

 		$hecho=$this->db->update('cita');

 		if ($hecho) {
				#borrado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro actualizado correctamente',
					'cita'=>$datos
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
 	}


public function get_citas(){
   // $query=$this->db->get('cita');
 	//return $query->result();

 	$this->db->select('*');
    $this->db->from('cita');
 	$this->db->join('usuario', 'usuario.id_cliente=cita.id_cliente','inner');
    $query=$this->db->get();
    return $query->result();

 	/*foreach ($query->result() as $row)
     {
         $this->id_cita=$row->id_cita;
         $this->motivo=$row->title;
         $this->title=$row->nombre;
         $this->start=$row->start;
         $this->color=$row->color;
         $this->textColor=$row->textColor;
        //echo $row->body;
            return [$this];
    }*/



 	}

    public function set_datos($data_cruda){
    	 $objeto =array();
        ///par aquitar campos no existentes
        foreach ($data_cruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Cita_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        return $objeto;
 	}//fin de capitalizar los datos segun el modelo y campos correctos de la base

 	public function insertCita($id_cliente,$descripcion,$motivo,$color,$textColor,$start,$fecha,$hora){

			//insertar el registro
 			foreach ($descripcion as $row) {
			$this->id_cliente=$id_cliente;
			$this->descripcion=$row['$descripcion'];
			$this->title=$motivo;
			$this->textColor="#FFFFFF";
			$this->color="#007bff";
			$this->start=$start;
			$this->fecha=$fecha;
			$this->hora=$hora;
			$hecho=$this->db->insert('cita',$this);
		 }

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente',
					'cita_id'=>$this->db->insert_id(),
					'ver'=>$this
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
<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Encomienda_model extends CI_Model
{

public $id_encomienda;
public $id_usuario;
public $ciudad_origen;
public $codigo_postal_origen;
public $estado;
public $fecha;
public $total_encomienda;
public $total_comision;
public $total_cliente;


public function eliminar($datos){

		$query=$this->db->get_where('encomienda',array('id_encomienda'=>$datos["id_encomienda"]) );
		$encomienda=$query->row();

			if (!isset($encomienda)) {
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'La encomienda no existe'
			);
			return $respuesta;
			}

		$this->db->where('id_encomienda',$datos["id_encomienda"]);

 		$hecho=$this->db->delete('encomienda');

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

public function modificar_encomienda($datos){

	$nombreTabla = "encomienda";
    $this->db->set($datos);
    $this->db->where('id_encomienda',$datos["id_encomienda"]);
    $update=$this->db->update('encomienda');

        if (!$update) {
            //NO GUARDO 
            $respuesta = array(
                'err'          => TRUE,
                'mensaje'      => 'Error al insertar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'Encomienda'   => null
            );
            return $respuesta;
        } else {
            //$identificador = $this->db->insert_id();
           
            $respuesta = array(
                'err'          => FALSE,
                'mensaje'      => 'Registro Guardado Exitosamente',
                'id'           => $datos['id_encomienda'],
                'encomienda'   => $datos
            );
            return $respuesta;
        }






	//************
		$this->db->set($datos);
 		$this->db->where('id_encomienda',$datos["id_encomienda"]);

 		$hecho=$this->db->update('encomienda');

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
 	}

public function get_encomiendaEnvio($data){
    $this->db->select('*');
    $this->db->from('encomienda');
    $this->db->join('usuario', 'usuario.id_cliente=encomienda.id_usuario','inner');
     $this->db->where($data);
    $this->db->where_in(array('estado'=>'Enviado'));
    $query=$this->db->get();
    return $query->result();
}

public function get_encomienda(){
	$this->db->select('encomienda.id_encomienda,encomienda.id_usuario, encomienda.ciudad_origen,encomienda.codigo_postal_origen, usuario.nombre, DATE_FORMAT(encomienda.fecha, "%d-%m-%Y") as fecha,encomienda.estado');
    $this->db->from('encomienda');
    $this->db->join('usuario', 'usuario.id_cliente=encomienda.id_usuario','inner');
    $query=$this->db->get();
    return $query->result();
}

public function get_encomiendaModificar(array $data){
	$this->db->select('*');
    $this->db->from('encomienda');
    $this->db->join('usuario', 'usuario.id_cliente=encomienda.id_usuario','inner');
     $this->db->where($data);
    $query=$this->db->get();
    return $query->result();
}

//************para sacar los datos de destino
public function get_encomiendaDestino(array $data){
    $this->db->select('*');
    $this->db->from('detalle_destino');
    $this->db->where($data);
    $query=$this->db->get();
    return $query->result();
}


  public function set_datos($data_cruda){

 		 $objeto =array();
        ///par aquitar campos no existentes
        foreach ($data_cruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Encomienda_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        return $objeto;
 	}//fin de capitalizar los datos segun el modelo y campos correctos de la base

 	public function insertarEncomienda($datos){

 		$nombreTabla = "encomienda";
        $insert = $this->db->insert($nombreTabla, $datos);


        if ($insert) {
            #insertado
            $this->load->model('Imagen_model');
            $identificador = $this->db->insert_id();
            $this->Imagen_model->guardarGaleria("encomienda",  $identificador);
            //EN ESTE CASO NO GUARDARA UNA FOTO SI NO UN PDF
            $this->Imagen_model->guardarImagen("comprobante_encomienda",  $identificador);
            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro insertado correctamente',
                'encomienda_id' => $this->db->insert_id()
            );
        } else {
            //error
            $this->load->model('Imagen_model');
            $identificador = $this->db->insert_id();
            ///ESTO ES PARA GUARDAR UNA IMAGEN INDIVIDUAL Y UNA GALERIA
            $this->Imagen_model->guardarGaleria("encomienda", $identificador);
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al insertar',
                'error' => $this->db->_error_message(),
                'error_num' => $this->db->_error_number()
            );
        }
        return $respuesta;

 	}//fin de insertar la pregunta

}

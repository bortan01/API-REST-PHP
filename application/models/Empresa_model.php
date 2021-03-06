<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Empresa_model extends CI_Model
{

public $id_empresa;
public $nombre_empresa;
public $nombre_encargado;
public $direccion;
public $telefono;
public $forma_operacion;
public $unidad;

 public function set_datos( $data_cruda){

 		foreach ($data_cruda as $nombre_campo => $valor_campo) {

 		if (property_exists('Empresa_model',$nombre_campo)) {
 			$this->$nombre_campo=$valor_campo;
 		
 		}
 			
 		}
 		return $this; //retornamos el objeto de clase
 	}//fin de capitalizar los datos segun el modelo y campos correctos de la base

public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///quitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Empresa_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }

public function get_municipio(array $data){

	//$parametros = $this->verificar_camposEntrada($data);

        $this->db->select('*');
        $this->db->from('municipios');
        $this->db->where($data);
        $query = $this->db->get();

        //$query=$this->db->get('vehiculo');

        return $query->result();

}
public function get_deptos(){
	$this->db->select('*');
    $this->db->from('departamentos');
    $query=$this->db->get();

    return $query->result();
   
 	}


public function get_empresas($data){
	$this->db->select('*');
    $this->db->from('empresa');
    $this->db->where($data);
    $query=$this->db->get();
   

        return $query->result();
   
 	}

public function insertarEmpresa(){

 		//verificar el correo
		$query=$this->db->get_where('empresa',array('nombre_empresa'=>$this->nombre_empresa) );
		$empresa_ya=$query->row();

			if (isset($empresa_ya)) {
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'La empresa ya esta registrada'
			);
			return $respuesta;
			}

			//insertar el registro
			$hecho=$this->db->insert('empresa',$this);

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Empresa registrada correctamente',
					'pregunta_id'=>$this->db->insert_id()
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
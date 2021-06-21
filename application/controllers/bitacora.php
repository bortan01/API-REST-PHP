<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class bitacora extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Bitacora_model');
	}

	public function bitacora_get(){
		$data = $this->get();
	    $clase=$this->Bitacora_model->get_bitacora($data);

	if (isset($clase)) {
		
		$respuesta=array(
			'err'=>FALSE,
			'mensaje'=>'Registro Cargado correctamente',
			'bitacora'=>$clase

		);
		$this->response($respuesta,REST_Controller::HTTP_OK);
	}else{
		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'Error al cargar los datos.',
			'bitacora'=>null

		);
		$this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);

	}
}
	//INSERTAR
	public function insertarBitacora_post(){

		$data=$this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);

		if ( $this->form_validation->run('bitacora_put')) {
		
		$detalles=$this->Bitacora_model->set_datos($data);
   
		$respuesta=$detalles->insert(); 

		if ($respuesta['err']) {

		$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST); 	

		}else{
		$this->response($respuesta); 	
		}
		}else{

			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'Hay errores en el envio de la informacion',
				'errores'=>$this->form_validation->get_errores_arreglo()
			);
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST); 
		}
	}
	//MODIFICAR
	public function actualizarBitacora_put(){

		$data = $this->put();
        if (!isset($data["idbitacora"])) {
            $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de la bitacora');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            try {
                $respuesta = $this->Bitacora_model->editar($data);
                if ($respuesta['err']) {
                    $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
                } else {
                    $this->response($respuesta, REST_Controller::HTTP_OK);
                }
            } catch (\Throwable $th) {
                $respuesta = array('err' => TRUE, 'mensaje' => 'Error interno de servidor');
            }
        }
	}

	//ELIMINAR
	public function eliminarBitacora_delete()
    {
        $data = $this->delete();
        if (!isset($data["idbitacora"])) {
            $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de Bitacora');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
			$campos = array('idbitacora' => $data["idbitacora"], 'activo' => FALSE);
			
            try {
                $respuesta = $this->Bitacora_model->borrar($campos);
                if ($respuesta['err']) {
                    $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
                } else {
                    $this->response($respuesta, REST_Controller::HTTP_OK);
				}
				
            } catch (\Throwable $th) {
                $respuesta = array('err' => TRUE, 'mensaje' => 'Error interno de servidor');
            }
        }
    }


}
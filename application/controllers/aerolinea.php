<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class aerolinea extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Aerolinea_model');
	}

	public function aerolinea_get(){

	$aerolineas=$this->Aerolinea_model->get_aerolinea();

	if ($aerolineas['err']) {
		
		$this->response($aerolineas,REST_Controller::HTTP_OK);
	}else{
		
		$this->response($aerolineas,REST_Controller::HTTP_NOT_FOUND);

	}
}
	
	//INSERTAR
	public function aerolinea_post(){

		$data=$this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);

		if ( $this->form_validation->run('aerolinea_put')) {
		
		$aerolineas=$this->Aerolinea_model->set_datos($data);
   
		$respuesta=$aerolineas->insert(); 

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
	public function actualizarAerolinea_put(){

		$data = $this->put();
        if (!isset($data["idaerolinea"])) {
            $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de Aerolinea');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            try {
                $respuesta = $this->Aerolinea_model->editar($data);
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
	public function eliminarAerolinea_delete()
    {
        $data = $this->delete();
        if (!isset($data["idaerolinea"])) {
            $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de Aerolinea');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
			$campos = array('idaerolinea' => $data["idaerolinea"], 'activo' => FALSE);
			//var_dump($campos);
			//die();
            try {
                $respuesta = $this->Aerolinea_model->borrar($campos);
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
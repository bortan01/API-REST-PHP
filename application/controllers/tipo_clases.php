<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: https://admin.tesistours.com/');
require APPPATH . '/libraries/REST_Controller.php';
class tipo_clases extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Clases_model');
	}
	public function clases_get(){
		$data = $this->get();
	    $clase=$this->Clases_model->get_clases($data);

	if (isset($clase)) {
		
		$respuesta=array(
			'err'=>FALSE,
			'mensaje'=>'Registro Cargado correctamente',
			'clase'=>$clase

		);
		$this->response($respuesta,REST_Controller::HTTP_OK);
	}else{
		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'Error al cargar los datos.',
			'clase'=>null

		);
		$this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);

	}
}
	//INSERTAR
	public function clases_post(){

		$data=$this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);

		if ( $this->form_validation->run('clases_put') ) {
		
		$clases=$this->Clases_model->set_datos($data);

		$respuesta=$clases->insert(); 

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
	public function actualizarClase_put(){

		$data = $this->put();
        if (!isset($data["idclase"])) {
            $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de Tipo de Clase');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            try {
                $respuesta = $this->Clases_model->editar($data);
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
		public function eliminarClase_delete()
		{
			$data = $this->delete();
			if (!isset($data["idclase"])) {
				$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de Clase');
				$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
			} else {
				$campos = array('idclase' => $data["idclase"], 'activo' => FALSE);
				try {
					$respuesta = $this->Clases_model->borrar($campos);
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
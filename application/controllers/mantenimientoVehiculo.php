<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class mantenimientoVehiculo extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Mantenimiento_model');
	}
	public function mantenimiento_get(){

	$data = $this->get();
	$mantenimientos=$this->Mantenimiento_model->get_mantenimiento($data);

	if (isset($mantenimientos)) {
		
		$respuesta=array(
			'err'=>FALSE,
			'mensaje'=>'Registro Cargado correctamente',
			'mantenimiento'=>$mantenimientos

		);
		$this->response($respuesta,REST_Controller::HTTP_OK);
	}else{
		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'Error al cargar los datos.',
			'mantenimiento'=>null

		);
		$this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);

	}
}

	
	//INSERTAR
	public function mantenimiento_post(){

		$data=$this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);

		if ( $this->form_validation->run('mantenimiento_put') ) {
		
		$mantenimientos=$this->Mantenimiento_model->set_datos($data);

		$respuesta=$mantenimientos->insert(); 

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
	public function actualizarMantenimiento_put(){

		$data = $this->put();
        if (!isset($data["id_mantenimiento"])) {
            $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador del Mantenimiento');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            try {
                $respuesta = $this->Mantenimiento_model->editar($data);
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
		public function eliminarMantenimiento_delete()
		{
			$data = $this->delete();
			if (!isset($data["id_mantenimiento"])) {
				$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador del Mantenimiento');
				$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
			} else {
				$campos = array('id_mantenimiento' => $data["id_mantenimiento"], 'activo' => FALSE);
				//var_dump($campos);
				//die();
				try {
					$respuesta = $this->Mantenimiento_model->borrar($campos);
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
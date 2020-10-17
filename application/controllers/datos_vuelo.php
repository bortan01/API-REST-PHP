<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class datos_vuelo extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Datos_model');
	}
	public function generales_get(){

	$datos=$this->Datos_model->get_generales();

	if (isset($datos)) {
		
		$respuesta=array(
			'err'=>FALSE,
			'mensaje'=>'Registro Cargado correctamente',
			'datos'=>$datos

		);
		$this->response($respuesta,REST_Controller::HTTP_OK);
	}else{
		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'Error al cargar los datos.',
			'datos'=>null

		);
		$this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);

	}
}

	
	//INSERTAR
	public function datos_post(){

		$data=$this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);

		if ( $this->form_validation->run('datos_put') ) {
		
		$datos=$this->Datos_model->set_datos($data);

		$respuesta=$datos->insert(); 

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
	public function actualizarDatos_put(){

		$data = $this->put();
        if (!isset($data["id_generales"])) {
            $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de Datos Generales');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            try {
                $respuesta = $this->Datos_model->editar($data);
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
		public function eliminarDatos_delete()
		{
			$data = $this->delete();
			if (!isset($data["id_generales"])) {
				$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de Datos Generales');
				$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
			} else {
				$campos = array('id_generales' => $data["id_generales"], 'activo' => FALSE);
				try {
					$respuesta = $this->Datos_model->borrar($campos);
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
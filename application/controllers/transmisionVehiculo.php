<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class transmisionVehiculo extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Transmision_model');
	}
	public function transmision_get(){

	$transmisiones=$this->Transmision_model->get_transmision();

	if (isset($transmisiones)) {
		
		$respuesta=array(
			'err'=>FALSE,
			'mensaje'=>'Registro Cargado correctamente',
			'transmision'=>$transmisiones

		);
		$this->response($respuesta,REST_Controller::HTTP_OK);
	}else{
		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'Error al cargar los datos.',
			'transmision'=>null

		);
		$this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);

	}
}
	//INSERTAR
	public function transmision_post(){

		$data=$this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);

		if ( $this->form_validation->run('transmision_put') ) {
		
		$transmisiones=$this->Transmision_model->set_datos($data);

		$respuesta=$transmisiones->insert(); 

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
	public function actualizarTransmision_put(){

		$data = $this->put();
        if (!isset($data["idtransmicion"])) {
            $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de la Transmision');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            try {
                $respuesta = $this->Transmision_model->editar($data);
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
		public function eliminarTransmision_delete()
		{
			$data = $this->delete();
			if (!isset($data["idtransmicion"])) {
				$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de Transmision');
				$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
			} else {
				$campos = array('idtransmicion' => $data["idtransmicion"], 'activo' => FALSE);
				
				try {
					$respuesta = $this->Transmision_model->borrar($campos);
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
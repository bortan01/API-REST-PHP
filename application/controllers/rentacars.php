<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class rentacars extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('RentaAuto_model');
	}
	public function rentaAuto_get(){

	$agencia=$this->RentaAuto_model->get_rentaAuto();

	if (isset($agencia)) {
		
		$respuesta=array(
			'err'=>FALSE,
			'mensaje'=>'Registro Cargado correctamente',
			'agencia'=>$agencia

		);
		$this->response($respuesta,REST_Controller::HTTP_OK);
	}else{
		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'Error al cargar los datos.',
			'agencia'=>null

		);
		$this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);

	}
}

	
	//INSERTAR
	public function renta_post(){

		$data=$this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);

		if ( $this->form_validation->run('renta_put') ) {
		
		$carro=$this->RentaAuto_model->set_datos($data);

		$respuesta=$carro->insert(); 

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
	public function actualizarRenta_put(){

		$data = $this->put();
        if (!isset($data["id_rentaCar"])) {
            $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador del Renta Car');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            try {
                $respuesta = $this->RentaAuto_model->editar($data);
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
		public function eliminarRenta_delete()
		{
			$data = $this->delete();
			if (!isset($data["id_rentaCar"])) {
				$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de Renta Car');
				$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
			} else {
				$campos = array('id_rentaCar' => $data["id_rentaCar"], 'activo' => FALSE);
				try {
					$respuesta = $this->RentaAuto_model->borrar($campos);
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
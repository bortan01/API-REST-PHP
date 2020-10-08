<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class cotizarVuelo extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('cotizarVuelo_model');
	}
	public function cotizar_get(){

	$cotizacion=$this->cotizarVuelo_model->get_cotizar();

	if (isset($cotizacion)) {
		
		$respuesta=array(
			'err'=>FALSE,
			'mensaje'=>'Registro Cargado correctamente',
			'informacion'=>$cotizacion

		);
		$this->response($respuesta);
	}else{
		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'Error al cargar los datos.',
			'informacion'=>null

		);
		$this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);

	}
}

	
	//INSERTAR
	public function cotizacionv_post(){

		$data=$this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);

		if ( $this->form_validation->run('cotizacionv_put') ) {
		
		$cotizar=$this->cotizarVuelo_model->set_datos($data);

		$respuesta=$cotizar->insert(); 

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
	public function actualizarCotizacion_put(){

		$data = $this->put();
        if (!isset($data["id_cotizacion"])) {
            $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de la Cotizacion');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            try {
                $respuesta = $this->cotizarVuelo_model->editar($data);
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
		public function eliminarCotizacion_delete()
		{
			$data = $this->delete();
			if (!isset($data["id_cotizacion"])) {
				$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de Cotizacion');
				$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
			} else {
				$campos = array('id_cotizacion' => $data["id_cotizacion"], 'activo' => FALSE);
				try {
					$respuesta = $this->cotizarVuelo_model->borrar($campos);
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
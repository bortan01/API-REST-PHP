<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: https://admin.tesistours.com/');
require APPPATH . '/libraries/REST_Controller.php';
class usuarioRentaCar extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('UsuarioRentaAuto_model');
	}
	public function usuario_get(){

	$usu=$this->UsuarioRentaAuto_model->get_usuario();

	if (isset($usu)) {
		
		$respuesta=array(
			'err'=>FALSE,
			'mensaje'=>'Registro Cargado correctamente',
			'usuario'=>$usu

		);
		$this->response($respuesta,REST_Controller::HTTP_OK);
	}else{
		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'Error al cargar los datos.',
			'usuario'=>null

		);
		$this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);

	}
}

	
	//INSERTAR
	public function usuario_post(){

		$data=$this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);

		if ( $this->form_validation->run('usuarioRenta_put') ) {
		
		$usu=$this->UsuarioRentaAuto_model->set_datos($data);

		$respuesta=$usu->insert(); 

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
	public function actualizarUsuario_put(){

		$data = $this->put();
        if (!isset($data["idusuarioRentaCar"])) {
            $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador del Encargado del Renta Car');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            try {
                $respuesta = $this->UsuarioRentaAuto_model->editar($data);
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
		public function eliminarUsuario_delete()
		{
			$data = $this->delete();
			if (!isset($data["idusuarioRentaCar"])) {
				$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador del Encargo del Renta Car');
				$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
			} else {
				$campos = array('idusuarioRentaCar' => $data["idusuarioRentaCar"], 'activo' => FALSE);
				try {
					$respuesta = $this->UsuarioRentaAuto_model->borrar($campos);
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
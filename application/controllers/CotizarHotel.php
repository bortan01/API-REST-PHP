<?php
defined('BASEPATH') or exit('No direct script access allowed');
$allowedOrigins = [
	"https://admin.martineztraveltours.com",
	"https://martineztraveltours.com/"
];
if (isset($_SERVER["HTTP_ORIGIN"]) && in_array($_SERVER["HTTP_ORIGIN"], $allowedOrigins)) {
	header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
}
require APPPATH . '/libraries/REST_Controller.php';
class cotizarHotel extends REST_Controller
{

	public function __construct()
	{
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('cotizarHotel_model');
		$this->load->model('Mail_model');
		$this->load->model('Conf_model');
	}
	public function cotizar_get()
	{

		$data = $this->get();
		$cotizacion = $this->cotizarHotel_model->get_cotizar($data);

		if (isset($cotizacion)) {

			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'informacion' => $cotizacion

			);
			$this->response($respuesta, REST_Controller::HTTP_OK);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'informacion' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}


	//INSERTAR
	public function cotizacion_post()
	{

		$data = $this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);

		if ($this->form_validation->run('cotizarHotel_post')) {

			$cotizar = $this->cotizarHotel_model->set_datos($data);

			$respuesta = $cotizar->insert($data);

			if ($respuesta['err']) {

				$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
			} else {

				$this->db->select('nombre');
				$this->db->from('usuario');
				$this->db->where('id_cliente',$data['idcliente']);
				$query = $this->db->get();
				foreach ($query->result() as $row)
				{
				 $cuerpo="<h2><h2>Han realizado una cotización de hotel</h2><br>
				 <h4>Cliente: ".$row->nombre."</h4><br>
				 <h4>Habitaciones: " . $data['detalleHabitaciones'] . "</h4><br>
				 <h4>Servicios Adicionales: " . $data['servicios_adicionales'] ."</h4><br>
				 <h4>Fue procesada con éxito, pendiente de respuesta</h4><br>
				 <h4>Verificar Cotización: ".$this->Conf_model->SISTEMA."</h4>	
				 <br>Atte:<br>Martínez Travel & Tours";
				}
				 
				 $this->Mail_model->metEnviar('Cotización de hotel','Cotización de Cliente',$cuerpo);

				
				$this->response($respuesta);
			}
		} else {

			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Hay errores en el envio de la informacion',
				'errores' => $this->form_validation->get_errores_arreglo()
			);
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		}
	}

	//MODIFICAR
	public function actualizarCotizacion_put()
	{

		$data = $this->put();
		if (!isset($data["idcotizacion_hotel"])) {
			$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de la Cotizacion');
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			try {
				$respuesta = $this->cotizarHotel_model->editar($data);
				if ($respuesta['err']) {
					$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
				} else {
					 //para mandar el correo
					 $this->db->select('*');
					 $this->db->from('cotizacion_hotel');
					 $this->db->where('idcotizacion_hotel',$data['idcotizacion_hotel']);
					 $query = $this->db->get();
					 foreach ($query->result() as $row)
					 {
						 $id=$row->id_cliente;
						 $cuerpo="<h2><h2>Han realizado una cotización de hotel</h2><br>
						 <h4>Cliente: ".$row->nombre."</h4><br>
						 <h4>Habitaciones: " . $data['detalleHabitaciones'] . "</h4><br>
						 <h4>Servicios Adicionales: " . $data['servicios_adicionales'] ."</h4><br>
						 <h4>Fue procesada con éxito, pendiente de respuesta</h4><br>
						 <h4>Verificar Cotización: ".$this->Conf_model->SISTEMA."</h4>	
						 <br>Atte:<br>Martínez Travel & Tours";
					 }
					
					  $this->Mail_model->metEnviarUno('Cotización de Hotel','','Respuesta Cotización de Vuelo',$cuerpo,$id);
					
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
		if (!isset($data["idcotizacion_hotel"])) {
			$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de Cotizacion');
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			$campos = array('idcotizacion_hotel' => $data["idcotizacion_hotel"], 'activo' => FALSE);
			try {
				$respuesta = $this->cotizarHotel_model->borrar($campos);
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

	public function mostrarCotizacion_get()
	{

		$data = $this->get();
		$cotizacion = $this->cotizarHotel_model->get_mostrarCotizacion($data);

		if (isset($cotizacion)) {

			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'informacion' => $cotizacion

			);
			$this->response($respuesta, REST_Controller::HTTP_OK);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'informacion' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}
}
<?php
defined('BASEPATH') or exit('No direct script access allowed');
$allowedOrigins = [
	"https://admin.tesistours.com",
	"https://tesistours.com"
];
if (isset($_SERVER["HTTP_ORIGIN"]) && in_array($_SERVER["HTTP_ORIGIN"], $allowedOrigins)) {
	header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
}
require APPPATH . '/libraries/REST_Controller.php';
class cotizarVuelo extends REST_Controller
{

	public function __construct()
	{
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('cotizarVuelo_model');
		$this->load->model('Mail_model');
		$this->load->model('Conf_model');
	}
	public function cotizar_get()
	{

		$data = $this->get();
		$cotizacion = $this->cotizarVuelo_model->get_cotizar($data);

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
	public function cotizacionv_post()
	{

		$data = $this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);

		if ($this->form_validation->run('cotizacionv_put')) {

			$cotizar = $this->cotizarVuelo_model->set_datos($data);

			$respuesta = $cotizar->insert($data);

			if ($respuesta['err']) {

				$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
			} else {

				$this->db->select('nombre');
				$this->db->from('usuario');
				$this->db->where('id_cliente',$data['id_cliente']);
				$query = $this->db->get();
				foreach ($query->result() as $row)
				{
				 $cuerpo="<h2><h2>Han realizado una cotización de vuelo</h2><br>
				 <h4>Cliente: ".$row->nombre."</h4><br>
				 <h4>Punto de salida: " . $data['ciudad_partida'] . "</h4><br>
				 <h4>Punto de llegada: " . $data['ciudad_llegada'] ."</h4><br>
				 <h4>Fue procesada con éxito, pendiente de respuesta</h4><br>
				 <h4>Verificar Cotización: ".$this->Conf_model->SISTEMA."</h4>	
				 <br>Atte:<br>Martínez Travel & Tours";
				}
				 
				 $this->Mail_model->metEnviar('Cotización de vuelo','Cotización de Cliente',$cuerpo);

				//fin de para mandar correo

				// enviar correo electronico a usuarios tipo empleado
				// informacion al interiror de $data 
				// {
				// 	"id_cliente": "2036220712",
				// 	"ciudad_partida": "San Salvador",
				// 	"fechaPartida": "2021-10-14",
				// 	"HoraPartida": "17:47:00",
				// 	"ciudad_llegada": "Cartago",
				// 	"adultos": "1",
				// 	"ninos": "2",
				// 	"bebes": "3",
				// 	"maletas": "4",
				// 	"idaerolinea": "1",
				// 	"idclase": "1",
				// 	"idtipo_viaje": "2",
				// 	"detallePasajero": "",
				// 	"opc_avanzadas": " rrrff, ",
				// 	"fechaLlegada": "2021-10-15",
				// 	"HoraLlegada": "17:47:00"
				// }
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
		if (!isset($data["id_cotizacion"])) {
			$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de la Cotizacion');
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			try {
				$respuesta = $this->cotizarVuelo_model->editar($data);
				if ($respuesta['err']) {
					$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
				} else {
					 //para mandar el correo
					 $this->db->select('*');
					 $this->db->from('cotizacion_vuelo');
					 $this->db->where('id_cotizacion',$data['id_cotizacion']);
					 $query = $this->db->get();
					 foreach ($query->result() as $row)
					 {
						 $id=$row->id_cliente;
						 $cuerpo="<h2><h2>La cotización realizada con salida de: " . $row->ciudad_partida. "</h2><br>
						 <h4>con llegada a: " . $row->ciudad_llegada . " fue procesada con éxito.
						 Con un precio de: $" . $data['total'] . " con un descuento de: $" . $data['descuentos'] ."</h4>
						 </h4><br><h4>Gracias por preferirnos, puedes verificar la respuesta a tu cotización nuestra página web: ".$this->Conf_model->PAGINA."
						 </h4><br>También puedes descargar nuestra aplicación móvil<br>Atte:<br>Martínez Travel & Tours";
					 }
					
					  $this->Mail_model->metEnviarUno('Cotización de Vuelo','','Respuesta Cotización de Vuelo',$cuerpo,$id);
					// ENVIAR CORREO A CLIENTE QUE HIZO LA COTIZACION
					// INFORMACION QUE VIENE EN $data 
					// "id_cotizacion": "15",
					// "descuentos": "2",
					// "total": "120"
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

	public function mostrarCotizacion_get()
	{

		$data = $this->get();
		$cotizacion = $this->cotizarVuelo_model->get_mostrarCotizacion($data);

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

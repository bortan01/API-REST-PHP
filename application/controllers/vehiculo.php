<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class vehiculo extends REST_Controller
{

	public function __construct()
	{
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Vehiculo_model');
	}
	public function vehiculos_get()
	{
		$data = $this->get();
		$carro = $this->Vehiculo_model->get_vehiculo($data);

		if (isset($carro)) {
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'autos' => $carro

			);
			$this->response($respuesta, REST_Controller::HTTP_OK);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'autos' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function get_vehiculoForApp(array $data)
	{
		$this->db->select('*');
		$this->db->from('vehiculo');
		$this->db->join('transmisionvehiculo', 'vehiculo.id_transmicionFK=transmisionvehiculo.idtransmicion');
		$this->db->join('modelo', 'vehiculo.idmodelo = modelo.idmodelo');
		$this->db->join('marca_vehiculo', 'modelo.id_marca = marca_vehiculo.id_marca');
		$this->db->join('categoria', 'vehiculo.idcategoria=categoria.idcategoria');
		$this->db->join('usuario', 'vehiculo.id_rentaCarFK=usuario.id_cliente');

		if (isset($data['idCategoria']) && !empty($data['idCategoria'])) {
			$this->db->where('vehiculo.idcategoria', $data['idCategoria']);
		}


		$this->db->where('vehiculo.activo', 1);
		$query = $this->db->get();

		$respuesta = $query->result();
		$this->load->model('Imagen_model');
		foreach ($respuesta as $row) {
			$row->opc_avanzadas =   explode(",", $row->opc_avanzadas);
			$identificador = $row->idvehiculo;
			$respuestaFoto =   $this->Imagen_model->obtenerImagenUnica('vehiculo', $identificador);
			if ($respuestaFoto == null) {
				//por si no hay ninguna foto mandamos una por defecto
				$row->foto = "http://localhost/API-REST-PHP/uploads/auto.png";
			} else {
				$row->foto = $respuestaFoto;
			}
			$respuestaGaleria =   $this->Imagen_model->obtenerGaleria('vehiculo', $identificador);
			if ($respuestaGaleria == null) {
				//por si no hay ninguna foto mandamos una por defecto
				$row->galeria = [];
			} else {
				$row->galeria = $respuestaGaleria;
			}
		}




		return $respuesta;
	}

	//INSERTAR
	public function vehiculo_post()
	{
		$data = $this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);

		if ($this->form_validation->run('autos_put')) {

			$carro = $this->Vehiculo_model->set_datos($data);

			$respuesta = $carro->insert();

			if ($respuesta['err']) {

				$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
			} else {
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
	public function actualizarVehiculo_put()
	{

		$data = $this->put();
		if (!isset($data["idvehiculo"])) {
			$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador del Vehiculo');
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			try {
				$respuesta = $this->Vehiculo_model->editar($data);
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
	public function eliminarVehiculo_delete()
	{
		$data = $this->delete();
		if (!isset($data["idvehiculo"])) {
			$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador del Vehiculo');
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			$campos = array('idvehiculo' => $data["idvehiculo"], 'activo' => FALSE);

			try {
				$respuesta = $this->Vehiculo_model->borrar($campos);
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


	public function flota_get()
	{
		$data = $this->get();
		$carro = $this->Vehiculo_model->getFlota($data);

		if (isset($carro)) {

			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'autos' => $carro

			);
			$this->response($respuesta, REST_Controller::HTTP_OK);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'autos' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}
	public function show_get()
	{
		$data                = $this->get();
		$carro               = $this->Vehiculo_model->get_vehiculoForApp($data);
		$opcionesAdicionales = $this->Vehiculo_model->get_opciones();
		if (isset($carro)) {
			$respuesta = array(
				'err'                 => FALSE,
				'mensaje'             => 'Registro Cargado correctamente',
				'autos'               => $carro,
				'opcionesAdicionales' => $opcionesAdicionales

			);
			$this->response($respuesta, REST_Controller::HTTP_OK);
		} else {
			$respuesta = array(
				'err'     => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'autos'   => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}
}
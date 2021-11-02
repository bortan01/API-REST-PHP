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
class Notification extends REST_Controller
{
	public function __construct()
	{
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Notification_model');
	}
	public function show_get()
	{
		$respuesta = $this->Notification_model->obtenerNotifications();
		$this->response($respuesta, REST_Controller::HTTP_OK);
	}
	public function showUltimasReservas_get()
	{
		$respuesta = $this->Notification_model->getInfoUltimasReservas();
		$this->response($respuesta, REST_Controller::HTTP_OK);
	}
	public function estadoNotificacion_put()
	{
		$data = $this->put();
		// $data['ultimaConexion'] = DateTime::createFromFormat('d/m/Y',  new DateTime())->format('Y-m-d');
		$respuesta = $this->Notification_model->actualizarReserva($data);
		if ($respuesta['err']) {
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			$this->response($respuesta, REST_Controller::HTTP_OK);
		}
	}
}
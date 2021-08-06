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
class FormularioMigratorio extends REST_Controller
{

	public function __construct()
	{
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Cita_model');
		$this->load->model('Pregunta_model');
		$this->load->model('FormularioMigratorio_model');
		$this->load->model('Rama_model');
	}

	public function save_post()
	{
		$data         = $this->post();
		$id_cita      = $data['id_cita'];

		$AllQuestion  = json_decode($data['AllQuestion'],  true);
		$respuesta    = $this->FormularioMigratorio_model->guardar($AllQuestion);
		if ($respuesta['err']) {
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			$this->Cita_model->formularioModificar($id_cita);
			$this->response($respuesta, REST_Controller::HTTP_OK);
		}
	}
	public function update_post()
	{
		$data = $this->post();
		$newQuestion = json_decode($data['newQuestion'],  true);
		$oldQuestion = json_decode($data['oldQuestion'],  true);
		// print_r($AllQuestion);
		// die();

		$respuestaGuardar    = $this->FormularioMigratorio_model->guardar($newQuestion);
		if ($respuestaGuardar['err']) {
			// si no pudo guardar las pregutnas nuevas enviamos el error
			$this->response($respuestaGuardar, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			// si puedo guardarlas procedemos a actualizar las antiguas
			$respuestaActualizar = $this->FormularioMigratorio_model->actualizar($oldQuestion);
			if ($respuestaActualizar['err']) {
				$this->response($respuestaActualizar, REST_Controller::HTTP_BAD_REQUEST);
			} else {
				$this->response($respuestaActualizar, REST_Controller::HTTP_OK);
			}
		}
	}

	public function usuarioFormularios_get()
	{
		$data = $this->get();
		//aqui estoy me falta hacer la consulta para sacar el usuario 
		$respuesta = $this->FormularioMigratorio_model->usuarioForm($data);
		$this->response($respuesta);
	}
	public function deleteFormulario_post()
	{

		$data = $this->post();
		$verificar = $this->FormularioMigratorio_model->set_datos($data);
		$respuesta = $this->FormularioMigratorio_model->eliminar($verificar);

		if ($respuesta['err']) {

			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			$this->response($respuesta);
		}
	} //fin metodo

	public function updateFormulario_post()
	{
		$data = $this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);

		if ($this->form_validation->run('formulario_put')) {
			//todo bien
			//$this->response('Todo bien');
			$id_cita = $data['id_cita'];

			//***************//para los combobox
			if (isset($data['id_pregunta'])) {
				# code...
				$id_pregunta = $data['id_pregunta'];
				$respuestas = $data['respuesta'];
			} else {
				$id_pregunta = NULL;
				$respuestas = NULL;
			}

			//para los input que solo es una pregunta
			if (isset($data['id_pregunta1'])) {
				$id_pregunta1 = $data['id_pregunta1'];
				$respuestas1 = $data['respuesta1'];
			} else {
				$id_pregunta1 = NULL;
				$respuestas1 = NULL;
			}

			$respuesta = $this->FormularioMigratorio_model->insertarActualizaciones($id_cita, $id_pregunta, $respuestas, $id_pregunta1, $respuestas1, $data);

			if ($respuesta['err']) {

				$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
			} else {
				$this->response($respuesta);
			}
		} else {
			//algo mal

			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Hay errores en el envio de la informacion',
				'errores' => $this->form_validation->get_errores_arreglo()
			);
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		}
	} //fin de metodo

	public function formulariosLlenos_get()
	{

		$id = $this->uri->segment(3);
		$opciones       = $this->Pregunta_model->get_opciones();
		$formulario     = $this->FormularioMigratorio_model->get_formularios_llenos($id);
		$ramas = $this->Rama_model->get_rama();
		$clienteReporte = $this->FormularioMigratorio_model->clienteFormulario($id);
		$data = array('id_cita' => $id);
		$personas = $this->Cita_model->verCita($data);
		$masRespuesta = $this->FormularioMigratorio_model->get_masRespuesta($id);
		$preguntas_mas = $this->Pregunta_model->get_pregustasMas();
		if (isset($formulario)) {
			//quitar campos que no quiero
			//unset($cliente->telefono1);
			//sunset($cliente->telefono2);
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'formulario' => $formulario,
				'opciones' => $opciones,
				'cliente' => $clienteReporte,
				'ramas' => $ramas,
				'pesonas' => $personas,
				'mas' => $masRespuesta,
				'preguntas_mas' => $preguntas_mas
			);
			$this->response($respuesta);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.'
			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function formularios_get()
	{

		$formulario = $this->FormularioMigratorio_model->get_form();

		if (isset($formulario)) {
			//quitar campos que no quiero
			//unset($cliente->telefono1);
			//sunset($cliente->telefono2);
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'formulario' => $formulario

			);
			$this->response($respuesta);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.'
			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	} //fin metodo

	public function formulario_post()
	{

		$data = $this->post();
		echo   "<pre class='xdebug-var-dump' dir='ltr'>
		<small>C:\wamp64\www\API-REST-PHP\application\controllers\FormularioMigratorio.php:174:</small>
		<b>array</b> <i>(size=2)</i>
		  'respuestas' <font color='#888a85'>=&gt;</font> 
			 <b>array</b> <i>(size=7)</i>
				0 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'2021-07-09'</font> <i>(length=10)</i>
				1 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'(+239) 4123-4021'</font> <i>(length=16)</i>
				2 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'verapaz san vicente'</font> <i>(length=19)</i>
				3 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'42038402-8'</font> <i>(length=10)</i>
				4 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'3420-398402-830-4'</font> <i>(length=17)</i>
				5 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'K80800809'</font> <i>(length=9)</i>
				6 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'2021-07-23'</font> <i>(length=10)</i>
		  'respuesta1' <font color='#888a85'>=&gt;</font> 
			 <b>array</b> <i>(size=3)</i>
				0 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Si'</font> <i>(length=2)</i>
				1 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Si'</font> <i>(length=2)</i>
				2 <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'Si'</font> <i>(length=2)</i>
		</pre>";
		// var_dump($data);
		die();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);

		if ($this->form_validation->run('formulario_put')) {
			//todo bien
			//$this->response('Todo bien');
			$id_cita = $data['id_cita'];

			//para los combobox
			if (isset($data['id_pregunta'])) {
				# code...
				$id_pregunta = $data['id_pregunta'];
				$respuestas = $data['respuesta'];
			} else {
				$id_pregunta = NULL;
				$respuestas = NULL;
			}

			//para los input que solo es una pregunta
			if (isset($data['id_pregunta1'])) {
				$id_pregunta1 = $data['id_pregunta1'];
				$respuestas1 = $data['respuesta1'];
			} else {
				$id_pregunta1 = NULL;
				$respuestas1 = NULL;
			}
			//die();
			$this->Cita_model->formularioModificar($id_cita);
			$respuesta = $this->FormularioMigratorio_model->insertarFormularios($id_cita, $id_pregunta, $respuestas, $id_pregunta1, $respuestas1, $data);

			if ($respuesta['err']) {

				$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
			} else {
				$this->response($respuesta);
			}
		} else {
			//algo mal

			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Hay errores en el envio de la informacion',
				'errores' => $this->form_validation->get_errores_arreglo()
			);
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		}
	} //fin metodo

}
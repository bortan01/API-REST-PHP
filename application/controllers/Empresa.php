<?php
defined('BASEPATH') or exit('No direct script access allowed');
// $allowedOrigins = [
// 	"https://admin.tesistours.com",
// 	"https://tesistours.com"
// ];
// if (isset($_SERVER["HTTP_ORIGIN"]) && in_array($_SERVER["HTTP_ORIGIN"], $allowedOrigins)) {
// 	header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
// }
require APPPATH . '/libraries/REST_Controller.php';
class Empresa extends REST_Controller
{

	public function __construct()
	{
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Empresa_model');
		$this->load->model('Restore_model');
		$this->load->helper('url');
		$this->load->helper('file');
		$this->load->helper('download');
		$this->load->library('zip');
		$this->load->database();
		$this->load->dbforge();


	}

	public function municipios_get()
	{
		$data = $this->get();
		$municipio = $this->Empresa_model->get_municipio($data);

		if (isset($municipio)) {

			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'municipios' => $municipio

			);
			$this->response($respuesta, REST_Controller::HTTP_OK);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'municipios' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function deptos_get()
	{

		$deptos = $this->Empresa_model->get_deptos();

		if (isset($deptos)) {
			//quitar campos que no quiero
			//unset($cita->motivo);
			//unset($cliente->telefono2);
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registros cargados correctamente',
				'deptos' => $deptos
			);

			$this->response($respuesta);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'citas' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}
	public function empresas_get()
	{
		$data = $this->get();
		$empresa = $this->Empresa_model->get_empresas($data);

		if (isset($empresa)) {
			//quitar campos que no quiero
			//unset($cita->motivo);
			//unset($cliente->telefono2);
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registros cargados correctamente',
				'empresa' => $empresa
			);

			$this->response($respuesta);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'citas' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}



	public function empresa_post()
	{

		$data = $this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);
		if ($this->form_validation->run('empresa_put')) {
			//todo bien
			//$this->response('Todo bien');
			$empresa = $this->Empresa_model->set_datos($data);
			$respuesta = $empresa->insertarEmpresa();

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
	}
	public function backup_get()
	{
		$this->load->dbutil();
		$db_format = array('format' => 'zip', 'filename' => 'my_db_backup.sql');
		$backup = $this->dbutil->backup($db_format);
		$dbname = 'backup-on-' . date('Y-m-d') . '.zip';
		$save = 'assets/db_backup/' . $dbname;
		write_file($save, $backup);
		force_download($dbname, $backup);
	}
	public function restore_post()
	{
		$this->Restore_model->droptable();	
		die();	
		$fupload = $_FILES["datafile"];
		$name = $_FILES["datafile"]["name"];
		
		if(isset($fupload)){
			$local_file = $fupload["tmp_name"];
			$directorio = "$name";
			move_uploaded_file($local_file,"$directorio");
			
		}
		$is_file = file_get_contents($directorio);
		$string_query = trim($is_file, "\n;");
		$array_query = explode(";", $string_query);

		foreach ($array_query as $query) {
			$this->db->query($query);
		}
		unlink($directorio);
	
	}
}
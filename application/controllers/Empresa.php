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
		$this->load->model('Conf_model');
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

		$connection = mysqli_connect(
			$this->Conf_model->HOST,
			$this->Conf_model->USER,
			$this->Conf_model->PASS,
			$this->Conf_model->DBNM
		);

		// ORDEN INVERSO EN EL QUE SE ELIMINARN LAS TABLAS
		$tables = [
			"galeria",
			"chat_record",
			"detalle_servicio",
			"servicios_adicionales",
			"tipo_servicio",
			"contacto",
			"itinerario",
			"sitio_turistico",
			"tipo_sitio",
			"reserva_tour",
			"detalle_tour",
			"tours_paquete",
			"cotizar_tourpaquete",
			"galeria_vehiculo",
			"cotizarvehiculo",
			"mantenimiento",
			"reserva_vehiculo",
			"detalle_serviciosvehiculo",
			"detalle_vehiculo",
			"vehiculo",
			"modelo",
			"transmisionvehiculo",
			"usuarioRentaCar",
			"rentaCar",
			"categoria",
			"marca_vehiculo",
			"servicios_opc",
			"bitacora",
			"comision",
			"detalle_encomienda",
			"tarifa",
			"unidades_medidas",
			"producto",
			"detalle_destino",
			"detalle_envio",
			"encomienda",
			"municipio_envio",
			"cita",
			"opciones_respuestas",
			"formulario_migratorio",
			"pregunta",
			"ramas_preguntas",
			"info_adicional",
			"general",
			"cotizacion_vuelo",
			"tipo_viaje",
			"promocion_vuelo",
			"aerolinea",
			"alianza",
			"tipo_clase",
			"usuariorentacar",
			"rentacar",
			"usuario"
		];

		$sql = '';
		foreach (array_reverse($tables) as $table) {
			// OBTENEMOS TODA LA DATA DE LA TABLA ITERADA
			$result = mysqli_query($connection, "SELECT * FROM " . $table);
			$num_fields = mysqli_num_fields($result);
			// EMPEZAMOS A CREAAR EL SQL
			$sql .= 'DROP TABLE IF EXISTS ' . $table . ';';
			$row2 = mysqli_fetch_row(mysqli_query($connection, "SHOW CREATE TABLE " . $table));
			$sql .= "\n\n" . $row2[1] . ";\n\n";

			for ($i = 0; $i < $num_fields; $i++) {
				while ($row = mysqli_fetch_row($result)) {
					$sql .= "INSERT INTO " . $table . " VALUES(";
					for ($j = 0; $j < $num_fields; $j++) {
						$row[$j] = addslashes($row[$j]);
						if ($row[$j] == '') {
							// SI EL VALOR ESTA VACIO CONCATENAMOS UN NULL
							$sql .= "NULL";
						} else if (isset($row[$j])) {
							// CONCATENAMOS EL VALOR DEL CAMPO
							$sql .= '"' . $row[$j] . '"';
						} else {
							$sql .= '""';
						}
						// AGREGAMOS UNA COMA SI ES EL ULTIMO CAMPO
						if ($j < $num_fields - 1) {
							$sql .= ',';
						}
					}
					$sql .= ");\n";
				}
			}
			$sql .= "\n\n\n";
		}

		// CORREGIMOS NUESTRO SQL POR EL PROBLEMA DE COMILLAS
		$order     = array('"');
		$replace   = "'";
		$scriptSql = str_replace($order, $replace, $sql);

		$order     = array('"[\"');
		$replace   = `'[\"`;
		$scriptSql = str_replace($order, $replace, $sql);

		$order     = array(`\']"`);
		$replace   = `\"]'`;
		$scriptSql = str_replace($order, $replace, $sql);
		// DEFINIMOS NUESTRA ZONA HORARIA
		date_default_timezone_set('America/El_Salvador');
		$nombreBase =	"backup-on-" . date('Y-m-d-H-i-s') . ".sql";
		force_download($nombreBase, $scriptSql);
	}

	public function restore_post()
	{
		$this->Restore_model->droptable();

		$connection = mysqli_connect(
			$this->Conf_model->HOST,
			$this->Conf_model->USER,
			$this->Conf_model->PASS,
			$this->Conf_model->DBNM
		);


		if (isset($_FILES['sqlFile'])) {

			$filename =   $_FILES['sqlFile']["tmp_name"];

			$handle = fopen($filename, "r+");
			$contents = fread($handle, filesize($filename));

			$sql = explode(';', $contents);
			foreach ($sql as $query) {
				$result = mysqli_query($connection, $query);
				if ($result) {
					// echo '<tr><td><br></td></tr>';
					// echo '<tr><td>' . $query . ' <b>SUCCESS</b></td></tr>';
					// echo '<tr><td><br></td></tr>';
				} else {
					// echo '<br><tr><td> -->>>>' . $query . ' <b>FAIL</b></td></tr><br>';
				}
			}
			fclose($handle);
			$this->response(array('respuesta' => 'Restauración Exitosa'), REST_Controller::HTTP_OK);
		}
	}
}
<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('America/El_Salvador');
class Cita_model extends CI_Model
{
	public $id_cita;
	public $id_cliente;
	public $title;
	public $color;
	public $textColor;
	public $start;
	public $fecha;
	public $hora;
	public $asistencia;


	public function ingresos($data)
	{
		//var_dump($data);

		//die();
		$this->db->select('*');
		$this->db->from("cita");
		$this->db->join('usuario', 'usuario.id_cliente=cita.id_cliente', 'inner');
		$this->db->where('fecha >=', $data['fechaInicio']);
		$this->db->where('fecha <=', $data['fechaFin']);
		$ingresos = $this->db->get();
		$result  = $ingresos->result();
		$cuantos = count($result);
		if ($cuantos > 0) {
			return $respuesta =  array(
				'err' => FALSE,
				'ingresos' => $result,
				'cuantos' => $cuantos
			);
		} else {
			return $respuesta =  array(
				'err' => FALSE,
				'ingresos' => $result,
				'cuantos' => $cuantos
			);
		}
	}

	public function verCita($data)
	{

		$personas = [];
		$pasaportes = [];

		$query = $this->db->get_where('cita', array('id_cita' => $data['id_cita']));
		$cita = $query->row();
		$result  = $query->result();

		/*$this->db->select('*');
    $this->db->from("cita");
    $this->db->where('id_cliente',$data['id_cliente']);
    $cita = $this->db->get();
    $result  = $cita->result();*/

		foreach ($result as $per) {
			$personas =  json_decode($per->personas_citas, true);
			$pasaportes =  json_decode($per->pasaporte_personas, true);
		}

		if (!isset($cita)) {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'La cita no existe',
				'existe' => $cita
			);
			return $respuesta;
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Existe',
				'existe' => $cita,
				'personas' => $personas,
				'pasaportes' => $pasaportes
			);
			return $respuesta;
		}
	}

	public function existSioNo($data)
	{

		$query = $this->db->get_where('cita', array('id_cliente' => $data['id_cliente']));
		$cita = $query->row();
		$result  = $query->result();


		if (!isset($cita)) {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'La cita no existe',
				'existe' => $cita
			);
			return $respuesta;
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Existe',
				'existe' => $cita
			);
			return $respuesta;
		}
	}

	public function eliminar($datos)
	{

		$query = $this->db->get_where('cita', array('id_cita' => $datos["id_cita"]));
		$cita = $query->row();

		if (!isset($cita)) {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'La cita no existe'
			);
			return $respuesta;
		}

		$this->db->where('id_cita', $datos["id_cita"]);

		$hecho = $this->db->delete('cita');

		if ($hecho) {
			#borrado
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro eliminado correctamente'
			);
		} else {
			//error

			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al eliminar',
				'error' => $this->db->_error_message(),
				'error_num' => $this->db->_error_number()
			);
		}
		return $respuesta;
	} //fin metodo

	public function mover($id_cita, $fecha, $start, $hora)
	{

		$query = $this->db->where(array('fecha' => $fecha, 'hora' => $hora));
		$query = $this->db->get('cita');
		$cita = $query->row(); //No modificara hora

		if (isset($cita)) {
			# code...
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'La hora ya esta ocupada!!'
			);

			return $respuesta;
		}

		$datos = array(
			'fecha' => $fecha,
			'start' => $start,
			'hora' => $hora
		);

		$this->db->set($datos);
		$this->db->where('id_cita', $id_cita);
		$hecho = $this->db->update('cita');

		if ($hecho) {
			#borrado
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Cambio correctamente!',
				'cita' => $datos
			);
		} else {
			//error

			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al realizar el cambio!',
				'error' => $this->db->_error_message(),
				'error_num' => $this->db->_error_number()
			);
		}
		return $respuesta;
	}

	public function formularioModificar($id)
	{
		//aqui vamos a modificar el estado, cuando ya se aya llenado el formulario
		//al cliente
		$this->db->set(array('color' => '#FF0040', 'estado_cita' => 0, 'asistencia' => 'Multiples Ocaciones'));
		$this->db->where('id_cita', $id);
		$respuestas = $this->db->update('cita');
		return $respuestas;
	}
	public function modificar_cita($id_cita, $hora, $fecha)
	{
		//$this->db->set($datos);
		$horas_validas = array(
			0 => '8:00 AM',
			1 => '9:00 AM',
			2 => '10:00 AM',
			3 => '11:00 AM',
			5 => '1:00 PM',
			6 => '2:00 PM',
			7 => '3:00 PM'
		);
		$el_pollo = array(0 => '12:00 PM');

		if (in_array($hora, $el_pollo)) {
			# es la hora del pollo
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Hora de almuerzo!!'
			);

			return $respuesta;
		} else {

			//VAMOS A VERIFICAR QUE LA HORA QUE LLEGUE TENGA LA DIFERENCIA DE 1HR
			if (in_array($hora, $horas_validas)) {
				# si esta dentro de las horas validas va ha pasar por el desorden de abajo


				$query = $this->db->where(array('id_cita' => $id_cita, 'hora' => $hora));
				$query = $this->db->get('cita');
				$cita = $query->row(); //No modificara hora

				if (!isset($cita)) {

					$datos = array(
						'start' => $fecha . ' ' . $hora,
						'hora' => $hora
					);

					$this->db->set($datos);
					$this->db->where('id_cita', $id_cita);
					$hecho = $this->db->update('cita');
					if ($hecho) {
						#borrado
						$respuesta = array(
							'err' => FALSE,
							'mensaje' => 'Registro actualizado correctamente',
							'cita' => $datos
						);
					} else {
						//error

						$respuesta = array(
							'err' => TRUE,
							'mensaje' => 'Error al actualizar',
							'error' => $this->db->_error_message(),
							'error_num' => $this->db->_error_number()
						);
					}
					return $respuesta;
				} else {

					$respuesta = array(
						'err' => TRUE,
						'mensaje' => 'Hora no valida, es la hora actual que posee!!'
					);
					return $respuesta;
				}
			} else {

				$respuesta = array(
					'err' => TRUE,
					'mensaje' => 'Hora no valida, Las asesorías duran 1HR!!'
				);

				return $respuesta;
			}
		}	//else pollo
	} //function

	public function insertCita($id_cliente, $motivo, $color, $textColor, $start, $fecha, $hora)
	{
		//insertar el registro
		$horas_validas = array(
			0 => '8:00 AM',
			1 => '9:00 AM',
			2 => '10:00 AM',
			3 => '11:00 AM',
			5 => '1:00 PM',
			6 => '2:00 PM',
			7 => '3:00 PM'
		);
		$el_pollo = array(0 => '12:00 PM');

		if (in_array($hora, $el_pollo)) {
			# es la hora del pollo
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Hora de almuerzo!!'
			);

			return $respuesta;
		} else {

			//VAMOS A VERIFICAR QUE LA HORA QUE LLEGUE TENGA LA DIFERENCIA DE 1HR
			if (in_array($hora, $horas_validas)) {
				# si esta dentro de las horas validas va ha pasar por el desorden de abajo
				$query = $this->db->where(array('fecha' => $fecha, 'id_cliente' => $id_cliente));
				$query = $this->db->get('cita');
				$cliente_el_mismo_dia = $query->row(); //si es el mismo cliente para el mismo dia

				if (!isset($cliente_el_mismo_dia)) {
					# -------
					$query = $this->db->where(array('fecha' => $fecha, 'hora' => $hora));
					$query = $this->db->get('cita');
					$cita = $query->row(); //si ya esta esa hora con esa fecha

					if (!isset($cita)) {


						$this->id_cita = $this->db->insert_id();
						$this->id_cliente = $id_cliente;

						$this->title = $motivo;
						$this->textColor = "#FFFFFF";
						$this->start = $start;
						$this->fecha = $fecha;
						$this->hora = $hora;



						//ANTES DE INSERTAR NECESITO ESTE ID  ESTO YA VEREMOS
						//vamos a extraer el id de la cita con que se registro la primera vez
						$this->db->select('id_cita,fecha');
						$this->db->from('cita');
						$this->db->where('id_cliente', $id_cliente);
						$this->db->order_by('id_cita', 'DESC');
						$id_citaExistente = $this->db->get();
						$row = $id_citaExistente->row('id_cita');
						////************************



						if ($row == null) {
							$this->color = "#007bff";
							$this->asistencia = 'Primera vez';
							$hecho = $this->db->insert('cita', $this);
							if ($hecho) {
								#insertado
								$respuesta = array(
									'err' => FALSE,
									'mensaje' => 'Registro insertado correctamente',
									'cita_id' => $this->db->insert_id(),
									'ver' => $this,
									'row' => $row
								);
							} else {
								//error
								$this->load->model('Imagen_model');
								$identificador = $this->db->insert_id();

								$respuesta = array(
									'err' => TRUE,
									'mensaje' => 'Error al insertar',
									'error' => $this->db->_error_message(),
									'error_num' => $this->db->_error_number()
								);
							}
						} else {
							$this->color = "#FF0040";
							$this->asistencia = 'Otra vez';
							$hecho = $this->db->insert('cita', $this);
							if ($hecho) {
								#insertado
								$respuesta = array(
									'err' => FALSE,
									'mensaje' => 'Registro insertado correctamente',
									'cita_id' => $this->db->insert_id(),
									'ver' => $this,
									'row' => $row
								);
							} else {
								//error
								$this->load->model('Imagen_model');
								$identificador = $this->db->insert_id();

								$respuesta = array(
									'err' => TRUE,
									'mensaje' => 'Error al insertar',
									'error' => $this->db->_error_message(),
									'error_num' => $this->db->_error_number()
								);
							}
							///**********************fin 
						}

						return $respuesta;
					} else {
						$respuesta = array(
							'err' => TRUE,
							'mensaje' => 'La hora ya esta ocupada'
						);

						return $respuesta;
					} //fin else para la hora
				} else {

					$respuesta = array(
						'err' => TRUE,
						'mensaje' => 'No se puede registrar cita, el cliente ya tiene una para este dia'
					);

					return $respuesta;
				} //fin else para el mismo cliene el mismo dia
			} else {
				$respuesta = array(
					'err' => TRUE,
					'mensaje' => 'Hora no valida, Las asesorías duran 1HR!!'
				);

				return $respuesta;
			}
		}
	} //fin de insertar la pregunta

	public function get_formularios()
	{

		$this->db->select('cita.id_cita,usuario.nombre,DATE_FORMAT(cita.fecha, "%d-%m-%Y") as fecha,cita.hora');
		$this->db->from('cita');
		$this->db->join('usuario', 'usuario.id_cliente=cita.id_cliente', 'inner');
		$this->db->where(array('color' => '#007bff'));
		$query = $this->db->get();
		return $query->result();
	}

	public function get_citasFormulario()
	{

		$this->db->select('*');
		$this->db->from('cita');
		$this->db->join('usuario', 'usuario.id_cliente=cita.id_cliente', 'inner');
		// $this->db->where(array('fecha' => date("Y-m-d"), 'estado_cita' => 1));
		//$this->db->where(array('estado_cita'=>1));
		$query = $this->db->get();
		return $query->result();
	}

	public function get_citas()
	{

		$this->db->select('*');
		$this->db->from('cita');
		$this->db->join('usuario', 'usuario.id_cliente=cita.id_cliente', 'inner');
		$query = $this->db->get();
		return $query->result();
	}

	//*********citas pagina web
	public function get_citasWeb($data)
	{

		$parametros = $this->verificar_camposEntrada($data);
		$where = $parametros['id_cliente'];


		$this->db->select('*');
		$this->db->from('cita');
		$this->db->join('usuario', 'usuario.id_cliente=cita.id_cliente', 'inner');
		$this->db->select('DATE_FORMAT(cita.fecha,"%d-%m-%Y") as fechaConver');
		$this->db->where('cita.id_cliente', $where);
		$query = $this->db->get();
		return $query->result();
	}

	//*********************

	public function set_datos($data_cruda)
	{
		$objeto = array();
		///par aquitar campos no existentes
		foreach ($data_cruda as $nombre_campo => $valor_campo) {
			# para verificar si la propiedad existe..
			if (property_exists('Cita_model', $nombre_campo)) {
				$objeto[$nombre_campo] = $valor_campo;
			}
		}

		return $objeto;
	} //fin de capitalizar los datos segun el modelo y campos correctos de la base



	//VERIFICAR DATOS
	public function verificar_camposEntrada($dataCruda)
	{
		$objeto = array();
		///quitar campos no existentes
		foreach ($dataCruda as $nombre_campo => $valor_campo) {
			# para verificar si la propiedad existe..
			if (property_exists('Cita_model', $nombre_campo)) {
				$objeto[$nombre_campo] = $valor_campo;
			}
		}
		return $objeto;
	}

	public function getPasaportes(array $data)
	{
		$parametros = $this->verificar_camposEntrada($data);

		$this->load->model('Imagen_model');
		$this->db->select('*');
		$this->db->select('cita.id_cita,usuario.nombre,DATE_FORMAT(cita.fecha, "%d-%m-%Y") as fecha,cita.hora');
		$this->db->from('cita');
		$this->db->join('usuario', 'usuario.id_cliente=cita.id_cliente', 'inner');
		//$this->db->join('formulario_migratorio','formulario_migratorio.id_cita=cita.id_cita','inner');
		$this->db->where(array('estado_cita' => 0));

		$this->db->where($parametros);

		$query = $this->db->get();

		$respuesta = $query->result();
		foreach ($respuesta as $row) {

			$identificador = $row->id_cita;
			$respuestaFoto =   $this->Imagen_model->obtenerImagen('pasaportes', $identificador);
			if ($respuestaFoto == null) {
				//por si no hay ninguna foto mandamos una por defecto
				$row->foto = [];
			} else {
				$row->foto = $respuestaFoto;
			}
		}
		return $respuesta;
	}
}
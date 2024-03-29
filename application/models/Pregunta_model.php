<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pregunta_model extends CI_Model
{

	public $id_pregunta;
	public $pregunta;
	public $opcion;
	public $mas_respuestas;
	public $tipo;
	public $id_rama;


	public function eliminar($datos)
	{

		$query = $this->db->get_where('pregunta', array('id_pregunta' => $datos["id_pregunta"]));
		$pregunta = $query->row();

		if (!isset($pregunta)) {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'La pregunta no existe'

			);
			return $respuesta;
		}
		//SI ES PREGUNTA CERRADA VA A BORRAR SUS OPCIONES RESPUESTAS
		//PERO SI ES ABIERTA SOLO VA A BORRAR LA ABIERTA	
		$this->db->where('id_pregunta', $datos["id_pregunta"]);
		$this->db->delete('opciones_respuestas');
		//***********************
		$this->db->where('id_pregunta', $datos["id_pregunta"]);
		$hecho = $this->db->delete('pregunta');

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


	public function modificar_pregunta($datos)
	{
		$this->db->set($datos);
		$this->db->where('id_pregunta', $datos["id_pregunta"]);

		$hecho = $this->db->update('pregunta');

		if ($hecho) {
			#borrado
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro actualizado correctamente',
				'preguntas' => $datos
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
	}

	public function obtener_cerradas()
	{

		$this->db->select('id_pregunta');
		$this->db->from('pregunta');
		$this->db->where('opcion', 'cerrada');
		$query = $this->db->get();
		$respuesta = array('id' => $query->result());
		return $respuesta;
	}
	public function get_pregunta()
	{
		$this->db->select('*');
		$this->db->from('pregunta');
		$this->db->join('ramas_preguntas', 'pregunta.id_rama=ramas_preguntas.id_rama', 'inner');
		$this->db->where('estado_pregunta', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_respuesta($id_cliente)
	{
		$this->db->select('*');
		$this->db->from('pregunta');
		$this->db->join('ramas_preguntas', 'pregunta.id_rama=ramas_preguntas.id_rama', 'inner');
		$this->db->where('estado_pregunta', 1);
		$query = $this->db->get();
		$listPreguntas = $query->result();

		foreach ($listPreguntas as  $pregunta) {
			$this->db->select('*');
			$this->db->from('formulario_migratorio');
			$this->db->where('id_pregunta', $pregunta->id_pregunta);
			$this->db->where('id_cliente', $id_cliente);
			$query = $this->db->get();
			$result = $query->row();

			if ($pregunta->mas_respuestas == 'Si') {
				if ($result == null) {
					$pregunta->respuesta =  json_decode('[""]', true);
				} else {
					$pregunta->id_formulario =  $result->id_formulario;
					$pregunta->respuesta =  json_decode($result->respuesta, true);
				}
			} else {
				if ($result == null) {
					$pregunta->respuesta =  '';
				} else {
					$pregunta->id_formulario =  $result->id_formulario;
					$pregunta->respuesta =  $result->respuesta;
				}
			}
		}
		return $listPreguntas;
	}
	public function get_reporte($id_cliente)
	{
		$this->db->select('*');
		$this->db->from('ramas_preguntas');
		$listaRamas = $this->db->get()->result();


		foreach ($listaRamas as  $rama) {
			$this->db->select('*');
			$this->db->from('pregunta');
			$this->db->join('formulario_migratorio', 'id_pregunta');
			// $this->db->where('id_cliente', $id_cliente);
			$this->db->where('id_rama', $rama->id_rama);

		

			$preguntas = $this->db->get()->result();
			$rama->preguntas = $preguntas;
			
			
		}
		return $listaRamas;
	}

	//preguntas con mas respuesta
	public function get_pregustasMas()
	{
		$this->db->select('*');
		$this->db->from('pregunta');
		$this->db->where(array('estado_pregunta' => 1, 'mas_respuestas' => 'Si'));
		$query = $this->db->get();
		return $query->result();
	}
	//**************

	public function get_opciones()
	{
		$this->db->select('*');
		$this->db->from('opciones_respuestas');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_abierta()
	{
		$this->db->select('*');
		$this->db->from('pregunta');
		$this->db->join('ramas_preguntas', 'pregunta.id_rama=ramas_preguntas.id_rama', 'inner');
		$this->db->where(array('opcion' => 'abierta', 'estado_pregunta' => 1));
		$query = $this->db->get();


		return $query->result();
	}
	public function get_cerrada()
	{
		$this->db->select('*');
		$this->db->from('pregunta');
		$this->db->join('ramas_preguntas', 'pregunta.id_rama=ramas_preguntas.id_rama', 'inner');
		$this->db->where(array('opcion' => 'cerrada', 'estado_pregunta' => 1));
		$query = $this->db->get();


		return $query->result();
	}

	public function verificar_campos($dataCruda)
	{
		$objeto = array();
		///par aquitar campos no existentes
		foreach ($dataCruda as $nombre_campo => $valor_campo) {
			# para verificar si la propiedad existe..
			if (property_exists('Pregunta_model', $nombre_campo)) {
				$objeto[$nombre_campo] = $valor_campo;
			}
		}

		//este es un objeto tipo cliente model
		return $objeto;
	}


	public function set_datos($data_cruda)
	{

		foreach ($data_cruda as $nombre_campo => $valor_campo) {

			if (property_exists('Pregunta_model', $nombre_campo)) {
				$this->$nombre_campo = $valor_campo;
			}
		}
		return $this; //retornamos el objeto de clase
	} //fin de capitalizar los datos segun el modelo y campos correctos de la base

	public function actualizar($id, $pregunta, $id_rama, $opcion_respuesta, $cuantos)
	{

		$data = array('pregunta' => $pregunta, 'id_rama' => $id_rama);

		$this->load->model('PreguntasCerradas_model');

		$this->db->set($data);
		$this->db->where('id_pregunta', $id);
		$hecho = $this->db->update('pregunta');
		$this->PreguntasCerradas_model->actualizarOpciones($opcion_respuesta, $cuantos, $id);
		if ($hecho) {
			#insertado
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro actualizado correctamente'
			);
		} else {
			//error

			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al insertar',
				'error' => $this->db->_error_message(),
				'error_num' => $this->db->_error_number()
			);
		}

		return $respuesta;
	}

	public function insertarCerrada($pregunta, $id_rama, $opcion, $opcion_respuesta, $cuantos)
	{
		$query = $this->db->get_where('pregunta', array('pregunta' => $pregunta));
		$pregunta_ya = $query->row();

		if (isset($pregunta_ya)) {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'La pregunta ya esta registrada'
			);
			return $respuesta;
		}
		$this->pregunta = $pregunta;
		$this->id_rama = $id_rama;
		$this->opcion = $opcion;

		$this->load->model('PreguntasCerradas_model');



		$hecho = $this->db->insert('pregunta', $this);
		$id = $this->db->insert_id();
		$this->PreguntasCerradas_model->insertarCerrada($opcion_respuesta, $cuantos, $id);
		if ($hecho) {
			#insertado
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro insertado correctamente',
				'pregunta_id' => $this->db->insert_id(),
				'ver' => $this
			);
		} else {
			//error

			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al insertar',
				'error' => $this->db->_error_message(),
				'error_num' => $this->db->_error_number()
			);
		}

		return $respuesta;
	}

	public function pregunta_auto()
	{



		$query = $this->db->get_where('pregunta', array('pregunta' => 'Cuantas personas viajan con usted'));
		$pregunta_ya = $query->row();

		if (isset($pregunta_ya)) {

			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Ya existen preguntas'

			);
			return $respuesta;
		} else {

			$dato1 = array('pregunta' => 'Cuantas personas viajan con usted', 'opcion' => 'abierta', 'mas_respuestas' => 'No', 'id_rama' => 2, 'estado_pregunta' => 0);
			$dato2 = array(
				'pregunta' => 'Nombre de las personas', 'opcion' => 'abierta',
				'mas_respuestas' => 'No', 'id_rama' => 2, 'estado_pregunta' => 0
			);

			$this->db->insert('pregunta', $dato1);
			$this->db->insert('pregunta', $dato2);

			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro insertado correctamente'

			);
			return $respuesta;
		}
	}
	public function insertarPregunta()
	{

		//verificar el correo
		$query = $this->db->get_where('pregunta', array('pregunta' => $this->pregunta));
		$pregunta_ya = $query->row();

		if (isset($pregunta_ya)) {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'La pregunta ya esta registrada'
			);
			return $respuesta;
		}

		//insertar el registro
		$hecho = $this->db->insert('pregunta', $this);

		if ($hecho) {
			#insertado
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro insertado correctamente',
				'pregunta_id' => $this->db->insert_id()
			);
		} else {
			//error

			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al insertar',
				'error' => $this->db->_error_message(),
				'error_num' => $this->db->_error_number()
			);
		}



		return $respuesta;
	} //fin de insertar la pregunta


}
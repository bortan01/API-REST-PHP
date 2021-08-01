<?php
defined('BASEPATH') or exit('No direct script access allowed');
class FormularioMigratorio_model extends CI_Model
{

	public $id_formulario;
	public $id_pregunta;
	public $id_cita;
	public $respuesta;
	public $identificador_persona;


	public function guardar(array $preguntas)
	{
		$nombreTabla = "formulario_migratorio";
		if (count($preguntas) < 1) {
			$respuesta = array(
				'err'          => FALSE,
				'mensaje'      => "NO SE INSERTO NINGUN REGISTRO",
			);
			return $respuesta;
		} else {
			$insert = $this->db->insert_batch($nombreTabla, $preguntas);
			if (!$insert) {
				//NO GUARDO
				$respuesta = array(
					'err'          => TRUE,
					'mensaje'      => 'Error al insertar ', $this->db->error_message(),
					'error_number' => $this->db->error_number(),
				);
				return $respuesta;
			} else {
				$respuesta = array(
					'err'          => FALSE,
					'mensaje'      => 'Registro Guardado Exitosamente',
				);
				return $respuesta;
			}
		}
	}

	public function actualizar(array $preguntas)
	{
		$nombreTabla = "formulario_migratorio";
		if (count($preguntas) < 1) {
			$respuesta = array(
				'err'          => FALSE,
				'mensaje'      => "NO SE INSERTO NINGUN REGISTRO",
			);
			return $respuesta;
		} else {
			$insert = $this->db->update_batch($nombreTabla, $preguntas, 'id_formulario');

			if (!$insert) {
				//NO SE MODIFICO NINGUNA FILA
				$error =  $this->db->error();
				if ($error['code'] != 0) {
					$respuesta = array(
						'err'          => TRUE,
						'mensaje'      => $error['message'],

					);
					return $respuesta;
				} else {
					$respuesta = array(
						'err'          => FALSE,
						'mensaje'      => 'Registro Guardado Exitosamente',
					);
					return $respuesta;
				}
			} else {
				$respuesta = array(
					'err'          => FALSE,
					'mensaje'      => 'Registro Guardado Exitosamente',
				);
				return $respuesta;
			}
		}
	}

	public function usuarioForm($data)
	{

		//saco el id de la primera pregunta
		$this->db->select('id_cliente');
		$this->db->from('cita');
		$this->db->where(array('id_cita' => $data['id_cita']));
		$id = $this->db->get();
		$row = $id->row('id_cliente');

		$this->db->select('nombre');
		$this->db->from('usuario');
		$this->db->where(array('id_cliente' => $row));
		$res = $this->db->get();

		$respuesta = array(
			'err' => TRUE,
			'mensaje' => 'Datos cargados correctamente',
			'usuario' => $res->result()
		);
		return $respuesta;
	}

	public function eliminar($datos)
	{

		$query = $this->db->get_where('formulario_migratorio', array('id_formulario' => $datos["id_formulario"]));
		$formulario = $query->row();

		if (!isset($formulario)) {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'La pregunta no existe'
			);
			return $respuesta;
		}

		$this->db->where('id_formulario', $datos["id_formulario"]);

		$hecho = $this->db->delete('formulario_migratorio');

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


	public function modificar_formulario($datos)
	{
		$this->db->set($datos);
		$this->db->where('id_formulario', $datos["id_formulario"]);

		$hecho = $this->db->update('formulario_migratorio');

		if ($hecho) {
			#borrado
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro actualizado correctamente',
				'Formularios' => $datos
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
	} //fin metodo
	//para el reporte no quiero hacer otra api reutilizo
	public function clienteFormulario($id)
	{

		$this->db->select('id_cliente');
		$this->db->from('cita');
		$this->db->where(array('id_cita' => $id));
		$id = $this->db->get();
		$row = $id->row('id_cliente');

		$this->db->select('*');
		$this->db->from('usuario');
		$this->db->where(array('id_cliente' => $row));
		$res = $this->db->get();
		return $res->result();
	}
	//fin para el reporte
	//para los input con mas de una respuesta
	// para los input que tienen mas respuesta
	public function get_masRespuesta($id)
	{
		$this->db->select('*');
		$this->db->from('pregunta');
		$this->db->join('formulario_migratorio', 'pregunta.id_pregunta=formulario_migratorio.id_pregunta', 'inner');
		$this->db->join('ramas_preguntas', 'pregunta.id_rama=ramas_preguntas.id_rama', 'inner');
		$this->db->where(array('formulario_migratorio.id_cita' => $id, 'pregunta.mas_respuestas' => 'Si'));
		$query = $this->db->get();

		//return $query->result();

		$respuesta = $query->result();
		return $respuesta;
	}
	//fin para las respuesta que tienen mas input
	public function get_formularios_llenos($id)
	{

		$this->load->model('Conf_model');
		$this->db->select('*');
		$this->db->from('pregunta');
		$this->db->join('formulario_migratorio', 'pregunta.id_pregunta=formulario_migratorio.id_pregunta', 'inner');
		$this->db->join('ramas_preguntas', 'pregunta.id_rama=ramas_preguntas.id_rama', 'inner');
		$this->db->where(array('formulario_migratorio.id_cita' => $id));
		$query = $this->db->get();

		//return $query->result();

		$respuesta = $query->result();
		//para capturar las personas

		$this->load->model('Imagen_model');
		foreach ($respuesta as $row) {

			$identificador = $row->id_cita;
			$respuestaFoto =   $this->Imagen_model->obtenerImagenUnica('pasaportes', $identificador);
			if ($respuestaFoto == null) {
				//por si no hay ninguna foto mandamos una por defecto
				$row->foto = $this->Conf_model->URL_SERVIDOR . "uploads/viaje.png";
			} else {
				$row->foto = $respuestaFoto;
			}
			$respuestaGaleria =   $this->Imagen_model->obtenerGaleria('pasaportes', $identificador);
			if ($respuestaGaleria == null) {
				//por si no hay ninguna foto mandamos una por defecto
				$row->galeria = [];
			} else {
				$row->galeria = $respuestaGaleria;
			}
		}

		return $respuesta;
	}

	public function get_form()
	{


		$query = $this->db->get('formulario_migratorio');

		return $query->result();
	}

	public function set_datos($data_cruda)
	{
		$objeto = array();
		///par aquitar campos no existentes
		foreach ($data_cruda as $nombre_campo => $valor_campo) {
			# para verificar si la propiedad existe..
			if (property_exists('FormularioMigratorio_model', $nombre_campo)) {
				$objeto[$nombre_campo] = $valor_campo;
			}
		}

		return $objeto;
	} //fin de capitalizar los datos segun el modelo y campos correctos de la base

	public function insertarActualizaciones($id_cita, $id_pregunta, $respuestas, $id_pregunta1, $respuestas1, $data)
	{

		//EXTRAER EL ID DE LAS PREGUNTAS 

		$this->db->select('id_pregunta');
		$this->db->from('pregunta');
		$this->db->where(array('pregunta' => 'Cuantas personas viajan con usted'));
		$pregunta1 = $this->db->get();
		$row = $pregunta1->row('id_pregunta');

		//saco el id de la segunda pregunta
		$this->db->select('id_pregunta');
		$this->db->from('pregunta');
		$this->db->where(array('pregunta' => 'Nombre de las personas'));
		$pregunta2 = $this->db->get();
		$row2 = $pregunta2->row('id_pregunta');
		//VAMOS A EXTRAER LAS RESPUESTA NO MODIFICABLE EN EL FORMULARIO
		$this->db->select('respuesta');
		$this->db->from('formulario_migratorio');
		$this->db->where(array('id_cita' => $id_cita, 'id_pregunta' => $row));
		$respuesta1 = $this->db->get();
		$res1 = $respuesta1->row('respuesta');
		//SEGUNDA PREGUNTA
		$this->db->select('respuesta');
		$this->db->from('formulario_migratorio');
		$this->db->where(array('id_cita' => $id_cita, 'id_pregunta' => $row2));
		$respuesta2 = $this->db->get();
		$res2 = $respuesta2->row('respuesta');
		$valic = $respuesta2->num_rows(); //PARA VALIDAR CUANDO NO LLEVEN PERSONAS
		/*echo $row;
    echo $row2;
    echo 'respuesta:'.$res1;
    echo 'respuesta2:'.$valic;*/

		//BORRAMOS POR EL TIPO DE PROCEDIMIENTO
		$this->db->where('id_cita', $id_cita);
		$this->db->delete('formulario_migratorio');


		//die();
		//PROCESO PARA ACTUALIZARLO
		//para los input normales
		if ($id_pregunta1 != NULL) {
			# code...
			$recorrer1 = count($id_pregunta1);

			for ($pivote = 0; $pivote < $recorrer1; $pivote++) {
				# code...
				$this->id_cita               = $id_cita;
				$this->id_pregunta           = $id_pregunta1[$pivote];
				$this->respuesta             = $respuestas1[$pivote];
				//insertar el registro
				$this->db->insert('formulario_migratorio', $this);
			}
		}

		//PARA VOLVER A INSERTAR LA PERSONAS QUE BORRE
		$this->id_cita               = $id_cita;
		$this->id_pregunta           = $row;
		$this->respuesta             = $res1;
		$this->identificador_persona = $id_cita;
		//insertar el registro
		$this->db->insert('formulario_migratorio', $this); //fin de pregunta 1
		$this->id_cita               = $id_cita;
		$this->id_pregunta           = $row2;
		$this->respuesta             = $res2;
		$this->identificador_persona = $id_cita;
		//insertar el registro
		$this->db->insert('formulario_migratorio', $this);
		//FIN DE PARA VOLVER A INSERTAR
		//*****************************************
		//par los input que tiene mas respuestas
		//vamos hacer una prueba piloto
		$query = $this->db->query('SELECT * FROM pregunta WHERE mas_respuestas="Si"');
		$cuantas_mas = $query->num_rows();

		for ($index = 0; $index < $cuantas_mas; $index++) {
			$this->id_cita               = $id_cita;
			$this->id_pregunta           = $data['id_pregunta_mas' . $index];
			$this->respuesta             = json_encode($data['respuesta_mas' . $index]);
			//insertar el registro
			$this->db->insert('formulario_migratorio', $this);
			//echo json_encode($data['respuesta_mas'.$index]);
		}
		//fin de prueba
		//****************************************

		//para los combobox
		if ($id_pregunta != NULL) {
			# code...

			$recorrer = count($id_pregunta);

			for ($i = 0; $i < $recorrer; $i++) {
				# code...
				$this->id_cita = $id_cita;
				$this->id_pregunta = $id_pregunta[$i];
				$this->respuesta = $respuestas[$i];
				//insertar el registro
				$hecho = $this->db->insert('formulario_migratorio', $this);
			}
		}
		//FIN DE PROCESO PARA ACTUALIZARLO

		if ($hecho) {
			#insertado
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro actualizado correctamente',
				'pregunta_id' => $this->db->insert_id(),
				'datos' => $this
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
	} //fin de insertar

	public function modificarPersonaCambiosNombres($id_cita, $personas, $cuantos)
	{

		//BORRAMOS POR EL TIPO DE PROCEDIMIENTO
		$this->db->where('identificador_persona', $id_cita);
		$this->db->delete('formulario_migratorio');

		//saco el id de la primera pregunta
		$this->db->select('id_pregunta');
		$this->db->from('pregunta');
		$this->db->where(array('pregunta' => 'Cuantas personas viajan con usted'));
		$pregunta1 = $this->db->get();
		$row = $pregunta1->row('id_pregunta');

		//saco el id de la segunda pregunta
		$this->db->select('id_pregunta');
		$this->db->from('pregunta');
		$this->db->where(array('pregunta' => 'Nombre de las personas'));

		$pregunta2 = $this->db->get();
		$row2 = $pregunta2->row('id_pregunta');


		//PARA VOLVER A INSERTAR LA PERSONAS QUE BORRE
		$this->id_cita               = $id_cita;
		$this->id_pregunta           = $row;
		$this->respuesta             = $cuantos;
		$this->identificador_persona = $id_cita;
		//insertar el registro
		$this->db->insert('formulario_migratorio', $this); //fin de pregunta 1
		$this->id_cita               = $id_cita;
		$this->id_pregunta           = $row2;
		$this->respuesta             = $personas;
		$this->identificador_persona = $id_cita;
		//insertar el registro
		$this->db->insert('formulario_migratorio', $this); //fin de pregunta 2

	} //fin del metodo
	//para modificar los id de cita en el formulario migratorio

	public function modificar_idformulario($row, $cita)
	{
		//la cita anterior en estado 1 para que me traiga el id de la cita recien registrada
		var_dump($row);
		var_dump($cita);
		$this->db->set(array('estado_cita' => 1));
		$this->db->where('id_cita', $row);
		$this->db->update('cita'); //la cita anterior

		//vamos a actualizar el estado de la cita y color
		$this->db->set(array('estado_cita' => 0, 'color' => '#FF0040'));
		$this->db->where('id_cita', $cita);
		$this->db->update('cita'); //NUEVA CITA


		//*****************************
		$this->db->set(array('id_cita' => $cita));
		$this->db->where('id_cita', $row);

		$hecho = $this->db->update('formulario_migratorio');

		if ($hecho) {
			#borrado
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro actualizado correctamente',
				// 'Categorias' => $datos
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
	} //fin metodo


	public function insertarRespuestaPersonas($cita, $id_cliente, $personas, $cuantos, $roww)
	{

		//no mas llega esta informacion pregunto esta el cliente registrado en la tabla citas
		$query_esta   = $this->db->where(array('id_cliente' => $id_cliente, 'color' => '#FF0040'));
		$query_esta   = $this->db->get('cita');
		$cliente_esta = $query_esta->row(); //si ya esta el cliete

		if (!isset($cliente_esta)) {
			# si no esta el id cliente se ejecutara el siguiente codigo
			if ($cuantos != 0) {
				$this->db->select('id_pregunta');
				$this->db->from('pregunta');
				$this->db->where(array('pregunta' => 'Cuantas personas viajan con usted'));
				$pregunta1 = $this->db->get();

				$row = $pregunta1->row('id_pregunta');

				$this->db->select('id_pregunta');
				$this->db->from('pregunta');
				$this->db->where(array('pregunta' => 'Nombre de las personas'));
				$pregunta2 = $this->db->get();
				$row2 = $pregunta2->row('id_pregunta');

				//pregunta:Cuantas personas viajan con usted
				$this->id_pregunta            = $row;
				$this->id_cita                = $cita;
				$this->respuesta              = $cuantos;
				$this->identificador_persona  = $cita;


				$this->db->insert('formulario_migratorio', $this);

				//pregunta: Nombre de las personas
				$this->id_pregunta = $row2;
				$this->id_cita = $cita;
				$this->respuesta = $personas;
				$this->identificador_persona  = $cita;
				$hecho = $this->db->insert('formulario_migratorio', $this);
				if ($hecho) {
					#insertado
					$respuesta = array(
						'err' => FALSE,
						'mensaje' => 'Registro insertado correctamente'
					);
				} else {
					//error

					$respuesta = array(
						'err' => TRUE,
						'mensaje' => 'Error al insertar',
						'error' => $this->db->_error_message(),
						'error_num' => $this->db->_error_number()
					);
				} //else hecho
			} //if cuantos
		} else {
			//si el cliente esta

			$this->db->where('identificador_persona', $roww);
			$this->db->delete('formulario_migratorio');
			//***************
			//cambiar el id de la cita a las respuesta del formulario
			//esto nos ayudara a que una nueva cita de ese cliente pero la misma informacion
			$this->FormularioMigratorio_model->modificar_idformulario($roww, $cita);

			if ($cuantos != 0) {
				$this->db->select('id_pregunta');
				$this->db->from('pregunta');
				$this->db->where(array('pregunta' => 'Cuantas personas viajan con usted'));
				$pregunta1 = $this->db->get();

				$row = $pregunta1->row('id_pregunta');

				$this->db->select('id_pregunta');
				$this->db->from('pregunta');
				$this->db->where(array('pregunta' => 'Nombre de las personas'));
				$pregunta2 = $this->db->get();
				$row2 = $pregunta2->row('id_pregunta');

				//pregunta:Cuantas personas viajan con usted
				$this->id_pregunta            = $row;
				$this->id_cita                = $cita;
				$this->respuesta              = $cuantos;
				$this->identificador_persona  = $cita;


				$this->db->insert('formulario_migratorio', $this);

				//pregunta: Nombre de las personas
				$this->id_pregunta = $row2;
				$this->id_cita = $cita;
				$this->respuesta = $personas;
				$this->identificador_persona  = $cita;
				$hecho = $this->db->insert('formulario_migratorio', $this);
				if ($hecho) {
					#insertado
					$respuesta = array(
						'err' => FALSE,
						'mensaje' => 'Registro insertado correctamente'
					);
				} else {
					//error

					$respuesta = array(
						'err' => TRUE,
						'mensaje' => 'Error al insertar',
						'error' => $this->db->_error_message(),
						'error_num' => $this->db->_error_number()
					);
				} //else hecho
			} //if cuantos
		} //else cliente esta

		return $respuesta;
	}

	public function insertarFormularios($id_cita, $id_pregunta, $respuestas, $id_pregunta1, $respuestas1, $data)
	{


		//para los input normales
		if ($id_pregunta1 != NULL) {
			# code...
			$recorrer1 = count($id_pregunta1);

			for ($pivote = 0; $pivote < $recorrer1; $pivote++) {
				# code...
				$this->id_cita               = $id_cita;
				$this->id_pregunta           = $id_pregunta1[$pivote];
				$this->respuesta             = $respuestas1[$pivote];
				//insertar el registro
				$this->db->insert('formulario_migratorio', $this);
			}
		}

		//par los input que tiene mas respuestas
		//vamos hacer una prueba piloto
		$query = $this->db->query('SELECT * FROM pregunta WHERE mas_respuestas="Si"');
		$cuantas_mas = $query->num_rows();

		for ($index = 0; $index < $cuantas_mas; $index++) {
			$this->id_cita               = $id_cita;
			$this->id_pregunta           = $data['id_pregunta_mas' . $index];
			$this->respuesta             = json_encode($data['respuesta_mas' . $index]);
			//insertar el registro
			$this->db->insert('formulario_migratorio', $this);
			//echo json_encode($data['respuesta_mas'.$index]);
		}
		//fin de prueba
		//die();
		//para los combobox
		if ($id_pregunta != NULL) {
			# code...

			$recorrer = count($id_pregunta);

			for ($i = 0; $i < $recorrer; $i++) {
				# code...
				$this->id_cita = $id_cita;
				$this->id_pregunta = $id_pregunta[$i];
				$this->respuesta = $respuestas[$i];
				//insertar el registro
				$hecho = $this->db->insert('formulario_migratorio', $this);
			}
		}
		if ($hecho) {
			#insertado
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro insertado correctamente',
				'pregunta_id' => $this->db->insert_id(),
				'datos' => $this
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
	} //fin de insertar


}
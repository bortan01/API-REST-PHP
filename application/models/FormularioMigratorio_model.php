<?php
defined('BASEPATH') or exit('No direct script access allowed');
class FormularioMigratorio_model extends CI_Model
{

	public $id_formulario;
	public $id_pregunta;
	public $id_cita;
	public $respuesta;
	public $identificador_persona;

public function usuarioForm($data){

	//saco el id de la primera pregunta
	$this->db->select('id_cliente');
    $this->db->from('cita');
    $this->db->where(array('id_cita'=>$data['id_cita']));
    $id=$this->db->get();
    $row = $id->row('id_cliente');

    $this->db->select('nombre');
    $this->db->from('usuario');
    $this->db->where(array('id_cliente'=>$row));
    $res=$this->db->get();

    $respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'Datos cargados correctamente',
				'usuario'=>$res->result()
			);
	return $respuesta;
}

public function eliminar($datos){

		$query=$this->db->get_where('formulario_migratorio',array('id_formulario'=>$datos["id_formulario"]) );
		$formulario=$query->row();

			if (!isset($formulario)) {
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'La pregunta no existe'
			);
			return $respuesta;
			}

		$this->db->where('id_formulario',$datos["id_formulario"]);

 		$hecho=$this->db->delete('formulario_migratorio');

 		if ($hecho) {
				#borrado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro eliminado correctamente'
				);
			}else{
				//error

				$respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Error al eliminar',
					'error'=>$this->db->_error_message(),
					'error_num'=>$this->db->_error_number()
				);
			
			}
 		return $respuesta;
}//fin metodo


public function modificar_formulario($datos){
		$this->db->set($datos);
 		$this->db->where('id_formulario',$datos["id_formulario"]);

 		$hecho=$this->db->update('formulario_migratorio');

 		if ($hecho) {
				#borrado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro actualizado correctamente',
					'Formularios'=>$datos
				);

			

			}else{
				//error

				$respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Error al actualizar',
					'error'=>$this->db->_error_message(),
					'error_num'=>$this->db->_error_number()
				);
			
			}
 		return $respuesta;
}//fin metodo

public function get_formularios_llenos($id){
	$this->db->select('*');
    $this->db->from('pregunta');
    $this->db->join('formulario_migratorio', 'pregunta.id_pregunta=formulario_migratorio.id_pregunta','inner');
    $this->db->join('ramas_preguntas', 'pregunta.id_rama=ramas_preguntas.id_rama','inner');
 	$this->db->where(array('formulario_migratorio.id_cita'=>$id));
    $query=$this->db->get();

    //return $query->result();

    $respuesta = $query->result();

     $this->load->model('Imagen_model');
    foreach ($respuesta as $row) {
        
        $identificador = $row->id_cita;
        $respuestaFoto =   $this->Imagen_model->obtenerImagenUnica('pasaportes', $identificador);
        if ($respuestaFoto == null) {
            //por si no hay ninguna foto mandamos una por defecto
            $row->foto = "http://localhost/API-REST-PHP/uploads/viaje.png";
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

public function get_form(){


 	$query=$this->db->get('formulario_migratorio');
 	
 		return $query->result();
}

public function set_datos($data_cruda){
    	 $objeto =array();
        ///par aquitar campos no existentes
        foreach ($data_cruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('FormularioMigratorio_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
           }

        return $objeto;
}//fin de capitalizar los datos segun el modelo y campos correctos de la base

public function insertarActualizaciones($id_cita,$id_pregunta,$respuestas,$mas_respuesta,$mas_id,$id_pregunta1,$respuestas1){

	//BORRAMOS POR EL TIPO DE PROCEDIMIENTO
	$this->db->where('id_cita',$id_cita);
    $this->db->delete('formulario_migratorio');

	$recorrer=count($id_pregunta);
	$recorrer1=count($id_pregunta1);
	$contar=count($mas_id);

	for ($pivote=0; $pivote < $recorrer1 ; $pivote++) { 
		# code...
		$this->id_cita=$id_cita;
		$this->id_pregunta=$id_pregunta1[$pivote];
		$this->respuesta=$respuestas1[$pivote];
		//insertar el registro
	  $this->db->insert('formulario_migratorio',$this);
	}

	for ($index=0; $index < $contar ; $index++) { 
		# code...
		$this->id_cita=$id_cita;
		$this->id_pregunta=$mas_id[$index];
		$this->respuesta=$mas_respuesta[$index];
		//insertar el registro
	$this->db->insert('formulario_migratorio',$this);

	}

	for ($i=0; $i < $recorrer ; $i++) { 
		# code...
		$this->id_cita=$id_cita;
		$this->id_pregunta=$id_pregunta[$i];
		$this->respuesta=$respuestas[$i];
		//insertar el registro
			$hecho=$this->db->insert('formulario_migratorio',$this);
	}


			

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro actualizado correctamente',
					'pregunta_id'=>$this->db->insert_id(),
					'datos'=>$this
				);

			

			}else{
				//error

				$respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Error al insertar',
					'error'=>$this->db->_error_message(),
					'error_num'=>$this->db->_error_number()
				);
			
			}



 		return $respuesta;
 	}//fin de insertar

public function modificarPersonaCambiosNombres($cita,$input,$asistiran){

	//BORRAMOS POR EL TIPO DE PROCEDIMIENTO
	$this->db->where('id_cita',$cita);
    $this->db->delete('formulario_migratorio');

	//saco el id de la primera pregunta
	$this->db->select('id_pregunta');
    $this->db->from('pregunta');
    $this->db->where(array('pregunta'=>'Cuantas personas viajan con usted'));
    $pregunta1=$this->db->get();
    $row = $pregunta1->row('id_pregunta');

    //saco el id de la segunda pregunta
    $this->db->select('id_pregunta');
    $this->db->from('pregunta');
    $this->db->where(array('pregunta'=>'Nombre de las personas'));
    
    $pregunta2=$this->db->get();
    $row2 = $pregunta2->row('id_pregunta');

    //cuento las personas para cambiar la cantidad
    if ($input==NULL) {
    	# code...
    	$conteo2=0;
    }else{
    	 $conteo2 = count($input);
    }
    if ($asistiran==NULL) {
    	# code...
    	$conteo1=0;
    }else{
    $conteo1= count($asistiran);
	}
   
    $cantidad_personas =($conteo1)+($conteo2);

    $this->id_pregunta = $row;
    $this->id_cita = $cita;
    $this->respuesta = $cantidad_personas;

    $this->db->insert('formulario_migratorio',$this);

    if ($conteo1>0) {
    	# code...
    	for ($i=0; $i < $conteo1 ; $i++) { 
    	# code...
    	$this->id_pregunta = $row2;
    	$this->id_cita = $cita;
    	$this->respuesta = $asistiran[$i];

    	$this->db->insert('formulario_migratorio',$this);
       }//llave for
    }

    
if ($conteo2>0) {
	# code...
	for ($i=0; $i < $conteo2 ; $i++) { 
    	# code...
    	$this->id_pregunta = $row2;
    	$this->id_cita = $cita;
    	$this->respuesta = $input[$i];

    	$this->db->insert('formulario_migratorio',$this);
    }//llave del for
}
    

}
//para modificar los id de cita en el formulario migratorio
public function modificar_idformulario($row,$cita){

		//vamos a actualizar el estado de la cita y color
		$this->db->set(array('estado_cita'=>0,'color'=>'#FF0040'));
 		$this->db->where('id_cita',$cita);
		$this->db->update('cita');//NUEVA CITA

		$this->db->set(array('estado_cita'=>1));
 		$this->db->where('id_cita',$row);
		$this->db->update('cita');//la cita anterior
		//*****************************
		$this->db->set(array('id_cita'=>$cita));
 		$this->db->where('id_cita',$row);

 		$hecho=$this->db->update('formulario_migratorio');

 		if ($hecho) {
				#borrado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro actualizado correctamente',
					'Categorias'=>$datos
				);

			

			}else{
				//error

				$respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Error al actualizar',
					'error'=>$this->db->_error_message(),
					'error_num'=>$this->db->_error_number()
				);
			
			}
 		return $respuesta;
}//fin metodo


public function insertarRespuestaPersonas($cita,$personas){
	$this->db->select('id_pregunta');
    $this->db->from('pregunta');
    $this->db->where(array('pregunta'=>'Cuantas personas viajan con usted'));
    $pregunta1=$this->db->get();

    $row = $pregunta1->row('id_pregunta');

    $this->db->select('id_pregunta');
    $this->db->from('pregunta');
    $this->db->where(array('pregunta'=>'Nombre de las personas'));
    $pregunta2=$this->db->get();
     $row2 = $pregunta2->row('id_pregunta');

    $cantidad_personas = count($personas);

    $this->id_pregunta            = $row;
    $this->id_cita                = $cita;
    $this->respuesta              = $cantidad_personas;
    $this->identificador_persona  = $cita;

    $this->db->insert('formulario_migratorio',$this);

    for ($i=0; $i < $cantidad_personas-1 ; $i++) { 
    	# code...
    	$this->id_pregunta = $row2;
    	$this->id_cita = $cita;
    	$this->respuesta = $personas[$i];

    	$this->db->insert('formulario_migratorio',$this);
    }

}

public function insertarFormularios($id_cita,$id_pregunta,$respuestas,$mas_respuesta,$mas_id,$id_pregunta1,$respuestas1){


	//para los input normales
	if ($id_pregunta1!=NULL) {
		# code...
		$recorrer1=count($id_pregunta1);

		for ($pivote=0; $pivote < $recorrer1 ; $pivote++) { 
		# code...
		$this->id_cita               = $id_cita;
		$this->id_pregunta           = $id_pregunta1[$pivote];
		$this->respuesta             = $respuestas1[$pivote];
		$this->identificador_persona = $id_cita;
		//insertar el registro
	     $this->db->insert('formulario_migratorio',$this);
	     }
	}
	
	//par los input que tiene mas respuestas
	if ($mas_id!=NULL) {
		# code...
		$contar=count($mas_id);

	 for ($index=0; $index < $contar ; $index++) { 
		# code...
		$this->id_cita               = $id_cita;
		$this->id_pregunta           = $mas_id[$index];
		$this->respuesta             = $mas_respuesta[$index];
		//insertar el registro
	    $this->db->insert('formulario_migratorio',$this);

	    }
	}
	
	//para los combobox
	if ($id_pregunta!=NULL) {
		# code...

		$recorrer=count($id_pregunta);

	    for ($i=0; $i < $recorrer ; $i++) { 
		# code...
		$this->id_cita=$id_cita;
		$this->id_pregunta=$id_pregunta[$i];
		$this->respuesta=$respuestas[$i];
		//insertar el registro
			$hecho=$this->db->insert('formulario_migratorio',$this);
	    }

	}
			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente',
					'pregunta_id'=>$this->db->insert_id(),
					'datos'=>$this
				);

			

			}else{
				//error

				$respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Error al insertar',
					'error'=>$this->db->_error_message(),
					'error_num'=>$this->db->_error_number()
				);
			
			}



 		return $respuesta;
 	}//fin de insertar


}
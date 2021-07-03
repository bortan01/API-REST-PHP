<?php
defined('BASEPATH') or exit('No direct script access allowed');
class PersonasCitas_model extends CI_Model
{
//public $id_persona;
public $id_cita;
public $nombres_personas;
public $pasaporte_personas;

public function get_personas($id){

	$this->db->where(array('id_cita'=>$id));

 	$query=$this->db->get('personas_cita');
 	return $query->result();
 }

public function modificarPersona($id_cita,$input,$asistiran,$inputPas,$pasaporte_personas){

	//BORRAMOS POR EL TIPO DE PROCEDIMIENTO
	$this->db->where('id_cita',$id_cita);
    $this->db->delete('personas_cita');
    //***************

    //CUANDO HAY CAMBIOS EN LA PERSONA 
      	$this->load->model('FormularioMigratorio_model');
	    $this->FormularioMigratorio_model->modificarPersonaCambiosNombres($id_cita,$input,$asistiran);
	    //CODIGO PARA INSERTAR LAS PERSONAS COMO SON CAPTURADOS DE DIFERENTES ARRAY
	    // VARIABLES $input y $asistiran
	if ($input!=NULL) {// SI ES NULL NO ARA NADA PORQ NO TRAE DATOS PARA EVITAR PROCEDIMIENTOS
		# code...
		$nuevos=count($input);
		for ($i=0; $i < $nuevos ; $i++) { 
		# code...
		$this->id_cita=$id_cita;
		//$this->cantidad_personas=$nuevos;
		
	    $this->nombres_personas=$input[$i];
	    $this->pasaporte_personas=$inputPas[$i];
	    $hecho=$this->db->insert('personas_cita',$this);
	    }//for

	     if ($hecho) {
				#actualizado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Actualizado'
				);
			}else{

				$respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Error'
				);

			}
	}else{

		$respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Error al insertar'
				);
	}
	
	if($asistiran!=NULL){
	$modificar=count($asistiran);
	for ($i=0; $i <$modificar ; $i++) { 
		# code...

		$this->id_cita=$id_cita;
		//$this->cantidad_personas=$modificar;
		$this->nombres_personas=$asistiran[$i];
		$this->pasaporte_personas=$pasaporte_personas[$i];
        $hecho=$this->db->insert('personas_cita',$this);
        
	   }//for

	   if ($hecho) {
				#actualizado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Actualizado'
				);
			}else{

				$respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Error'
				);

			}

	}else {
		$respuesta=array(
					'err'=>TRUE,
					'mensaje'=>'Error al insertar'
				);
	}

	//FIN DE INSERTAR LOS NOMBBRES DE LAS PERSONAS SIGUE LOS PASAPORTES

	  //CODIGO PARA INSERTAR LOS PASAPORTES COMO SON CAPTURADOS DE DIFERENTES ARRAY
	    // VARIABLES $inputPas y $pasaporte_personas

	


 		return $respuesta;

}//modificarPersonas

public function insertarPersonas($id_cliente,$cita,$personas,$pasaporte_personas,$row){
	//no mas llega esta informacion pregunto esta el cliente registrado en la tabla citas
	 $query_esta   = $this->db->where(array('id_cliente'=>$id_cliente,'color'=>'#FF0040') );
	 $query_esta   = $this->db->get('cita');
      $cliente_esta = $query_esta->row();//si ya esta el cliete

    // var_dump(!isset($cliente_esta));

     if (!isset($cliente_esta)) {
     	# si no esta el id cliente se ejecutara el siguiente codigo
     if ($personas!=NULL) {
     	# para que no me limite de entrar ha este metodo
	$cuantos=count($personas);//nombre de las personas

	$this->load->model('FormularioMigratorio_model');
	$this->FormularioMigratorio_model->insertarRespuestaPersonas($cita,$personas);
	for ($i=0; $i < $cuantos-1 ; $i++) {
		# code...
		$this->id_cita=$cita;
	    $this->nombres_personas=$personas[$i];
	    $this->pasaporte_personas=$pasaporte_personas[$i];
	    $hecho=$this->db->insert('personas_cita',$this);
	   }

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente',
					'personas_id'=>$this->db->insert_id(),
					'ver'=>$this
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
      }else{

      	$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'No traes personas a la asesoria'
				);
      	return $respuesta;

      }
    }else{

    	//si el cliente ya esta en la tabla cita pero ya tomo una asesoria
    	/*
    	 $this->db->select('main.*'); 
     $this->db->from("ci_table main"); 
     $this->db->order_by("main.id", "DESC"); 
     return $this->db->get()->result(); 
    	*/
         
          var_dump('aqi estoy'.$row);
          //die();
         //********************
         //BORRAMOS A LAS PERSONAS ANTERIORES
      	
      	$this->db->where('identificador_persona',$row);
         $this->db->delete('formulario_migratorio');
         //***************
        //cambiar el id de la cita a las respuesta del formulario
	     //esto nos ayudara a que una nueva cita de ese cliente pero la misma informacion
         $this->load->model('FormularioMigratorio_model');
	     $this->FormularioMigratorio_model->modificar_idformulario($row,$cita);

       

        if ($personas!=NULL) {
		# como es null las personas pero si ya ha tomado una asesoria
         $cuantos=count($personas);//nombre de las personas
	    
	    $this->FormularioMigratorio_model->insertarRespuestaPersonas($cita,$personas);
	    for ($i=0; $i < $cuantos-1 ; $i++) {
		# code...
		$this->id_cita=$cita;
	    $this->nombres_personas=$personas[$i];
	    $this->pasaporte_personas=$pasaporte_personas[$i];
	    $hecho=$this->db->insert('personas_cita',$this);
	    }//for

			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente',
					'personas_id'=>$this->db->insert_id(),
					'ver'=>$this
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
		}else{
			$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Exito cambios correctos'
				);
		}
        return $respuesta;

    }
}

}
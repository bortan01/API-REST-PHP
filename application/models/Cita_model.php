<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Cita_model extends CI_Model
{
public $id_cita;
public $id_cliente;
public $compania;
public $title;
public $color;
public $textColor;
public $start;
public $fecha;
public $hora;

public function eliminar($datos){

		$query=$this->db->get_where('cita',array('id_cita'=>$datos["id_cita"]) );
		$cita=$query->row();

			if (!isset($cita)) {
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'La cita no existe'
			);
			return $respuesta;
			}

		$this->db->where('id_cita',$datos["id_cita"]);

 		$hecho=$this->db->delete('cita');

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


public function modificar_cita($id_cita,$fecha,$compania,$input,$asistiran,$hora){
		//$this->db->set($datos);
		$horas_validas= array(
						0 =>'8:00 AM',
						1 =>'9:00 AM',
						2 =>'10:00 AM',
						3 =>'11:00 AM',
						5 =>'1:00 PM',
						6 =>'2:00 PM',
						7 =>'3:00 PM');
		$el_pollo = array(0 =>'12:00 PM');

		if (in_array($hora,$el_pollo)) {
			# es la hora del pollo
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'Hora de almuerzo!!'
			);

			return $respuesta;
		}else{

		//VAMOS A VERIFICAR QUE LA HORA QUE LLEGUE TENGA LA DIFERENCIA DE 1HR
         if (in_array($hora,$horas_validas)) {
			# si esta dentro de las horas validas va ha pasar por el desorden de abajo
		

	    $query=$this->db->where(array('id_cita'=>$id_cita,'hora'=>$hora) );
	    $query = $this->db->get('cita');
		$cita=$query->row();//No modificara hora

	if (isset($cita)) {

	$this->load->model('PersonasCitas_model');//cargo el modelo para actualizar en la otra tabla si es necesario
		$datos=array(
			'compania'=>$compania,
			'start'=>$fecha.' '.$hora,
			'hora'=>$hora
		);

		$this->db->set($datos);
        $this->db->where('id_cita',$id_cita);
        $hecho=$this->db->update('cita');

        $this->PersonasCitas_model->modificarPersona($id_cita,$input,$asistiran);

 		if ($hecho) {
				#borrado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro actualizado correctamente',
					'cita'=>$datos,
					'ver'=>$validar
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
		
	 }else{

	 	$query=$this->db->where(array('fecha'=>$fecha,'hora'=>$hora) );
	    $query = $this->db->get('cita');
		$cita=$query->row();//No modificara hora

		if (isset($cita)) {
			# code...
			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'La hora ya esta ocupada!!'
			);

			return $respuesta;
		}

		$this->load->model('PersonasCitas_model');//cargo el modelo para actualizar en la otra tabla si es necesario
		$datos=array(
			'compania'=>$compania,
			'start'=>$fecha.' '.$hora,
			'hora'=>$hora
		);

		$this->db->set($datos);
        $this->db->where('id_cita',$id_cita);
        $hecho=$this->db->update('cita');

        $this->PersonasCitas_model->modificarPersona($id_cita,$input,$asistiran);

 		if ($hecho) {
				#borrado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro actualizado correctamente',
					'cita'=>$datos
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



	 }//ese del si no modifica fecha

	}else{

		$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'Hora no valida, Las asesorías duran 1HR!!'
			);

			return $respuesta;
	}
  }	//else pollo
 }//function

 public function insertCita($id_cliente,$asitencia,$personas,$motivo,$color,$textColor,$start,$fecha,$hora){
		//insertar el registro
		
 		

 			$this->id_cita=$this->db->insert_id();
 			$this->id_cliente=$id_cliente;
 			$this->compania=$asitencia;
			$this->title=$motivo;
			$this->textColor="#FFFFFF";
			$this->color="#007bff";
			$this->start=$start;
			$this->fecha=$fecha;
			$this->hora=$hora;

			$this->load->model('PersonasCitas_model');
			
			
			//for ($i=0; $i <$cuantos ; $i++) {
			//$this->descripcion=$descripcion[$i];
			$hecho=$this->db->insert('cita',$this);
			if ($personas !=NULL) {
				# code...
			$cita=$this->db->insert_id();
			$this->PersonasCitas_model->insertarPersonas($cita,$personas);
			}
		    //}
			if ($hecho) {
				#insertado
				$respuesta=array(
					'err'=>FALSE,
					'mensaje'=>'Registro insertado correctamente',
					'cita_id'=>$this->db->insert_id(),
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
 	}//fin de insertar la pregunta


public function get_citas(){
   // $query=$this->db->get('cita');
 	//return $query->result();

 	$this->db->select('*');
    $this->db->from('cita');
 	$this->db->join('usuario', 'usuario.id_cliente=cita.id_cliente','inner');
    $query=$this->db->get();
    return $query->result();

 	/*foreach ($query->result() as $row)
     {
         $this->id_cita=$row->id_cita;
         $this->motivo=$row->title;
         $this->title=$row->nombre;
         $this->start=$row->start;
         $this->color=$row->color;
         $this->textColor=$row->textColor;
        //echo $row->body;
            return [$this];
    }*/



 	}

    public function set_datos($data_cruda){
    	 $objeto =array();
        ///par aquitar campos no existentes
        foreach ($data_cruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Cita_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        return $objeto;
 	}//fin de capitalizar los datos segun el modelo y campos correctos de la base

 	


}
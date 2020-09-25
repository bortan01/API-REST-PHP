<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Cita_model extends CI_Model
{
public $id_cita;
public $id_usuario;
public $descripcion;
public $motivo;
public $color;
public $textColor;
public $start;
public $end;

public function get_citas(){


 	$query=$this->db->get('cita');
 	
 		return $query->result();
 	}

}
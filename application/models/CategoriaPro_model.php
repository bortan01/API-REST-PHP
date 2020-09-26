<?php
defined('BASEPATH') or exit('No direct script access allowed');
class CategoriaPro_model extends CI_Model
{

	public $id_categoria;
	public $nombre;

	public function get_categoria(){


 	$query=$this->db->get('categoria');
 	
 		return $query->result();
 	}    
}
<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Restore_model extends CI_Model
{
   function droptable(){
      $cek = $this->db->query("SHOW TABLES");
      if ($cek->num_rows()>0) {
         $query = $this->db->query('DROP TABLE ');
      } else {
         # code...
      }
      
   }
}
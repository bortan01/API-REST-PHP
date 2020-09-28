<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Utils_model extends CI_Model
{

    public function selectTabla(string $nombreTabla, array $condiciones = array(), bool $unico = false)
    {
        $this->load->database();
        $nombreModelo = ucwords($nombreTabla) . "_model";

        $this->load->model($nombreModelo);
        try {
            if (count($condiciones) > 0) {

                //colocamos las condiciones
                $this->db->where($condiciones);
            }
            //hacemos la consulta
            $query = $this->db->get($nombreTabla);

            if ($unico) {
                $result = $query->custom_row_object(0, $nombreModelo);
            } else {

                $result = $query->custom_result_object($nombreModelo);
            }

            return $result;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function verificar_campos($dataCruda, $nombreTabla)
    {
        ///par aquitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists($nombreTabla, $nombre_campo)) {
                $this->$nombre_campo = $valor_campo;
            }
        }
        if ($this->activo == NULL) {
            $this->activo = 1;
        }
        //este es un objeto tipo cliente model
        return $this;
    }
}
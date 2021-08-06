<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: https://admin.tesistours.com/');
class Blog extends CI_CONTROLLER
{
    public function index()
    {
        echo 'holla';
    }

    public function comentarios($id)
    {
        if (!is_numeric($id)) {
            $respuesta  = array('err' => true, 'mensaje' => 'no pueden se letras');
            echo json_encode($respuesta);
            return;
        }

        $comentarios = array(
            array('id' => 1, 'mensaje' => "Lorem dolore eiusmod reprehenderit et voluptate non officia qui do reprehenderit duis."),
            array('id' => 2, 'mensaje' => "Lorem dolore eiusmod  officia qui do reprehenderit duis."),
            array('id' => 3, 'mensaje' => "Lorem dolore eiusmod reprehenderit et voluptate "),
            array('id' => 4, 'mensaje' => " et voluptate non officia qui do reprehenderit duis."),
        );
        echo json_encode($comentarios[$id]);
    }
}
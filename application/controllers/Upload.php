<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Upload extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('upload_form', array('error' => ' '));
    }

    public function imagen_post()
    {
        $config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'gif|jpg|png';
        // $config['max_size']             = 100;
        // $config['max_width']            = 1024;
        // $config['max_height']           = 768;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('userfile')) {

            $respuesta = array(
                'err'     => TRUE,
                'mensaje' =>  $this->upload->display_errors(),
                'imagen'  => null
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $imagen = array('upload_data' => $this->upload->data());
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => "Imagen subida exitosamente",
                'imagen'  => $imagen
            );
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }


    public function aaa_post()
    {
        echo 'dfdf';
    }
}
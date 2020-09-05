<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class SitioTuristico extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Imagen_model');
    }

    public function index()
    {
        $this->load->view('upload_form', array('error' => ' '));
    }

    public function imagen_post()
    {

        print_r($this->Imagen_model->guardarImagen());
        print_r($this->Imagen_model->guardarGaleria());
    }
}
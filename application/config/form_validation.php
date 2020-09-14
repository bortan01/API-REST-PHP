<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


$config = array(
	//primer parametro el nombre del campo ejemplo (correo, nombre,zip)
	//el segundo es un nombre para identificar el campo (correo electronico, nombre,zip)
	//el tercero son las reglas
	'cliente_put' => array(
		array('field' => 'correo', 'label' => 'correo electronico', 'rules' => 'trim|required|valid_email'),
		array('field' => 'nombre', 'label' => 'nombre', 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'zip', 'label' => 'zip', 'rules' => 'trim|required|min_length[2]|max_length[5]')
	),


	'cliente_post' => array(
		array('field' => 'id', 'label' => 'cliente id', 'rules' => 'trim|required|Integer'),
		array('field' => 'nombre', 'label' => 'nombre', 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'zip', 'label' => 'zip', 'rules' => 'trim|required|min_length[2]|max_length[5]')
	),

	'rama_put' => array(
		array('field' => 'nombre_rama', 'label' => 'nombre', 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'numero_rama', 'label' => 'numero de rama', 'rules' => 'trim|required|Integer')
	),

	'pregunta_put' => array(
		array('field' => 'pregunta', 'label' => 'Pregunta', 'rules' => 'trim|required|min_length[2]|max_length[255]')
	),

	'autos_put' => array(
		array('field' => 'placa', 'label' => 'Placa', 'rules' => 'trim|required|min_length[2]|max_length[255]')
	),

	'insertarSitio' => array(
		array('field' => 'nombre'	           , 'label' => 'nombre'                 , 'rules' => 'trim|required'),
		array('field' => 'longitud'            , 'label' => 'longiitud'              , 'rules' => 'trim|required|min_length[3]|max_length[255]'),
		array('field' => 'latitud'             , 'label' => 'latitud'                , 'rules' => 'trim|required|min_length[3]|max_length[45]'),
		array('field' => 'ubicacion'           , 'label' => 'ubicacion'              , 'rules' => 'trim|required|min_length[3]|max_length[45]'),
		array('field' => 'descripcion'         , 'label' => 'descripcion'            , 'rules' => 'trim|required|min_length[3]|max_length[45]'),
		array('field' => 'informacion_contacto', 'label' => 'informacion de contacto', 'rules' => 'trim|required|min_length[3]|max_length[45]'),
		array('field' => 'tipo'                , 'label' => 'El tipo es necesario'   , 'rules' => 'trim|required|min_length[3]|max_length[45]'),
	),



);
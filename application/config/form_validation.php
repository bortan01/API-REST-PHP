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
	)


);
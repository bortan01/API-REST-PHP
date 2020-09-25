<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


$config = array(
	//primer parametro el nombre del campo ejemplo (correo, nombre,zip)
	//el segundo es un nombre para identificar el campo (correo electronico, nombre,zip)
	//el tercero son las reglas
	'cliente_put' => array(
		array('field' => 'correo', 'label' => 'correo electronico', 'rules' => 'trim|required|valid_email'),
		array('field' => 'nombre', 'label' => 'nombre'            , 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'zip'   , 'label' => 'zip'               , 'rules' => 'trim|required|min_length[2]|max_length[5]')
	),


	'cliente_post' => array(
		array('field' => 'id'    , 'label' => 'cliente id', 'rules' => 'trim|required|Integer'),
		array('field' => 'nombre', 'label' => 'nombre'    , 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'zip'   , 'label' => 'zip'       , 'rules' => 'trim|required|min_length[2]|max_length[5]')
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
	'marca_put' => array(
		array('field' => 'marca', 'label' => 'Marca', 'rules' => 'trim|required|min_length[2]|max_length[255]')
	),
	'modelo_put' => array(
		array('field' => 'modelo', 'label' => 'Modelo', 'rules' => 'trim|required|min_length[2]|max_length[255]')
	),
	'transmision_put' => array(
		array('field' => 'transmision', 'label' => 'Transmision', 'rules' => 'trim|required|min_length[2]|max_length[255]')
	),
	'mantenimiento_put' => array(
		array('field' => 'fecha', 'label' => 'Fecha', 'rules' => 'trim|required|min_length[2]|max_length[255]')
	),
	'renta_put' => array(
		array('field' => 'nombre', 'label' => 'Nombre', 'rules' => 'trim|required|min_length[2]|max_length[255]')
	),
	'lugar_put' => array(
		array('field' => 'nombre_lugar', 'label' => 'Lugar', 'rules' => 'trim|required|min_length[2]|max_length[255]')
	),
	'servicios_put' => array(
		array('field' => 'nombre_servicio', 'label' => 'Servicio', 'rules' => 'trim|required|min_length[2]|max_length[255]')
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
	
	'insertarTurPaquete' => array(
		array('field' => 'nombreTours'	    ,'label' => 'Nombre del Viaje'    , 'rules' => 'trim|required'),
		array('field' => 'fecha_salida'     ,'label' => 'Fecha de Salida'     , 'rules' => 'trim|required'),
		array('field' => 'lugar_salida'     ,'label' => 'Lugar de salida'     , 'rules' => 'trim|required|min_length[3]|max_length[45]'),
		array('field' => 'incluye'          ,'label' => 'Incluye'             , 'rules' => 'trim|required|min_length[3]|max_length[45]'),
		array('field' => 'no_incluye'       ,'label' => 'No Incluye'          , 'rules' => 'trim|required|min_length[3]|max_length[45]'),
		array('field' => 'requisitos'       ,'label' => 'Requisitos'          , 'rules' => 'trim|required|min_length[3]|max_length[45]'),
		array('field' => 'promociones'      ,'label' => 'Promociones'         , 'rules' => 'trim|required|min_length[3]|max_length[45]'),
		array('field' => 'descripcion'      ,'label' => 'Descripcion'         , 'rules' => 'trim|required|min_length[3]|max_length[45]'),
		array('field' => 'nombre_encargado' ,'label' => 'Nombre de Encargado' , 'rules' => 'trim|required|min_length[3]|max_length[45]'),
		array('field' => 'estado'           ,'label' => 'Estado'              , 'rules' => 'trim|required|min_length[3]|max_length[45]'),
		array('field' => 'tipo'             ,'label' => 'Tipo'                , 'rules' => 'trim|required|min_length[3]|max_length[45]'),
		array('field' => 'aprobado'         ,'label' => 'Aprobacion'          , 'rules' => 'trim|required|min_length[3]|max_length[45]'),
		array('field' => 'cupos_disponibles','label' => 'Cupos de Paquetes'   , 'rules' => 'trim|required|integer'),
		array('field' => 'precio'           ,'label' => 'Precio'              , 'rules' => 'trim|required|numeric'),
	
	),
	'insertarUsuario' => array(
		array('field' => 'fullname'  ,'label' => 'Nombre Copleto'     , 'rules' => 'trim|required|min_length[3]|max_length[45]'),
		array('field' => 'username'  ,'label' => 'Nombre de Usuario'  , 'rules' => 'trim|required|min_length[3]|max_length[45]'),
		array('field' => 'email'     ,'label' => 'Correo Electronico' , 'rules' => 'trim|required|min_length[3]|max_length[45]'),
		array('field' => 'password'	 ,'label' => 'Password'           , 'rules' => 'trim|required|min_length[3]|max_length[45]')	
	),
	'loginUsuario' => array(
		array('field' => 'username'  ,'label' => 'Correo Electronico' , 'rules' => 'trim|required|min_length[3]|max_length[45]'),
		array('field' => 'password'	 ,'label' => 'Password'           , 'rules' => 'trim|required|min_length[3]|max_length[45]')	
	),

	'citas_put' => array(
		array('field' => 'descripcion'  ,'label' => 'descripcion' , 'rules' => 'trim|required|min_length[4]|max_length[255]')
	),

);
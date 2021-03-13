<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


$config = array(
	//primer parametro el nombre del campo ejemplo (correo, nombre,zip)
	//el segundo es un nombre para identificar el campo (correo electronico, nombre,zip)
	//el tercero son las reglas
	

	'rama_put' => array(
		array('field' => 'categoria_rama', 'label' => 'nombre', 'rules' => 'trim|required|min_length[2]|max_length[255]')
	),

	'pregunta_put' => array(
		array('field' => 'pregunta', 'label' => 'Pregunta', 'rules' => 'trim|required|min_length[2]|max_length[255]')
	),

	'autos_put' => array(
		array('field' => 'placa'          , 'label' => 'Placa'              , 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'anio'           , 'label' => 'Año'                , 'rules' => 'trim|required|min_length[4]|max_length[255]'),
		array('field' => 'color'          , 'label' => 'Color'              , 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'puertas'        , 'label' => 'Puertas'            , 'rules' => 'trim|required|min_length[1]|max_length[255]'),
		array('field' => 'pasajeros'      , 'label' => 'Pasajeros'          , 'rules' => 'trim|required|min_length[1]|max_length[255]'),
		array('field' => 'precio_diario'  , 'label' => 'Precio Diario'      , 'rules' => 'trim|required|min_length[1]|max_length[255]'),
		array('field' => 'descripcion'    , 'label' => 'Descripción'        , 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'detalles'       , 'label' => 'Detalles'           , 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'kilometraje'    , 'label' => 'Kilometraje'        , 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'tipoCombustible', 'label' => 'Tipo de Combustible', 'rules' => 'trim|required|min_length[2]|max_length[255]')		
	),
	'marca_put' => array(
		array('field' => 'marca', 'label' => 'Marca', 'rules' => 'trim|required|min_length[2]|max_length[255]')
	),
	'modelo_put' => array(
		array('field' => 'modelo'  , 'label' => 'Modelo', 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'id_marca', 'label' => 'Marca' , 'rules' => 'trim|required|min_length[1]|max_length[255]')
	),
	'transmision_put' => array(
		array('field' => 'transmision', 'label' => 'Transmision', 'rules' => 'trim|required|min_length[2]|max_length[255]')
	),
	'categorias_put' => array(
		array('field' => 'nombre_categoria'     , 'label' => 'Nombre de Categoria'     , 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'descripcion_categoria', 'label' => 'Descripcion de Categoria', 'rules' => 'trim|required|min_length[2]|max_length[255]')
	),
	'mantenimiento_put' => array(
		array('field' => 'fecha_mantenimiento', 'label' => 'Fecha', 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'lugar_mantenimiento', 'label' => 'Lugar', 'rules' => 'trim|required|min_length[2]|max_length[255]')
	),
	'renta_put' => array(
		array('field' => 'rentaCar'   , 'label' => 'Nombre de Renta Cars'               , 'rules' => 'trim|required|min_length[6]|max_length[255]'),
		array('field' => 'lugar_renta'      , 'label' => 'Dirección donde esta ubicado'       , 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'descripcion_renta', 'label' => 'Descripción'                        , 'rules' => 'trim|required|min_length[10]|max_length[255]'),
		array('field' => 'telefono_renta'   , 'label' => 'Telefono'                           , 'rules' => 'trim|required|min_length[8]|max_length[255]'),
		array('field' => 'contrasena_renta' , 'label' => 'Contraseña'                         , 'rules' => 'trim|required|min_length[6]|max_length[255]'),
		array('field' => 'usuario_renta'    , 'label' => 'Usuario del Encargado de Renta cars', 'rules' => 'trim|required|min_length[6]|max_length[255]'),
	
	),
	'usuarioRenta_put' => array(
		array('field' => 'usuario'   , 'label' => 'Usuario del Encargado de Renta cars', 'rules' => 'trim|required|min_length[6]|max_length[255]'),
		array('field' => 'contrasena', 'label' => 'Contraseña'                         , 'rules' => 'trim|required|min_length[6]|max_length[255]')
	
	),
	'lugar_put' => array(
		array('field' => 'nombre_lugar', 'label' => 'Lugar', 'rules' => 'trim|required|min_length[2]|max_length[255]')
	),
	'servicios_put' => array(
		array('field' => 'nombre_servicio', 'label' => 'Servicio'   , 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'descripcion'    , 'label' => 'Descripción', 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'precio'         , 'label' => 'Precio'     , 'rules' => 'trim|required|min_length[1]|max_length[255]')
	
	),
	'cotizar_put' => array(
		array('field' => 'modelo'                , 'label' => 'Marca', 'rules' => 'trim|required|min_length[1]|max_length[255]'),
		array('field' => 'anio'                , 'label' => 'Vehiculo', 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'caracteristicas'     , 'label' => 'Vehiculo', 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'direccion_recogida'  , 'label' => 'Vehiculo', 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'fechaRecogida'   , 'label' => 'Vehiculo', 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'direccion_devolucion', 'label' => 'Vehiculo', 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'fechaDevolucion' , 'label' => 'Vehiculo', 'rules' => 'trim|required|min_length[2]|max_length[255]')
	),
	'aerolinea_put' => array(
		array('field' => 'nombre_aerolinea', 'label' => 'Aerolinea', 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'idalianza'       , 'label' => 'Alianza'  , 'rules' => 'trim|required|min_length[1]|max_length[255]')
	),
	'alianzas_put' => array(
		array('field' => 'nombre_alianza', 'label' => 'Alianza', 'rules' => 'trim|required|min_length[2]|max_length[255]')
	),
	'clases_put' => array(
		array('field' => 'nombre_clase', 'label' => 'Clase'      , 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'descripcion' , 'label' => 'Descripción', 'rules' => 'trim|required|min_length[2]|max_length[2000]')
	),
	'direccion_put' => array(
		array('field' => 'idlugar'            , 'label' => 'Recogida'                  , 'rules' => 'trim|required|min_length[1]|max_length[255]'),
		array('field' => 'direccionRecogida'  , 'label' => 'Dirección de Recogida'     , 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'fechaHoraRecogida'  , 'label' => 'Fecha y Hora de Recogida'  , 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'direccionDevolucion', 'label' => 'Dirección de Devolución'   , 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'fechaHoraDevolucion', 'label' => 'Fecha y Hora de Devolución', 'rules' => 'trim|required|min_length[2]|max_length[255]')
	
	),
	'viajes_put' => array(
		array('field' => 'nombre_tipoviaje', 'label' => 'Viaje'      , 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'descripcion'     , 'label' => 'Descripción', 'rules' => 'trim|required|min_length[2]|max_length[2000]')
	
	),
	
	'datos_put' => array(
		array('field' => 'ciudad_partida'  , 'label' => 'Ciudad de Partida'      , 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'fechaHoraPartida', 'label' => 'Fecha y Hora de Partida', 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'ciudad_llegada'  , 'label' => 'Ciudad de Llegada'      , 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'fechaHoraLlegada', 'label' => 'Fecha y Hora de Llegada', 'rules' => 'trim|required|min_length[2]|max_length[255]'),
		array('field' => 'maletas'         , 'label' => 'Cantidad de Maletas'    , 'rules' => 'trim|required|min_length[2]|max_length[255]')
	),
	'informacion_put' => array(
		array('field' => 'condiciones', 'label' => 'Condiciones', 'rules' => 'trim|required|min_length[2]|max_length[2000]')
	),
	'opciones_put' => array(
		array('field' => 'idaerolinea' , 'label' => 'Aerolinea'    , 'rules' => 'trim|required|min_length[1]|max_length[255]'),
		array('field' => 'idclase'     , 'label' => 'Tipo de Clase', 'rules' => 'trim|required|min_length[1]|max_length[255]'),
		array('field' => 'idtipo_viaje', 'label' => 'Tipo de Viaje', 'rules' => 'trim|required|min_length[1]|max_length[255]')
	
	),
	'cotizacionv_put' => array(
		array('field' => 'opc_avanzadas', 'label' => 'Opciones Avanzadas', 'rules' => 'trim|required|min_length[1]|max_length[255]'),
		array('field' => 'idaerolinea'  , 'label' => 'Aerolinea'         , 'rules' => 'trim|required|min_length[1]|max_length[255]')
	),

	'insertarSitio' => array(
		array('field' => 'nombre_sitio'        , 'label' => 'nombre'                 , 'rules' => 'trim|required'),
		array('field' => 'longitud'            , 'label' => 'longiitud'              , 'rules' => 'trim|required|min_length[3]'),
		array('field' => 'latitud'             , 'label' => 'latitud'                , 'rules' => 'trim|required|min_length[3]'),
		array('field' => 'descripcion_sitio'   , 'label' => 'descripcion'            , 'rules' => 'trim|required|min_length[3]'),
		array('field' => 'informacion_contacto', 'label' => 'informacion de contacto', 'rules' => 'trim|required'),
		array('field' => 'id_tipo_sitio'       , 'label' => 'El tipo es necesario'   , 'rules' => 'trim|required'),
	),
	
	'insertarTurPaquete' => array(
		array('field' => 'nombreTours'      ,'label' => 'Nombre del Viaje'    , 'rules' => 'trim|required'),
		array('field' => 'start'            ,'label' => 'Fecha de Salida'     , 'rules' => 'trim|required'),
		array('field' => 'descripcion_tur'  ,'label' => 'Descripcion'         , 'rules' => 'trim|required|min_length[3]'),
		array('field' => 'estado'           ,'label' => 'Estado'              , 'rules' => 'trim|required'),
		array('field' => 'tipo'             ,'label' => 'Tipo'                , 'rules' => 'trim|required'),
		array('field' => 'aprobado'         ,'label' => 'Aprobacion'          , 'rules' => 'trim|required'),
		array('field' => 'cupos_disponibles','label' => 'Cupos de Paquetes'   , 'rules' => 'trim|required|integer'),
		array('field' => 'precio'           ,'label' => 'Precio'              , 'rules' => 'trim|required|numeric'),
		// array('field' => 'lugar_salida'     ,'label' => 'Lugar de salida'     , 'rules' => 'trim|required'),
		// array('field' => 'no_incluye'       ,'label' => 'No Incluye'          , 'rules' => 'trim|required'),
		// array('field' => 'requisitos'       ,'label' => 'Requisitos'          , 'rules' => 'trim|required'),
		// array('field' => 'promociones'      ,'label' => 'Promociones'         , 'rules' => 'trim|required'),
		// array('field' => 'incluye'          ,'label' => 'Incluye'             , 'rules' => 'trim|required'),
		// array('field' => 'nombre_encargado' ,'label' => 'Nombre de Encargado' , 'rules' => 'trim|required|min_length[3]|max_length[45]'),
	
	),
	'editarTurPaquete' => array(
		array('field' => 'id_tours'         ,'label' => 'ID TUR'              , 'rules' => 'trim|required'),
		array('field' => 'nombreTours'      ,'label' => 'Nombre del Viaje'    , 'rules' => 'trim|required'),
		array('field' => 'start'            ,'label' => 'Fecha de Salida'     , 'rules' => 'trim|required'),
		array('field' => 'descripcion_tur'  ,'label' => 'Descripcion'         , 'rules' => 'trim|required|min_length[3]'),
		array('field' => 'tipo'             ,'label' => 'Tipo'                , 'rules' => 'trim|required'),
		array('field' => 'cupos_disponibles','label' => 'Cupos de Paquetes'   , 'rules' => 'trim|required|integer'),
		array('field' => 'precio'           ,'label' => 'Precio'              , 'rules' => 'trim|required|numeric'),
		array('field' => 'lugar_salida'     ,'label' => 'Lugar de salida'     , 'rules' => 'trim|required'),
		array('field' => 'no_incluye'       ,'label' => 'No Incluye'          , 'rules' => 'trim|required'),
		array('field' => 'requisitos'       ,'label' => 'Requisitos'          , 'rules' => 'trim|required'),
		array('field' => 'promociones'      ,'label' => 'Promociones'         , 'rules' => 'trim|required'),
		array('field' => 'incluye'          ,'label' => 'Incluye'             , 'rules' => 'trim|required'),
		// array('field' => 'nombre_encargado' ,'label' => 'Nombre de Encargado' , 'rules' => 'trim|required|min_length[3]|max_length[45]'),
	
	),
	
	'loginUsuario' => array(
		array('field' => 'username'  ,'label' => 'Correo Electronico' , 'rules' => 'trim|required|min_length[3]|max_length[45]'),
		array('field' => 'password'	 ,'label' => 'Password'           , 'rules' => 'trim|required|min_length[3]|max_length[45]')	
	),
	'insertarUsuario' => array(
		array('field' => 'nombre'    ,'label' => 'Nombre Copleto'     , 'rules' => 'trim|required|min_length[3]|max_length[50]'),
		array('field' => 'correo'    ,'label' => 'Correo Electronico' , 'rules' => 'trim|required'),
		array('field' => 'password'  ,'label' => 'Password'           , 'rules' => 'trim|required|min_length[8]|max_length[45]'),	
		array('field' => 'nivel'     ,'label' => 'Nivel de Usuario'   , 'rules' => 'trim|required'),
		// array('field' => 'celular'   ,'label' => 'Celular'            , 'rules' => 'trim|min_length[8]|max_length[45]'),
		
	),
	'ActualizarUsuario' => array(
		array('field' => 'id_cliente'  ,'label' => 'id usuario' , 'rules' => 'trim|required'),
		array('field' => 'correo'      ,'label' => 'Correo Electronico'       , 'rules' => 'trim|required'),
		array('field' => 'password'    ,'label' => 'Password'                 , 'rules' => 'trim|min_length[8]'),		
	),

	'citas_put' => array(
		array('field' => 'fecha'       ,'label' => 'fecha'                    , 'rules' => 'trim|required|min_length[4]|max_length[255]'),
		array('field' => 'id_cliente'  ,'label' => 'Seleccione el cliente'    , 'rules' => 'trim|required'),
		array('field' => 'asistencia'  ,'label' => 'Seleccione la asistencia' , 'rules' => 'trim|required')

	),

	'estandar' => array(
		array('field' => 'costo'             ,'label' => 'costo'               , 'rules' => 'trim|required'),
		array('field' => 'punto_referencia'  ,'label' => 'punto de referencia' , 'rules' => 'trim|required|min_length[4]|max_length[255]')
	),

	'empresa_put' => array(
		array('field' => 'nombre_empresa'    ,'label' => 'nombre de la empresa' , 'rules' => 'trim|required|min_length[4]|max_length[255]'),
		array('field' => 'nombre_encargado'  ,'label' => 'nombre del encargado' , 'rules' => 'trim|required|min_length[4]|max_length[255]'),
		array('field' => 'direccion'         ,'label' => 'la dirección'         , 'rules' => 'trim|required|min_length[4]|max_length[255]'),
		array('field' => 'telefono'          ,'label' => 'el teléfono'          , 'rules' => 'trim|required|min_length[8]|max_length[9]')
	),

	'encomienda_put' => array(
		array('field' => 'direccion'      ,'label' => 'direccion'       , 'rules' => 'trim|required|min_length[4]|max_length[255]')
	),
	'detalleEnvio_put' => array(
		array('field' => 'descripcion'  ,'label' => 'descripcion' , 'rules' => 'trim|required|min_length[4]|max_length[255]')
	),
	'cate_put' => array(
		array('field' => 'nombre'  ,'label' => 'Nombre categoria' , 'rules' => 'trim|required|min_length[4]|max_length[255]')
	),
	'formulario_put' => array(
		array('field' => 'respuesta'  ,'label' => 'respuesta' , 'rules' => 'trim|min_length[1]|max_length[255]')
	),
	'producto_put' => array(
		array('field' => 'nombre_producto'  ,'label' => 'Nombre del producto' , 'rules' => 'trim|required|min_length[4]|max_length[255]')
	),
	'detalleEncomienda_put' => array(
		array('field' => 'cantidad'  ,'label' => 'catidad' , 'rules' => 'required')
	),
	'caja_put' => array(
		array('field' => 'capacidad'  ,'label' => 'capacidad' , 'rules' => 'required')
	),
	'tarifa_put' => array(
		array('field' => 'libras'  ,'label' => 'libras' , 'rules' => 'required')
	),

	'crearEnlace' => array(
		array('field' => 'monto'           ,'label' => 'Monto'                , 'rules' => 'trim|required|numeric'),
		array('field' => 'nombreProducto'  ,'label' => 'Nombre Producto'      , 'rules' => 'trim|required|min_length[5]'),
		array('field' => 'descripcion'     ,'label' => 'Descripción Producto' , 'rules' => 'trim|required|min_length[10]'),
		//array('field' => 'foto'            ,'label' => 'Foto'                 , 'rules' => 'trim|required') 
	),
	'insertarDetalleVehiculo' => array(
	array('field' => 'id_cliente'           ,'label' => 'Id Usuario'   , 'rules' => 'trim|required|integer'),
	array('field' => 'direccionRecogida'       ,'label' => 'Dirección de Recogida'                , 'rules' => 'trim|required'),
	array('field' => 'direccionDevolucion'       ,'label' => 'Dirección de Devolución'                , 'rules' => 'trim|required'),
	array('field' => 'fechaHora'       ,'label' => 'Fecha y Hora'                , 'rules' => 'trim|required'),
			
	),
	'insertarServicio' => array(
		array('field' => 'nombre_servicio'       ,'label' => 'Nombre'                , 'rules' => 'trim|required'),
		array('field' => 'descripcion_servicio'  ,'label' => 'Descripcion Servicio'  , 'rules' => 'trim|required|min_length[10]'),
		array('field' => 'id_tipo_servicio'      ,'label' => 'Tipo de Servicio'      , 'rules' => 'trim|required'),
		array('field' => 'costos_defecto'        ,'label' => 'Costo por defecto'     , 'rules' => 'trim|required|numeric'),
		array('field' => 'id_contacto'           ,'label' => 'Informacion Contacto'  , 'rules' => 'trim|required')
	),
	'insertarServicioDetalle' => array(
		array('field' => 'id_tours'      ,'label' => 'Id Tours'     , 'rules' => 'trim|required|integer'),
		array('field' => 'id_servicios'  ,'label' => 'Id Servicio'  , 'rules' => 'trim|required|integer'),
		array('field' => 'costo'         ,'label' => 'Costo'        , 'rules'  =>'trim|required|numeric'),

		
	),
	'insertarContacto' => array(
		array('field' => 'nombre_contacto' ,'label' => 'Nombre', 'rules' => 'trim|required|min_length[3]')
	),
	'insertarTipoServicio' => array(
		array('field' => 'tipo_servicio' ,'label' => 'Tipo de Servicio' , 'rules' => 'trim|required')
	),
	'insertarTipoSitio' => array(
		array('field' => 'tipo_sitio' ,'label' => 'Tipo de Sitio Turistico' , 'rules' => 'trim|required')
	),
	'ActualizarContacto' => array(
		array('field' => 'id_contacto' ,'label' => 'id contacto' , 'rules' => 'trim|required'),
	),
	'EliminarContacto' => array(
		array('field' => 'id_contacto' ,'label' => 'id contacto'        , 'rules' => 'trim|required'),
	),
	'insertarDetalleTur' => array(
		array('field' => 'id_tours'               ,'label' => 'id tur'                , 'rules' => 'trim|required|integer'),
		array('field' => 'id_cliente'             ,'label' => 'id cliente'            , 'rules' => 'trim|required|integer'),
		array('field' => 'asientos_seleccionados' ,'label' => 'asientos seleccionados', 'rules' => 'trim|required'),
		array('field' => 'total'                  ,'label' => 'total'                 , 'rules' => 'trim|required|numeric'),
		array('field' => 'label_asiento'          ,'label' => 'label de asientos'     , 'rules' => 'trim|required'),
		array('field' => 'nombre_producto'        ,'label' => 'nombre del producto'   , 'rules' => 'trim|required'),
		array('field' => 'descripcionProducto'    ,'label' => 'descripcion'           , 'rules' => 'trim|required'),
		array('field' => 'cantidad_asientos'       ,'label' => 'cantidad de asientos'  , 'rules' => 'trim|required|integer'),
	),
	
);
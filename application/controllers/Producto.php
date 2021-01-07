<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Producto extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Producto_model');

	}

	public function altaProducto_delete(){

	 $data=$this->delete();
     
	 $data=$this->delete();
	 $verificar=$this->Producto_model->set_datos($data);
     $respuesta=$this->Producto_model->altaProducto($verificar);

      if ($respuesta['err']) {

			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST); 

		}else{
			 $this->response($respuesta);

	 }
	 }


	public function deleteProducto_delete(){

	 $data=$this->delete();
     
	 $data=$this->delete();
	 $verificar=$this->Producto_model->set_datos($data);
     $respuesta=$this->Producto_model->eliminarProducto($verificar);

      if ($respuesta['err']) {

			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST); 

		}else{
			 $this->response($respuesta);

	 }
	 }

	public function updateProducto_post(){

		$data=$this->post();

		$prod=$this->Producto_model->set_datos($data);
        $respuesta=$this->Producto_model->modificar_producto($data); 

		if ($respuesta['err']) {

		$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST); 	

		}else{
		$this->response($respuesta); 	
		}

	}//fin de metodo

public function productosTabla_get(){

	//este get es para ver los productos en la tabla 
	//para editar o dar de baja o alta

	$product=$this->Producto_model->get_productoTabla();

	if (isset($product)) {
		//quitar campos que no quiero
		//unset($cliente->telefono1);
		//sunset($cliente->telefono2);
		$respuesta=array(
			'err'=>FALSE,
			'mensaje'=>'Registro Cargado correctamente',
			'product'=>$product

		);
		$this->response($respuesta);
	}else{
		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'Error al cargar los datos.'
		);
		$this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);

	}
}//fin metodo

public function productos_get(){

	$product=$this->Producto_model->get_producto();

	if (isset($product)) {
		//quitar campos que no quiero
		//unset($cliente->telefono1);
		//sunset($cliente->telefono2);
		$respuesta=array(
			'err'=>FALSE,
			'mensaje'=>'Registro Cargado correctamente',
			'product'=>$product

		);
		$this->response($respuesta);
	}else{
		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'Error al cargar los datos.'
		);
		$this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);

	}
}//fin metodo

	public function producto_post(){

	    $data=$this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);

		if ( $this->form_validation->run('producto_put') ) {
			//todo bien
			//$this->response('Todo bien');
		$prod=$this->Producto_model->set_datos($data);
        $respuesta=$this->Producto_model->insertarProducto($data); 

		if ($respuesta['err']) {

		$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST); 	

		}else{
		$this->response($respuesta); 	
		}

		}else{
			//algo mal

			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'Hay errores en el envio de la informacion',
				'errores'=>$this->form_validation->get_errores_arreglo()
			);
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST); 
		}
}//fin metodo

}
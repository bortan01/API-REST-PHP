<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Clientes extends REST_Controller
{
    public function __construct()
    {
        //llamado del constructor del padre 
        parent::__construct();
        $this->load->database();
        $this->load->model('Cliente_model');
    }

    ////
    public function index_get()
    {
        $data = array(
            'nombre' => 'boris',
            'contacto' => 'abi',
            'direcion' => 'san verapaz'
        );
        echo json_encode($data);
    }
    public function insert()
    {
        $data = array(
            'nombre' => 'boris',
            'apellido' => 'miranda'

        );

        $this->db->insert('test', $data);
        // Produces: INSERT INTO mytable (title, name, date) VALUES ('My title', 'My name', 'My date')


        $respuesta = array(
            'err' => FALSE,
            'id_ insertado' => $this->db->insert_id()
        );
        echo json_encode($respuesta);
    }
    public function insertSimultaneo()
    {
        $data = array(
            array(
                'nombre' => 'pedro',
                'apellido' => 'solis'
            ),
            array(
                'nombre' => 'vicente',
                'apellido' => 'fernandez'
            )
        );

        $this->db->insert_batch('test', $data);
        // Produces: INSERT INTO mytable (title, name, date) VALUES ('My title', 'My name', 'My date'),  ('Another title', 'Another name', 'Another date')


        $respuesta = array(
            'err' => FALSE,
            'registros_guardados' => $this->db->affected_rows()
        );
        echo json_encode($respuesta);
    }
    public function actualizar($id)
    {
        $data = array(
            'nombre' => 'goku',
            'apellido' => 'son '
        );
        $this->db->where('id', $id);
        $this->db->update('test', $data);
        echo 'todo ok';
        // Executes: REPLACE INTO mytable (title, name, date) VALUES ('My title', 'My name', 'My date')
    }
    public function eliminar(int $id)
    {
        $this->db->where('id', $id);
        $this->db->delete('test');
        echo 'todo ok';
    }
    ///
    public function cliente_get()
    {
        //este parametro viene por url 
        $cliente_id = $this->uri->segment(3);

        //validamos el parametro si no existe enviamos un HTTP_BAD_REQUEST
        if (!isset($cliente_id)) {
            $respuesta = array(
                'error' => TRUE,
                'mensaje' => 'Es necesario el ID del cliente',
                'cliente' => null
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            //este return es para que no continue
            return;
        }
        //solicitamos ese cliente al modelo 
        $cliente = $this->Cliente_model->obtenerCliente($cliente_id);

        ///validamos si existe el cliente, si no existe enviamos un HTTP_NOT_FOUND
        if (!isset($cliente)) {
            $respuesta = array(
                'error' => TRUE,
                'mensaje' => 'EL registro con el id ' . $cliente_id . ' no existe',
                'cliente' => null
            );
            $this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
        } else {
            ///si llegamos aqui todo esta bien y podemos retornar un HTTP_OK


            ///si deseamos eliminar un campo del modelo podemos hacer lo siguiente, esto puede ser util
            //si deseamos elimar una password
            unset($cliente->telefono1);

            $respuesta = array(
                'error' => TRUE,
                'mensaje' => 'todo ok',
                'cliente' => $cliente
            );
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }

    public function cliente_put()
    {
        $data = $this->put();
        $this->load->library('form_validation');
        $this->form_validation->set_data($data);

        //corremos las reglas de validacion
        if ($this->form_validation->run('cliente_put')) {
            //VERIFICAMOS QUE TODOS LOS PARAMETROS ESTEN BIEN
            $cliente = $this->Cliente_model->verificar_campos($data);
            $respuesta =  $cliente->guardar();

            if ($respuesta['err']) {
                $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $this->response($respuesta, REST_Controller::HTTP_OK);
            }
        } else {
            //algo mal 
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'har errores en el envio de informacion',
                'errores' => $this->form_validation->get_errores_arreglo()
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        }
    }



    public function cliente_post()
    {



        $data = $this->post();
        $cliente_id = $this->uri->segment(3);


        //agregamos el id a la data 
        $data['id'] = $cliente_id;

        $this->load->library('form_validation');
        $this->form_validation->set_data($data);

        //corremos las reglas de validacion
        if ($this->form_validation->run('cliente_post')) {
            //VERIFICAMOS QUE TODOS LOS PARAMETROS ESTEN BIEN
            $cliente = $this->Cliente_model->verificar_campos($data);
            $respuesta =  $cliente->actualizar();

            if ($respuesta['err']) {
                $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $this->response($respuesta, REST_Controller::HTTP_OK);
            }
        } else {
            //algo mal 
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'har errores en el envio de informacion',
                'errores' => $this->form_validation->get_errores_arreglo()
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Pruebasdb extends CI_CONTROLLER
{

    public function __construct()
    {
        //llamado del constructor del padre 
        parent::__construct();
        $this->load->database();
    }

    public function clientes_beta()
    {
        // $this->load->database();
        $query = $this->db->query('SELECT id,nombre,correo FROM clientes LIMIT 10');
        // foreach ($query->result()  as $row) {
        //     echo $row->id;
        //     echo $row->nombre;
        //     echo $row->correo;
        // }
        // echo 'total de registros ' . $query->num_rows();
        $respuesta = array(
            'err' => FALSE,
            'mensaje' => 'Registro cargados correctamente',
            'total registros' =>  $query->num_rows(),
            'clientes' =>  $query->result()

        );

        echo json_encode($respuesta);
    }


    public function cliente($id)
    {
        //$this->load->database();
        $query = $this->db->query('SELECT * FROM clientes WHERE id = 3' . $id);
        //solo devuelve una fila  el query row
        $fila  = $query->row();

        if (isset($fila)) {
            //si tenemos datos
            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro cargados correctamente',
                'total registros' => 1,
                'clientes' =>  $fila

            );
            echo json_encode($respuesta);
        } else {
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'NO HAY DATOS CON ESE ID ' . $id,
                'total registros' => 0,
                'clientes' =>  NULL

            );
            echo json_encode($respuesta);
        }
    }

    public function tabla()
    {
        //este es un select * from y con un limit de 10 registras a partir del 20
        //  $query    = $this->db->get('clientes', 10, 20);
        //  echo json_encode($query->result());


        //====================================
        //para hacer una seleccion con where y campos
        //====================================
        $this->db->select('id, nombre, correo');
        $this->db->from('clientes');
        $this->db->where('id', 10);
        $query = $this->db->get();
        echo json_encode($query->row());
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
}
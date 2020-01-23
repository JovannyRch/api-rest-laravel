<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Clientes;
class ClientesController extends Controller
{
    public function index(){
        $json = array(
            'detail' => 'Not found'
        );
        echo json_encode($json);
    }

    //** Crear un registro */
    //** Crear en la tabla las columnas updated_at y created_at del tipo timestamp con valor predeteminado null */
    public function store(Request $request)
    {
        //Recoger datos
        $nombre = $request->input('nombre');
        $apellido = $request->input('apellido');
        $email = $request->input('email');
        $datos = array(
            'nombre' => $nombre,
            'email' => $email,
            'apellido' => $apellido,
        );

        $validator = Validator::make($datos,[
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|string|max:255|email|unique:clientes'
        ]);
        if($validator->fails()){
            $respuesta = array('detail' => 'Registro no válido','status'=>404);
            echo json_encode($respuesta, true);
        }
        else{
            $id_cliente = Hash::make($nombre.$apellido.$email);
            $id_cliente = str_replace('$','-',$id_cliente);
            $llave_secreta = Hash::make($apellido.$email.$nombre,[
                'rounds' => 12
                ]);
            $llave_secreta = str_replace('$','-',$llave_secreta);
            $cliente = new Clientes();
            $cliente->nombre = $nombre;
            $cliente->email = $email;
            $cliente->apellido = $apellido;
            $cliente->id_cliente = $id_cliente;
            $cliente->llave_secreta = $llave_secreta;
            $cliente->save();
            $respuesta = array(
                'status'=> 200,
                'detail'=> 'Registro exitoso',
                'credenciales' => array(
                    'id_cliente'=>$id_cliente,
                    'llave_secreta'=>$llave_secreta
                )
            );

            echo json_encode($respuesta);
        }
    }
}

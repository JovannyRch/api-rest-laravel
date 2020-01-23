<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cursos;
use App\Clientes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CursosController extends Controller
{
    //Mostrar todos los registros de la tabla de cursos
    public function Index(Request $request)
    {
        /* $cursos = Cursos::all(); */
        $cursos = DB::table('cursos')->paginate(15);
        $respuesta = array(
            'status' => 200,
            'total_registros' => count($cursos),
            'data' => $cursos 
        );
        echo json_encode($respuesta,true);
    }

    //GUARDAR UN CURSO NUEVO
    public function store(Request $request){
        $datos = array(
            'titulo' => $request->input('titulo'),
            'descripcion' => $request->input('descripcion'),
            'instructor' => $request->input('instructor'),
            'imagen' => $request->input('imagen'),
            'precio' => $request->input('precio')
        );
        //Validación de datos
        if(!empty($datos)){
            $curso = new Cursos();
            $curso->titulo = $datos['titulo'];
            $curso->descripcion = $datos['descripcion'];
            $curso->imagen = $datos['imagen'];
            $curso->instructor = $datos['instructor'];
            $curso->precio = $datos['precio'];
            $curso->save();
            $json = array(
                'status'=> 200,
                'detail'=>'Su curso ha sido guardado'
            );
            echo json_encode($json,true);

        }else{
            $json = array(
                'status'=> 404,
                'detail'=>'Registro con errores'
            );
            echo json_encode($json,true);
        }
    }

    public function update($id,Request $request)  {
        $traer_registro = Cursos::where('id',$id)->get()[0];
       
        $datos = array(
            'titulo' => $request->input('titulo'),
            'descripcion' => $request->input('descripcion'),
            'instructor' => $request->input('instructor'),
            'imagen' => $request->input('imagen'),
            'precio' => $request->input('precio')
        );
        $respuesta = Cursos::where('id',$id)->update($datos);
        $res = array(
            'status'=> 200,
            'detail'=> 'Todo correcto'
        );
        echo json_encode($res,true);
    }

    public function show($id, Request $request){
        $peticion = Cursos::where('id',$id)->get();

        if(sizeof($peticion) > 0){
            echo json_encode($peticion[0],true);
        }
        else{
            $respuesta = array(
                'status'=> 404,
                'detail'=> 'No se ha encontrado el id'
            );
            echo json_encode($respuesta, true);
        }
    }

    public function destroy($id){
        $curso = Cursos::where('id',$id)->delete();

        echo json_encode(
            array(
                'status'=> 200,
                'detail'=> 'Se ha eliminado el curso'
            ), true);
        
    }

    public function saluda(){
        echo json_encode(array('status'=> 200, 'detail' => 'Hola a todos'));
    }
}

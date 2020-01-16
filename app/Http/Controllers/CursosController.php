<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cursos;
class CursosController extends Controller
{
    //Mostrar todos los registros de la tabla de cursos
    public function Index()
    {
        $cursos = Cursos::all();
        $respuesta = array(
            'status' => 200,
            'total_registros' => count($cursos),
            'data' => $cursos 
        );
        echo json_encode($respuesta,true);
    }
}

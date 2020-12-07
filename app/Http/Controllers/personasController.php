<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Http;
use DataTables;
use App\Models\personas;

class personasController extends Controller
{

    public function mostrarVista(){
        $datoPersona = personas::all();
        return view('vista', compact('datoPersona'));
    }

    public function mostrarDatos($rut){
        $respuesta = Http::get('https://siichile.herokuapp.com/consulta', ['rut'=>$rut]);
        $data  = $respuesta->json();
        return $data;
    }

    public function enviarDatos(Request $request){
        $datoPersona = personas::where('rut',$request->resRut)->first();
        if($datoPersona == NULL){
            $personas = new personas();
            $personas->rut = $request->resRut;
            $personas->razon_social = $request->resRazonSocial;
            $personas->actividades = [$request->resActividades,$request->resActividadesCodigo];
            $personas->save();
            return true;
        }else{
            return false;
        }
    }

    public function editarDatos(Request $request){
        $datoPersona = personas::where('rut',$request->inputRutEdit)->first();
        $datoPersona->razon_social = $request->inputRSEdit;
        $datoPersona->actividades = [$request->inputActEdit];
        $datoPersona->save();
        return $datoPersona;
    }

    public function elminarFila(Request $request){
        $datoPersona = personas::where('rut',$request->rut)->first();
        $datoPersona->delete();
        return response()->json(['mensaje' => 'Borrado con éxito']);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use DB;
class ClienteController extends Controller
{
    public function create(Request $request)
    {
        try {
            return DB::transaction(function()use($request){
                return Cliente::registar($request->all());
            },5);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function updated($id, Request $request)
    {
        try {
            return DB::transaction(function()use($request,$id){
                return Cliente::actualizar($request->all(),$id);
            },5);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function listar()
    {
        try {
            return Cliente::listar();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function eliminar($id)
    {
        try {
            return Cliente::eliminar($id);
        } catch (Exception $e) {
            return $e;
        }
    }
}
